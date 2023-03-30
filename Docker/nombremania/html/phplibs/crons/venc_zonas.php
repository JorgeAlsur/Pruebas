<?
/* 
 Llamar a este archivo pasando los dias que queremos analizar hacia adelante.
 normalmente 7 dias 
 llamada venc_zonas.php?dias=7
 si se vence la zona y tiene dias pagados deberia comprar mas zonas y enviar un email por las zonas que no tenemos que renovar. Baja manual.
*/

IF (!ISSET($dias)){
?>
<h4> analisis de vencimiento en zoneedit (analiza fechas de vencimiento  no creditos)
<form name=zonas action=<?=$PHP_SELF?> method=get>
<br>
cantidad de DIAS para analizar vencimiento : 
<input type=text name=dias size=5>
<input type=submit >

</form>
<?
}
else {
ob_start(); 
$dias=$dias+0; //normaliza a enteros
$hasta= time()+ (24*60*60*$dias);
$hasta = date("Y-m-d",$hasta);
$desde=("2002-01-01");
$variacion = 25;  // (dias)
include "hachelib.php";
$server= "www.zoneedit.com";
$pass="luison:luciano";  //usuario:password de zoneedit.

//&rpt=zone&filter=expire&dfrom=2002-01-17&export=F
$comando="http://luison:luciano@www.zoneedit.com/auth/export.html?type=RPTS&rpt=zone&filter=expire&dfrom=$desde&dto=$hasta&export=T&ext=.csv";
$datos=file("$comando");
$campos="";
$venciendo=array();
$result=array();
$j=0;
echo "Obtenidos desde zoneedit con el siguiente comando <pre>$comando</pre>".count($datos) ;
var_crudas($datos);
if (count($datos) <=1){
echo "Nada recuperado desde zoneedit, no vence nada";

}
else {
foreach($datos as $linea){
						if ($campos==""){
							 $campos = explode(",",$linea);
						}	 
						else {
						$aux=explode(",",$linea);
						$n=0;
						foreach($campos as $k){
								$k=trim($k);						
										$result[$j][$k]=$aux[$n];
										$n++;				
						}						
						}
$j++;
}
include "basededatos.php";
$conn->debug=true;
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$error="";

foreach($result as $zonas){

$zonas["Expiredate"] = substr(str_replace("-","/",$zonas["Expiredate"]),0,18);

if (strtotime($zonas["Expiredate"]) <= strtotime("+$dias days")){
$comprado_en_zedit_hasta =$zonas["Expiredate"];
echo "<br>vence".strtotime($zonas["Expiredate"]) . " --now +$dias days--".strtotime("+$weeks week");
echo "<table><tr><td> datos de zoneedit<br>";
var_crudas($zonas);
echo "</td><td>datos de nombremania<br>";
$zona= $zonas["Zone"]; 
$sql="select * from zonas where dominio='$zona'";
$sql = $conn->execute($sql);
if ($sql===false ) {
 	echo "error en recuperacion de cliente de zonas revisar dominio = $zona. "; 
 }elseif ($conn->affected_rows()<=0) {
		echo "zona no creada en las tablas de nombremania ..!!!!!!!!CORREGIR!!!!"; 
 } else {
 var_crudas($sql->fields);
 echo "Fecha de venta : ". date("Y-m-d",$sql->fields["fecha"]);
 echo "<br>Periodos :".$sql->fields["period"];
 $vendido_hasta = $sql->fields["fecha"] + ($sql->fields["period"]*365*24*60*60);
 echo " <br>Vendido hasta : ".date("Y-m-d",$vendido_hasta)."<br>"; 
 $vendido_hasta -= ($variacion * 24*60*60);  // se refiere a el desfase de dias entre la comprar del dominio y la zona de los primeros registros
 echo " <br>Vendido hasta menos variacion: ".date("Y-m-d",$vendido_hasta)."<br>";
 if ( $vendido_hasta > strtotime($comprado_en_zedit_hasta)){
 		echo "<h3>Deberiamos comprar</h3>";
		echo "Comprado a zedit hasta el  ".$comprado_en_zedit_hasta. "<br> vendido al cliente hasta: ". date("Y-m-d",$vendido_hasta)."<br>";
		$creditos[]=$sql->fields["dominio"]; 		
 }elseif ($vendido_hasta < strtotime("+2 days")) {
     echo "Venciendo en dos dias ";
		 $venciendo[]=$sql->fields["dominio"];			 
 }
 }
  echo "</td></tr></table>";
 }				
}
echo "Agregar creditos a las siguientes zonas<br>";
var_crudas($creditos);
include("zoneedit.php");

}
$salida = ob_get_contents(); 
ob_end_clean();
print "<hr>$salida<hr>";
mail("jose@alsur.es","Resumen de vencimientos de zonas", $salida);
if (count($venciendo)>0){
	 mail("soporte@nombremania.com", "vencimiento de zonas", "estas zonas vencen en zoneedit: ".explode("\n",$venciendo));
} 
/*
if (!agregar_credito("zonavr.com")){
	 echo "error nada agregado";
}else 
  {
	echo "agregado el credito a ".$creditos[8];
	}
	*/
}
?>

