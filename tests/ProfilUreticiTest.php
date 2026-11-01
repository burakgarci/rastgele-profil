<?php

namespace RastgeleProfil\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use RastgeleProfil\ProfilUretici;
use RastgeleProfil\Rastgele;
use RastgeleProfil\Veri;

final class ProfilUreticiTest extends TestCase
{
    /** @var Veri */
    private static $veri;

    public static function setUpBeforeClass(): void
    {
        $a = test_ayarlari();
        self::$veri = Veri::yukle($a['veri_dizini'], $a['foto_dizini']);
    }

    private function uretici(?int $seed = null): ProfilUretici
    {
        return new ProfilUretici(self::$veri, new Rastgele($seed), 'https://ik.imagekit.io/brk/');
    }

    public function testAlanlarVeSiralariEskiSozlesmeyleAyni(): void
    {
        $eski = json_decode(file_get_contents(__DIR__ . '/fixtures/eski-sozlesme.json'), true);
        $p = $this->uretici()->uret();
        $this->assertSame(array_keys($eski), array_keys($p));
        foreach ($p as $alan => $deger) {
            $this->assertIsString($deger, $alan);
            $this->assertNotSame('', $deger, $alan);
        }
    }

    public function testDegerlerdeGorunmezKarakterYok(): void
    {
        $u = $this->uretici();
        for ($i = 0; $i < 300; $i++) {
            foreach ($u->uret() as $alan => $deger) {
                $this->assertDoesNotMatchRegularExpression('/[\r\n\t]|\xEF\xBB\xBF|^\s|\s$|\s{2}/', $deger, "$alan: $deger");
            }
        }
    }

    public function testAlanlarBirbiriyleTutarli(): void
    {
        $u = $this->uretici();
        $bugun = new DateTimeImmutable('today');
        for ($i = 0; $i < 300; $i++) {
            $p = $u->uret();
            $this->assertSame($p['isim'] . ' ' . $p['soyisim'], $p['tam_isim']);
            $this->assertContains($p['cinsiyet'], ['erkek', 'kadın']);
            $this->assertContains($p['isim'], $p['cinsiyet'] === 'kadın' ? self::$veri->kadinIsimleri : self::$veri->erkekIsimleri);
            $this->assertStringContainsString($p['cinsiyet'] === 'kadın' ? '/kadin/' : '/erkek/', $p['profil_resmi']);

            $dogum = DateTimeImmutable::createFromFormat('!d.m.Y', $p['dogum_tarihi']);
            $this->assertNotFalse($dogum);
            $this->assertSame((string) $dogum->diff($bugun)->y, $p['yas']);
            $this->assertGreaterThanOrEqual(20, (int) $p['yas']);
            $this->assertLessThanOrEqual(50, (int) $p['yas']);
            $this->assertStringContainsString($p['yas'] . ' yaşında', $p['biyografi']);

            $this->assertStringEndsWith(' ' . $p['sehir'], $p['adres']);
            $this->assertMatchesRegularExpression('/ No:[1-9]\d{0,2} /', $p['adres']);
            $this->assertDoesNotMatchRegularExpression('/(Caddesi|Bulvarı|Sokağı|Yolu) (Caddesi|Bulvarı|Sokağı)/u', $p['adres']);

            $this->assertMatchesRegularExpression('/^[a-z0-9_]+@example\.com$/', $p['eposta']);
            $this->assertMatchesRegularExpression('/^[A-Za-z0-9_]+$/', $p['kullanici_adi']);
            $this->assertMatchesRegularExpression('/^[a-z0-9_-]+\.(com|net|org)(\.tr)?$/', $p['web_adresi']);
            $this->assertMatchesRegularExpression('/^\+90851\d{7}$/', $p['telefon_numarasi']);
            $this->assertMatchesRegularExpression('/^[A-Za-z0-9]{10}$/', $p['sifre']);
            $this->assertSame(10, count(array_unique(str_split($p['sifre']))));
        }
    }

    public function testCinsiyetVeEpostaAlanAdiUygulanir(): void
    {
        $u = $this->uretici();
        for ($i = 0; $i < 50; $i++) {
            $this->assertSame('kadın', $u->uret('kadın')['cinsiyet']);
            $this->assertSame('erkek', $u->uret('erkek')['cinsiyet']);
            $this->assertStringEndsWith('@firmam.com', $u->uret(null, 'firmam.com')['eposta']);
        }
    }

    public function testAyniSeedAyniSonucuVerir(): void
    {
        $a = [$this->uretici(42)->uret(), $this->uretici(42)->uret()];
        $b = $this->uretici(42);
        $this->assertSame($a[0], $b->uret());
        $this->assertSame($a[0], $a[1]);
        $this->assertNotSame($this->uretici(42)->uret(), $this->uretici(43)->uret());
    }
}
