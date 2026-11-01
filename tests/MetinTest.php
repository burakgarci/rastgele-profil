<?php

namespace RastgeleProfil\Tests;

use PHPUnit\Framework\TestCase;
use RastgeleProfil\Metin;

final class MetinTest extends TestCase
{
    public function testSlug(): void
    {
        $this->assertSame('ayse_nur_gunes', Metin::slug('Ayşe Nur Güneş'));
        $this->assertSame('ismail', Metin::slug('İsmail'));
        $this->assertSame('cagri-oz', Metin::slug('Çağrı Öz', '-'));
        $this->assertSame('irem', Metin::slug("I\u{307}rem"), 'ayrık noktalı harf');
    }

    public function testAnahtar(): void
    {
        foreach (['kadın', 'kadin', 'KADIN', 'Kadın', ' KADİN '] as $d) {
            $this->assertSame('kadin', Metin::anahtar($d), $d);
        }
    }

    public function testIlkBuyuk(): void
    {
        $this->assertSame('İzcilik', Metin::ilkBuyuk('izcilik'));
        $this->assertSame('Çim hokeyi', Metin::ilkBuyuk('çim hokeyi'));
        $this->assertSame('Atletizm', Metin::ilkBuyuk('atletizm'));
        $this->assertSame('', Metin::ilkBuyuk(''));
    }
}
