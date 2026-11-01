<?php

namespace RastgeleProfil;

/** Türkçe metin yardımcıları. mbstring gerektirmez. */
final class Metin
{
    private const ASCII = [
        'ı' => 'i', 'İ' => 'I', 'ç' => 'c', 'Ç' => 'C', 'ü' => 'u', 'Ü' => 'U',
        'ö' => 'o', 'Ö' => 'O', 'ş' => 's', 'Ş' => 'S', 'ğ' => 'g', 'Ğ' => 'G',
    ];

    private const BUYUK = ['ç' => 'Ç', 'ğ' => 'Ğ', 'ı' => 'I', 'i' => 'İ', 'ö' => 'Ö', 'ş' => 'Ş', 'ü' => 'Ü'];

    /** "Ayşe Nur" -> "ayse_nur" (kullanıcı adı, e-posta, alan adı için) */
    public static function slug(string $metin, string $bosluk = '_'): string
    {
        $slug = strtolower(str_replace(' ', $bosluk, strtr($metin, self::ASCII)));
        return preg_replace('/[^a-z0-9_-]/', '', $slug);
    }

    /** Parametre karşılaştırması için: "KADIN", "Kadın", "kadin" -> "kadin" */
    public static function anahtar(string $metin): string
    {
        return strtolower(trim(strtr($metin, self::ASCII)));
    }

    /** Türkçe uyumlu ilk harf büyütme: "izcilik" -> "İzcilik" */
    public static function ilkBuyuk(string $metin): string
    {
        if (!preg_match('/^./us', $metin, $m)) {
            return $metin;
        }
        $ilk = self::BUYUK[$m[0]] ?? strtoupper($m[0]);
        return $ilk . substr($metin, strlen($m[0]));
    }
}
