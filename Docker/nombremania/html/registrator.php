<?

// dirige al cgi oportuno segun seleccion----------------------------------
// mira que datos tienen las imagenes y determina que tipo de registro se hace
//var_dump($HTTP_VAR_POST);
//var_dump($HTTP_VAR_GET);

if (isset($estandar_x)){
$tipo='estandar';
}
else {
$tipo='pro';
}


if($tipo=='pro')
{
	$donde='/cgi-bin/r24/reg_system-es.cgi?';
	if ($domain1!=''){$donde.="domain=$domain1";}
	if ($domain2!=''){$donde.="&domain=$domain2";}
	if ($domain3!=''){$donde.="&domain=$domain3";}
	$donde.="&action=$action&affiliate_id=$affiliate_id&nm_registro_tipo=PRO";
}
elseif($tipo=='estandar')
{
	$donde="/cgi-bin/r24/reg_system-es.cgi?";
	if($domain1!=''){$donde.="domain=$domain1";}
	if($domain2!=''){$donde.="&domain=$domain2";}
	if($domain3!=''){$donde.="&domain=$domain3";}
	$donde.="&action=$action&affiliate_id=$affiliate_id&nm_registro_tipo=ESTANDAR";
}
else
{
	$donde='registro.php';
}
header("Location: $donde");
?>