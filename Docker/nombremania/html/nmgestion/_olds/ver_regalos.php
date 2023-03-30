<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<?
include "estilo.css";
include "barra.php";
//ver  cola de procesamiento
include "basededatos.php";

$campos="regalo.id,concat('<a href=ficha.php?id=',regalo.id_solicitud,'>solicitud</a>'),email_regalante,fecha_email, domain as dominio, from_unixtime(date) as fecha ";
$ncampos="id|solicitud|email_regalante|fecha_mail|dominios|fecha";
$rs=$conn->execute("select $campos from regalo,solicitados where regalo.id_solicitud=solicitados.id order by fecha DESC");
rs2html($rs,"class=tabla align=center",explode("|",$ncampos));
?>


