<?

if (!isset($dominio)) formulario();
include "hachelib.php";

require_once 'osrsh/openSRS.php';

$_test_or_live=$tipo;

$O = new openSRS($_test_or_live);
/*$O->USERNAME = "luison";
$O->PRIVATE_KEY = "ae8f31ff89e9610ae3231df17097a34d748d036cba48e414efde609174c5c06fcdfa8839e0e8cdb70d5c54968e4651d3a8640fc1125860f";
$O->RELATED_TLDS = array(
                array( '.com', '.net', '.org','.cc','.tv' ),
        );
				*/ 
$cmd = array(
"action" => "set",
"object" => "cookie",
"registrant_ip" => "213.96.190.180",
"attributes" => array(
                 "reseller" => "luison",
								 "reseller_ip"=>"62.22.15.23",
                 "domain" => $dominio ,
                 "reg_username" => $usuario,
                 //"reg_password" => trim($pass)."2008"
                 "reg_password" => trim($pass)
)
);
var_crudas($cmd);
   $result = $O->send_cmd($cmd);
	 var_crudas($result);
//echo "primera salida";
// var_crudas($result);
if (!isset($page))$page=1;
if (isset($result["attributes"]["cookie"]) and $result["attributes"]["cookie"]<>""){
         $cmd = array(
                  "action" => "get",
                  "object" => "domain",
                  "cookie" =>$result["attributes"]["cookie"],
                  "attributes" => array(
                      "type" => "list",
                      "page" =>$page-1
                  )
                );
      $result = $O->send_cmd($cmd);
	//		var_Crudas($result);
echo "<h2>Lista de dominios asociados</h2>";
echo "Cantidad :". $result["attributes"]["count"];
echo "<table><tr><td>numero</td><td>dominio</td><td>expira</td></tr>";
  $i=1;
foreach($result["attributes"]["ext_results"] as $dom){

                         $nombre=array_keys($dom);
                         $domm= array_values($dom);

                         echo  "<tr><td>$i</td><td>{$nombre[0]}</td>";
                         echo "<td>".$domm[0]["expiredate"]."</td></tr>";
$i++;
}
echo "</table>";
//var_crudas($result);
}
else {
     echo "<h2>error de autentificacion </h2>";
}

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
<br>usuario: <input type=text name=usuario size=50>
<br>password: <input type=text name=pass size=50>
<BR>
Server :
<select name=tipo>
<option value="test" >TEST</option>
<option value="LIVE" >LIVE</option>
</SELECT>
<br>
Pagina :(nuestra de 35 en 35) <input type=text size=2 name=page value=1>
<input type=submit>

</form>



</body>

</html>

 <?
exit;

}


?>

