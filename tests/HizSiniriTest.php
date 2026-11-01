<?php

namespace RastgeleProfil\Tests;

use PHPUnit\Framework\TestCase;
use RastgeleProfil\HizSiniri\BellekDeposu;
use RastgeleProfil\HizSiniri\HizSiniri;

final class HizSiniriTest extends TestCase
{
    /** @var float */
    private $zaman = 1800000000.0;

    private function sinir(array $ayar): HizSiniri
    {
        return new HizSiniri($ayar, new BellekDeposu(), function () {
            return $this->zaman;
        });
    }

    private function izinler(HizSiniri $h, int $n, string $ip = '1.1.1.1', ?string $anahtar = null): array
    {
        $s = [];
        for ($i = 0; $i < $n; $i++) {
            $s[] = $h->kontrol($ip, $anahtar)->izin;
        }
        return $s;
    }

    public function testPatlamaKadarIzinVerirSonraReddeder(): void
    {
        $h = $this->sinir(['saniyede' => 10, 'patlama' => 20]);
        $this->assertSame(array_fill(0, 20, true), $this->izinler($h, 20));
        $s = $h->kontrol('1.1.1.1');
        $this->assertFalse($s->izin);
        $this->assertSame(1, $s->bekle);
        $this->assertSame(10, $s->limit);
        $this->assertSame(0, $s->kalan);
    }

    public function testJetonlarZamanlaDolar(): void
    {
        $h = $this->sinir(['saniyede' => 10, 'patlama' => 20]);
        $this->izinler($h, 20);
        $this->zaman += 0.5;
        $this->assertSame(array_merge(array_fill(0, 5, true), [false]), $this->izinler($h, 6));
        $this->zaman += 60;
        $this->assertSame(array_fill(0, 20, true), $this->izinler($h, 20));
    }

    public function testIpAdresleriAyriSayilir(): void
    {
        $h = $this->sinir(['saniyede' => 1, 'patlama' => 1]);
        $this->assertSame([true, false], $this->izinler($h, 2, '1.1.1.1'));
        $this->assertSame([true, false], $this->izinler($h, 2, '2.2.2.2'));
    }

    public function testSinirsizSecenekleri(): void
    {
        $this->assertNotContains(false, $this->izinler($this->sinir(['saniyede' => 0, 'gunluk' => 0]), 500));
        $this->assertNotContains(false, $this->izinler($this->sinir(['etkin' => false]), 500));
        $this->assertNotContains(false, $this->izinler($this->sinir(['beyaz_liste' => ['9.9.9.9']]), 500, '9.9.9.9'));
        $this->assertNotContains(false, $this->izinler($this->sinir(['anahtarlar' => ['gizli']]), 500, '1.1.1.1', 'gizli'));
        $this->assertContains(false, $this->izinler($this->sinir(['anahtarlar' => ['gizli']]), 500, '1.1.1.1', 'yanlis'));
        $this->assertNull($this->sinir(['saniyede' => 0])->kontrol('1.1.1.1')->limit);
    }

    public function testGunlukTavan(): void
    {
        $h = $this->sinir(['saniyede' => 0, 'gunluk' => 3]);
        $this->assertSame([true, true, true, false], $this->izinler($h, 4));
        $this->assertGreaterThan(0, $h->kontrol('1.1.1.1')->bekle);
        $this->zaman += 86400;
        $this->assertSame([true, true, true, false], $this->izinler($h, 4));
    }
}
