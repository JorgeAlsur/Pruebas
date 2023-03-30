<?
$acceso2=base64_decode($acceso);
if (sscanf($acceso2,"acceso a la oferta %d") >0 ){
include "hachelib.php";
//da de alta el acceso a registrar
$expira=time()+24*60*60; 
if (isset($_COOKIE["affiliate_id"]) and $_COOKIE["affiliate_id"] == "24"){ 
	 }
	 else {
	      setcookie("affiliate_id","22",$expira,"/",$SERVER_NAME);
	 }
header("Location: /registrar.php");
}
else {
header("Location: /error.php?error=Error en el acceso a la oferta");
exit;
}
?>
