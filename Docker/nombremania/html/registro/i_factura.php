<?
include "../phplibs/basededatos.php";
$conn->debug=0;
$sql="select * from facturas where id=$id";
$rs=$conn->execute($sql);
//print_r($rs);
include "../phplibs/EasyTemplate.inc.php";
$t=new EasyTemplate("./factura.html");
extract($rs->fields);
$t->assign("nombre",$nombre);
$t->assign("nif",$nif);
$t->assign("direccion",$direccion);
$t->assign("direccion",$direccion);
$t->assign("provincia",$provincia);
$t->assign("cod_postal",$codigo_postal);
$precio_sin_iva=$precio/1.21;
$total_iva=round($precio_sin_iva*0.21);
$t->assign("total_iva",$total_iva);
$t->assign("precio_total",$precio);
$t->assign("concepto",$concepto);
$t->assign("precio_sin_iva",round($precio_sin_iva));
$fecha1=date("d-m-Y",$fecha);
$hora=date("h:i",$fecha);
$t->assign("fecha",$fecha1);
$t->assign("hora",$hora);
$t->assign("poblacion",$poblacion);
$t->assign("numero",$id);
$t->easy_print();

print $t->error;


?>