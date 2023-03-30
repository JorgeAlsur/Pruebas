<? 
/* calculo de comisiones para los clientes registrados
se calculan en base a la cantidad de registros (numero de dominios referidos)
en el periodo de tiempo N anterior al registro en curso 
hay que hacer una consulta para saber el porcentaje a aplicar
SELECT count(*) as cantidad, dominios.* FROM `dominios`,operaciones where 
id_operacion = operaciones.id and 
operaciones.affiliate_id>100 and (operaciones.reg_type='new' or operaciones.reg_type='transfer') group by operaciones.affiliate_id 
*/
function calcula_comision($affiliate_id){
Global $conn;
include_once( "basededatos.php");
include_once( "hachelib.php");
$conn->debug=true;

$rs=$conn->execute("select op_liquidadas from clientes where id = $affiliate_id");
if ($rs===false or $conn->affected_rows()<=0){
	 							return 0;
}

$ultima_liquidada = $rs->fields["op_liquidadas"];
//  falta agregar la fecha de inicio para la cuenta de dominios !!!!!!!!!!!!!!!!!
//   comisiones desde => comision en porcentaje (0 hasta 10 => 2%, 11 hasta 20 4%)
$comisiones = array( 1 =>5, 11=>7,31=>9,51=>10);

$hasta_fecha = time()-(120*24*60*60); // 4 meses

$sql="SELECT count(*) as cantidad, dominios.* FROM `dominios`,operaciones where 
id_operacion = operaciones.id and operaciones.affiliate_id=$affiliate_id  and id_operacion>$ultima_liquidada and (operaciones.reg_type='new' or operaciones.reg_type='transfer') and operaciones.fecha >= $hasta_fecha  group by operaciones.affiliate_id"; 
$rs=$conn->execute($sql);
/*var_crudas($rs);
exit;*/
$cantidad = $rs->fields["cantidad"] ; 
//var_crudas($cantidad);
if ($cantidad==0){
	 return 0;
}else {
arsort($comisiones); //invierto el orden de las comisiones para poder analizarlas en un bucle
while  (list($rango,$porcentaje) = each($comisiones )){
										if ($cantidad >= $rango)	  break;	// encontrado se aplica $porcentaje					   										
}
return $porcentaje;
}


}

// asi debe llamarse la funcion para obtener el porcentaja a aplicar
//echo "porcentaje a aplicar : $afiliado ".calcula_comision($afiliado);  


 ?>
  