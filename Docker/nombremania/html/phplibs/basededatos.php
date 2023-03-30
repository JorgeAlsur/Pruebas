<?

mb_internal_encoding('UTF-8');

if(!defined("basededatos_ado"))
{
	define("basededatos_ado","cargada");
	include("adodb/adodb.inc.php");
	include("adodb/tohtml.inc.php");
}
$__host="localhost";
$__usuario="nombremania";
$__clave="MySQL_Nombremania";
$__bddatos="nombremania";
ADOLoadCode("mysql");
$conn = &ADONewConnection();  // create a connection
if(!$conn->Connect($__host,$__usuario,$__clave,$__bddatos))
{
	//cambio a connect en vez de pconnect
	die("Error de base de datos reintente en unos minutos");
}
$sql="set names 'utf8';";
$conn->execute($sql);

?>