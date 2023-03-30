<?
//require "zoneedit.php";
require "hexonet.php";
$error="";

/**

	JOSE (11-03-2010):
	Cambiada la funcion de borrar con los
	parametros que requiere, distinto en hexonet
	que en zoneedit.
	
	Para pruebas en local, descomentar
	admzona, para no ser necesario tener cookies.
	
	//$admzona='todopc.com';
**/
//$admzona='todopc.com';

switch (strtolower(trim($tarea))){
 case "agregar / modificar redireccion":
/* if (!ereg("^http://",urldecode($hasta)) and $tipo<>"webforward") {
    $error=urlencode("Error :  es necesario que la dirección de destino de un Webforward comience con http://");
    header("Location: paneladminv2.php?error_wf=".$error);
    exit;
 }*/
 $que=agregar($tipo,$desde,$hasta,$admzona,$disimulada);
   if ($que==1){
       header("Location: paneladmin.php");
   }
   elseif ($que===0){
   $error=urlencode("Error de creacion de WF o Wp");
   header("Location: paneladmin.php?error_wf=".$error);
   }
   else  {
    $error= urlencode($que);
   header("Location: paneladmin.php?error_wf=".$error);
   }
   break;
 case "borrar seleccionadas":
   if (borrar($borrar_wf,$admzona)){
   header("Location: paneladmin.php");
   }
   else {
   $error= "Error de borrado de zonas";
   header("Location: paneladmin.php?error_wf=". urlencode($error));
   }
  break;
   default:
   header("Location: paneladmin.php");
}


function agregar($tipo,$desde,$hasta,$dominio,$disimulada=false){
switch( strtolower($tipo)){
        case "webforward":

              if ($hasta==""){
              $error="Falta uno de los valores necesarios para realizar el Webforwad.";
              }
              elseif (!ereg("^http://",$hasta)){
              $error = "Para crear un webforward se debe incluir http:// en la direcci&oacute;n de destino";
              }
               if ($disimulada=="on"){
                   $shadow="&shadow=true";
               }else {
                    $shadow="&shadow=false";
               }
							 $hasta= urlencode($hasta);
                    $destinos="dnsfrom=$desde&forward=$hasta".$shadow;
         $tipo="WF";
         break;
        default:
              if ($desde==""){
              $error="Falta el subdominio para poder crear el WebParking requerido.";
              }
        $destinos="dnsfrom=$desde&dnsto=".urlencode($tipo);
          $tipo="WP";
         break;
}
if ($error<>""){
return $error;
}


if (agregar_registro($dominio,$tipo,$destinos,$dominio)){
    return 1;
}
else {
      return 0;
}

}

function borrar($a_borrar,$dominio){
if (!is_array($a_borrar)){
$volver=false;
return $volver;
}

foreach($a_borrar as $borrar){
//list($tipo,$id)=split("-",$borrar);


//if (!borrar_registro($id,$dominio,$tipo,$dominio)){
if (!borrar_registro($dominio, $borrar)){																	 
     $error="registro $id, $dominio no se pudo borrar";
     $volver=false;
     }
else {
     $volver=true;
}
}
return $volver;
}
?>
