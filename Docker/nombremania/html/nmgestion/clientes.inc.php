<?
/*
  clientes.inc
  sopporte para agregar operaciones a clientes y consultas de saldos y demas cosas
  le pasamos un array vacio para recibir los errores
*/
function cargo_a_clientes($form,&$errores)
{
	global $conn;
	$sql=insertdb('transacciones',$form);
	//echo $sql;exit;
	$rs=$conn->execute($sql);
	return $conn->Insert_ID();
}
?>

