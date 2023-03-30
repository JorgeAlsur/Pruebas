<?
$expira=time()+(45*24*60*60);   //30 dias de vencimiento de la cookie de afiliado
setcookie("affiliate_id",$affiliate_id,$expira,"/","www.nombremania.com");
header("Location: /afiliados.php");
?>


