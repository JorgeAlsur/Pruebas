<?
//echo ini_get("include_path");

//if($_SERVER['REMOTE_ADDR']=='')exit;

function caja_login()
{
	global $_COOKIE,$conn;
	$templates=$_SERVER['DOCUMENT_ROOT']."/clientes/templates/";
	include_once('class.templateh.php');
	$t=new Template($templates,'remove');

	if(!isset($_COOKIE) or $_COOKIE["id_cliente"]=="")
	{
		$t->set_file("template","cliente_no_registrado_inc.html");
		$t->set_var("volver",$_SERVER['REQUEST_URI']);
		return $t->parse("template");
	}
	else
	{
		$id_cliente=$_COOKIE['id_cliente'];
		$rs=$conn->execute("select  * from clientes where id=$id_cliente");

		if($rs===false)
		{
			header("Location: /error.php?error=error en la base de datos");
			exit;
		}
		$cliente=$rs->fields["usuario"];

		$t->set_file('template','cliente_registrado_inc.html');
		$t->set_var('cliente',$cliente);
		$t->set_var('volver',$_SERVER['REQUEST_URI']);

		return $t->parse('template');
	}
}

include("func_registro.inc.php");
include("hachelib.php");
$__logueado=false;
if(isset($_COOKIE['id_cliente']))
{
	$__logueado=true;
}

$time_start=getmicrotime();
/******************************************
muestra los datos del registro previamente realizado y presenta las opciones de pago
*/
if(!isset($id_operacion) or $id_operacion=="" or !isset($dominio) or $dominio=="")
{
	print "Falta el id de la operación o el dominio.";
	exit;
}
include("basededatos.php");
if(!$conn)die("error en las bases de datos, intentelo luego");
$id_operacion=trim($id_operacion);
$rs=$conn->execute("select * from solicitados where id=$id_operacion");

if(!$rs) die("identificador erroneo");
//registro de la cookie para mantener el identificador para pago por cliente.
setcookie("id_nombremania","$id_operacion",time()+3600,"/");

$reg=$rs->fields;
include_once("hachelib.php");
//var_crudas($reg);
// arreglo de algunas variables
if(!ereg("$dominio",$reg["domain"]))
{
	$error=urlencode("Dominio inv&aacute;lido.");
	header("Location: /error.php?error=$error\n");
	exit;
}
$__pagado=false;
if($reg['nm_cod_aprob']<>'')
{
	$__pagado=true;
}


$dominios=str_replace("**",'<br>',$reg['domain']);
$vinculodepago="<form action=\"https://tpv.4b.es/tpvv/teargral.exe\" method=\"post\" target=\"pago_tarjeta\">
<input type=\"hidden\" name=\"id_operacion\" value=\"$id_operacion\"><input type=\"hidden\" name=\"tienda\" value=\"PI00008144\"><input type=\"hidden\" name=\"idioma\" value=\"es\"><input type=\"submit\"  value=\"Pago con tarjeta\"></form>";

$datos=array();
include('conf.inc.php');

foreach(array_keys($contact_fields) as $campo)
{
	$datos["billing_$campo"]=$rs->fields["billing_$campo"];
}

if($__logueado)
{
	$tiene_saldo=false;
	$sql_saldo="select sum(importe) as saldo from transacciones where id_cliente=$id_cliente";
	//if($debug){echo $sql;exit;}
	$saldo=$conn->execute($sql_saldo);
	if($saldo===false)
	{
                $__nsaldo=0;
	}
	else
	{
		$__nsaldo=$saldo->fields["saldo"];
	}
}

$tpl="paso6/datos_pago.html";
include("class.templateh.php");
$t= new Template("templates","remove");
$t->set_file("template", "$tpl");

$t->set_var("reg_text", $reg_types[$reg["reg_type"]]);

while (list($k,$v)=each($datos)){
      $t->set_var($k,$v);
    }
    if (!$__pagado){
    $precio_ptas=round($reg['nm_preciototal']*166.386,0);
    $t->set_var('nm_preciototal',"&euro; ".$reg["nm_preciototal"]);
		$t->set_var('nm_convertir',str_replace(".",",",$reg["nm_preciototal"]));		
    $t->set_var('nm_preciototal_ptas',$precio_ptas." ptas");
    $t->set_var('vinculodepago',$vinculodepago);
    $t->set_var('id_operacion',$id_operacion);

$volver=$_SERVER['REQUEST_URI'];
	if(!$__logueado)
	{

    	$t->set_var('CAJA_LOGIN',get_content('cliente_no_registrado_inc.html',array('volver'=>$volver),"../clientes/templates/"));
	    $t->parse('NOLOGUEADO','NOLOGUEADO',true);
     }
	else
	{
		$tiene_saldo = ($reg['nm_preciototal']<=$__nsaldo) ? true:false;
		if($tiene_saldo)
		{
			$t->set_var('dominio',$_GET['dominio']);
     $t->parse("CONSALDO","CONSALDO",true);
     $t->set_var("CAJA_LOGIN",get_content("cliente_registrado_inc.html",array("clientes"=>"CLIENTE","volver"=>$volver),"../clientes/templates/"));
//     $t->parse("cliente","");

}
   else {
     $t->parse("SINSALDO","SINSALDO",true);
}
     
		 }
         $t->parse("nopagado","nopagado",true);
		 }
        else {
        $t->parse("pagado","pagado",true);
            }
$t->set_var("caja_login",caja_login());
$t->set_var("id_operacion",$id_operacion);
$t->set_var("dominios",str_replace("**",", ",$reg["domain"]));
$t->pparse("template");

if($debug)debug();

?>