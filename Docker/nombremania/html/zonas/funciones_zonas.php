<?
//programa en php horacio degiorgi


/**
	JOSE (11-03-2010):
	Modificadas las funciones debido al cambio
	de zoneedit por hexonet.
	Las paginas que incluye este script son
	paneladmin_adv y procesa_adv, que ya
	deberian incluir a hexonet.php.
	
	El cambio se da en las funciones de borrado
	que cambia el parametro $a_borrar, que con
	zoneedit llegaba del modo $tipo-$id y en
	hexonet llega con la cadena a borrar.
	
	Tambien cambia la funcion de borrar_registro
	que recibe los parametros ($dominio, $borrar)
	en lugar de ($id, $dominio, $tipo, $dominio)
	
	Las funciones para agregar mx e ip, y agregar_registro
	se mantienen igual.
**/

function borrar_mx($a_borrar,$dominio){
//recibe mx-numero donde numero es el id de zoneedit
foreach($a_borrar as $borrar){
//list($tipo,$id)=split("-",$borrar);
//if (!borrar_registro($id,$dominio,$tipo,$dominio)){
if (!borrar_registro($dominio, $borrar)){
     $error="Registro $id, $dominio no se pudo borrar";
//   	 print $error;
	   $volver=false;
     }
else {
     $volver=true;
}
}
return $volver;
}

function agregar_mx($tipo,$desde,$hasta,$dominio,$rank=""){
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
               $desde=$desde;
              $destinos="dnsfrom=$desde&dnsto=$hasta".$rank;
             $tipo="MX";
         break;
        default:
           $error="Falta el subdominio para poder crear el WebParking requerido.";
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

function agregar_ip($tipo,$desde,$hasta,$dominio){
           $error="";
          $destinos="dnsto=$hasta&dnsfrom=$desde";
          if ($tipo==""){
          $error="Falta la definicion de tipo de registro a agregar";
          }
if ($error<>""){
	 return $error;
}

return agregar_registro($dominio,$tipo,$destinos,$dominio);
}

function borrar_ip($a_borrar,$dominio){

foreach($a_borrar as $borrar){
$id=$borrar;
$tipo="A";
//if (!borrar_registro($id,$dominio,$tipo,$dominio)){
if (!borrar_registro($dominio, $borrar)){
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
