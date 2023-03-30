<HTML>

<HEAD><TITLE>Administracion de Nombremania</TITLE>

<link rel="stylesheet" type="text/css" href="../estilo.css">

</HEAD>

<body>



<?

//ver el log de la cola de procesamiento

include("conf.inc.php");

if($fecha=="")
{
	$fech_archivo=date("Ymd");
}
else
{
	$fech_archivo=$fecha;
}
?>

<center>

<form action=<?=$PHP_SELF ?>>

Fecha para ver el log:

<input type="text" name=fecha value="<?=$fech_archivo;?>"> (yyyymmdd)

</form> </center>

<?
if(is_file($dir_logs."/log-$fech_archivo.log"))
{
	echo "<pre>";
	readfile($dir_logs."/log-$fech_archivo.log");
	echo "</pre>";
}
else
{
	echo "<h4 align=\"center\">archivo ".$dir_logs."/log-$fech_archivo.log no existe </h4>";
}
?>