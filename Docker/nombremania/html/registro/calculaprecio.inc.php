<?
function precio($tipo,$anios,$dominios){
if (is_array($dominios)) {
$cantidad=count($dominios);
}
else {
$cantidad=$dominios;
}
$precios=array();
#       tipo   años = precio 
$precios["PRO"][1]=5650;
$precios["PRO"][2]=2*5650;
$precios["PRO"][3]=2*5650;
$precios["PRO"][4]=3*5650;
$precios["PRO"][5]=4*5650;
$precios["PRO"][6]=5*5650;
$precios["PRO"][7]=6*5650;
$precios["PRO"][8]=7*5650;
$precios["PRO"][9]=9*5650;
$precios["PRO"][10]=10*5650;

$precios["ESTANDAR"][1]=3650;
$precios["ESTANDAR"][2]=2*3650;
$precios["ESTANDAR"][3]=2*3650;
$precios["ESTANDAR"][4]=3*3650;
$precios["ESTANDAR"][5]=4*3650;
$precios["ESTANDAR"][6]=5*3650;
$precios["ESTANDAR"][7]=6*3650;
$precios["ESTANDAR"][8]=7*3650;
$precios["ESTANDAR"][9]=9*3650;
$precios["ESTANDAR"][10]=10*3650;


$precio_pesetas=$cantidad * $anios * $precio_unitario;
$precio_euros=$cantidad * $anios * $precio_unitario_euros;
$precio_pesetas= $precios[strtoupper($tipo)][$anios] * $cantidad;
return $precio_pesetas;

}

?>
