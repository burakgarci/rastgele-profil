<?php

namespace RastgeleProfil\HizSiniri;

use RuntimeException;

/** Paylaşımlı hostingte de çalışan, flock ile kilitli dosya tabanlı sayaç. */
final class DosyaDeposu implements Depo
{
    /** @var string */
    private $dizin;

    public function __construct(string $dizin)
    {
        if (!is_dir($dizin) && !@mkdir($dizin, 0700, true) && !is_dir($dizin)) {
            throw new RuntimeException('Hız sınırı dizini oluşturulamadı.');
        }
        $this->dizin = $dizin;
    }

    public function guncelle(string $anahtar, callable $islem)
    {
        $f = fopen($this->dizin . '/' . sha1($anahtar), 'c+');
        if ($f === false) {
            throw new RuntimeException('Hız sınırı dosyası açılamadı.');
        }
        try {
            flock($f, LOCK_EX);
            $durum = json_decode((string) stream_get_contents($f), true);
            [$yeni, $donus] = $islem(is_array($durum) ? $durum : null);
            rewind($f);
            ftruncate($f, 0);
            fwrite($f, json_encode($yeni));
            fflush($f);
            return $donus;
        } finally {
            flock($f, LOCK_UN);
            fclose($f);
        }
    }

    /** İki günden eski sayaç dosyalarını siler. */
    public function temizle(int $yas = 172800): void
    {
        foreach (glob($this->dizin . '/*') ?: [] as $d) {
            if (is_file($d) && filemtime($d) < time() - $yas) {
                @unlink($d);
            }
        }
    }
}
