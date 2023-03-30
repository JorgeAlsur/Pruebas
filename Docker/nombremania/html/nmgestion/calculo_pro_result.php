<?

include("basededatos.php");


$dominio=$_POST["dominio"];
$fi=$_POST["fi"];
$ff=$_POST["ff"];
$tipo=$_POST["tipo"];
$id=$_POST["id"];

echo "antes=$tipo despues=$new_tipo<br/>";

if(!strcasecmp($tipo,"ESTANDAR") || (!strcasecmp($tipo,"PRO2")&&!strcasecmp($new_tipo,"PRO3")))
{
	$modo="upgrade";
}
else $modo="downgrade";

echo "$modo<br/>";

$gastos_gestion=10;


//obtener la diferencia de meses entre ff y fi
//normalizar
list($yf,$mf,$df) = explode("-",$ff);
list($yi,$mi,$di) = explode("-",$fi);

$totalf=(($yf-1900)*12 ) + $mf;
$totali=(($yi-1900)*12 ) + $mi;
	
//echo "$totalf $totali<br/>";
	
$dif= $totalf-$totali;
if($dif<0)
{
	if($modo=="downgrade")$dif=0;
	else
	{
		echo "La fecha final es necesaria y debe ser superior a la fecha inicial.";
		exit;
	}
}
echo "diferencia en meses : ".$dif."<br>";
//$r = ($dif/12,2);

if($dif<=12)$rango=0;
else if($dif<=48)$rango=1;
else $rango=2;

$sql="select * from precios where producto='pro2' or producto='pro3';";
$rs=$conn->execute($sql);
	
$temp["PRO2"][0]=$rs->fields["pxrango1"];
$temp["PRO2"][1]=$rs->fields["pxrango2"];
$temp["PRO2"][2]=$rs->fields["pxrango3"];
$rs->movenext();
$temp["PRO3"][0]=$rs->fields["pxrango1"];
$temp["PRO3"][1]=$rs->fields["pxrango2"];
$temp["PRO3"][2]=$rs->fields["pxrango3"];

$precio=$temp[$new_tipo][$rango];

//echo "precio=$precio new_tipo=$new_tipo rango=$rango<br/>";

if($modo=="downgrade")echo "en Downgrade no hay precio que valorar.<br/>";
else
{
	echo "precio anual=$precio&euro;<br/>";
	$precio = $gastos_gestion + $precio;
	$masiva=$precio*1.21;
	echo "El precio de contratacion de los servicios de DNS avanzado para su dominio hasta el $df-$mf-$yf es de $precio&euro; + IVA , total $masiva&euro;";
}
?>
<form action="<?=$modo?>.php" method="post">
<input type="hidden" name="id" value="<?=$id?>"/>
<input type="hidden" name="dominio" value="<?=$dominio?>"/>
<input type="hidden" name="precio" value="<?=$precio?>"/>
<input type="submit" name="<?=$modo?>" value="<?=$modo?>"/>
</form>