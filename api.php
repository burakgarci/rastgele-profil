<?php
header("Content-type: application/json; charset=utf-8");

// Hataları günlüğe kaydetme
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Hata günlüğü dosyası
$logDosyasi = 'hatalar.log';

// Profil sınıfını dahil etme
require_once 'classes/Profil.php';

// Parametreleri al
$adet = $_GET['adet'] ?? null;
$cinsiyet = $_GET['cinsiyet'] ?? null;

// API çağrısı
try {
    $profil = new Profil();
    $apiSonuc = $profil->api($adet, $cinsiyet);

    // Sonucu JSON formatında çıktı verme
    echo json_encode($apiSonuc);
} catch (Exception $e) {
    // Hata mesajını günlüğe kaydetme
    error_log("Hata: " . $e->getMessage(), 3, $logDosyasi);

    // Hata durumunda genel bir hata mesajı JSON formatında çıktı verme
    echo json_encode(['hata' => 'İşlem sırasında bir hata oluştu.']);
}
