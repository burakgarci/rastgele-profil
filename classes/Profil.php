<?php

/**
* Rastgele Profil Oluşturucu
* Burak Garcı
* burakgarci.net 
*/

require_once 'Veri.php';
$veri = new Veri();

class Profil
{
	public $isim, $soyisim, $tam_isim, $cinsiyet, $sehir, $adres, $meslek, $eposta, $kullanici_adi, $sifre, $dogum_tarihi, $yas, $telefon_numarasi, $ilgi_alani, $biyografi, $profil_resmi, $web_adresi;

 	function __construct() {
		$this->olustur();
 	}

	private function adres() {
		global $veri;
		return $veri->cbsm[array_rand($veri->cbsm)]." ".$veri->cbsmek[array_rand($veri->cbsmek)]." No:".mt_rand(0,999)." ".$this->sehir;
	}

	private function kullanici_adi() {
		$bul   = array('ı', 'İ', 'ç', 'Ç', 'Ü', 'ü', 'Ö', 'ö', 'ş', 'Ş', 'ğ', 'Ğ', ' ');
		$yap   = array('i', 'I', 'c', 'C', 'U', 'u', 'O', 'o', 's', 'S', 'g', 'G', '_');

		$kullanici_adlari   = array();
		$kullanici_adlari[] = strtolower(str_replace($bul, $yap, $this->tam_isim));
		$kullanici_adlari[] = ucfirst(strtolower(str_replace($bul, $yap, $this->isim))).ucfirst(strtolower(str_replace($bul, $yap, $this->soyisim)));
		$kullanici_adlari[] = ucfirst(strtolower(str_replace($bul, $yap, $this->soyisim))).ucfirst(strtolower(str_replace($bul, $yap, $this->isim)));
		$kullanici_adlari[] = strtolower(str_replace($bul, $yap, $this->tam_isim)).mt_rand(00,999);
		$kullanici_adlari[] = strtolower(str_replace($bul, $yap, $this->isim)).mt_rand(00,999);
		$kullanici_adlari[] = strtolower(str_replace($bul, $yap, $this->soyisim)).mt_rand(00,999);

		return $kullanici_adlari[array_rand($kullanici_adlari)];
	}

	private function eposta() {
		$bul   = array('ı', 'İ', 'ç', 'Ç', 'Ü', 'ü', 'Ö', 'ö', 'ş', 'Ş', 'ğ', 'Ğ', ' ');
		$yap   = array('i', 'I', 'c', 'C', 'U', 'u', 'O', 'o', 's', 'S', 'g', 'G', '_');

		$epostalar   = array();
		$epostalar[] = strtolower(str_replace($bul, $yap, $this->tam_isim));
		$epostalar[] = strtolower(str_replace($bul, $yap, $this->tam_isim)).mt_rand(00,999);
		$epostalar[] = strtolower(str_replace($bul, $yap, $this->soyisim).str_replace($bul, $yap, $this->isim));
		$epostalar[] = strtolower(str_replace($bul, $yap, $this->soyisim).str_replace($bul, $yap, $this->isim)).mt_rand(00,999);

		return $epostalar[array_rand($epostalar)]."@example.com";
	}

	private function web_adresi() {
		$bul   = array('ı', 'İ', 'ç', 'Ç', 'Ü', 'ü', 'Ö', 'ö', 'ş', 'Ş', 'ğ', 'Ğ', ' ');
		$yap   = array('i', 'I', 'c', 'C', 'U', 'u', 'O', 'o', 's', 'S', 'g', 'G', '_');

		$adresler   = array();
		$adresler[] = strtolower(str_replace($bul, $yap, $this->soyisim).str_replace($bul, $yap, $this->isim));
		$adresler[] = strtolower(str_replace($bul, $yap, $this->soyisim)."-".str_replace($bul, $yap, $this->isim));
		$adresler[] = strtolower(str_replace($bul, $yap, $this->isim).str_replace($bul, $yap, $this->soyisim));
		$adresler[] = strtolower(str_replace($bul, $yap, $this->isim)."-".str_replace($bul, $yap, $this->soyisim));

		$uzantilar = array("com", "net", "org", "com.tr", "net.tr", "org.tr" );

		return $adresler[array_rand($adresler)].".".$uzantilar[array_rand($uzantilar)];
	}

	private function dogum_tarihi() {
		$rastgele = rand(strtotime("1.1.1976"), strtotime(date("d.m.").(date("Y")-20)));

		return date('d.m.Y', $rastgele);
	}

	private function yas() {
		return date_diff(date_create($this->dogum_tarihi), date_create('now'))->y;
	}

	private function biyografi(){
		$biyolar = array();

		$biyolar[] = "$this->yas yaşında, $this->meslek";
		$biyolar[] = "$this->yas yaşında, $this->meslek, $this->ilgi_alani";
		$biyolar[] = "$this->yas yaşında, $this->ilgi_alani, $this->meslek. $this->sehir";
		$biyolar[] = "$this->ilgi_alani, $this->meslek. $this->yas yaşında.";
		$biyolar[] = "$this->meslek. ".ucfirst($this->ilgi_alani).". $this->yas yaşında.";

		return $biyolar[array_rand($biyolar)];
	}

	private function telefon_numarasi(){
		// $kodlar = array('505', '506', '507', '552', '553', '554', '555', '559', '530', '531', '532', '533', '534', '535', '536', '537', '538', '539', '540', '541', '542', '543', '544', '545', '546', '547', '548', '549');
		// return "+90".$kodlar[array_rand($kodlar)].mt_rand(1111111,9999999);
		return "+90851".mt_rand(1111111,9999999);
	}

	private function profil_resmi() {
		$dizin    = ($this->cinsiyet == "kadın") ? './photos/kadin/' : './photos/erkek/' ;
		$resimler = glob($dizin . '*.jpg');
		$resim 	  = $resimler[array_rand($resimler)];
		// return $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"]).substr($resim, 1);
		// return "//".$_SERVER["HTTP_HOST"].dirname($_SERVER["SCRIPT_NAME"]).substr($resim, 1);
		return    "http://res.cloudinary.com/brk/image/upload/".substr($resim, strlen('./photos/'));
	}

	public function olustur($cinsiyet = null){
		global $veri;

		if (in_array($cinsiyet, $veri->cinsiyetler))
			$this->cinsiyet     = $cinsiyet;
		else
			$this->cinsiyet     = $veri->cinsiyetler[array_rand($veri->cinsiyetler)];

		if ($this->cinsiyet     == "kadın")
			$this->isim         = $veri->kadin_isimleri[array_rand($veri->kadin_isimleri)];
		if ($this->cinsiyet     == "erkek")
			$this->isim         = $veri->erkek_isimleri[array_rand($veri->erkek_isimleri)];
		else
			$this->isim         = $veri->kadin_isimleri[array_rand($veri->kadin_isimleri)];

		$this->soyisim          = $veri->soyisimler[array_rand($veri->soyisimler)];
		$this->tam_isim         = $this->isim." ".$this->soyisim;
		$this->sehir            = $veri->sehirler[array_rand($veri->sehirler)];
		$this->adres            = $this->adres();
		$this->meslek           = $veri->meslekler[array_rand($veri->meslekler)];
		$this->kullanici_adi    = $this->kullanici_adi();
		$this->eposta           = $this->eposta();
		$this->sifre            = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 10);
		$this->dogum_tarihi     = $this->dogum_tarihi();
		$this->yas     			= (string) $this->yas();
		$this->telefon_numarasi = $this->telefon_numarasi();
		$this->ilgi_alani       = ucfirst($veri->sporlar[array_rand($veri->sporlar)])." ".$veri->sporlarek[array_rand($veri->sporlarek)];
		$this->biyografi        = $this->biyografi();
		$this->profil_resmi     = $this->profil_resmi();
		$this->web_adresi       = $this->web_adresi();

		return $this;
	}

	public function api($adet = 1, $cinsiyet = null){
		$profiller = array();

		if ($adet > 250) $adet = 250;
		if ($adet < 0) $adet = 1;
		if (!ctype_digit($adet)) $adet = 1;
		if ($cinsiyet != "kadın" && $cinsiyet != "erkek") $cinsiyet = null;

		for ($i=0; $i < $adet; $i++) {
			$profil = new Profil();
			$profiller[] = $profil->olustur($cinsiyet);
		}

		if ($adet === 1) $profiller = $profiller[0];

		// return json_encode($profiller, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		return json_encode($profiller, 64 | 256 | 128);
	}	

	public function get_yas()              {return $this->yas;}
	public function get_isim()             {return $this->isim;}
	public function get_sifre()            {return $this->sifre;}
	public function get_sehir()            {return $this->sehir;}
	public function get_adres()            {return $this->adres;}
	public function get_meslek()           {return $this->meslek;}
	public function get_eposta()           {return $this->eposta;}
	public function get_soyisim()          {return $this->soyisim;}
	public function get_cinsiyet()         {return $this->cinsiyet;}
	public function get_tam_isim()         {return $this->tam_isim;}
	public function get_biyografi()        {return $this->biyografi;}
	public function get_ilgi_alani()       {return $this->ilgi_alani;}
	public function get_web_adresi()       {return $this->web_adresi;}
	public function get_dogum_tarihi()     {return $this->dogum_tarihi;}
	public function get_profil_resmi()     {return $this->profil_resmi;}
	public function get_kullanici_adi()    {return $this->kullanici_adi;}
	public function get_telefon_numarasi() {return $this->telefon_numarasi;}
}

?>