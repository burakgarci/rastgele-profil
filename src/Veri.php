<?php

namespace RastgeleProfil;

use RuntimeException;

/** assets/data altındaki TXT kaynaklarını ve fotoğraf listesini yükler. */
final class Veri
{
    /** @var string[] */
    public $erkekIsimleri;
    /** @var string[] */
    public $kadinIsimleri;
    /** @var string[] */
    public $soyisimler;
    /** @var string[] */
    public $sokaklar;
    /** @var string[] */
    public $sehirler;
    /** @var string[] */
    public $meslekler;
    /** @var string[] */
    public $sporlar;
    /** @var array{erkek: string[], kadın: string[]} Göreli yollar: "erkek/12.jpg" */
    public $fotograflar;

    public static function yukle(string $veriDizini, string $fotoDizini): self
    {
        $v = new self();
        $v->erkekIsimleri = self::liste("$veriDizini/isimler-erkek.txt");
        $v->kadinIsimleri = self::liste("$veriDizini/isimler-kadin.txt");
        $v->soyisimler = self::liste("$veriDizini/soyisimler.txt");
        $v->sokaklar = self::liste("$veriDizini/cbsm.txt");
        $v->sehirler = self::liste("$veriDizini/sehirler.txt");
        $v->meslekler = self::liste("$veriDizini/meslekler.txt");
        $v->sporlar = self::liste("$veriDizini/sporlar.txt");
        $v->fotograflar = [
            'erkek' => self::fotolar($fotoDizini, 'erkek'),
            'kadın' => self::fotolar($fotoDizini, 'kadin'),
        ];
        return $v;
    }

    /** @return string[] */
    private static function liste(string $dosya): array
    {
        $icerik = is_readable($dosya) ? file_get_contents($dosya) : false;
        if ($icerik === false) {
            throw new RuntimeException('Veri dosyası okunamadı: ' . basename($dosya));
        }
        $icerik = preg_replace('/^\xEF\xBB\xBF/', '', $icerik);
        // Ayrık yazılmış noktalı harfler: "I" + U+0307 -> "İ", "i" + U+0307 -> "i"
        $icerik = str_replace(["I\u{307}", "i\u{307}"], ['İ', 'i'], $icerik);
        $satirlar = array_values(array_filter(array_map('trim', preg_split('/\R/u', $icerik)), 'strlen'));
        if (!$satirlar) {
            throw new RuntimeException('Veri dosyası boş: ' . basename($dosya));
        }
        return $satirlar;
    }

    /** @return string[] */
    private static function fotolar(string $fotoDizini, string $klasor): array
    {
        $dosyalar = glob("$fotoDizini/$klasor/*.jpg") ?: [];
        if (!$dosyalar) {
            throw new RuntimeException("Fotoğraf bulunamadı: $klasor");
        }
        return array_map(function ($d) use ($klasor) {
            return $klasor . '/' . basename($d);
        }, $dosyalar);
    }
}
