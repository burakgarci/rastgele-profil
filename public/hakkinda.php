<?php
$ayar = require __DIR__ . '/../bootstrap.php';

$sayfa = 'hakkinda';
$baslik = 'Hakkında, Rastgele Profil';
$aciklama = 'Rastgele Profil Oluşturucu hakkında: kim geliştirdi, veriler nereden geliyor ve değişiklik geçmişi.';
$degisiklikler = RastgeleProfil\Degisiklikler::oku(__DIR__ . '/../CHANGELOG.md');

require __DIR__ . '/../sablonlar/ust.php';
$kod = function ($metin) use ($e) {
    return preg_replace('/`([^`]+)`/', '<code>$1</code>', $e($metin));
};
?>
<section class="hero">
<h1>Hakkında</h1>
<p>Rastgele Profil Oluşturucu, tasarımcılar ve geliştiriciler için rastgele Türkçe profil bilgileri üreten basit bir araç.</p>
</section>

<div class="belge">
<section class="bolum">
<h2>Proje</h2>
<div>
<p><a class="link" href="https://burakgarci.net">Burak Garcı</a> tarafından geliştirildi. Kaynak kodu <a class="link" href="https://github.com/burakgarci/rastgele-profil">GitHub</a> üzerinde, MIT lisansıyla açık.</p>
<p>Tüm bilgiler hayalidir; gerçek kişilerle benzerlikler tesadüftür. Profil fotoğrafları <a class="link" href="https://unsplash.com">unsplash.com</a>'da CC0 1.0 Evrensel lisansıyla yayımlanan fotoğraflardan seçilmiştir.</p>
<p>Görüş ve önerileriniz için <a class="link" href="https://burakgarci.net/iletisim">burakgarci.net/iletisim</a>.</p>
</div>
</section>

<section class="bolum">
<h2>Destek olmak isterseniz</h2>
<div>
<div class="alanlar">
<div class="satir"><span class="e">BTC</span><span class="d mono">1GKGCkBYfAU5mGttLsN5imPhSmuJnKtnTv</span><button type="button" class="kopya" aria-label="BTC adresini kopyala">Kopyala</button></div>
<div class="satir"><span class="e">ETH</span><span class="d mono">0xdbec89337c699facf749b3fdbaef778d30b86313</span><button type="button" class="kopya" aria-label="ETH adresini kopyala">Kopyala</button></div>
</div>
</div>
</section>

<section class="bolum" id="degisiklikler">
<h2>Değişiklikler</h2>
<div>
<ul class="list">
<?php foreach ($degisiklikler as $d) : ?>
<li>
<span><?= $e($d['baslik']) ?></span>
<span class="d"><?= $e($d['tarih']) ?></span>
<?php if ($d['maddeler']) : ?>
<ul><?php foreach ($d['maddeler'] as $m) : ?><li><?= $kod($m) ?></li><?php endforeach; ?></ul>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>
</div>
</section>
</div>
<?php require __DIR__ . '/../sablonlar/alt.php'; ?>
