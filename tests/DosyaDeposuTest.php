<?php

namespace RastgeleProfil\Tests;

use PHPUnit\Framework\TestCase;
use RastgeleProfil\HizSiniri\DosyaDeposu;
use RastgeleProfil\HizSiniri\HizSiniri;

final class DosyaDeposuTest extends TestCase
{
    /** @var string */
    private $dizin;

    protected function setUp(): void
    {
        $this->dizin = sys_get_temp_dir() . '/rp-test-' . uniqid();
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob($this->dizin . '/*') ?: []);
        @rmdir($this->dizin);
    }

    public function testDurumKalicidir(): void
    {
        $topla = function (?array $d) {
            $n = ($d['n'] ?? 0) + 1;
            return [['n' => $n], $n];
        };
        $this->assertSame(1, (new DosyaDeposu($this->dizin))->guncelle('1.1.1.1', $topla));
        $this->assertSame(2, (new DosyaDeposu($this->dizin))->guncelle('1.1.1.1', $topla));
        $this->assertSame(1, (new DosyaDeposu($this->dizin))->guncelle('2.2.2.2', $topla));
    }

    public function testHizSiniriIleCalisir(): void
    {
        $h = new HizSiniri(['saniyede' => 1, 'patlama' => 2], new DosyaDeposu($this->dizin), function () {
            return 1800000000.0;
        });
        $this->assertTrue($h->kontrol('1.1.1.1')->izin);
        $this->assertTrue($h->kontrol('1.1.1.1')->izin);
        $this->assertFalse($h->kontrol('1.1.1.1')->izin);
    }

    public function testTemizleEskiDosyalariSiler(): void
    {
        $d = new DosyaDeposu($this->dizin);
        $d->guncelle('eski', function () {
            return [[], null];
        });
        $d->guncelle('yeni', function () {
            return [[], null];
        });
        touch($this->dizin . '/' . sha1('eski'), time() - 3 * 86400);
        $d->temizle();
        $this->assertFileDoesNotExist($this->dizin . '/' . sha1('eski'));
        $this->assertFileExists($this->dizin . '/' . sha1('yeni'));
    }
}
