<?
include "basededatos.php";
//$con=conecta();
echo "Respuesta del banco despues de sus procesos";
print "<br>Rresultado de la operacion en banco : $result<br>";
if ($result==1){
	//operacion satisfactoria
	print "Referencia de la compra  $pszPurchorderNum<br>";
	print "Codigo de aprobacion : $pszApprovalCode<br>";
	print "id transaccion : $pszTxnID<br>";
  $sql="update solicitados set nm_aprob_banco = '$pszApprovalCode' where id=$pszPurchorderNum";
	print $sql;
  $rs=mysql_query($sql);
	if ($rs)  print "grabacion con exito";
}
else { 
print "operacion cancelada o fallida";
}

?>