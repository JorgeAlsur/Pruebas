<?
//tomar cada registro de registrados y enviar a operaciones /dominios /zonas segun corresponda

include "basededatos.php";
$conn->debug=1;
$sql_viejo="select * from dbpair.registrados ";

$viejo = $conn->execute($sql_viejo);
  include "hachelib.php";
$errores="";
while (!$viejo->EOF){
      $v=$viejo->fields;
foreach ($v as $k=>$i){
          if (is_int($k) ) {
          unset($v[$k]);
           }
           else {
           $v[$k] = addslashes($i);
           }
          }
          //arma para dominios
          $dominios=explode("**",$v["domain"]);
          $tipo=$v["tipo"];
          unset($v["tipo"]);
          if ($tipo<>""){
              $tipo="PRO2";
              $v["tipo"]=$tipo;
          }
          else {
               $tipo="ESTANDAR";
              $v["tipo"]=$tipo;
               }
$sql=insertdb("operaciones",$v);
$xx=$conn->execute($sql);
if ($xx===false) die("error en grabacion".$sql . mysql_error());
            $domis=array();
            $domis["id_operacion"]=$conn->Insert_ID();
            $domis["id_solicitud"]=$v["id_solicitud"];
            $domis["fecha"]=$v["fecha"];
            $domis["period"]=$v["period"];
            $domis["precio"]=round(3650/166.386,2);
            $domis["cod_opensrs"]=$v["cod_opensrs"];
            $domis["affiliate_id"]=$v["affiliate_id"];

          foreach($dominios as $dom){
            $domis["dominio"]=$dom;
            $sql2=insertdb("dominios",$domis);
            $domm=$conn->execute($sql2);
            if ($v["tipo"]=="PRO2"){
               $zone=$domis;
               $zone["redirecciones"]=5;
               $zone["emails"]=5;
               $zone["tipo"]="PRO2";
               $zone["precio"]=round(2000/166.386,2);
                unset($zone["cod_opensrs"]);
                unset($zone["affiliate_id"]);
                $sql3=insertdb("zonas",$zone);
                 $domm=$conn->execute($sql3);
                 }
             }

$viejo->movenext();
}

?>

