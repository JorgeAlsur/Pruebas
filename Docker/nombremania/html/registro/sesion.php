<?php
//ini_set("session.use_trans_sid", 1);
//ini_set("error_reporting", 0);
ini_set('session.save_handler','user');
ini_set('session.gc_maxlifetime',3*60*60);
session_cache_limiter('private');
$ADODB_SESS_LIFE=3*60*60;
ini_set('session.cache_expire',3*60);  //180 minutos
include('adodb/adodb.inc.php');
ADOLoadCode('mysql');
include('adbsession.php');
session_start();
session_register('registrando');
/*
  acciones que crean sesion:
  1. lookup
  2. lookup_avanzado
  3. check_transfer
	4.valorar
	5. bulkorder
  en todas las otras deberia requerirse un $registrando['domain']
*/
$check=array('lookup','lookup_avanzado','check_transfer','valorar','bulk_order','bulk_transfer');
if(!in_array($action,$check))
{
	if(!isset($registrando['domain']))
	{
		include('func_registro.inc.php');
		mostrar_error_nm('Fallo en la sesi&oacute;n: ha pasado demasiado tiempo desde comienzo del proceso de registro. Deber&aacute; reiniciar el proceso.');
		exit;
	}
}
else
{
	if(isset($id_registrador) or isset($registrando['id_registrador']))
	{
		include('basededatos.php');
		if(isset($registrando['id_registrador']))$id_registrador=$registrando['id_registrador'];
		$sql="select * from registradores where id = $id_registrador and activo=1";
		$rs=$conn->execute($sql);
		if($rs===false)die('error de SQL '.mysql_error());
		if($conn->affected_rows()==0)
		{
			mostrar_error_nm("Registrador no Ingresado en la base de datos o no ACTIVO");
		}
		$registrando['templates']=$rs->fields['templates'];
		$registrando['logo_registrador']=$rs->fields['logo'];
		$registrando['url_registrador']=$rs->fields['url'];
		$registrando['nivel_registrador']=$rs->fields['nivel'];
		$registrando['id_registrador']=$rs->fields['id'];
		$registrando['nm_registro_tipo']='pro2';
		unset($rs);
	}
	else
	{
		$registrando['templates']='templates';  //default de templates
	}
}

?>