<?
// cambia el password usando mangaesp2.cgi
 $error="";
 /*
 if (!isset($HTTP_REFERER)) die("no se aceptan llamadas locales");
$url=parse_url("$HTTP_REFERER");
/*
if ($url["host"]!="www.nombremania.com"){
  echo "la llamada fue realizada desde un host no habilitado";
  exit;
}
*/
*/
function error($error){
mail("soporte@nombremania.com","error en cambio password de administracion dominios",$error);
exit;
}

if (!isset($k) or $k==""){
error("Llamada invalida k no contiene datos");
}
list($dominio,$password,$usuario)=split(":",$k);
if ($dominio=="" or $password=="") {
    error("error en el envio del dominio o la clave para realizar el cambio, datos enviados k=$k , dominio = $dominio , clave=$clave");
}

if (isset($usuario)){
          if  ($usuario=="") {
                   error("error cambio de usuario  usuario=$usuario, clave=$password , dominio=$dominio ");
              }
            else {
              $sql="update operaciones set reg_username=\"$usuario\" , reg_password=\"$password\" where domain LIKE \"%$dominio%\"";
             // error("todo ok u: $usuario, pass=$password , dom: $dominio");
            }
}
else {
      $sql="update operaciones set reg_password=\"$password\" where domain LIKE \"%$dominio%\"";
}

include "basededatos.php";
$rs=$conn->execute($sql);
if ($rs==false ){
$error=$conn->MsgError();
error("error al cambiar los passwords en tabla de registrados dominio: $dominio , clave=$password,error $error");
}

?>

