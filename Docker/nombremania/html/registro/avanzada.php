<?
if (!isset($palabra1) or !isset($palabra2)){
    mostrar_error_nm("Las dos palabras deben ser ingresadas para poder componer la consulta.");
    exit;
    }
if (!isset($palabra1) or trim($palabra1)=="") {
    $palabra1=$palabra2;
    unset($palabra2);
}
$p1=strtolower(trim($palabra1));
$p2=strtolower(trim($palabra2));
if (substr($p1,-1)=="s") $p3=substr($p1,0,-1);
if (substr($p2,-1)=="s") $p4=substr($p2,0,-1);
$opciones=array();
$separadores=array("-");
       $opciones[]=$p1.$p2.".com";
       $opciones[]=$p2.$p1.".com";
       if (isset($p3)) $opciones[]=$p2.$p3.".com";
       if (isset($p4)) $opciones[]=$p1.$p4.".com";
foreach($separadores as $sep){
       $opciones[]=$p1.$sep.$p2.".com";
       if (isset($p3)) $opciones[]=$p2.$sep.$p3.".com";
       if (isset($p4))  $opciones[]=$p1.$sep.$p4.".com";
    }
include "hachelib.php";
include "func_registro.inc.php";
include "race.php";
include "conf.inc.php";
$x=array();

if ($sugerencia==1) {
$prefijos=array("todo","e-","i-");
$sufijos=array("info","-red");
foreach($prefijos as $pre){
    $opciones[]=$pre.$p1.".com";
    $opciones[]=$pre.$p2.".com";
    }
foreach($sufijos as $suf){
    $opciones[]=$p1.$suf.".com";
    $opciones[]=$p2.$suf.".com";
    }
}
foreach($opciones as $opc){
    $x[]= utf8_encode($opc);
   }
$x=opensrs_lookup_race_bulk($x);
var_crudas($x);
?>
