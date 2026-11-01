<?php

namespace RastgeleProfil;

use RastgeleProfil\HizSiniri\ApcuDeposu;
use RastgeleProfil\HizSiniri\DosyaDeposu;
use RastgeleProfil\HizSiniri\Depo;
use RastgeleProfil\HizSiniri\HizSiniri;
use RastgeleProfil\HizSiniri\Sonuc;
use Throwable;

/** api.php'nin HTTP katmanı: doğrulama, hız sınırı, hata yönetimi. */
final class Api
{
    /** @var array */
    private $ayar;
    /** @var Depo|null */
    private $depo;

    public function __construct(array $ayar, ?Depo $depo = null)
    {
        $this->ayar = $ayar;
        $this->depo = $depo;
    }

    /**
     * @param array<string, mixed> $sorgu $_GET
     * @param array<string, mixed> $sunucu $_SERVER
     */
    public function isle(array $sorgu, array $sunucu): Yanit
    {
        $ortak = ['Cache-Control' => 'no-store'];
        if (!empty($this->ayar['cors'])) {
            $ortak['Access-Control-Allow-Origin'] = $this->ayar['cors'];
        }

        try {
            $metot = strtoupper($sunucu['REQUEST_METHOD'] ?? 'GET');
            if ($metot === 'OPTIONS') {
                return new Yanit(204, '', $ortak + [
                    'Access-Control-Allow-Methods' => 'GET, OPTIONS',
                    'Access-Control-Allow-Headers' => 'X-Api-Anahtari',
                    'Access-Control-Max-Age' => '86400',
                ]);
            }
            if ($metot !== 'GET' && $metot !== 'HEAD') {
                return Yanit::hata(405, 'Yalnızca GET istekleri desteklenir.', $ortak + ['Allow' => 'GET, OPTIONS']);
            }

            $hs = $this->hizKontrolu($sorgu, $sunucu);
            if ($hs->limit !== null) {
                $ortak['X-RateLimit-Limit'] = (string) $hs->limit;
                $ortak['X-RateLimit-Remaining'] = (string) max(0, (int) $hs->kalan);
            }
            if (!$hs->izin) {
                return Yanit::hata(429, 'Çok fazla istek. Lütfen biraz bekleyin.', $ortak + ['Retry-After' => (string) $hs->bekle]);
            }

            $istek = ApiIstegi::coz($sorgu, (int) $this->ayar['azami_adet'], $this->ayar['eposta_alan_adi']);
            $uretici = new ProfilUretici(
                Veri::yukle($this->ayar['veri_dizini'], $this->ayar['foto_dizini']),
                new Rastgele($istek->seed),
                $this->ayar['resim_url_tabani']
            );

            if ($istek->adet === null) {
                $veri = $uretici->uret($istek->cinsiyet, $istek->epostaAlanAdi);
            } else {
                $veri = [];
                for ($i = 0; $i < $istek->adet; $i++) {
                    $veri[] = $uretici->uret($istek->cinsiyet, $istek->epostaAlanAdi);
                }
            }
            return Yanit::json(200, $veri, $ortak);
        } catch (GecersizIstek $e) {
            return Yanit::hata(400, $e->getMessage(), $ortak);
        } catch (Throwable $e) {
            error_log('rastgele-profil: ' . get_class($e) . ': ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
            return Yanit::hata(500, 'İşlem sırasında bir hata oluştu.', $ortak);
        }
    }

    /** Sayaç deposu bozulursa API durmaz: hata günlüğe yazılır, istek geçer. */
    private function hizKontrolu(array $sorgu, array $sunucu): Sonuc
    {
        try {
            $anahtar = $sunucu['HTTP_X_API_ANAHTARI'] ?? $sorgu['anahtar'] ?? null;
            return $this->hizSiniri()->kontrol($this->ip($sunucu), is_string($anahtar) ? $anahtar : null);
        } catch (Throwable $e) {
            error_log('rastgele-profil hiz siniri: ' . $e->getMessage());
            return Sonuc::sinirsiz();
        }
    }

    private function hizSiniri(): HizSiniri
    {
        $ayar = $this->ayar['hiz_siniri'];
        if ($this->depo === null) {
            $dosya = ($ayar['depo'] ?? 'otomatik') === 'dosya' || !ApcuDeposu::kullanilabilir();
            $this->depo = $dosya ? new DosyaDeposu($ayar['dizin']) : new ApcuDeposu();
            if ($this->depo instanceof DosyaDeposu && mt_rand(1, 1000) === 1) {
                $this->depo->temizle();
            }
        }
        return new HizSiniri($ayar, $this->depo);
    }

    private function ip(array $sunucu): string
    {
        $baslik = $this->ayar['hiz_siniri']['ip_basligi'] ?? null;
        $ip = $baslik ? ($sunucu[$baslik] ?? null) : null;
        return is_string($ip) && $ip !== '' ? trim(explode(',', $ip)[0]) : (string) ($sunucu['REMOTE_ADDR'] ?? '0.0.0.0');
    }
}
