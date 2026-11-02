<?php
$ayar = require __DIR__ . '/../bootstrap.php';

$sayfa = 'api';
$baslik = 'API belgeleri, Rastgele Profil';
$aciklama = 'Rastgele Türkçe profil üreten ücretsiz JSON API: parametreler, yanıt biçimi, hata kodları ve kullanım sınırı.';
$hs = $ayar['hiz_siniri'];
$limitli = $hs['etkin'] && $hs['saniyede'] > 0;
$azami = (int) $ayar['azami_adet'];

require __DIR__ . '/../sablonlar/ust.php';
?>
<section class="hero">
<h1>API</h1>
<p>Parametreleri seç, isteği gönder, yanıtı olduğu gibi gör. Bu sitedeki oluşturucu da aynı API'yi kullanır.</p>
</section>

<main class="duzen">
<form class="yan" id="istek" style="display: grid; gap: 16px" novalidate>
<h2 class="label" style="margin: 0">İstek</h2>
<div class="sekmeler" role="group" aria-label="Cinsiyet">
<button type="button" data-cinsiyet="" aria-pressed="true">Rastgele</button>
<button type="button" data-cinsiyet="kadin" aria-pressed="false">Kadın</button>
<button type="button" data-cinsiyet="erkek" aria-pressed="false">Erkek</button>
</div>
<div class="sayac">
<label for="adet" style="font-size: 15px; color: var(--muted)">Adet</label>
<div>
<button type="button" id="adet-azalt" aria-label="Azalt">−</button>
<input id="adet" type="text" inputmode="numeric" placeholder="–" autocomplete="off">
<button type="button" id="adet-artir" aria-label="Artır">+</button>
</div>
</div>
<label for="alan" class="gizli">E-posta alan adı</label>
<input id="alan" class="alan" type="text" placeholder="E-posta alan adı (<?= $e($ayar['eposta_alan_adi']) ?>)" autocomplete="off" autocapitalize="off" spellcheck="false">
<label for="seed" class="gizli">Seed</label>
<input id="seed" class="alan" type="text" inputmode="numeric" placeholder="Seed (isteğe bağlı)" autocomplete="off">
<button type="submit" class="dugme">İsteği gönder</button>
<p class="not" id="kalan"></p>
</form>

<section class="ana" aria-label="Yanıt" style="gap: 10px; align-content: start">
<div class="adres" aria-label="İstek adresi"><span class="y">GET</span><span class="u" id="istek-url">/api.php</span><button type="button" class="mini" id="url-kopyala">Kopyala</button></div>
<div class="yanit-ust" style="border-bottom: 1px solid var(--faint)">
<div class="sekmeler" role="tablist" aria-label="Görünüm">
<button type="button" role="tab" data-gorunum="json" aria-selected="true">JSON</button>
<button type="button" role="tab" data-gorunum="profil" aria-selected="false">Profil</button>
<button type="button" role="tab" data-gorunum="curl" aria-selected="false">cURL</button>
</div>
<div class="bilgi" aria-live="polite"><span id="kod-durum"></span><span id="kod-sure"></span></div>
</div>
<div class="cikti-kutu">
<div class="kod-arac"><button type="button" class="mini" id="cikti-kopyala">Kopyala</button><button type="button" class="mini" id="cikti-indir">İndir</button></div>
<pre class="kod" id="cikti" tabindex="0"></pre>
<div class="alanlar" id="profil-gorunum" hidden></div>
</div>
</section>
</main>

<div class="belge" id="belgeler">
<section class="bolum">
<h2>Kullanım</h2>
<div>
<p>Tek adres, GET isteği. Çıktı JSON, karakter kodlaması UTF-8.</p>
<pre><code>https://rp.burakgarci.net/api.php</code></pre>
<p>Parametresiz istek tek bir profil nesnesi döner. <code>adet</code> verildiğinde (<code>adet=1</code> dahil) yanıt her zaman dizidir.</p>
</div>
</section>

<section class="bolum">
<h2>Parametreler</h2>
<div>
<table>
<thead><tr><th>Parametre</th><th>Değer</th><th>Açıklama</th></tr></thead>
<tbody>
<tr><td><code>cinsiyet</code></td><td><code>kadın</code>, <code>kadin</code>, <code>erkek</code></td><td>Büyük/küçük harf ve Türkçe karakter fark etmez. Boş, <code>uniseks</code> ya da <code>rastgele</code> rastgele seçer.</td></tr>
<tr><td><code>adet</code></td><td><code>1</code>–<code><?= $azami ?></code></td><td>Kaç profil üretileceği. <?= $azami ?> üstü <?= $azami ?>'ye indirilir.</td></tr>
<tr><td><code>eposta_alan_adi</code></td><td>ör. <code>firmam.com</code></td><td>E-posta adreslerinin alan adı. Varsayılan <code><?= $e($ayar['eposta_alan_adi']) ?></code>.</td></tr>
<tr><td><code>seed</code></td><td><code>0</code>–<code>2147483647</code></td><td>Aynı seed aynı gün içinde aynı sonucu verir.</td></tr>
</tbody>
</table>
</div>
</section>

<section class="bolum">
<h2>Alanlar</h2>
<div>
<p>Her profilde aynı sırayla 17 alan bulunur, hepsi metindir: <code>isim</code>, <code>soyisim</code>, <code>tam_isim</code>, <code>cinsiyet</code>, <code>sehir</code>, <code>adres</code>, <code>meslek</code>, <code>eposta</code>, <code>kullanici_adi</code>, <code>sifre</code>, <code>dogum_tarihi</code> (gg.aa.yyyy), <code>yas</code>, <code>telefon_numarasi</code>, <code>ilgi_alani</code>, <code>biyografi</code>, <code>profil_resmi</code>, <code>web_adresi</code>.</p>
<p>Veriler hayalidir. Telefonlar kullanılmayan <code>+90 851</code> önekiyle, e-postalar <code>example.com</code> ile üretilir.</p>
</div>
</section>

<section class="bolum">
<h2>Hatalar ve kullanım sınırı</h2>
<div>
<p><?= $limitli ? 'Makul kullanım sınırı IP başına saniyede ' . (int) $hs['saniyede'] . ' istektir (kısa süreli en fazla ' . (int) ($hs['patlama'] ?: $hs['saniyede']) . ').' : 'Şu an istek sınırı yoktur.' ?> Her yanıtta <code>X-RateLimit-Limit</code> ve <code>X-RateLimit-Remaining</code> başlıkları bulunur. Hatalarda gövde <code>{"hata": "..."}</code> biçimindedir.</p>
<table>
<thead><tr><th>Durum</th><th>Ne zaman</th></tr></thead>
<tbody>
<tr><td><code>400</code></td><td>Geçersiz parametre (ör. <code>adet=abc</code>, <code>cinsiyet=foo</code>)</td></tr>
<tr><td><code>405</code></td><td>GET dışında bir metot</td></tr>
<tr><td><code>429</code></td><td>Kullanım sınırı aşıldı; <code>Retry-After</code> kaç saniye bekleneceğini söyler</td></tr>
<tr><td><code>500</code></td><td>Sunucu hatası</td></tr>
</tbody>
</table>
</div>
</section>

<section class="bolum">
<h2>Profil resimleri hakkında</h2>
<div>
<p>Resimler bu sitenin sunucusundan verilir ve bant genişliği sınırlıdır. Resim adreslerini uygulamanızda sürekli kullanmak yerine, kullanacağınız resimleri indirip kendi sunucunuzda saklayın.</p>
</div>
</section>
</div>
<?php require __DIR__ . '/../sablonlar/alt.php'; ?>
