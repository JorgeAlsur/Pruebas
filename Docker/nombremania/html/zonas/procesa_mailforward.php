<?
//require "zoneedit.php";
require "hexonet.php";

/**
	JOSE (11-03-2010):
	Se cambian las funciones de agregar y borrar
	mailforwards, con motivo del cambio a hexonet.
	
	Cambia la funcion de borrar, ya que en hexonet
	recibe otros parametros. Tambien se modifica
	la funcion de agregar para no recibir usuario,
	ya que da problemas para anadir registros.
	De todas formas esta ignorado en la funcion.
	
	Las variables $admzona y $usuario estaban
	para hacer pruebas en local sin intervencion
	de las cookies.
	
	//$admzona='todopc.com';
	//$usuario='nombremania.com';
**/

switch (strtolower(trim($tarea))){
 case "agregar redireccion":
 include "String_Validation.inc.php";
  if (!is_email($hasta)){
        $error=urlencode("Error:  la direccion de destino debe ser un mail v&aacute;lido.");
    header("Location: paneladmin.php?error_mf=$error");
   exit;

 }
//$tipo='MX';
 $que=agregar($tipo,$desde,$hasta,$admzona);

   if ($que==1){

       header("Location: paneladmin.php");

   }

   elseif ($que===0){

   $error=urlencode("Error de creacion de MF");

   header("Location: paneladmin.php?error_mf=$error");

   exit;

   }

   else  {

   $error=urlencode($que);

   header("Location: paneladmin.php?error_mf=$error");

   exit;



   }

   break;

 case "borrar seleccionados":

	if (borrar($mf_borrar,$admzona)){

   header("Location: paneladmin.php");

   }

   else {

   $error=urlencode("Error de borrado de Mailforward");

   header("Location: paneladmin.php?error_mf=$error");

   exit;

   }

   break;

   default:

   echo "error";

}





function agregar($tipo,$desde,$hasta,$dominio){
		
         $error="";

              if ($desde=="" or $hasta==""){

              $error="Falta uno de los valores necesarios para realizar el MailForward.";

              }

 $destinos="dnsto=$desde&forward=$hasta";

if ($error<>""){

    return $error;

}

$tipo="MF";
//$usuario='nombremania.com'; // siempre debe ser nombremania.com

//if (agregar_registro($dominio,$tipo,$destinos,$usuario)){
	if (agregar_registro($dominio,$tipo,$destinos)){

    return "1";

}

else {

      return "0";

}



}



function borrar($a_borrar,$dominio){



foreach($a_borrar as $borrar){




//if (!borrar_registro($borrar,$dominio,"MF",$dominio)){
if (!borrar_registro($dominio, $borrar)){
	
     $error="registro $borrar, $dominio no se pudo borrar";

     $volver=false;

     }

else {

     $volver=true;

}

}

return $volver;

}

?>
