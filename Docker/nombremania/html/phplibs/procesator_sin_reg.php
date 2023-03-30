<?
// procesator_sin_reg.php
set_time_limit(0);
$conn=false;

include("basededatos.php");

function pagator($tipo,$id,$cod_aprob)
{
	global $conn;
	if(!isset($tipo) or $tipo=="")
	{
		return false;
	}
	$fecha=date("d-m-Y h:i:s");
	loguear("**************** $fecha ****************");
	loguear("Registrando pago $tipo , solicitud=$id,codigo= $cod_aprob");
	$ok=false;$ok2=false;
	switch($tipo)
	{
		case "manual":
			$ok=grabar_codigo($id,"nm-".$cod_aprob);
		break;
		case "cliente":
			$ok=grabar_codigo($id,"cf-".$cod_aprob);
		break;
		case "banco":
			$ok=grabar_codigo($id,"4b-".$cod_aprob);
			if($ok)
			{
				$sql="select nm_preciototal,domain from solicitados where id = $id";
				$rs=$conn->execute($sql);
				if($rs===false)
				{
					loguear("ERROR en recuperacion de datos de solicitados, falta grabar en transacciones operacion 4B ". mysql_error());
				}
				else
				{
					$importe=$rs->fields["nm_preciototal"];
					$observacion="pago por ".$rs->fields["domain"];
					$fecha= date("Y-m-d");
					$sql="insert into transacciones (id_cliente,importe,tipo,fecha,observacion)
					values (1,$importe,'d','$fecha','$observacion')";
					$conn->execute($sql);
				}
			}
		break;
	}
	if($ok)
	{
		$ok2= armar_cola($id);
	}
	else
	{
		$ok2=false ;
	}
	$fecha=date("d-m-Y h:i:s");
	loguear("**************** $fecha ****************");

	return $ok2;
}

function loguear($texto="")
{
	include("conf.inc.php");
	if($texto=="")return false;
	$texto=date("d-m-Y h:i:s")." -- $texto";
	$texto="\n $texto";
	$fech_archivo=date("Ymd");
	$archivo=$dir_logs."/log-$fech_archivo.log";
	$fp = fopen($archivo, "a");
	fwrite($fp,$texto);
	fclose($fp);
}

function grabar_codigo($id,$cod_aprob)
{
	global $conn;
	include_once("hachelib.php");
	//var_crudas($conn);
	if($conn===false)
	{
		loguear("Error en la conexion con la base de datos , grabando datos de pago operacion: $cod_aprob \n error en la conexion con la base de datos");
		return false;
	}
	$sql="update solicitados set nm_cod_aprob=\"$cod_aprob\" where id=$id";
	$rs=$conn->execute($sql);
	if($rs===$false)
	{
		loguear("Error en la base de datos , grabando datos de pago operacion: $cod_aprob \n error en la grabacion del codigo".mysql_error());
		return false;
	}
	else
	{
		$sql2="select nm_nif,domain,nm_preciototal,owner_country from solicitados where id=$id";
		$rs2=$conn->execute($sql2);
		if($rs===$false)
		{
			loguear("Error en la base de datos , recuperando datos de la operacion (factura), id_solicitud:$id  \n ".mysql_error());
			return false;
		}
		else
		{
			if($rs2->fields["nm_nif"]<>"" and $rs2->fields["nm_nif"]<>"nm_nofactura"  and $rs2->fields["owner_country"]=="ES")
			{
				//$sql="insert into facturas (fecha,precio,concepto,nif) values (".time().",".$rs2->fields["preciototal"].",'Registro de dominios','".$rs2->fields["nif"]."')";
				$sql= "insert into facturas (nif,precio,fecha,concepto,nombre,direccion,poblacion,provincia,codigo_postal,id_solicitud)
select nm_nif as nif ,nm_preciototal as precio ,date as fecha, domain  as  concepto , billing_org_name as nombre,billing_address1 as direccion, billing_city as poblacion, billing_state as provincia ,
billing_postal_code as codigo_postal , id as id_solicitud from solicitados where id=$id";
				$rs3=$conn->execute($sql);
				if($rs3===false)
				{
					loguear("Error en la base de datos, error al grabar la factura solicitud_id = $id".mysql_error());
				}
			}
		}
		return  true;
	}
}


function armar_cola($id)
{
	// rutina que arma la cola de procesamiento una entrada por dominio
	global $conn;
	$sql="select domain,nm_cod_aprob,nm_registro_tipo from solicitados where id=$id ";
	$rs_dominios=$conn->execute($sql);
	if($rs_dominios==false)
	{
		loguear("Error en la base de datos, recuperando los datos de la base dato id:$id proceso de cola abortado ".mysql_error());
		return false;
	}
	$dominios = $rs_dominios->fields["domain"];
	$registros = explode("**",$dominios);
	$cod_aprob=$rs_dominios->fields["nm_cod_aprob"];
	$estado = (eregi("^PRO",$rs_dominios->fields["nm_registro_tipo"])) ? 1 : 2; //determina el tipo de registro
           /*
            1 para pro y 2 para ESTANDAR
           */
           
	$ok=true;
	foreach ($registros as $registro)
	{
		$duplicado=$conn->execute("select count(*) as canti from cola where id_solicitud=$id and dominio='$registro'");
		if($duplicado->fields["canti"]==0)
		{
			$sql="insert into cola (id_solicitud,dominio,fecha,estado,intentos,cod_aprob,en_proceso)
              values ($id,'$registro',NOW(),'$estado','0','$cod_aprob','0')";
			$rs2=$conn->execute($sql);
			if($rs2==false)
			{
				loguear("Error en grabacion de cola id_solicitud = $id dominio= $registro".$conn->ErrorMsg());
				$ok=false;
			}
		}
		else
		{
			loguear("Error, registro duplicado en la cola id_solicitud: $id, dominio=$registro");
			$ok=false;
		}
	}
	if($ok)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function procesa_cola()
{
	//include_once( "basededatos2.php");
	// lee entradas en la cola ordenadas por intentos primero las que menos intentos tengan
	global $conn;
	$time=time()-600 ; //tiempo limite 10 minutos por proceso

	$sql="select * from cola where estado<>100 and (en_proceso=0 or en_proceso<$time) order by intentos ";
	$rs=$conn->execute($sql);

	if($rs==false or $conn->Affected_Rows()==0)
	{
		loguear("\n\tProceso de cola iniciado, sin tareas pendientes\n finalizado.");
		return ;
	}
  
	$procesados=0;
	$tit_ini=str_repeat("-=", 60);
	loguear("$tit_ini\n\t\tINICIO PROCESO DE COLA\n$tit_ini");
	unset($tit_ini);
	$aciertos=0;
  
	while(!$rs->EOF)
	{
		$ok=false;
		$id_cola=$rs->fields["id"];

		if($rs->fields["estado"]==1)
		{
			$ok=alta_zoneedit($rs->fields["id_solicitud"],$id_cola,$rs->fields["dominio"],$rs->fields["intentos"]);
		}
		elseif($rs->fields["estado"]==2)
		{
			$ok=alta_opensrs($rs->fields["id_solicitud"],$id_cola,$rs->fields["dominio"],$rs->fields["intentos"]);
		}
		elseif($rs->fields["estado"]==3)
		{
			$ok=a_registrados($id_cola,$rs->fields["id_solicitud"],$rs->fields["dominio"]);
		}
		if($ok)$aciertos++;
		$procesados++;
		$rs->MoveNext();
	}
	loguear("Procesados en cola $procesados correctos $aciertos");
	loguear("Terminado el proceso: ".date("d-m-Y h:i:s"));
	loguear("\n*********************************************************\n");
}

function calcula_userzone($zone)
{
	$largo_zone=strlen($zone);
         $largo_total=50;
         if ($largo_zone>$largo_total){
         list($nombre,$suf)=split("\.",$zone); //separa el sufijo
         $largo_sufijo=strlen($suf);
         $largo_nombre=strlen($nombre);

         $largo_posible_nombre=$largo_total-$largo_sufijo- 3 - 1 ;  // 1 por el punto -3 por los random
         $n1=substr($nombre,0,$largo_posible_nombre); // -1 porque empieza en 0
          srand((double)microtime()*1000000);
          $num=rand(0,999);
         $zone=$n1.$num.".".$suf;
         }
         return $zone;
}

function alta_zoneedit($id_solicitud,$id_cola,$dominio,$intento){
Global $conn;
//return true;   /// quitar
$sql="select id,nm_wf_wp,nm_mf from solicitados where id=$id_solicitud";
$dom=$conn->execute($sql);
$mailzone=$dom->fields["nm_mf"];
list($mail_desde,$mail_hasta) = explode("/",$mailzone);
$wfzone=$dom->fields["nm_wf_wp"];
if ($dom==false){
    loguear("error en la recuperacion de los datos de la zona para la creacion");
    return false;
}

         include_once("zoneedit.php");
         $userzone=calcula_userzone($dominio);

        if (agrega_usuario($userzone,"nm2008")) {
                $bien_zona=true;
               //graba el estado a estado +1
               loguear("alta de usuario en zoneedit relizada con exito, usuario:$userzone");
               $dns=agrega_zona($userzone,$dominio);

               if (is_array($dns) and $dns["resultado"]){
                  $bien_zona=true;
                  loguear("- Alta de zona en zoneedit realizada con exito, usuario:$userzone ,dominio=$dominio\n dns asignadas = ");
                  if ($dns["dns1"]=="" or $dns["dns2"]==""){
                  loguear("- Zona dada de alta pero sin dns ");
                  //asigno dns por defautl
                  $sql="update cola set dns1='ns1.zoneedit.com', dns2='ns2.zoneedit.com' where id = $id_cola";
                  mail("soporte@nombremania.com","Error de creacion de zona","dominio : $userzone sin dns asignadas por zoneedit, chequear log");
                  }
                  else {
									loguear("dns1 = {$dns["dns1"]} , dns2= {$dns["dns2"]} ");
                  $sql="update cola set dns1='".$dns["dns1"]."', dns2='".$dns["dns2"]."' where id = $id_cola";
                  }
                  $dnss=$conn->execute($sql);
                  if ($dnss==false){
                  loguear("Error en la grabacion de dns cola dns1=".$dns["dns1"]." dns2=".$dns["dns2"].".");
                  }
                  if ($mailzone==""){
                     loguear("Mailforward no creado por falta de direccion de destino");
                  } else {
                   // crear mailforward
                    if (agregar_registro($dominio,"MF","dnsto=$mail_desde&forward=$mail_hasta",$userzone)){
                    loguear("mail forward creado con exito $mailzone\n");
                    }
                    else {
										mail("soporte@nombremania.com","Error de Al crear Mailforward","Dominio : $userzone ,  \ncomando enviado : dnsto=$mail_desde&forward=$mail_hasta chequear log.");																						 
                    loguear("Error al crear el mailforward\n");
                    $bien_zona=true;
                    }
                   }
                    //crear webforward
                    if ($wfzone<>"parking"){
                                  if (eregi("^http://",$wfzone)){
                                  if (agregar_wf($dominio,"",$wfzone,$userzone)){
                                   loguear("web forward agregado a zona $dominio\n");
                                  } else {
                                   $bien_zona=true;
                                   loguear("Error al crear web forward en zona $dominio\n");
                                  }
                                  }
                                  else {
                                  loguear("el webforward no empieza con http://");
                                  $bien_zona=true;
                                  }
                           }
                     else {
                    // agrega un web parking default
                        if (agregar_registro($dominio,"WP","dnsfrom=www&dnsto=Under+Construction",$userzone)){
                           loguear("web parking creado con exito zona = $dominio");
													 $bien_zona=true;													 							
                         }
                         else {
                            $bien_zona=true;
                         loguear("error en la creacion del web parking $dominio ");
                         }
                    }
               }
               else {
                  loguear("Error en alta de zona en zoneedit , usuario:$userzone ,dominio=$dominio. ");
                                  $bien_zona=false;
               }
          }
         else {
               //aumenta la cantidad de intentos
							 loguear("Error de creacion de usuario en Zoneedit");
               $bien_zona=false;
        }
//     15 intentos para asignar dns default y enviar email
         if (!$bien_zona){
              if ($intento>8){
              mail("soporte@nombremania.com","ERROR en zoneedit","se produjo un error en zoneedit con el registro de cola =$id_cola CHEQUEAR. ");
							mail("626288508@correo.movistar.net","ERROR en zoneedit","se produjo un error en zoneedit con el registro de cola =$id_cola CHEQUEAR. ");
              $update="update cola set estado=91 where id=$id_cola";
              $rs=$conn->execute($update);
              return false;
              }
              else {
               loguear("Error en alta de usuario en zoneedit. usuario:$userzone");
               $sql="update cola set intentos=intentos+1 where id=$id_cola";
               $rs=$conn->execute($sql);
               return false;
               }
            }
            else {
               /*
                 cambiar en caso de solo venta de OPCIONES PRO
               */
               $sql="update cola set estado=2 where id=$id_cola";
               $rs=$conn->execute($sql);
            if ($rs===false ){
               mail("soporte@nombremania.com","Error en cambio de estado","Se produjo un error en zoneedit con el registro de cola =$id_cola CHEQUEAR. ");
                }
               return true;
               }

}


/*
Toma todos los datos de la cola y los reparte en las diferentes tablas segun el tipo
*/
function a_registrados($id_cola,$id_solicitud,$dominio){
Global $conn;
 $conn->debug=0;
$rs=$conn->execute("select * from solicitados where id=$id_solicitud");
if ($rs===false){
        loguear("Error en la grabacion en registrados");
        return false;
}

$datos=$rs->fields;
$reg_username=addslashes($datos["reg_username"]);
$affiliate_id=addslashes($datos["affiliate_id"]);
$reg_password=addslashes($datos["reg_password"]);
$reg_type=addslashes($datos["reg_type"]);
$period=addslashes($datos["period"]);
$owner_first_name=addslashes($datos["owner_first_name"]);
$owner_last_name=addslashes($datos["owner_last_name"]);
$owner_email=addslashes($datos["owner_email"]);
$owner_phone=addslashes($datos["owner_phone"]);
$tipo=$datos["nm_registro_tipo"];
$precio=$datos["nm_preciototal"];
$id_solicitud=$datos["id"];
$cod_aprob=addslashes($datos["nm_cod_aprob"]);
//$aux=split(":",$datos["resultado_opensrs"]);
$codigo_opensrs=$datos["nm_resultado_opensrs"];
$dominios=$datos["domain"]; //guardamos todos los dominios
$affiliate_id=$datos["affiliate_id"];

// verifica que no este repetido el id en operaciones
$sql="select id from operaciones where id_solicitud = $id_solicitud";
$aux = $conn->execute($sql);
$fecha=time();
if ($aux <> false and $conn->affected_rows()==0){
     //  $rs2=$conn->execute("insert into registrados (dominio,)");
       $sql="insert into operaciones (fecha,affiliate_id,reg_username,reg_password,reg_type,period,domain,owner_first_name,owner_last_name,owner_email,owner_phone,tipo,codigo_opensrs,id_solicitud,precio,cod_aprob)
            values
          ('$fecha','$affiliate_id','$reg_username','$reg_password','$reg_type','$period','$dominios','$owner_first_name','$owner_last_name','$owner_email','$owner_phone','$tipo','$codigo_opensrs','$id_solicitud','$precio','$cod_aprob')";
          $operacion=$conn->execute("$sql");
        $id_operacion=$conn->insert_id();
}else {
      $operacion=true;
      $id_operacion=$aux->fields["id"];
}

  if ($operacion === false ){
  loguear("Error en la grabacion  en registrados dominio: $dominio , id_solicitud=$id_solicitud , id_cola=$id_cola".mysql_error());
           return false;
  }
  else {
       // insert into dominios
       // $precio=calculaprecio();   //agregar el calculo de precio
        $precio=20;
        $sql="insert into dominios (fecha,id_solicitud,id_operacion,dominio,period,precio,cod_opensrs,affiliate_id)
        values ($fecha,$id_solicitud,$id_operacion,'$dominio',$period,$precio,'$cod_opensrs','$affiliate_id')";
        $rs=$conn->execute($sql);
        if (eregi("^PRO",$tipo)){
            $tipo= strtoupper($tipo);
           if ($tipo=="PRO1"){
              $emails=0 ; $redirecciones=0;
              
           }elseif ($tipo=="PRO2"){
              $emails=5 ; $redirecciones=5;
           }elseif ($tipo=="PRO3"){
              $emails=99999 ; $redirecciones=99999;
                }
           $sql_zona="insert into zonas (fecha,id_solicitud,id_operacion,dominio,period,emails,redirecciones,tipo,precio)
           values ($fecha,$id_solicitud,$id_operacion,'$dominio',$period,$emails,$redirecciones,'$tipo',$precio)";
           $rs=$conn->execute($sql_zona);
            }

        $updcola=$conn->execute("update cola set estado=100 where id=$id_cola");
                 if ($updcola){
                   	$sql_para_email="select count(*) as estados from cola where id_solicitud = $id_solicitud and estado<90";
										$contar = $conn->execute($sql_para_email);
										if ($contar and $contar->fields["estados"]==0){
											 enviar_mail_cliente($id_solicitud);
											 }
									   return true;
                   }
  								 
	}
}

function limpia($datos){
while (list($k,$v) = each($datos)){
	if (is_int($k)) unset($datos[$k]);
}
return $datos;
}


function alta_opensrs($id_solicitud,$id_cola,$dominio,$intentos){
GLOBAL $conn;

//$debug=true ; //para mostrar o no los mensajes de OPENSRS

   $bien_dominio=false;

if (!isset($id_solicitud) ) {
//     loguear("en el alta de opensrs Falta el identificador de la cola");
     $bien_dominio=false;

}
// recepta el identificador de la base de datos y procesa las peticiones de alta en opensrs y zoneedit si tiene opcion pro
$rs=$conn->execute("select *  from solicitados where id=$id_solicitud");
$cola=$conn->execute("select * from cola where id=$id_cola");

if ($rs===false or $cola===false ) {
   loguear("Error en base de datos");
exit;
    }

//preparo los datos a enviar
if (strtolower($rs->fields["nm_registro_tipo"])=="estandar"){
	$custom_nameservers=1;  //lo pone el cliente
	$custom_tech_contact=1;  // lo pone el cliente
}
else  // cualquier tipo de PRO
{
    $rs->fields["nm_registro_tipo"] = "PRO";
	$custom_nameservers=1; //los ponemos nosotros de zoneedit esta en uno porque lo grabamos en la base de datos
	$custom_tech_contact=0;   //lo pone opensrs de la base de datos
    $dns1=$cola->fields["dns1"]; //saca las dns de la cola de proceso grabadas alli por zoneedit()
    $dns2=$cola->fields["dns2"];
}
if ($datos["reg_type"]=="transfer"){
   $custom_nameservers=0;
}


$datos=limpia($rs->fields);
extract($datos);
$campos=array("first_name","last_name",
"phone","fax","email","org_name","address1","adress2","address3",
"city","state","country","postal_code","email"); // falta la URL no requerida

$owner=array();$billing=array();$tech=array();

foreach ($campos as $campo){ //registra cada registro con el mismo nombre
	$owner[$campo]=($datos["owner_$campo"]==NULL) ? "":  $datos["owner_$campo"];
	if ($custom_tech_contact== 1) $tech[$campo]= ($datos["tech_$campo"]==NULL) ? "": $datos["tech_$campo"];
	$billing[$campo]=($datos["billing_$campo"]==NULL) ? "": $datos["billing_$campo"];
}
// genera las entradas de dns y asigna el orden (importante)
if ($custom_nameservers==1){
$dns=array();
for ($i=1; $i<=6;$i++){
	$aux="fqdn$i";
	if (isset($$aux) and $$aux<>"") {
	$dns[]= array("name"=>$$aux,"sortorder"=>$i);
	}
}
}
if (eregi("^PRO",$nm_registro_tipo)) {
	$dns=array(array("name"=>$dns1,"sortorder"=>1),
		array("name"=>$dns2,"sortorder"=>2));
}


// LLAMADA A OPENSRS
require_once("osrsh/openSRS.php");
include("conf.inc.php");
$O = new opensrs($_test_or_live);
require_once("race.php");
include_once("hachelib.php");

$RACE = new Net_RACE("UTF-8");
list($n,$x)=explode(".",$dominio);
    $encoding_type="";
    if (!$RACE->doRace($n)){
    loguear("Error en la CONVERSION RACE del dominio : $dominio    - error{$RACE->raceError}");
    return false;
        }
        
/* echo "dominio orig: $dominio";
echo "<br>dominio UTF8: ".$RACE->raceResult;
*/
   if (!$RACE->raceConverted) {
     $dominio=$dominio;  //sin cambios
   //  echo "sin cambio";
        }
        else {
        $dominio = $RACE->raceResult.".$x";  //hecho el race
        $encoding_type="utf-8"; //no anda como dice el manual
      }


$cmd=array(
	"action"=>"sw_register",
	"object"=>"domain",
	"attributes"=>array(
		"domain"=>$dominio,
		"encoding_type"=>"$encoding_type",
		"reg_type"=>$datos["reg_type"],
		"reg_username"=>$datos["reg_username"],
		"reg_password"=>$datos["reg_password"]."2008",
		"affilliate_id"=>$datos["affiliate_id"],
		"period"=>$datos["period"],
		"bulk_order"=>0,
		"auto_renew"=>0,
		"process_inmediate"=>0,
		"custom_nameservers"=>$custom_nameservers,
		"custom_tech_contact"=>$custom_tech_contact,
		"contact_set"=>array(
			"owner" =>$owner,
			"billing"=>$billing,
		),
	)
);

// basado en perfil
// si tenemos dominios multiples asociamos todos al primer dominio
if ($datos["reg_domain"]) {
    $cmd["attributes"]["reg_domain"]=$reg_domain;
    $cmd["attributes"]["link_domains"]=1;
}
else {
$aux_domi=explode("**",$datos["domain"]);
if (count($aux_domi)>1){
   if ($dominio<>$aux_domi[0]){
    $cmd["attributes"]["reg_domain"]=$aux_domi[0];
    $cmd["attributes"]["link_domains"]=1;
   }
   }
else {
   $cmd["attributes"]["link_domains"]=0;
}
}


// agrego los datos de contacto tecnico
if ($custom_tech_contact==1) $cmd["attributes"]["contact_set"]["tech"]=$tech;
// agrego los datos de dns
if ($custom_nameservers==1) $cmd["attributes"]["nameserver_list"]=$dns;
/*
if (1){   print "<br>Comando enviado a OPENSRS <hr><pre>";
   var_dump($cmd);
   print "</pre><hr>";
	
}
*/

 //llamar al send_cmd para enviar los datos a opensrs.
$result=$O->send_cmd($cmd);
ob_start(); 
var_dump($result); 
$salida = ob_get_contents(); 
ob_end_clean();

loguear("\nResultado del envio a OPENSRS \n\n". $salida );


if ($result["response_code"]==200) {  //registro o traslado completo
   $aciertos++;
   $bien_dominio=true;
   $resultado=$result["response_code"]. " -- ". $result["response_text"];
    }
    else {
  $bien_dominio=false;
  $resultado=$result["response_code"]. " -- ". $result["response_text"];
}
if ($bien_dominio){
                   $resulta_osrs = $result["attributes"]["id"];
        $sql1="update solicitados set nm_resultado_opensrs=concat(nm_resultado_opensrs,'\n$dominio - $resulta_osrs') where id=$id_solicitud";
        $sql="update cola set estado=3 where id=$id_cola";
        $rs=$conn->execute($sql);
        $rs1=$conn->execute($sql1);
        if ($rs==false or $rs1==false) {
            loguear("Error en grabacion de la base de datos, proceso graba en dominios ". mysql_error() );
        }
        $bien_dominio=false;
        return true;
}
else {

     if ($intentos>4){
          mail("soporte@nombremania.com","ERROR en OPENSRS","se produjo un error en OPENSRS con el registro de cola =$id_cola CHEQUEAR. ");
          $update="update cola set estado=90 where id=$id_cola";
              $rs=$conn->execute($update);
              return false;
              }
              else {
   loguear("ERROR en la llamada a OPENSRS - id=$id_solicitud dominio=$dominio, resultado=$resultado");
   $sql="update cola set intentos=intentos+1 where id=$id_cola";
   $rs2=$conn->execute($sql);
   return false;
   }
 }
}

function enviar_mail_cliente($id_solicitud){
Global $conn;
include "enviar_email.php";
$rs=$conn->execute("select  * from solicitados where id= $id_solicitud");
$ruta=$_SERVER['DOCUMENT_ROOT'];
if ($rs===false ) {
								loguear("error al enviar el mail ".mysql_error());											 
	 							return false;
								}
$dat=array(); //datos de la factura al propietario

$rs_fact= $conn->execute("select * from facturas where id_solicitud= $id_solicitud");
if ($rs_fact===false  or $conn->affected_rows()<1) {
	 $factura=false;	 											 
	 	}
		else {
		$factura=true;
		$factura_owner=true;
		$link_factura = "<a href=http://www.nombremania.com/clientes/pago/i_factura.php?id="
									.$rs_fact->fields["id"].">Factura</a>";
		if ($rs->fields["owner_email"] != $rs->fields["billing_email"] ){
			 $factura_owner=false;
			 $df=array();
			 $df["first_name"]=$rs->fields["billing_first_name"];
			 $df["last_name"]=$rs->fields["billing_last_name"];
			 $df["email_cliente"]=$rs->fields["billing_email"];
			 $df["link_factura"]=$link_factura;
			 if (!envioemail($ruta."/procesos/mails_a_enviar/mail_solo_factura.txt",$df)){
			  loguear("Error en el envio del Mail , id  de solicitud : $id_solicitud");
				exit;
         }
			 			 						 			 											 														 
			 }else {
			 // se incluye el link a facturas en el mail del propietario
			 $dat["link_factura"]=$link_factura;
			 } 
		}
 

$dat["first_name"]=$rs->fields["owner_first_name"];
$dat["last_name"]=$rs->fields["owner_last_name"];
$dat["email_cliente"]=$rs->fields["owner_email"];

if ($dat["email_cliente"]==""){
loguear("error al enviar el mail a cliente id_solicitud = $id_solicitud , falta el emal del cliente");
exit;
    }

$dat["domain"]=$rs->fields["nm_resultado_opensrs"];

if ($rs->fields["reg_type"] == "transfer"){
   $dat["transfer"] = "Recuerde que si su solicitud es de traslado/s de 
dominio/s, deberá confirmar esta acción con el 
email que recibira el titular en el registrador actual. Para mas informacion consulte lasinstrucciones de nuestra pagina para los traslados de dominios, http://www.nombremania.com/ayuda/traslado_dominios.html ";
}
else {
     $dat["transfer"]="";
}

if (!envioemail($ruta."/procesos/mails_a_enviar/mail_aceptacion_registros.txt",$dat)){
 loguear("Error en el envio del Mail , id  de solicitud : $id_solicitud");
    exit;
    }
 
}

// if (isset($arma)) pagator("manual",224,"xxxxjjj"); asi se llama a cola
if (isset($procesa_cola)){
echo "<html><head><meta http-equiv=\"Content-type\" content=\"text/html; charset=UTF-8\"></head><body>
<h3>Verifique log de trabajo y cola de procesos</h3>
<a href=\"/nmgestion/ver_log.php\">Ver log de procesamiento</a><br>
<a href=\"/nmgestion/ver_log.php\">Ver cola de procesamiento</a><br>";
procesa_cola();
echo "</body></html>";
}

?>
