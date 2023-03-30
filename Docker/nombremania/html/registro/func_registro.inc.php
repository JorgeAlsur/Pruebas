<?
function get_content($template,$datos='',$directorio='')
{
	global $debug;
	$_error='';
	if($directorio == '')
	{
    	$templates="templates";
	}
	else
	{
		$templates=$directorio;
	}
	include_once("EasyTemplate.inc.php");
	$template=new EasyTemplate("$templates/$template");
	$template->debug=false;
	$template->first = "{{";
	$template->end = "}}";
	if(is_array($datos))
	{
		while(list($k,$v)=each($datos))
		{
			//asigna los distintos valores
			$template->assign($k, $v);
		}
	}
	$contenido=$template->easy_parse();
	if(!$debug) $contenido=preg_replace('{{.*}}','',$contenido);
	if(!$contenido and $debug)
	{
		$_error.= $template->error;
	}
	return $contenido;
}

function print_form($tpl,$datos)
{
	global $debug,$registrando;
	include "class.templateh.php";
	if(!isset($registrando["templates"]) or $registrando["templates"]=='')$registrando["templates"]="templates";
	$t=new Template($registrando["templates"],"remove");
	$t->set_file("template", "$tpl");
	if(is_array($datos))
	{
		while(list($k,$v)=each($datos))
		{
			$t->set_var("$k", $v);
		}
	}

	$t->pparse("template");

	if($debug)
	{
		$tpl1=$tpl;
		debug();
	}
}

function print_form_old($template,$datos)
{
	global $debug;
	$_error='';
	$templates="templates";
	include_once("EasyTemplate.inc.php");
	$template = new EasyTemplate("$templates/$template");
	$template->debug=$debug;
	$template->first = "{{";
	$template->end = "}}";
	while(list($k,$v)=each($datos))
	{
		//asigna los distintos valores
		$template->assign($k, $v);
	}

	$contenido=$template->easy_parse();

	$contenido=preg_replace("{{.*.}}",'',$contenido);
	if(!$contenido and $debug)
	{
		$_error.= $template->error;
	}

/*$base = new EasyTemplate("$templates/base.html");
$base->debug=false;
$base->first = "{{";
$base->end = "}}";
$base->assign("CONTENT",$contenido);
*/

/*$ret=$template->easy_print();

if(!$ret and $debug){
 $_error.= $template->error;
}

*/

	print $contenido;

	if($debug) print $_error;

	echo "<a href=ver_sesion.php target=sesion>Ver sesion</a>";
}

function opensrs_check_transfer($dominio,$afiliado)
{
	global $_test_or_live; 
	require_once('osrsh/openSRS.php');
	/*$RACE = new Net_RACE("UTF-8");
	$convertido=false;
    $dominio=trim($dominio);
    list($n,$v)=explode(".",$dominio);
    if (!$RACE->doRace($n))
	{
		return "error de conversion RACE, verifique que su navegador soporta UTF8";
	}

	if(!$RACE->raceConverted)
	{
		$dominio=$dominio;
     $convertido=false;
        }
        else {
     $convertido=true;
     $dominio=$RACE->raceResult;
      }*/
	
	$debug=0;
	$tmp=multilingue($dominio);
	if($debug)$debug_string.=print_r($tmp);
	//list($dominio,$extension)=explode(".",$dominio);

	if($tmp["valor"]==false)$convertido=false;
	else
	{
		$dominio=$tmp['punycode'];
		$convertido=true;
	}

	if($convertido)
	{
		return "No pueden transferirse dominios multiling&uuml;es actualmente";
	}
	$O=new openSRS($_test_or_live);
	// mira si el primer dominio esta libre
	$cmd=array(
				'action' => "check_transfer",
				"object" => 'domain',
				'attributes' => array(
									'domain' => "$dominio",
									"affiliate_id"=>"$afiliado"
									)
                );
	$result=$O->send_cmd($cmd);
	//var_crudas($result,"result");
	// $resultado[$dom]=$result['attributes']['status'];
	if($result["is_success"])
	{
		$ok="ok";
		if($result['attributes']["transferrable"]=="1")
		{
				$ok="transferrable";
		}
		else
		{
			// error en la llamada o de protocolo
			if (isset($result['attributes']["reason"]))
			{
				$ok=$result['attributes']["reason"];
			}
			else
			{
			}
		}
	}
	else
	{
		$ok=$result["response_text"];
	}
	return $ok;
}

function opensrs_check_transfer_bulk($dominios)
{
	global $_test_or_live;
	require_once('osrsh/openSRS.php');
	$O=new openSRS($_test_or_live);
	//chequeamos transferibilidad para cada dominio
	$bad_domains=array();$good_domains=array();

	foreach($dominios as $dominio)
	{
		$cmd = array(
                  'action' => "check_transfer",
                  "object" => 'domain',
                  'attributes' => array(
                                   'domain' => "$dominio",
								   "affiliate_id"=>"$afiliado"
					                  )
                );
		$result = $O->send_cmd($cmd);
		//var_Crudas($result,"result");
		if($result["is_success"])
		{
			if($result['attributes']["transferrable"]==1)
			{
				$good_domains[]=$dominio;
			}
			else
			{
				$reason=$result['attributes']["reason"];
				$bad_domains[]=$dominio."/".$reason;
			}
		}
	}
	return array("good_domains"=>$good_domains, "bad_domains"=>$bad_domains);
}

/*function opensrs_lookup_old($dominio,$sugerencia=false)
{
	global $_test_or_live;
	require_once("openSRS.php");
	//var_Crudas($dominio);
	$O=new openSRS($_test_or_live);
	// mira si el primer dominio esta libre
	$cmd = array(
				'action' => 'lookup',
				"object" => 'domain',
				"registrant_ip" => "111.121.121.121",
				'attributes' => array(
								'domain' => "$dominio",
								)
              );
	$result = $O->send_cmd($cmd);
	//$resultado[$dom]=$result['attributes']['status'];
	//$O->showlog();
	$tomados=array();$disponibles=array();
	if($result['attributes']['status']=='available')
	{
		$status= 'available';
	}
	// buscamos alternativos
	list($nombre,$ext)=explode(".",$dominio);
	$exts=array(".com",".net",".org",".tv",".cc");   // extensiones a ofrecer
	// seleccion de prefijos a buscar
	$prefijos=array('','todo');
	$separador="-";
	$sufijos =  array("online");

	// genero nombres por todas las extensiones
	foreach($exts as $ext1)
	{
		if($ext<>$ext1)	// descarto el que ya mire
		{
			$dominio1=$prefijo.$nombre.$ext1;
			$para_ver[]=$dominio1;
		}
	}
	if($sugerencia==true)
	{
// usando los prefijos
		foreach ($prefijos as $prefijo)
		{
			foreach($exts as $ext1)
			{
				if($ext<>$ext1)	 // descarto el que ya mire
				{
					$dominio1=$prefijo.$separador.$nombre.$ext1;
					$para_ver[]=$dominio1;
				}
			}
		}
		// usando los sufijos
		foreach($sufijos as $sufijo)
		{
		foreach($exts as $ext1)
		{
			if($ext<>$ext1)	// descarto el que ya mire
			{
				$dominio1=$nombre.$separador.$sufijo.$ext1;
				$para_ver[]=$dominio1;
			}
		}
	}
}
foreach($para_ver as $dominio1)
{

                 $cmd = array(
                  'action' => 'lookup',
                  "object" => "cookie",
                  "registrant_ip" => "111.121.121.121",
                  'attributes' => array(
                                   'domain' => "$dominio1",
                  )
                );
                  $result = $O->send_cmd($cmd);

	if ($result['attributes']['status']=='available') {
		  $disponibles[]=$dominio1;
		  }
		  else {
//          echo "$dominio1 ".$result['attributes']['status'];
		  $tomados[]=$dominio1;
		  }
	}



if ($status<>'available') {
	if (count($disponibles) > 0 ){
			$status="takenmas1";
		}
		else  {
			$status="taken";
		}
}


return array('status'=>"$status",
	"tomados"=>$tomados, "disponibles"=>$disponibles);
}
*/

function opensrs_lookup_race_bulk($dominios,$sugerencia=false)
{
	global $_test_or_live;
	
	$debug=0;
	
	if(!is_array($dominios))
	{
		$dominios=explode("\n",$dominios);
    }
	require_once('osrsh/openSRS.php');
	/*require "race.php"; //funciones de conversion RACE
	$RACE = new Net_RACE("UTF-8");
	$evaluar=array();
	$convertido=false;
	foreach($dominios as $dominio)
	{
	    $dominio=trim($dominio);
    	list($n,$v)=explode(".",$dominio);
	    if (!$RACE->doRace($n))
		{
    		$evaluar[]=array('domain'=>$dominio,"error"=>$RACE->raceError);
			return $dominios;
		}
		if(!$RACE->raceConverted)
		{
			$evaluar[]=array('domain'=>$dominio,'race'=>$dominio,'convertido'=>false);
        }
        else
		{
			$convertido=true;			
			$evaluar[]=array('domain'=>$dominio,'race'=>$RACE->raceResult.".$v",'convertido'=>true);
		}
	}
	unset($RACE);*/
	foreach($dominios as $dominio)
	{
		$tmp=multilingue($dominio);
		if($tmp["valor"]==false)
		{
			$evaluar[]=array('domain'=>$dominio,'race'=>$dominio,'punycode'=>$dominio,'convertido'=>false);
			$convertido=false;
		}
		else 
		{
			$evaluar[]=array('domain'=>$dominio,'race'=>$tmp['race'],'punycode'=>$tmp['punycode'],'convertido'=>true);
			$convertido=true;
		}
		$evaluar[]=array('domain'=>$dominio,'race'=>$tmp['race'],'punycode'=>$tmp['punycode'],'convertido'=>true);
	}
	
	//if($debug){echo "Evaluar: ";print_r($evaluar);echo "<br/>";}
	
	$opciones=array();
	$tomados=array();$disponibles=array();$excluidos=array();

	$status="taken"; //asumo que no hay ninguno disponible
	$O=new openSRS($_test_or_live);
	foreach($evaluar as $xx)
	{
		list(,$ext)=explode(".",$xx['domain']);
		$cmd=array(
                  'action' => 'lookup',
                  "object" => 'domain',
                  "registrant_ip" => "217.11.124.45",
                  'attributes' => array(
                     'domain' => $xx['punycode'],
                  )
                );
		
		if($xx['convertido'] and ($ext=="tv" or $ext=="cc" or $ext=="info"))
		{
			/* mostrar_error_nm("Los dominios multiling&uuml;es solo pueden usar extensiones com y net");
			exit;*/
			$excluidos[]= $xx;
		}
		else
		{
			unset($result);
			$result = $O->send_cmd($cmd);
			//print var_dump($result);		 
			if($result['attributes']['status']=='available')
			{
				//print "<br>si -- ".$xx['domain'];
				$opciones[]=$xx;
				$status='available';
			}
			else
			{
				// print "<br>no -- ".$xx['domain'];
				$tomados[]=$xx;
			}
		}
	}
	$O->logout();
	unset($O);

	unset($result);
	$ret=array('status'=>"$status",'convertido'=>$convertido,
	"tomados"=>$tomados, 
	"disponibles"=>$opciones,
	"excluidos"=>$excluidos);

	return $ret;
}


function opensrs_lookup_race($dominio,$sugerencia=false,$exts_seleccionadas=array())
{
	global $_test_or_live,$ext_soportadas;
	
	//print_r($ext_soportadas);exit;

	$debug=0;
	
	if($debug && $_SERVER['REMOTE_ADDR']=='89.130.215.195')echo "Entra como $dominio<br/>";

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

	//var_crudas($dominio,'dominio');
	//var_crudas($O->OPENSRS_TLDS_REGEX);
	if(!preg_match('/^[a-zA-Z0-9][.a-zA-Z0-9\-]*[a-zA-Z0-9]'.$O->OPENSRS_TLDS_REGEX.'$/', $dominio['race']))
	{
		mostrar_error_nm("Dominio no v&aacute;lido, ({$dominio['race']}), intente algo como midominio.com");
		exit;
	}

	$result=array();

	list(,$ext)=explode(".",$dominio['dominio']);
	
	if($convertido and ($ext=='.tv' or $ext=='.cc' or $ext=='.biz'))
	{
		mostrar_error_nm("Los dominios multiling&uuml;es solo pueden usar extensiones com y net, ".$dominio['domain'].$ext);
		exit;
	}
	else
	{
		if($debug && $_SERVER['REMOTE_ADDR']=='89.130.215.195'){print_r($dominio);echo '<br/>';}
		// mira si el primer dominio esta libre
		$cmd = array(
                  'action' => 'lookup',
                  "object" => 'domain',
                  "registrant_ip" => "111.121.121.121",
                  'attributes' => array(
		                  				'domain' => $dominio['punycode'],
										)
					);
		$result = $O->send_cmd($cmd);
	}
	
	if($debug && $_SERVER['REMOTE_ADDR']=='89.130.215.195'){echo 'Primera busqueda: ';print_r($result);echo '<br/>';}

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
		if($debug && $_SERVER['REMOTE_ADDR']=='89.130.215.195')echo "$ext<br/>";
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
			$pos=strpos($dominio['punycode'],'.');
			$opciones[]=array('domain'=>$n.$ext,'race'=>substr($dominio['punycode'],0,$pos).$ext,'convertido'=>$convertido);
			//$opciones[]=array('domain'=>$n.$ext,'race'=>$dominio['punycode'].$ext,'convertido'=>$convertido);
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
		$exts[] = ".$v"; //agrego la que ya busqu
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
		foreach ($sufijos as $sufijo)
		{
			foreach ($exts as $ext1)
			{
				if($debug)echo "$ext1<br/>";
				$dominio1=$nombre.$separador.$sufijo.$ext1;
				$para_ver[]=array('domain'=>$dominio1,'race'=>$dominio1);
			}
		}
	}

	// hago los lookups a opensrs
	foreach($para_ver as $dominio1)
	{
		if($debug && $_SERVER['REMOTE_ADDR']=='89.130.215.195'){print_r($dominio1);echo "<br/>";}
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
	
	//if($debug && $_SERVER['REMOTE_ADDR']=='89.130.215.195'){print_r($ret);echo '<br/>';exit;}

	$O->logout();
	unset($O);
	return $ret;
}

function opensrs_profile($cookie='false')
{
	global $_test_or_live;
	require_once('osrsh/openSRS.php');
	$O=new openSRS($_test_or_live);
	if(!$cookie)
	{
		die('Falta setear cookie.');
	}

	$cmd=array('action'=>'get',
	'object'=>'domain',
	'cookie'=>$cookie,
	'attributes'=>array('type'=>'all_info')
	);

	$result=$O->send_cmd($cmd);
	//var_crudas($result);
	if(!$result['is_success'])
	{
	    mostrar_error_nm('Error en la recuperacion de datos del perfil/usuario.');
	    exit;
	}

	return $result['attributes'];
}

function setear_cookie($dominio,$usuario,$password)
{
	global $_test_or_live, $registrando;

	$tmp=multilingue($dominio);
	$dominio=$tmp['punycode'];

	require_once('osrsh/openSRS.php');
	
	$O=new openSRS($_test_or_live);
	
	$cmd = array(
	'action' => 'SET',
	'object' => 'COOKIE',
	'registrant_ip' => $_SERVER['REMOTE_ADDR'],
	'attributes' => array(
                 'reseller' => 'luison',
                 'domain' => $dominio ,
                 'reg_username' => $usuario,
                 //'reg_password' => trim($password).'2008'
                 'reg_password' => trim($password)
	)
	);
	
	/*if($_SERVER['REMOTE_ADDR']=='87.216.16.239')
	{
		print_r($cmd);
	}*/

	$result = $O->send_cmd($cmd);
	
	/*if($_SERVER['REMOTE_ADDR']=='87.216.16.239')
	{
		print_r($result);
		exit;
	}*/
	
	if(!$result["is_success"])
	{
		mostrar_error_nm("Error en la validacion del usuario/perfil : ".$result["response_text"]);
		exit;	
	}
	$COOKIE = 'REGISTRANT_LIVE_KEY';
	setcookie($COOKIE,$result['attributes']['cookie']);
	return $result['attributes']['cookie'];
}

function datos_por_cookie($cookie)
{
	global $_test_or_live;
	require_once('osrsh/openSRS.php');
	$O = new openSRS($_test_or_live);
	//recupero los datos minimos usando userinfo
	$cmd = array(
	'action' => "get",
	"object" => "userinfo",
	"registrant_ip" => $_SERVER['REMOTE_ADDR'],
	"cookie"=>$cookie);
	$result = $O->send_cmd($cmd);
	if(!$result["is_success"])
	{
		mostrar_error_nm("Error la validacion del usuario/perfil : ".$result["response_text"]);
		exit;	
	}
	return $result;
}


function countries($name,$defa='')
{
	$countries=file('countries');
	$ret="<select name=$name>\n";
	foreach($countries as $line)
	{
		$code = substr($line,0,2);
		$countrie=substr($line,3);
		$ret.="<option value=\"$code\"";
		if($defa<>'' and $defa==$code)
		{
			$ret.=" selected ";		
		}
		$ret.=">$countrie</option>\n";
	}
	$ret.="</select>";
	return $ret;
}

function periodos()
{
	global $REG_PERIODS;
	$ret='';
	while (list($k,$v) = each($REG_PERIODS))
	{
		$ret.="<option value=\"$k\">$v </option>\n";
	}
	return $ret;
}
function limpia_vars($datos)
{
	if(!is_array($datos))
	{
		die('error de limpieza de variables');
	}
	$ret=array();
	while (list($k,$v)=each($datos))
	{
	}
}

function oculta($datos)
{
	$ret='';
	while(list($k,$v)=each($datos))
	{
		$ret.="<input type=\"hidden\" name=\"$k\" value=\"$v\">\n";
	}
	return $ret;
}

function mostrar_error_nm($errores)
{
	if(is_array($errores))
	{
		$errores=join('<br>',$errores);
	}
	print_form('error.html',array('ERROR'=>$errores));
	exit;
}

function debug()
{
	global $time_start,$tpl1,$registrando,$action;
	$time_end = getmicrotime();
	$time = round($time_end - $time_start,4);
	echo "<p align=right><a href=ver_sesion.php target=sesion>Ver sesion</a> | tiempo: $time seg.";
	print "<br>tpl: ".$tpl1;
	print "template_dir: ".$registrando["templates"];
	include("debugh.php");
	$D = new LensDebug();
	$tiempo= getmicrotime()-$time_start;
	$D->msg("Ventana de debug $action<br>tiempo: $tiempo");
	$D->v($registrando,"registrando"); // display a variable and its type
}


function check_domain_syntax($domain)
{
	// no usar
	$domain = strtolower($domain);
	
	$MAX_UK_LENGTH = 61;
	$MAX_NSI_LENGTH = 67;

	if(substr($domain,-3)=='.uk')
	{
		$maxLengthForThisCase = $MAX_UK_LENGTH;
	}
	else
	{
		$maxLengthForThisCase = $MAX_NSI_LENGTH;
	}

	if(strlen($domain) > $maxLengthForThisCase)
	{
		return "Domain name exceeds maximum length for registry ($maxLengthForThisCase)";
	}
	else if(!preg_match('/'.$asd.'$/', $domain))
	{
		return "Top level domain in \"$domain\" is unavailable";
	}
	else if(!preg_match('/^[a-zA-Z0-9][.a-zA-Z0-9\-]*[a-zA-Z0-9]'.$this->OPENSRS_TLDS_REGEX.'$/', $domain))
	{
		return "Invalid domain $domain form    at (try something similar to \"yourname.com\")";
	}
	return false;
}

function calculaprecio($tipo,$periodos,$dominios,$moneda,$iva=false,$descuento=0)
{
	global $cambio_monedas;
	include("basededatos.php");
	$rs=$conn->execute("select producto,pxrango1,pxrango2,pxrango3
                           from precios" );

	$multiplicador_iva=1.21;
	$round=2;
	if($moneda=="ptas")
	{
		$round=0;
	}
	$moneda=$cambio_monedas[$moneda];

	while(!$rs->EOF)
	{
		$precios[$rs->fields["producto"]]["pvp1"]=round(($rs->fields["pxrango1"] - $descuento) *$moneda,$round);
		$precios[$rs->fields["producto"]]["pvp2"]=round(($rs->fields["pxrango2"]- $descuento) *$moneda,$round);
		$precios[$rs->fields["producto"]]["pvp3"]=round(($rs->fields["pxrango3"]- $descuento) *$moneda,$round);
		$rs->movenext();
	}

	unset($rs);


	// calculo por dominio
	$rango=rango_precios($periodos);

	$ptotal=0;
	foreach($dominios as $domi)
	{
		list($nombre,$ext)=explode(".",$domi);
		$precio_por_anio=$precios["estandar-$ext"]["pvp$rango"];
		$precio=$precio_por_anio*$periodos;
		$ptotal+=$precio;
	}
	// sumamos los extras
	$extras=0;
	if(eregi("^PRO",$tipo))
	{
		$cantidad=count($dominios);
		$extras=$cantidad * $precios[$tipo]["pvp$rango"]*$periodos;
	}
	if(!$iva)
	{
		$ret=$ptotal+$extras;
	}
	else
	{
		$ret=round(($ptotal+$extras)*$multiplicador_iva,2);
	}
	return $ret;
}

function mostrar_lookup($o)
{
	global $ext_soportadas,$registrando;

	//var_crudas($o);
	$por_dominios=array();
	if(isset($registrando['domain']) and is_array($registrando['domain']))
	{
		foreach($registrando['domain'] as $selecto)
		{
			list($d,$ext)=explode(".",$selecto);			
			$por_dominios[$d][$ext]=99;
		}
	}
	if(!is_array($o))
	{
		mostrar_error_nm("Existe un error en la petici&oacute;n de su dominio. <br>Verifique que no existan espacios en blanco o caracteres no aceptados.");
				 
	}
	if(isset($registrando["disponibles"]))
	{
		$o["disponibles"]=array_merge($o["disponibles"],$registrando["disponibles"]);
		$registrando["disponibles"]=$o["disponibles"];
	}
	foreach ($o["disponibles"] as $dispo)
	{
		list($d,$ext)=explode(".",$dispo['domain']);			
		$por_dominios[$d][$ext]=1;
	}
	if(isset($registrando["tomados"]))
	{
		$o["tomados"]=array_merge($o["tomados"],$registrando["tomados"]);
		$registrando["tomados"]=$o["tomados"];
}

foreach ($o["tomados"] as $dispo){
	 list($d,$ext)=explode(".",$dispo['domain']);			
   $por_dominios[$d][$ext]=0;
}
foreach ($o["excluidos"] as $dispo){
	 list($d,$ext)=explode(".",$dispo['domain']);			
   $por_dominios[$d][$ext]=-1;
}
if (isset($o['subasta'])){
foreach ($o['subasta'] as $dispo){
	 list($d,$ext)=explode(".",$dispo['domain']);			
   $por_dominios[$d][$ext]=-2;
}
}
//var_crudas($por_dominios);
$texto_dominio="
<td class=\"td_form_titulo\" align=\"right\"><b class=\"body_11\"><font color=\"#990000\">{domi}&nbsp;</font></b></td>
";
$disponible="<td class=\"td_form_valor\" align=\"center\" bgcolor=\"#CCCCCC\" valign=\"middle\"> 
<input type=\"checkbox\" name=\"{nombre_var}\" value=\"{domext}\" {checked} class=\"input_radios\">
</td>";
$tomado= "<td class=\"td_form_valor\" align=\"center\" bgcolor=\"#CCCCCC\" valign=\"middle\"><b><span class=\"body_10\"><a href=\"javascript:;\" onClick=\"MM_openBrWindow('/cgi-bin/whois_new/whois_flot.cgi?domain={domext}','whoismania','scrollbars=yes,width=520,height=510')\"></a>
<a href=\"javascript:;\" onClick=\"MM_openBrWindow('/cgi-bin/whois_new/whois_flot.cgi?domain={domext}','whoismania','scrollbars=yes,width=520,height=510')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('equis{i}','','/imagenes/equis_f2.gif',1)\"><img src=\"/imagenes/equis.gif\" width=\"15\" height=\"16\" border=\"0\" name=\"equis{i}\"  
alt=\"dominio registrado ver el Whois\"></a></span></b></td>";
$no_aplicable = "<td class=\"td_form_valor\" align=\"center\" bgcolor=\"#CCCCCC\" valign=\"middle\"><b><span class=\"body_10\"><a href=\"#\" onClick=\"MM_popupMsg('Tipo de extension no disponible para este nombre de dominio.')\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('no_d{i}','','/imagenes/nd_f2.gif',1)\"><img src=\"/imagenes/nd.gif\" width=\"24\" height=\"13\" border=\"0\" name=\"no_d{i}\" alt=\"no aplicable para este tipo de dominio\"></a></span></b></td>";

$a_buscar= "<td class=\"td_form_valor\" align=\"center\" bgcolor=\"#CCCCCC\" valign=\"middle\">
<a href=\"#\" onClick=\"enviar('{domi}');\" onMouseOut=\"MM_swapImgRestore()\" onMouseOver=\"MM_swapImage('interoga{i}','','/imagenes/interoga_f2.gif',1)\">
<img src=\"/imagenes/interoga.gif\" width=\"15\" height=\"16\" border=\"0\" name=\"interoga{i}\" alt=\"click para chequear disponibilidad\"></a>
</td>";

$a_subasta= "<td class=\"td_form_valor\" align=\"center\" bgcolor=\"#CCCCCC\" valign=\"middle\">
<a href=\"javascript:;\" onClick=\"alert('el dominio {domi} tiene un precio especial, si desea informacion contacte con registros@nombremania.com');\" >
<img src=\"/iconweb/alert1.gif\" width=\"15\" height=\"16\" border=\"0\" name=\"interoga{i}\" alt=\"Dominio con precio de subasta\"></a>
</td>";

$pie = "<tr><td colspan=\"7\" height=\"2\"><img src=\"/imagenes/spacer.gif\" width=\"5\" height=\"1\"></td>
</tr>";

$matches1='';

//$matches1 .= "<table> ";

$matches1="<script type=\"text/javascript\" >\nfunction enviar(buscar){\n document.formularillo.busqueda.value=buscar;\n\ndocument.formularillo.submit();\n}\n</script>";
$i=0;									 
foreach($por_dominios as $dom =>$exts){
			$vexts=array_keys($exts);
 
     $matches1.= "<tr>";
			$domi= $dom;
			$matches1.= str_replace("{domi}",$domi,$texto_dominio);
			foreach($ext_soportadas as $ext){
					     if ($por_dominios[$dom][$ext]==1 ) {
							 		//disponible
										$aux =str_replace("{domext}",$domi.".$ext",$disponible); 
										$aux = str_replace("{nombre_var}","dominio[$i]",$aux);
										if ($ext==$vexts[0]) { // es el primero de la lista de por_dominios
											 $aux = str_replace("{checked}"," checked ",$aux);
										 }		 
											 else {
											 $aux = str_replace("{checked}"," ",$aux);
											 }
											 $aux = str_replace("{i}",$i,$aux);
										$matches1.=$aux;										
								 
								 }elseif ($por_dominios[$dom][$ext]===0){
								  //no disponible
  								 $aux = str_replace("{i}",$i,$tomado);
									$matches1.=str_replace("{domext}",$domi.".$ext",$aux);
								  
								 }elseif ($por_dominios[$dom][$ext]==-1){
								   // no aplicable
  								 $aux = str_replace("{i}",$i,$no_aplicable);
									 $matches1.=$aux;
								 }elseif ($por_dominios[$dom][$ext]==99){
								    // seleccionados antes
										$aux = str_replace("{i}",$i,$disponible);
										$aux =str_replace("{domext}",$domi.".$ext",$aux); 
										$aux = str_replace("{nombre_var}","dominio[$i]",$aux);
									  $aux = str_replace("{checked}"," checked ",$aux);
										$matches1.=$aux;										
									 }	
									 elseif ($por_dominios[$dom][$ext]==-2){
									 // subasta
									 $aux = str_replace("{i}",$i,$a_subasta);
									 $matches1.=  str_replace("{domi}",$domi.".$ext",$aux);
									 }
									 else {
									 // buscar
									 $aux = str_replace("{i}",$i,$a_buscar);
									 $matches1.=  str_replace("{domi}",$domi.".$ext",$aux);
									 }									
							     
//								  print "<br> --- $ext ".$por_dominios[$dom][$ext];												

						 $i++;
						}
										
			$matches1.="</tr>$pie";			
}

return $matches1;

}


function rango_precios($period)
{
	if($period==1)
	{
		return 1;
	}
	elseif($period>=2 and $period<=4)
	{
		return 2;
	}
	elseif($period>=5)
	{
		return 3;
	}
}

function multilingue($dominio)	// devuelve el valor race y punycode de un dominio, o false si no es necesario
{

	include_once("idna_convert.class.php");
	include_once("race.php");
	
	$idna=new Net_IDNA();
	$tmp=$dominio;
	$resultado['punycode']=$idna->encode($tmp);
	$RACE=new Net_RACE("UTF-8");
	list($n,$v)=explode(".",$dominio);  //separamos el dominio y TLD
	$RACE->doRace($n);
	$resultado['race']=$RACE->raceResult.".$v";
	if($dominio==$resultado['race'] || $dominio==$resultado['punycode'])$resultado["valor"]=false;
	else $resultado["valor"]=true;
	return $resultado;
}

?>