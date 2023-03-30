<?
if($_SERVER['REMOTE_ADDR']<>'213.96.190.180')
{
	echo 'Error, denegado.';
	exit;
}
include('basededatos.php');

if (isset($id)){
$sql="select SESSKEY,DATA  from sessions  WHERE SESSKEY='$id'";
$conn->debug=true;
$rs=$conn->execute($sql);
include('hachelib.php');
print urldecode($rs->fields['DATA']);
$a=session_decode(urldecode($rs->fields['DATA']));
var_dump($a);
var_crudas($registrando);
}
else
{
	echo 'Conexion';
	include('hachelib.php');
	var_crudas($conn);

	$sql="select SESSKEY, UNIX_TIMESTAMP()-EXPIRY as segundos , concat(\"<a href=$PHP_SELF?id=\",SESSKEY,\">datos</a>\") as otro from sessions order by segundos";
	$conn->debug=true;
	$rs=$conn->execute($sql);
	rs2html($rs);
}
?>
