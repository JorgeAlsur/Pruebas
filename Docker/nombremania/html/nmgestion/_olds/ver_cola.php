<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<?

include "estilo.css";

include "barra.php";

//ver  cola de procesamiento

include "basededatos.php";

?>

<div align=center>

<a href="/phplibs/procesator.php?procesa_cola=1" target=cola>Forzar el procesamiento de la cola</a>

</div>

<br>

<?

$campos="id,concat('<a href=ficha.php?id=',id_solicitud,'>solicitud</a>'),dominio,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i'),estado,intentos,cod_aprob,dns1,dns2,from_unixtime(procesar_fecha) ,concat('<a href=borrar_cola.php?id=',id,'>borrar</a>') ";

$ncampos="id|solicitud|dominios|fecha|estado|intentos|codigo|dns1|dns2|fecha_proceso|tarea";

$rs=$conn->execute("select $campos from cola order by fecha DESC");

rs2html($rs,"class=tabla align=center",explode("|",$ncampos));

?>

<p align=center>C&oacute;digos de Estado</p>

<table align=center width=300>

<tr>

<td>

<ul>

<li>100 proceso terminado</li>

<li>1 alta en zoneedit</li>

<li>2 alta en opensrs</li>

<li>3 alta en registrados</li>

<li>91 falta zoneedit</li>

<li>90 error en opensrs</li>

</ul>

</td>

</tr>

</table>
