<?
include "../estilo.css";
include "barra.php";
//ver  cola de procesamiento
include "basededatos.php";

$ncampos="mes|cantidad|tipo";
$rs=$conn->execute("select month(from_unixtime(fecha)) as mes,count(*) as count ,tipo from operaciones group by month(from_unixtime(fecha)),tipo");
rs2html($rs,"class=tabla align=center",explode("|",$ncampos),false);


?>
<center>
<img src=graph.php>
</center>
