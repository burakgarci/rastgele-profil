<?php

namespace RastgeleProfil\HizSiniri;

interface Depo
{
    /**
     * Anahtarın durumunu okuyup günceller.
     * @param callable(array|null): array{0: array, 1: mixed} $islem Yeni durumu ve dönüş değerini verir.
     * @return mixed $islem'in dönüş değeri
     */
    public function guncelle(string $anahtar, callable $islem);
}
