<?
//include('zoneedit.php'); 
include('hexonet.php');
include('./defaultzona.php');
include('./funciones_zonas.php');
$aux=$k;

# JOSE (11-03-2010): Solo cambios de libreria.

# comentado para pruebas

if(!isset($_COOKIE['admzona']))
{
	header("Location: /error.php?error=Se ha vencido la sesion del sistema deber&aacute; ingresar nuevamente.");
	exit;
}


$dominio=$_COOKIE['admzona'];
//$dominio='todopc.com';
$usuario=$_COOKIE['admzona'];
//$usuario='nombremania.com';


include('hachelib.php');
$registros1=registros_zona($usuario,$dominio);
//var_crudas($registros1);
 
$ret=true;
if (isset($borrar_mx) and is_array($borrar_mx)){
	 $ret=borrar_mx($borrar_mx,$dominio);
}
if (isset($borrar_ip) and is_array($borrar_ip)){
	 $ret=borrar_ip($borrar_ip,$dominio);
	 } 
if (isset($desde) and isset($hasta) and $desde<>"" and $hasta<>""){
     if (strstr($tipo,"mx")){
		 		//es mx lo que queremos agregar
				$rank=str_replace("mx","",$tipo);
				if ($desde=="@") $desde="";
	  		$ret = agregar_mx("mx",$desde,$hasta,$dominio,$rank);
		 }
		 else {
 				if ($desde=="@") $desde="";
		 		$ret= agregar_ip($tipo,$desde,$hasta,$dominio);	
		 }
}
if (!$ret){
header("location: paneladmin_adv.php?error=indeterminado"); //sirve para descartar tambien
}else {
header("location: paneladmin_adv.php"); //sirve para descartar tambien
}
//var_crudas($_POST);

?>