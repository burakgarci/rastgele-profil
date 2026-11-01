<?php

namespace RastgeleProfil\HizSiniri;

/** APCu varsa bellekte tutar. Okuma-yazma atomik değildir; hız sınırı için yeterli. */
final class ApcuDeposu implements Depo
{
    public static function kullanilabilir(): bool
    {
        return function_exists('apcu_enabled') && apcu_enabled();
    }

    public function guncelle(string $anahtar, callable $islem)
    {
        $anahtar = 'rp_hs_' . sha1($anahtar);
        $durum = apcu_fetch($anahtar, $ok);
        [$yeni, $donus] = $islem($ok && is_array($durum) ? $durum : null);
        apcu_store($anahtar, $yeni, 172800);
        return $donus;
    }
}
