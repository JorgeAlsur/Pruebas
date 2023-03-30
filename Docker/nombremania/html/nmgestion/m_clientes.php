<?php
include "basededatos.php";
function entrecomillas(&$tabla, $clave) {
   $tabla = "'$tabla'";
}


function grabar ($datos){
global $conn;
$valores=array_values($datos);
$campos=array_keys($datos);
$campos=join(",",$campos);
array_walk($valores,"entrecomillas");
$valores=join(",",$valores);
$sql="insert into clientes ($campos) values ($valores)";
$rs=$conn->execute($sql);
if ($rs){ echo "Grabacion con exito, registro ingresado con ID = ". $conn->Insert_ID();
}
else {echo print_errores("Error en la grabación");
print "<br>".$conn-> ErrorMsg();
}

}




require("String_Validation.inc.php");
require("EasyTemplate.inc.php");




/*
 *      void sprint_error(string string)
 *      Return a formatted error message
 */
function print_errores($string)
{
    if(!empty($string))
    {
        return  "<br><font color=\"red\"><b>&nbsp;!&nbsp;</b></font>$string\n";
    }
}

// Initialize $message
$message = "Ingrese los datos requeridos:";

// Has the form been posted?
if (isset($_GET["id"])){
$rs=$conn->execute("select * from clientes where id=$id");
$form=$rs->fields;
}



if($REQUEST_METHOD == "POST")
{
    // Initialize the errors array
    $errors = array();

    // Trim all submitted data
    while(list($key, $value) = each($form))
    {
        $form[$key] = trim($value);
    }

    // Check submitted name
    if(strlen($form["nombre"])<4)
    {
        $errors["nombre"] = "El nombre ingresado no es valido. (menos de 4 caracteres)";
    }

    // Check submitted email address
    if(!is_email($form["email"]))
    {
        $errors["email"] = "El email ingresado no es valido.";
    }
    if ($form["direccion"]==""){
        $errors["direccion"] = "La direccion no es correcta.";
    }

    if ($form["clave"]!= $clave2 ){
        $errors["clave"]="error en la clave.";
    }
    print('<div align="center">');

    // Can the form be processed or are there any errors?
    if(count($errors) == 0)
    {
       //paso con exito
       grabar($form);
        exit;
    }
    else
    {
        $message = "Hay Errores en la Carga.";
    }

    print('</div>');
}

// Create a new EasyTemplate class
$template = new EasyTemplate("form/form_clientes.html");

// Assign template values
$template->assign("HEADER", $message);
$template->assign("ACTION", basename($PHP_SELF));
$template->assign("NOMBRE_VALUE", isset($form["nombre"]) ? $form["nombre"] : "");
$template->assign("NOMBRE_ERROR", print_errores(isset($errors["nombre"]))) ;

$template->assign("EMAIL_VALUE",  isset($form["email"]) ? $form["email"] : "");
$template->assign("EMAIL_ERROR",  print_errores($errors["email"]));

$template->assign("DIRECCION_VALUE", isset($form["direccion"]) ? $form["direccion"] : "");
$template->assign("DIRECCION_ERROR", print_errores($errors["direccion"]) );

$template->assign("TELEFONO_VALUE",  isset($form["email"]) ? $form["telefono"] : "");
$template->assign("TELEFONO_ERROR", isset($errors["telefono"])  ? print_error("error en telefono") : "");

$template->assign("CLAVE_VALUE",  isset($form["clave"]) ? $form["clave"] : "");
$template->assign("CLAVE_ERROR", print_errores($errors["clave"]) );


$template->easy_print() or die($template->error);



?>
