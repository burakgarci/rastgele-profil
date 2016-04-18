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
	<meta name="description" content="Tasarımcılar için rastgele profil oluşturucu. İsim, soyisim, biyografi, kullanıcı adı..."/>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic&amp;subset=latin,greek-ext' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="assets/style.css">
</head>
<body>

	<header>
		<div class="tutucu">
			<nav>
				<a href="#erkek"><button class="erkek-btn" data-cinsiyet="erkek">E</button></a>
				<a href="#kadin"><button class="kadin-btn" data-cinsiyet="kadin">K</button></a>
				<a href="#uniseks"><button class="uniseks-btn" data-cinsiyet="üniseks" class="aktif">Ü</button></a>
			</nav>
		</div><!-- /.tutucu -->
	</header>

	<main>
		<div class="tutucu">
			<div id="profil">
				<img id="profil_resmi" src="<?php echo $p->get_profil_resmi(); ?>">

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

				<div class="clear"></div>
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
					<h3>Kullanım</h3>
					<p>
					<pre>http://burakgarci.net/lab/rastgele-profil/api.php</pre>

					</p><pre>{
    "isim": "Metin",
    "soyisim": "Yüksel",
    "tam_isim": "Metin Yüksel",
    "cinsiyet": "erkek",
    "sehir": "Zile, Tokat",
    "adres": "Çalıkuşu Sokağı No:826 Zile, Tokat",
    "meslek": "Kurumsal Web İletişim Uzmanı",
    "eposta": "yukselmetin239@example.com",
    "kullanici_adi": "metin279",
    "sifre": "qLJaNm89EI",
    "dogum_tarihi": "19.07.1988",
    "yas": "27",
    "telefon_numarasi": "+905392037096",
    "ilgi_alani": "Bilardo meraklısı",
    "biyografi": "27 yaşında, Kurumsal Web İletişim Uzmanı",
    "profil_resmi": "http://res.cloudinary.com/brk/image/upload/erkek/93.jpg",
    "web_adresi": "yukselmetin.net"
}</pre>
					
					<h3>Parametreler</h3>

					<p>
						Cinsiyet seçerek oluşturma (kadın, erkek):
						<pre>http://burakgarci.net/lab/rastgele-profil/api.php?cinsiyet=kadın</pre>
					</p>

					<p>
						Birden fazla profil oluşturma (en fazla 200):
						<pre>http://burakgarci.net/lab/rastgele-profil/api.php?adet=20</pre>
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