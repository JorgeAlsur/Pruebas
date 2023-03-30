<HTML>

<HEAD><TITLE>ficha de opcione de pago Nombremania -administracion interna</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="estilo.css">

</HEAD>
<?
include("hachelib.php");
include("barra.php");

include("basededatos.php");

$id=$_POST["id"];
$domain=$_POST["dominio"];
$precio=$_POST["precio"];

$debug=0;

//$sql="update operaciones set estado = 3 where id=$id";
$sql="select * from solicitados where domain='$domain' order by id desc;";
$rs=$conn->execute($sql);
$id_solicitud=$rs->fields["id"];
$period=$rs->fields["period"];
$sql="select * from operaciones where domain='$domain' order by id desc;";
$rs=$conn->execute($sql);
$id_operacion=$rs->fields["id"];
$fecha=$rs->fields["fecha"];

if(!strcasecmp($rs->fields["tipo"],"PRO3"))exit;
if(!strcasecmp($rs->fields["tipo"],"ESTANDAR"))$new_tipo="PRO2";
if(!strcasecmp($rs->fields["tipo"],"PRO2"))$new_tipo="PRO3";

if($new_tipo=="PRO2")
{
	$emails=5;
	$redirecciones=5;
}
else if($new_tipo=="PRO3")
{
	$emails=99999;
	$redirecciones=99999;
}
else
{
	$emails=0;
	$redirecciones=0;
}

if($debug)echo "id_solicitud=$id_solicitado id_operacion=$id_operacion<br/>";

$sql="update operaciones set notas='fecha=$fecha precio=$precio',tipo='$new_tipo' where id=$id_operacion;";
if($debug)
{
	echo "$sql<br/>";
}
else
{
	$rs=$conn->execute($sql);
}

$sql="select * from zonas where id_solicitud=$id_solicitud and id_operacion=$id_operacion;";
$rs=$conn->execute($sql);
if($conn->Affected_Rows()>0)
{
	$id_zona=$rs->fields["id"];
	$sql="update zonas set tipo='$new_tipo' where id=$id_zona;";
	if($debug)
	{
		echo "$sql<br/>";
	}
	else
	{
		$rs=$conn->execute($sql);
	}
}
else
{
	$sql="insert into zonas (id,id_solicitud,id_operacion,fecha,dominio,period,emails,redirecciones,tipo,precio) values (NULL,$id_solicitud,$id_operacion,$fecha,'$domain',$period,$emails,$redirecciones,'$new_tipo',$precio);";
	if($debug)
	{
		echo "$sql<br/>";
	}
	else
	{
		$rs=$conn->execute($sql);
	}
}

echo "Operación realizada correctamente. <a href=\"/nmgestion/ficha.php?id=$id\">Volver</a>";
?>