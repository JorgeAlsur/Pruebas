<?

include("basededatos.php");
include("zoneedit.php");
if($_GET['prueba']=='yes')
{
	$sql="SELECT Zone FROM export_zoneedit where Zone NOT IN ('domaindemo.com', 'alsur.es', 'alsur.info', 'alsur.net', 'elsurexiste.com', 'juanperro.com', 'miguelangeljimenez.com', 'miguelangeljimenez.info', 'miguelangeljimenez.net', 'peterleonard.com', 'regaleundominio.com', 'regalaundominio.com', 'singlesfuengirola.com');";
	$rs=$conn->execute($sql);
	while(!$rs->EOF)
	{
		$zone=$rs->fields['Zone'];
		$command="command=deluser&user=$zone";
		$que=zoneedit($command);
		$rs->movenext();
	}
}


?>