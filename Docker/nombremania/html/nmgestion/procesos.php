<?
function alta_opensrs($id){
GLOBAL $conn;
if (!get_cfg_var('safe_mode')) {
      set_time_limit(180);
}

if (!isset($id) )
{
echo "Falta el identificador";
return array("retorno"=>false);
}
// recepta el identificador de la base de datos y procesa las peticiones de alta en opensrs y zoneedit si tiene opcion pro
if ($tarea="opensrs"){

$rs=mysql_query("select * from dominios where id=$id");
$datos=mysql_fetch_array($rs,MYSQL_ASSOC);
if ($datos["tipo"]=="PRO"){
$url="http://www.nombremania.com/cgi-bin/registros/sp/registrarpro.cgi";
}
else {
$url="http://www.nombremania.com/cgi-bin/registros/sp/registrar.cgi";
}
$variables=array();
while (list($k,$v)=each($datos)){
if ($k=="domain"){
            $aux=split("\*\*",$v);
                       for ($j=0;$j<=count($aux)-1;$j++){
                             $variables[]="domain=".urlencode($aux[$j]);
                        }
            }
else {
$variables[]="$k=".urlencode($v);
}
}
$pasar=join("&",$variables);
$destino=$url."?".$pasar."&action=register";
/*print htmlentities($destino);
exit;
  */
//header("Location:".$destino);
// llamada al cgi
$dominios = $datos["domain"];
$dominios = str_replace("**","<br>",$dominios);
$respuesta= "Pedido de registracion de dominios en OPENSRS.\n
ID base de datos ALSUR: $id , se ha solicitado el registro de los siguientes dominios: \n$dominios";
$respuesta.="\n".str_repeat ("-=", 40)."\n";
$respuesta.="la respuesta de OPENSRS ha sido:\n\n";

$opensrs=file("$destino");
$errores=0;$aciertos=0;
while (list($row,$datos)=each($opensrs)){
        list($dominio,$estado,$mensaje)=split("::",$datos);
        $estado=trim($estado);
        if ($estado =="OK"){
            $respuesta.= "el dominio $dominio fue aceptado con id:  $mensaje \n\n";
            $aciertos++;
        }
        else {
            $respuesta.= "el dominio $dominio fue rechazado por $mensaje  \n\n";
            $errores++;
        }
        }
$respuesta.="\n\n\tcantidad de errores encontrados: $errores\n\tcantidad de aciertos encontrados: $aciertos";
$respuesta.= "\n\n\nFIN de repuesta.";
}
//print "<pre>$respuesta</pre>";
$mails=true;
if (!$mails){
     $mail_cliente=$datos["owner_email"];
     mail("registros@nombremania.com,$mail_cliente","Confirmacion de pedido de registro en Nombremania",$respuesta);
}

$ret=array("retorno"=>true,"mensaje"=>$respuesta);
return $ret;
  }

?>