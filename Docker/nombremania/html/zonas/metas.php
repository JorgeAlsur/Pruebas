<?
include("zoneedit.php");
include("./defaultzona.php");
if($_COOKIE["admzona"]=="")
{
	$error=urlencode("Error en la llamada al sistema de Zonas, ingrese nuevamente o contacte a soporte@nombremanina.com");
	header("Location: /nm/error.php?error=$error");
	exit;
}
//$aux=base64_decode($k);
$aux=$k;

//list($usuario,$dominio)=split(":",$aux);


//include "../phplibs/procesator.php";
//$dominio="electrodavid.com";
//$usuario=calcula_userzone($dominio);
$dominio=$_COOKIE["admzona"];
$usuario=$_COOKIE["admzona"];

if($tarea=="" or !isset($tarea))
{
	mostrar_datos();
	//formulario();
	exit;
}

function mostrar_datos()
{
	global $PHP_SELF,$dominio,$k,$usuario,$tipos,$error_mf,$error_wf,$mensajes;
	$datosmetas=metas_zona($usuario,$dominio);
	/*print "<pre> dominio = $dominio -- zonas == $usuario";
	var_dump($datosmetas);
	print "</pre>";
	exit;*/

	$metas=  $datosmetas["metas"];
	$titulo=$datosmetas["titulo"];
	$metasdesc=$datosmetas["metadesc"];
	$texto=$datosmetas["texto"];

	include("class.templateh.php");
	$t=new Template("templates","remove");
	$t->set_file("template","metas.html");
	$t->set_var("DOMAIN_NAME",$dominio);
	$t->set_var("titulo",$titulo);
	$t->set_var("texto", $texto);
	$t->set_var("metasdesc",$metasdesc);
	$t->set_var("metas",$metas);
	$t->set_var("mensajes",$mensajes);
	//limpio el bloque no
	$t->set_var("NO","");
	if($debug)
	{
		include("debugh.php");
		$D = new LensDebug();
		$D->v($texto,"texto"); // display a variable and its type
		$D->v($titulo,"titulo"); // display a variable and its type
		$D->v($datosmetas);
	}
	$t->pparse("template");
}
?>