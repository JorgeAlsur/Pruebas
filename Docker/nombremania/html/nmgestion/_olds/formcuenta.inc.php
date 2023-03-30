<form action={action} method=post>
<table class=tabla align=center>
<tr>
<td>Importe: (123.33)</td><td><input type=text name=form[importe] value={importe}></td>
<tr>
<td>Tipo:</td><td><select name=form[tipo]><option value=credito>credito <OPTION name=debito>debito
</select>
</td>
</tr>
<tr>
<td>Fecha: (dd/mm/aaaa)</td>
<td><input type=text MAXLENGTH=10 SIZE=10 VALUE="{fecha}" NAME=form[fecha] ></td>
</tr>
<tr>
<td>Observacion: (200) </td>
<td><input type=text MAXLENGTH=200 SIZE=60 name=form[observacion] VALUE={observacion}></td>
</tr>

<input TYPE=hidden name=form[id_cliente] value={id_cliente}><tr><td colspan=2><input type=submit value=enviar></td></tr>

<input type=hidden name=tarea value={tarea}>
</form>

</tr>
</table>