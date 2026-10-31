<?php
header("Content-type: application/json; charset=utf-8");

// Hatalar ekrana basılmaz, sunucunun PHP hata günlüğüne yazılır.
// Deprecation uyarıları Etap 1'de kod güncellenene kadar kapalı.
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);

require_once 'classes/Profil.php';

$adet = $_GET['adet'] ?? null;
$cinsiyet = $_GET['cinsiyet'] ?? null;

try {
    $profil = new Profil();
    // Profil::api() zaten JSON string döndürür.
    echo $profil->api($adet, $cinsiyet);
} catch (Throwable $e) {
    error_log('rastgele-profil: ' . $e->getMessage());
    echo json_encode(['hata' => 'İşlem sırasında bir hata oluştu.'], JSON_UNESCAPED_UNICODE);
}
