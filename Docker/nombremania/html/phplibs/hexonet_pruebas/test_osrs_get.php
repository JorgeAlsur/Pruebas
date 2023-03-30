<?

	/**
	
	JOSE 15-03-2010:
	Script que devuelve la fecha de
	expiracion de cada uno de los
	dominios que hay en opensrs.
	
	**/
include_once('conf.inc.php');
include("basededatos.php");
	require_once('osrsh/openSRS.php');
	$O=new openSRS('LIVE');
	/*
	$cmd=array(
                  'action' => "get_domain",
                  "object" => 'domain'
				  //,'attributes' => array('domain' => 'golfinspain.com')
					);
	*/
	$cmd=array(
					  'action' => 'get_domains_by_expiredate',
					'object' => 'domain',
					'attributes' => array(
						'exp_from' => '2005-01-01',
						'exp_to' => '2030-12-25'
						#,'page' => '1',
						,'limit' => '500'
						)
					);
	
	
	//*
	/*
	$cmd=array(
			   		'action' => 'get'
                  ,"object" => 'domain'
				  ,'attributes' => array('domain' => 'todopc.com'
										 , 'type' => 'all_info'
				
										 )
					);
	*/
	/*/*/
	$comando=$O->send_cmd($cmd);
	//echo "<pre>";print_r($comando);echo "</pre>";
	$dominios=$comando['attributes']['exp_domains'];
	
	echo "<pre>";print_r($dominios);echo "</pre>";
	
	/*
	foreach($dominios as $dominio)
	{
		$doms[$dominio['name']]=$dominio['expiredate'];
	}
	foreach($doms as $dom=>$expira)
	{
		// JOSE .. 17-03-2010
		$sql="UPDATE zonas_box SET hasta='$expira' WHERE dominio='$dom'"; // clausulas solo para ultima solicitud
		$conn->debug=true;
		$conn->execute($sql);
	}
	
	echo "<pre>";print_r($doms);echo "</pre>";
	*/
	
?>