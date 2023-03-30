<?

require "hexonet.php";

if($_GET['prueba']=='hexonet') 
{
	$registros=borrar_zona('nombremania.com', 'astudillo.info');
	//borrar_registro('golfinspain.com',
	if($registros) echo "si";
	else echo "no";
	//print_r($registros);
}

?>