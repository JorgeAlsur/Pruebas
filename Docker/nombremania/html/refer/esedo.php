<?
$expira=time()+(45*24*60*60);   //30 dias de vencimiento de la cookie de afiliado
setcookie("affiliate_id","178",$expira,"/","www.nombremania.com");
$exts=array(".".$tld);
$exts[]= ".com";$exts[]=".net";$exts[]=".org";
$ext = implode(',',$exts);
header("Location: /registro/index.php?domain=$sld&action=lookup&affiliate_id=178&sugerencia=0&exts=$ext");
?>


