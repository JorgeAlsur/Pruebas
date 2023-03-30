<? 
function existe_registrado($dominios)
{
	global $conn;
	$rs1=$conn->execute("select id from solicitados where domain='$dominios' and nm_cod_aprob<>''");
	if($rs1->_numOfRows>0)
	{
		return true;
	}
	else return false;
}

ini_set("include_path",".:/usr/lib/php:/home/webs/nombremania.com/html/phplibs:/home/webs/phplibs");
include("basededatos.php");

/*
 Verifica las solicitudes no completadas y envia mails a los propietarios
*/
$cuatro=(4*24*60*60)+1;
$quince=(15*24*60*60);
$sql="select * from solicitados where date >= UNIX_TIMESTAMP()-$cuatro and date >= UNIX_TIMESTAMP()-$quince and nm_cod_aprob='' and aviso_email=1";
include("basededatos.php");
$conn->debug=true;
$rs=$conn->execute($sql);
print "encontrados: ".$rs->_numOfRows;
$enviar_4dias =array();

while (!$rs->EOF){
		if (!existe_registrado($rs->fields["domain"])){
			$enviar_4dias[]=array("email"=>$rs->fields["owner_email"],
			"dominios" => str_replace("**",", ",$rs->fields["domain"]),
			"nombre"=>$rs->fields["owner_last_name"].", ".$rs->fields["owner_first_name"],
			"id"=>$rs->fields["id"]);																													
			}
			$rs->movenext();
}


include "hachelib.php";
/*var_crudas($enviar_4dias);
exit;*/
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
	$id=$envio["id"];
	$dat["dominios"]=$envio["dominios"];
	print "<br>".$dat["email_cliente"];
			
	if(!envioemail($ruta."/procesos/mails_a_enviar/mail_15_dias.txt",$dat))
	{
		$errores.="Error de envio mail a {$dat["email"]}<bR>";
	}
	else
	{
		$sql="update solicitados set aviso_email=2 where id=$id";
		$rs=$conn->execute($sql);
	}     												 
}
print $errores;		
?>