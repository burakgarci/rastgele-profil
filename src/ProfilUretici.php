<?php

namespace RastgeleProfil;

use DateTimeImmutable;

/** HTTP'den bağımsız profil üretim motoru. */
final class ProfilUretici
{
    public const CINSIYETLER = ['erkek', 'kadın'];

    private const SOKAK_EKLERI = ['Caddesi', 'Bulvarı', 'Sokağı'];
    private const SPOR_EKLERI = ['meraklısı', 'sevdalısı', 'hayranı', 'izleyicisi', 'sever', 'hastası', 'manyağı', 'düşkünü', 'bağımlısı'];
    private const UZANTILAR = ['com', 'net', 'org', 'com.tr', 'net.tr', 'org.tr'];
    private const SIFRE_KARAKTERLERI = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    private const EN_KUCUK_YAS = 20;
    private const EN_BUYUK_YAS = 50;

    /** @var Veri */
    private $veri;
    /** @var Rastgele */
    private $r;
    /** @var string */
    private $resimUrlTabani;

    public function __construct(Veri $veri, Rastgele $rastgele, string $resimUrlTabani)
    {
        $this->veri = $veri;
        $this->r = $rastgele;
        $this->resimUrlTabani = $resimUrlTabani;
    }

    /**
     * @param string|null $cinsiyet "erkek", "kadın" ya da null (rastgele)
     * @return array<string, string> Alan sırası mevcut API sözleşmesiyle aynıdır.
     */
    public function uret(?string $cinsiyet = null, string $epostaAlanAdi = 'example.com'): array
    {
        $r = $this->r;
        $v = $this->veri;

        $cinsiyet = $cinsiyet ?? $r->sec(self::CINSIYETLER);
        $isim = $r->sec($cinsiyet === 'kadın' ? $v->kadinIsimleri : $v->erkekIsimleri);
        do {
            $soyisim = $r->sec($v->soyisimler); // "Yılmaz Yılmaz" gibi tekrarları önle
        } while ($soyisim === $isim);
        $sehir = $r->sec($v->sehirler);
        $meslek = $r->sec($v->meslekler);
        $dogum = $this->dogumTarihi();
        $yas = (string) $dogum->diff(new DateTimeImmutable('today'))->y;
        $ilgiAlani = Metin::ilkBuyuk($r->sec($v->sporlar)) . ' ' . $r->sec(self::SPOR_EKLERI);

        return [
            'isim' => $isim,
            'soyisim' => $soyisim,
            'tam_isim' => "$isim $soyisim",
            'cinsiyet' => $cinsiyet,
            'sehir' => $sehir,
            'adres' => $this->adres($sehir),
            'meslek' => $meslek,
            'eposta' => $this->eposta($isim, $soyisim) . '@' . $epostaAlanAdi,
            'kullanici_adi' => $this->kullaniciAdi($isim, $soyisim),
            'sifre' => substr($r->karistir(self::SIFRE_KARAKTERLERI), 0, 10),
            'dogum_tarihi' => $dogum->format('d.m.Y'),
            'yas' => $yas,
            'telefon_numarasi' => '+90851' . $r->sayi(1111111, 9999999),
            'ilgi_alani' => $ilgiAlani,
            'biyografi' => $this->biyografi($yas, $meslek, $ilgiAlani, $sehir),
            'profil_resmi' => $this->resimUrlTabani . $r->sec($v->fotograflar[$cinsiyet]),
            'web_adresi' => $this->webAdresi($isim, $soyisim),
        ];
    }

    private function adres(string $sehir): string
    {
        $sokak = $this->r->sec($this->veri->sokaklar);
        // "Atatürk Bulvarı" gibi türü zaten içeren adlara ikinci bir ek eklenmez.
        if (!preg_match('/ (Caddesi|Bulvarı|Sokağı|Sokak|Yolu|Mahallesi)$/u', $sokak)) {
            $sokak .= ' ' . $this->r->sec(self::SOKAK_EKLERI);
        }
        return "$sokak No:" . $this->r->sayi(1, 999) . " $sehir";
    }

    private function kullaniciAdi(string $isim, string $soyisim): string
    {
        $i = Metin::slug($isim);
        $s = Metin::slug($soyisim);
        $tam = Metin::slug("$isim $soyisim");
        return $this->r->sec([
            $tam,
            ucfirst($i) . ucfirst($s),
            ucfirst($s) . ucfirst($i),
            $tam . $this->r->sayi(0, 999),
            $i . $this->r->sayi(0, 999),
            $s . $this->r->sayi(0, 999),
        ]);
    }

    private function eposta(string $isim, string $soyisim): string
    {
        $tam = Metin::slug("$isim $soyisim");
        $ters = Metin::slug($soyisim) . Metin::slug($isim);
        return $this->r->sec([$tam, $tam . $this->r->sayi(0, 999), $ters, $ters . $this->r->sayi(0, 999)]);
    }

    private function webAdresi(string $isim, string $soyisim): string
    {
        $i = Metin::slug($isim);
        $s = Metin::slug($soyisim);
        return $this->r->sec(["$s$i", "$s-$i", "$i$s", "$i-$s"]) . '.' . $this->r->sec(self::UZANTILAR);
    }

    private function dogumTarihi(): DateTimeImmutable
    {
        $bugun = new DateTimeImmutable('today');
        $enGec = $bugun->modify('-' . self::EN_KUCUK_YAS . ' years')->getTimestamp();
        $enErken = $bugun->modify('-' . (self::EN_BUYUK_YAS + 1) . ' years +1 day')->getTimestamp();
        return (new DateTimeImmutable('@' . $this->r->sayi($enErken, $enGec)))
            ->setTimezone($bugun->getTimezone())
            ->setTime(0, 0);
    }

    private function biyografi(string $yas, string $meslek, string $ilgi, string $sehir): string
    {
        return $this->r->sec([
            "$yas yaşında, $meslek",
            "$yas yaşında, $meslek, $ilgi",
            "$yas yaşında, $ilgi, $meslek. $sehir",
            "$ilgi, $meslek. $yas yaşında.",
            "$meslek. $ilgi. $yas yaşında.",
        ]);
    }
}
