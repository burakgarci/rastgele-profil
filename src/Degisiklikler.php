<?php

namespace RastgeleProfil;

/** CHANGELOG.md'yi okur: "## 2026-11-02 — Başlık" başlıkları ve "- madde" satırları. */
final class Degisiklikler
{
    private const AYLAR = [1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];

    /** @return array<int, array{tarih: string, baslik: string, maddeler: string[]}> */
    public static function oku(string $dosya): array
    {
        $liste = [];
        foreach (is_readable($dosya) ? file($dosya) : [] as $satir) {
            $satir = rtrim($satir);
            if (preg_match('/^## (\d{4})-(\d{2})(?:-(\d{2}))?\s+[—–-]\s+(.+)$/u', $satir, $m)) {
                $ay = self::AYLAR[(int) $m[2]] ?? '';
                $liste[] = [
                    'tarih' => ($m[3] !== '' ? (int) $m[3] . ' ' : '') . "$ay {$m[1]}",
                    'baslik' => $m[4],
                    'maddeler' => [],
                ];
            } elseif ($liste && preg_match('/^- (.+)$/u', $satir, $m)) {
                $liste[count($liste) - 1]['maddeler'][] = $m[1];
            }
        }
        return $liste;
    }
}
