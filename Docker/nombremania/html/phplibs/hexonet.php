<?
/**

JOSE 16-03-2009:
Script creado para conectar con hexonet.

**/
//ini_set("include_path",$_SERVER['DOCUMENT_ROOT']."/phplibs");
include_once('conf.inc.php');

function hexonet($enviar)
{
	include_once('conf.inc.php');
	$test=false; // Si es true conecta con el servidor de prueba
				// si es falso conecta con el real
	$client = new SoapClient(NULL,					 
	array(
	//"location" => "http://93.190.235.176:8080",
        "location" => "http://93.190.235.176:8080",
	"uri" => "urn:Api",
	"style" => SOAP_RPC,
	"use" => SOAP_ENCODED,
	"trace" => 1
	));
	if($test) {	$usuario=array("s_login" => "alsur.hexonet.net", "s_pw" => "htLucianoCero8", "s_entity" => 1234); }
	else {		$usuario=array("s_login" => "alsur.hexonet.net", "s_pw" => "htLucianoCero8"); }
	$params=array(array_merge($usuario, $enviar));
	//$params = array(array("s_login" => "alsur.hexonet.net", "s_pw" => "Luciano08", "command" => "StatusDNSZone", "dnszone" => "alsur.es."));
	$result = $client->__call(
	"xcall",
	$params,
	array("uri" => "urn:Api", "soapaction" => "urn:Api#xcall")
	);
	# descomentar para comprobar los valores que llegan.
	/*	
	
	$parax=$params[0];
	foreach($parax as $key=>$param)
	{
		$p[]=$key."=".$param;
	}
	$pp=implode("&", $p);
	echo "<p>$pp</p>";
	print_r($params);
	*/
	//echo "<pre>"; print_r($result); echo "</pre>";
	$salida["code"]=$result->CODE;
	$salida["xml"]=$result;
	return $salida;
}

// agrega_usuario no tiene uso en hexonet

function agrega_usuario($usuario,$password="nombremania",$email="")
{
	$usuario=multilingue_temp($usuario);
	//$command="command=adduser&user=$usuario&pass=$password";
	$command=array("command" => "AddUser", "subuser" => $usuario, "password" => $password);
	$que=hexonet($command);
	if($que["codigo"]==200)
	{
		return true;
	}
	return false;
}

function agrega_zona($usuario,$zona)
{
	$usuario=multilingue_temp($usuario);
	$zona=multilingue_temp($zona);

	//$command="command=adduserzone&user=$usuario&zone=$zona&editmenu=F&typemenu=F";
	$command=array("s_user" => $usuario, "command" => CreateDNSZone, "dnszone" => $zona.'.');
	//echo "<pre>";print_r($command);echo "</pre>";
	$que=hexonet($command);
	//echo "<pre>";print_r($que);echo "</pre>";
	if($que["code"]<=300)
	{
		
		$dns["resultado"]=true;
		$dns["dns1"]="ns1.nombremania.net";
		$dns["dns2"]="ns2.nombremania.net";
		$dns["dns3"]="ns3.nombremania.net";
		return $dns;
	}
	else
	{
		$dns["resultado"]=false;
		return $dns;
	}	
}

function borrar_zona($usuario,$zona)
{
	$user_dis=false; // en principio da error hacer algunas funciones si se hace
					// con un sub-usuario, por lo que se deshabilita por si acaso.
	$usuario=multilingue_temp($usuario);
	$zona=multilingue_temp($zona);

	//$command="command=adduserzone&user=$usuario&zone=$zona&editmenu=F&typemenu=F";
	//if($user_dis) 
	$command=array("s_user" => $usuario, "command" => DeleteDNSZone, "dnszone" => $zona.'.');
	//else $command=array("command" => DeleteDNSZone, "dnszone" => $zona.'.');
	//echo "<pre>";print_r($command);echo "</pre>";
	$que=hexonet($command);
	//echo "<pre>";print_r($que);echo "</pre>";
	if($que["code"]==200) return true;
	else return false;
}


function registros_zona($usuario,$zona,$extended=false,$debugHXN=false)
{
	$usuario=multilingue_temp($usuario);
	$zona=multilingue_temp($zona);

	//include_once("xml.php");
	
	
	//$comando="command=ViewRecord&user=$usuario&zone=$zona";
	//$comando=array("command" => "QueryDNSZoneRRList", "dnszone" => $zona.".", "s_user" => $usuario, "extended" => 1);
	if($extended) $comando=array("command" => "QueryDNSZoneRRList", "dnszone" => $zona.".", "extended" => 1);
	else $comando=array("command" => "QueryDNSZoneRRList", "dnszone" => $zona.".");
	$recibo=hexonet($comando);

	//echo "<pre>";print_r($recibo);echo "</pre>";
	//$datos=$recibo["xml"];
	
	//print_r($recibo);exit;
	
	$code=$recibo['code'];
	if($code=="200")
	{
		$rr=$recibo['xml']->PROPERTY->RR;
		if($debugHXN == true) {print_r($rr);}
		if(count($rr)>0)
		{
			
			for($i=0;$i<count($rr);$i++)
			{
				
				
				$rr_split='';
				$dnsfrom='';
				$dnsto='';
				$forward='';
				$rank='';
				$tipo='';
				$disimulada='';
				$rr_split=explode(" ", $rr[$i]);
				$reemplazar=array(".$zona", "$zona");
				switch($rr_split[3])
				{
					case 'A':
					case 'CNAME':
					case 'PTR':
						$tipo=$rr_split[3];
						$dnsfrom=str_replace($reemplazar, "", substr($rr_split[0], 0, -1)); // debe acabar en punto
						if(substr($rr_split[4], -1, 1)=='.') $dnsto=substr($rr_split[4], 0, -1);
						else $dnsto=$rr_split[4];
						break;
					case 'X-SMTP':
						$tipo='MF';
						$dnsto=str_replace("@", '', $rr_split[4]);
						$forward=$rr_split[6];
						break;
					case 'X-HTTP':
						$tipo='WF';
						$dnsfrom=str_replace($reemplazar, "", substr($rr_split[0], 0, -1));
						$forward=$rr_split[5];
						break;
					case 'MX':
						$tipo=$rr_split[3];
						$dnsfrom=str_replace($reemplazar, "", substr($rr_split[0], 0, -1));
						$rank=$rr_split[4];
						$dnsto=substr($rr_split[5], 0, -1);
						break;						
				}
				if($rr_split[3]=='TXT')
				{
					
					if(count($rr_split>5))
					{
						for($j=5;$j<count($rr_split);$j++)
						{
							$rr_split[4].=' '.$rr_split[$j];
							if(count($rr_split)==($j+1)) break;
						}
					}
					$tipo=$rr_split[3];
					$dnsfrom=str_replace($reemplazar, "", substr($rr_split[0], 0, -1));
					$dnsto=$rr_split[4];
					
				}
				if($rr_split[3]=='X-HTTP' && $rr_split[4]=='FRAME') $disimulada = 1;
				if($rr_split[3]=='MX')
				{
					$registros[]=array("rr" => $recibo['xml']->PROPERTY->RR[$i], "tipo" => $tipo, "dnsto" => $dnsto, "forward" => $forward, "dnsfrom" => $dnsfrom, "rank" => $rank);
				}
				else
				{
					$registros[]=array("rr" => $recibo['xml']->PROPERTY->RR[$i], "tipo" => $tipo, "dnsto" => $dnsto, "forward" => $forward, "dnsfrom" => $dnsfrom, "disimulada" => $disimulada);
				}
				if($debugHXN == true) flush();
			}
			
		}
		else return false;
	}
	else return false;
	return $registros;
}

function borrar_registro($dominio,$registro,$usuario='')
{
	$user_dis=false; // en principio da error hacer algunas funciones si se hace
					// con un sub-usuario, por lo que se deshabilita por si acaso.
	if($usuario) $usuario=multilingue_temp($usuario);
	$dominio=multilingue_temp($dominio);
	
	if($user_dis) $command=array("command" => "UpdateDNSZone", "dnszone" => $dominio.".", "delrr0" => "% $tipo", "s_user" => $usuario, "extended" => 1);
	else $command=array("command" => "UpdateDNSZone", "dnszone" => $dominio.".", "delrr0" => $registro, "extended" => 1);
	//print_r($command);exit;
	$recibo=hexonet($command);
	//print_r($recibo);exit;
	if($recibo['code']<>"200") return false;
	else return true;
}



function agregar_registro($dominio,$tipo,$destinos,$usuario='')
{
	/*
	
	Todos los tipos deben pasar por esta funcion
	
	siguen este formato (los nombres de las variables da igual, importa el orden)
	si tipo A 			= dsnfrom=X&dnsto=Y
	si tipo CNAME 		= dnsfrom=X&dnsto=Y
	si tipo MX 			= dnsfrom=X&dnsto=Y&rank=Z
	si tipo PTR 		= dnsfrom=X&dnsto=Y
	si tipo TXT 		= dnsfrom=X&forward=Y
	si tipo MF 			= dnsto=X&forward=Y
	si tipo WF_FRAME 	= dnsfrom=X&dnsto=Y
	si tipo WF_REDIRECT = dnsfrom=X&dnsto=Y
	
	*/
	$user_dis=false;  // en principio da error hacer algunas funciones si se hace
					// con un sub-usuario, por lo que se deshabilita por si acaso.
	$usuario=multilingue_temp($usuario);
	$dominio=multilingue_temp($dominio);
	
	// ¿?
	$command=array("command" => "StatusDNSZone", "dnszone" => $dominio.".");
	$datos=hexonet($command);
	
	
	//$destinos=$desde[1].". ".$datos["xml"]->PROPERTY->SOATTL[0]." IN $tipo ".$hasta[1];
	if($tipo=='A')
	{
		list($hasta, $desde) = split("&", $destinos); // llegan en modo hasta&desde
		$desde=split("=", $desde);
		$hasta=split("=", $hasta);
		if($desde[1]=='') $desde[1]='@';
		//$destinos=$desde[1].". ".$datos["xml"]->PROPERTY->SOATTL[0]." IN A ".$hasta[1];
		//if($desde[1]!='@') $desde[1].=".$dominio";
		$destinos=$desde[1]." IN A ".$hasta[1];
	}
	elseif($tipo=='CNAME')
	{
		list($hasta, $desde) = split("&", $destinos);// llegan en modo hasta&desde
		$desde=split("=", $desde);
		$hasta=split("=", $hasta);
		if($desde[1]=='') $desde[1]='@';
		//$destinos=$desde[1].".$dominio IN CNAME ".$hasta[1];
		$destinos=$desde[1]." IN CNAME ".$hasta[1].".";
		//if($prueba_cname) { echo "<h1>$destinos</h1>";exit; }
	}
	elseif($tipo=='MX')
	{
		list($desde, $hasta, $rank) = split("&", $destinos);
		$desde=split("=", $desde);
		$hasta=split("=", $hasta);
		$rank=split("=", $rank);
		if($desde[1]=='') $desde[1]='@';
		//if($desde[1]!='@') $desde[1].=".$dominio";
		$destinos=$desde[1]." IN MX ".$rank[1].' '.$hasta[1].".";
	}
	elseif($tipo=='PTR')
	{
		list($desde, $hasta) = split("&", $destinos);
		$desde=split("=", $desde);
		$hasta=split("=", $hasta);
		//$destinos=$desde[1].".$dominio IN PTR ".$hasta[1];
		$destinos=$desde[1]." IN PTR ".$hasta[1];
	}
	elseif($tipo=='TXT')
	{
		list($desde, $forward) = split("&", $destinos);
		$desde=split("=", $desde);
		$forward=split("=", $forward);
		
		if(count($forward)>2)
		{
			for($i=2;i<count($forward);$i++)
			{
				$forward[1].='='.$forward[$i];
				if(count($forward)==$i+1) break; // Por alguna razon no corta el bucle.
			}
		}
		
		if($desde[1]=='') $desde[1]='@';
		//$desde[1]=str_replace('dkim.-', 'dkim._', $desde[1]);
		$destinos=$desde[1]." IN TXT ".$forward[1];
	}
	
	elseif($tipo=='MF')
	{
		list($hasta, $forward) = split("&", $destinos);
		$hasta=split("=", $hasta);
		$forward=split("=", $forward);
		if($hasta[1]=='*') $hasta[1]='@';
		if($hasta[1]!='@') $hasta[1].='@';
		$destinos="$dominio. IN X-SMTP ".$hasta[1]." MAILFORWARD ".$forward[1];
	}
	elseif($tipo=='WF_FRAME' || $tipo=='WF_REDIRECT' || $tipo=='WF' || $tipo=='WP')
	{
		if($tipo=='WF')
		{
			list($desde, $hasta, $disimulada) = split("&", $destinos);
		}
		else { list($desde, $hasta) = split("&", $destinos); }
		$desde=split("=", $desde);
		$hasta=split("=", $hasta);
		if(count($hasta)>2)
		{
			for($i=2;i<count($hasta);$i++)
			{
				$hasta[1].='='.$hasta[$i];
				if(count($hasta)==$i+1) break; // Por alguna razon no corta el bucle.
			}
		}
		if($desde[1]=='') $desde[1]='@';
		if($disimulada) $disimulada=split("=", $disimulada);
		$hasta[1]=urldecode($hasta[1]);
		if($tipo=='WF_FRAME' || $disimulada[1]=='true') $destinos=$desde[1]." IN X-HTTP FRAME ".$hasta[1];
		elseif($tipo=='WP') $destinos=$desde[1]." IN X-HTTP FRAME http://www.nombremania.com/parking/";
		else $destinos=$desde[1]." IN X-HTTP REDIRECT ".$hasta[1];
	}
	//$command="command=AddRecord&zone=$dominio&user=$usuario&type=$tipo&$destinos";
	if($user_dis) $command=array("s_user" => $usuario, "command" => "UpdateDNSZone", "dnszone" => $dominio.".", "addrr0" => "$destinos");
	else $command=array("command" => "UpdateDNSZone", "dnszone" => $dominio.".", "addrr0" => "$destinos");
	if($tipo=='MF' || $tipo=='WF_FRAME' || $tipo=='WF_REDIRECT' || $tipo=='WF' || $tipo=='WP') $command=array_merge($command, array('extended' => 1));
	$recibo=hexonet($command);
	//print_r($recibo);exit;
	if($recibo["code"]<>200) return false;
	return true;
}

function agregar_wf($dominio,$desde,$hasta,$usuario='')
{
	$usuario=multilingue_temp($usuario);
	$dominio=multilingue_temp($dominio);
	//$destinos="dnsfrom=$desde&forward=$hasta";
	//$command=array("command" => "StatusDNSZone", "dnszone" => $dominio.".");
	//$datos=hexonet($command);
	// Se anade $datos["xml"]->PROPERTY->SOATTL[0] porque se cree
	// que de ahi procede el 3600 que se indica siempre
	// ej. sub.domain.com. 3600 IN A 10.10.0.2
	//$destinos=$desde.". ".$datos["xml"]->PROPERTY->SOATTL[0]." IN WF ".$hasta;
	$destinos="dnsfrom=$desde&forward=$hasta";
	//$t=agregar_registro($dominio,"WF",$destinos,$usuario);
	$t=agregar_registro($dominio,"WF",$destinos);
	if($t)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//function metas_zona($usuario,$zona)
//{
//	global $debug;
//	
//	$usuario=multilingue_temp($usuario);
//	$zona=multilingue_temp($zona);
//	
//	include_once("xml.php");
//	$comando="command=ViewZone&user=$usuario&zone=$zona";
//	if($debug)echo $comando;
//	$recibo=zoneedit($comando);
//	$texto=$recibo["xml"];
//	/*print "<pre>";
//	print htmlentities($texto);
//	print "<pre>";
//	*/
//	$xml=new XML();
//	//$texto=implode("",file($file));
//	$xml->load_file("",$texto);
//	$consulta="/ze[1]/response[1]/*";
//
//	$results=$xml->evaluate($consulta);
//	$registros=array();
//	foreach($results as $registro)
//	{
//		$re=$registro;
//		//print "registro : ". $registro;
//		$registros["titulo"]= $xml->get_content($re."/attribute::title");
//		$registros["metas"] = $xml->get_content($re."/attribute::metakey");
//		$registros["metadesc"]= $xml->get_content($re."/attribute::metadesc");
//		$registros["texto"]= $xml->get_content($re."/attribute::txt");
//    }
//	return $registros;
//}

//function agregar_metas($zona,$usuario,$agregar="")
//{
//	global $debug;
//	
//	$usuario=multilingue_temp($usuario);
//	$zona=multilingue_temp($zona);
//	
//	if($agregar=="")return false;
//	//$command="command=changerecord&userzone=$usuario&zone=$zona&$agregar";
//	$command="command=changerecord&zone=$zona&type=meta&$agregar";
//	if($debug)echo $comando;
//	$recibo=zoneedit($command);
//	$texto=$recibo["xml"];
///*print "<pre>";
//print "comando : $command";
//print var_dump($texto);
//print "</pre>";
//*/
//	$texto=str_replace("<br>","",$texto);
//
//	include_once("xml.php");
//	$xml=new XML();
//	$xml->load_file("",$texto);
//	$consulta="/ze[1]/response[1]/success[1]";
//
//	$results=$xml->evaluate($consulta);
//	$re=$results[0];
//	$resultado=$xml->get_content($re."/attribute::code");
//	if($resultado<>"200")
//	{
//		return false ;
//	}
//	return true;
//}

# agregar_credito sin uso en hexonet

//function agregar_credito($user,$credit=1)
//{
//	global $debug;
//	if($user=="")
//	{
//		return false;
//	}
//	
//	$user=multilingue_temp($user);
//	
//	$command="command=AddUserCredit&user=$user&credits-amount=$credit&credits-paid=$credit";
//	$recibo=zoneedit($command);
//	$texto=$recibo["xml"];
//	/*print "<pre>";
//	print "comando : $command";
//	print var_dump($texto);
//	print "</pre>";
//	*/
//	$texto=str_replace("<br>","",$texto);
//	include_once("xml.php");
//	$xml=new XML();
//	$xml->load_file("",$texto);
//	$consulta="/ze[1]/response[1]/success[1]";
//	$results=$xml->evaluate($consulta);
//	$re=$results[0];
//	$resultado=$xml->get_content($re."/attribute::code");
//	if($resultado<>"200")
//	{
//		return false ; 
//	}
//	return true;
//}


function multilingue_temp($dominio)
{
	global $server_path;
	include_once($server_path."/registro/func_registro.inc.php");
	
	$tmp=multilingue($dominio);
	
	$resultado=$tmp["punycode"];
	return $resultado;
}



?>
