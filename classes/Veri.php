<?php 

/**
* Gerekli verileri yükle
*/

class Veri
{
	function __construct ()
	{
		try {
			$this->erkek_isimleri = explode("\n", file_get_contents("assets/data/isimler-erkek.txt"));
			$this->kadin_isimleri = explode("\n", file_get_contents("assets/data/isimler-kadin.txt"));
			$this->soyisimler     = explode("\n", file_get_contents("assets/data/soyisimler.txt"));
			$this->cbsm           = explode("\n", file_get_contents("assets/data/cbsm.txt"));
			$this->sehirler       = explode("\n", file_get_contents("assets/data/sehirler.txt"));
			$this->meslekler      = explode("\n", file_get_contents("assets/data/meslekler.txt"));
			$this->sporlar        = explode("\n", file_get_contents("assets/data/sporlar.txt"));
			$this->sporlarek      = array("meraklısı", "sevdalısı", "hayranı", "izleyicisi", "sever", "hastası", "manyağı", "düşkünü", "bağımlısı");
			$this->cinsiyetler    = array("erkek", "kadın");
			$this->cbsmek         = array("Caddesi", "Bulvarı", "Sokağı", "Mahallesi");
		} catch (Exception $e) {
			return $this->hata 	  = $e->getMessage();
		}
	}
}

?>