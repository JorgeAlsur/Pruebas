<?
/*if($_SERVER['REMOTE_ADDR']=='89.130.215.195')
{
	print_r($_COOKIE);exit;
}*/
if(!isset($_COOKIE['REGISTRANT_LIVE_KEY']))
{
	$text=urlencode('La llamada fue realizada desde un host no habilitado o ha expirado el tiempo de acceso, reingrese a administraci&oacute;n de dominios y reintente.');
	header("Location: /nm/error.php?error=$text");
	exit;
}

if(isset($domain))
{
	setcookie('admzona',$domain,time()+3600,"/",'ssl.alsur.es',1);
	header("Location: /nm/clientes/ver_facturas.php\n");
	exit;
}

$debug=0;

?>
<html>
<head>
<title>lista de facturas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
td { font-family: Verdana; font-size: 11px ; }
table {font-family: Verdana; font-size: 11px; }
</style>
</head>
<body>
<strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="../imagenes/logochico.gif" width="102" height="16"><br>
Lista de Facturas<br>
</font></strong><br>
<?
$buscar=trim($_COOKIE['admzona']);
if($buscar == "")
{
	die("error, contacte con soporte@nombremania.com");
}
include("basededatos.php");
$campos=" id,DATE_FORMAT(FROM_UNIXTIME(fecha),'%d-%m-%y') as fecha2,concepto,CONCAT('<a href=\"ficha.php?id=',id_solicitud,'\">solicitud</a>')";
$sql="select $campos from facturas where concepto like \"%$buscar%\" ";
if($debug)$debug_string.="$sql <br/>";
$conn->debug=0;
$rs=$conn->execute($sql);

if($debug)
{
	$debug_string.="<br/>conn:<br/>";
	foreach($conn as $key=>$value)
	{
		$debug_string.="$key = $value <br/>";
	}
	if($debug)$debug_string.="<br/>rs:<br/>";
	foreach($rs as $key=>$value)
	{
		$debug_string.="$key = $value <br/>";
	}
	if($debug)$debug_string.="<br/>".$rs->RecordCount()."<br/>";
}

if($rs->RecordCount()<1)
{
	print 'Nada encontrado para este dominio.';
}
else
{
	print "<table class=\"tabla\"> <tr><td>Fecha</td><td>Concepto</td><td>Accion</td></tr>";
	while(!$rs->EOF)
	{
		$fecha=$rs->fields['fecha2'];
		$c=$rs->fields['concepto'];
		$link="http://www.nombremania.com/clientes/pago/i_factura.php?id=".base64_encode($rs->fields["id"]."-nm");
		print "<tr><td>$fecha</td><td>$c</td><td><a href=\"$link\" target=\"_blank\">Factura</a></td></tr>";	
		$rs->movenext();
	}
	print "</table>";
}
if($debug)mail("jose@alsur.es","Debug System ver_facturas",$debug_string,"From: Debug System <jose@alsur.es>\nContent-type: text/html");
?>
</body>
</html>