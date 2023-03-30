<?
include "class.templateh.php";
include "precios_dinamicos.inc.php";
$t = new Template(".");
$t->set_file("template" ,"precios_ventana_tpl.html");
if (isset($pesetas) and $pesetas==1){
    $moneda="pesetas";
    $tabla= tabla_precios($moneda,"$PHP_SELF");
		$t->set_var("TABLA_PRECIOS",$tabla);
	}else {
    $moneda="euros";
    $tabla= tabla_precios($moneda,"$PHP_SELF?pesetas=1");
		$t->set_var("TABLA_PRECIOS",$tabla);	

}
$t->set_var("CGI",$PHP_SELF);

$t->pparse("template");


?>

