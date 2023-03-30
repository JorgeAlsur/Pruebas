<?
//borra registro de la cola de proceso
include "basededatos.php";

$rs=$conn->execute("delete from cola where id=$id");
if ($rs2===false){
echo "Error en  el borrado id = $id, vuelva y reintente";
}
else {
header("Location: ver_cola.php");
}
?>
