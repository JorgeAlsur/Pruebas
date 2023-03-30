<?
include "hachelib.php";
if(isset($zonas) and is_array($zonas)){
		$sql="delete from zonas where id in (".implode(",",$zonas).")";
		echo $sql;						 								 
}

if(isset($dominios) and is_array($dominios)){
		$sql="delete from dominios where id in (".implode(",",$dominios).")";
		echo $sql;						 								 
}

if (isset($factura) and $factura==1){
	 $sql="select * from facturas where id = $numero_factura";
	 echo $sql;
	 $sql2="insert into facturas values(-$importe)";
echo $sql2;
}

?>