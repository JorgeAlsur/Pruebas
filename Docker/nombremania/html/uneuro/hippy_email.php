<?
$archivo="visitas.txt";
$texto = date("Y-m-d h:m:s")."\t".$REMOTE_ADDR."\n";
$fp = fopen($archivo, "a");
fwrite($fp,$texto);
fclose($fp);
header("Content_Type: image/gif\n\n");
readfile("hippy_email.gif");
?>