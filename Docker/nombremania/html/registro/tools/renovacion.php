  <?

if (!isset($dominio)) formulario();
include "hachelib.php";

require_once 'opensrs-php/openSRS.php';

$_test_or_live=$tipo;

$O = new openSRS($_test_or_live);

         $cmd = array(
                  "action" => "renew",
                  "object" => "domain",
                  "attributes" => array(
                                   "domain" => "$dominio",
                                   "currentexpirationyear"=>"$anio",
                                   "auto_renew"=>0,
                                   "period"=>$period
                       )
                );
      $result = $O->send_cmd($cmd);

var_crudas($result);
exit;

if ($result["attributes"]["transferrable"]==1){
   echo "<h2>TRANSFERIBLE  sin procesos pendientes</h2>";
}
else {
echo "<h2>no transferible </h2>";
echo  "<br>estado : ".$result["attributes"]["status"];
echo "<br>";
echo  "razon : ".$result["attributes"]["reason"];
echo "<br>";
echo  "ultima actualizacion : ".$result["attributes"]["timestamp"];

}
var_crudas($result);
formulario();

function formulario()
{
Global $PHP_SELF;

  ?>

<!doctype html public "-//W3C//DTD HTML 4.0 //EN">

<html>

<head>

       <title>RENOVAR un dominio</title>

</head>

<body>

 <h3>RENOVACION DE DOMINIOS </h3>

<form action=<?=$PHP_SELF?> method=post >

dominio a renovar: <input type=text name=dominio size=50>
<BR>
Server :
<select name=tipo>
<option value="test" >TEST</option>
</SELECT>
<br>
periodo: <input type=text name=period value=1><br>
a&ntilde;o de vencimiento : <input type=text name=anio value=2001>
<input type=submit>

</form>



</body>

</html>

 <?
exit;

}


?>

