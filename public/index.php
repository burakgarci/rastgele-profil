<?php
$ayar = require __DIR__ . '/../bootstrap.php';

$sayfa = 'ana';
$baslik = 'Rastgele Profil Oluşturucu';
$aciklama = 'Tasarımcılar ve geliştiriciler için rastgele Türkçe profil oluşturucu: isim, kullanıcı adı, e-posta, adres, telefon ve dahası. Ücretsiz JSON API.';

$gruplar = [
    'Hesap' => [['tam_isim', 'Ad soyad'], ['kullanici_adi', 'Kullanıcı adı'], ['eposta', 'E-posta'], ['sifre', 'Şifre', true]],
    'Kişisel' => [['dogum_tarihi', 'Doğum tarihi'], ['telefon_numarasi', 'Telefon', true], ['meslek', 'Meslek'], ['ilgi_alani', 'İlgi alanı'], ['biyografi', 'Biyografi'], ['web_adresi', 'Web adresi']],
    'Adres' => [['adres', 'Adres'], ['sehir', 'Şehir']],
    'Görsel' => [['profil_resmi', 'Profil resmi', true]],
];

require __DIR__ . '/../sablonlar/ust.php';
?>
<section class="hero">
<h1>Rastgele <span>Türkçe</span> profil</h1>
<p>Tasarım ve test için gerçekçi ama hayali kişi bilgileri. Her alan tek tıkla kopyalanır.</p>
</section>

<main class="duzen yukleniyor" id="profil">
<section class="yan kart" aria-label="Profil">
<div class="avatar" id="avatar" aria-hidden="true"></div>
<div class="kimlik">
<h2 id="ad">&nbsp;</h2>
<p id="ozet">&nbsp;</p>
<p id="yer">&nbsp;</p>
</div>
<p class="bio" id="bio">&nbsp;</p>
<div class="sekmeler" role="group" aria-label="Cinsiyet">
<button type="button" data-cinsiyet="" aria-pressed="true">Rastgele</button>
<button type="button" data-cinsiyet="kadin" aria-pressed="false">Kadın</button>
<button type="button" data-cinsiyet="erkek" aria-pressed="false">Erkek</button>
</div>
<button type="button" class="dugme yeni" data-yeni>Yeni profil üret <kbd>Boşluk</kbd></button>
<button type="button" class="baglanti" id="json-kopyala">JSON olarak kopyala</button>
<p class="durum" id="durum" role="status" aria-live="polite"></p>
<noscript><p class="durum">Bu sayfa JavaScript ile çalışır. Profilleri <a class="link" href="api.php">API</a> üzerinden de alabilirsiniz.</p></noscript>
</section>

<div class="ana">
<?php foreach ($gruplar as $grup => $alanlar) : ?>
<section>
<h2 class="label"><?= $grup ?></h2>
<div class="alanlar">
<?php foreach ($alanlar as $a) : ?>
<div class="satir">
<span class="e"><?= $a[1] ?></span>
<span class="d<?= empty($a[2]) ? '' : ' mono' ?>" data-alan="<?= $a[0] ?>">&nbsp;</span>
<button type="button" class="kopya" aria-label="<?= $a[1] ?> kopyala">Kopyala</button>
</div>
<?php endforeach; ?>
</div>
</section>
<?php endforeach; ?>
</div>
</main>

<div class="alt-cubuk">
<button type="button" class="dugme" data-yeni>Yeni profil üret</button>
</div>
<?php require __DIR__ . '/../sablonlar/alt.php'; ?>
