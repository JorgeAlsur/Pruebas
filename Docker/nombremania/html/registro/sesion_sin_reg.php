<?php
//ini_set("session.use_trans_sid", 1);
//ini_set("error_reporting", 0);



ini_set("session.save_handler", "user");
session_cache_limiter('private');
$ADODB_SESS_LIFE=3*60*60;
ini_set("session.cache_expire",$ADODB_SESS_LIFE);
include('adodb/adodb.inc.php');
include('adodb/adodb-session.php');
session_start();
session_register("registrando");
/*
  acciones que crean sesion:
  1. lookup
  2. lookup_avanzado
  3. check_transfer
  en todas las otras deberia requerirse un $registrando["domain"]

*/
$check= array("lookup","lookup_avanzado","check_transfer","valorar","bulk_order","bulk_transfer");
if (!in_array($action,$check)){
    if (!isset($registrando["domain"])){
                include "func_registro.inc.php";
                mostrar_error_nm("Fallo en la sesi&oacute;n: han pasado m&aacute;s de tres horas desde el comienzo del proceso de registro. Deber&aacute; reiniciar el proceso.");
                exit;
       }
}

?>
