<html><head>
<link rel="stylesheet" type="text/css" href="estilo.css">
</head><body>
<?
include "barra.php"; include "basededatos.php"; include "hachelib.php";
?>
<table width="700" border="0" cellspacing="0" cellpadding="2" align="center">
   <tr>
     <td width="210" bgcolor="#000033" align="center">
       <div align="center"><font color="#FFFFFF"><b><font size="2">CLIENTES distintos</font></b></font></div>
     </td>
		 </tr></table>
<?
$sql="select reg_username,count(*) as operacs from operaciones group by reg_username order by operacs DESC";
$campos_tit="username:operaciones realizadas";
$rs=$conn->execute($sql);

rs2html($rs,"class=tabla border=1 align=Center",$campo_tit,false);


?>