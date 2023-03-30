<?
include "zoneedit.php";
include "./defaultzona.php";
$aux=$k;
if (!isset($_COOKIE["admzona"])){
    header("Location: /error.php?error=Cookie+vencida");
    exit;
    }
$dominio=$_COOKIE["admzona"];
$usuario=$_COOKIE["admzona"];

include "hachelib.php";


if ($tarea=="" or !isset($tarea))
{
include "basededatos.php";
//$conn->debug=true;
$sql="select * from zonas where dominio = \"$dominio\" and tipo like \"PRO%\" ";

$res=$conn->execute($sql);
if ($conn->Affected_Rows()>0) {
$usuario=$res->Fields("reg_username");
$clave=$res->Fields("reg_password");
$total_mf=$res->Fields("emails");  ;
$total_wf=$res->Fields("redirecciones");  ;
    mostrar_datos();
//    formulario();
    exit;
   }
}

function mostrar_datos(){
Global $PHP_SELF,$dominio,$k,$usuario,$tipos,$error_mx,$error_ip,$total_wf,$total_mf;

$registros1=registros_zona($usuario,$dominio);
//var_crudas($registros1);

$wf_id=0;$mf_id=0;$wp_id=0;
$a_mf=array();
$a_wf=array();
$a_wp=array();
$a_ip=array();
$a_mx=array();
for ($i=0;$i<count($registros1);$i++){
 if ($registros1[$i]["tipo"] =="MA") {
           $a_mf[]=array("desde"=>$registros1[$i]["dnsto"],
                   "hasta"=>$registros1[$i]["forward"],
                   "id"=>$registros1[$i]["id"]);
}
elseif ($registros1[$i]["tipo"] =="WP"){
        $desde=$registros1[$i]["dnsfrom"];
        if ($desde=="") $desde="*";
        $hasta=$registros1[$i]["dnsto"];
        if (!$hasta) $hasta="default ";
        $a_wf[]=array("desde"=>$desde,
          "hasta"=>$hasta,
          "id"=>$registros1[$i]["id"],"tipo"=>"parking","type"=>$registros1[$i]["tipo"]);
}
elseif ($registros1[$i]["tipo"] =="WF"){
        $a_wf[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["forward"],
        "id"=>$registros1[$i]["id"],"tipo"=>"forward","type"=>$registros1[$i]["tipo"],
        "disimulada"=>$registros1[$i]["disimulada"]);
}
elseif ($registros1[$i]["tipo"] =="MX"){
        $a_mx[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>"MX","type"=>$registros1[$i]["tipo"],
"rank"=>$registros1[$i]["rank"]);
}
elseif ($registros1[$i]["tipo"] =="A"){
        $a_ip[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>$registros1[$i]["tipo"],"type"=>$registros1[$i]["tipo"]);
}
elseif ($registros1[$i]["tipo"] =="CNAME"){
        $a_ip[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>"CNAME","type"=>$registros1[$i]["tipo"]);
}

}
//var_crudas($a_mx);

 include "class.templateh.php";
$t=new Template("templates","remove");
$t->set_file("template","panel_avanzado.html");
$t->set_var("DOMAIN_NAME",$dominio);
//limpio el bloque no
$t->set_var("NO","");
if ($error_mf<>"" or $error_wf <>"") $t->set_var("texto","Se produjo un error en la solicitud. Verifique los errores.");
$t->set_var("error_ip",$error_ip);
$t->set_var("error_mx",$error_mx);

/*
  bloque de mx
*/

$mxs=0;
foreach($a_mx as $mx){
    //var_crudas($mx);
    $t->set_var("desde_mx",$mx["desde"]);
    $t->set_var("hasta_mx",$mx["hasta"]);
    $t->set_var("tipo_mx",$mx["tipo"]);
    if ($mx["rank"]==0) {
    $rank="1&deg;";
   }
    else{ $rank=($mx["rank"]/5)+1 . "&deg;";
    }
    $t->set_var("rank_mx",$rank);
    $mxs++;
     $borrar="<input type=checkbox name=borrar_mx[] value=\"{$mx["type"]}-{$mx["id"]}\">";
    $t->set_var("borrar_mx",$borrar);
    $t->parse("MX","MX",true);
}
if ($mxs>0){
 $t->parse("BORRAR_MX","BORRAR_MX",true);
} else {
$t->set_var("BORRAR_MX","");
}

//agregar MX (se agrega siempre) por ahora


//listado de ip

foreach($a_ip as $ip){
         $t->set_var("tipo_ip",$ip["tipo"]);
         ($ip["desde"] =="") ? $t->set_var("desde_ip",$ip["desde"]) :$t->set_var("desde_ip",$ip["desde"]."." ) ;
         $t->set_var("hasta_ip",$ip["hasta"]);
         $t->set_var("borrar_ip","<input type=checkbox name=\"borrar_ip[]\" value=\"{$ip["id"]}\">");
$t->parse("IP","IP",true);
}
if (count($a_ip)>0){
$t->parse("BORRAR_IP","BORRAR_IP",true);
}

$t->pparse("template");

}





?>
