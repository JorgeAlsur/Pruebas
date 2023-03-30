<?
include_once('conf.inc.php');
include("basededatos.php");
include('hexonet.php');

function todas_zonas()
{
	$command=array("command" => QueryDNSZoneList);
	//else $command=array("command" => DeleteDNSZone, "dnszone" => $zona.'.');
	//echo "<pre>";print_r($command);echo "</pre>";
	$que=hexonet($command);
	//echo "<pre>";print_r($que);echo "</pre>";exit;
	if($que["code"]==200)
	{
		//echo "<pre>";print_r($que["xml"]->PROPERTY->DNSZONE);echo "</pre>";exit;
		return $que["xml"]->PROPERTY->DNSZONE;
	}
	else return false;
}


if($_GET['param']=='zonas')
{
	$conn->debug=true;
	$zonas=todas_zonas();
	
	//print_r($zonas);exit;
	
	$sql_delete="DELETE FROM hexonet_zonas";
	$rs=$conn->execute($sql_delete);
	
	foreach($zonas as $zona)
	{
		if(substr($zona, -1, 1)=='.') $zona=substr($zona, 0, -1);
		$registros=registros_zona('alsur.hexonet.net', $zona);
		//print_r($registros);exit;
		if($registros)
		{
			foreach($registros as $registro)
			{
				$rr=$registro['rr'];
				$tipo=$registro['tipo'];
				$dnsto=$registro['dnsto'];
				$forward=$registro['forward'];
				$dnsfrom=$registro['dnsfrom'];
				$rank=$registro['rank'];
				$disimulada=$registro['disimulada'];
				
				$sql_insert="INSERT INTO hexonet_zonas (domain, rr, tipo, dnsto, forward, dnsfrom, rank, disimulada) VALUES ('$zona', '$rr', '$tipo', '$dnsto', '$forward', '$dnsfrom', '$rank', '$disimulada')";
				$rs=$conn->execute($sql_insert);
			}
		}
		
	}
	
	
}	
else echo "Indica parametro.";
?>