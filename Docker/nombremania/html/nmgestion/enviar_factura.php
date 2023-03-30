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

if (isset($enviar)){
echo "enviando PASSWORD";
$dat=array();
include "enviar_email.php";
$dat["email_cliente"] =$enviar;
$dat["first_name"] = $rs->fields["owner_first_name"];
$dat["last_name"] = $rs->fields["owner_last_name"];
$dat["fecha"]=date("d-m-Y");
$conn->debug=1;
$fact = "select * from facturas where id_solicitud = {$rs->fields["id_solicitud"]}";
$f = $conn->execute($fact);
$factura="no";
if ($f !==false and $f->_numOfRows>0){
$factura=base64_encode($f->fields["id"]."-nm");
}else {
   echo"<h3>Este dominio no tiene factura asociada </h3><centeR><a href=/nmgestion >Volver al menu</a></center>";
   exit;
}

$dat["link_factura"]="http://www.nombremania.com/clientes/pago/i_factura.php?id=$factura";
$ruta=$_SERVER['DOCUMENT_ROOT'];
include_once( "enviar_email.php");

if (!envioemail($ruta."/procesos/mails_a_enviar/mail_solo_factura.txt",$dat)){
		echo "Error de envio de password";
    }else 
	{
	echo "<h3>Mail con la factura enviado con exito</h3><centeR><a href=/nmgestion >Volver al menu</a></center>";
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
}
?>