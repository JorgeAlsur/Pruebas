<?
// procesator.php
set_time_limit(0);
$conn=false;

include('basededatos.php');
include_once('conf.inc.php');

/*mb_internal_encoding('UTF-8');
$sql="SET NAMES 'utf8'";
$rs=$conn->execute($sql);*/

function pagator($tipo,$id,$cod_aprob,$expired=false)
{
	global $conn;
	if(!isset($tipo) or $tipo=='')
	{
		return false;
	}
	$fecha=date('d-m-Y h:i:s');
	loguear("**************** $fecha ****************");
	loguear("Registrando pago $tipo , solicitud=$id,codigo= $cod_aprob");
	$ok=false;$ok2=false;
	switch($tipo)
	{
		case 'manual':
			$ok=grabar_codigo($id,"nm-".$cod_aprob);
		break;
		case 'cliente':
			$ok=grabar_codigo($id,"cf-".$cod_aprob);
		break;
		case 'banco':
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
		if($expired)$ok2=armar_cola($id,true);
		else $ok2=armar_cola($id);
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
	
	//system('whoami');exit;
	
	if(file_exists($archivo))$fp=fopen($archivo,"a");
	else $fp=fopen($archivo,"w");
	fwrite($fp,$texto);
	fclose($fp);
	chmod($archivo,0664);
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
		$sql2="select nm_nif,domain,nm_preciototal,billing_country from solicitados where id=$id";
		$rs2=$conn->execute($sql2);
		if($rs===$false)
		{
			loguear("Error en la base de datos , recuperando datos de la operacion (factura), id_solicitud:$id  \n ".mysql_error());
			return false;
		}
		else
		{
			if($rs2->fields["nm_nif"]<>"" and $rs2->fields["nm_nif"]<>"nm_nofactura"  and $rs2->fields["billing_country"]=="ES")
			{
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

function armar_cola($id,$expired=false)
{
	// rutina que arma en la cola de procesamiento una entrada por dominio
	global $conn;
	$sql="select domain,nm_cod_aprob,reg_type, nm_registro_tipo, vencimiento from solicitados where id=$id ";
	$rs_dominios=$conn->execute($sql);
	if($rs_dominios==false)
	{
		loguear("Error en la base de datos, recuperando los datos de la base dato id:$id proceso de cola abortado ".mysql_error());
		return false;
	}
	$dominios = $rs_dominios->fields["domain"];
	$registros = explode("**",$dominios);
	$cod_aprob=$rs_dominios->fields["nm_cod_aprob"];
	$vencimiento=strtotime($rs_dominios->fields["vencimiento"]);
	//$estado = (eregi("^PRO",$rs_dominios->fields["nm_registro_tipo"])) ? 1 : 2; //determina el tipo de registro // JOSE .. estado inicial
	$estado = 20; // estado inicial fijo
	if($expired)$estado=77;
	if($rs_dominios->fields['reg_type']=='zona_change') $estado=30; // anadido

           /*
            1 para pro y 2 para ESTANDAR
           */
           
	$ok=true;
	foreach ($registros as $registro)
	{
		$registro=trim($registro);
		$duplicado=$conn->execute("select count(*) as canti from cola where id_solicitud=$id and dominio='$registro'");
		if($duplicado->fields["canti"]==0)
		{
			//$sql="insert into cola (id_solicitud,dominio,fecha,estado,intentos,cod_aprob,en_proceso)               values ($id,'$registro',NOW(),'$estado','0','$cod_aprob','0')";
			$sql="insert into cola (id_solicitud,dominio,fecha,estado,intentos,cod_aprob,en_proceso, procesar_fecha)               values ($id,'$registro',NOW(),'$estado','0','$cod_aprob','0', '$vencimiento')";
			$rs2=$conn->execute($sql);
			if($rs2===false)
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
	// include_once( "basededatos2.php");
	// lee entradas en la cola ordenadas por intentos primero las que menos intentos tengan
	global $conn;
	$time=time()-600 ; //tiempo limite 10 minutos por proceso

	$sql="select * from cola where estado < 100 and (en_proceso=0 or en_proceso<$time) order by intentos ";
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

		/**
		
			JOSE 15-03-2009:
			Modificado el orden, debido
			al cambio a hexonet.
		**/
		
		if($rs->fields["estado"]==20)
		{
			$ok=alta_opensrs($rs->fields["id_solicitud"],$id_cola,$rs->fields["dominio"],$rs->fields["intentos"]);
		}
		elseif($rs->fields["estado"]==30)
		{
			$ok=a_registrados($id_cola,$rs->fields["id_solicitud"],$rs->fields["dominio"]);
		}		
		elseif($rs->fields["estado"]==40)
		{
			// JOSE (11-03-2010) Cambio a alta en hexonet.
			$ok=alta_hexonet($rs->fields["id_solicitud"],$id_cola,$rs->fields["dominio"],$rs->fields["intentos"]);
		}
		elseif($rs->fields["estado"]==66)
		{
			// JOSE (17-03-2010) Cambio a alta en hexonet.
			$ok=borrado_zona($rs->fields["id_solicitud"],$id_cola,$rs->fields["dominio"],$rs->fields["intentos"]);
		}
		elseif ($rs->fields["estado"]==10 and $rs->fields["procesa_fecha"]<=time())
		{
			// email del regalo
			$ok=enviar_mail_regalo($rs->fields["id_solicitud"]);
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
	if($largo_zone>$largo_total)
	{
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

// JOSE .. alta en zoneedit
function alta_zoneedit($id_solicitud,$id_cola,$dominio,$intento)
{
	global $conn;
	//return true;   /// quitar
	$sql="select id,nm_wf_wp,nm_mf,reg_type,period,domain from solicitados where id=$id_solicitud";
	$dom=$conn->execute($sql);
	$mailzone=$dom->fields["nm_mf"];
	$reg_type= $dom->fields["reg_type"];
	$period=$dom->fields["period"];
	$zona = $dom->fields["domain"];
	list($mail_desde,$mail_hasta) = explode("/",$mailzone);
	$wfzone=trim($dom->fields["nm_wf_wp"]);
	if($reg_type =="renew")
	{
		// se actualiza la cantidad de periodos en la tabla de zonas y se espera a que el cron haga el resto
		// si ok se pasa a renovacion del dominio 
		$conn->debug=false;
		$control=$conn->execute("select * from zonas where dominio = '$zona' ");
		if($conn->affected_rows()<=0)
		{
			loguear("error zona no encontrada en tabla zonas imposible actualizacion id_cola= ".$id_cola);
			return false;										 
		}
		else
		{
			$id_zona=$control->fields["id"];
			$actualizacion=$conn->execute("update zonas set period = period+$period where id=$id_zona");
			if($actualizacion  ===false )
			{
				loguear("error en la actualizacion de los datos de la zona Renovacion id_cola= $id_cola");
				return false;
			}				 				
			else
			{
				loguear("Renovacion de la zona del dominio $zona id_cola $id_cola realizada con exito");
				$bien_zona=true;
			} 																 
		}
	}
	else
	{
		if($dom==false)
		{
			loguear("error en la recuperacion de los datos de la zona para la creacion");
			return false;
		}
		include_once("zoneedit.php");
		$userzone=calcula_userzone($dominio);
		if(agrega_usuario($userzone,"nm2008"))
		{
			$bien_zona=true;
			//graba el estado a estado +1
			loguear("alta de usuario en zoneedit relizada con exito, usuario:$userzone");
			$dns=agrega_zona($userzone,$dominio);
			if(is_array($dns) and $dns["resultado"])
			{
				loguear("alta de zona en zoneedit realizada con exito, usuario:$userzone ,dominio=$dominio\n dns asignadas = ");
				if($dns["dns1"]=="" or $dns["dns2"]=="")
				{
					loguear("zona dada de alta pero sin dns ");
					//asigno dns por defautl
					$sql="update cola set dns1='ns1.zoneedit.com', dns2='ns2.zoneedit.com' where id = $id_cola";
					mail("soporte@nombremania.com","Error de creacion de zona","dominio : $userzone sin dns asignadas por zoneedit, chequear log");
				}
				else
				{
					loguear("dns1 = {$dns["dns1"]} , dns2= {$dns["dns2"]} ");
					$sql="update cola set dns1='".$dns["dns1"]."', dns2='".$dns["dns2"]."' where id = $id_cola";
				}
                  $dnss=$conn->execute($sql);
                  if ($dnss==false){
                  loguear("Error en la grabacion de dns cola dns1=".$dns["dns1"]." dns2=".$dns["dns2"].".");
                  }
                  if ($mailzone==""){
                  loguear("Mailforward no creado por falta de direccion de destino");
                  } 
									else {
                  // crear mailforward
                    if (agregar_registro($dominio,"MF","dnsto=$mail_desde&forward=$mail_hasta",$userzone)){
                    loguear("mail forward creado con exito $mailzone\n");
                    }
                    else {
                    loguear("error al crear el mailforward con los parametros dnsto=$mail_desde&forward=$mail_hasta y $userzone\n");
                    $bien_zona=true;
                    }
                   }
                    //crear webforward
                    if ($wfzone<>"parking"){
										 if ($wfzone=="regalo"){
														$id_regalo=base64_encode("nm-" . $id_solicitud);
										 				$wfzone=urlencode("http://www.regaleundominio.com/regalo/index.php?ID=$id_regalo");
										 				if (agregar_wf($dominio,"",$wfzone,$userzone)){
														   loguear("Web forward de regalo agregado a zona $dominio\n");
														}							
									       }
                       elseif (eregi("^http://",$wfzone)){
		       // cambiado el wf a www
                                  if (agregar_wf($dominio,"www",$wfzone,$userzone)){
                                   loguear("web forward agregado a zona $dominio\n");
                                  } else {
                                   $bien_zona=true;
                                   loguear("  !!! Error al crear web forward en zona $dominio\n");
                                  }
                           }
                         else {
                                  loguear("El webforward no empieza con http://");
                                  $bien_zona=true;
                                  }
                           }
										else {
                    // agrega un web parking default
                        if (agregar_registro($dominio,"WP","dnsfrom=www&dnsto=Default",$userzone)){
                           loguear("web parking creado con exito zona = $dominio");
                         } else {
							 $bien_zona=true;
							 loguear("!!! Error en la creacion del web parking $dominio con $userzone");
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
               $bien_zona=false;
        }
}
			
         if (!$bien_zona){
              if ($intento>8){
              mail("soporte@nombremania.com","ERROR en zoneedit","se produjo un error en zoneedit con el registro de cola =$id_cola CHEQUEAR. ");
              $update="update cola set estado=91 where id=$id_cola";
              $rs=$conn->execute($update);
              return false;
              }
              else {
           //    loguea("Error en alta de usuario en zoneedit. usuario:$userzone");
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
               mail("soporte@nombremania.com","error en cambio de estado","Se produjo un error en zoneedit con el registro de cola =$id_cola CHEQUEAR. ");
                }
               return true;
               }
}

// JOSE (09-03-2010): alta en hexonet
function alta_hexonet($id_solicitud,$id_cola,$dominio,$intento)
{
	/**
	
	JOSE (11-03-2010)
	
	Funcion de alta en hexonet.
	Casi igual que hexonet, pero variando las DNS y el archivo a incluir.
	
	**/
	global $conn;
	//return true;   /// quitar
	$sql="select id,nm_wf_wp,nm_mf,reg_type,period,domain, vencimiento, nm_registro_tipo, nm_preciototal from solicitados where id=$id_solicitud"; // se anade vencimiento
	$dom=$conn->execute($sql);
	$mailzone=$dom->fields["nm_mf"];
	$reg_type= $dom->fields["reg_type"];
	$period=$dom->fields["period"];
	$zona = $dom->fields["domain"];
	$tipo = $dom->fields["nm_registro_tipo"];
	$precio = $dom->fields["nm_preciototal"];
	$vencimiento = $dom->fields["vencimiento"];
	list($mail_desde,$mail_hasta) = explode("/",$mailzone);
	$wfzone=trim($dom->fields["nm_wf_wp"]);
	
	if($dom==false)
	{
		loguear("error en la recuperacion de los datos de la zona para la creacion");
		return false;
	}
		include_once("hexonet.php"); // anadido
		$userzone='nombremania.com'; // anadido .. siempre es nombremania, que es el que crea las zonas
		$dns=agrega_zona($userzone,$dominio);
		if($dns["resultado"] === false){
			// si devuelve falso presumiblemente es porque la zona ya esta creada.
			loguear("zona ya estaba dada de alta");
			$bien_zona=true;
		} else {
			// agrega zona es estatica devuelve siempre ns1.nombr.... no hace falta ver los resultados
			loguear("zona dada de alta pero sin dns ");
			//asigno dns por defautl
			$sql="update cola set dns1='ns1.nombremania.net', dns2='ns2.nombremania.net', dns3='ns3.nombremania.net' where id = $id_cola"; // meter tercera dns // es de suponer que ya lo mete en alta_opensrs.
			$dnss=$conn->execute($sql);
			if ($dnss==false){
			loguear("Error en la grabacion de dns cola dns1=".$dns["dns1"]." dns2=".$dns["dns2"].".");
			}
			if ($mailzone==""){
			loguear("Mailforward no creado por falta de direccion de destino");
			$bien_zona=true;
			} 
			else {
			  // crear mailforward
				if (agregar_registro($dominio,"MF","dnsto=$mail_desde&forward=$mail_hasta",$userzone)){
				loguear("mail forward creado con exito $mailzone\n");
				}
				else {
				loguear("error al crear el mailforward\n");
				$bien_zona=true;
				}
			}
			if($wfzone!=''){
				if ($wfzone<>"parking"){
						if ($wfzone=="regalo"){
									$id_regalo=base64_encode("nm-" . $id_solicitud);
									$wfzone=urlencode("http://www.regaleundominio.com/regalo/index.php?ID=$id_regalo");
									if (agregar_wf($dominio,"",$wfzone,$userzone)){
										loguear("Web forward de regalo agregado a zona $dominio\n");
									}							
						} elseif (eregi("^http://",$wfzone)){
						   // cambiado el wf a www
							if (agregar_wf($dominio,"www",$wfzone,$userzone)){
									loguear("web forward agregado a zona $dominio\n");
									$bien_zona=true;
							 } else {
								   loguear("  !!! Error al crear web forward en zona $dominio\n");
							 }
						} else {
								loguear("El webforward no empieza con http://");
								$bien_zona=false;
								}
			   } else {
						// agrega un web parking default
					   if (agregar_registro($dominio,"WP","dnsfrom=www&dnsto=Default",$userzone)){
							 loguear("web parking creado con exito zona = $dominio");
							 $bien_zona=true;
					   } else {
							 loguear("!!! Error en la creacion del web parking $dominio ");
					   }
			   }
			}
		}
			
         if (!$bien_zona){
              if ($intento>8){
              mail("soporte@nombremania.com","ERROR en hexonet","se produjo un error en hexonet con el registro de cola =$id_cola CHEQUEAR. ");
              $update="update cola set estado=91 where id=$id_cola";
              $rs=$conn->execute($update);
              return false;
              }
              else {
           //    loguea("Error en alta de usuario en zoneedit. usuario:$userzone");
               $sql="update cola set intentos=intentos+1 where id=$id_cola";
               $rs=$conn->execute($sql);
               return false;
               }
            }
            else {
              /*
                 cambiar en caso de solo venta de OPCIONES PRO
               */
			   // pasa a estado 100
               //$sql="update cola set estado=2 where id=$id_cola"; // cambiar numero de cola
			   $sql="update cola set estado=100 where id=$id_cola";
               $rs=$conn->execute($sql);
            if ($rs===false ){
               mail("soporte@nombremania.com","error en cambio de estado","Se produjo un error en zoneedit con el registro de cola =$id_cola CHEQUEAR. ");
                }
               return true;
               }
}

// JOSE (17-03-2010): borrado de zona
function borrado_zona($id_solicitud,$id_cola,$dominio,$intento)
{
	/**
	
	JOSE (17-03-2010)
	
	Funcion creada para borrar un registro de zona
	que ya este con fecha caducada. En caso de que
	no haya mas registros de ese dominio, lo borraria
	en hexonet.
	
	**/
	global $conn;
	//return true;   /// quitar
	$sql="select id,nm_wf_wp,nm_mf,reg_type,period,domain, vencimiento, nm_registro_tipo, nm_preciototal from solicitados where id=$id_solicitud"; // se anade vencimiento
	$dom=$conn->execute($sql);
	$mailzone=$dom->fields["nm_mf"];
	$reg_type= $dom->fields["reg_type"];
	$period=$dom->fields["period"];
	$zona = $dom->fields["domain"];
	$tipo = $dom->fields["nm_registro_tipo"];
	$precio = $dom->fields["nm_preciototal"];
	$vencimiento = $dom->fields["vencimiento"];
	list($mail_desde,$mail_hasta) = explode("/",$mailzone);
	$wfzone=trim($dom->fields["nm_wf_wp"]);
	$sql="select * from zonas where domain = '$dominio' and id_solicitud <> '$id_solicitud'";
	$rs2=$conn->execute($sql);
	if($rs2===false)
	{
		include_once "hexonet.php";
		$ok=borrar_zona('nombremania.com', $dominio);
		if(!$ok)
		{
			if($intento>4)
			{
				loguear("error al eliminar zona de hexonet");
				mail("soporte@nombremania.com","ERROR en HEXONET","se produjo un error con HEXONET al borrar el registro de cola =$id_cola CHEQUEAR. ");
				$update="update cola set estado=94 where id=$id_cola";
				$rs_94=$conn->execute($update);
				return false;
			}
			else
			{
				$sql="update cola set intentos=intentos+1 where id=$id_cola";
        		loguear("error al eliminar zona de hexonet");
        		$conn->execute($sql);
				return false;
			}
		}
	}
		
	$sql="insert into zonas_hist select * from zonas where id_solicitud = '$id_solicitud'";
	$rs3=$conn->execute($sql);
	if($rs3===false)
	{
		loguear("error al insertar en historica la orden $id_solicitud");
		if($intento>4)
		{
			mail("soporte@nombremania.com","ERROR en tabla zonas","se produjo un error al insertar en la tabla historica de zonas el registro de cola =$id_cola CHEQUEAR. ");
			$update="update cola set estado=94 where id=$id_cola";
			$rs_94=$conn->execute($update);
			return false;
		}
		else
		{
			$sql="update cola set intentos=intentos+1 where id=$id_cola";
			$conn->execute($sql);
        	return false;
		}
	}
	else
	{
		$sql="delete from zonas where id_solicitud = '$id_solicitud'";
		$rs4=$conn->execute($sql);
		if($rs4===false)
		{
			loguear("error al eliminar de la tabla zonas la solicitud $id_solicitud");
			if($intento>4)
			{
				mail("soporte@nombremania.com","ERROR en tabla zonas","se produjo un error al borrarcon HEXONET al borrar el registro de cola =$id_cola CHEQUEAR. ");
				$update="update cola set estado=94 where id=$id_cola";
				$rs_94=$conn->execute($update);
				return false;
			}
			else
			{
				$sql="update cola set intentos=intentos+1 where id=$id_cola";
				$conn->execute($sql);
				return false;
			}
		}
	}
	
	$sql="update cola set estado=100 where id=$id_cola";
    $rs7=$conn->execute($sql);
    if ($rs7===false ){
               mail("soporte@nombremania.com","error en cambio de estado","Se produjo un error al cambiar a estado 100 con el registro de cola =$id_cola CHEQUEAR. ");
                }
	return true;
}

/*
Toma todos los datos de la cola y los reparte en las diferentes tablas segun el tipo
*/
function a_registrados($id_cola,$id_solicitud,$dominio)
{
	global $conn;
	$conn->debug=0;
	$debug=0;
	if($debug)$debug_string="comienza debug en a_registrados.<br/>";
	$sql="select * from solicitados where id=$id_solicitud";
	$rs=$conn->execute($sql);
	if($debug)$debug_string.="$sql <br/>";
	if($rs===false)
	{
		loguear("Error en la grabacion en registrados");
		return false;
	}
	$datos=$rs->fields;
	$reg_username=addslashes($datos["reg_username"]);
	$affiliate_id=addslashes($datos["affiliate_id"]);
	$reg_password=addslashes($datos["reg_password"]);
	$reg_type=addslashes($datos["reg_type"]);
	$period=addslashes($datos["period"]);
	$billing_first_name=addslashes($datos["billing_first_name"]);
	$billing_last_name=addslashes($datos["billing_last_name"]);
	$billing_email=addslashes($datos["billing_email"]);
	$billing_phone=addslashes($datos["billing_phone"]);
	$regalo=$datos["nm_wf_wp"]; // regalo
	$tipo=$datos["nm_registro_tipo"];
	$precio=$datos["nm_preciototal"];
	$id_solicitud=$datos["id"];
	$id_registrador=$datos["id_registrador"];
	$cod_aprob=addslashes($datos["nm_cod_aprob"]);
	//$aux=split(":",$datos["resultado_opensrs"]);
	$codigo_opensrs=$datos["nm_resultado_opensrs"];
	$dominios=$datos["domain"]; //guardamos todos los dominios
	$affiliate_id=$datos["affiliate_id"];
	
	

	// verifica que no este repetido el id en operaciones
	$sql="select id from operaciones where id_solicitud = $id_solicitud";
	if($debug)$debug_string.="$sql <br/>";
	$aux = $conn->execute($sql);
	$fecha=time();
	if($aux <> false and $conn->affected_rows()==0)
	{
		//$rs2=$conn->execute("insert into registrados (dominio,)");
		if($affiliate_id<>"" and $affiliate_id > 100)
		{
			include_once("comisiones.php");
			$porcentaje_comision = calcula_comision($affiliate_id);
			$comision = $precio * ($porcentaje_comision / 100); 
		}
		else
		{
			$porcentaje_comision=0;
			$comision=0;
		}		 
		$sql="insert into operaciones (fecha,affiliate_id,reg_username,reg_password,reg_type,period,domain,owner_first_name,owner_last_name,owner_email,owner_phone,tipo,codigo_opensrs,id_solicitud,precio,cod_aprob,id_registrador,comision,porc_comision)
            values
          ('$fecha','$affiliate_id','$reg_username','$reg_password','$reg_type','$period','$dominios','$billing_first_name','$billing_last_name','$billing_email','$billing_phone','$tipo','$codigo_opensrs','$id_solicitud','$precio','$cod_aprob','$id_registrador',$comision,$porcentaje_comision)";
		if($debug)$debug_string.="$sql <br/>";
		$operacion=$conn->execute("$sql");
		$id_operacion=$conn->insert_id();
	}
	else
	{
		$operacion=true;
		$id_operacion=$aux->fields["id"];
	}
	
	if($operacion === false )
	{
		loguear("Error en la grabacion  en registrados dominio: $dominio , id_solicitud=$id_solicitud , id_cola=$id_cola".mysql_error());
		if($debug)mail("jose@alsur.es","debug",$debug_string,"Content-Type: text/html");
		return false;
	}
	else
	{
		
			//if($reg_type<>"renew")
			if($reg_type<>"renew" and $reg_type<>"renew+upgrade" and $reg_type<>"zona_change")
			{
		
			// insert into dominios
			// $precio=calculaprecio();   //agregar el calculo de precio
			$precio=20;
			$sql="insert into dominios (fecha,id_solicitud,id_operacion,dominio,period,precio,cod_opensrs,affiliate_id)
		values ($fecha,$id_solicitud,$id_operacion,'$dominio',$period,$precio,'$cod_opensrs','$affiliate_id')";
		if($debug)$debug_string.="$sql <br/>";
			$rs=$conn->execute($sql);
			}
			
			if(eregi("^PRO",$tipo))
			{
				$es_pro=true;
				$tipo= strtoupper($tipo);
				if($tipo=="PRO1")
				{
					$emails=0 ; $redirecciones=0;
				}
				elseif($tipo=="PRO2")
				{
					$emails=5;$redirecciones=5;
				}
				elseif($tipo=="PRO3")
				{
					$emails=99999 ; $redirecciones=99999;
				}
				$proveedor='hexonet';
				//$desde=date("Y-m-d");
				
				/**
				*
				* JOSE (14/03/2012)
				* Si es renovacion: desde es fecha de vencimiento y hasta es vencimiento+periodo
				* Si es upgrade: desde es hoy y hasta es vencimiento+periodo
				* Si es nuevo registro: desde es hoy y hasta es hoy+periodo
				*
				*/
				
				
				
				if($reg_type=="renew"){
					$desde = $datos['vencimiento'];
					$vencimiento = date('Y-m-d', strtotime("+$period year", strtotime($datos['vencimiento'])));
				} elseif($reg_type == "renew+upgrade"){
					$desde = date('Y-m-d');
					$vencimiento = date('Y-m-d', strtotime("+$period year", strtotime($datos['vencimiento'])));
				} elseif($reg_type == "zona_change"){
					$desde = date('Y-m-d');
					$vencimiento = $datos['vencimiento'];
				} else {
					$desde = date('Y-m-d');
					$vencimiento = date('Y-m-d', strtotime("+$period year"));
				}
				$sql_zona="insert into zonas (fecha,id_solicitud,id_operacion,proveedor, desde, dominio,hasta,period,emails,redirecciones,tipo,precio)
			values ($fecha,$id_solicitud,$id_operacion,'$proveedor', '$desde','$dominio','$vencimiento', $period,$emails,$redirecciones,'$tipo',$precio)"; // esta sql esta bien porque ocurre solo cuando es nueva. // ya ocurre siempre
				if($debug)$debug_string.="$sql <br/>";
				$rs=$conn->execute($sql_zona);
			}
			
			if($es_pro) 
			{
				$updcola=$conn->execute("update cola set estado=40 where id=$id_cola");
				if($updcola==false)
				{
					loguear("Error al pasar el id $id_cola de la cola a estado 40");
					//$ok=false;
				}
			}
			else
			{
				$updcola=$conn->execute("update cola set estado=100 where id=$id_cola");
				if($updcola)
				{
					$sql_para_email="select count(*) as estados from cola where id_solicitud = $id_solicitud and estado<90";
					if($debug)$debug_string.="$sql <br/>";
					$contar = $conn->execute($sql_para_email);
					if($contar and $contar->fields["estados"]==0)
					{
						//envia mail solo cuando todos los dominios han terminado
						if($regalo=="regalo")
						{
							//enviar_mail_regalo($id_solicitud);
							//alta_email_regalo
							$sql_fecha_regalo="select unix_timestamp(fecha_email) as fecha_unix from regalo where id_solicitud=$id_solicitud";
							if($debug)$debug_string.="$sql <br/>";
							$rs_regalo=$conn->execute($sql_fecha_regalo);
							if($rs_regalo === false)
							{
								mail("soporte@nombremania.com", "error en alta de cola email regalo","");
							}
							elseif($conn->affected_rows()==0)
							{
								mail("soporte@nombremania.com", "error en alta de cola email regalo","ningulo encontrado con id_solicitud= $id_solicitud");
							}
							else
							{													 
								$procesar_fecha=$rs_regalo->fields["fecha_unix"];
								$sql_regalo="insert into cola (id_solicitud,procesar_fecha,estado) values ($id_solicitud,$procesar_fecha,10) ";
								$rs_regalo=$conn->execute($sql_regalo);
								if($rs_regalo === false)
								{
									mail("soporte@nombremania.com", "error en alta de cola email regalo","");
								}
							}
						}
						else
						{
							//enviar_mail_cliente($id_solicitud);
						}					
					}
					if($debug)mail("jose@alsur.es","debug",$debug_string,"Content-Type: text/html");
					return true;
				}
				else
				{
					loguear("No se pudo actualizar la cola de proceso a codigo 100\n id_cola=$id_cola .");
				}
			}
	}
	if($debug)mail("jose@alsur.es","debug",$debug_string,"Content-Type: text/html");
}

function limpia($datos)
{
	while(list($k,$v)=each($datos))
	{
		if(is_int($k)) unset($datos[$k]);
	}
	return $datos;
}

function alta_opensrs($id_solicitud,$id_cola,$dominio,$intentos)
{
	global $conn;
//	$conn->debug=1;
	//$debug=true ; //para mostrar o no los mensajes de OPENSRS
	$bien_dominio=false;

	if(!isset($id_solicitud))
	{
		loguear("en el alta de opensrs Falta el identificador de la cola");
		$bien_dominio=false;
	}
	//recepta el identificador de la base de datos y procesa las peticiones de alta en opensrs y zoneedit si tiene opcion pro
	$rs=$conn->execute("select *  from solicitados where id=$id_solicitud");
	//$cola=$conn->execute("select * from cola where id=$id_cola"); // sin uso ya

	//if($rs===false or $cola===false)
	if($rs===false)
	{
		loguear("Error en base de datos recuperacion de datos de la cola id_cola = $id_cola");
		exit;
	}
	require_once("osrsh/openSRS.php");
	// @todo incluir renew+upgrade
	if($rs->fields["reg_type"] == "renew" or $rs->fields["reg_type"] == "renew+upgrade")
	{

		//$O = new opensrs("LIVE");
		include_once("conf.inc.php");
		$O = new opensrs($_test_or_live);
		require_once("race.php");
		include_once("hachelib.php");
		
		if($rs->fields['reg_type']=='renew+upgrade') $rs->fields['nm_expira']=date('Y',($rs->fields['vencimiento']) ? $rs->fields['vencimiento'] : '');
		

		$cmd=array(
		"action"=>"renew",
		"object"=>"domain",
		"attributes"=>array("domain" =>$rs->fields["domain"],
		"currentexpirationyear"=>( $rs->fields["nm_expira"]+ 0 )   ,
		"period"=>$rs->fields["period"],
        "auto_renew"=>0)
		);
		var_Crudas($cmd);
	}
	else
	{
		//preparo los datos a enviar
		if(!chequear_tv($dominio))
		{
			loguear("dominio $dominio en subasta . CHEQUEAR con www.tv !!!!");
			return false;
		}
		if(strtolower($rs->fields["nm_registro_tipo"])=="estandar")
		{
			$custom_nameservers=1;  //lo pone el cliente
			$custom_tech_contact=1;  // lo pone el cliente
		}
		else  // cualquier tipo de PRO
		{
			$rs->fields["nm_registro_tipo"] = "PRO";
			$custom_nameservers=1; //los ponemos nosotros de zoneedit esta en uno porque lo grabamos en la base de datos
			$custom_tech_contact=0;   //lo pone opensrs de la base de datos
			//$dns1=$cola->fields["dns1"]; //saca las dns de la cola de proceso grabadas alli por zoneedit()
			//$dns2=$cola->fields["dns2"];
			$dns1='ns1.nombremania.net'; // son fijas
			$dns2='ns2.nombremania.net';
			$dns3='ns3.nombremania.net'; // anadido
		}
		if($datos["reg_type"]=="transfer")
		{
			$custom_nameservers=0;
		}

		$datos=limpia($rs->fields);
		extract($datos);
		$campos=array("first_name","last_name",
		"phone","fax","email","org_name","address1","adress2","address3",
		"city","state","country","postal_code","email"); // falta la URL no requerida
		
		$owner=array();$billing=array();$tech=array();

		foreach($campos as $campo)	//registra cada registro con el mismo nombre
		{
			$owner[$campo]=($datos["owner_$campo"]==NULL) ? "":  $datos["owner_$campo"];
			if($custom_tech_contact== 1) $tech[$campo]= ($datos["tech_$campo"]==NULL) ? "": $datos["tech_$campo"];
			$billing[$campo]=($datos["billing_$campo"]==NULL) ? "": $datos["billing_$campo"];
		}
		// genera las entradas de dns y asigna el orden (importante)
		if($custom_nameservers==1)
		{
			$dns=array();
			for($i=1; $i<=6;$i++)
			{
				$aux="fqdn$i";
				if(isset($$aux) and $$aux<>"")
				{
					$dns[]= array("name"=>$$aux,"sortorder"=>$i);
				}
			}
		}
		if(eregi("^PRO",$nm_registro_tipo))
		{
			$dns=array(array("name"=>$dns1,"sortorder"=>1),
			array("name"=>$dns2,"sortorder"=>2),
			array("name"=>$dns3,"sortorder"=>3)); // se anade la tercera dns
		}


// LLAMADA A OPENSRS
		include("conf.inc.php");
		require_once("osrsh/openSRS.php");
		$O = new opensrs($_test_or_live);
		//require_once("race.php");
		include_once("hachelib.php");
		include_once($server_path."/registro/func_registro.inc.php");

		//$RACE = new Net_RACE("UTF-8");
		list($n,$x)=explode(".",$dominio);

$encoding_type="";
$tmp=multilingue($n);
if($tmp["valor"]==true)
{
	if($x=="org")$dominio=$tmp["race"].".".$x;
	else $dominio=$tmp["punycode"].".".$x;
	$encoding_type="spa";
}

if($x=='org' || $x=='info')
{
	$owner['first_name']=htmlentities(utf8_decode($owner['first_name']));
	$owner['last_name']=htmlentities(utf8_decode($owner['last_name']));
	$owner['org_name']=htmlentities(utf8_decode($owner['org_name']));
	$owner['city']=htmlentities(utf8_decode($owner['city']));
	$owner['state']=htmlentities(utf8_decode($owner['state']));
	
	$billing['first_name']=htmlentities(utf8_decode($billing['first_name']));
	$billing['last_name']=htmlentities(utf8_decode($billing['last_name']));
	$billing['org_name']=htmlentities(utf8_decode($billing['org_name']));
	$billing['city']=htmlentities(utf8_decode($billing['city']));
	$billing['state']=htmlentities(utf8_decode($billing['state']));
	
	$tech['first_name']=htmlentities(utf8_decode($tech['first_name']));
	$tech['last_name']=htmlentities(utf8_decode($tech['last_name']));
	$tech['org_name']=htmlentities(utf8_decode($tech['org_name']));
	$tech['city']=htmlentities(utf8_decode($tech['city']));
	$tech['state']=htmlentities(utf8_decode($tech['state']));
}

$cmd=array(
	"action"=>"sw_register",
	"object"=>"domain",
	"attributes"=>array(
		"domain"=>$dominio,
		"encoding_type"=>"$encoding_type",
		"reg_type"=>$datos["reg_type"],
		"reg_username"=>$datos["reg_username"],
		//"reg_password"=>$datos["reg_password"]."2008",
		"reg_password"=>$datos["reg_password"],
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
if($datos["reg_domain"])
{
	$cmd["attributes"]["reg_domain"]=$reg_domain;
	$cmd["attributes"]["link_domains"]=1;
}
else
{
	$aux_domi=explode("**",$datos["domain"]);
	if(count($aux_domi)>1)
	{
		if($dominio<>$aux_domi[0])
		{
			$cmd["attributes"]["reg_domain"]=$aux_domi[0];
			$cmd["attributes"]["link_domains"]=1;
		}
	}
	else
	{
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
}
 //llamar al send_cmd para enviar los datos a opensrs.
  
$result=$O->send_cmd($cmd);

/*$fd=fopen('/logs/nm/nm.xml','w');
fwrite($fd,print_r($result,1));
fwrite($fd,print_r($O,1));
fclose($fd);*/

ob_start(); 
var_dump($cmd);
var_dump($result); 
$salida = ob_get_contents(); 
ob_end_clean();
echo $salida;

loguear("\nResultado del envio a OPENSRS \n\n". $salida );
//loguear(print_r($O,1));


if($result["response_code"]==200)	  //registro o traslado completo
{
	$aciertos++;
	$bien_dominio=true;
	$resultado=$result["response_code"]. " -- ". $result["response_text"];
}
else
{
	$bien_dominio=false;
	$resultado=$result["response_code"]. " -- ". $result["response_text"];
}
if($bien_dominio)
{
	$resulta_osrs = $result["attributes"]["id"];
	$sql1="update solicitados set nm_resultado_opensrs=concat(nm_resultado_opensrs,'\n$dominio - $resulta_osrs') where id=$id_solicitud";
	//$sql="update cola set estado=3 where id=$id_cola";
	$sql="update cola set estado=30 where id=$id_cola"; // cambia a estado 30 (pasa a registrados)
	$rs=$conn->execute($sql);
	$rs1=$conn->execute($sql1);
	if($rs==false or $rs1==false)
	{
		loguear("Error en grabacion de la base de datos, proceso graba en dominios ". mysql_error() );
	}
	$bien_dominio=false;
	return true;
}
else
{
 	if($intentos>4)
	{
		mail("soporte@nombremania.com","ERROR en OPENSRS","se produjo un error en OPENSRS con el registro de cola =$id_cola CHEQUEAR. ");
		$update="update cola set estado=90 where id=$id_cola";
		$rs=$conn->execute($update);
		return false;
	}
	else
	{
		loguear("ERROR en la llamada a OPENSRS - id=$id_solicitud dominio=$dominio, resultado=$resultado");
		$sql="update cola set intentos=intentos+1 where id=$id_cola";
		$rs2=$conn->execute($sql);
		return false;
	}
}
}

function enviar_mail_cliente($id_solicitud)
{
	global $conn;
	return true;
	include_once("enviar_email.php");
	$rs=$conn->execute("select  * from solicitados where id= $id_solicitud");
	$ruta=$_SERVER['DOCUMENT_ROOT'];
	if($rs===false )
	{
		loguear("error al enviar el mail ".mysql_error());											 
		return false;
	}
	$dat=array(); //datos de la factura al propietario

	$rs_fact= $conn->execute("select * from facturas where id_solicitud= $id_solicitud");
	if($rs_fact===false or $conn->affected_rows()<1)
	{
		$factura=false;	 											 
	}
	else
	{
		$factura=true;
		$factura_owner=true;
		$link_factura = "<a href=http://www.nombremania.com/clientes/pago/i_factura.php?id="
									.base64_encode($rs_fact->fields["id"]."-nm").">Factura</a>";
		if($rs->fields["owner_email"] != $rs->fields["billing_email"])
		{
			$factura_owner=false;
			$df=array();
			$df["first_name"]=$rs->fields["billing_first_name"];
			$df["last_name"]=$rs->fields["billing_last_name"];
			$df["email_cliente"]=$rs->fields["billing_email"];
			$df["link_factura"]=$link_factura;
			if(!envioemail($ruta."/procesos/mails_a_enviar/mail_solo_factura.txt",$df))
			{
				loguear("Error en el envio del Mail , id  de solicitud : $id_solicitud");
				exit;
			}
		}
		else
		{
			 // se incluye el link a facturas en el mail del propietario
			 $dat["link_factura"]=$link_factura;
		} 
	}
 

	$dat["first_name"]=$rs->fields["owner_first_name"];
	$dat["last_name"]=$rs->fields["owner_last_name"];
	$dat["email_cliente"]=$rs->fields["owner_email"];

	if($dat["email_cliente"]=="")
	{
		loguear("error al enviar el mail a cliente id_solicitud = $id_solicitud , falta el emal del cliente");
		exit;
    }

	$dat["domain"]=$rs->fields["nm_resultado_opensrs"];

	if($rs->fields["reg_type"] == "transfer")
	{
	   $dat["transfer"] = "Recuerde que si su solicitud es de traslado/s de 
dominio/s, deberá confirmar esta acción con el 
email que recibira el titular en el registrador actual. Para mas informacion consulte lasinstrucciones de nuestra pagina para los traslados de dominios, http://www.nombremania.com/ayuda/traslado_dominios.html ";
	}
	else
	{
		$dat["transfer"]="";
	}

	if(!envioemail($ruta."/procesos/mails_a_enviar/mail_aceptacion_registros.txt",$dat))
	{
		loguear("Error en el envio del Mail , id  de solicitud : $id_solicitud");
		exit;
	}
}

function enviar_mail_regalo($id_solicitud)
{
	global $conn;
	loguear("enviando mail de regalo a regalado y regalante");
	$sql_regalo= "select * from regalo where id_solicitud=$id_solicitud";
	$rs=$conn->execute($sql_regalo);
	if($rs===false or $conn->affected_rows()==0)
	{
		loguear("Error en la recuperacion del email para el regalo , chequear : id = $id_solicitud");
		return false;
	}
	if($rs->fields["enviar_email"]<>"")
	{ 
		$dat["email_regalado"]=$rs->fields["enviar_email"];
		$dat["email_regalante"]=$rs->fields["email_regalante"];
		$dat["nombre_regalado"]=$rs->fields["nombre_regalado"];
		$dat["regalante"]=$rs->fields["nombre_regalante"];
		$dat["fecha"]=date("d-m-Y");
		$id_regalo=base64_encode("nm-" . $id_solicitud);
		$rs_dom=$conn->execute("select domain from operaciones where id_solicitud=$id_solicitud");
		$dat["dominio"]=$rs_dom->fields["domain"];
		$dat["destino"]="http://www.regaleundominio.com/regalo/index.php?ID=$id_regalo";
		// codigo para enviar el email   ACA !!!

		$ruta=$_SERVER['DOCUMENT_ROOT'];
		include_once("enviar_email.php");
		if(!envioemail($ruta."/procesos/mails_a_enviar/mail_regalo.txt",$dat))
		{
		    loguear("Error en el envio del Mail de regalo a regalado");
		}

		if(!envioemail($ruta."/procesos/mails_a_enviar/mail_regalo_regalante.txt",$dat))
		{
	    	loguear("Error en el envio del Mail de regalo a regalante");
		}
		$update=$conn->execute("update cola set estado=100 where id_solicitud=$id_solicitud");
		return $update;
	}
}

function chequear_tv($dominio)
{
	list($n,$e)=explode('.',$dominio);
	if(strtoupper($e)<>'TV')
	{
		return true; // no es tv todo ok
	}
	include("conf.inc.php");
	$O=new openSRS($_test_or_live); // creo el objeto opensrs
	$cmd = array(
	"action" => "lookup",
	"object" => "domain",
	"registrant_ip" => "111.121.121.121",
	"attributes" => array(
		"domain" => $dominio,
		)
	);
	$result = $O->send_cmd($cmd);
	var_crudas($result);									
	if($result['attributes']['status']=='available')
	{
		return true;
	}
	else
	{
		return false;
	}
}

function procesa_expired()
{
	global $conn;

	$sql="select * from cola where estado = 77 and FROM_UNIXTIME(procesar_fecha)<NOW();";
	$rs=$conn->execute($sql);

	if($rs==false or $conn->Affected_Rows()==0)exit;
  
	$ok=0;
	while(!$rs->EOF)
	{
		$id_cola=$rs->fields['id'];
		$dominio=$rs->fields['dominio'];

		$fp=fsockopen('rr-n1-tor.opensrs.net',51000,$errno,$errstr,30);
		$result='';
	
		if(!$fp)
		{
			//loguear("$errstr ($errno)");
			exit;
		}
		else
		{
			$out="check_domain $dominio";
			fwrite($fp,$out);
			while(!feof($fp))
			{
				$result.=fgets($fp,256);
			}
			fclose($fp);
		}
		
		//mail('joseluis@alsur.es','Cola Expired System Debug',"$dominio -> $result");
		//trigger_error('En procesator.php, después del mail.');
		
		//echo "$dominio: $result<br/>";
		//lmc 22-09-2010 activo log para verlo en la cola
		loguear("$dominio: $result");
		
		if(substr($result,0,3)=='210')
		{
			$sql="update cola set estado=20 where id=$id_cola;";
			$conn->execute($sql);
			$ok=1;
			mail('soporte@nombremania.com','Registro expired',"El dominio $dominio, que estaba a punto de expirar, ha sido registrado con éxito.");
		}
		$sql="select fecha from cola where fecha > now() - interval 47 day and id=$id_cola";
		$tmp_rs=$conn->execute($sql);
		if($tmp_rs->_numOfRows<1)
		{
			$sql="update cola set estado=99 where id=$id_cola;";
			$conn->execute($sql);
			mail('soporte@nombremania.com','Registro expired',"El dominio $dominio debe haber sido renovado o reregistrado, ya que no se ha conseguido registrarlo en el plazo de tiempo especificado.");
		}
		//if($ok)procesa_cola();
		$rs->movenext();
	}
	exit;
}

// if (isset($arma)) pagator("manual",224,"xxxxjjj"); asi se llama a cola
if(isset($procesa_cola))
{
	echo "<html><head><meta http-equiv=\"Content-type\" content=\"text/html; charset=UTF-8\"></head><body>
	<h3>Verifique log de trabajo y cola de procesos</h3>
	<a href=\"/nmgestion/ver_log.php\">Ver log de procesamiento</a><br>
	<a href=\"/nmgestion/ver_log.php\">Ver cola de procesamiento</a><br>";
	//echo "Testing Mode";
	procesa_cola();
	echo "</body></html>";
}
?>
