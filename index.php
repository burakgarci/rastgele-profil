<?php
require_once 'classes/Profil.php';
$p = new Profil();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
	<meta charset="UTF-8">
	<title>Rastgele Profil Oluşturucu</title>
	<meta name="robots" content="noimageindex">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Tasarımcılar için rastgele profil oluşturucu. İsim, soyisim, biyografi, kullanıcı adı ve dahası." />
	<meta name="keywords" content="ad soyad oluşturucu, fake isim soyisim, isim üretici, ismi oluşturma, random kullanıcı adı, rastgele adres, rastgele bilgiler, rastgele eposta, rastgele erkek isim soyisim, rastgele erkek ismi, rastgele fotoğraf, rastgele gmail, rastgele isim, rastgele isim listesi, rastgele isim seçici, rastgele kullanıcı adı, rastgele link, rastgele mail, rastgele nick, rastgele numara, rastgele numara bulma, rastgele numara seç, rastgele numara üretici, rastgele oyuncu ismi, rastgele parola, rastgele üniversite, rastgele şehir, rastgele şifre, türkçe isim veritabanı">
	<link href='//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic&amp;subset=latin,greek-ext' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="assets/style.css">
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5770696223060387" crossorigin="anonymous"></script>
</head>

<body>

	<header>
		<div class="tutucu">
			<nav>
				<a href="#erkek"><button class="erkek-btn" data-cinsiyet="erkek">♂</button></a>
				<a href="#kadin"><button class="kadin-btn" data-cinsiyet="kadin">♀</button></a>
				<a href="#uniseks"><button class="uniseks-btn" data-cinsiyet="üniseks">⚥</button></a>
			</nav>
		</div><!-- /.tutucu -->
	</header>

	<main>
		<div class="tutucu">
			<div id="profil">
				<img id="profil_resmi" src="<?php echo $p->get_profil_resmi(); ?>?tr=h-150,w-150,q-80" alt="<?php echo $p->get_tam_isim() ?> avatarı">

				<div class="clear"></div>

				<div class="pdlt"><label for="tam_isim">İsim</label></div>
				<div class="pdit">
					<input readonly spellcheck="false" type="text" id="tam_isim" value="<?php echo $p->get_tam_isim() ?>">
				</div>

				<div class="pdlt"><label for="sehir">Şehir</label></div>
				<div class="pdit">
					<input readonly spellcheck="false" type="text" id="sehir" value="<?php echo $p->get_sehir() ?>">
				</div>

				<div class="pdlt"><label for="biyografi">Biyografi</label></div>
				<div class="pdit">
					<input readonly spellcheck="false" type="text" id="biyografi" value="<?php echo $p->get_biyografi() ?>">
				</div>

				<div class="pdlt"><label for="kullanici_adi">Kullanıcı adı</label></div>
				<div class="pdit">
					<input readonly spellcheck="false" type="text" id="kullanici_adi" value="<?php echo $p->get_kullanici_adi() ?>">
				</div>

				<div class="pdlt"><label for="sifre">Şifre</label></div>
				<div class="pdit">
					<input readonly spellcheck="false" type="text" id="sifre" value="<?php echo $p->get_sifre() ?>">
				</div>

				<div class="pdlt"><label for="eposta">E-posta</label></div>
				<div class="pdit">
					<input readonly spellcheck="false" type="text" id="eposta" value="<?php echo $p->get_eposta() ?>">
				</div>

				<div id="daha">

					<div class="pdlt"><label for="adres">Adres</label></div>
					<div class="pdit">
						<input readonly spellcheck="false" type="text" id="adres" value="<?php echo $p->get_adres() ?>">
					</div>

					<div class="pdlt"><label for="meslek">Meslek</label></div>
					<div class="pdit">
						<input readonly spellcheck="false" type="text" id="meslek" value="<?php echo $p->get_meslek() ?>">
					</div>

					<div class="pdlt"><label for="dogum_tarihi">Doğum tarihi</label></div>
					<div class="pdit">
						<input readonly spellcheck="false" type="text" id="dogum_tarihi" value="<?php echo $p->get_dogum_tarihi() ?>">
					</div>

					<div class="pdlt"><label for="yas">Yaş</label></div>
					<div class="pdit">
						<input readonly spellcheck="false" type="text" id="yas" value="<?php echo $p->get_yas() ?>">
					</div>

					<div class="pdlt"><label for="telefon_numarasi">Telefon</label></div>
					<div class="pdit">
						<input readonly spellcheck="false" type="text" id="telefon_numarasi" value="<?php echo $p->get_telefon_numarasi() ?>">
					</div>

					<div class="pdlt"><label for="ilgi_alani">İlgi alanı</label></div>
					<div class="pdit">
						<input readonly spellcheck="false" type="text" id="ilgi_alani" value="<?php echo $p->get_ilgi_alani() ?>">
					</div>

					<div class="pdlt"><label for="profil_resmi_url">Profil resmi</label></div>
					<div class="pdit">
						<input readonly spellcheck="false" type="text" id="profil_resmi_url" value="<?php echo $p->get_profil_resmi() ?>">
					</div>

					<div class="pdlt"><label for="web_adresi">Web adresi</label></div>
					<div class="pdit">
						<input readonly spellcheck="false" type="text" id="web_adresi" value="<?php echo $p->get_web_adresi() ?>">
					</div>

					<div class="clear"></div>

				</div><!-- /#daha -->

				<div class="clear"></div>
				<div id="daha-ak" class="arrow down">DAHA FAZLA DETAY...</div><!-- /#profil -->
			</div><!-- /#profil -->

			<div id="detaylar">
				<div id="hakkinda" class="modal">
					<h2 class="modal-baslik">Hakkında</h2>
					<p>Rastgele Profil Oluşturucu tasarımcılar için rastgele profil bilgileri oluşturmaya yarayan basit bir araçtır. <a href="//burakgarci.net">Burak Garcı</a> tarafından geliştirilmiştir.</p>
					<p>Profil fotoğrafları <a href="//unsplash.com">unsplash.com</a> sitesi üzerinde <strong>CC0 1.0 Evrensel</strong> lisansı altında yayınlanan fotoğraflardan seçilmiştir.</p>
					<p>Ayrıca bu web sitesi açık kaynak kodludur. <a target="_blank" href="//github.com/burakgarci">GitHub</a> üzerinden kaynak kodları görüntüleyebilirsiniz.</p>
					<p><a href="//burakgarci.net/iletisim">burakgarci.net/iletisim</a> adresinden geribildirimde bulunabilirsiniz.</p>
					<p>
						<strong>BTC</strong>: 1GKGCkBYfAU5mGttLsN5imPhSmuJnKtnTv
						<br>
						<strong>ETH</strong>: 0xdbec89337c699facf749b3fdbaef778d30b86313
					</p>
					<!-- <p>Bir şeyler ısmarlak ister misiniz? <a href="//paypal.me/garci">Evet</a> - <a href="https://google.com.tr/search?q=isteyenin+bir+yüzü+vermeyenin+iki+yüzü+kara">Hayır</a></p> -->

					<button class="modal-tamam">Hmm, iyiymiş.</button>
				</div>
				<div id="api" class="modal">
					<h2 class="modal-baslik">API</h2>
					<div>İstek limiti yoktur, bir defada en fazla 200 farklı kullanıcı profili oluşturabilirsiniz. Çıktı JSON formatındadır.</div>

					<h3>Uyarı</h3>
					<div class="uyari">Profil resimlerini sunmak için ücretli bir servis kullanmadığımdan bant genişliği sınırı mevcut. Resim adreslerini uygulamanızdan veya web sitenizden sürekli olarak erişmek için kullanmayın. Kullanacağınız resimleri indirin ve kendi sunucunuzda saklayın. Bu servis resim sunucusu amaçlı değil restgele profil oluşturmak için kodlandı. Ücretsiz profil resimleri edinmek için <a target="_blank" href="https://unsplash.com/developers">unsplash.com API</a> kullanın.</div>

					<h3>Kullanım</h3>
					<div>
						<pre>https://rp.burakgarci.net/api.php</pre>
					</div>
					<pre><?php echo $p->api(1); ?></pre>

					<h3>Parametreler</h3>

					<div>Cinsiyet seçerek oluşturma (kadin, erkek):
						<pre>https://rp.burakgarci.net/api.php?cinsiyet=kadin</pre>
					</div>

					<div>
						Birden fazla profil oluşturma (en fazla 200):
						<pre>https://rp.burakgarci.net/api.php?adet=20</pre>
					</div>

					<button class="modal-tamam">Eyvallah</button>
				</div>
			</div><!-- /#detaylar -->
		</div><!-- /.tutucu -->
	</main>
	<section id="rek" style="text-align: center; padding:10px">
		<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5770696223060387" crossorigin="anonymous"></script>
		<!-- rp.burakgarci.net - footer yatay -->
		<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-5770696223060387" data-ad-slot="2670722103" data-ad-format="auto" data-full-width-responsive="true"></ins>
		<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	</section>
	<footer>
		<div class="tutucu">
			<a href="#hakkinda" id="hakkinda-gg">Hakkında</a> · <a href="//burakgarci.net/iletisim">İletişim</a> · <a href="#api" id="api-gg">API</a>
			<p><em><small>Profil resimleri yenilendi; 350+ erkek ve 500+ kadın. · 30 Kasım 2021</small></em></p>

		</div><!-- /.tutucu -->
	</footer>
	<script src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
	<script src="assets/script.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-31177906-3"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'UA-31177906-3');
	</script>

</body>

</html>