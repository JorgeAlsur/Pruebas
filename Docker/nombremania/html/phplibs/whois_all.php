<?
/*
Este script, solo con lanzarlo, barre todos los nombres de dominios del fichero
"dominios.txt" (mismo directorio que el este script) y lanza un whois por cada uno.
Por cada uno también se consulta en la base de datos de nombremanita, tabla operaciones,
para ver si es pro o no. Finalmente, guarda en la tabla zoneedit una entrada por cada
uno de ellos, con los datos de si la respuesta del whois contiene la palabra nombremania,
la palabra zoneedit, si es pro y el periodo de contratación en años. Nótese que si ya
hay datos en la base de datos, estos se añadirán al final, por lo que antes de lanzarlo
es conveniente borrar todos los datos de zoneedit para actualizarlos todos de nuevo.

*/
include("whois/main.whois");
include("basededatos.php");
$dominios_=file("dominios2.txt");
$flujo=fopen("resultado.txt","w");
$k=0;

/*$cadena[]="hola";$cadena[]="adios";$cadena[]="fin";
echo implode(" ",$cadena);
echo "<br/>".implode($cadena," ");
exit;*/

foreach($dominios_ as $dominio)$dominios[]=rtrim($dominio);

if(is_array($dominios))
foreach($dominios as $dominio)
{

	//$local=mysql_connect("home.alsur.es","program","mysql2008");
	//mysql_select_db("nombremania",$local);

	$k++;
	$whois = new Whois($dominio);
	$result = $whois->Lookup();
	if(is_array($result))$texto=@implode($result["rawdata"],"\n");
	else $texto="";
	$texto=ereg_replace("'"," ",$texto);
	if(strpos($texto,'connection limit reached',0))
	{
		echo "Connection Limit Reached.";
		break;
	}
	else 
	{
		if(!strpos($texto,'NombreMania',0))
		{
			echo "$k NO contiene NombreMania";		
			$nombremania=0;
		}
		else
		{
			echo "$k SI contiene Nombremania";
			$nombremania=1;
		}
		if(!strpos($texto,'ZONEEDIT',0))
		{
			echo "$k NO contiene ZONEEDIT";		
			$zoneedit=0;
		}
		else
		{
			echo "$k SI contiene ZONEEDIT";
			$zoneedit=1;
		}
	}
	$dom="";
	$sql="select id,tipo,fecha,period from operaciones where domain='$dominio';";
	//echo $sql;
	$rs=$conn->execute($sql);
	$dom=$rs->fields["tipo"];

	if($dom=="pro" || $dom=="PRO" || $dom=="pro2" || $dom=="PRO2" || $dom=="pro3" || $rdom=="PRO3")
	{
		$filtrados[]=$dominio;
		echo " y SI PRO ";
		$pro=1;
	}
	else 
	{
		echo " y NO es PRO ";
		$pro=0;
	}
		
	
	$fecha=date("Y/m/d",$rs->fields['fecha']);
	$period=$rs->fields['period'];
	if($period=="")$period=0;
	
	$sql="insert into zoneedit values(NULL,'$dominio',$nombremania,$zoneedit,$pro,'$fecha',$period,'$texto');";
	echo $sql."<br/>";
	//mysql_query($sql,$local);
	$conn->execute($sql);
	
	sleep(8);
	//exit;
}
else echo "El fichero está vacío.";

/*$resultado=array_diff($dominios,$filtrados);
	
if(is_array($resultado))foreach($resultado as $dominio)
{
	fwrite($flujo,$dominio."\n");
}
fclose($flujo);
*/
?>
