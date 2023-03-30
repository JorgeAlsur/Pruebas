
<?
//  paso de todas las tablas.

//paso de dominios a registrados
include "basededatos.php";
include "hachelib.php";
$conn->debug=0;
$sql_regi = "select * from operaciones group  by owner_email";
$sqlc = "";
$regi  = $conn->execute($sql_regi);
while (!$regi->EOF){
 		$a = array();
			$v= limpia_rs($regi->fields);
			$a["email_id"]=$v["owner_email"];
			$a["domain"]=substr($v["owner_email"],strpos($v["owner_email"],'@')+1);			
			$a["password"] = "nombremania";
		  $a["signup_dt"]="2001-10-30 11:10:00";
			$a["option1"]= 1;
			$a["option2"]=$v["owner_last_name"];
			$a["option3"]=$v["owner_first_name"];
			$a["option4"]=$v["tipo"];
			$sqlc = insertdb("ciao.elm_list",$a)."\n";
			$carga = $conn->execute($sqlc);
			print mysql_error();
			$sqlc = "insert into ciao.elm_catlist values ('{$a["email_id"]}','nm')\n";
			$carga = $conn->execute($sqlc);
			print mysql_error();
			$sqlc ="";
 $regi->movenext();
}




$sql_viejo="SELECT `owner_first_name`,`owner_last_name`,`owner_email`, nm_registro_tipo FROM `solicitados` WHERE 1 GROUP BY owner_email " ;

$soli = $conn->execute($sql_viejo);
if ($soli === false ) die ("error mysql ". mysql_error());
//rs2html($soli);

$errores="";
$sqlc = "";
while (!$soli->EOF){
			$a = array();
			$v= limpia_rs($soli->fields);
			$a["email_id"]=$v["owner_email"];
			$a["domain"]=substr($v["owner_email"],strpos($v["owner_email"],'@')+1);			
			$a["password"] = "nombremania";
		  $a["signup_dt"]="2001-10-30 11:10:03";
			$a["option1"]= 0;
			$a["option2"]=$v["owner_last_name"];
			$a["option3"]=$v["owner_first_name"];
      $a["option4"]=$v["nm_registro_tipo"];
			$sqlc = insertdb("ciao.elm_list",$a)."\n";
  		$carga = $conn->execute($sqlc);
			print mysql_error();
			$sqlc = "insert into ciao.elm_catlist values ('{$a["email_id"]}','nm')\n";
			$carga = $conn->execute($sqlc);
			print mysql_error();
$soli->movenext();
}
/*print "<pre>";
print $sqlc;
print "</pre><hr>";
*/
$sqlc ="";

   
?>