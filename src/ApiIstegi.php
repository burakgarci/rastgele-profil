<?php

namespace RastgeleProfil;

/** api.php sorgu parametrelerini doğrular ve normalize eder. */
final class ApiIstegi
{
    /** @var int|null null: parametresiz istek, tek nesne döner */
    public $adet;
    /** @var string|null "erkek", "kadın" ya da null (rastgele) */
    public $cinsiyet;
    /** @var string */
    public $epostaAlanAdi;
    /** @var int|null */
    public $seed;

    /** Rastgele cinsiyet anlamına gelen değerler (arayüzdeki üniseks butonu dahil). */
    private const RASTGELE = ['', 'uniseks', 'rastgele'];

    /** @param array<string, mixed> $sorgu */
    public static function coz(array $sorgu, int $azamiAdet, string $varsayilanAlanAdi): self
    {
        $i = new self();
        $i->adet = self::adet($sorgu['adet'] ?? null, $azamiAdet);
        $i->cinsiyet = self::cinsiyet($sorgu['cinsiyet'] ?? null);
        $i->epostaAlanAdi = self::alanAdi($sorgu['eposta_alan_adi'] ?? null, $varsayilanAlanAdi);
        $i->seed = self::seed($sorgu['seed'] ?? null);
        return $i;
    }

    private static function metin($deger, string $ad): ?string
    {
        if ($deger === null) {
            return null;
        }
        if (!is_string($deger)) {
            throw new GecersizIstek("'$ad' parametresi tek bir değer olmalı.");
        }
        return trim($deger);
    }

    private static function adet($deger, int $azami): ?int
    {
        $deger = self::metin($deger, 'adet');
        if ($deger === null || $deger === '') {
            return null;
        }
        if (!ctype_digit($deger) || (int) $deger < 1) {
            throw new GecersizIstek("'adet' 1 ile $azami arasında bir tam sayı olmalı.");
        }
        return strlen($deger) > 6 ? $azami : min((int) $deger, $azami);
    }

    private static function cinsiyet($deger): ?string
    {
        $deger = self::metin($deger, 'cinsiyet');
        if ($deger === null) {
            return null;
        }
        $a = Metin::anahtar($deger);
        if (in_array($a, self::RASTGELE, true)) {
            return null;
        }
        if ($a === 'kadin') {
            return 'kadın';
        }
        if ($a === 'erkek') {
            return 'erkek';
        }
        throw new GecersizIstek("'cinsiyet' kadın (kadin) ya da erkek olmalı.");
    }

    private static function alanAdi($deger, string $varsayilan): string
    {
        $deger = self::metin($deger, 'eposta_alan_adi');
        if ($deger === null || $deger === '') {
            return $varsayilan;
        }
        $deger = strtolower($deger);
        if (strlen($deger) > 253 || !preg_match('/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,63}$/', $deger)) {
            throw new GecersizIstek("'eposta_alan_adi' geçerli bir alan adı olmalı (ör. firmam.com).");
        }
        return $deger;
    }

    private static function seed($deger): ?int
    {
        $deger = self::metin($deger, 'seed');
        if ($deger === null || $deger === '') {
            return null;
        }
        if (!ctype_digit($deger) || strlen($deger) > 10 || (int) $deger > 2147483647) {
            throw new GecersizIstek("'seed' 0 ile 2147483647 arasında bir tam sayı olmalı.");
        }
        return (int) $deger;
    }
}
