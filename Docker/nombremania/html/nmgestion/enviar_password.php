<html>
<head>
<link href="/nmgestion/estilo.css" rel="stylesheet" type="text/css">
</head>
<body>

<?
if (!isset($id)){
	echo "error de id de operacion";
	exit;
}

include "basededatos.php";
$sql="select * from operaciones where id=$id";
$rs = $conn->execute($sql);
$dat=array();
$dat["nombre"] =  $rs->fields["owner_last_name"].", ".$rs->fields["owner_first_name"] ;
$dat["email"] =$enviar;
$dat["usuario"] = $rs->fields["reg_username"];
$dat["password"] = $rs->fields["reg_password"];
print "<pre>";
print_r($dat);
print "</pre>";
if (isset($enviar)){


include "enviar_email.php";

$dat["fecha"]=date("d-m-Y");
print_r($dat);
$ruta=$_SERVER['DOCUMENT_ROOT'];
include_once( "enviar_email.php");
if (!envioemail($ruta."/procesos/mails_a_enviar/mail_password.txt",$dat)){
		echo "Error de envio de password";
    }else 
	{
	echo "<h3>Mail con el password enviado con exito</h3><centeR><a href=/nmgestion >Volver al menu</a></center>";
	}
}
else {
// se muestra el whois y el siguiente paso
echo "<h3>CHEQUEAR EL EMAIL DEL PROPIETARIO DEL DOMINIO: </h3>"
?>
<form action="<?=$PHP_SELF?>">
<input type=hidden name=id value="<?=$id?>">
	Email a enviar el password: <input type="text" name="enviar" value="<?=$rs->fields["owner_email"]?>" size="70">
	<input type="submit" value="Enviar">
	<centeR><a href=/nmgestion >Volver al menu</a></center>
</form>
<?
	echo "<b>$dat</b><p>";
	$fqdn = explode("**",$rs->fields["domain"]);
	$fqdn = $fqdn[0];
	//include("whois/main.whois");
	include("whois_php/whois.php");
	   /*
               $whois = new Whois($fqdn);
               $result = $whois->Lookup();
               echo "<b>Resultado de  $fqdn :</b><p>";
               if(isSet($whois->Query["errstr"])) {
               echo "<b>Errores:</b><br>".implode($whois->Query["errstr"],"<br>");
               }
               if($output=="object") {
                       include("whois/utils.whois");
                       $utils = new utils;
                       $utils->showObject($result);
               } else {
                       if(!empty($result["rawdata"])) {
                               echo implode("<BR>\n",$result["rawdata"]);
                       } else { echo "<br>nada"; }
               }
*/
}
?>