<?php

/** Ortak başlangıç: autoload + ayarlar. Composer kurulu olmasa da çalışır. */

if (is_file(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    spl_autoload_register(function (string $sinif) {
        $onek = 'RastgeleProfil\\';
        if (strncmp($sinif, $onek, strlen($onek)) === 0) {
            $dosya = __DIR__ . '/src/' . str_replace('\\', '/', substr($sinif, strlen($onek))) . '.php';
            if (is_file($dosya)) {
                require $dosya;
            }
        }
    });
}

error_reporting(E_ALL);
ini_set('display_errors', '0');

$ayar = require __DIR__ . '/config.php';
if (is_file(__DIR__ . '/config.local.php')) {
    $birlestir = function (array $a, array $b) use (&$birlestir): array {
        foreach ($b as $k => $v) {
            // İlişkisel diziler derinlemesine birleşir, listeler (beyaz_liste vb.) tamamen değişir.
            $a[$k] = is_array($v) && isset($a[$k]) && is_array($a[$k]) && $v !== array_values($v)
                ? $birlestir($a[$k], $v) : $v;
        }
        return $a;
    };
    $ayar = $birlestir($ayar, require __DIR__ . '/config.local.php');
}

date_default_timezone_set($ayar['saat_dilimi']);

return $ayar;
