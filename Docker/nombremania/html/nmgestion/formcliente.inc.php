<html><head>
<title>Clientes de nombremania</title>
<script language="JavaScript">
<!--
function MM_popupMsg(msg) { //v1.0
 alert(msg);
}
function MM_findObj(n, d) { //v3.0
 var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
   d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
 if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
 for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}
-->
</script>
</head>
<body>
<table bgcolor="#CCCCCC" align=Center>
<tr><td>Operaciones realizadas: </td>
<td>{cantidad_operaciones}</td>
</tr></table>

<div align="center"> <b>{HEADER} </b>
 <form method="POST" action="{ACTION}">
   <table border="0">
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">ID 
           interno:</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[id_interno]" value="{id_interno}" size="15">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Contacto:</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[contacto]" value="{contacto}" size="25">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Nombre</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[nombre]" value="{nombre}" size="25">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">DNI-NIF:</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[dni_nif]" value="{dni_nif}" size="25">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Direccion</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[direccion]" value="{direccion}" size="45">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Poblaci&oacute;n</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[poblacion]" value="{poblacion}" size="30">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Pa&iacute;s</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[pais]" value="{pais}" size="30">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Provincia:</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[provincia]" value="{provincia}" size="30">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">CP:</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[cp]" value="{cp}" size="15">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Fax:</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[fax]" value="{fax}" size="30">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Telefono</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[telefono]" value="{telefono}" size="30">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">EMail</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[email]" value="{email}" size="30">
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">nombre 
           de usuario web: <br>
           (8 caracteres)</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[usuario]" value="{usuario}" size="8" maxlength="8" onChange="MM_popupMsg('Ojo!!! al cambiar el nombre de usuario o password el cliente perder&aacute; su acceso hasta conocer estos nuevo datos.')">
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Clave</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <input type="text" name="form[clave]" value="{clave}" size="10" maxlength="10" onChange="MM_popupMsg('Ojo!!! al cambiar el nombre de usuario o password el cliente perder&aacute; su acceso hasta conocer estos nuevo datos.')">
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">ACTIVO</font></b></font></div>
       </td>
       <td bgcolor="#A0C5EB" width="210"> 
         <select name="form[activo]" size="1">
           <option value="1" {checked_si} selected>SI</option>
           <option value="0" {checked_no}>NO</option>
         </select>
       </td>
     </tr>
     <tr> 
       <td bgcolor="#CCCCCC" width="236"> 
         <div align="right"><font color="#000000"><b><font size="1" face="Verdana, Arial, Helvetica, sans-serif">URL</font></b></font></div>
       </td>
			 <td bgcolor="#A0C5EB" width="210">
			 <INPUT type=text name="form[URL]" value="{URL}">  
       </td>
     </tr>
		 
     <tr> 
       <td width="236"> 
         <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="1"><font size="1"><font color="#FFFFFF"></font></font></font></font></font></font></div>
       </td>
       <td width="210"> 
       <input type="submit" name="Submit" value=" ENVIAR " onClick="MM_validateForm('nombre','','R','dni_nif','','R','email','','RisEmail','usuario','','R','clave','','R');return document.MM_returnValue" >
         <input type=hidden name=tarea value={tarea}>
         <input type=hidden  name=id value={id}>
       </td>
     </tr>
		    </table>
				</form>
</div>				
			
<form name='formulario' method='post' action='/clientes/login.php'>

  <table border="0" cellspacing="0" cellpadding="2" width="300" bgcolor=white align=Center>
    <tr> 
      <td class="body_10"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Usuario</font></td>
      <td class="body_10"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Clave</font></td>
    </tr>
    <tr> 
      <td class="body_10"> 
        <input type=hidden name=volver value="/afiliados.php">
        <input type="hidden" name="action" value="login">
        <input type='text' name='nombre' size='8' maxlength=16 class="input9" value="{usuario}">
      </td>
      <td class="body_10"> <font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
        <input type='password' name='clave' size='8' maxlength=16 class="input9" VALUE="{clave}">
        </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td><a href="/afiliados.php"><img src="/imagenes/cliente_registrado.gif" width="67" height="26" border="0"></a> 
      </td>
      <td> 
        <input type="image" src='/imagenes/entrar.gif' align='middle' border='0' name='buscar2'>
      </td>
    </tr>
  </table>

</body>
</html>
