<?php

namespace RastgeleProfil\Tests;

use PHPUnit\Framework\TestCase;
use RastgeleProfil\ApiIstegi;
use RastgeleProfil\GecersizIstek;

final class ApiIstegiTest extends TestCase
{
    private function coz(array $sorgu): ApiIstegi
    {
        return ApiIstegi::coz($sorgu, 250, 'example.com');
    }

    public function testVarsayilanlar(): void
    {
        $i = $this->coz([]);
        $this->assertNull($i->adet);
        $this->assertNull($i->cinsiyet);
        $this->assertSame('example.com', $i->epostaAlanAdi);
        $this->assertNull($i->seed);
    }

    /** @dataProvider gecerliAdetler */
    public function testGecerliAdet(string $deger, ?int $beklenen): void
    {
        $this->assertSame($beklenen, $this->coz(['adet' => $deger])->adet);
    }

    public function gecerliAdetler(): array
    {
        return [['', null], ['1', 1], ['05', 5], ['250', 250], ['251', 250], ['99999999999999999999', 250], [' 3 ', 3]];
    }

    /** @dataProvider gecersizIstekler */
    public function testGecersizIstekReddedilir(array $sorgu): void
    {
        $this->expectException(GecersizIstek::class);
        $this->coz($sorgu);
    }

    public function gecersizIstekler(): array
    {
        return [
            [['adet' => 'abc']], [['adet' => '0']], [['adet' => '-5']], [['adet' => '1.5']], [['adet' => ['1']]],
            [['cinsiyet' => 'foo']], [['cinsiyet' => ['kadin']]],
            [['eposta_alan_adi' => 'bad_domain']], [['eposta_alan_adi' => 'firma']], [['eposta_alan_adi' => 'a@b.com']],
            [['eposta_alan_adi' => 'şirket.com']], [['seed' => 'x']], [['seed' => '2147483648']], [['seed' => '-1']],
        ];
    }

    /** @dataProvider cinsiyetler */
    public function testCinsiyetTurkceVeAsciiKabulEdilir(string $deger, ?string $beklenen): void
    {
        $this->assertSame($beklenen, $this->coz(['cinsiyet' => $deger])->cinsiyet);
    }

    public function cinsiyetler(): array
    {
        return [
            ['kadın', 'kadın'], ['kadin', 'kadın'], ['KADIN', 'kadın'], ['Kadın', 'kadın'],
            ['erkek', 'erkek'], ['ERKEK', 'erkek'],
            ['', null], ['üniseks', null], ['uniseks', null], ['rastgele', null],
        ];
    }

    public function testEpostaAlanAdiVeSeed(): void
    {
        $i = $this->coz(['eposta_alan_adi' => 'Firmam.COM.tr', 'seed' => '42']);
        $this->assertSame('firmam.com.tr', $i->epostaAlanAdi);
        $this->assertSame(42, $i->seed);
    }
}
