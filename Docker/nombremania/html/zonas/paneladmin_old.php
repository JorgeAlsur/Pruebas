<?
include "zoneedit.php";
include "./defaultzona.php";
/*if ($_COOKIE["admzona"]==""){
$error="Error en la llamada al sistema de Zonas, ingrese nuevamente o contacte a soporte@nombremanina.com";
header("Location: /error.php?error=$error");
exit;
} */
//$aux=base64_decode($k);
$aux=$k;

//list($usuario,$dominio)=split(":",$aux);


//include "../phplibs/procesator.php";
//$dominio="electrodavid.com";
//$usuario=calcula_userzone($dominio);
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
$nm_tipo_registro=$res->fields("tipo");
    mostrar_datos();
//    formulario();
    exit;
   }
}

function mostrar_datos(){
Global $PHP_SELF,$dominio,$k,$usuario,$tipos,$error_mf,$error_wf,$total_wf,$total_mf,$tipo,$nm_tipo_registro;

$registros1=registros_zona($usuario,$dominio);
/* print "<pre class=nada>";
print var_dump($registros1);
print "</pre>";
  */
$wf_id=0;$mf_id=0;$wp_id=0;
$a_mf=array();
$a_wf=array();
$a_wp=array();

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
    /*    elseif ($registros1[$i]["tipo"] =="MX"){
        $a_wf[]=array("desde"=>$registros1[$i]["dnsfrom"],
        "hasta"=>$registros1[$i]["dnsto"],
        "id"=>$registros1[$i]["id"],"tipo"=>"MX","type"=>$registros1[$i]["tipo"]);
}     */
}
include "class.templateh.php";
$t=new Template("templates","remove");
$t->set_file("template","panel_basico.html");
$t->set_var("DOMAIN_NAME",$dominio);
//limpio el bloque no
$t->set_var("NO","");
if ($error_mf<>"" or $error_wf <>"") $t->set_var("texto","Se produjo un error en la solicitud. Verifique los errores.");
$t->set_var("error_mf",$error_mf);
$t->set_var("error_wf",$error_wf);
/*
link para panel avanzado

if ($nm_tipo_registro=="PRO3"){
   $t->parse("link_avanzado","link_avanzado",true);
}
 else $t->set_var("link_avanzado","");
*/


/*
  bloque de webforwards
*/


$wfs=0;
foreach($a_wf as $wf){
//    var_crudas($wf);
    $t->set_var("desde_wf",$wf["desde"]);
    $t->set_var("hasta_wf",$wf["hasta"]);
    $t->set_var("tipo_wf",$wf["tipo"]);
    if ($wf["tipo"]<>"MX"){
    $wfs++;
     $borrar="<input type=checkbox name=borrar_wf[] value=\"{$wf["type"]}-{$wf["id"]}\">";
      $t->set_var("borrar_wf",$borrar);
      }else {
      $t->set_var("borrar_wf","");
    }
$disi="";
 if ($wf["type"]=="WF"){
           if ($wf["disimulada"]) {
           $disi="S&iacute;";
           }
           else {
           $disi="No";
           }
      }
    $t->set_var("disimulada",$disi);
    $t->parse("webforwards","webforwards",true);
}
if ($wfs>0){
 $t->parse("BORRAR_WF","BORRAR_WF",true);
} else {
$t->set_var("BORRAR_WF","");
}

//agregar web forward
if ($wfs<$total_wf){
    $a_tipos="";
reset($tipos);
while (list($k,$v)=each($tipos)){
     $a_tipos.="<option value=\"$k\">$v</option>\n";
}
$t->set_var("a_tipos",$a_tipos);
 $t->parse("AGREGAR_WF","AGREGAR_WF",true);
}
//listado de MF

foreach($a_mf as $mf){
         $t->set_var("desde_mf",$mf["desde"]);
         $t->set_var("hasta_mf",$mf["hasta"]);
         $t->set_var("borrar_mf","<input type=checkbox name=\"mf_borrar[]\" value=\"{$mf["id"]}\">");
$t->parse("MAILFORWARD","MAILFORWARD",true);
}


if ($total_wf==9999) $total_wf="ilimitado";
if ($total_mf==9999) $total_mf="ilimitado";
$t->set_var("total_wf",$total_wf);
$t->set_var("total_mf",$total_mf);
if (count($a_mf)>0){
$t->parse("BORRAR_MF","BORRAR_MF",true);
}
$t->parse("AGREGAR_MF","AGREGAR_MF",true);

$t->pparse("template");

}


?>

