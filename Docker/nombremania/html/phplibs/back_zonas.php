<?

$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];
set_time_limit(0);
include("zoneedit.php");
include("hachelib.php");
include("basededatos.php");

$usuario="todopc.com";
$dominio="todopc.com";


$sql="select id,dominio from zonas";
$rs=$conn->execute($sql);
if(!$rs)die("error de basededatos");

//$registros=registros_zona($usuario,$dominio);
include_once("xml.php");
include("conf.inc.php");

$i=0;
echo "<pre>\n";

$fech_archivo=date("Ymd");
$archivo=$dir_logs."/zonas-backup.txt";

$fp = fopen($archivo, "w");

while(!$rs->EOF)
{
	$i++;
	$usuario=$rs->fields["dominio"];
	$dominio=$rs->fields["dominio"];
	$id=$rs->fields["id"];
	$comando="command=ViewRecord&user=$usuario&zone=$dominio";
	$recibo=zoneedit($comando);
	$a="<!--  inicio -->\nid:\t$id  zona:\t$usuario \n";
	$a.=$recibo["xml"];
	$a.="\n<!-- final -->\n";
	echo htmlentities($a);
	fwrite($fp,$a);
	$rs->movenext();
}
fclose($fp);
echo "</pre>\n";
?>