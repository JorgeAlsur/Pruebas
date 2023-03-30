<?
include "conf.inc.php";
include "hachelib.php";
include "sesion.php";
include "func_registro.inc.php";
include "String_Validation.inc.php";
$errores=array();
if (!is_email($email_regalante))$errores[]="Error en el email de la persona que hace el regalo";
if ($email_hasta<>"" and  !is_email($email_hasta))$errores[]="Error de redireccion con errores";
if ($fecha_regalo<>"" and !is_date($fecha_regalo)) $errores[]="Error en la fecha, dejar en blanco para enviar inmediatamente o ingresar en formato dd/mm/aaaa"; 
if (count($errores)>0){
	 mostrar_error_nm(implode("<br>",$errores)); // fallo en las validaciones
  exit;
}
//pasaron las validaciones se agregan a la sesion
//$registrando["regalo_email_hasta"] = $email_hasta;
$registrando["regalo_email_regalante"] = $email_regalante;
$registrando["regalo_fecha_email"]=fecha2mysql($fecha_regalo);
$registrando["regalo_texto"]=$regalo_texto;
$registrando["regalo_titulo"]=$regalo_titulo;
$registrando["regalo_enviar_email"] = ($enviar_email=="1") ? $email_hasta:"";
$registrando["regalo_modelo"]=$regalo_modelo;
$registrando["regalo_nombre_regalante"]=$nombre_regalante;

header("Location: index.php?action=otro_paso&PHPSESSID=". session_id());


?>