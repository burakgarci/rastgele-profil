<?php

/*
 * Varsayılan ayarlar. Sunucuya özel değişiklikleri bu dosyaya değil,
 * config.local.php'ye yazın (yalnızca değişen anahtarlar; dosya git'e girmez):
 *
 *   <?php return ['hiz_siniri' => ['anahtarlar' => ['gizli-bot-anahtari']]];
 */

return [
    'azami_adet' => 250,
    'eposta_alan_adi' => 'example.com',
    'resim_url_tabani' => 'https://ik.imagekit.io/brk/',
    'veri_dizini' => __DIR__ . '/data',
    'foto_dizini' => __DIR__ . '/public/photos',
    'saat_dilimi' => 'Europe/Istanbul',
    'zaman_asimi' => 10,  // saniye
    'cors' => '*',        // null: CORS başlığı gönderilmez

    'hiz_siniri' => [
        'etkin' => true,
        'saniyede' => 10,     // IP başına saniyedeki istek; 0 = sınırsız
        'patlama' => 20,      // kısa süreli en fazla istek
        'gunluk' => 0,        // IP başına günlük tavan; 0 = kapalı
        'beyaz_liste' => [],  // sınırsız IP'ler
        'anahtarlar' => [],   // sınırsız API anahtarları (X-Api-Anahtari başlığı ya da ?anahtar=)
        'ip_basligi' => null, // ör. Cloudflare arkasında 'HTTP_CF_CONNECTING_IP'
        'depo' => 'otomatik', // otomatik (APCu varsa APCu) | dosya
        'dizin' => __DIR__ . '/depo/hiz-siniri',
    ],
];
