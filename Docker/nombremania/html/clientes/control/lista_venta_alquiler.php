<?
require "verifica.php";
$id_cliente=$_COOKIE["id_cliente"];

include "basededatos.php";
$tpl="lista_venta.html";
if (isset($alquiler)){
$sql="select id,date_format(from_unixtime(fecha_alq),\"%d-%m-%Y\") as fecha ,dominio
 ,precio,precio_vta, maximo from alq_dominios  where affiliate_id=$id_cliente " ;

    }
else {
$sql="select id,date_format(from_unixtime(fecha_vta),\"%d-%m-%Y\") as fecha ,dominio
,precio from venta_dominios where affiliate_id=$id_cliente " ;

}
if (isset($orden)){
   $sql="$sql order by $orden ";
}


$rs=$conn->execute($sql);
if ($rs===false){
   print "error : ". mysql_error();
}
include "class.templateh.php";
$t = new Template(".","keep");
      $t->set_file("template","$tpl");
$rs->movefirst();
while (!$rs->EOF){
      $t->set_var("fecha",$rs->fields["fecha"]);
      $t->set_var("dominio",$rs->fields["dominio"]);
      $t->set_var("precio",$rs->fields["precio"]);
      $t->set_var("bgcolor", ($color==0)?"#E1E8F0":"#FFF0E1" );

if (isset($alquiler)){
  $t->set_var("precio_vta","&nbsp;".$rs->fields["precio_vta"]);
  $t->set_var("maximo","&nbsp;".$rs->fields["maximo"]);
$t->parse("solo_alquiler","solo_alquiler");

}
if (isset($alquiler)){
$t->set_var("venta_alquiler","alquiler");
 $t->parse("tit_solo_alquiler","tit_solo_alquiler");
$PHP_SELF=$PHP_SELF."?alquiler=1";
}
else {
$t->set_var("venta_alquiler","venta");
 $t->set_var("tit_solo_alquiler","");
 $t->set_var("solo_alquiler","");
$PHP_SELF=$PHP_SELF."?venta=1";
}
$t->set_var("orden_fecha","<a href=$PHP_SELF&orden=fecha>fecha</a>");
$t->set_var("orden_dominio","<a href=$PHP_SELF&orden=dominio>dominio</a>");
$t->set_var("orden_precio","<a href=$PHP_SELF&orden=precio>precio</a>");
$t->parse("ofertas","ofertas",true);
$rs->movenext();
$color=($color==0)?1:0;
}
$t->pparse("template");

?>
