<?
require "zoneedit.php";
$error="";
include "hachelib.php";
//var_crudas($tarea);
switch (strtolower(trim($tarea))){
 case "agregar registro avanzado":
 //admzona sale de la cookie
 $que=agregar($desde,$hasta,$admzona,$tipo);
   if ($que==1){
       header("Location: paneladmin_adv.php");
   }
   elseif ($que===0){
   $error=urlencode("Error de creacion de Direccion IP");
   header("Location: paneladmin_adv.php?error_ip=".$error);
   }
   else  {
    $que;
header("Location: paneladmin_adv.php?error_ip=". urlencode($que));
   }
   break;
 case "borrar seleccionados":
   if (borrar($borrar_ip,$admzona)){
   header("Location: paneladmin_adv.php");
   }
   else {
   $error= "Error de borrado de zonas";
   }
  break;
   default:
   header("Location: paneladmin_adv.php");
}


function agregar($desde,$hasta,$dominio,$tipo){
           $error="";
             if ($hasta=="" or $desde==""){
             $error="Falta uno de los valores necesarios para actualizar el REGISTRO (A).";
             }
             if ($tipo=="A" and !ereg("^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$", $hasta)){
             $error="La direccion de destino debe ser un n&uacute;mero v&aacute;lido de IP, del tipo 192.168.0.122";
          }
          $destinos="dnsto=$hasta&dnsfrom=$desde";
          if ($tipo==""){
          $error="Falta la definicion de tipo de registro a agregar";
          }
          //          $tipo="A";


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
$id=$borrar;
$tipo="A";
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
