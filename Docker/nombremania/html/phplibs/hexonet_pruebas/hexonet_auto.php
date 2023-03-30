<?
include_once('conf.inc.php');
include("basededatos.php");
include('hexonet.php');

if($_GET['param']=='count')
{
	$sql="select count(*) as count from hexonet_altas";
	//echo $sql;
	$rs=$conn->execute($sql);
	echo $rs->fields['count'];
	exit;
}
elseif($_GET['param']=='insertar')
{
	if($_GET['n'])
	{
		$limit='LIMIT '.(($_GET['n']-1)*100).',100';
	}
	if($_GET['alsur']==1 || $_GET['ALSUR']==1)
	{
		$alsur="WHERE domain='alsur.es'";
	}

	/*if(!$_GET['parte']) $limit="LIMIT 15";
	if($_GET['parte'])
	{
		$limit = "LIMIT ".(($_GET['parte']-1)*15).",15";
	}
	*/
	
	//$sql="select * from hexonet_altas";
	
	//$sql="select * from hexonet_altas where domain='alsur.es'";
	$sql="select * from hexonet_altas";
	if($limit) $sql.=" $limit";
	if($alsur) $sql.=" $alsur";
	echo "<h1>$sql</h1>";
	//echo $sql;
	$dominio='';
	$rs=$conn->execute($sql);
	while(!$rs->EOF)
	{
		$existe=registros_zona('alsur.hexonet.net', $rs->fields['domain']);
		if($rs->fields['domain']!=$dominio)
		{
			$dominio=$rs->fields['domain'];
			$usuario='nombremania.com';
			agrega_zona($usuario,$dominio);
		}
		unset($destino);
		/*	si tipo A 			= dsnfrom=X&dnsto=Y
		si tipo CNAME 		= dnsfrom=X&dnsto=Y
		si tipo MX 			= dnsfrom=X&dnsto=Y&rank=Z
		si tipo PTR 		= dnsfrom=X&dnsto=Y
		si tipo TXT 		= dnsfrom=X&forward=Y
		si tipo MF 			= dnsto=X&forward=Y
		si tipo WF_FRAME 	= dnsfrom=X&dnsto=Y
		si tipo WF_REDIRECT = dnsfrom=X&dnsto=Y
		*/
		$tipo=$rs->fields['tipo'];
		switch($tipo)
		{
			case 'A':
			case 'CNAME':
			case 'PTR':
				$destino = 'dnsfrom='.$rs->fields['dnsfrom'].'&dnsto='.$rs->fields['dnsto'];
				break;
			case 'MX':
				$destino = 'dnsfrom='.$rs->fields['dnsfrom'].'&dnsto='.$rs->fields['dnsto'].'&rank='.$rs->fields['rank'];
				break;
			case 'WF_REDIRECT':
			case 'WF_FRAME':
			case 'TXT':
				$destino = 'dnsfrom='.$rs->fields['dnsfrom'].'&forward='.$rs->fields['forward'];
				break;
			case 'MF':
				$destino = 'dnsto='.$rs->fields['dnsto'].'&forward='.$rs->fields['forward'];
				break;
			
		}
		if($destino) 
		{
			agregar_registro($dominio, $rs->fields['tipo'], $destino);
		}
		$rs->movenext();
	}
}
else echo "NADA";

?>