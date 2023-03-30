<?
$error="";

# comentar if para pruebas

if(!isset($_COOKIE['REGISTRANT_LIVE_KEY']))
{
	$text=urlencode("La llamada fue realizada desde un host no habilitado o ha expirado el tiempo de acceso, reingrese a administraci&oacute;n de dominios y reintente");
	header("Location: /nm/error.php?error=$text");
	exit;
}

//rellamada a alsur.es para acceso a zoneedit.
include("basededatos.php");

if(isset($domain) and $domain!='')
{
	//$domain=base64_decode($domain);
	//    cambiar por registrados
	//$conn->debug=true;

	$sql="select * from zonas where dominio = \"$domain\" and tipo like \"PRO%\" and hasta>=CURDATE() ";
	$res=$conn->execute($sql);
	if($conn->Affected_Rows()>0)
	{
		$fecha=$res->fields['fecha'];
		$dias=getdate($fecha);

		$fecha_venc=mktime(0,0,0,$dias['mon'],$dias['mday'],$dias['year']+$res->fields['period']);
		include('hachelib.php');

		$usuario=$res->fields('reg_username');
		$clave=$res->fields('reg_password');

		// graba cookie
		setcookie('admzona',$domain,time()+3600,"/",'ssl.alsur.es',1);
		//setcookie('admzona', $domain, time()+3600,"/",'local.nombremania.com', 1); #crea cookie en local (pruebas)
		if($adv==1)
		{
			if($res->fields('tipo')=='PRO3')
			{
				$a_donde="paneladmin_adv.php";
			}
			else
			{
				$a_donde="/nm/error.php?error=SERVICIOS PRO-DNS NO CONTRATADOS" ;
			}
		}
		else
		{
			$a_donde="paneladmin.php";
		}
	}
	else
	{
		$a_donde="/nm/error.php?error=SERVICIOS NO CONTRATADOS" ;
	}
	header("Location: $a_donde");
}
?>
