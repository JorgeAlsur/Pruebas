<?

require "zoneedit.php";

$error="";
include "hachelib.php";
//var_crudas($tarea);
switch (strtolower(trim($tarea))){

 case "agregar / modificar mx":
 //admzona sale de la cookie
 $que=agregar($tipo,$desde,$hasta,$admzona,$rank);
   if ($que==1){
       header("Location: paneladmin_adv.php");
   }
   elseif ($que===0){
   $error=urlencode("Error de creacion de servers MX");
   header("Location: paneladmin_adv.php?error_mx=".$error);
   }
   else  {
    $que;
   }
   break;
 case "borrar seleccionados":
   if (borrar($borrar_mx,$admzona)){
   header("Location: paneladmin_adv.php");
   }
   else {
   $error= "Error de borrado de zonas";
   }
  break;
   default:
   header("Location: paneladmin_adv.php");
}


function agregar($tipo,$desde,$hasta,$dominio,$rank){
switch( strtolower($tipo)){
        case "mx":
              if ($hasta==""){
              $error="Falta uno de los valores necesarios para realizar el Servidor de Correo.";
              }
              if ($rank==""){
                   $rank="&rank=0";
               }else {
                    $rank="&rank=$rank";
               }
//               $desde=$desde.".$dominio";
              $destinos="dnsfrom=$desde&dnsto=$hasta".$rank;
             $tipo="MX";
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

foreach($a_borrar as $borrar){
list($tipo,$id)=split("-",$borrar);

if (!borrar_registro($id,$dominio,$tipo,$dominio)){
     $error="Registro $id, $dominio no se pudo borrar";
     $volver=false;
     }
else {
     $volver=true;
}
}
return $volver;
}
?>
