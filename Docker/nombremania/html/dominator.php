<?
if(isset($_COOKIE['id_cliente']))
{
	$_GET['affiliate_id']=$id_cliente;
	$_POST['affiliate_id']=$id_cliente;
}
//$affiliate_id=260;
if(isset($_GET['affiliate_id']) or isset($_POST['affiliate_id']))
{
	$expira=time()+(45*24*60*60);   //45 dias de vencimiento de la cookie de afiliado
	if(!setcookie('affiliate_id',$affiliate_id,$expira,"/",$SERVER_NAME))
	{
		header("Location: error.php?error=ESTE+SITIO+REQUIERE+COOKIES+PARA+FUNCIONAR");
		exit;
	}
}
if(!isset($sugerencia))$sugerencia=0;    //redefino sugerencia
// recogemos los datos y separamos el sufijo-----------------------------------

list($nombre,$extension)=explode(".",$domain);
// mira si contiene campo oculto lookup----------------------------------


if($action=='lookup')
{
	// envia al lugar oportuno segun la extension----------------------------------
	include('conf.inc.php');

	if(in_array($extension,$ext_soportadas))
	{
		$donde="/registro/index.php?domain=$domain&action=$action&affiliate_id=$affiliate_id&sugerencia=$sugerencia";
	}
	else
	{
		
		$donde="/registro/index.php?domain=$domain.com&action=$action&affiliate_id=$affiliate_id&sugerencia=$sugerencia";
		/*$error = "Lo sentimos, pero el dominio <b>";
                          $error .= $domain;
                          $error .= "</b> no parece tener una sintaxis correcta<br>";
                          $error=urlencode($error);
                          $donde="/error.php?error=$error";
 */
	}
}
elseif($action=='lookup_selectivo')
{
	/*if($_SERVER['REMOTE_ADDR']=='89.130.215.195')
	{
		echo "exts=".print_r($exts,1)." domain=$domain";
		exit;
	}*/
	if(!isset($exts) or count($exts)==0 or $domain=='')
	{
		$error='Lo sentimos, pero se ha realizado ';
		$error.='una llamada incorrecta ';
		$error.='a nuestro sistema.<br>';
		$error=urlencode($error);
		$donde="/error.php?error=$error" ;
	}
	else
	{
		/*if($extension=='es')
		{
			$donde="/dominioses/paso1.php?domain=$domain&action=$action&affiliate_id=$affiliate_id";
		}
		else*/
		$donde="/registro/index.php?domain=$nombre&action=lookup&affiliate_id=$affiliate_id&sugerencia=$sugerencia&exts=".implode(",",$exts);
	}
}
elseif($action=='check_transfer')
{
	if(($extension=='com') || ($extension=='net') || ($extension=='org') || ($extension=='info') || ($extension=='eu') || ($extension=='es'))
	{
		//$donde="/cgi-bin/r24/reg_system-es.cgi?domain=$domain&action=$action&affiliate_id=$affiliate_id";
		$donde="/registro/index.php?domain=$domain&action=$action&affiliate_id=$affiliate_id";
	}
	else
	{
		$error='Lo sentimos, pero el dominio  que desear transferir <b>';
		$error.=$domain;
		$error.='</b> no parece tener una sintaxis correcta.<br>';
		$error=urlencode($error);
		$donde="/error.php?error=$error";
	}
}

// si no contiene el campo oculto lookup o checktransfer
else
{
	$error='Lo sentimos, pero se ha realizado ';
	$error.='una llamada incorrecta ';
	$error.='a nuestro sistema.<br>';
	$error=urlencode($error);
	$donde="/error.php?error=$error" ;
}
header("Location: $donde");
?>
