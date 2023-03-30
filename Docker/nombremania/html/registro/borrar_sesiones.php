<?php
include "basededatos.php";
$sql="delete from sessions ";
$conn->debug=true;
$rs=$conn->execute($sql);

echo "BORRADAS TODAS LAS SESIONES";

?>
