<?
  // toma la lista de operaciones y selecciona
function procesa_cron()
{
	global $conn;
	$DOCUMENT_ROOT = '/home/webs/nombremania.com/html';
	set_include_path(get_include_path() . PATH_SEPARATOR . $DOCUMENT_ROOT . '/phplibs' . PATH_SEPARATOR . '/home/webs/phplibs');
	include( $DOCUMENT_ROOT."/phplibs/basededatos.php");
	$sql="select  * from operaciones where estado_transfer=0 and reg_type=\"transfer\" ";
	$rs=$conn->execute($sql);
	if ($rs === false ) die("error de base de datos". mysql_error());
	require_once($DOCUMENT_ROOT."/phplibs/osrsh/openSRS.php");

	$_test_or_live=$tipo;
	include($DOCUMENT_ROOT."/phplibs/conf.inc.php");
	$O = new openSRS($_test_or_live);

	$dominios=array();
	$hoy=time();
	while(!$rs->EOF)
	{
		$dominio=$rs->fields["domain"];
		$cmd = array(
                  "action" => "check_transfer",
                  "object" => "domain",
                  "attributes" => array(
                                   "domain" => "$dominio",
				   "affiliate_id"=>"$afiliado"
                  )
                );
		$result = $O->send_cmd($cmd);
		$dias = intval( ($hoy - $rs->fields["fecha"])/ (24*60*60) ) ;
		$dominios[$dominio]=array("id"=>$rs->fields["id"],"dias"=> $dias, "estado"=>$result["attributes"]["status"]);
		$rs->movenext();
	}
	reset($dominios);
	foreach($dominios as $key => $dom)
	{
		if (strtolower($dom["estado"]) == "completed")
		{
			$sql="update operaciones set estado_transfer = 1 where id = ".$dom["id"] ;
			$rs2=$conn->execute($sql);
			echo $sql."<br>";
		}
		else
		{
			if($dias >=15)
			{
				if($dias==15)
				{
					mail("soporte@nombremania.com","despues de 15 dias no se ha completado un traslado","dominio $key" );
				}
				if($dias >= 30 )
				{
					mail("soporte@nombremania.com","cancelamos el traslado de $key","dominio $key" );
					mail("administracion@nombremania.com","cancelamos el traslado de $key"," errores en el traslado de dominio $key" );
					$sql="update set estado_transfer = 99 where id = ".$dom["id"];
					$rs2=$conn->execute($sql);
				}
			}
		}
	}
}
//var_crudas($dominios);
?>
