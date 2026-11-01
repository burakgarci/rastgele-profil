<?php

namespace RastgeleProfil;

final class Yanit
{
    /** @var int */
    public $durum;
    /** @var array<string, string> */
    public $basliklar;
    /** @var string */
    public $govde;

    public function __construct(int $durum, string $govde = '', array $basliklar = [])
    {
        $this->durum = $durum;
        $this->govde = $govde;
        $this->basliklar = $basliklar;
    }

    /** @param mixed $veri */
    public static function json(int $durum, $veri, array $basliklar = []): self
    {
        $json = json_encode($veri, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if ($json === false) {
            throw new \RuntimeException('JSON üretilemedi: ' . json_last_error_msg());
        }
        return new self($durum, $json, ['Content-Type' => 'application/json; charset=utf-8'] + $basliklar);
    }

    public static function hata(int $durum, string $mesaj, array $basliklar = []): self
    {
        return self::json($durum, ['hata' => $mesaj], $basliklar);
    }

    public function gonder(): void
    {
        http_response_code($this->durum);
        foreach ($this->basliklar as $ad => $deger) {
            header("$ad: $deger");
        }
        echo $this->govde;
    }
}
