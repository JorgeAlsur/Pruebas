<?
 if (isset($id_cliente) and $id_cliente!=""){
 header("Location: /clientes/control/panel.php");
exit;
}
 
?>
<HTML><!-- #BeginTemplate "/Templates/nm2_base.dwt" --><!-- DW6 -->
<HEAD>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<!-- #BeginEditable "doctitle" --> 
<TITLE>Programa de afiliados de NombreMania</TITLE>
<!-- #EndEditable --> 
<SCRIPT language="JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</SCRIPT>
<meta name="title" content="NombreMania - registro y traslado de dominios - en tus dominios solo mandas tu">
<meta name="description" content="Nunca fue tan facil registrar y trasladar dominios. En NombreMania ofrecemos registro de dominios al mejor precio, en castellano y con las herramientas mas potentes de administraci�n.">
<meta name="keywords" content="registro de dominios, registrar dominios, dominios, traslado, renovar, registro dominios espa�ol, dominios en castellano">
<meta name="language" content="Spanish">
<meta name="author" content="El Sur Existe s.l.">
<meta name="copyright" content="El Sur Existe s.l.">
<meta name="revisit-after" content="30 days">
<meta name="document-classification" content="Internet Services">
<meta name="document-rights" content="unlimited">
<meta name="document-type" content="Public">
<meta name="document-rating" content="General">
<meta name="Abstract" content="Nunca fue tan facil registrar y trasladar dominios. En NombreMania ofrecemos registro de dominios al mejor precio, en castellano y con las herramientas mas potentes de administraci�n.">
<meta name="Target" content="registro de dominios, registrar dominios, dominios, traslado, renovar, registro dominios espa�ol">
<meta http-equiv="Content-Language" content="ES">
<link rel="stylesheet" href="/Templates/nm2_base_registro.css" type="text/css">
</HEAD>
<body bgcolor="#ffcc00" onLoad="MM_preloadImages('/imagenes/base3/simbolo_f2.gif','/imagenes/base3/logo_f2.gif','/imagenes/base3/registrar_f2.gif','/imagenes/base3/trasladar_f2.gif','/imagenes/base3/administrar_f2.gif','/imagenes/base3/comprar_f2.gif','/imagenes/base3/alquiler_f2.gif','/imagenes/base3/demo_f2.gif','/imagenes/base3/precios_f2.gif','/imagenes/base3/afiliados_f2.gif','/imagenes/base3/ayuda_f2.gif','/imagenes/base3/whois_f2.gif')"><!-- #BeginLibraryItem "/Library/nm2_navegador.lbi" --><table border="0" cellpadding="0" cellspacing="0" width="740" align="center">
  <tr> 
    <td><img src="/imagenes/base3/spacer.gif" width="12" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="3" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="23" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="3" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="29" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="62" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="55" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="4" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="39" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="8" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="36" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="77" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="77" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="89" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="10" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="73" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="70" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="56" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="14" height="1" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="1" height="1" border="0"></td>
  </tr>
  <tr> 
    <td rowspan="2" bgcolor="#ffffff"><img src="/imagenes/base3/cortearriba.gif" width="12" height="14" border="0"></td>
    <td rowspan="5" colspan="2"><img name="simbolo" src="/imagenes/base3/simbolo.gif" width="26" height="42" border="0" alt="registro de dominios"></td>
    <td><img name="corteb_arriba" src="/imagenes/base3/corteb_arriba.gif" width="3" height="5" border="0" alt="registro de dominios"></td>
    <td colspan="3" bgcolor="#ffffff"><img src="/imagenes/base3/corteb_abajo.gif" width="146" height="5" border="0"></td>
    <td rowspan="2"><img name="cortearriba" src="/imagenes/base3/cortearriba.gif" width="4" height="14" border="0" alt="registro de dominios"></td>
    <td rowspan="2" colspan="11" bgcolor="#ffffff"><img src="/imagenes/base3/cortearriba.gif" width="549" height="14" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="1" height="5" border="0"></td>
  </tr>
  <tr> 
    <td rowspan="3" colspan="4"><a href="/home.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('simbolo','','/imagenes/base3/simbolo_f2.gif','logo','','/imagenes/base3/logo_f2.gif',1);" ><img name="logo" src="/imagenes/base3/logo.gif" width="149" height="27" border="0" alt="NombreMania - registro de dominios"></a></td>
    <td><img src="/imagenes/base3/spacer.gif" width="1" height="9" border="0"></td>
  </tr>
  <tr> 
    <td><img name="slice_r3_c1" src="/imagenes/base3/slice_r3_c1.gif" width="12" height="15" border="0" alt="registrar "></td>
    <td><img name="cortemedio" src="/imagenes/base3/cortemedio.gif" width="4" height="15" border="0" alt="registro de dominios"></td>
    <td colspan="3"><img name="slice_r3_c9" src="/imagenes/base3/cortemedio.gif" width="83" height="15" border="0" alt="registro de dominios"></td>
    <td><a href="/registrar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('registrar','','/imagenes/base3/registrar_f2.gif',1);" ><img name="registrar" src="/imagenes/base3/registrar.gif" width="77" height="15" border="0" alt="Registra uno o varios dominios"></a></td>
    <td><a href="/trasladar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('trasladar','','/imagenes/base3/trasladar_f2.gif',1);" ><img name="trasladar" src="/imagenes/base3/trasladar.gif" width="77" height="15" border="0" alt="Traslada tu dominio a NombreMania "></a></td>
    <td><a href="http://admin.nombremania.com" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('administrar','','/imagenes/base3/administrar_f2.gif',1);" ><img name="administrar" src="/imagenes/base3/administrar.gif" width="89" height="15" border="0" alt="Gestiona tus dominios"></a></td>
    <td><img name="slice_r3_c15" src="/imagenes/base3/cortemedio.gif" width="10" height="15" border="0" alt="registro de dominios"></td>
    <td><a href="/comprar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('comprar','','/imagenes/base3/comprar_f2.gif',1);" ><img name="comprar" src="/imagenes/base3/comprar.gif" width="73" height="15" border="0" alt="Dominios en venta"></a></td>
    <td><a href="/alquilar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('alquiler','','/imagenes/base3/alquiler_f2.gif',1);" ><img name="alquiler" src="/imagenes/base3/alquiler.gif" width="70" height="15" border="0" alt="Los dominios también se alquilan"></a></td>
    <td><a href="/demos/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('demo','','/imagenes/base3/demo_f2.gif',1);" ><img name="demo" src="/imagenes/base3/demo.gif" width="56" height="15" border="0" alt="Demos del sistema"></a></td>
    <td><img name="slice_r3_c19" src="/imagenes/base3/cortemedio.gif" width="14" height="15" border="0" alt="registro de dominios"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="1" height="15" border="0"></td>
  </tr>
  <tr> 
    <td rowspan="2" bgcolor="#ffffff"><img src="/imagenes/base3/corteabajo.gif" width="12" height="13" border="0" alt="dominios"></td>
    <td rowspan="2"><img name="corteabajo" src="/imagenes/base3/corteabajo.gif" width="4" height="13" border="0" alt="registro de dominios"></td>
    <td rowspan="2" colspan="11" bgcolor="#ffffff"><img src="/imagenes/base3/corteabajo.gif" width="549" height="13" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="1" height="3" border="0"></td>
  </tr>
  <tr> 
    <td><img name="corteb_abajo" src="/imagenes/base3/corteb_abajo.gif" width="3" height="10" border="0" alt="registro de dominios"></td>
    <td colspan="3" bgcolor="#ffffff"><img src="/imagenes/base3/corteb_abajo.gif" width="146" height="10" border="0"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="1" height="10" border="0"></td>
  </tr>
  <tr> 
    <td rowspan="2" bgcolor="#FFFFFF"><img name="iz" src="/imagenes/base3/iz.gif" width="12" height="16" border="0" alt="registro de dominios"></td>
    <td rowspan="2" colspan="4" bgcolor="#FFFFFF"><a href="/precios.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('precios','','/imagenes/base3/precios_f2.gif',1);" ><img name="precios" src="/imagenes/base3/precios.gif" width="58" height="16" border="0" alt="Precios y tarifas"></a></td>
    <td rowspan="2" bgcolor="#FFFFFF"><a href="/afiliados.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('afiliados','','/imagenes/base3/afiliados_f2.gif',1);" ><img name="afiliados" src="/imagenes/base3/afiliados.gif" width="62" height="16" border="0" alt="Programa de afiliados"></a></td>
    <td rowspan="2" bgcolor="#FFFFFF"><a href="/ayuda/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('ayuda','','/imagenes/base3/ayuda_f2.gif',1);" ><img name="ayuda" src="/imagenes/base3/ayuda.gif" width="55" height="16" border="0" alt="Ayuda y documentación"></a></td>
    <td rowspan="2" colspan="2" bgcolor="#FFFFFF"><a href="/whois/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('whois','','/imagenes/base3/whois_f2.gif',1);" ><img name="whois" src="/imagenes/base3/whois.gif" width="43" height="16" border="0" alt="Consulta nuestro Whois"></a></td>
    <td rowspan="2" bgcolor="#FFFFFF"><img name="slice_r6_c10" src="/imagenes/base3/slice_r6_c10.gif" width="8" height="16" border="0" alt="registro de dominios"></td>
    <td rowspan="2" colspan="8" bgcolor="#FFFFFF"><img name="Lineadeinfo" src="/imagenes/base3/spacer.gif" width="488" height="16" border="0" alt="registro de dominios"></td>
    <td rowspan="2" bgcolor="#FFFFFF"><img name="derecho" src="/imagenes/base3/derecho.gif" width="14" height="16" border="0" alt="registro de dominios"></td>
    <td><img src="/imagenes/base3/spacer.gif" width="1" height="12" border="0"></td>
  </tr>
  <tr> 
    <td><img src="/imagenes/base3/spacer.gif" width="1" height="4" border="0"></td>
  </tr>
</table><!-- #EndLibraryItem --><table bgcolor="#ffcc00" border="0" cellpadding="0" cellspacing="0" width="740" align="center">
  <!-- fwtable fwsrc="Nuevabase_izq_ok1.png" fwbase="nm2_base.gif" fwstyle="Dreamweaver" fwdocid = "1425088159" fwnested="0" -->
  <tr bgcolor="#FFFFFF"> 
    <td width="15"><img src="/imagenes/base2/slice_r4_c1.gif" width="15" height="1" border="0"></td>
    <td width="6"><img src="/imagenes/base2/spacer.gif" width="6" height="1" border="0"></td>
    <td width="8"><img src="/imagenes/base2/spacer.gif" width="8" height="1" border="0"></td>
    <td width="5"><img src="/imagenes/base2/spacer.gif" width="5" height="1" border="0"></td>
    <td width="4"><img src="/imagenes/base2/spacer.gif" width="4" height="1" border="0"></td>
    <td width="12"><img src="/imagenes/base2/spacer.gif" width="12" height="1" border="0"></td>
    <td width="9"><img src="/imagenes/base2/spacer.gif" width="9" height="1" border="0"></td>
    <td width="39"><img src="/imagenes/base2/spacer.gif" width="39" height="1" border="0"></td>
    <td width="9"><img src="/imagenes/base2/spacer.gif" width="9" height="1" border="0"></td>
    <td width="33"><img src="/imagenes/base2/spacer.gif" width="33" height="1" border="0"></td>
    <td width="10"><img src="/imagenes/base2/spacer.gif" width="10" height="1" border="0"></td>
    <td width="31"><img src="/imagenes/base2/spacer.gif" width="31" height="1" border="0"></td>
    <td width="120"><img src="/imagenes/base2/spacer.gif" width="120" height="1" border="0"></td>
    <td width="57"><img src="/imagenes/base2/spacer.gif" width="57" height="1" border="0"></td>
    <td width="15"><img src="/imagenes/base2/spacer.gif" width="15" height="1" border="0"></td>
    <td width="60"><img src="/imagenes/base2/spacer.gif" width="60" height="1" border="0"></td>
    <td width="12"><img src="/imagenes/base2/spacer.gif" width="12" height="1" border="0"></td>
    <td width="76"><img src="/imagenes/base2/spacer.gif" width="76" height="1" border="0"></td>
    <td width="25"><img src="/imagenes/base2/spacer.gif" width="25" height="1" border="0"></td>
    <td width="58"><img src="/imagenes/base2/spacer.gif" width="58" height="1" border="0"></td>
    <td width="10"><img src="/imagenes/base2/spacer.gif" width="10" height="1" border="0"></td>
    <td width="53"><img src="/imagenes/base2/spacer.gif" width="53" height="1" border="0"></td>
    <td width="18"><img src="/imagenes/base2/spacer.gif" width="18" height="1" border="0"></td>
    <td width="41"><img src="/imagenes/base2/spacer.gif" width="41" height="1" border="0"></td>
    <td width="14"><img src="/imagenes/base2/corteder.gif" width="14" height="1" border="0"></td>
    <td width="1"><img src="/imagenes/base2/spacer_naranja.gif" width="1" height="1" border="0"></td>
  </tr>
  <tr> 
    <td rowspan="3" height="150" bgcolor="#FFFFFF" background="/imagenes/base2/corteizq.gif" valign="bottom"><img name="esq_izq_abajo" src="/imagenes/base2/esq_izq_abajo.gif" width="15" height="21" border="0" alt="registro de dominios"></td>
    <td colspan="23" height="325" bgcolor="#FFFFFF" valign="top"><br>
	<!-- #BeginEditable "contenido" --> 
      <table width="710" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="180" valign="top" align="center"><br>
            <img src="/imagenes/perso_tarzan.gif" width="180" height="256"> 
            <table border="0" cellpadding="0" cellspacing="0" width="180">
              <tr> 
                <td width="9"><img name="izs" src="/imagenes/marco/izs.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/mea.gif"><img name="mea" src="/imagenes/marco/mea.gif" width="82" height="9" border="0"></td>
                <td width="9"><img name="des" src="/imagenes/marco/des.gif" width="9" height="9" border="0"></td>
              </tr>
              <tr> 
                <td background="/imagenes/marco/izm.gif" width="9"><img name="izm" src="/imagenes/marco/izm.gif" width="9" height="43" border="0"></td>
                <td valign="middle" align="center"> 
                  <!-- login de cliente -->
                  <?
  include $_SERVER['DOCUMENT_ROOT']."/clientes/logueado.php";
?>
                </td>
                <td background="/imagenes/marco/dem.gif" width="9"><img name="dem" src="/imagenes/marco/dem.gif" width="9" height="43" border="0"></td>
              </tr>
              <tr> 
                <td width="9"><img name="izb" src="/imagenes/marco/izb.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/meb.gif"><img name="meb" src="/imagenes/marco/meb.gif" width="82" height="9" border="0"></td>
                <td width="9"><img name="deb" src="/imagenes/marco/deb.gif" width="9" height="9" border="0"></td>
              </tr>
            </table>
            <p><br>
              <img src="imagenes/col180_arriba.gif" width="180" height="8"><b><font color="#990000"><span class="body_11"><br>
              Pon NombreMania <br>
              en t&uacute; p&aacute;gina. </span></font></b><br>
              Conoce algunos de los banners y buscadores a t&uacute; disposici&oacute;n.</p>
            <p><a href="/clientes/control/opciones_afiliado.php?demo=1">Pulsa 
              aqui...</a><img src="imagenes/col180_abajo.gif" width="180" height="9"><br>
            </p>
          </td>
          <td width="10" valign="top"><img src="/imagenes/base2/spacer.gif" width="10" height="8" border="0"></td>
          <td width="520" valign="top" class="body_10"><img src="/imagenes/titulo_afiliados.gif" width="520" height="32"> 
            <ul>
              <li> 
                <div align="left"><font color="#666666" face="Verdana, Arial, Helvetica, sans-serif" size="1"><span class="body_11">&iquest;Eres 
                  un profesional de internet con un <font color="#000066">volumen 
                  considerable de registros</font>?</span></font></div>
              </li>
              <li> 
                <div align="left" class="body_11"><font color="#666666" size="1" face="Verdana, Arial, Helvetica, sans-serif" class="body_11"> 
                  &iquest;Quieres beneficiarte de <font color="#000066">descuentos 
                  adicionales</font>?</font></div>
              </li>
              <li> 
                <div align="left" class="body_11"><font color="#666666" size="1" face="Verdana, Arial, Helvetica, sans-serif" class="body_11">&iquest;Quieres 
                  <font color="#000066">rentabilizar tu web</font> y ofrecer desde 
                  ella un servicio de registro a tus visitantes?</font></div>
              </li>
            </ul>
            <a href="/clientes/solicitud_alta.php"><img src="/imagenes/afiliate.gif" width="191" height="26" border="0"></a> 
            <p align="left"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="2" class="body_11">Con 
              nuestro programa de afiliados desde ahora puedes beneficiarte de 
              un <B><FONT color="#990000">descuento por tus registros </FONT>y 
              por <FONT color="#990000">los que nos remitas desde tu p&aacute;gina 
              web</FONT></B>.</FONT></p>
            <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font face="Base9" color="#000099" size="3"><img name="Comoclientedirecto0" src="imagenes/Comoclientedirecto0.gif" width="164" height="15" border="0" alt="Como cliente directo..."> 
              <!-- fwtable fwsrc="Comoclientedirecto0.png" fwbase="Comoclientedirecto0" fwstyle="Dreamweaver" fwdocid = "742308039" fwnested="0" -->
              <br>
              </font><font size="1">Si tienes un n&uacute;mero considerable de 
              dominios o registras habitualmente para tus clientes, puedes beneficiarte 
              de las m&uacute;ltiples ventajas:</font></font></p>
            <ul>
              <li><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><FONT color="#000066">Descuentos 
                especiales en tus registros</FONT></b><FONT color="#990000">.</FONT> 
                Registra o traslada tus dominios desde tu <A href="/demos/panelafiliados.html" target="new">panel 
                de cliente</A> y beneficiate de descuentos por volumen.</font></li>
              <li><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000066"><b>Mejores 
                formas de pago</b></FONT><font face="Verdana, Arial, Helvetica, sans-serif" size="1">. 
                Los clientes registrados pueden beneficiarse de formas de pago 
                alternativas, como el <b>prepago bonificado</b>, por el que dispondr&aacute;s 
                de tu saldo para registros sin necesidad de usar tarjetas o transferencias 
                obteniendo un descuento adicional.</font></li>
              <li><b><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000066">Extractos 
                de operaciones</FONT></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">. 
                Que te permitir&aacute; conocer tus transacciones y registros 
                abonados.</font></li>
              <li><b><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000066">Compra 
                y alquiler de dominios</FONT></b><font face="Verdana, Arial, Helvetica, sans-serif" size="1">. 
                </font></li>
              <li><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><FONT color="#000066">Acceso 
                a zonas restringidas</FONT></b> de la web.</font></li>
              <li><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b><FONT color="#000066">Informaci&oacute;n 
                prioritaria</FONT></b> de nuevos productos.</font></li>
            </ul>
            <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font face="Base9" color="#000099" size="3"><img src="imagenes/Comoafiliado0.gif" alt="Como afiliado.." width="170" height="15"><br>
              </font><font size="1">Un sistema para rentabilizar tu/s web/s incorporando 
              un <b>buscador de dominios o algunos de nuestros <a href="/clientes/control/opciones_afiliado.php?demo=1">banners</a></b> 
              promocionales. Incluyendo en tu p&aacute;gina el codigo HTML necesario 
              </font><font face="Verdana, Arial, Helvetica, sans-serif" size="1">(accesible 
              en tu <A href="/demos/panelafiliados.html" target="new">panel de 
              control</A>)</font> <font size="1"><b>todos los registros de tu 
              procedencia ser&aacute;n comisionables</b>. Sin programaci&oacute;n, 
              CGIs, ni instalaciones adicionales. <a href="/clientes/control/opciones_afiliado.php?demo=1">Aqu&iacute; 
              puedes ver</a> una muestra de algunos de los elementos promocionales 
              para afiliados que pondremos a tu disposici&oacute;n. </font></font><font face="Verdana, Arial, Helvetica, sans-serif" size="1">A 
              cada cliente que nos remitas se le grabar&aacute; una <b>&quot;cookie&quot; 
              por un periodo de 45 d&iacute;as </b>durante el cual quedar&aacute; 
              registrada a tu nombre la operaci&oacute;n.</font></p>
            <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><B><FONT color="#000066" size="2">COMISIONES 
              / DESCUENTOS</FONT></B><BR>
              Contabilizan tanto tus propios registros como aquellos que nos remitas 
              desde tu web. El margen se calcula en cada operaci&oacute;n seg&uacute;n 
              la cantidad total de dominios/a&ntilde;os (un dominio por 3 a&ntilde;os 
              cuenta como 3) ventas que realices o remitas en los <B>4 meses</B> 
              inmediatamente anteriores. </font></p>
            <table border="0" cellspacing="2" cellpadding="0" align="center">
              <tr align="center"> 
                <td width="180" bgcolor="#CCCCCC" class="td_form_valor2">N&uacute;mero 
                  de <br>
                  <B>dominios /a&ntilde;o </B></td>
                <td bgcolor="#FFFFFF" width="1">&nbsp;</td>
                <td width="120" bgcolor="#CCCCCC" class="td_form_valor2">Comisi&oacute;n<BR>
                  Descuento</td>
              </tr>
              <tr align="center"> 
                <td class="td_form_valor">0 a 10</td>
                <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
                <td class="td_form_valor"><B>5%</B></td>
              </tr>
              <tr align="center"> 
                <td height="8" class="td_form_valor">11 a 30</td>
                <td height="8" bgcolor="#FFFFFF" width="1">&nbsp;</td>
                <td height="8" class="td_form_valor"><B>7%</B></td>
              </tr>
              <tr align="center"> 
                <td class="td_form_valor">31 a 50</td>
                <td bgcolor="#FFFFFF" width="1">&nbsp;</td>
                <td class="td_form_valor"><B>9%</B></td>
              </tr>
              <tr align="center"> 
                <td class="td_form_valor">51 a 100</td>
                <td bgcolor="#FFFFFF" width="1">&nbsp;</td>
                <td class="td_form_valor"><B>10%</B></td>
              </tr>
              <tr align="center"> 
                <td class="td_form_valor">+100</td>
                <td bgcolor="#FFFFFF" width="1">&nbsp;</td>
                <td class="td_form_valor">
                  <SCRIPT language="JavaScript">
<!-- Begin
document.write('<a href=\"mailto:afiliados'+ '@' + 'nombremania.com?Subject=Afiliados%20-%20Descuentos%20por%20volumen\">');
document.write('consultar</a>');
// End -->

</SCRIPT>
                </td>
              </tr>
              <tr align="left" valign="bottom"> 
                <td class="body_9" colspan="3" height="16">*Importes v&aacute;lidos 
                  desde Feb. 2002, seg&uacute;n condiciones.</td>
              </tr>
            </table>
            <p align="left"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Los 
              pagos se realizar&aacute;n de forma mensual si la cantidad generada 
              a tu favor supera los 30 euros (5.000 Ptas). Aqu&iacute; puedes 
              ver las <B><A href="#" onClick="MM_openBrWindow('/legales/ventana_cond_afiliados.html','Condiciones','width=400,height=400')">condiciones 
              de afiliaci&oacute;n</A></B>.</FONT></p>
            <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><B><FONT color="#000066" size="2">GRANDES 
              VOLUMENES </FONT></B><BR>
              Tambi&eacute;n contamos con un completo sistema de registros personalizable 
              para <b>integrar en sites</b> que quieran ofrecer su propio servicio 
              de dominios. Si est&aacute;s interesado o tienes un importante <b>volumen 
              regular de registros</b> (+30 al mes) estaremos encantados de estudiar 
              ofertas y/o colaboraciones adaptadas a tus necesidades, asi como 
              precios m&aacute;s ajustados. No dudes en contactarnos en <a href="mailto:afiliados@nombremania.com">afiliados@nombremania.com</a></font></p>
            <p align="left"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font face="Base9" color="#000099" size="3"><b><img src="imagenes/Siquiero0.gif" alt="Si, quiero..."></b></font><br>
              <font size="1">Suscribirse a nuestro programa de afiliados es muy 
              <b>sencillo y gratuito</b>, rellena el <a href="/clientes/solicitud_alta.php">formulario</a> 
              y recibir&aacute;s las instrucciones necesarias para sacar provecho 
              de tu web y registrar tus propios dominios gratis.</font></font></p>
            <p align="center"><a href="/clientes/solicitud_alta.php"><img src="/imagenes/afiliate.gif" width="191" height="26" border="0"></a><br>
            </p>
          </td>
        </tr>
      </table>
      <br>
      <!-- #EndEditable --> </td>
    <td rowspan="3" height="150" bgcolor="#FFFFFF" background="/imagenes/base2/corteder.gif" valign="bottom"><img name="esq_der_abajo" src="/imagenes/base2/esq_der_abajo.gif" width="14" height="21" border="0" alt="registro de dominios"></td>
    <td width="1" height="150"><img src="/imagenes/base2/spacer.gif" width="1" height="8" border="0"></td>
  </tr>
  <tr> 
    <td colspan="23" bgcolor="#FFFFFF" valign="bottom"><!-- #BeginEditable "Pie de p%EF%BF%BDgina" -->&nbsp;<!-- #EndEditable --><!-- #BeginLibraryItem "/Library/nm2_pie_generico.lbi" --><table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td valign="middle"><p class="mini">Tus <a href="http://www.golfinspain.com/esp/?utm_source=nombremania&utm_medium=link&utm_content=footer&utm_campaign=sitios">viajes de golf</a> en GolfinSpain.com · <a href="http://alsur.es">hecho alsur </a></p></td>
    <td valign="middle" align="right">
      <table border="0" cellspacing="0" cellpadding="0" class="mini">
        <tr> 
          <td align="right"> <font face="Verdana, Arial" size="1" color="#666666"><span class="mini">[<a class="mini"  href="/registrar.php">registrar</a>] 
            [<a href="/trasladar.php">trasladar</a>] [<a href="http://admin.nombremania.com">administrar</a>] 
            [<a href="/ayuda/">ayuda</a>]</span></font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-32785580-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script><!-- #EndLibraryItem --></td>
    <td width="1"><img src="/imagenes/base2/spacer.gif" width="1" height="13" border="0"></td>
  </tr>
  <tr> 
    <td width="6"><img name="abajo1" src="/imagenes/base2/abajo1.gif" width="6" height="8" border="0" alt="registro de dominios"></td>
    <td width="8"><img name="abajo" src="/imagenes/base2/abajo.gif" width="8" height="8" border="0" alt="registro de dominios"></td>
    <td colspan="21"><img name="ko_abajo" src="/imagenes/base2/ko_abajo.gif" width="697" height="8" border="0" alt="registro de dominios"></td>
    <td width="1"><img src="/imagenes/base2/spacer.gif" width="1" height="8" border="0"></td>
  </tr>
</table>
</body>
<!-- #EndTemplate --></HTML>
