<?
include("basededatos.php");

if($_GET['prueba']=='yes')
{
	$sql="select Zone from export_zoneedit LIMIT 100";
	$rs=$conn->execute($sql);
	
	
	while(!$rs->EOF)
	{
		//$host=system('host -W 1 -a '.$rs->fields['Zone'].' | grep NS');
		$host=array();
		//echo $rs->fields['Zone'];exit;
		exec('host -W 1 -a '.$rs->fields['Zone'].' | grep -w NS', $host);
		$host_with_NS=false;
		foreach($host as $h)
		{
			$pos_NS=strpos($h, 'NS');
			if($pos_NS) 
			{
				$pos_NS=$pos_NS+3;
				$host_NS=substr($h, $pos_NS);
				/*if(eregi('nombremania', $host_NS)) 
				{
					$host_with_NS=true;
					break;
				}
				*/
				if(eregi('zoneedit', $host_NS) || eregi('dnsvr', $host_NS)) {
					$host_with_NS=true;
					break;
				}
				//if(eregi('zoneedit', $host_NS)) $host_with_NS=true;
				//if(eregi('dnsvr.com', $host_NS)) $host_with_NS=true;
				//$hosts_NS[]=array('host' => $rs->fields['Zone'], 'NS' => $host_NS);
			}
		}
		if($host_with_NS)
		{
			$hosts_NS[]=$rs->fields['Zone'];
		}
		
		if(!$host_with_NS)
		{
			$NOhosts_NS[]=$rs->fields['Zone'];
		}
		
		
		$rs->movenext();
	
	}
	
	echo "<p>----HOST----</p>";
	echo "<pre>";
	print_r($hosts_NS);
	echo "</pre>";
	echo "<p>----NOHOST----</p>";
	echo "<pre>";
	print_r($NOhosts_NS);
	echo "</pre>";
}
else echo "Falta parametro1.";
?>