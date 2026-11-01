<?php

namespace RastgeleProfil;

/**
 * Rastgelelik kaynağı. Seed verilirse aynı seed aynı sonucu üretir
 * (doğum tarihi/yaş bugünün tarihine göre hesaplandığı için aynı gün içinde).
 */
final class Rastgele
{
    public function __construct(?int $seed = null)
    {
        mt_srand($seed ?? random_int(0, mt_getrandmax()));
    }

    public function sayi(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }

    /** @template T @param T[] $liste @return T */
    public function sec(array $liste)
    {
        return $liste[mt_rand(0, count($liste) - 1)];
    }

    public function karistir(string $karakterler): string
    {
        $a = str_split($karakterler);
        for ($i = count($a) - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);
            [$a[$i], $a[$j]] = [$a[$j], $a[$i]];
        }
        return implode('', $a);
    }
}
