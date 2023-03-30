<?
ini_set("include_path",".:/usr/lib/php:/home/webs/nombremania.com/html/phplibs:/home/webs/phplibs");
include("basededatos.php");

// usuarios que no han completado  
$cuatro=(4*24*60*60);
$quince=(15*24*60*60);
//"select id,owner_email,domain,from_unixtime(date) as fecha from solicitados where date <= UNIX_TIMESTAMP()-$cuatro and date >= UNIX_TIMESTAMP()-$quince and nm_cod_aprob=\"\" and aviso_email=0 and (reg_type='new'  or reg_type='transfer')";
$conn->debug=1;
$sql="select domain,owner_last_name,owner_first_name,id,owner_email,domain,from_unixtime(date) as fecha  from solicitados where date >= UNIX_TIMESTAMP()-$quince and date <= UNIX_TIMESTAMP()-$cuatro and  nm_cod_aprob='' and aviso_email=0 and (reg_type='new'  or reg_type='transfer')";
include("basededatos.php");
$rs=$conn->execute($sql);
$enviar_4dias =array();

while(!$rs->EOF)
{
	$enviar_4dias[]=array("email"=>$rs->fields["owner_email"],
			"dominios" => str_replace("**",", ",$rs->fields["domain"]),
			"nombre"=>$rs->fields["owner_last_name"].", ".$rs->fields["owner_first_name"],
			"id"=>$rs->fields["id"]);																													
		$rs->movenext();
}

include("hachelib.php");
reset($enviar_4dias);
$ruta=$_SERVER['DOCUMENT_ROOT'];
$errores="";
include("enviar_email.php");
foreach($enviar_4dias as $envio)
{
	$dat=array();
	$dat["fecha"] = date("d-m-Y");
	$dat["email_cliente"]=$envio["email"];
	$dat["nombre"]=$envio["nombre"];														 								
	$dominio=explode(", ",$envio["dominios"]);
	$dominio=$dominio[0]; //tomo el primero
	$id=$envio["id"];
	$destino="http://".$GLOBALS["SERVER_NAME"]."/registro/"."datos_pago.php?id_operacion=$id&dominio=$dominio";
	$dat["destino"]=$destino;

	if(!envioemail($ruta."/procesos/mails_a_enviar/mail_4_dias.txt",$dat))
	{
		$errores.="Error de envio mail a {$dat["email"]}<bR>";
	}
	else
	{
		$sql="update solicitados set aviso_email=1 where id=$id";
		$rs=$conn->execute($sql);
	}     												 
}
print $errores;		
?>