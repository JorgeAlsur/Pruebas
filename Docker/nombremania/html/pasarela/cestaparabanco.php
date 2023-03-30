<?
include "basededatos.php";
//$con=conecta();
//recibe una variable order con la referencia de la operacion id_operacion
$sql="select nm_preciototal,domain from solicitados where id=$order";
//$conn->debug=1;
$rs=$conn->execute($sql);
$registro=$rs->fields;
//$preciototal=$registro["preciototal"];  antes era asi para el euro
$precio=trim($registro["nm_preciototal"]*100);
$preciototal="M978".$precio;
$dominios=str_replace("**",", ",$registro["domain"]);
echo "$preciototal\n1\n$order\nRegistro de dominios $dominios\n1\n$precio";
?>