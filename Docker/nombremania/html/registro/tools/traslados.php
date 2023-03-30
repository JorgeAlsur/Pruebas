<?

if (!isset($dominio)) formulario();
include "hachelib.php";

require_once 'opensrs-php/openSRS.php';

$_test_or_live=$tipo;

$O = new openSRS($_test_or_live);

         $cmd = array(
                  "action" => "check_transfer",
                  "object" => "domain",
                  "attributes" => array(
                                   "domain" => "$dominio",
				   "affiliate_id"=>"$afiliado"
                  )
                );
      $result = $O->send_cmd($cmd);


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

       <title>chequear estado de traslado</title>

</head>

<body>



<form action=<?=$PHP_SELF?> method=post >

dominio a chequear: <input type=text name=dominio size=50>
<BR>
Server :
<select name=tipo>
<option value="test" >TEST</option>
<option value="LIVE" >LIVE</option>
</SELECT>

<input type=submit>

</form>



</body>

</html>

 <?
exit;

}


?>

