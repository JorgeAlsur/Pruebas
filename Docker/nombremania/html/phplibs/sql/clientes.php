<?
include "basededatos.php";
$conn->debug=1;
$sql_viejo="select * from dbpair.clientes ";

 $viejo = $conn->execute($sql_viejo);
 include "hachelib.php";
 $errores="";
while (!$viejo->EOF){
 $v=limpia_rs($viejo->fields);
 $sql=insertdb("clientes",$v);
 $xx=$conn->execute($sql);
 if ($xx===false) die("error en grabacion".$sql . mysql_error());
  $viejo->movenext();
}
?>