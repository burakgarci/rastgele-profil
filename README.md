## Hakkında

Rastgele Profil Oluşturucu tasarımcılar ve geliştiriciler için Türkçe rastgele profil bilgileri oluşturmaya yarayan basit bir araçtır. [Burak Garcı](http://burakgarci.net) tarafından geliştirilmiştir.

- Profil fotoğrafları [unsplash.com](http://unsplash.com) sitesi üzerinde **CC0 1.0 Evrensel** lisansı altında yayınlanan fotoğraflardan seçilmiştir.
- [burakgarci.net/iletisim](http://burakgarci.net/iletisim) adresinden geribildirimde bulunabilirsiniz.

- Bir şeyler ısmarlak ister misiniz? [Evet](http://paypal.me/garci) - [Hayır](https://google.com.tr/search?q=isteyenin+bir+yüzü+vermeyenin+iki+yüzü+kara)

## API

Bir defada en fazla 250 farklı kullanıcı profili oluşturabilirsiniz. Çıktı JSON formatındadır. Makul kullanım sınırı IP başına saniyede 10 istektir (kısa süreli en fazla 20).

### Kullanım

	https://rp.burakgarci.net/api.php

**Çıktı:**

	{
	    "isim": "Ayhan",
	    "soyisim": "Koç",
	    "tam_isim": "Ayhan Koç",
	    "cinsiyet": "erkek",
	    "sehir": "Osmaneli, Bilecik",
	    "adres": "İstiklal Bulvarı No:663 Osmaneli, Bilecik",
	    "meslek": "Akademik Direktör",
	    "eposta": "kocayhan@example.com",
	    "kullanici_adi": "ayhan_koc",
	    "sifre": "f6CT21ckEq",
	    "dogum_tarihi": "08.12.1987",
	    "yas": "38",
	    "telefon_numarasi": "+908512519224",
	    "ilgi_alani": "Jimnastik izleyicisi",
	    "biyografi": "Jimnastik izleyicisi, Akademik Direktör. 38 yaşında.",
	    "profil_resmi": "https://rp.burakgarci.net/photos/erkek/7.jpg",
	    "web_adresi": "ayhankoc.net.tr"
	}

### Parametreler

| Parametre | Değer | Açıklama |
|---|---|---|
| `cinsiyet` | `kadın` / `kadin`, `erkek` | Büyük/küçük harf ve Türkçe karakter fark etmez. Boş, `uniseks` ya da `rastgele` rastgele seçer. |
| `adet` | `1`–`250` | Verildiğinde yanıt her zaman dizidir (`adet=1` dahil). 250 üstü 250'ye indirilir. |
| `eposta_alan_adi` | ör. `firmam.com` | E-posta adreslerinin alan adı. Varsayılan `example.com`. |
| `seed` | `0`–`2147483647` | Aynı seed aynı gün içinde aynı sonucu verir. |

**Cinsiyet seçerek oluşturma:**

	https://rp.burakgarci.net/api.php?cinsiyet=kadin

**Birden fazla profil oluşturma (en fazla 250):**

	https://rp.burakgarci.net/api.php?adet=20

**Kendi alan adınızla e-posta:**

	https://rp.burakgarci.net/api.php?eposta_alan_adi=firmam.com

### Hatalar ve sınırlar

Hatalarda gövde her zaman `{"hata": "..."}` biçimindedir.

| Durum | Ne zaman |
|---|---|
| `400` | Geçersiz parametre (ör. `adet=abc`, `cinsiyet=foo`) |
| `405` | GET dışında bir metot |
| `429` | Kullanım sınırı aşıldı; `Retry-After` başlığı kaç saniye sonra denenebileceğini söyler |
| `500` | Sunucu hatası |

Her yanıtta `X-RateLimit-Limit` ve `X-RateLimit-Remaining` başlıkları bulunur.

## Kurulum

PHP 7.4 veya üstü yeterlidir (8.x önerilir). Sunucuda Composer veya terminal gerekmez.

```
public/        alt alan adının kök dizini: sayfalar, api.php, saglik.php, assets/, photos/
src/           motor ve HTTP katmanı
sablonlar/     sayfaların ortak üst ve alt kısmı
data/          TXT veri kaynakları
depo/          hız sınırı sayaçları (PHP yazabilmeli)
config.php     varsayılan ayarlar (sunucuya özel olanlar config.local.php'ye)
CHANGELOG.md   değişiklikler; Hakkında sayfasındaki liste buradan okunur
```

- Alt alan adının kök dizinini `public/` yapın. Panel izin vermiyorsa proje kökündeki `.htaccess` istekleri `public/`'e yönlendirir.
- `tests/`, `vendor/`, `composer.json`, `phpunit.xml.dist` ve `.md` dosyalarının (CHANGELOG.md hariç) yüklenmesi gerekmez.
- Yüklemeden sonra `saglik.php` veri dosyalarını, fotoğrafları ve sayaç deposunu kontrol eder; sorun varsa `503` döner.

### Ayarlar

Varsayılanlar `config.php` içindedir. Sunucuya özel değişiklikleri `config.local.php` dosyasına yazın (git'e girmez):

```php
<?php
return [
    'hiz_siniri' => [
        'saniyede' => 10,               // 0 = sınırsız
        'gunluk' => 0,                  // 0 = kapalı
        'anahtarlar' => ['gizli-anahtar'], // X-Api-Anahtari başlığıyla sınırsız erişim
        'beyaz_liste' => ['1.2.3.4'],   // sınırsız IP'ler
    ],
];
```

### Geliştirme

```sh
composer install
composer test
```
