<?

if ($_COOKIE["admzona"]==""){

$error="Error en la llamada al sistema de Zonas, ingrese nuevamente o contacte a soporte@nombremanina.com";

header("Location: http://www.nombremania.com/error.php?error=$error");

exit;

}

require "zoneedit.php";
$debug=false;
$error="";

if (strlen($descripcion)>250){
$error.="Los meta description no pueden tener mas de 250 caracteres.";
}

if (strlen($metas)>150){
$error.="Los palabras claves no pueden tener mas de 150 caracteres.";
}
if ($error!=""){
    header("Location: metas.php?mensajes=".urlencode('<b>Error:</b>'.$error));
    exit;
}


$accion="title=".urlencode($titulo)."&metakey=".urlencode($metas).
        "&metadesc=".urlencode($descripcion)."&txt=".urlencode($texto);
//print "accion: ".$accion;


$dominio=$_COOKIE["admzona"];

$usuario=$_COOKIE["admzona"];



if (agregar_metas($dominio,$usuario,$accion)){

    header("Location: metas.php?mensajes=Cambios+grabados+con+exito");

    exit;

}

else {

    header("Location: metas.php?mensajes=Error+en+la+grabacion+de+registros");

    exit;

    }



?>
