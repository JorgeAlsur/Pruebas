<?
function calcula_userzone($zone){
$largo_zone=strlen($zone);
$largo_total=30;
if ($largo_zone>$largo_total){
list($nombre,$suf)=split("\.",$zone); //separa el sufijo
$largo_sufijo=strlen($suf);
$largo_nombre=strlen($nombre);

$largo_posible_nombre=$largo_total-$largo_sufijo- 3 - 1 ;  // 1 por el punto -3 por los random
$n1=substr($nombre,0,$largo_posible_nombre); // -1 porque empieza en 0
 srand((double)microtime()*1000000);
 $num=rand(0,999);
$zone=$n1.$num.".".$suf;
}
return $zone;
}

function altazona($userzone,$passzone,$zone,$wfzone,$mailzone){

$userzone=calcula_userzone($zone);
$destino="http://www.alsur.es/nombremania/zonas/alta_zona2k.php";
$params="?userzone=$userzone&passzone=$passzone&zone=$zone&mailzone=$mailzone&wfzone=$wfzone";
$zoneedit=file($destino.$params);
// no se que hacer con la respuesta por ahora se toma igual y se devuelve siempre ok
mail("soporte@nombremania.com","respuesta de zoneedit ","destino: $destino\n"."params =$params\n\n----\n".join("\n",$zoneedit));

}

function alta_opensrs($id){
GLOBAL $conn;
if (!isset($id) )
{
echo "Falta el identificador";
return array("retorno"=>false);
}
// recepta el identificador de la base de datos y procesa las peticiones de alta en opensrs y zoneedit si tiene opcion pro

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
 elseif ($k=="reg_password"){
//alteramos el password
            $variables[]="$k=".urlencode($v."2008");
}
            else {
            $variables[]="$k=".urlencode($v);
}
}
$pasar=join("&",$variables);
$destino=$url."?".$pasar."&action=register";
//print htmlentities($destino);
//exit;

//header("Location:".$destino);
// llamada al cgi

$dominios = $datos["domain"];
$dominios = str_replace("**","<br>",$dominios);
$respuesta= "Pedido de registracion de dominios en OPENSRS.\n
ID base de datos ALSUR: $id , se ha solicitado el registro de los siguientes dominios: \n$dominios";
$respuesta.="\n".str_repeat ("-=", 40)."\n";
$respuesta.="la respuesta de OPENSRS ha sido:\n\n";


// analiza que la primer linea contenga registracion NOMBREMANIA
// llama al cgi correspondiente
$opensrs=file("$destino");
$primerlinea = array_shift ($opensrs);


$errores=0;$aciertos=0;
$resultado=array();
$registrados=array();
if (ereg("registracion NOMBREMANIA",$primerlinea)){
if ($datos["tipo"]=="PRO"){
   $wfs=split("\*\*",$datos["wf_wp"]);
   $mfs=split("\*\*",$datos["mf"]);
   $zonas=array();
}
$i=0;
while (list($row,$datos1)=each($opensrs)){
        list($dominio,$estado,$mensaje)=split("::",$datos1);
        $estado=trim($estado);
        if ($estado =="OK"){
            $respuesta.= "el dominio $dominio fue aceptado con id:  $mensaje \n\n";
            $aciertos++;
            $resultado[]="$dominio:OK:$mensaje";
            $registrados[]="$dominio:$mensaje";
                        if ($datos["tipo"]=="PRO"){
                            $zonas[]=array("$dominio",$wfs[$i],$mfs[$i]);
                        }
        }
        else {
            $respuesta.= "el dominio $dominio fue rechazado por $mensaje  \n\n";
            $errores++;
            $resultado[]="$dominio:KO:$mensaje";
        }

        $i++;
        }
$respuesta.="\n\n\tcantidad de errores encontrados: $errores\n\tcantidad de aciertos encontrados: $aciertos";
$respuesta.= "\n\n\nFIN de repuesta.";
}
else {
// el archivo no empieza con registracion NOMBREMANIA
 $errores=1;
 $resultado[]="error en la llamada del CGI id = $id";
 $respuesta.="error en el CGI id= $id ";
      }
$mails=false;
if ($mails){
     $mail_cliente=$datos["owner_email"];
     mail("registros@nombremania.com,$mail_cliente","Confirmacion de pedido de registro en Nombremania",$respuesta,"From : registros@nombremania.com\n");
}

       //guarda datos del registro
       $result=join("\n",$resultado);
       $sql="update dominios set resultado_opensrs='$result' where id=$id";
       $rs1=$conn->execute($sql);
       if ($rs1==false)
       mail("soporte@nombremania.com","error en la base de datos","error al guardar el resultado de opensrs valor=$result ");

//

// alta en zoneedit si es pro
if ($datos["tipo"]=="PRO"){
    $userzone=$datos["reg_username"];
    $passzone=$datos["reg_password"];
  foreach($zonas as $zona){
    $rezone=altazona(trim($userzone),trim($passzone),trim($zona[0]),trim($zona[1]),trim($zona[2]));
  }

}

//grabar datos en tabla registrados
reset($datos);
$reg_username=$datos["reg_username"];
$affiliate_id=$datos["affiliate_id"];
$reg_password=$datos["reg_password"];
//$reg_password=$datos["reg_password"]."2008";
$reg_type=$datos["reg_type"];
$period=$datos["period"];
$owner_first_name=$datos["owner_first_name"];
$owner_last_name=$datos["owner_last_name"];
$owner_email=$datos["owner_email"];
$owner_phone=$datos["owner_phone"];
$tipo=$datos["tipo"];
$precio=$datos["preciototal"];
$id_solicitud=$datos["id"];
$cod_aprob=$datos["cod_aprob"];
$codigo_opensrs="";


foreach ($registrados as $registro){
         $fecha=time();
         list($dominio,$codigo_opensrs)=split(":",$registro);
         $sql="insert into registrados (fecha,affiliate_id,reg_username,reg_password,reg_type,period,domain,owner_first_name,owner_last_name,owner_email,owner_phone,tipo,codigo_opensrs,id_solicitud,precio,cod_aprob) values
          ('$fecha','$affiliate_id','$reg_username','$reg_password','$reg_type','$period','$dominio','$owner_first_name','$owner_last_name','$owner_email','$owner_phone','$tipo','$codigo_opensrs','$id_solicitud','$precio','$cod_aprob')";
//          $conn->debug=1;
           $rs2=$conn->execute($sql);
       if ($rs2==false)
       mail("soporte@nombremania.com","error en la base de datos","error al guardar los datos en tabla registrados dominio=$dominio, codigo_opensrs=$codigo_opensrs");

 }
   // fin de carga de registrados
$retornar=true;
if ($errores>0) $retornar=false;
$ret=array("retorno"=>$retornar,"mensaje"=>$respuesta,"resultado"=>$resultado);
return $ret;
  }

?>