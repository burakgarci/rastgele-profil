<?php

namespace RastgeleProfil;

use RastgeleProfil\HizSiniri\ApcuDeposu;
use Throwable;

/** saglik.php: veri dosyaları, fotoğraflar ve hız sınırı deposunun durumu. */
final class Saglik
{
    public static function rapor(array $ayar): Yanit
    {
        $sonuc = ['durum' => 'ok', 'zaman' => date('c')];
        try {
            $v = Veri::yukle($ayar['veri_dizini'], $ayar['foto_dizini']);
            $sonuc['veri'] = [
                'erkek_isimleri' => count($v->erkekIsimleri),
                'kadin_isimleri' => count($v->kadinIsimleri),
                'soyisimler' => count($v->soyisimler),
                'sokaklar' => count($v->sokaklar),
                'sehirler' => count($v->sehirler),
                'meslekler' => count($v->meslekler),
                'sporlar' => count($v->sporlar),
                'fotograflar' => ['erkek' => count($v->fotograflar['erkek']), 'kadin' => count($v->fotograflar['kadın'])],
            ];
        } catch (Throwable $e) {
            error_log('rastgele-profil saglik: ' . $e->getMessage());
            $sonuc['durum'] = 'hata';
            $sonuc['veri'] = 'yüklenemedi';
        }

        $hs = $ayar['hiz_siniri'];
        $apcu = ($hs['depo'] ?? 'otomatik') !== 'dosya' && ApcuDeposu::kullanilabilir();
        $sonuc['hiz_siniri'] = [
            'etkin' => (bool) $hs['etkin'],
            'depo' => $apcu ? 'apcu' : 'dosya',
            'yazilabilir' => $apcu || self::yazilabilir($hs['dizin']),
        ];
        if (!$sonuc['hiz_siniri']['yazilabilir']) {
            $sonuc['durum'] = 'hata';
        }

        return Yanit::json($sonuc['durum'] === 'ok' ? 200 : 503, $sonuc, ['Cache-Control' => 'no-store']);
    }

    private static function yazilabilir(string $dizin): bool
    {
        return (is_dir($dizin) || @mkdir($dizin, 0700, true)) && is_writable($dizin);
    }
}
