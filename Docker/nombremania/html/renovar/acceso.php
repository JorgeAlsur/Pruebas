<?
include('hachelib.php');
// para mostrar los template de este directorio
//var_crudas($_POST);
include('conf.inc.php');
include($_SERVER['DOCUMENT_ROOT'].'/registro/func_registro.inc.php');
$registrando['templates']=$_SERVER['DOCUMENT_ROOT'].'/registro/templates';

if($_SERVER['REMOTE_ADDR']=='89.130.215.195')$debug=0;

if(isset($_GET['reg']))
{
	$reg=$_GET['reg'];
	setcookie('REGISTRANT_LIVE_KEY',$reg);
}
else $reg=$_COOKIE['REGISTRANT_LIVE_KEY'];
$cookie=$reg;

/**
	JOSE (12-03-2010):
	Uso de expiredate actualmente.
	-	login = expiredate bien, pero para parsear
	-	verify = usa vencimiento que no se asigna nunca
	-	renew = inserta el anyo actual
**/


if($action=='login')
{
	if((isset($_COOKIE['REGISTRANT_LIVE_KEY'])||$reg) and $c=='manage')
	{
		//$cookie=$_COOKIE["REGISTRANT_LIVE_KEY"];
		if($reg=='')$reg=$_COOKIE['REGISTRANT_LIVE_KEY'];
		$cookie=$reg;
		$administrando=datos_por_cookie($reg);
		$reg_domain=$administrando['attributes']['domain'];
	}
	else
	{
		//viene de manage y tiene la cookie seteada
		if($reg_domain=='' or $reg_username=='' or $reg_password=='')
		{
			mostrar_error_nm('Faltan datos para realizar la renovaci&oacute;n, vuelva y reintente');
			exit;
		}
		$cookie=setear_cookie($reg_domain,$reg_username,$reg_password);
	}
	
	$perfil=opensrs_profile($cookie);
	
	//var_crudas($perfil);
	$datos=array();
	$datos["domains"]=$reg_domain;
	$datos["vencimiento"]=$perfil["expiredate"];
	$datos["reg_profile"]=$reg_domain;
	$datos["reg_type_text"]="Renovaci&oacute;n";
	$registrando=array();	//usado para las templates y otras cosas

	include("basededatos.php");
	$conn->debug=0;
	$ADODB_FETCH_MODE=ADODB_FETCH_ASSOC;
	
	/*
	
	JOSE 2010-05-14:
	
	Busca el estado en la tabla zonas en la fecha actual, ya que solo se puede downgradear.
	
	Antes buscaba el tipo en la tabla dominios (no cambiaba desde la primera vez) y ahora solo cuenta si existe el dominio.
	
	*/
	//$sql="select tipo from dominios,operaciones where operaciones.id=dominios.id_operacion and dominios.dominio = '$reg_domain' ";
	$sql="select count(*) as cuenta from dominios,operaciones where operaciones.id=dominios.id_operacion and dominios.dominio = '$reg_domain' ";
	//$sql="select tipo from zonas where dominios.dominio = '$reg_domain' and desde<=CURDATE() and hasta>=CURDATE()";
	$rs=$conn->execute($sql);
	$existe_dominio=$rs->fields['cuenta'];
	
	// sql modificado para que siga saliendo la zona dos semanas despues de que se cumpla, para que siga manteniendo el tipo.
	//$sql="select tipo from zonas where dominio = '$reg_domain' and hasta>=CURDATE()";
	$sql = "select tipo from zonas where dominio = '$reg_domain' and DATE(hasta)>=SUBDATE(CURDATE(), INTERVAL 2 WEEK)";
	$rs=$conn->execute($sql);
	$x=0;
	$tipo=strtolower($rs->fields["tipo"]);	//obtencion de la pos de producto
	if($rs->fields["tipo"]=="")$rs->fields["tipo"]="estandar";
	foreach($nm_productos as $key=>$value)
	{
		if($key==$tipo)
		{
			$pos=$x;
			break;
		}
		$x++;
	}
	$a_ofertar=$nm_productos;
	if($debug)$debug_string.="pos=$pos<br/>";
	$a_ofertar=array_slice($nm_productos,0,$pos+1); //corta el array para permitir solo downgrade de productos.
	if($debug)$debug_string.="tipo=".$rs->fields["tipo"]."<br/>";
	$datos["producto_actual"]=strtolower($rs->fields["tipo"]);
	//echo $rs->fields["tipo"];exit;
	$periodos=$REG_PERIODS;   //cambiar en caso de cambios en los periodos de renovacion
	
	if(substr($reg_domain,strlen($reg_domain)-3)=='.es')$period_default=1;
	else $period_default=2;
	
	$periodos=form_select($periodos,"period",$period_default,true);
	$productos=form_select($a_ofertar,"producto",$tipo,true);
	$datos['productos']=$productos;
	$datos['periods']=$periodos;
	//if($conn->affected_rows()<=0) //  or $conn->affected_rows()>1
	if(((int)$existe_dominio)<=0) //  or $conn->affected_rows()>1
	{
		//var_crudas($rs);
		@mail("soporte@nombremania.com","El dominio $reg_domain no esta en la base de datos","imposible renovar -.----  REPARAR !!!!!!!!!","From: Alertas Nombremania <soporte@nombremania.com"); 
		mostrar_error("Error en la recuperacion de datos para la renovaci&oacute;n, contacte con soporte@nombremania.com");
		exit;
	}
	$datos['CGI']=$_SERVER['PHP_SELF'];
	if($debug)mail("jose@alsur.es","Debug System renovaciones",$debug_string,"From: Debug System <jose@alsur.es>\nContent-Type: text/html");
	print_form('seleccion.html',$datos);
}
elseif($action=='verify')
{
	//verificar los datos 
	//var_crudas($_COOKIE);
	$datos=array();

	//echo $reg;exit;
	$perfil=opensrs_profile($reg);

	$administrando=datos_por_cookie($reg);
	$datos['domains']=$administrando['attributes']['domain'];

	//$datos["vencimiento"] =$administrando["expiredate"];
	//$datos["producto"]=$producto;
	$datos['period']=$period;
	$datos['vencimiento']=$vencimiento;
	$datos['reg_type_text']="Renovaci&oacute;n";
        if(count($perfil['contact_set']['owner']) > 0) {
        while(list($k,$v)=each($perfil['contact_set']['owner']))
        {
                $datos["owner_$k"]=$v;
        }
        }
	//$datos["owner_country"] = countries("owner_country",$datos["owner_country"]);
        	

        if(count($perfil['contact_set']["billing"]) > 0) {
        while(list($k,$v) = each($perfil['contact_set']["billing"]))
        {
                $datos["billing_$k"]=$v;
        }
        }

	$preciototal=calculaprecio($producto,$period,array($datos["domains"]),"euros",false,0);

	$datos['nm_preciototal']=$preciototal;
	$datos['nm_precioconiva']="&euro; ".$preciototal*1.21;
	$datos['nm_precioconiva_ptas']=round($preciototal*1.21*166.386, 0).' Ptas';
	$datos['nm_iva']="&euro; ".$preciototal*0.21;
	$datos['CGI']=$_SERVER['PHP_SELF'];
	$datos['"period']=$period;
	//cambio por producto a secas
	$datos['producto']=strtolower($producto);

	if($_SERVER['REMOTE_ADDR']=='87.216.16.239')
	{
		if($datos['billing_email']=='')
		{
			foreach($datos as $key=>$value)
			{
				if(substr($key,0,6)=='owner_')$datos['billing_'.substr($key,6)]=$value;
			}
		}
		//print_r($datos);exit;
	}

	if($datos['billing_country']=='ES')
	{
		/*$datos["nm_nif_text"]="<p><b><font color=\"#CC0000\">ATENCION!!</font></b><font color=\"#CC0000\">
                    - Al ser el titular residente en Espa&ntilde;a,<b> tendr&aacute;
                    que introducir el NIF </b>correspondiente.</font></p> ";
	*/
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

	}
	$registrando['templates']=".";
	if($producto_actual != $producto)
	{
		$datos["error_cambio_de_producto"] ="<p><br>
                        <b><font color=\"#990000\"> &iexcl;&iexcl;&iexcl;ATENCI&Oacute;N!!! 
                        </font></b><font color=\"#990000\"><br>
                        Has solicitado renovar un dominio con un tipo de registro 
                        inferior al actual ($producto_actual). Este cambio puede provocar que se 
                        pierdan redirecciones de correo y web y llegado el caso 
                        que el dominio deje de ser operativo. Contin&uacute;a 
                        solo si estas seguro de que esta es la opci&oacute;n que 
                        deseas.</font></p> ". $nm_productos[$producto] ;
	}
	print_form('verificacion.html',$datos);
}
elseif($action=='renew')
{
	include_once('basededatos.php');
	$datos=array();
	$perfil=opensrs_profile($reg);
	$administrando=datos_por_cookie($reg);

	foreach($perfil['contact_set']['owner'] as $campo=>$valor)
	{
		if($valor!='')$datos["owner_$campo"]=addslashes($valor);
	}
	if(isset($perfil['contact_set']['billing']))foreach($perfil['contact_set']["billing"] as $campo=>$valor)
	{
		$datos["billing_$campo"]=addslashes($valor);
	}
	else
	{
		foreach($perfil['contact_set']['owner'] as $campo=>$valor)
		{
			if($valor!='')$datos["billing_$campo"]=addslashes($valor);
		}
	}
	
	foreach($perfil['contact_set']['tech'] as $campo=>$valor)
	{
		$datos["tech_$campo"]=addslashes($valor);
	}
	unset($datos['billing_url'], $datos['billing_status']);
	unset($datos['owner_url'], $datos['owner_status']);
	unset($datos['tech_url'], $datos['tech_status']);
	
	$datos['domain']=$administrando["attributes"]['domain'];
	
	$datos["period"]=$period;
	$preciototal=calculaprecio($producto,$period,array($datos['domain']),"euros",false,0);
	$datos["nm_preciototal"]=$preciototal;
 
	$datos["reg_type"]="renew";
	$datos["date"]=time();
	$datos["nm_registro_tipo"]=$producto;
	/**
	
	JOSE (15-03-2010)
	Cambio para que se guarde el anyo de expiracion
	real, siempre se guardaba el actual y en caso de
	renovar un dominio antes de llegar a ese anyo,
	daba error en opensrs.
	
	**/
	$expiredate=$perfil['expiredate'];
	$datos["vencimiento"]=date("Y-m-d", strtotime($expiredate)); // Si se crea el campo
	$datos["nm_expira"]=date("Y", strtotime($expiredate));
	//$datos["nm_expira"]=date("Y");
	if($datos["billing_country"]=="ES")
	{
		if($nm_nif=="")
		{
			mostrar_error_nm("Los residentes espa&ntilde;oles deben indicar el nif para validar la operaci&oacute;n.");
			exit;
		}
		$datos["nm_nif"]=$nm_nif ;
		$datos["nm_preciototal"]=$preciototal*1.21;
	}
	
	unset($datos['owner_lang']);
	unset($datos['billing_lang']);
	unset($datos['tech_lang']);
	unset($datos['tech_vat']);
	
	$sql=insertdb('solicitados',$datos);

	/*if($_SERVER['REMOTE_ADDR']=='87.216.16.239')
	{
		print_r($datos);
		print_r($sql);
		exit;
	}*/

	//if ($_SERVER['REMOTE_ADDR']=="213.96.190.180") $conn->debug=1;
	$rs=$conn->execute($sql);

	if(!$rs)
	{
		@mail("sistemas@alsur.es","El dominio $reg_domain ha fallado en el registro",$sql,"From: Alertas Nombremania <soporte@nombremania.com");
		mostrar_error_nm('Error en el alta de renovacion, vuelva atr&aacute;s y reintente.');
		exit;
	}
	$id=$conn->Insert_ID();
	$dominio=$datos['domain'];
	$x="";
	if($_SERVER['SERVER_NAME']=='ssl.alsur.es')
	{
		$_SERVER['SERVER_NAME']='ssl.alsur.es/nm'; //lo cambio solo  en el caso de atacar desdde ssl.alsur.es
		$x="s";								 
	}
	$destino="http$x://".$_SERVER['SERVER_NAME']."/registro/"."datos_pago.php?id_operacion=$id&dominio=$dominio";

	$ruta=$_SERVER['DOCUMENT_ROOT'];
	include_once("enviar_email.php");
	$dat=array();
	$dat['nombre']=$perfil['contact_set']["billing"]["last_name"].", ".$perfil['contact_set']["billing"]["first_name"];
	$dat["fecha"]="";
	$dat["destino"]=$destino;
	$dat["email_cliente"]=$perfil["contact_set"]["billing"]["email"];
	//$dat["email_cliente"] = "horaciod@alsur.es";

	if(!envioemail("../procesos/mails_a_enviar/mail_recepta_renovar.txt",$dat))
	{
		mostrar_error_nm("Error en el envio del Mail.");
		exit;
	}

	// variable para controlar el envio unitario
	$registrando["recepta"]="OK";
	//
	header("Location: $destino");
}
//var_crudas($_POST);
?>
