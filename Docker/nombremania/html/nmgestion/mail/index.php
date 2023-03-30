<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1">
<title>Nombremania - Registro y gestion de dominios</title>
<style type="text/css">
<!--
tbody {font-family: Tahoma,Verdana; font-size: 12px ; }
.par {background-color: #9999ff;}
.impar {background-color: #ccccff;}
.bordes {border-style:solid;border-color:black ;border-width: thin; background-color: #9999ff;}
-->
</style>


<body bgcolor="#ffffff" >
<?
include "conf_email.inc.php";
include "hachelib.php";

?>

<script language="JavaScript" type="text/javascript">
<!--
function ver_sqls(){
var to1 = document.nmemails.to.options[document.nmemails.to.selectedIndex].value ;
window.open('ver_sqls.php?ver='+to1, 'Sample', 'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=500,height=500');
}
// -->
</script>

<FORM  name="nmemails" method="post" target="_new" ACTION=enviar.php>
<table summary="center" align=Center width=700  class="bordes" >
<tr>
<th colspan="1" class="impar">Envio de Emails a Clientes</th>

<td><a href="#" onclick="window.open('ayuda.html', 'Sample', 'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=250,height=250');"><img src="/imagenes/ico_ayuda.gif" width="20" border=0 height="20" alt="ayuda.gif (1K)"></a></td>
<tr>
<td align="right">From:</td>
<td>
nombre: <input type="text" name="from_nombre" value="registros" size=50><br>
email: <input type="text" name="from" value="registros@nombremania.com" size=50>
</td>
</tr><tr class="impar">
<td align="right">To:</td>
<td><select name="to">
<? echo form_select($destinos,"to","",true); ?> 			 
</select>
<a href="#" onclick="ver_sqls();">Ver DATOS</a>
</td>
</tr><tr>
<td align="right">Tema:</td>
<td ><input type="text" name="subject" value=""></td>
</tr>
<tr class="impar">
<td align="center" colspan="2">Texto del mensaje: </td></tr>
<tr>
<td align="center" colspan="2"><textarea rows="10" cols="72" name="texto_mensaje">
</textarea>  </td></tr>
<tr class="impar">
<td align="center" colspan="2" >Texto del mensaje (html): </td></tr>
<tr>
<td align="center" colspan="2">
<IMG onclick="window.open('http://linux.alsur.es/demos/_editor.php?formu=nm&amp;campo=htexto','test','width=450,height=500,top=150,left=150,toolbar=yes,status=yes,scrollbars=yes');"
 height=16 alt="edit1.gif (1K)" src="http://www.codigophp.com/iconweb/edit1.gif" width=16 value="Editor Html"> 
<textarea rows="10" cols="72" name="texto_mensaje_html"></textarea></td></tr>


<tr class="impar"><td align="right">
Enviar a este como prueba: </td><td><input name=test_nombre type=text value="correo_at_alsur"> &nbsp;
<input type=text name="test_email" value="correo@alsur.es		" >
<input type=submit name="test" value="test" >
<br>
</td>
</tr>
<tr> <td></td><td  class="par" align="right" >
<input type="submit" name="enviar" onclick="return confirm('Seguro de enviar la lista '+document.nmemails.to.value+ ' ???');">
</td>
</tr>

</table>
</form>
</body>
</HTML>
