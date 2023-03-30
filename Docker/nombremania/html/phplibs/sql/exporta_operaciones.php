<?

include "basededatos.php";
include "hachelib.php";
$sql="select id,domain,date_format( from_unixtime(fecha),'%d-%m-%Y') as fecha,period,tipo,reg_type from operaciones ";
$rs=$conn->execute($sql);
echo "<html><body>";
echo "<code>";$i=0;
while (!$rs->EOF){
	$aux=limpia_rs($rs->fields);
$i++;
if ($i==1){
	$a=array_keys($aux);
  $z=  implode(", ",$a);
  echo htmlentities($z)."<br>";
} 


	$a=array_values($aux);
  $z=  implode(", ",$a);
  echo htmlentities($z)."<br>";
	$rs->movenext();     
}
echo "</code>";
echo "</body></html>";

?>
