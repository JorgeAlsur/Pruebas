<?
ini_set("include_path",$_SERVER['DOCUMENT_ROOT']."/phplibs");
//function zoneedit($enviar) {
include("conf.inc.php");
if(strtolower($_test_or_live) == "test")
{
	$pass="domaindemo.com:2008";
	$server= "dns.nombremania.com";
	//echo "pass : $pass";
}
else
{
	//echo "pass : $pass";
	$server= "dns.nombremania.com";
	$pass="luison:existe95";
}
$enviar="command=viewzone&user=encofradosconarga.com&zone=encofradosconarga.com";

$url="http://$pass@$server/auth/admin/command.html?$enviar";
//print $url;
$temp=file($url);
echo "$url<br/>";
print_r($temp);
exit;

$temp=join("\n",$temp);
if (ereg("failure",$temp)){
    $pos1=strpos($temp,"failure code=\"");
    $cod=substr($temp,$pos1+14,3);
}
else{
    $pos1=strpos($temp,"success code=\"");
    $cod=substr($temp,$pos1+14,3 );
}
$salida=array();
$salida["codigo"]=$cod;
//$temp=str_replace("cloaked","cloaked=\"true\"",$temp);

$salida["xml"]=$temp;
//return $salida;
//}


function zoneedit2($enviar){
$url="https://www.zoneedit.com/auth/admin/command.html";
//Start ouput buffering
ob_start();
ob_implicit_flush(0);
$ch = curl_init();
$pass="luison:luciano";
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
curl_setopt ($ch, CURLOPT_USERPWD, $pass);
curl_setopt($ch, CURLOPT_SSLVERSION, 2);
curl_setopt($ch, CURLOPT_TIMEOUT, 6); //times out after 4s
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $enviar);
curl_exec ($ch);
curl_close ($ch);
$temp = ob_get_contents();
//Shut off buffering
ob_end_clean();
//if (!$error)  echo "<h3>Error</h3>";
//$temp=str_replace("\\r\\n","",$temp);
$salida=array();
if (ereg("failure",$temp)){
    $pos1=strpos($temp,"failure code=\"");
    $cod=substr($temp,$pos1+14,3);
}
else{
    $pos1=strpos($temp,"success code=\"");
    $cod=substr($temp,$pos1+14,3 );

}
$salida["codigo"]=$cod;
$salida["xml"]=$temp;
return $salida;
}

function agrega_usuario($usuario,$password="nombremania",$email=""){
$command="command=adduser&user=$usuario&pass=$password";
$que=zoneedit($command);
/*
print "<hr>creacion de zona para usuario <br>$command
<pre>";
print htmlentities($que["xml"]);
print "</pre>";
  */
if ($que["codigo"]<500){
return true;
}
return false;
}

function agrega_zona($usuario,$zona){
$command="command=adduserzone&user=$usuario&zone=$zona&editmenu=F&typemenu=F";
$que=zoneedit($command);

/* print "<hr>creacion de zona para usuario <br>$command
<pre>";
print htmlentities($que["xml"]);
print "</pre>";
*/
$dns=array();
$dns["codigo"]=$que["codigo"];
if ($que["codigo"]<="300"){
$texto=$que["xml"];
include_once "xml.php";
$xml = new XML();
$xml->load_file("",$texto);
$ns="";
$ns = $xml->get_content($re."/ze[1]/response[1]/success[1]/attribute::nameservers");

if ($ns!=""){
        list($dns1,$dns2)=split(",",$ns);
}
$dns["resultado"]=true;
$dns["dns1"]=$dns1;
$dns["dns2"]=$dns2;

return $dns;
}
else {
$dns["resultado"]=false;
return $dns;
}
}


function registros_zona($usuario,$zona){
include_once "xml.php";
$comando="command=ViewRecord&user=$usuario&zone=$zona";
$recibo=zoneedit($comando);

$texto=$recibo["xml"];
/*print "<pre>";
print htmlentities($texto);
print "<pre>";*/
$xml = new XML();
//$texto=implode("",file($file));
$xml->load_file("",$texto);
$consulta="/ze[1]/response[1]/*";

$results = $xml->evaluate($consulta);
$registros=array();
foreach ( $results as $registro )
            {
                // Retrieve information about the person.
                $re=$registro;
                $tipo  = $xml->get_content($re."/attribute::type");
                $dnsfrom  = $xml->get_content($re."/attribute::dnsfrom");
                $dnsto = $xml->get_content($re."/attribute::dnsto");
                $forward = $xml->get_content($re."/attribute::forward");
                $id=$xml->get_content($re."/attribute::id");
                if ($tipo=="MX") {
                $rank=$xml->get_content($re."/attribute::rank");
$registros[]=array("tipo"=>$tipo,
                "dnsto"=>$dnsto, "forward"=>$forward , "dnsfrom"=>$dnsfrom, "id"=>$id ,"rank"=>$rank );
                }
                else{
                $disimulada=$xml->get_content($re."/attribute::cloaked");
                $registros[]=array("tipo"=>$tipo,
                "dnsto"=>$dnsto, "forward"=>$forward , "dnsfrom"=>$dnsfrom, "id"=>$id ,"disimulada"=>$disimulada );
                 }
    }
return $registros;

}

function borrar_registro($id,$dominio,$tipo,$usuario) {
if ($id==""){
return false;
}
else {
//print "<hr>id:$id";
}
$recibo=zoneedit("command=DelRecord&zone=$dominio&user=$usuario&type=$tipo&id=$id");
$texto=$recibo["xml"];
include_once  "xml.php";
$xml = new XML();
$xml->load_file("",$texto);
$consulta="/ze[1]/response[1]/success[1]";

$results = $xml->evaluate($consulta);
$re=$results[0];

$resultado  = $xml->get_content($re."/attribute::code");
if ($resultado<>"200"){
return false ;
}
else {
return true;
}


}

function agregar_registro($dominio,$tipo,$destinos,$usuario) {
$command="command=AddRecord&zone=$dominio&user=$usuario&type=$tipo&$destinos";
// print "<br>comando : $command</br>"; 
$recibo=zoneedit($command);
$texto=$recibo["xml"];
/*print "<pre>";
print var_dump($texto);
print "</pre>";
  */
$texto=str_replace("<br>","",$texto);

include_once "xml.php";
$xml = new XML();
$xml->load_file("",$texto);
$consulta="/ze[1]/response[1]/success[1]";

$results = $xml->evaluate($consulta);
$re=$results[0];

$resultado  = $xml->get_content($re."/attribute::code");
if ($resultado<>"200"){
return false ;

}

return true;
}

function agregar_wf($dominio,$desde,$hasta,$usuario){

$destinos="dnsfrom=$desde&forward=$hasta";
$t=agregar_registro($dominio,"WF",$destinos,$usuario);
         if ($t) {
         return true;
         }
         else {
         return false;
                 }
}


function metas_zona($usuario,$zona){
Global $debug;
include_once "xml.php";
$comando="command=viewzone&user=$usuario&zone=$zona";
if ($debug) echo $comando;
$recibo=zoneedit($comando);
$texto=$recibo["xml"];
/*print "<pre>";
print htmlentities($texto);
print "<pre>";
  */
$xml = new XML();
//$texto=implode("",file($file));
$xml->load_file("",$texto);
$consulta="/ze[1]/response[1]/zone[1]";

$results = $xml->evaluate($consulta);
$registros=array();
foreach ( $results as $registro )
            {
                $re=$registro;
    //            print "registro : ". $registro;
                $registros["titulo"]= $xml->get_content($re."/attribute::title");
                $registros["metas"] = $xml->get_content($re."/attribute::metakey");
                $registros["metadesc"]= $xml->get_content($re."/attribute::metadesc");
                $registros["texto"]= $xml->get_content($re."/attribute::txt");
    }
return $registros;
}

function agregar_metas($zona,$usuario,$agregar=""){
Global $debug;
if ($agregar=="")  return false;
//$command="command=changerecord&userzone=$usuario&zone=$zona&$agregar";
$command="command=changerecord&zone=$zona&type=meta&$agregar";
if ($debug) echo $comando;
$recibo=zoneedit($command);
$texto=$recibo["xml"];
/*print "<pre>";
print "comando : $command";
print var_dump($texto);
print "</pre>";
*/
$texto=str_replace("<br>","",$texto);

include_once "xml.php";
$xml = new XML();
$xml->load_file("",$texto);
$consulta="/ze[1]/response[1]/success[1]";

$results = $xml->evaluate($consulta);
$re=$results[0];
$resultado  = $xml->get_content($re."/attribute::code");
if ($resultado<>"200"){
return false ;
}

return true;
}

function agregar_credito($user,$credit=1){
Global $debug;
if ($user==""){
return false;
}
$command="command=AddUserCredit&user=$user&credits-amount=$credit&credits-paid=$credit";
$recibo=zoneedit($command);
$texto=$recibo["xml"];
/*print "<pre>";
print "comando : $command";
print var_dump($texto);
print "</pre>";
*/
$texto=str_replace("<br>","",$texto);
include_once "xml.php";
$xml = new XML();
$xml->load_file("",$texto);
$consulta="/ze[1]/response[1]/success[1]";
$results = $xml->evaluate($consulta);
$re=$results[0];
$resultado  = $xml->get_content($re."/attribute::code");
if ($resultado<>"200"){
return false ; 
}
return true;
}
?>
