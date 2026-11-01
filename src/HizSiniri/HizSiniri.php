<?php

namespace RastgeleProfil\HizSiniri;

/**
 * IP başına token bucket + isteğe bağlı günlük tavan.
 * Ayarlar: etkin, saniyede, patlama, gunluk, beyaz_liste, anahtarlar (0 = sınırsız).
 */
final class HizSiniri
{
    /** @var array */
    private $ayar;
    /** @var Depo */
    private $depo;
    /** @var callable(): float */
    private $saat;

    public function __construct(array $ayar, Depo $depo, ?callable $saat = null)
    {
        $this->ayar = $ayar + ['etkin' => true, 'saniyede' => 10, 'patlama' => 20, 'gunluk' => 0, 'beyaz_liste' => [], 'anahtarlar' => []];
        $this->depo = $depo;
        $this->saat = $saat ?? function (): float {
            return microtime(true);
        };
    }

    public function kontrol(string $ip, ?string $anahtar = null): Sonuc
    {
        $a = $this->ayar;
        $saniyede = (float) $a['saniyede'];
        $gunluk = (int) $a['gunluk'];

        if (!$a['etkin'] || ($saniyede <= 0 && $gunluk <= 0)
            || in_array($ip, (array) $a['beyaz_liste'], true) || $this->anahtarGecerli($anahtar)) {
            return Sonuc::sinirsiz();
        }

        $simdi = ($this->saat)();
        $kapasite = max(1.0, (float) ($a['patlama'] ?: $saniyede));

        return $this->depo->guncelle($ip, function (?array $d) use ($simdi, $saniyede, $gunluk, $kapasite) {
            $gun = date('Y-m-d', (int) $simdi);
            $jeton = $d === null ? $kapasite : min($kapasite, $d['jeton'] + ($simdi - $d['zaman']) * $saniyede);
            $sayi = ($d['gun'] ?? '') === $gun ? (int) $d['sayi'] : 0;

            $s = new Sonuc();
            $s->limit = $saniyede > 0 ? (int) $saniyede : null;
            if ($gunluk > 0 && $sayi >= $gunluk) {
                $s->izin = false;
                $s->bekle = max(1, strtotime('tomorrow', (int) $simdi) - (int) $simdi);
            } elseif ($saniyede > 0 && $jeton < 1) {
                $s->izin = false;
                $s->bekle = (int) ceil((1 - $jeton) / $saniyede);
            } else {
                $s->izin = true;
                $jeton -= $saniyede > 0 ? 1 : 0;
                $sayi++;
            }
            $s->kalan = $saniyede > 0 ? (int) floor($jeton) : null;

            return [['jeton' => $jeton, 'zaman' => $simdi, 'gun' => $gun, 'sayi' => $sayi], $s];
        });
    }

    private function anahtarGecerli(?string $anahtar): bool
    {
        if ($anahtar === null || $anahtar === '') {
            return false;
        }
        foreach ((array) $this->ayar['anahtarlar'] as $gecerli) {
            if (is_string($gecerli) && $gecerli !== '' && hash_equals($gecerli, $anahtar)) {
                return true;
            }
        }
        return false;
    }
}
