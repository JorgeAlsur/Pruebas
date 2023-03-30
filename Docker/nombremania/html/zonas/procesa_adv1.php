<?
echo "1";exit;
include "zoneedit.php";
include "./defaultzona.php";
include "./funciones_zonas.php";
$aux=$k;

if (!isset($_COOKIE["admzona"])){
    header("Location: /error.php?error=Se ha vencido la sesion del sistema deber&aacute; ingresar nuevamente.");
    exit;
    }
$dominio=$_COOKIE["admzona"];
$usuario=$_COOKIE["admzona"];

include "hachelib.php";
$registros1=registros_zona($usuario,$dominio);
//var_crudas($registros1);
 
if (isset($borrar_ip) or isset($borrar_mx)){ 
$selectas=array();
foreach($registros1 as $aux){
				
         if ( is_array($borrar_mx) and in_Array("MX-".$aux["id"],$borrar_mx)){
				        if ($aux["rank"]==0) {
									     $rank="1&deg;";
											    }
							    else{ $rank=($aux["rank"]/5)+1 . "&deg;";   }
				    $selectas[]="Servidor Mx $rank ".$aux["dnsfrom"].".$dominio ." ;
				 }
				 
				 elseif (is_array($borrar_ip) and in_array($aux["id"],$borrar_ip)){
				   $selectas[]="Registro DNS avanzado \"".$aux["tipo"]."\" desde: ".$aux["dnsfrom"]." hasta:".$aux["dnsto"];
				 }
}
}
//var_crudas($selectas);

if (!isset($confirmar)){
  //preparamos el texto para procesar plantilla de cambios.
	 	if (isset($selectas) and count($selectas)>0){						
	 $texto_borrar_adv="Ud. ha solicitado borrar los siguientes registros avanzados:<ul><li>".
	 												implode("<li>\n",$selectas)."</ul>"; 
	 }
	 else $texto_borrar_adv="";
	 

//print $texto_borrar_adv;
$form ="<form action=\"$PHP_SELF\" METHOD=POST >\n";
//incluimos los mx a borrar
if (isset($borrar_mx) and is_array($borrar_mx)){
foreach($borrar_mx as $v){
    $form.="<input type=hidden name=borrar_mx[] value=$v>\n";
}
}
if (isset($borrar_ip) and is_array($borrar_ip)){
foreach($borrar_ip as $v){
    $form.="<input type=hidden name=borrar_ip[] value=$v>\n";
}
}
$agregaremos="";
if (isset($desde) and isset($hasta) and $desde<>"" and $hasta<>"" and $tipo<>""){
$agregaremos="Se agregar&aacute; un registro $tipo desde $desde hasta $hasta<br>\n";
$form.="\n<input type=hidden name=desde value=\"$desde\">\n";
$form.="\n<input type=hidden name=hasta value=\"$hasta\">\n";
$form.="\n<input type=hidden name=tipo value=\"$tipo\">\n";
}
$form.="\n<input type=submit name=confirmar value=\"  Aceptar los cambios \">";
$form.="\n<input type=submit name=descartar value=\"  Descartar los cambios \"></form>";

//print $form;

include "class.templateh.php";
$t=new Template("templates","remove");
$t->set_file("template","cambios.html");
$t->set_var("DOMAIN_NAME",$dominio);
//limpio el bloque no
$t->set_var("NO","");
$t->set_var("a_borrar","$texto_borrar_adv");
$t->set_var("a_agregar","$agregaremos");
$t->set_var("formulario","$form");
$t->pparse("template");
}
else {

//borrar los mx definidos
$ret=true;
if (isset($borrar_mx) and is_array($borrar_mx)){
	 $ret=borrar_mx($borrar_mx,$dominio);
}
if (isset($borrar_ip) and is_array($borrar_ip)){
	 $ret=borrar_ip($borrar_ip,$dominio);
	 } 
if (isset($desde) and isset($hasta) and $desde<>"" and $hasta<>""){
     if (strstr($tipo,"mx")){
		 		//es mx lo que queremos agregar
				$rank=str_replace("mx","",$tipo);
	  		$ret = agregar_mx("mx",$desde,$hasta,$dominio,$rank);
		 }
		 else {
		 		$ret= agregar_ip($tipo,$desde,$hasta,$dominio);	
		 }
}
if (!$ret){
header("location: paneladmin_adv.php?error=indeterminado"); //sirve para descartar tambien
}else {
header("location: paneladmin_adv.php"); //sirve para descartar tambien
}
//var_crudas($_POST);
}
?>