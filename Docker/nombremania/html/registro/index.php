<?
include('sesion.php'); // todas las variables se van a llevar en el array "registrando"

//if($_SERVER['REMOTE_ADDR']=='89.130.215.195')system('ifconfig');

if(!isset($action))
{
	$action='xxxx';
}
require('func_registro.inc.php');
require('hachelib.php');
//require "race.php"; //funciones de conversion RACE
require('conf.inc.php');

$time_start=getmicrotime();
//if ($action<>"recepta" and $action<>"otro_paso") unset($registrando["recepta"]);

//if($_SERVER['REMOTE_ADDR']=='89.130.215.195')$debug=1;

if($action=='recepta')
{
	recepta();
	exit;
}
elseif($action=='lookup')
{
	//print "domi: $domain <br>decode:  ".urldecode($domain);
	if(isset($affiliate_id))$registrando['affiliate_id']=$affiliate_id;
	$registrando['reg_type']='new';
	/*if (isset($exts) ){
	$_POST["palabra1"]=$domain; //lo igualo para usar la misma func
	$_POST["palabra2"]=''; //lo igualo para usar la misma func
	$_POST["tipo"]=explode(",",$exts); //lo igualo para usar la misma func
	lookup_avanzado();
	exit;
	}*/
	lookup($domain);
	exit;
}
elseif($action=='lookup_avanzado')
{
	if(isset($affiliate_id))$registrando['affiliate_id']=$affiliate_id;
	$registrando['reg_type']='new';
	if(count($exts)<=0)
	{
		mostrar_error_nm('Debe seleccionar al menos una extension para realizar las b&uacute;squedas.');
		exit;
	}
	$domain=lookup_avanzado();
	exit;
}
elseif($action=='valorar')
{
	if(isset($buscar_bucle_x) or isset($busqueda) and $busqueda<>'')
	{
		$registrando['domain']=$dominio;
										list($n,$v)=explode('.',$busqueda);
									if ($v==''){	
										if (count($exts)<=0){
										mostrar_error_nm('Debe seleccionar al menos una extension.');
										exit;
										}else {
												$exts=implode(",",$exts);	
										}
										}
										else {
										 $exts=".$v";
										}
                    lookup($n);			
                    exit;
                    }
                    else {
                        if (count($dominio)==0)
						{
                            mostrar_error_nm(array('No ha seleccionado ningun dominio.'));
                            exit;
                            }
                       valoracion();
                       exit;
                        }
}
elseif($action=='setup_profile')
{
	if(isset($continuar_x))
	{
		setup_profile();
		exit;
	}
	else
	{
		valoracion(true);
		exit;
	}

}
elseif($action=='bulk_order')
{
	if(isset($affiliate_id)) $registrando['affiliate_id']=$affiliate_id;
	$registrando['reg_type']='new';

	$registrando['bulk_order']=1;
	$doms=opensrs_lookup_race_bulk($domain);
	$registrando['domain']=$doms['disponibles']['domain'];
	$registrando['bad_domains']=$doms['tomados']['domain'];
	foreach($doms['disponibles'] as $disp)
	{
		$registrando['domain'][]=$disp['domain'];
	}
	foreach($doms["tomados"] as $disp)
	{
		$registrando['bad_domains'][]=$disp['domain'];
	}
	valoracion();

	//bulk($affiliate_id);
	exit();
}
elseif($action=='do_setup_profile')
{
	do_setup_profile();
}
elseif($action=='verify_order')
{
	verify_order();
	exit;
}
elseif($action=='otro_paso')
{
	
	if($registrando['id_registrador']<>'100')	 //regalo
	{
		if(strtoupper($registrando['nm_registro_tipo'])=='ESTANDAR')
		{
			// verifica las dns
			verifica_tecnico();
			if($registrando['reg_type']<>'transfer')
			{
				dneses();
			}
		}
		else
		{
			// verifica las redirecciones
			registro_pro();
		}
	}
	paso_final();
	exit;
}
elseif($action=='check_transfer')
{
	if(isset($affiliate_id))$registrando['affiliate_id']=$affiliate_id;
	transferencia();
}
elseif($action=='bulk_transfer')
{
	if(isset($affiliate_id))$registrando['affiliate_id']=$affiliate_id;
	transferencia_multiple();
}
else {
     mostrar_error_nm(array("Error en la llamada falta la acci&oacute;n a realizar"));
     exit;
    }


/*
  funciones
*/
// transferencia de dominios multiple
function transferencia_multiple()
{
	global $registrando;
	$registrando['affiliate_id']=$affiliate_id;
	extract($_POST);
	$datos=array();
	$datos['CGI']=$PHP_SELF;
	//if (isset($affiliate_id)) $registrando['affiliate_id']=$affiliate_id;
	$datos['reg_type']='transfer';

	$datos['title']='Transferencia multiple de dominios';
	$datos['bulk_order']=1;

	$datos["nm_registro_tipo"]='estandar';

	$domain=explode("\n",$domain);
	$domii=array();
	foreach($domain as $domi)
	{
		$domii[]=trim($domi);
    }

	$registrando['domain']=$domii;
	$registrando['bulk_order']=1;
	$registrando['reg_type']='transfer';

	$que=opensrs_check_transfer_bulk($registrando['domain']);
	$registrando['bad_domains']=$que['bad_domains'];

	if(count($que['good_domains'])>0)
	{
		$registrando['domain']=$que['good_domains'];
	}
	else
	{
		$bad=join("<br>",$registrando['bad_domains']);
		mostrar_error_nm("Ningun Dominio transferible.\n".$bad);
		exit;
	}
	valoracion();
}

function transferencia()
{
	$debug=0;
	global $domain,$affiliate_id,$PHP_SELF,$registrando;
	$que=opensrs_check_transfer($domain,$affiliate_id);
	if($que==="transferrable")
	{
		$dominio=multilingue_temp($domain);
		$datos=array();
		$domain_string="<input type=hidden name=dominio[] value=\"$dominio\">\n";
		$datos["domain_string"]=$domain_string;
		$datos['CGI']=$PHP_SELF;
		//$datos['affiliate_id']=$affiliate_id;
		//if (isset($affiliate_id)) $registrando['affiliate_id']=$affiliate_id;
		$datos['title']="Transferencia de dominio $dominio";
		$registrando['bulk_order']=0;
		$registrando["nm_registro_tipo"]='estandar';
		$registrando['domain']=array($dominio);
		$registrando['reg_type']='transfer';
		valoracion();
	}
	else
	{
		include("errores_opensrs.php");
		$que=traduce($que);
		mostrar_error_nm("$que");
	}
	exit;
}



function registro_pro()
{
	global $registrando;
	// valido y guardo las variables en registrando

	extract($_POST);
	include_once("String_Validation.inc.php");
	if($wp_o_wf=="parking")	 //   PARKING
	{
	$registrando["nm_wf_wp"]=$parking_tipo;
	}
	else
	{
		if(!eregi("^http://",$webforward))
		{
			$errores[]="La redirecci&oacute;n del dominio no comienza con http://";
		}
		else
		{
			$registrando["nm_wf_wp"]=$webforward;
		}
	}

	if($email_hasta<>'')
	{
    	if(!is_email($email_hasta))
		{
			$errores[]="El email de destino no tiene una sintaxis correcta";
		}
		else
		{
			if ($email_desde=='') $email_desde="*";
			$registrando["nm_mf"]=$email_desde."/".$email_hasta;
		}
	}
	if(count($errores)>0)
	{
		mostrar_error_nm($errores);exit;
	}
	return true;
}

/*
PASO 3
*/
function do_setup_profile()
{
	global $_POST,$registrando,$PHP_SELF,$REG_PERIODS,$nm_productos;
	extract($_POST);
	$reg_password=trim($reg_password);
	$reg_username=trim($reg_username);
	if(!$flag_get_userinfo)
	{
		// no usa perfil
		 
		if($reg_username=='')
		{
			error("Falta el nombre de Usuario \n");
		}
		if(!preg_match("/^[a-z_0-9]+$/",$reg_username))
		{
			error("Para el nombre de usuario s&oacute;lo se aceptan caracteres y numeros sin espacios\n");
		}
		if(strlen($reg_username)>20)
		{
			error("El nombre de usuario es demasiado largo, acorte el nombre de usuario\n");
		}
		if(!eregi("[a-z0-9]",strtolower($reg_username)))
		{
			error("El nombre de usuario debe contener solo caracteres alfanumericos\n $reg_username.");
		}
		$confirm_password=trim($confirm_password);
		if(!eregi("[a-z0-9]",strtolower($reg_password)) or strlen($reg_password)>16)
		{
			error("La clave  debe contener solo caracteres alfanumericos sin espacios intermedios y hasta 16 caracteres.");
		}
		if(strlen($reg_password)<10)
		{
			error("La clave  debe contener al menos 10 caracteres.");
		}
		if($reg_password=='' or $confirm_password=='')
		{
			error("Falta la clave de usuario o la confirmaci&oacute;n");
		}
		if($reg_password <> $confirm_password)
		{
			error("La clave y la confirmaci&oacute;n no coinciden, por favor corrija");
		}
	}
	else
	{
		if($reg_username=='')
		{
			error("Falta el nombre de Usuario \n");
		}
		if(strlen($reg_username)>20 or strlen($reg_username)<1)
		{
			error("El nombre de usuario debe contener al menos 1 y hasta 20 caracteres\n");
		}
		if(!eregi("[a-z0-9]",strtolower($reg_username)))
		{
			error("El nombre de usuario debe contener solo caracteres alfanumericos sin espacios \n $reg_username.");
		}

		if($reg_domain=='')
		{
			error("Falta el dominio del perfil al que desea asociar su registro.");
		}

		if($reg_password=='')
		{
			error("Falta la clave del perfil al que desea asociar su registro.");
		}
		$cookie=setear_cookie($reg_domain,$reg_username,$reg_password);
		$all_info=opensrs_profile($cookie);
	}

	$domain_string='';
	if($flag_get_userinfo)
	{
		$registrando['reg_domain']=$reg_domain;

		$registrando['perfil']=$all_info;

		while(list($k,$v)=each($registrando['perfil']["contact_set"]['owner']))
		{
			$datos["owner_$k"]=$v;
		}
		$datos['owner_country']=countries('owner_country',$datos['owner_country']);
		while(list($k,$v)=each($registrando['perfil']["contact_set"]['billing']))
		{
			$datos["billing_$k"]=$v;
		}

		$datos['billing_country']=countries('billing_country',$datos['billing_country']);
		// tech
		while(list($k,$v)=each($registrando['perfil']["contact_set"]['tech']))
		{
			$datos["tech_$k"]=$v;
		}
		$datos['reg_profile']="Asociado a perfil $reg_domain/$reg_username";
	}
	else
	{
		$datos=array();
		$datos['billing_country']=countries('billing_country','ES');
		$datos['owner_country']=countries('owner_country','ES');
		$datos['reg_profile']="Sin asociar a ning&uacute;n perfil";
		$datos['owner_phone']="+34.";
		$datos['billing_phone']="+34.";
	}
	//    genericos para perfil o no
	//if (!$registrando['bulk_order']){ // no es BULK
	$datos['domains']=join("\n<br>",$registrando['domain']);

	/*} else {
	$datos['domains']='';
	} */

	$datos["nm_registro_tipo_text"]=$nm_productos[$registrando["nm_registro_tipo"]];
	$datos["si_transfer"]=($registrando['reg_type']=='transfer') ? 'Confirme que el email asociado al dominio que quiera trasladar (email de contacto administrativo) se encuentra operativo antes de proceder.':'';


	$datos['nm_preciototal']=mostrar_precio();
	$datos['CGI']=$PHP_SELF;
	$registrando['reg_username']=$reg_username;
	$registrando['reg_password']=$reg_password;


	$datos["period_text"]=$REG_PERIODS[$registrando['period']];

	//$registrando['bulk_order']=$bulk_order;

	$datos["reg_type_text"]=($registrando['reg_type']=='new') ? "Nuevo":"Traslado desde otro registrador";
	$datos["reg_text"]=($registrando['reg_type']=='new') ? "Registro":"Traslado";

	if(isset($registrando['contactos']))
	{
		if(is_array($registrando['contactos']['owner']))
		{
			while(list($k,$v)=each($registrando['contactos']['owner']))
			{
				$datos["owner_$k"]=$v;
			}
			$datos['owner_country']=countries('owner_country',$datos['owner_country']);
		}
		if(is_array($registrando['contactos']['billing']))
		{
			while(list($k,$v)=each($registrando['contactos']['billing']))
			{
				$datos["billing_$k"]=$v;
			}
			$datos['billing_country']=countries('billing_country',$datos['billing_country']);
		}
	}

	print_form("paso3_datos1/order.html",$datos);
	exit;
}


function bulk($afiliado)
{
	global $PHP_SELF,$registrando;
	
	$registrando["afiliado"]=$afiliado;
	$datos=array();
	$domain_string="<input type=hidden name=nm_registro_tipo value=\"$tipo_registro\">";
	$datos["domain_string"]=$domain_string;
	$datos['CGI']=$PHP_SELF;
	$datos['reg_type']='new';
	$datos['title']="Registro M&uacute;ltiple";
	$datos['bulk_order']=1;
	$datos["nm_registro_tipo"]=$tipo_registro;
	print_form("paso2_perfil/setup_profile.html",$datos);
	exit;
}

/*
PASO 2
*/
function setup_profile()
{
	global $PHP_SELF,$registrando,$_POST,$nm_productos,$REG_PERIODS;

	//limpia los valores de la sesion
	unset($registrando['perfil']);
	unset($registrando['reg_username']);
	unset($registrando['reg_password']);
	unset($registrando['reg_domain']);

	// graba los de la valoracions
	$registrando['period']=$_POST['period'];
	$registrando['moneda']=$_POST['moneda'];
	$registrando["precio_java"]=$_POST["htotal"];
	$registrando['nm_preciototal']=calculaprecio($registrando["nm_registro_tipo"],$registrando['period'],$registrando['domain'],'euros',false,$registrando['descuento']);
	$datos['nm_preciototal']=mostrar_precio();
	$registrando["nm_registro_tipo"]=$_POST["nm_registro_tipo"];
	//

	$datos=array();
	$datos['CGI']=$PHP_SELF;
	$datos["reg_text"]=($registrando['reg_type']=='transfer') ? "Traslado":"Registro";
	$datos["nm_registro_tipo_text"]=$nm_productos[$registrando["nm_registro_tipo"]];
	$datos['domains']=implode("<br>",$registrando['domain']);
	$datos['nm_preciototal']=mostrar_precio();
	$datos["aviso"]='';
	$datos["period_text"]=$REG_PERIODS[$registrando['period']];
	print_form("paso2_perfil/setup_profile.html",$datos);
	exit;
}

/*
  LOOKUP PASO 1
*/
function lookup($dominio)
{
	global $_POST,$sugerencia,$registrando,$PHP_SELF,$exts;
	//presentar la plantilla
	if($exts<>'') 
	{
		$exts=explode(",",$exts);
		$dominio=$dominio.$exts[0];
	}

	//print_r($exts);exit;

	$o=opensrs_lookup_race($dominio,$sugerencia,$exts);
	//print_r($o);
	
	//if($_SERVER['REMOTE_ADDR']=='89.130.215.195')print_r($o);
	
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
			$template='paso1_disponibilidad/taken.html';
			$datos['domain']=$dominio;
		break;
	}
	if(isset($registrando['domain']) and $template=='paso1_disponibilidad/taken.html')
	{
		$template='paso1_disponibilidad/avail.html';
	}

	$datos['CGI']=$PHP_SELF;

	$matches1=mostrar_lookup($o);
	$datos['matches1']=$matches1;
	//var_crudas($datos);
	//registro en la session los disponibles de esta consulta
	if(!isset($registrando['disponibles'])) $registrando['disponibles']=$o['disponibles'];
	if(!isset($registrando['tomados'])) $registrando['tomados']=$o['tomados'];
	if($o['convertido']) $registrando['avisomulti']="Atenci&oacute;n: Los dominios multiling&uuml;es no son ni ser&aacute;n operativos a corto plazo. Puede registrarlos a modo de reserva pero no podr&aacute; utilizarlos. Las opciones PRO y DNS avanzada no est&aacute;n disponibles para estos dominios";
	else $registrando['avisomulti']='';
	print_form($template,$datos);
	exit;
}

function lookup_avanzado()
{
	global $registrando,$ext_soportadas;
	if(is_array($_POST))extract($_POST);
	unset($_POST);

	if(!isset($palabra1) or !isset($palabra2))
	{
		mostrar_error_nm('Las dos palabras deben ser introducidas para poder componer la consulta.');
		exit;
	}

	if(!isset($palabra1) or trim($palabra1)=='')
	{
		$palabra1=$palabra2;
		unset($palabra2);
	}
	$p1=trim($palabra1);
	$p2=trim($palabra2);
	/*$p1=utf8_encode($p1);
	$p2=utf8_encode($p2);
	*/
	if(substr($p1,-1)=='s')$p3=substr($p1,0,-1);
	if(substr($p2,-1)=='s')$p4=substr($p2,0,-1);
	$opciones=array();
	$separadores=array("-");
	if($p2=='')
	{
		$opciones[]=$p1;
	} 
	else
	{
		$opciones[]=$p1.$p2;
		$opciones[]=$p2.$p1;
	}
	if(isset($p3))$opciones[]=$p2.$p3;
	if(isset($p4))$opciones[]=$p1.$p4;

	foreach($separadores as $sep)
	{
		if($p2<>'')$opciones[]=$p1.$sep.$p2;
		if($p2<>'' and isset($p3)) $opciones[]=$p2.$sep.$p3;
		if(isset($p4) and $p4<>'')$opciones[]=$p1.$sep.$p4;
	}

	$x=array();

	if($sugerencia==1)
	{
		$prefijos=array("todo","e-","i-");
		$sufijos=array('info',"-red");
		foreach($prefijos as $pre)
		{
    		$opciones[]=$pre.$p1;
			$opciones[]=$pre.$p2;
		}
		foreach($sufijos as $suf)
		{
			$opciones[]=$p1.$suf;
			$opciones[]=$p2.$suf;
		}
	}

	$opciones2=array();
	$tipo=$exts;
	unset($exts);
	foreach($tipo as $exts)
	{

		foreach($opciones as $opc)
		{
			$opc.=$exts;
			$opciones2[]=$opc;
		}
	}

	foreach($opciones2 as $opc)
	{
		$x[]=$opc;
	}

	$o=opensrs_lookup_race_bulk($x);
	/*$mostrar=array();

$i=1;
$columnas=3;$aux_check=0;
$matches= "<table align=Center width=100%>";
foreach ($o['disponibles'] as $dispo){

if ($i==1) $matches.= "<tr>";
if ($i<=$columnas){
    $matches.="<td class=\"matches\">";
    }
if ($aux_check==0){
   $matches.="<input type=checkbox class=input_radios name=dominio[$aux_check] value=\"".$dispo['domain']."\" checked> ".$dispo['domain'];
}else {
   $matches.="<input type=checkbox class=input_radios name=dominio[$aux_check] value=\"".$dispo['domain']."\" > ".$dispo['domain'];
     }
if ($i<$columnas){
 $matches.="</td>";
 $i++;
}
elseif ($i==$columnas){
    $matches.="</td></tr>";
    $i=1;
    }
$aux_check++;
}
$matches.="</table>";
*/

	$matches1=mostrar_lookup($o); 
	unset($o);
	$datos=array();
	//$datos['matches']=$matches;
	$datos['matches1']=$matches1;
	$datos['domain']=$dominio;
	$registrando['domain']=$o['disponibles'][0];
	//$registrando['affiliate_id']=$affiliate_id;

	$template="paso1_disponibilidad/avail.html";
	//print $matches;
	$datos["aviso"]='';
	$datos['CGI']=$_SERVER['PHP_SELF'];
	print_form($template,$datos);
	unset($datos);
	exit;
}

function valoracion($notocar=false)
{
	global $registrando,$REG_PERIODS_tv,$REG_PERIODS,$debug,
       $monedas,$simbolos_monedas,$cambio_monedas;
	if(isset($_POST['nm_registro_tipo']))
	{
		$registrando['nm_registro_tipo']=$_POST['nm_registro_tipo'];
	}
	$descuento=0;
	if($registrando['reg_type']=='new')
	{
		if(!isset($registrando['bulk_order']))$registrando['bulk_order']=0;
		if($registrando['bulk_order']==0)
		{
			if(!$notocar)
			{
				unset($registrando['domain']);
				foreach($_POST['dominio'] as $x)
				{
					$registrando['domain'][]=$x;
				}
			}
		}
		$mensaje='';
		if($registrando['affiliate_id']=='22'  or $registrando['affiliate_id']=='24')
		{
			// 24 para la oferta del euro para elistas
			$descuento=1; // se aplica la oferta SIMO
			$mensaje="Estamos aplicando el Descuento de 1 &euro; para la compra de dominios.<br>";
		}

	}
	$registrando['descuento']=$descuento;
	if(isset($_POST['period']))
	{
		$registrando['period']=$_POST['period'];
	}
	if(!isset($period_default))$period_default=2;

	$nm_registro_tipo_default='estandar';

	if(isset($_POST["nm_registro_tipo"])) $registrando["nm_registro_tipo"];	
	$period_default=(isset($registrando['period'])) ? $registrando['period'] : $period_default;
	$registrando["nm_registro_tipo"] =(isset($registrando["nm_registro_tipo"])) ? $registrando["nm_registro_tipo"] : $nm_registro_tipo_default;
	$periodos=array();
	$alerta_tv=false;
	$alerta_info=false;
	if($registrando['reg_type']=='transfer')
	{
		$periodos= array("1"=>"1 a&ntilde;o");
		$period_default=1;
	}
	else
	{
		if(extensiones($registrando['domain'],'tv'))
		{
			$periodos=$REG_PERIODS_tv;
			unset($periodos[2]);  //quitamos el 2 por se tv
			$period_default=3; 
			$alerta_tv=true;
		}
		else
		{
			$periodos=$REG_PERIODS;
		}
		/*if(extensiones($registrando['domain'],'info'))
		{
			unset($periodos["1"]);
			$alerta_info=true;
		}*/
		if(extensiones($registrando['domain'],'biz'))
		{
			unset($periodos["1"]);
			$alerta_info=true;
		}

	}
	$periodos=form_select($periodos,'period',$period_default,true);
	$datos=array();
	$datos['CGI']=$_SERVER['PHP_SELF'];
	if(isset($mensaje) && $mensaje<>'')$datos['mensaje']=$mensaje;
	$datos['aviso']=($alerta_tv or $alerta_info)  ? "<b>Nota:</b> " : '';

	$datos['aviso'].= (!$alerta_tv) ? '' : "Al contener un dominio TV, el periodo de a&ntilde;os debe ser 1,3,5 &oacute; 10.&nbsp; ";
	$datos["aviso"].= (!$alerta_info) ? '' : "Los dominios .biz s&oacute;lo se pueden registrarse por 2 a&ntilde;os  o m&aacute;s.";
	if(isset($registrando["avisomulti"]) and $registrando["avisomulti"]!='') $datos["aviso"].="<br>{$registrando["avisomulti"]}";
	//template con bloques dom y extras
	$tpl='paso1_disponibilidad/valoracion.html';
	include('class.templateh.php');
	$t=new Template($registrando["templates"],'remove');
	$t->set_file('template',$tpl);
	$i=0;
	include('basededatos.php');
	$rs=$conn->execute("select producto,pxrango1,pxrango2,pxrango3
                           from precios where tipo='dominio'");
	$precios=array();

	if(!isset($_POST['moneda']))
	{
		$moneda_default='euros';
	}
	else
	{
		$moneda_default=$_POST['moneda'];
	}

	$simbolo_moneda=$simbolos_monedas[$moneda_default];
	$round=2;
	if($moneda_default=='ptas')
	{
		$simbolo_moneda='';
		$round=0;
	}
	$moneda=$cambio_monedas[$moneda_default];


	$t->set_var("list_moneda",form_select($monedas,'moneda',$moneda_default,true));

	while(!$rs->EOF)
	{
		$precios[$rs->fields['producto']]["pvp1"]=round(($rs->fields["pxrango1"] - $descuento) *$moneda,$round);
		$precios[$rs->fields['producto']]["pvp2"]=round(($rs->fields["pxrango2"] - $descuento) *$moneda,$round);
		$precios[$rs->fields['producto']]["pvp3"]=round(($rs->fields["pxrango3"]-$descuento) *$moneda,$round);
		$rs->movenext();
	}

	unset($rs);

	$rango=rango_precios($period_default);
	$cantidad=count($registrando['domain']);
	$estandarsum=0;
	$preciototalbasico1=0;
	$preciototalbasico2=0;
	$preciototalbasico3=0;
	
	if(is_array($registrando['domain']))foreach($registrando['domain'] as $domi)
	{
		list($nombre,$ext)=explode(".",$domi);

		$i++;
		$t->set_var("dom",$domi);

		$t->set_var("pvp_1","pvp_1_$i");
		$t->set_var("hpvp_1","hpvp_1_$i");
		$t->set_var("hvpvp_1",$precios["estandar-$ext"]["pvp1"]);
		$t->set_var("div_pvp_1",$simbolo_moneda." ".$precios["estandar-$ext"]["pvp1"]);

		$t->set_var("pvp_2","pvp_2_$i");
		$t->set_var("hpvp_2","hpvp_2_$i");
		$t->set_var("hvpvp_2",$precios["estandar-$ext"]["pvp2"]);
		$t->set_var("div_pvp_2",$simbolo_moneda." ".$precios["estandar-$ext"]["pvp2"]);
		$t->set_var("pvp_3","pvp_3_$i");
		$t->set_var("hpvp_3","hpvp_3_$i");
		$t->set_var("hvpvp_3",$precios["estandar-$ext"]["pvp3"]);
		$t->set_var("div_pvp_3",$simbolo_moneda." ".$precios["estandar-$ext"]["pvp3"]);

		//totales   para cada dominio de basicos (suma horizontal)

		$precio=$precios["estandar-$ext"]["pvp$rango"];
		$period=$period_default;
		$precio_sel=round($period*$precio,2);

		$t->set_var("div_domsum","domsum_$i");
		$t->set_var("domsum",$simbolo_moneda." ".$precio_sel);
		$estandarsum+=$precio_sel;


		//totales de basicos (suma vertical)
    
		$preciototalbasico1+=$precios["estandar-$ext"]['pvp1']*$period;
		$preciototalbasico2+=$precios["estandar-$ext"]['pvp2']*$period;
		$preciototalbasico3+=$precios["estandar-$ext"]['pvp3']*$period;

		$t->parse("DOM","DOM",true);
	}
	$t->set_var('estandar_1',$simbolo_moneda." ".round($preciototalbasico1,$round));
	$t->set_var('estandar_2',$simbolo_moneda." ".round($preciototalbasico2,$round));
	$t->set_var('estandar_3',$simbolo_moneda." ".round($preciototalbasico3,$round));
	$t->set_var('estandarsum',$simbolo_moneda." ".round($estandarsum,$round));

// precios extras
$rs=$conn->execute("select producto,pxrango1,pxrango2,pxrango3
                           from precios where tipo='extra'");
$extrass=array();
while (!$rs->EOF)
{
	$extras[$rs->fields['producto']]["pvp1"]=$rs->fields['pxrango1']*$moneda*$cantidad;
	$extras[$rs->fields['producto']]["pvp2"]=$rs->fields['pxrango2']*$moneda*$cantidad;
	$extras[$rs->fields['producto']]["pvp3"]=$rs->fields['pxrango3']*$moneda*$cantidad;
	$rs->movenext();
}
//
unset($rs);
if ($registrando['reg_type']=='new'){
for ($j=1;$j<=3;$j++){
$i=1;
foreach($extras["pro$j"] as $extra){
 $svar="pro$j"."_$i";
 $t->set_var("$svar",$simbolo_moneda." ".round($extra,$round));
 $i++;
    }
}
$pro1sum=$estandarsum+($extras["pro1"]["pvp$rango"]*$period);
$t->set_var("pro1sum",$simbolo_moneda." ".round($pro1sum,$round));

$pro2sum=$estandarsum+($extras["pro2"]["pvp$rango"]*$period);
$t->set_var("pro2sum",$simbolo_moneda." ".round($pro2sum,$round));

$pro3sum=$estandarsum+($extras["pro3"]["pvp$rango"]*$period);
$t->set_var("pro3sum",$simbolo_moneda." ".round($pro3sum,$round));
// radio buttons para los tipos de registros
if (!isset($_POST["nm_registro_tipo"])){
   $_POST["nm_registro_tipo"]=$registrando["nm_registro_tipo"]; // toma valor por defecto declarado arriba
    }

if ($_POST["nm_registro_tipo"]=='estandar'){
    $total=$estandarsum;
    $t->set_var("check_estandar","checked");
  }
    else {
    $svar=$_POST["nm_registro_tipo"]."sum";
    $svar_c="check_".$_POST["nm_registro_tipo"];
    $t->set_var($svar_c,"checked");
    $total=$$svar;
}
// fin radio buttons

$t->parse("PRO2","PRO2",true);
$t->parse("PRO3","PRO3",true);
}  // procesa los bloques pro2 y pro3
else  {
			$total=$estandarsum;
			$t->set_var("check_estandar","checked");
			$t->parse("OFERTAR_TRANSFER","OFERTAR_TRANSFER",true);
			$_POST["nm_registro_tipo"]='estandar';
}
if ($_POST["nm_registro_tipo"]=='estandar'){
    $total=$estandarsum;
    $t->set_var("check_estandar","checked");
  }
    else {
    $svar=$_POST["nm_registro_tipo"]."sum";
    $svar_c="check_".$_POST["nm_registro_tipo"];
    $t->set_var($svar_c,"checked");
    $total=$$svar;
}

$t->set_var("total",$simbolo_moneda." ".round($total,$round));
$t->set_var("hvtotal",$total);
$datos["period_list"]=$periodos;
$datos["reg_text"]=($registrando['reg_type']=='new') ? "Registro":"Traslado";

if (isset($registrando['bad_domains'])){
    $datos['bad_domains']="Dominios Erroneos :<b>".join("<br>",$registrando['bad_domains'])."</b>";

    }

if (is_array($datos)){
while (list($k,$v)=each($datos)){
      $t->set_var("$k", $v);
}
}


$t->pparse('template');
  if ($debug) {
  debug();
}

}


function extensiones($doms,$extension_buscada='tv')
{
	if(is_array($doms))foreach($doms as $dom)
	{
    	list($n,$ext)=explode(".",$dom);
	    if($ext==$extension_buscada)return TRUE;
    }
	return false;
}

/*
PASO 4
 */
function verify_order()
{
	global $registrando,$contact_fields,$REG_PERIODS,$nm_productos,$_test_or_live,$debug;

	$datos=array();

	reset($_POST);
	if(isset($registrando['contactos']['owner'])) unset($registrando['contactos']['owner']); // limpio la variable

        while (list($k,$valor)=each($_POST)){
                if (ereg("^owner_",$k)){
                      //  $datos["$k"] ="$valor";
                        $var=substr($k,strpos($k,"_")+1); //nombre de la variable
                        $registrando['contactos']['owner']["$var"]="$valor";
                }
        }
reset($_POST);
unset($registrando['contactos']['billing']); // limpio la variable
if ($_POST["flag_use_contact_info"]){
        while (list($k,$valor)=each($_POST)){
                if (ereg("^owner_",$k)){
                        $var='billing'.substr($k,strpos($k,"_"));
                       // $datos["$var"] ="$valor";
                        $var=substr($k,strpos($k,"_")+1); //nombre de la variable
                        $registrando['contactos']['billing']["$var"]="$valor";
                }
        }
		//print_r($registrando['contactos']);exit;
}
else {
        while (list($k,$valor)=each($_POST)){
                if (ereg("^billing_",$k)){
                     //   $datos["$k"] ="$valor";
                        $var=substr($k,strpos($k,"_")+1); //nombre de la variable
                        $registrando['contactos']['billing']["$var"]="$valor";
                }
        }
}

// si usamos perfil recuperamos variables y mostramos
if (isset($registrando['perfil'])){
    while (list($k,$v)=each($registrando['perfil']["contact_set"]['tech'])){
          $datos["tech_".$k]=$v;
        }
        $datos['reg_profile']="Asociado a perfil ".$registrando['reg_domain']."/".$registrando['reg_username'];
    }

require_once('osrsh/openSRS.php');

$O=new openSRS($_test_or_live);

//$datos['dominio']=$registrando['domain'];

$validado=$O->validate_nm($registrando);

/*if($_SERVER['REMOTE_ADDR']=='87.216.16.239')
{
	$validado['is_success']=1;
}*/

if($validado['is_success']==1)
{

$datos['CGI']=$PHP_SELF;
if(isset($registrando['bad_domains'])){ $datos['bad_domains']="<br>Dominios Erroneos :<b><br>".join("<br>",$registrando['bad_domains'])."</b>";
                                      }
$datos["nm_registro_tipo_text"]=$nm_productos[$registrando["nm_registro_tipo"]];

if (isset($registrando['perfil'])){
$datos['reg_profile']="Asociado a perfil {$registrando['reg_domain']}/{$registrando['reg_username']}";
}
else {
     $datos['reg_profile']="Sin asociar a ning&uacute;n perfil";
    }
$datos["reg_type_text"]=($registrando['reg_type']=='new') ? "Nuevo":"Traslado desde otro registrador";
$datos['nm_preciototal']=mostrar_precio();
$datos["reg_text"]= ($registrando['reg_type']=='new') ? "Registro":"Traslado";
$datos["period_text"]= $REG_PERIODS[$registrando['period']];
$datos['domains']=implode("<br>",$registrando['domain']);

// ingreso del otro paso

if (strtoupper($registrando["nm_registro_tipo"])=="ESTANDAR"){

$datos["tech_country"]=countries("tech_country",$_POST['owner_country']);
   //recupera cada dns usando sortorder para armar las variables
  $dns_datos=array();

   if (isset($registrando['perfil'])){
      if (is_array($registrando['perfil']["nameserver_list"])){
           foreach($registrando['perfil']["nameserver_list"] as $ddnnss){
              $dns_datos["fqdn".$ddnnss["sortorder"]]=$ddnnss["name"];
            }
       }
    }
if ($registrando['reg_type']<>'transfer'){
   $datos["otro_paso"]=get_content("paso4_datos2/dns.inc.html",$dns_datos);
}

   $tpl="order_adic_est.html";
   $datos["dominios"]=implode("<br>",$registrando['domain']);

$datos["aviso"]='';
print_form("paso4_datos2/$tpl",$datos);


}
else {
   $datos['otro_paso']=pro($registrando['domain']);
   $tpl='paso4_datos2/order_adic_pro.html';
   
include 'class.templateh.php';
$t=new Template($registrando['templates'],'remove');
$t->set_file('template',$tpl);

  
   // mail forward aparece solo para los pro2 y pro3
   if (strtoupper($registrando["nm_registro_tipo"])=="PRO2" or strtoupper($registrando["nm_registro_tipo"])=="PRO3" ) {
//      $datos["MAILFORWARD"]=get_content("paso4_datos2/mf_inc.html",'');
      $t->parse("mailforward","mailforward",true);
       }
if (isset($registrando["nm_wf_wp"])){
        if (eregi("^http://",$registrando["nm_wf_wp"])){
             $v_webforward=$registrando["nm_wf_wp"];
             $webforward_checked=" checked ";
            $list_parking=form_select(array("parking"=>"Dominio Reservado",
                      "venta"=> "Dominio en Venta",
                      "regalo"=>"P&aacute;gina de regalo"),'', '',true);

            }
            else {
               $list_parking=form_select(array("parking"=>"Dominio Reservado",
                      "venta"=> "Dominio en Venta",
                      "regalo"=>"P&aacute;gina de regalo"), '',$registrando["nm_wf_wp"],true);
               $parking_checked=" checked ";
                $v_webforward="http://";
                }
            
    }
    else {
        $list_parking= form_select(array("parking"=>"Dominio Reservado",
                   "venta"=> "Dominio en Venta",
                      "regalo"=>"P&aacute;gina de regalo"),'', $registrando["nm_wf_wp"],true);
             $v_webforward="http://";
               $parking_checked=" checked ";
        }

while(list($k,$v)=each($datos)){
    $t->set_var("$k",$v);
    }
if ($registrando["id_registrador"]=="100"){
    include "lista_regalos.php";
   $t->set_var("modelos", form_select($regalos,"modelos_regalos"));
}
$t->set_var('domains',implode("<br>",$registrando['domain']));
$t->set_var("parking_checked", $parking_checked);
$t->set_var("webforward_checked",$webforward_checked);
$t->set_var("list_parking",$list_parking);
$t->set_var("v_webforward",$v_webforward);
$t->pparse('template');
if ($debug) debug();
}

}
else { // hay errores anteriores
        $errores=array($validado["error_msg"]);
        mostrar_error_nm($errores);
        exit;
}

}

function paso_final()
{
	global $REG_PERIODS,$registrando,$nm_productos;
	
	if($debug)
	{
		print_r($registrando);
		exit;
	}
	
	unset($registrando['perfil']); //borro el perfil

	//extract($_POST);
	$datos['CGI']="$PHP_SELF";
	$datos["action"]="recepta";
	$ocultas=array();
	$datos["reg_type_text"]=($registrando['reg_type']=='new') ? "Nuevo":"Traslado";

	$datos['bad_domains']=(isset($registrando['bad_domains'])) ? implode("<br>",$registrando['bad_domains']):'';

	$datos['reg_profile']=(isset($registrando['reg_domain'])) ? "Basado en ".$registrando['reg_domain']."/".$registrando['reg_username']:"Sin asociar a ning&uacute;n perfil";

	$datos["reg_text"]="Registro";
	$datos['domains']=implode("\n<br>",$registrando['domain']);
	$datos["period_text"]=$REG_PERIODS[$registrando['period']];
	$datos["nm_registro_tipo_text"]=$nm_productos[$registrando["nm_registro_tipo"]];


	while(list($k,$v)=each($registrando['contactos']['billing']))
	{
		$datos["billing_$k"]=$v;
	}

	if($registrando['nm_registro_tipo']=='estandar')
	{
		if(is_array($registrando['contactos']['tech']))while(list($k,$v)=each($registrando['contactos']['tech']))
		{
			$datos["tech_$k"]=$v;
		}
	}

	foreach($registrando['contactos']['owner'] as $k=>$v)
	{
		$datos["owner_$k"]=$v;
	}

//$datos['nm_preciototal']=$GLOBALS["simbolos_monedas"][$registrando['moneda']].$registrando['nm_preciototal'];
//arma datos en base a http_post_vars
$datos['nm_preciototal']=mostrar_precio();
$wp_wf=array();$mensa=array();
$tipo=strtoupper($registrando["nm_registro_tipo"]);
if (eregi("^PRO",$tipo)) {
    $mensa[]="El tr&aacute;fico web destinado a estos dominios ser&aacute; redirigido a ".$registrando["nm_wf_wp"] ;
    list($desde,$hasta)=explode("/",$registrando["nm_mf"]);
    $mensa[]="El correo ser&aacute; desviado de $desde a $hasta";
$mensa=join("<br>",$mensa);
$datos1=array();
$datos1["REDIRECCIONES"].=$mensa;
$datos["TECH_INFO"]=get_content("paso5_verificar/info_pro.inc.html",$datos1);
}
else {
    $datos["TECH_INFO"]=get_content("paso5_verificar/info_tech.inc.html",$datos);

if ($registrando['reg_type']<>'transfer'){
$dns=array();
$i=0;
    foreach($registrando["nameserver_list"] as $fqdn){
           $i++;
           $dns["dns$i"]=$fqdn;
        }

    $datos["NAMESERVER_INFO"]=get_content("paso5_verificar/info_dns.inc.html",$dns);
    unset($dns);
}
}
if ($registrando['contactos']['billing']["country"]=='ES'){
//$datos["NIF"]="Al ser un cliente residente en Espa&ntilde;a <br>necesitamos su NIF para realizar la factura:<INPUT type=text name=nm_nif value=\"\" size=15>";

$datos["nm_nif_text"]="<p><b><font color=\"#CC0000\">ATENCION!!</font></b><font color=\"#CC0000\">
                    - Al ser el titular residente en Espa&ntilde;a,<b> tendr&aacute;
                    que introducir el NIF </b>correspondiente.</font></p> ";
$datos["nm_nif"]=" <table border=0 cellpadding=2 cellspacing=1 width=\"100%\">
                      <tr valign=\"middle\" bgcolor=\"#FFFF99\">
                        <td align=\"right\" class=\"body_10\" bgcolor=\"#FFFFCC\"><font color=\"#CC0000\">ATENCION!!
                          - Al ser el titular residente en Espa&ntilde;a,<b> <br>
                          necesitamos su n&uacute;mero de NIF </b>para la factura.</font></td>
                        <td bgcolor=\"#CC0000\" class=\"body_10\">
                          <input type=\"text\" name=\"nm_nif\" size=\"12\">
                        </td>
                      </tr>
                      </table>"  ;

$datos["nm_precioconiva"]="&euro; ".$registrando['nm_preciototal']*1.21;
$datos["nm_precioconiva_ptas"]=round($registrando['nm_preciototal']*1.21*166.386, 0) ." Ptas";
$datos["nm_iva"]="&euro; ".$registrando['nm_preciototal']*0.21;
}  else {
   $datos["nm_iva"]="(no aplicable)";
   $datos["nm_precioconiva"]="&euro; ".$registrando['nm_preciototal'];
   $datos["nm_precioconiva_ptas"]=round($registrando['nm_preciototal']*166.386, 0 )." Ptas";	 
}

print_form("paso5_verificar/verify.html",$datos);
exit;
}



function recepta()
{
	include('basededatos.php');
	
	global $registrando;
	
	$debug=0;
	
	if($registrando['contactos']['billing']["country"]=='ES')
	{
		if($_POST["nm_nif"]=='')
		{
			mostrar_error_nm("Falta dato de NIF para residente Espal");
		}
	}
	$datos=array();
	$datos['date']=time();
	$datos['affiliate_id']=(int) (isset($registrando['affiliate_id']))? $registrando['affiliate_id']: 0 ;

	$datos['reg_username']=$registrando['reg_username'];
	$datos['reg_password']=$registrando['reg_password'];
	$datos['reg_domain']=(isset($registrando['reg_domain']))? $registrando['reg_domain']:NULL ;
	$datos['reg_type']=$registrando['reg_type'];
	$datos['period']=(int) $registrando['period'];

	// dominios
	$datos['domain']=implode("**",$registrando['domain']);
	//dns
	$i=1;
	if(isset($registrando["nameserver_list"]) and is_array($registrando["nameserver_list"]))
	{
		foreach($registrando["nameserver_list"] as $dns)
		{
			$datos["fqdn$i"]=$dns;
			$i++;
		}
	}
	// -- fin dns
	/*
		agregar precio en euros con IVA o sin IVA segun corresponda
	*/
	//contactos
	foreach($registrando['contactos']['owner'] as $campo=>$valor)
	{
		$datos["owner_$campo"]=$valor;
	}
	foreach($registrando['contactos']['billing'] as $campo=>$valor)
	{
		$datos["billing_$campo"]=$valor;
    }
	// solo si no es pro ?
	if(strtolower($registrando['nm_registro_tipo'])=='estandar')
	{
		if(is_array($registrando['contactos']['tech']))foreach($registrando['contactos']['tech'] as $campo=>$valor)
		{
			$datos["tech_$campo"]=$valor;
		}
	}
	
	if($debug)
	{
		print_r($registrando);
		exit;
	}
	
	//datos nombremania
	$datos["nm_registro_tipo"]=$registrando["nm_registro_tipo"];
	$datos['nm_preciototal']=mostrar_precio();
	if($registrando["id_registrador"]=="100")	    //REGALO en nombremania
	{
		$aux_regalo=array();
		foreach($registrando  as $k=>$v)
		{
			if(ereg("^regalo_",$k))
			{
				$k1=str_replace("regalo_",'',$k);
				$aux_regalo[$k1]=$v;		
			}							
		}
	}
	if(eregi("^PRO",$registrando["nm_registro_tipo"]))
	{
		/// datos del pro
		if($registrando["id_registrador"]=="100")		   //redirecciones de REGALO en nombremania
		{
			$datos["nm_wf_wp"]="regalo";
			$datos["nm_mf"]="*/".$registrando["regalo_enviar_email"];
		}
		else
		{
			$datos["nm_wf_wp"]=$registrando["nm_wf_wp"];
			$datos["nm_mf"]=$registrando["nm_mf"];
		}
	}
	$datos["nm_origen"]=$_POST["nm_origen"];
	if($registrando['contactos']['billing']['country']=='ES')
	{
		$datos["nm_nif"]=$_POST["nm_nif"];
		if($_POST["nm_nif"]=="nm_nofactura")
		{
			//se agrega sin iva si nif=nm_nofactura
			$datos['nm_preciototal']=calculaprecio($registrando["nm_registro_tipo"],$registrando['period'],$registrando['domain'],'euros',false,$registrando['descuento']);
		}
		else
		{
			$datos['nm_preciototal']=calculaprecio($registrando["nm_registro_tipo"],$registrando['period'],$registrando['domain'],'euros',true,$registrando['descuento']);
		}
	}
	else
	{
		$datos['nm_preciototal']=calculaprecio($registrando["nm_registro_tipo"],$registrando['period'],$registrando['domain'],'euros',false,$registrando['descuento']);
	}
	$datos["id_registrador"]=$registrando["id_registrador"];
	$sql=insertdb("solicitados",$datos);   //graba en la tabla

	$rs=$conn->execute($sql);
	$id=$conn->Insert_ID();
	//  envio de email al cliente

	$dat=array();
	$dat["nombre"]=$registrando['contactos']['billing']["last_name"].", ".$registrando['contactos']['billing']["first_name"];
	$dat["fecha"]='';
	$dat["email_cliente"]=$registrando['contactos']['billing']["email"];

	if($dat["email_cliente"]=='')
	{
		mostrar_error_nm("no def error en el envio del Mail");
		exit;
	}
	if($registrando["id_registrador"]==100)
	{
		// guardamos los datos en la tabla de regalos
		$aux_regalo["id_solicitud"]=$id;
		$sql_regalo=insertdb("regalo",$aux_regalo);
		$regalito=$conn->execute($sql_regalo); //alta en tabla de regalos
		unset($regalito);
	}

	$dominio=$registrando['domain'][0];
	//echo "docminio=$dominio";exit;
	$destino="http://".$_SERVER['SERVER_NAME']."/registro/"."datos_pago.php?id_operacion=$id&dominio=$dominio";
	$dat['destino']=$destino;
	if($registrando['reg_type']=='transfer')
	{
		$dat['transfer']='Recuerde leer las instrucciones de nuestra pagina para los traslados de dominios, http://www.nombremania.com/ayuda/traslado_dominios.html ';
	}
	else
	{
		$dat['transfer']='';
	}
	$ruta=$_SERVER['DOCUMENT_ROOT'];
	include("enviar_email.php");
	if(!envioemail($ruta."/procesos/mails_a_enviar/mail_recepta.txt",$dat))
	{
		mostrar_error_nm("Error en el envio del Mail");
    	exit;
    }
    
	// variable para controlar el envio unitario
	$registrando["recepta"]="OK";
   //
	header("Location: datos_pago.php?id_operacion=$id&dominio=$dominio");
}

    
function dom_seleccionado($ddd)
{
	global $registrando;
	if(!isset($registrando['domain']) or !is_array($registrando['domain']))return false;

	$valores=array_values($registrando['domain']);
	return in_array($ddd,$valores);
}

function error($error)
{
	$error=urlencode($error);
	header("Location: /error.php?error=$error\n");
	exit;
}

function dneses()
{
	global $registrando;
	extract($_POST);
	$errores=array();
	$dns_validas=array();

	for($i=1;$i<=2;$i++)
	{
    	$aux=trim("fqdn$i");
		if($$aux<>'')
		{
			if(!@checkdnsrr($$aux,"A"))
			{
				$errores[]="La dns numero $i no es v&aacute;lida.";
			}
			elseif (in_array($$aux,$dns_validas))
			{
				$errores[]="Las dns ingresadas deben ser diferentes. ";    
			}
			else
			{
				$dns_validas[]=$$aux;
			}
		}
		else
		{
			$errores[]="La dns numero $i no es v&aacute;lida.";
		}
	}
	for($i=3;$i<=6;$i++)
	{
		$aux=trim("fqdn$i");
		if(!isset($$aux) or $$aux=='')
		{
			break;
		}
		if(!@checkdnsrr($$aux,"A"))
		{
			$errores[]="La dns n&uacute;mero $i no es v&aacute;lida.";
		}
		else
		{
			$dns_validas[]=$$aux;
        }
	}
	if(count($errores)>0)
	{
		mostrar_error_nm($errores);
		exit;
	}
	$registrando["nameserver_list"]=$dns_validas;

	return true;
}


function pro($dominio)
{
	$numero=0;
	$ret="<table border=1>";
	foreach($dominio as $dom)
	{
		$numero++;
		$domainupper=strtoupper($dom);
		$ret.=get_content("redireccion.html",array("domainupper"=>"$domainupper", "numero"=>"$numero"));
	}
	$ret.="</table>";

	return $ret;
}

function verifica_tecnico()
{
	global $registrando,$debug;
	extract($_POST);
	
	while(list($k,$v)=each($registrando['contactos']['owner']))
	{
		if(isset($_POST['flag_tech_use_contact_info']))
		{
			$registrando['contactos']['tech'][$k]=$v;
		}
		else
		{
			$registrando['contactos']['tech'][$k]=$_POST["tech_$k"];
			//  OJO : validar el contacto tecnico de las variables post
		}
	}

	require_once('osrsh/openSRS.php');

	$O=new openSRS($_test_or_live);

	//$datos['dominio']=$registrando['domain'];


	$validado=$O->validate_nm($registrando,'true');  //valida los tecnicos y todos los campos
	
	/*if($_SERVER['REMOTE_ADDR']=='87.216.16.239')
	{
		$validado['is_success']=1;
	}*/
	
	if($validado['is_success']==1)
	{
		return true;
	}
	else	 // hay errores anteriores
	{
		$errores=array($validado['error_msg']);
		mostrar_error_nm($errores);
		exit;
	}
}

function mostrar_precio()
{
	global $registrando;
	$precio='<b>'.$GLOBALS['simbolos_monedas']['euros']."</b> ".$registrando['nm_preciototal']." ";
	$precio .= " - (" . round($registrando['nm_preciototal']*166.386,0)." ".$GLOBALS["simbolos_monedas"]['ptas'].")";

	return $precio;
}

function multilingue_temp($dominio)
{
	include_once('conf.inc.php');
	include_once($_SERVER['DOCUMENT_ROOT']."/registro/func_registro.inc.php");
	
	$tmp=multilingue($dominio);
	$resultado=$tmp['punycode'];
	return $resultado;
}
?>