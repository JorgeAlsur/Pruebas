<?
//include('zoneedit.php');
//include('hexonet.php'); // anadido
include('./defaultzona.php');
include('./funciones_zonas.php');
include('class.templateh.php');
$aux=$k;
// comentado en local para pruebas

if(!isset($_COOKIE['admzona']))
{
	header("Location: /nm/error.php?error=Cookie+vencida");
	exit;
}

$dominio=$_COOKIE['admzona'];
//if($dominio=='') $dominio='todopc.com'; #comentado para pruebas
$usuario=$_COOKIE['admzona'];

include("hachelib.php");
if($tarea=="" or !isset($tarea))
{
	include("basededatos.php");
	//$conn->debug=true;
	$sql="select * from zonas where dominio = \"$dominio\" and tipo like \"PRO%\"  and hasta>=CURDATE()";
	//echo $sql;exit;
	$res=$conn->execute($sql);
	if($conn->Affected_Rows()>0)
	{
		$usuario=$res->Fields("reg_username");
		$clave=$res->Fields("reg_password");
		$total_mf=$res->Fields("emails");  ;
		$total_wf=$res->Fields("redirecciones");
		$proveedor=$res->fields["proveedor"];
		//$proveedor='hexonet';
		//echo $proveedor;exit;
		if($proveedor=='hexonet') include('hexonet.php'); 
		elseif($proveedor=='zoneedit') include('zoneedit.php');
		else include('hexonet.php');
		$registros1=registros_zona($usuario,$dominio);
		if(isset($error) and $error="indeterminado")
		{
			$error_ip="Error indeterminado si estima que es un error del sistema pongase en contacto con soporte@nombremania.com";
		}
		
		mostrar_datos();
		//formulario();
		exit;
	}        else
        {
                $a_donde="/nm/error.php?error=SERVICIOS NO CONTRATADOS" ;
		header("Location: $a_donde");
		exit;
        }

}
else
{
	$error=array();
	//var_crudas($_POST);
	//borrar 
	//print_r($borrar_ip);exit;
	if(isset($borrar_ip) or isset($borrar_mx))
	{ 
		$selectas=array();
		foreach($registros1 as $aux)
		{	
			/**
			
			JOSE (11-03-2010):
			Cambia in_array, ya que en zoneedit llegaba de
			la forma $tipo-$id y en hexonet directamente la
			cadena a borrar.
			
			**/
			if($proveedor=='zoneedit')
			{
				if( is_array($borrar_mx) and in_Array("MX-".$aux["id"],$borrar_mx))
				{
					if($aux["rank"]==0)
					{
						$rank="1&deg;";
					}
					else
					{
						$rank=($aux["rank"]/5)+1 . "&deg;";
					}
					$selectas[]="Servidor Mx $rank ".$aux["dnsfrom"].".$dominio ." ;
				}
				elseif(is_array($borrar_ip) and in_array($aux["id"],$borrar_ip))
				{
					$selectas[]="Registro DNS avanzado tipo \"".$aux["tipo"]."\" host: ".$aux["dnsfrom"]." con destino:".$aux["dnsto"];
				}
			}
			elseif($proveedor=='hexonet')
			{
				if( is_array($borrar_mx) && in_array($aux["rr"], $borrar_mx))
				{
					if($aux["rank"]==0)
					{
						$rank="1&deg;";
					}
					else
					{
						$rank=($aux["rank"]/5)+1 . "&deg;";
					}
					$selectas[]="Servidor Mx $rank ".$aux["dnsfrom"].".$dominio ." ;
				}
				elseif(is_array($borrar_ip) and in_array($aux["rr"],$borrar_ip))
				{
					$selectas[]="Registro DNS avanzado tipo \"".$aux["tipo"]."\" host: ".$aux["dnsfrom"]." con destino:".$aux["dnsto"];
				}
			}
		}
	}
	//var_crudas($selectas);

	if(!isset($confirmar))
	{
		//preparamos el texto para procesar plantilla de cambios.
		if(isset($selectas) and count($selectas)>0)
		{
			$texto_borrar_adv="Ud. ha solicitado borrar los siguientes registros avanzados:<ul><li>".
	 												implode("<li>\n",$selectas)."</ul>"; 
	 }
	 else $texto_borrar_adv="";

$desde=trim($desde);
$hasta=trim($hasta);	
if ($desde<>""){
	 		//validaciones de datos
		if ($tipo =="A"){
			 $desde =str_replace(".$dominio","",$desde);
			 if ($desde==""){
			 		$error[] ="Especifique un host v&aacute;lido para la entrada IP, tipo www.$dominio";
			 }
			 if (!ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $hasta)){
			 		$error[] ="El destino de una entrada tipo \"A\" debe ser siempre una IP, corrija este valor";
			 }
		
		if (count($error)>0){
		  $error_ip=implode("<br>",$error);
			mostrar_datos();
			exit;
		}
		else {
		      $dat=array();
					$desde =str_replace(".$dominio","",$desde);
				  $dat["desde"]=$desde;
					$dat["hasta"]=$hasta;
					$dat["tipo"]=$tipo;
					if ($desde=="@") {
						 $desde_text="";
						 }else {					
						 	$desde_text ="$desde.";
					} 
					$dat["a_agregar"]="Se agregar&aacute; un registro \"A\" $desde_text$dominio (origen)  hacia la IP $hasta.";					
					$confirmar=true;
		}
		}	elseif ($tipo=="CNAME"){
	    $desde =str_replace(".$dominio","",$desde);
			if ($desde==""){
				 $error[]="Especifique un host v&aacute;lido para la entrada CNAME";
			}
			if (ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $hasta)){
				 $error="el Destino no puede ser una IP debe ser una direccion de host V&aacute;lida";
			}
					if (count($error)>0){
							  $error_ip=implode("<br>",$error);
  							mostrar_datos();
								exit;
     		}
		     else {
		      $dat=array();
					$dat["desde"]=$desde;
					$dat["hasta"]=$hasta;
					$dat["tipo"]=$tipo;
				if ($desde=="@") {
						 $desde_text="";
						 }else {					
						 	$desde_text ="$desde.";
					} 
					$dat["a_agregar"]="Se agregar&aacute; una entrada CNAME para $desde_text$dominio hacia $hasta.";					
					$confirmar=true;
		}
	}
	elseif (stristr($tipo,"mx")){
	      //mx 
				$desde =str_replace(".$dominio","",$desde);
				if (ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $hasta)){
				 $error[]="El Destino no puede ser una IP debe ser una direccion de host V&aacute;lida, realice primero un alta de IP.";
				 }
			if (count($error)>0){
							  $error_ip=implode("<br>",$error);
  							mostrar_datos();
								exit;
     		}
		     else {
				 $rank=str_replace("mx","",$tipo);
				 	
				        if ($rank==0) {
									     $text_rank="1&deg;";
											    }
							    else{ $text_rank=($rank/5)+1 . "&deg;";   }

		      $dat=array();
					$dat["desde"]=$desde;
					$dat["hasta"]=$hasta;
					$dat["tipo"]=$tipo;
					if ($desde=="@") {
						 $desde_text="";
						 }else {					
						 	$desde_text ="$desde.";
					} 
					
					$dat["a_agregar"]="Se agregar&aacute; una entrada MX: el correo con destino $desde_text$dominio ser&aacute; gestionado por $hasta como MX $text_rank.";
					$confirmar=true;
		} 
	}
	else {
			 // ningun tipo seleccionado mostrar error y variables
			 $error_ip="Debe seleccionar al menos un tipo de registro a crear";
			 mostrar_datos();
			 exit;
	}
}
if ($desde == "" and $hasta<>""){
		 $error_ip="Debe seleccionar al menos un origen si desea referenciar al mismo dominio utilice la arroba";
		 mostrar_datos();
		 exit;
}
if ($desde<>"" or (isset($borrar_ip) and  count($borrar_ip)>0) or ( isset($borrar_mx) and  count($borrar_mx)>0)){   	
	//enviamos a la confirmacion
$t=new Template("templates","remove");
$t->set_file("template","cambios.html");
$t->set_var("DOMAIN_NAME",$dominio);
//limpio el bloque no
$t->set_var("NO","");
$t->set_var("a_borrar",$texto_borrar_adv);
$t->set_var("a_agregar",$dat["a_agregar"]);
$form ="<form action=procesa_adv.php method=post>\n
<input type=hidden name=\"tipo\" value=\"$tipo\">
<input type=hidden name=\"desde\" value=\"$desde\">
<input type=hidden name=\"hasta\" value=\"$hasta\">";
if ($rank<>"") $form.="<input type=hidden name=\"rank\" value=\"$rank\">";

//bloque de borrado
if (isset($borrar_ip) and is_array($borrar_ip)){
foreach($borrar_ip as $v){
    $form.="<input type=hidden name=borrar_ip[] value='$v'>\n";
}
}

//bloque de borrado
if (isset($borrar_mx) and is_array($borrar_mx)){
foreach($borrar_mx as $v){
    $form.="<input type=hidden name=borrar_mx[] value='$v'>\n";
}
}

// fin bloque borrado



$form.="\n<input type=submit name=confirmar value=\"  Aceptar los cambios \">";
$form.="\n<input type=submit name=descartar value=\"  Descartar los cambios \"></form>";

$t->set_var("formulario","$form");
$t->pparse("template");
}	
}
}

function mostrar_datos()
{
	global $PHP_SELF,$dominio,$k,$usuario,$tipos,$error_mx,$error_ip,$total_wf,$total_mf,$registros1,$tipos_avanzados, $proveedor;
	//global $PHP_SELF,$dominio,$k,$usuario,$tipos,$error_mx,$error_ip,$total_wf,$total_mf,$registros1,$tipos_avanzados;

	//$registros1=registros_zona($usuario,$dominio);
	//var_crudas($registros1);

	$wf_id=0;$mf_id=0;$wp_id=0;
	$a_mf=array();
	$a_wf=array();
	$a_wp=array();
	$a_ip=array();
	$a_mx=array();
	for($i=0;$i<count($registros1);$i++)
	{
		/**

			JOSE (11-03-2010):
			Modificado for por cambio a hexonet.
			Incluye en el array el parametro rr
			que contiene la cadena completa para
			borrar, ya que en hexonet es necesario
			la cadena completa.
		
		**/
		if($registros1[$i]["tipo"] =="MA")
		{
			$a_mf[]=array("desde"=>$registros1[$i]["dnsto"],
                   "hasta"=>$registros1[$i]["forward"],
                   "id"=>$registros1[$i]["id"]);

		}
		elseif ($registros1[$i]["tipo"] =="WP")
		{
			$desde=$registros1[$i]["dnsfrom"];
			if($desde=="")$desde="*";
			$hasta=$registros1[$i]["dnsto"];
			if(!$hasta)$hasta="default ";
			$a_wf[]=array("desde"=>$desde,
          "hasta"=>$hasta,
          "id"=>$registros1[$i]["id"],"tipo"=>"parking","type"=>$registros1[$i]["tipo"]);
		}
		elseif($registros1[$i]["tipo"] =="WF")
		{
			if($proveedor=='hexonet')
			{
			$a_wf[]=array("rr"=>$registros1[$i]["rr"],"desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["forward"],
        "id"=>$registros1[$i]["id"],"tipo"=>"forward","type"=>$registros1[$i]["tipo"],
        "disimulada"=>$registros1[$i]["disimulada"]);
			}
			elseif($proveedor=='zoneedit')
			{
				$a_wf[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["forward"],
        "id"=>$registros1[$i]["id"],"tipo"=>"forward","type"=>$registros1[$i]["tipo"],
        "disimulada"=>$registros1[$i]["disimulada"]);
			}
		}
		elseif($registros1[$i]["tipo"] =="MX")
		{
			if($proveedor=='hexonet')
			{
			$a_mx[]=array("rr"=>$registros1[$i]["rr"],"desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>"MX","type"=>$registros1[$i]["tipo"],
"rank"=>$registros1[$i]["rank"]);
			}
			elseif($proveedor=='zoneedit')
			{
				$a_mx[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>"MX","type"=>$registros1[$i]["tipo"],
"rank"=>$registros1[$i]["rank"]);
			}
		}
		elseif($registros1[$i]["tipo"] =="A")
		{
			if($proveedor=='hexonet')
			{
	        $a_ip[]=array("rr"=>$registros1[$i]["rr"], "desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>$registros1[$i]["tipo"],"type"=>$registros1[$i]["tipo"]);
			}
			elseif($proveedor=='zoneedit')
			{
				$a_ip[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>$registros1[$i]["tipo"],"type"=>$registros1[$i]["tipo"]);
			}
		}
		elseif($registros1[$i]["tipo"] =="CNAME")
		{
			if($proveedor=='hexonet')
			{
	        	$a_ip[]=array("rr"=>$registros1[$i]["rr"], "desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>"CNAME","type"=>$registros1[$i]["tipo"]);
			}
			elseif($proveedor=='zoneedit')
			{
				$a_ip[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>"CNAME","type"=>$registros1[$i]["tipo"]);
			}
		}

	}
	//var_crudas($a_mx);


	$t=new Template("templates","remove");
	$t->set_file("template","panel_avanzado_new.html");
$t->set_var("DOMAIN_NAME",$dominio);
//limpio el bloque no
$t->set_var("NO","");
if ($error_mf<>"" or $error_wf <>"") $t->set_var("texto","Se produjo un error en la solicitud. Verifique los errores.");
$t->set_var("error_ip",$error_ip);
$t->set_var("error_mx",$error_mx);

/*
  bloque de mx
*/

$mxs=0;

/**

	JOSE (11-03-2010):
	Modificados mx y ax para que al borrar tenga
	como parametro la cadena que se quiere borrar.

**/
foreach($a_mx as $mx){
    //var_crudas($mx);
	 if ($mx["desde"]<>"") $mx["desde"]=$mx["desde"].".";
    $t->set_var("desde_ip",$mx["desde"]);
    $t->set_var("hasta_ip",$mx["hasta"]);

    if ($mx["rank"]==0) {
    $rank="1&deg;";
   }
    else{ $rank=($mx["rank"]/5)+1 . "&deg;";
    }
//    $t->set_var("rank_mx",$rank);
    $t->set_var("tipo_ip",$mx["tipo"]." ".$rank);
    $mxs++;
    // $borrar="<input type=checkbox name=borrar_mx[] value=\"{$mx["type"]}-{$mx["id"]}\">";
	 $borrar="<input type=checkbox name=borrar_mx[] value=\"{$mx["rr"]}\">";
    $t->set_var("borrar_ip",$borrar);
    $t->parse("IP","IP",true);
}
/*if ($mxs>0){
 $t->parse("BORRAR_MX","BORRAR_IP",true);
} else {
$t->set_var("BORRAR_MX","");
}*/

	//agregar MX (se agrega siempre) por ahora


	//listado de ip

	foreach($a_ip as $ip)
	{
		$t->set_var("tipo_ip",$ip["tipo"]);
		//($ip["desde"] =="") ? $t->set_var("desde_ip",$ip["desde"]) :$t->set_var("desde_ip",$ip["desde"]."." ) ;
		if($ip["desde"]<>"") $ip["desde"]=$ip["desde"].".";
		$t->set_var("desde_ip",$ip["desde"]);
		$t->set_var("hasta_ip",$ip["hasta"]);
		//$t->set_var("borrar_ip","<input type=checkbox name=\"borrar_ip[]\" value=\"{$ip["id"]}\">");
		$t->set_var("borrar_ip","<input type=checkbox name=\"borrar_ip[]\" value=\"{$ip["rr"]}\">");
		$t->parse("IP","IP",true);
	}
	//if (count($a_ip)>0 OR $mxs>0){
	$t->parse("BORRAR_IP","BORRAR_IP",true);
	//}
	$t->set_var("CGI",$PHP_SELF);
	$desde=$GLOBALS["HTTP_POST_VARS"]["desde"];
	$hasta=$GLOBALS["HTTP_POST_VARS"]["hasta"];
	$tipo=$GLOBALS["HTTP_POST_VARS"]["hasta"];
	$t->set_Var("desde",$desde);
	$t->set_Var("hasta",$hasta);
	$t->set_Var("tipos",form_select($tipos_avanzados,"tipo",$tipo));

	$t->pparse("template");
}
?>
