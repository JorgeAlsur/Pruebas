<?
//ini_set("include_path",$_SERVER['DOCUMENT_ROOT']."/phplibs");
include_once('conf.inc.php');
include("basededatos.php");

function prueba_hxnet()
{
	$url='http://api.ispapi.net/api/call.cgi?s_login=alsur.hexonet.net&s_pw=Luciano08&command=StatusDNSZone&dnszone=alsur.es.';
	$temp=file($url);
	//print_r($temp);
	$temp=join("\n",$temp);
	if(ereg("CODE",$temp))
	{
	    $pos1=strpos($temp,"CODE=\"");
	    $cod=substr($temp,$pos1+6,3);
	}
	$salida=array();
	$salida["codigo"]=$cod;
	//$temp=str_replace("cloaked","cloaked=\"true\"",$temp);

	$salida["xml"]=$temp;
	return $salida;
}

function zoneedit($enviar)
{
	include_once('conf.inc.php');
	if(strtolower($_test_or_live) == "test")
	{
		$pass="domaindemo.com:2008";
		$server="dns.nombremania.com";
		//echo "pass : $pass";
	}
	else
	{
		//echo "pass : $pass";
		$server="dns.nombremania.com";
		$pass="luison:existe95";
	}
	$url="http://$pass@$server/auth/admin/command.html?$enviar";
	//print $url;
	$temp=file($url);
	//print_r($temp);
	$temp=join("\n",$temp);
	if(ereg("failure",$temp))
	{
	    $pos1=strpos($temp,"failure code=\"");
	    $cod=substr($temp,$pos1+14,3);
	}
	else
	{
    	$pos1=strpos($temp,"success code=\"");
	    $cod=substr($temp,$pos1+14,3 );
	}
	$salida=array();
	$salida["codigo"]=$cod;
	//$temp=str_replace("cloaked","cloaked=\"true\"",$temp);

	$salida["xml"]=$temp;
	return $salida;
}

//function registros_zona($usuario,$zona)
function registros_zona($zona)

{
	//$usuario=multilingue_temp($usuario);
	//$zona=multilingue_temp($zona);

	include_once("xml.php");
	
	
	$comando="command=ViewRecord&user=$usuario&zone=$zona";
	$recibo=zoneedit($comando);

	$texto=$recibo["xml"];
	/*print "<pre>";
	print htmlentities($texto);
	print "<pre>";*/
	$xml = new XML();
	//$texto=implode("",file($file));
	$xml->load_file("",$texto);
	$consulta="/ze[1]/response[1]/*";

	$results=$xml->evaluate($consulta);
	$registros=array();
	foreach($results as $registro)
	{
		// Retrieve information about the person.
		$re=$registro;
		$tipo=$xml->get_content($re."/attribute::type");
		$dnsfrom=$xml->get_content($re."/attribute::dnsfrom");
		$dnsto=$xml->get_content($re."/attribute::dnsto");
		$forward=$xml->get_content($re."/attribute::forward");
		$id=$xml->get_content($re."/attribute::id");
		if($tipo=="MX")
		{
			$rank=$xml->get_content($re."/attribute::rank");
			$registros[]=array("dominio" => $zona, "tipo"=>$tipo,
			"dnsto"=>$dnsto, "forward"=>$forward , "dnsfrom"=>$dnsfrom, "id"=>$id ,"rank"=>$rank);
		}
		else
		{
			$disimulada=$xml->get_content($re."/attribute::cloaked");
			$registros[]=array("dominio" => $zona, "tipo"=>$tipo,
                "dnsto"=>$dnsto, "forward"=>$forward , "dnsfrom"=>$dnsfrom, "id"=>$id ,"disimulada"=>$disimulada );
		}
	}
	return $registros;
}

if($_GET['param']=='tabla_zoneedit')
{
	$sql="select * from zoneedit_dominios_validos";
	$rs=$conn->execute($sql);
	
	while(!$rs->EOF)
	{
		$registros=registros_zona($rs->fields['Zone']);
		if($registros)
		{
			foreach($registros as $registro)
			{
				$id=$registro['id'];
				$dominio=$registro['dominio'];
				$tipo=$registro['tipo'];
				$dnsto=$registro['dnsto'];
				$forward=$registro['forward'];
				$dnsfrom=$registro['dnsfrom'];
				$rank=$registro['rank'];
				$disimulada=($registro['disimulada']==true) ? 1 : 0;
				if($tipo=='') continue;
				if($tipo=='MX')
				{
					$sql2="INSERT INTO dns_zoneedit (id, domain, tipo, dnsto, forward, dnsfrom, rank) VALUES ('$id', '$dominio', '$tipo', '$dnsto', '$forward', '$dnsfrom','$rank')";
				}
				else
				{
					$sql2="INSERT INTO dns_zoneedit (id, domain, tipo, dnsto, forward, dnsfrom, disimulada) VALUES ('$id', '$dominio', '$tipo', '$dnsto', '$forward', '$dnsfrom','$disimulada')";
				}
				$conn->execute($sql2);
			}
		}
		
		$rs->movenext();
	}
	
	$mas_dominios=array('alsur.com', 'alsur.es', 'bajarsealmoro.com', 'golfinspain.com', 'marinasun.com', 'nombremania.com', 'nombremania.net', 'ozinspain.com', 'surlandia.com',
'valderramaestates.com', 'alsur.info', 'alsur.net', 'elsurexiste.com', 'juanperro.com', 'peterleonard.com', 'regalaundominio.com');
	foreach($mas_dominios as $mas_d)
	{
		$registros=registros_zona($mas_d);
		if($registros)
		{
			foreach($registros as $registro)
			{
				$id=$registro['id'];
				$dominio=$registro['dominio'];
				$tipo=$registro['tipo'];
				$dnsto=$registro['dnsto'];
				$forward=$registro['forward'];
				$dnsfrom=$registro['dnsfrom'];
				$rank=$registro['rank'];
				$disimulada=($registro['disimulada']==true) ? 1 : 0;
				if($tipo=='') continue;
				if($tipo=='MX')
				{
					$sql2="INSERT INTO dns_zoneedit (id, domain, tipo, dnsto, forward, dnsfrom, rank) VALUES ('$id', '$dominio', '$tipo', '$dnsto', '$forward', '$dnsfrom','$rank')";
				}
				else
				{
					$sql2="INSERT INTO dns_zoneedit (id, domain, tipo, dnsto, forward, dnsfrom, disimulada) VALUES ('$id', '$dominio', '$tipo', '$dnsto', '$forward', '$dnsfrom','$disimulada')";
				}
				$conn->execute($sql2);
			}
		}
		
	}
}
if($_GET['param']=='uno_zoneedit')
{
	$sql="select * from zoneedit_dominios_validos LIMIT 5";
	$rs=$conn->execute($sql);
	
	while(!$rs->EOF)
	{
		$registros=registros_zona($rs->fields['Zone']);
		if($registros)
		{
			echo "<pre>";print_r($registros);echo "</pre>";
		}
		
		$rs->movenext();
	}
}
elseif($_GET['param']=='prueba_hxnet')
{
	include_once("xml.php");
	$hxnet=prueba_hxnet();
	$texto=$hxnet["xml"];
	/*print "<pre>";
	print htmlentities($texto);
	print "<pre>";*/
	$xml = new XML();
	//$texto=implode("",file($file));
	/*$xml->load_file("",$texto);
	/*$consulta="[RESPONSE]";

	$results=$xml->evaluate($consulta);
	count($results);
	foreach($results as $result)
	{
		$i++;
		echo "<p>$i $result</p>";
	}*/
}
echo "FIN";




?>