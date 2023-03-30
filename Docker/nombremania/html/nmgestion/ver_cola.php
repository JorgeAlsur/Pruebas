<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<?
include("estilo.css");
include("barra.php");
//ver  cola de procesamiento
include("basededatos.php");
?>
<div align=center>
<a href="/phplibs/procesator.php?procesa_cola=1" target=cola>Forzar el procesamiento de la cola</a>
<br>
&nbsp; Fecha en el server : <?=date("r");?> - se ejecuta en 0,10,20,30,40,50 minutos de cada hora
<br>
<a href="<?=$PHP_SELF?>?todo=1">mostrar todos</a>
</div>
<br>
<?
$limit="";
if(!isset($todo))$limit=" limit 0,50 ";
$campos="id,concat('<a href=\"ficha.php?id=',id_solicitud,'\">solicitud</a>'),dominio,DATE_FORMAT(fecha,'%d-%m-%Y %h:%i'),estado,intentos,cod_aprob,dns1,dns2,dns3,from_unixtime(procesar_fecha, '%Y-%m-%d') ,concat('<a href=\"borrar_cola.php?id=',id,'\">borrar</a>') ,
concat('<a href=modi_cola.php?id=',id,'>cambiar estado</a>')";
$ncampos="id|solicitud|dominios|fecha|estado|intentos|codigo|dns1|dns2|dns3|fecha_proceso|tarea|modifica";
$rs=$conn->execute("select $campos from cola order by estado,fecha DESC $limit");
//echo "select $campos from cola order by estado,fecha DESC $limit";exit;
rs2html($rs,"class=\"tabla\" align=\"center\"",explode("|",$ncampos),false);
?>
<p align="center">C&oacute;digos de Estado</p>
<table align="center" width="300">
<tr>
<td>
<!--
<ul>
<li>100 proceso terminado</li>
<li>1 alta en zoneedit</li>
<li>2 alta en opensrs</li>
<li>3 alta en registrados</li>
<li>77 en cola expired</li>
<li>91 falta zoneedit</li>
<li>90 error en opensrs</li>
<li>99 expired fallido</li>
</ul>
-->
<ul>
<li>100 proceso terminado</li>
<li>20 alta en opensrs</li>
<li>30 alta en registrados</li>
<li>40 alta en hexonet</li>
<li>41 alta en tabla de zona</li>
<li>66 zona para borrar</li>
<li>77 en cola expired</li>
<li>90 error en opensrs</li>
<li>91 error en hexonet</li>
<li>92 error en zona</li>
<li>94 error al borrar zona</li>
<li>99 expired fallido</li>
</ul>
</td>
</tr>
</table>