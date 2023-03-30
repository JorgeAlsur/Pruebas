<?

include('basededatos.php');
include_once('conf.inc.php');

/*

JOSE 15-03-2010:

Funcion que mueve las zonas
a una tabla historica y borra
de la tabla original, si ha cumplido
al menos 10 dias desde que caduco.

*/

function barrido($borrar_hexonet=false)
{
	global $conn;
	$sql="select id, id_solicitud, dominio from zonas where hasta <= (CURDATE() - INTERVAL 30 DAY)";
	$conn->debug=true;
	$rs=$conn->execute($sql);
	if($rs===false)
	{
		loguear("No hay zonas que borrar");
	}
	else
	{
		while(!$rs->EOF)
		{
			$id=$rs->fields['id'];
			$id_solicitud=$rs->fields['id_solicitud'];
			$dominio=$rs->fields['dominio'];
                        
                        $sql = "select * FROM zonas where hasta > (CURDATE() - INTERVAL 30 DAY) AND dominio = '$dominio'";
                        $count_zonas = $conn->execute($sql);
                        if($count_zonas->_numOfRows == 0){
			$sql="insert into cola (id_solicitud,dominio,fecha,estado,intentos,cod_aprob,en_proceso)               values ($id_solicitud,'$dominio',NOW(),'66','0','nm-borrar','0')";
			$conn->execute($sql);
                        }
			$rs->movenext();
		}
	}
}
	
	if($_GET['barrido']==1) barrido();



?>
