<?
//  id de factura codificado con base64_encode($id."-nm")

include "basededatos.php";

$conn->debug=0;
$id_request= sscanf (base64_decode($id) , "%d-nm");
$id=$id_request[0];


$sql="select * from facturas where id=$id";
$numero=$id;
$sql="select facturas.* , solicitados.* from facturas,solicitados where facturas.id_solicitud=solicitados.id and facturas.id=$id";
//$conn->debug=1;

$rs=$conn->execute($sql);



//print_r($rs->fields);


include "EasyTemplate.inc.php";
$tpl="./factura.html";
if (isset($pesetas )) $tpl="./factura_ptas.html";


$t=new EasyTemplate($tpl);

extract($rs->fields);

$precio_sin_iva=$precio/1.21;
$total_iva=round($precio_sin_iva*0.21,2);
$t->assign("total_iva",$total_iva);
$t->assign("precio_total",number_format($precio,2));
$t->assign("precio_sin_iva",round($precio_sin_iva,2));
$t->assign("concepto",$concepto);
$t->assign("anos",$period);
$fecha1=date("d-m-Y",$fecha);
$hora=date("h:i",$fecha);
$t->assign("fecha",$fecha1);
$t->assign("hora",$hora);
$t->assign("numero",$numero);
$t->assign("nif",$nif);

if($_GET['modo'])
{
	$t->assign("nombre",$rs->fields['nombre']);
	$t->assign("direccion",$rs->fields['direccion']);
	$t->assign("provincia",$rs->fields['provincia']);
	$t->assign("cod_postal",$rs->fields['codigo_postal']);
	$t->assign("poblacion",$rs->fields['poblacion']);
}
else
{
	$nombre=$rs->fields["billing_org_name"];
	$t->assign("nombre",$nombre);
	
	$direccion=$rs->fields["billing_address1"];
	$t->assign("direccion",$direccion);
	
	$provincia=$rs->fields["billing_state"];
	$t->assign("provincia",$provincia);
	
	$cod_postal=$rs->fields["billing_postal_code"];
	$t->assign("cod_postal",$cod_postal);
	
	$poblacion=$rs->fields["billing_city"];
	$t->assign("poblacion",$poblacion);
}
$t->easy_print();
print $t->error;
?>
