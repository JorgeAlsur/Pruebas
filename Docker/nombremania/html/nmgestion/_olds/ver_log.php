<HTML>

<HEAD><TITLE>Administracion de Nombremania</TITLE>

<link rel="stylesheet" type="text/css" href="../estilo.css">

</HEAD>

<body>



<?

//ver el log de la cola de procesamiento



if ($fecha==""){

  $fech_archivo=date("Ymd");

}

else {

  $fech_archivo=$fecha;

}



?>

<center>

<form action=<?=$PHP_SELF ?>>

Fecha para ver el log:

<input type="text" name=fecha value="<?=$fech_archivo;?>"> (yyyymmdd)

</form> </center>

<?
$DIR=$GLOBALS["DOCUMENT_ROOT"]."/phplibs/";
if (file_exists("{$DIR}logs/log-$fech_archivo.log")){

echo "<pre>";

readfile("{$DIR}logs/log-$fech_archivo.log");

echo "</pre>";

}

else {

echo "<h4 align=center >archivo logs/log-$fech_archivo.log no existe </h4>";

}



?>
