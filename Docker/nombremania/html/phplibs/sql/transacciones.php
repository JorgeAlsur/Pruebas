<?
include "basededatos.php";
$conn->debug=1;
$sql_viejo="select * from dbpair.transacciones ";

 $viejo = $conn->execute($sql_viejo);
 include "hachelib.php";
 $errores="";
while (!$viejo->EOF){
 $v=limpia_rs($viejo->fields);
 $v["importe"] = round($v["importe"] / 166.386 ,2); 
 $sql=insertdb("transacciones",$v);
 
 $xx=$conn->execute($sql);
 if ($xx===false) die("error en grabacion".$sql . mysql_error());
  $viejo->movenext();
}
?>