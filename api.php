<?php 
header("Content-type: application/json; charset=utf-8");
// error_reporting(0);
// ini_set('display_errors', 0);
try {
	require_once 'classes/Profil.php';

	$miktar   = (isset($_GET['miktar'])) ? $_GET['miktar'] : null ;
	$cinsiyet = (isset($_GET['cinsiyet'])) ? $_GET['cinsiyet'] : null ;
	
	$p = new Profil();
	echo $p->api($miktar, $cinsiyet);

} catch (Exception $e) {
	echo '{"hata":"'.$e->getMessage().'"}';
}

?>