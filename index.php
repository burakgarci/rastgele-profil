<?php
require_once 'classes/Profil.php';
$p = new Profil();
?>
<!DOCTYPE html>
<html lang="tr_TR">
<head>
	<meta charset="UTF-8">
	<title>Rastgele Profil Oluşturucu</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Tasarımcılar için rastgele profil oluşturucu. İsim, soyisim, biyografi, kullanıcı adı ve dahası."/>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic&amp;subset=latin,greek-ext' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="assets/style.css">
</head>
<body>

	<header>
		<div class="tutucu">
			<nav>
				<a href="#erkek"><button class="erkek-btn" data-cinsiyet="erkek">♂</button></a>
				<a href="#kadin"><button class="kadin-btn" data-cinsiyet="kadin">♀</button></a>
				<a href="#uniseks"><button class="uniseks-btn" data-cinsiyet="üniseks" class="aktif">⚥</button></a>
			</nav>
		</div><!-- /.tutucu -->
	</header>

	<main>
		<div class="tutucu">
			<div id="profil">
				<img id="profil_resmi" src="<?php echo $p->get_profil_resmi()."?tr=h-150,w-150,q-70"; ?>">

				<div class="clear"></div>

				<div class="pdlt"><label for="tam_isim">İsim</label></div>
				<div class="pdit">
					<input onclick="select()" readonly spellcheck="false" type="text" id="tam_isim" value="<?php echo $p->get_tam_isim() ?>">
				</div>

				<div class="pdlt"><label for="sehir">Şehir</label></div>
				<div class="pdit">
					<input onclick="select()" readonly spellcheck="false" type="text" id="sehir" value="<?php echo $p->get_sehir() ?>">
				</div>

				<div class="pdlt"><label for="biyografi">Biyografi</label></div>
				<div class="pdit">
					<input onclick="select()" readonly spellcheck="false" type="text" id="biyografi" value="<?php echo $p->get_biyografi() ?>">
				</div>

				<div class="pdlt"><label for="kullanici_adi">Kullanıcı adı</label></div>
				<div class="pdit">
					<input onclick="select()" readonly spellcheck="false" type="text" id="kullanici_adi" value="<?php echo $p->get_kullanici_adi() ?>">
				</div>

				<div class="pdlt"><label for="sifre">Şifre</label></div>
				<div class="pdit">
					<input onclick="select()" readonly spellcheck="false" type="text" id="sifre" value="<?php echo $p->get_sifre() ?>">
				</div>

				<div class="pdlt"><label for="eposta">E-posta</label></div>
				<div class="pdit">
					<input onclick="select()" readonly spellcheck="false" type="text" id="eposta" value="<?php echo $p->get_eposta() ?>">
				</div>

				<div id="daha">
					
					<div class="pdlt"><label for="adres">Adres</label></div>
					<div class="pdit">
						<input onclick="select()" readonly spellcheck="false" type="text" id="adres" value="<?php echo $p->get_adres() ?>">
					</div>
					
					<div class="pdlt"><label for="meslek">Meslek</label></div>
					<div class="pdit">
						<input onclick="select()" readonly spellcheck="false" type="text" id="meslek" value="<?php echo $p->get_meslek() ?>">
					</div>
					
					<div class="pdlt"><label for="dogum_tarihi">Doğum tarihi</label></div>
					<div class="pdit">
						<input onclick="select()" readonly spellcheck="false" type="text" id="dogum_tarihi" value="<?php echo $p->get_dogum_tarihi() ?>">
					</div>
					
					<div class="pdlt"><label for="yas">Yaş</label></div>
					<div class="pdit">
						<input onclick="select()" readonly spellcheck="false" type="text" id="yas" value="<?php echo $p->get_yas() ?>">
					</div>
					
					<div class="pdlt"><label for="telefon_numarasi">Telefon</label></div>
					<div class="pdit">
						<input onclick="select()" readonly spellcheck="false" type="text" id="telefon_numarasi" value="<?php echo $p->get_telefon_numarasi() ?>">
					</div>
					
					<div class="pdlt"><label for="ilgi_alani">İlgi alanı</label></div>
					<div class="pdit">
						<input onclick="select()" readonly spellcheck="false" type="text" id="ilgi_alani" value="<?php echo $p->get_ilgi_alani() ?>">
					</div>
					
					<div class="pdlt"><label for="profil_resmi_url">Profil resmi</label></div>
					<div class="pdit">
						<input onclick="select()" readonly spellcheck="false" type="text" id="profil_resmi_url" value="<?php echo $p->get_profil_resmi() ?>">
					</div>
					
					<div class="pdlt"><label for="web_adresi">Web adresi</label></div>
					<div class="pdit">
						<input onclick="select()" readonly spellcheck="false" type="text" id="web_adresi" value="<?php echo $p->get_web_adresi() ?>">
					</div>

					<div class="clear"></div>
					
				</div><!-- /#daha -->

				<div class="clear"></div>
				<div id="daha-ak" class="arrow down">DAHA FAZLA DETAY...</div><!-- /#profil -->
			</div><!-- /#profil -->

			<div id="detaylar">
				<div id="hakkinda" class="modal">
					<h2 class="modal-baslik">Hakkında</h2>
					<p>Rastgele Profil Oluşturucu tasarımcılar için rastgele profil bilgileri oluşturmaya yarayan basit bir araçtır. <a href="http://burakgarci.net">Burak Garcı</a> tarafından geliştirilmiştir.</p>
					<p>Profil fotoğrafları <a href="http://unsplash.com">unsplash.com</a> sitesi üzerinde <strong>CC0 1.0 Evrensel</strong> lisansı altında yayınlanan fotoğraflardan seçilmiştir.</p>
					<p>Ayrıca bu web sitesi açık kaynak kodludur. <a target="_blank" href="http://github.com/burakgarci">GitHub</a> üzerinden kaynak kodları görüntüleyebilirsiniz.</p>
					<p><a href="http://burakgarci.net/iletisim">burakgarci.net/iletisim</a> adresinden geribildirimde bulunabilirsiniz.</p>
					<p>Bir şeyler ısmarlak ister misiniz? <a href="http://paypal.me/garci">Evet</a> - <a href="https://google.com.tr/search?q=isteyenin+bir+yüzü+vermeyenin+iki+yüzü+kara">Hayır</a></p>

					<button class="modal-tamam">Hmm, iyiymiş.</button>
				</div>
				<div id="api" class="modal">
					<h2 class="modal-baslik">API</h2>
					<p>İstek limiti yoktur, bir defada en fazla 200 farklı kullanıcı profili oluşturabilirsiniz. Çıktı JSON formatındadır.</p>

					<h3>Uyarı</h3>
					<p class="uyari">Profil resimlerini sunmak için ücretli bir servis kullanmadığımdan bant genişliği sınırı mevcut. Resim adreslerini uygulamanızdan veya web sitenizden sürekli olarak erişmek için kullanmayın. Kullanacağınız resimleri indirin ve kendi sunucunuzda saklayın. Bu servis resim sunucusu amaçlı değil restgele profil oluşturmak için kodlandı. Ücretsiz profil resimleri edinmek için <a target="_blank" href="https://unsplash.com/developers">unsplash.com API</a> kullanın.</p>

					<h3>Kullanım</h3>
					<p>
						<pre>http://rp.burakgarci.net/api.php</pre>

					</p><pre><?php echo $p->api(1); ?></pre>
					
					<h3>Parametreler</h3>

					<p>
						Cinsiyet seçerek oluşturma (kadın, erkek):
						<pre>http://rp.burakgarci.net/api.php?cinsiyet=kadın</pre>
					</p>

					<p>
						Birden fazla profil oluşturma (en fazla 200):
						<pre>http://rp.burakgarci.net/api.php?adet=20</pre>
					</p>

					<button class="modal-tamam">Eyvallah</button>
				</div>
			</div><!-- /#detaylar -->
		</div><!-- /.tutucu -->
	</main>

	<footer>
		<div class="tutucu">
			<a href="#hakkinda" id="hakkinda-gg">Hakkında</a> - <a href="#api" id="api-gg">API</a>
			<!-- <p><em><small>beta</small></em></p> -->

		</div><!-- /.tutucu -->
	</footer>
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
	<script type="text/javascript" src="assets/script.js"></script>
</body>
</html>