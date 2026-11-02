<?php

namespace RastgeleProfil\Tests;

use PHPUnit\Framework\TestCase;
use RastgeleProfil\Api;
use RastgeleProfil\HizSiniri\BellekDeposu;
use RastgeleProfil\Saglik;
use RastgeleProfil\Yanit;

final class ApiTest extends TestCase
{
    private function istek(array $sorgu = [], array $ayar = [], string $metot = 'GET', ?Api $api = null): Yanit
    {
        $api = $api ?? new Api(test_ayarlari($ayar), new BellekDeposu());
        return $api->isle($sorgu, ['REQUEST_METHOD' => $metot, 'REMOTE_ADDR' => '1.1.1.1']);
    }

    private function json(Yanit $y)
    {
        $this->assertSame('application/json; charset=utf-8', $y->basliklar['Content-Type'] ?? null);
        return json_decode($y->govde, true, 512, JSON_THROW_ON_ERROR);
    }

    public function testParametresizIstekTekNesneDoner(): void
    {
        $y = $this->istek();
        $this->assertSame(200, $y->durum);
        $veri = $this->json($y);
        $this->assertArrayHasKey('tam_isim', $veri);
        $this->assertSame('no-store', $y->basliklar['Cache-Control']);
        $this->assertSame('*', $y->basliklar['Access-Control-Allow-Origin']);
        $this->assertSame('10', $y->basliklar['X-RateLimit-Limit']);
    }

    public function testAdetVerilinceHerZamanDiziDoner(): void
    {
        $this->assertCount(1, $this->json($this->istek(['adet' => '1'])));
        $this->assertCount(3, $this->json($this->istek(['adet' => '3'])));
        $this->assertCount(250, $this->json($this->istek(['adet' => '1000'])));
    }

    public function testCiktiEskiBicimdeTekKodlanmisJson(): void
    {
        $y = $this->istek(['seed' => '7']);
        $this->assertStringStartsWith("{\n    \"isim\": ", $y->govde);
        $this->assertStringNotContainsString('\\u', $y->govde);
        $this->assertStringNotContainsString('\\/', $y->govde);
    }

    public function testParametrelerUygulanir(): void
    {
        $veri = $this->json($this->istek(['adet' => '5', 'cinsiyet' => 'KADIN', 'eposta_alan_adi' => 'firmam.com']));
        foreach ($veri as $p) {
            $this->assertSame('kadın', $p['cinsiyet']);
            $this->assertStringEndsWith('@firmam.com', $p['eposta']);
        }
        $this->assertSame($this->istek(['seed' => '42'])->govde, $this->istek(['seed' => '42'])->govde);
    }

    public function testGecersizIstek400(): void
    {
        $y = $this->istek(['adet' => 'abc']);
        $this->assertSame(400, $y->durum);
        $this->assertStringContainsString('adet', $this->json($y)['hata']);
    }

    public function testHizSiniri429(): void
    {
        $api = new Api(test_ayarlari(['hiz_siniri' => ['saniyede' => 1, 'patlama' => 2]]), new BellekDeposu());
        $this->assertSame(200, $this->istek([], [], 'GET', $api)->durum);
        $this->assertSame(200, $this->istek([], [], 'GET', $api)->durum);
        $y = $this->istek([], [], 'GET', $api);
        $this->assertSame(429, $y->durum);
        $this->assertSame('1', $y->basliklar['Retry-After']);
        $this->assertArrayHasKey('hata', $this->json($y));
    }

    public function testMetotlar(): void
    {
        $this->assertSame(204, $this->istek([], [], 'OPTIONS')->durum);
        $y = $this->istek([], [], 'POST');
        $this->assertSame(405, $y->durum);
        $this->assertArrayHasKey('hata', $this->json($y));
    }

    public function testVeriHatasiGenelMesajla500(): void
    {
        $eski = ini_set('error_log', '/dev/null');
        $y = $this->istek([], ['veri_dizini' => '/yok']);
        ini_set('error_log', (string) $eski);
        $this->assertSame(500, $y->durum);
        $this->assertSame(['hata' => 'İşlem sırasında bir hata oluştu.'], $this->json($y));
        $this->assertStringNotContainsString('/yok', $y->govde);
    }

    public function testResimAdresiIstegeGoreTamAdres(): void
    {
        $api = new Api(test_ayarlari(), new BellekDeposu());
        $p = json_decode($api->isle([], ['REQUEST_METHOD' => 'GET', 'REMOTE_ADDR' => '1.1.1.1', 'HTTP_HOST' => 'rp.burakgarci.net', 'HTTPS' => 'on', 'SCRIPT_NAME' => '/api.php'])->govde, true);
        $this->assertMatchesRegularExpression('#^https://rp\.burakgarci\.net/photos/(erkek|kadin)/\d+\.jpg$#', $p['profil_resmi']);
        $this->assertSame('http://localhost:8080/alt/photos/', Api::resimTabani(['HTTP_HOST' => 'localhost:8080', 'SCRIPT_NAME' => '/alt/api.php']));
        $this->assertSame('photos/', Api::resimTabani(['HTTP_HOST' => 'kötü host']));
        $p = json_decode((new Api(test_ayarlari(['resim_url_tabani' => 'https://cdn.ornek.com/']), new BellekDeposu()))->isle([], ['REQUEST_METHOD' => 'GET', 'REMOTE_ADDR' => '1.1.1.1'])->govde, true);
        $this->assertStringStartsWith('https://cdn.ornek.com/', $p['profil_resmi']);
    }

    public function testSaglik(): void
    {
        $y = Saglik::rapor(test_ayarlari(['hiz_siniri' => ['dizin' => sys_get_temp_dir() . '/rp-saglik']]));
        $this->assertSame(200, $y->durum);
        $veri = $this->json($y);
        $this->assertSame('ok', $veri['durum']);
        $this->assertGreaterThan(0, $veri['veri']['fotograflar']['kadin']);

        $eski = ini_set('error_log', '/dev/null');
        $y = Saglik::rapor(test_ayarlari(['foto_dizini' => '/yok']));
        ini_set('error_log', (string) $eski);
        $this->assertSame(503, $y->durum);
    }
}
