<?php

namespace RastgeleProfil\HizSiniri;

/** Testler için tek istek ömürlü depo. */
final class BellekDeposu implements Depo
{
    /** @var array<string, array> */
    private $veri = [];

    public function guncelle(string $anahtar, callable $islem)
    {
        [$this->veri[$anahtar], $donus] = $islem($this->veri[$anahtar] ?? null);
        return $donus;
    }
}
