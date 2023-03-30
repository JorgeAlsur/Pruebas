<?php 

//echo phpinfo();exit;

require('registro/func_registro.inc.php');
require('hachelib.php');
require "race.php"; //funciones de conversion RACE
require('conf.inc.php');

class Grabber
{
	public $content,$err,$data,$ch;
	
	public function __construct($url='')
	{
		$this->content='';
		$this->ch=curl_init();
		curl_setopt($this->ch,CURLOPT_RETURNTRANSFER,1);
		/*curl_setopt($this->ch,CURLOPT_STDERR,$this->err);*/
		curl_setopt($this->ch,CURLOPT_POST,1);
		curl_setopt($this->ch,CURLOPT_TIMEOUT,60);
		curl_setopt($this->ch,CURLOPT_SSL_VERIFYPEER,false);
		/*curl_setopt($ch,CURLOPT_VERBOSE,true);*/
		$this->content=curl_exec($this->ch);
		if($url)curl_setopt($this->ch,CURLOPT_URL,$url);
	}
		
	public function sendData($url='')
	{
		if($url)curl_setopt($this->ch,CURLOPT_URL,$url);
		curl_setopt($this->ch,CURLOPT_POSTFIELDS,$this->data);
		$this->content=curl_exec($this->ch);
		curl_close($this->ch);
	}
}

$xml='<?xml version=\'1.0\' encoding=\'UTF-8\' standalone=\'no\' ?>
<!DOCTYPE OPS_envelope SYSTEM \'ops.dtd\'>
<OPS_envelope>
<header>
<version>0.9</version>
</header>
<body>
<data_block>
<dt_assoc>
<item key="protocol">XCP</item>
<item key="action">SET</item>
<item key="object">COOKIE</item>
<item key="registrant_ip">91.121.23.8</item>
<item key="attributes">
<dt_assoc>
<item key="reg_username">trisquel</item>
<item key="reg_password">super132008</item>
<item key="domain">newagin.info</item>
</dt_assoc>
</item>
</dt_assoc>
</data_block>
</body>
</OPS_envelope>';

//echo $xml;exit;

$url='https://rr-n1-tor.opensrs.net:55443/resellers/';

$curl=new Grabber();
$curl->data=$xml;
$curl->sendData($url);
print_r($curl->content);
exit;

$domain=trim('amalgama');

lookup_($domain);

exit;

function lookup_($dominio)
{
	global $_POST,$sugerencia,$registrando,$PHP_SELF,$exts;
	//presentar la plantilla
	if($exts<>'') 
	{
		$exts=explode(",",$exts);
		$dominio=$dominio.$exts[0];
	}

	$o=opensrs_lookup_race_($dominio,$sugerencia,$exts);
	//print_r($o);
	$datos=array();
	switch ($o['status'])
	{
		case 'available':
			$template="paso1_disponibilidad/avail.html";
			$datos['domain']="Enhorabuena <font color=\"red\">$dominio</font> est&aacute; disponible";								
		break;
		case "takenmas1":
			$template="paso1_disponibilidad/avail.html";
			//$template="paso1_disponibilidad/takenmas1.html";
			$datos['domain']="Lo sentimos <font color=\"red\">$dominio</font> ya ha sido registrado, pero est&aacute;n disponibles algunos de los siguientes";								
		break;
		default:
			$template="paso1_disponibilidad/taken.html";
			$datos['domain']=$dominio;
		break;
	}
	if(isset($registrando['domain']) and $template=='paso1_disponibilidad/taken.html')
	{
		$template='paso1_disponibilidad/avail.html';
	}

	$datos['CGI']=$PHP_SELF;

	$matches1=mostrar_lookup($o);
	$datos["matches1"]=$matches1;
	//var_crudas($datos);
	//registro en la session los disponibles de esta consulta
	if(!isset($registrando['disponibles'])) $registrando['disponibles']=$o['disponibles'];
	if(!isset($registrando["tomados"])) $registrando["tomados"]=$o["tomados"];
	if($o["convertido"]) $registrando["avisomulti"]="Atenci&oacute;n: Los dominios multiling&uuml;es no son ni ser&aacute;n operativos a corto plazo. Puede registrarlos a modo de reserva pero no podr&aacute; utilizarlos. Las opciones PRO y DNS avanzada no est&aacute;n disponibles para estos dominios";
	else $registrando["avisomulti"]='';
	print_form("$template",$datos);
	exit;
}

function opensrs_lookup_race_($dominio,$sugerencia=false,$exts_seleccionadas=array())
{
	global $_test_or_live,$ext_soportadas;
	
	//print_r($ext_soportadas);exit;

	$debug=1;
	
	if($debug)echo "Entra como $dominio<br/>";

	//  $ext_soportadas=$exts_seleccionadas;

	require_once('osrsh/openSRS.php');

	$multilingue=true;
	if($multilingue)
	{
		//require "race.php"; //funciones de conversion RACE
		$tmp=multilingue($dominio);
		if($tmp['valor']==false)
		{
			$dominio=array('domain'=>$dominio,'race'=>$dominio,'punycode'=>$dominio);
			$convertido=false;
		}
		else
		{
			$dominio=array('domain'=>$dominio,'race'=>$tmp['race'],'punycode'=>$tmp['punycode']);
			$convertido=true;
		}
		
		/*$RACE=new Net_RACE("UTF-8");
		list($n,$v)=explode(".",$dominio);  //separamos el dominio y TLD
		if(!$RACE->doRace($n))
		{
			$dominio=array('domain'=>$dominio,"error"=>$RACE->raceError);
			return $dominios;
        }
        $convertido=false;
		if(!$RACE->raceConverted)
		{
			$dominio=array('domain'=>$dominio,'race'=>$dominio);
		}
		else
		{
			$dominio=array('domain'=>$dominio,'race'=>$RACE->raceResult.".$v");
            $convertido=true;
		}*/
	}
	else
	{
		$convertido=false;
		// echo "dominio : $dominio <br>";
		list($n,$v)=explode(".",$dominio);  //separamos el dominio y TLD
		//echo "dominio2 : $dominio <br>";
		$dominio=array('domain'=>$dominio,'race'=>$dominio);
	}						
	$O=new openSRS($_test_or_live); // creo el objeto opensrs


	//print_r($dominio);exit;
	//var_crudas($dominio,'dominio');
	//var_crudas($O->OPENSRS_TLDS_REGEX);
	
	/*if(!preg_match('/^[a-zA-Z0-9][.a-zA-Z0-9\-]*[a-zA-Z0-9]'.$O->OPENSRS_TLDS_REGEX.'$/',$dominio['race']))
	{
		if($debug){echo $dominio['race'].' no concuerda con '.$O->OPENSRS_TLDS_REGEX;exit;}
		mostrar_error_nm("Dominio no v&aacute;lido, ({$dominio['race']}), intente algo como midominio.com");
		exit;
	}*/

	$result=array();

	list(,$ext)=explode(".",$dominio['dominio']);
	
	
	if($convertido and ($ext=='.tv' or $ext=='.cc' or $ext=='.biz' or $ext=='.es'))
	{
		mostrar_error_nm("Los dominios multiling&uuml;es solo pueden usar extensiones com y net, ".$dominio['domain'].$ext);
		exit;
	}
	else
	{
		if($debug){print_r($dominio);echo '<br/>';}
		// mira si el primer dominio esta libre
		$cmd=array(
                  'action' => 'lookup',
                  "object" => 'domain',
                  "registrant_ip" => "111.121.121.121",
                  'attributes' => array(
		                  				'domain' => $dominio['punycode'],
										)
					);
		$result=$O->send_cmd($cmd);
	}
	
	if($debug){echo 'Primera búsqueda: ';print_r($result);echo '<br/>';}

	//echo "<hr>script";var_crudas($result,"result");
	//$O->showlog();
	$tomados=array();$disponibles=array();$excluidos=array();$subasta=array();
	if($result['attributes']['status']=='available')
	{
		$status='available';
		$disponibles[$dominio['domain']]=array('domain'=>$dominio['domain'],'race'=>$dominio[$multi],'convertido'=>$convertido);
	}
	elseif($result['attributes']['status']=='subasta')
	{
		if($result['attributes']['price_status']=="AUCTION")
		{
			$status= 'available';
			$subasta[$dominio['domain']]=array('domain'=>$dominio['domain'],'race'=>$dominio[$multi],'convertido'=>$convertido);
		}
		else
		{
			$status= 'available';
			$disponibles[$dominio['domain']]=array('domain'=>$dominio['domain'],'race'=>$dominio[$multi],'convertido'=>$convertido);
		} 		 
	}
	else
	{
		$status='taken';
		$tomados[$dominio['domain']]=array('domain'=>$dominio['domain'],'race'=>$dominio[$multi],'convertido'=>$convertido);
	}
	// buscamos alternativos

	$exts=array();
	// agrega el punto a las extensiones soportadas
	foreach($ext_soportadas as $ex)
	{
		if($ex <> $v) $exts[]=".$ex"; //quito la que ya busque 
	}

	$opciones=array();

	foreach($exts as $ext)
	{
		if($debug)echo "$ext<br/>";
		list($n,)=explode(".",$dominio['domain']);
		if($convertido and ($ext=='.info' or $ext=='.tv' or $ext=='.cc' || $ext=='.org' || $ext=='.es'))
		{
			// quita las extensiones que no soportan multilingues
			$excluidos[]=array('domain'=>$n."$ext",'race'=>$dominio['race']);
        }
		elseif(count($exts_seleccionadas)>0 and !in_array($ext,$exts_seleccionadas))
		{
			//$excluidos[]= array('domain'=>$n."$ext",'race'=>$RACE->raceResult."$ext");
		}
		else
		{
			echo "punycode=".$dominio['punycode'].'<br/>';
			//$opciones[]=array('domain'=>$n.$ext,'race'=>substr($dominio['punycode'],0,strlen($dominio['punycode'])-4).$ext,'convertido'=>$convertido);
			$opciones[]=array('domain'=>$n.$ext,'race'=>$dominio['punycode'].$ext,'convertido'=>$convertido);
		}
	}

	//if($debug){echo "Opciones: ";print_r($opciones);echo "<br/>";}

	// extensiones a ofrecer
	$para_ver=$opciones;

	//$sugerencia=false;
	if($sugerencia==true)
	{
		// seleccion de prefijos a buscar
		$prefijos=array('','todo');
		$separador="-";
		$sufijos=array("online");

		// usando los prefijos
		$exts[] = ".$v"; //agrego la que ya busqué
		foreach($prefijos as $prefijo)
		{
			foreach($exts as $ext1)
			{
				if($debug)echo "$ext1<br/>";
				$dominio1=$prefijo.$separador.$nombre.$ext1;
				$para_ver[]=array('domain'=>$dominio1,'race'=>$dominio1);
			}
		}
		// usando los sufijos
		foreach($sufijos as $sufijo)
		{
			foreach($exts as $ext1)
			{
				if($debug)echo "$ext1<br/>";
				$dominio1=$nombre.$separador.$sufijo.$ext1;
				echo $dominio1;exit;
				$para_ver[]=array('domain'=>$dominio1,'race'=>$dominio1);
			}
		}
	}

	// hago los lookups a opensrs
	foreach($para_ver as $dominio1)
	{
		if($debug){print_r($dominio1);echo "<br/>";}
		unset($result);
		$cmd = array(
                  'action' => 'lookup',
                  "object" => 'domain',
                  "registrant_ip" => "111.121.121.121",
                  'attributes' => array(
                                   'domain' => $dominio1['race'],
                  )
                );
		$result = $O->send_cmd($cmd);
		//$O->logout();
		//$O->showlog();
		// marcar el error !!!!!!!!

		//echo "<hr>script etapa 2 ".$dominio1['race'];var_crudas($result);
			if($result['attributes']['status']=='available')
			{
				$disponibles[]=array('domain'=>$dominio1['domain'],'race'=>$dominio1[$multi],'convertido'=>$convertido);
			}
			elseif($result['attributes']['status']=='subasta' )
			{
				if($result['attributes']['price_status']=="AUCTION")
				{
				$status= 'available';
				$subasta[$dominio1['domain']]=array('domain'=>$dominio1['domain'],'race'=>$dominio1[$multi],'convertido'=>$convertido);
			}
			else
			{
				$status='available';
				$disponibles[]=array('domain'=>$dominio1['domain'],'race'=>$dominio1[$multi],'convertido'=>$convertido);					
			} 		 
		}
		else
		{
			$tomados[]=array('domain'=>$dominio1['domain'],'race'=>$dominio1[$multi]);
		}
		if($debug){print_r($result);echo "<br/><br/>";}
	}


	if($status<>'available')
	{
		if(count($disponibles) > 0 )
		{
			$status='takenmas1';
		}
		else
		{
			$status='taken';
		}
	}

	$ret=array('status'=>$status,'convertido'=>$convertido,'dominio'=>$dominio,
	'tomados'=>$tomados,'disponibles'=>$disponibles,'excluidos'=>$excluidos,'subasta'=>$subasta);
	
	if($debug){print_r($ret);echo '<br/>';exit;}

	$O->logout();
	unset($O);
	return $ret;
}

?>