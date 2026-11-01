<?php

namespace RastgeleProfil\Tests;

use PHPUnit\Framework\TestCase;
use RastgeleProfil\Degisiklikler;

final class DegisikliklerTest extends TestCase
{
    public function testChangelogOkunur(): void
    {
        $liste = Degisiklikler::oku(__DIR__ . '/../CHANGELOG.md');
        $this->assertGreaterThanOrEqual(5, count($liste));
        $this->assertSame('Nisan 2016', end($liste)['tarih']);
        foreach ($liste as $d) {
            $this->assertNotSame('', $d['baslik']);
            $this->assertNotEmpty($d['maddeler']);
        }
    }

    public function testTarihBicimleri(): void
    {
        $dosya = tempnam(sys_get_temp_dir(), 'cl');
        file_put_contents($dosya, "# X\r\n\r\n## 2021-11-30 — Bir\r\n- a\r\n## 2020-08 - İki\n- b\n- c\n");
        $liste = Degisiklikler::oku($dosya);
        unlink($dosya);
        $this->assertSame([['tarih' => '30 Kasım 2021', 'baslik' => 'Bir', 'maddeler' => ['a']], ['tarih' => 'Ağustos 2020', 'baslik' => 'İki', 'maddeler' => ['b', 'c']]], $liste);
        $this->assertSame([], Degisiklikler::oku('/yok'));
    }
}
