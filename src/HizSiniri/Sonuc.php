<?php

namespace RastgeleProfil\HizSiniri;

final class Sonuc
{
    /** @var bool */
    public $izin;
    /** @var int|null Saniyedeki istek sınırı; null ise sınır uygulanmadı */
    public $limit;
    /** @var int|null */
    public $kalan;
    /** @var int Reddedildiyse kaç saniye sonra tekrar denenebileceği */
    public $bekle = 0;

    public static function sinirsiz(): self
    {
        $s = new self();
        $s->izin = true;
        return $s;
    }
}
