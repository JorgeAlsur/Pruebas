<?
//grabar cookie de affiliado 
setcookie("affiliate_id","");
$horas24=time()+(24*60*60);
setcookie("affiliate_id","22",$horas24,"/");
header("Location: confirmacion.php");
?>