<?
//  paso de todas las tablas.

//paso de dominios a registrados
 include "basededatos.php";
$conn->debug=0;
$sql_viejo="select * from dbpair.dominios ";

$viejo = $conn->execute($sql_viejo);
  include "hachelib.php";
$errores="";
while (!$viejo->EOF){
     $v=$viejo->fields;
     $v["nm_registro_tipo"]=$v["tipo"];
     unset($v["tipo"]);
     $v["nm_cod_aprob"]=$v["cod_aprob"];
     unset($v["cod_aprob"]);
     $v["nm_preciototal"]=     $v["preciototal"];
     unset($v["preciototal"]);
     $v["nm_wf_wp"]=     $v["wf_wp"];
unset($v["wf_wp"]);
     $v["nm_wp"]=     $v["wf_wp"];
unset($v["wp"]);
     $v["nm_mf"]=     $v["mf"];
unset($v["mf"]);
     $v["nm_idtrans"]=     $v["idtrans"];
unset($v["idtrans"]);

     $v["nm_cola"]=     0;
$v['nm_resultado_opensrs']=$v['resultado_opensrs'];
unset($v['resultado_opensrs']);
reset($v);
          foreach ($v as $k=>$i){
          if (is_int($k) ) {
          unset($v[$k]);
           }
           else {
           $v[$k] = addslashes($i);
           }
          }
unset($v["notes"]);
unset($v["fechatrans"]);

          $in=insertdb("solicitados",$v);
          $rs=$conn->execute($in);
          if ($rs===false){
             $errores.= "<br>".$v["id"] . mysql_error();
          }
$viejo->movenext();
}

print $errores;
?>


