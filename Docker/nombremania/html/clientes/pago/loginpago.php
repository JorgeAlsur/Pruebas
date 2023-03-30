<?

include "basededatos.php";

$error="";

$nombre=trim($nombre);

if ($nombre=="" or $clave=="") $error="Error en la verificaci&oacute;n de clave o usuario";



$rs=$conn->execute("select * from clientes where usuario=\"$nombre\"  and activo = 1");

if ($conn->affected_rows()==0){



$error= "Usuario no Encontrado ";



}



if ($rs->fields["clave"]!=$clave){

    $error="Error en la clave ingresada. Usuario $nombre.";

 }

if ($error==""){

 $cod=base64_encode("$nombre");

 setcookie("usuario",$cod,time()+3600);

 header("Location: pago.php\n\n");

 }

 else

 {

 header("Location: /error.php?error=$error"."<br>Cierre esta ventana y reintente.");



 }

?>
