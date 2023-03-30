<?php

	include_once("hexonet.php");
	include_once("basededatos.php");
	
	$conn->debug=true;
$op = $_GET['op'];
if($op=='hxn'){
	$comando=array("command" => "QueryDNSZoneList");
	$recibo=hexonet($comando);
	$code=$recibo['code'];
	if($code=="200")
	{
		$zonas = $recibo['xml']->PROPERTY->DNSZONE;
		$rs1=$conn->execute("truncate table hexonet_zonas");
		//for($i=0;$i<count($zonas);$i++){
		//$zonas = array('alsur.com.');
		$zonas_validas=array();
		foreach($zonas as $zona){
			//echo count($zonas);flush();
			//$zona = $zonas[$i];
			echo "<b>$zona</b><br>";
			
			$zonas_validas[]=$zona;
			
			if(preg_match('/\.$/', $zona)){ $zona=substr($zona, 0, -1);}
			//if($i==0){
				$registros=registros_zona('alsur.hexonet.net', $zona, true, true);
				 print_r($registros);
				for($r=0;$r<count($registros);$r++){
					$tipo=$registros[$r]['tipo'];
					$dnsto=$registros[$r]['dnsto'];
					$forward=$registros[$r]['forward'];
					$dnsfrom=$registros[$r]['dnsfrom'];
					$disimulada=$registros[$r]['disimulada'];
					$rank=$registros[$r]['rank'];
					$rr=$registros[$r]['rr'];
					
					
					$rs2=$conn->execute("insert into hexonet_zonas (domain, tipo, dnsto, forward, dnsfrom, disimulada, rank, rr) values('$zona', '$tipo', '$dnsto', '$forward', '$dnsfrom', '$disimulada', '$rank', '$rr')");
				}
				//echo "<pre>";print_r($registros);echo "</pre>";
			//}
		}
		mail_debug('DNS Hexonet', print_r($zonas_validas, true));
		echo "Acabado...";
	}
	else {
                echo 'DNS Hexonet','Error en la recuperacion de dns de Hexonet'.print_r($recibo,true);
		mail_debug('DNS Hexonet','Error en la recuperacion de dns de Hexonet'.print_r($recibo,true));
		exit;
	}
}
else echo "Falta algo...";
exit;

function mail_debug($subject,$cadena)
{
	$debug_email='sistemas@alsur.es';
	$debug_subject='Debug System '.$subject;

	mail($debug_email,$debug_subject,$cadena,"From: $debug_subject <$debug_email>\nContent-Type: text/html");
}

?>
