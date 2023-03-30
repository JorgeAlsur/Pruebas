<?
  if (!isset($id_cliente) and !isset($demo)){
    echo "Falta el ID de cliente";
  exit();
    }
    else {
    if ($demo==1){
    $id_cliente="XXX";
    }
    }
if (isset($id_cliente) and $id_cliente!="XXX"){
  include "basededatos.php";
  $rs=$conn->execute("select * from clientes where id=$id_cliente");
  if ($rs===false or $conn->Affected_Rows()==0){
  echo "Error de id de cliente. ";
  exit;
  }
  else {
  $nombre=$rs->Fields("nombre");
  }
                       }
  ?>
<HTML><!-- #BeginTemplate "/Templates/nm2_solophp.dwt" --><!-- DW6 -->
<HEAD>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<!-- #BeginEditable "doctitle" --> 
<TITLE>Banners para afiliados</TITLE>
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
<meta name="description" content="Nunca fue tan facil registrar y trasladar dominios. En NombreMania ofrecemos registro de dominios al mejor precio, en espa�ol y con las herramientas m�s potentes de administraci�n.">
<meta name="keywords" content="registro de dominios, registrar dominios, dominios, traslado, renovar, registro dominios espa�ol">
<meta name="language" content="Spanish">
<meta name="author" content="AlSur">
<meta name="copyright" content="El Sur Existe s.l.">
<meta name="revisit-after" content="30 days">
<meta name="document-classification" content="Internet Services">
<meta name="document-rights" content="Copyrighted Work">
<meta name="document-type" content="Public">
<meta name="document-rating" content="General">
<meta name="Abstract" content="Nunca fue tan facil registrar y trasladar dominios. En NombreMania ofrecemos registro de dominios al mejor precio, en espa�ol y con las herramientas m�s potentes de administraci�n.">
<meta name="Target" content="registro de dominios, registrar dominios, dominios, traslado, renovar, registro dominios espa�ol">
<meta http-equiv="Content-Language" content="ES">
<link rel="stylesheet" href="/Templates/nm2_base_registro.css" type="text/css">
</HEAD>
<body bgcolor="#ffcc00" onLoad="MM_preloadImages('/imagenes/base2/simbolo_f2.gif','/imagenes/base2/logo_f2.gif','/imagenes/base2/registrar_f2.gif','/imagenes/base2/Lineadeinfo_f3.gif','/imagenes/base2/trasladar_f2.gif','/imagenes/base2/Lineadeinfo_f4.gif','/imagenes/base2/administrar_f2.gif','/imagenes/base2/Lineadeinfo_f6.gif','/imagenes/base2/comprar_f2.gif','/imagenes/base2/Lineadeinfo_f5.gif','/imagenes/base2/alquiler_f2.gif','/imagenes/base2/Lineadeinfo_f8.gif','/imagenes/base2/demo_f2.gif','/imagenes/base2/Lineadeinfo_f7.gif','/imagenes/base2/precios_f2.gif','/imagenes/base2/afiliados_f2.gif','/imagenes/base2/ayuda_f2.gif','/imagenes/base2/whois_f2.gif','/imagenes/base3/simbolo_f2.gif','/imagenes/base3/logo_f2.gif','/imagenes/base3/registrar_f2.gif','/imagenes/base3/trasladar_f2.gif','/imagenes/base3/administrar_f2.gif','/imagenes/base3/comprar_f2.gif','/imagenes/base3/alquiler_f2.gif','/imagenes/base3/demo_f2.gif','/imagenes/base3/precios_f2.gif','/imagenes/base3/afiliados_f2.gif','/imagenes/base3/ayuda_f2.gif','/imagenes/base3/whois_f2.gif')"><!-- #BeginLibraryItem "/Library/nm2_navegador.lbi" --><table border="0" cellpadding="0" cellspacing="0" width="740" align="center">
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
    <td colspan="23" height="150" bgcolor="#FFFFFF" valign="top"><br>
	<!-- #BeginEditable "contenido" --> 
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><br>
        </b></font></p>
      <table border=0 width="520%" align="center" cellpadding="0" cellspacing="0">
        <tr> 
          <td colspan="3"><img src="/imagenes/titular_planafiliados.gif" width="520" height="32"></td>
        </tr>
      </table>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1" class="body_10">Como 
        cliente registrado, puedes participar autom&aacute;ticamente en nuestro 
        programa de afiliados y beneficiarte de un descuento adicional remitiendonos 
        clientes desde tu/s p&aacute;ginas web. <b>Es muy f&aacute;cil</b> y a 
        continuaci&oacute;n tienes algunas de las opciones que ponemos a tu disposci&oacute;n. 
        Por cada diez dominios/a&ntilde;os registrados por clientes enviados por 
        ti <b>te abonamos en tu cuenta el importe de un dominio por un a&ntilde;o</b> 
        (valido solo para registro com,net,org y seg&uacute;n)(Los 10 primeros 
        dominios/a&ntilde;os registrados no se contabilizar&aacute;n).</font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><font color="#000099"><span class="titu_secciones">1.- 
        INCORPORA UN BUSCADOR DE DOMINIOS A TUS P&Aacute;GINAS:</span></font></b></font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> <font size="1" class="body_10">Te 
        ofrecemos varias opciones para que incluyas el c&oacute;digo que mejor 
        se adapta a tus necesidades. Copia y pega el codigo dentro de t&uacute; 
        p&aacute;gina tal cual, <b>ya incluye tu numero de afiliado</b>:</font></font></p>
      <table width="690" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td align="center" width="300"> 
            <table border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td><img name="izs" src="/imagenes/marco/izs.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/mea.gif"><img name="mea" src="/imagenes/marco/mea.gif" width="82" height="9" border="0"></td>
                <td><img name="des" src="/imagenes/marco/des.gif" width="9" height="9" border="0"></td>
              </tr>
              <tr> 
                <td background="/imagenes/marco/izm.gif" width="9"><img name="izm" src="/imagenes/marco/izm.gif" width="9" height="43" border="0"></td>
                <td> 
                  <form name='formulario' method='post' action='/dominator.php'>
                    <table border='0' cellspacing='0' cellpadding='2' align="right">
                      <tr> 
                        <td><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Buscar 
                          nuevo dominio</font></td>
                      </tr>
                      <tr> 
                        <td valign='middle' align="left" class="body_10"> 
                          <input type="hidden" name="affiliate_id" value="<? echo $affiliate_id?>">
                          <input type="hidden" name="action" value="lookup">
                          <input type='text' name='domain' size='20' maxlength=67>
                          <input type="image" src='/imagenes/buscar.gif' align='middle' border='0' name='buscar2' width="68" height="26">
                        </td>
                      </tr>
                    </table>
                  </form>
                </td>
                <td background="/imagenes/marco/dem.gif"><img name="dem" src="/imagenes/marco/dem.gif" width="9" height="43" border="0"></td>
              </tr>
              <tr> 
                <td><img name="izb" src="/imagenes/marco/izb.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/meb.gif"><img name="meb" src="/imagenes/marco/meb.gif" width="82" height="9" border="0"></td>
                <td><img name="deb" src="/imagenes/marco/deb.gif" width="9" height="9" border="0"></td>
              </tr>
            </table>
          </td>
          <td align="center" class="body_10" background="/imagenes/punto.gif"> 
            <form name="codigo1" >
              <br>
              <textarea name="elcodigo" cols="35" rows="8">&lt;table width=&quot;300&quot; border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;
&lt;tr align=&quot;right&quot;&gt;&lt;td bgcolor=&quot;#425A7B&quot;&gt;
&lt;font face='Verdana, Arial, Helvetica, sans-serif' color=&quot;#FFFFFF&quot; size='1'&gt;&lt;b&gt;
Registra tu dominio con NombreMania.com
&lt;/b&gt;&lt;/font&gt;&lt;/td&gt; &lt;/tr&gt;
&lt;tr align=&quot;right&quot; bgcolor=&quot;#FFCE00&quot; valign=&quot;middle&quot;&gt;
&lt;td&gt;&lt;form method=&quot;post&quot; action=&quot;http://www.nombremania.com/dominator.php&quot;&gt;
&lt;input type=&quot;hidden&quot; name=&quot;affiliate_id&quot; value=&quot;<?=$id_cliente;?>&quot;&gt;
&lt;input type=&quot;hidden&quot; name=&quot;action&quot; value=&quot;lookup&quot;&gt;
&lt;input type=&quot;text&quot; name=&quot;domain&quot;&gt;
&lt;input type=&quot;submit&quot; name=&quot;Submit&quot; value=&quot;Buscar&quot;&gt;
&lt;/form&gt;&lt;/td&gt;&lt;/tr&gt;&lt;tr align=&quot;right&quot; bgcolor=&quot;#FFFFFF&quot; valign=&quot;middle&quot;&gt;
&lt;td&gt;&lt;img src=&quot;http://www.nombremania.com/imagenes/logochico.gif&quot; width=&quot;102&quot; height=&quot;20&quot;&gt;
&lt;/td&gt;&lt;/tr&gt;&lt;tr align=&quot;right&quot; bgcolor=&quot;#FFCE00&quot;&gt;
&lt;td&gt;&amp;nbsp;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;</textarea>
            </form>
          </td>
        </tr>
        <tr> 
          <td align="center" colspan="2"> 
            <hr width="100%" color="orange">
          </td>
        </tr>
      </table>
      <table width="690" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td align="center" width="300"> 
            <table border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td><img name="izs" src="/imagenes/marco/izs.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/mea.gif"><img name="mea" src="/imagenes/marco/mea.gif" width="82" height="9" border="0"></td>
                <td><img name="des" src="/imagenes/marco/des.gif" width="9" height="9" border="0"></td>
              </tr>
              <tr> 
                <td background="/imagenes/marco/izm.gif" width="9"><img name="izm" src="/imagenes/marco/izm.gif" width="9" height="43" border="0"></td>
                <td> 
                  <form name='formulario' method='post' action='/dominator.php'>
                    <table border='0' cellspacing='0' cellpadding='2' align="right">
                      <tr> 
                        <td align="center"><img src="/imagenes/logochico.gif" width="102" height="16"></td>
                      </tr>
                      <tr> 
                        <td align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Buscar 
                          nuevo dominio</font></td>
                      </tr>
                      <tr> 
                        <td valign='middle' align="center" class="body_10"> 
                          <input type="hidden" name="affiliate_id" value="<? echo $affiliate_id?>">
                          <input type="hidden" name="action" value="lookup">
                          <input type='text' name='domain' size='20' maxlength=67>
                          <br>
                          <br>
                          <input type="image" src='/imagenes/buscar.gif' align='middle' border='0' name='buscar2' width="68" height="26">
                        </td>
                      </tr>
                    </table>
                  </form>
                </td>
                <td background="/imagenes/marco/dem.gif"><img name="dem" src="/imagenes/marco/dem.gif" width="9" height="43" border="0"></td>
              </tr>
              <tr> 
                <td><img name="izb" src="/imagenes/marco/izb.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/meb.gif"><img name="meb" src="/imagenes/marco/meb.gif" width="82" height="9" border="0"></td>
                <td><img name="deb" src="/imagenes/marco/deb.gif" width="9" height="9" border="0"></td>
              </tr>
            </table>
          </td>
          <td align="center" class="body_10" background="/imagenes/punto.gif"> 
            <form name="codigo2" >
              <br>
              <textarea name="elcodigo" cols="35" rows="8">&lt;table border=&quot;0&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot;&gt;
&lt;tr align=&quot;right&quot; bgcolor=&quot;#425A7B&quot;&gt;
&lt;td&gt;&lt;font face='Verdana, Arial, Helvetica, sans-serif' color=&quot;#FFFFFF&quot; size='1'&gt;&lt;b&gt;Registra tu dominio&lt;/b&gt;&lt;/font&gt;&lt;/td&gt;&lt;/tr&gt;&lt;tr align=&quot;right&quot; bgcolor=&quot;#FFCE00&quot;&gt;
&lt;td&gt;&lt;form method=&quot;post&quot; action=&quot;http://www.nombremania.com/dominator.php&quot;&gt;
&lt;input type=&quot;hidden&quot; name=&quot;affiliate_id&quot; value=&quot;<?=$id_cliente; ?>&quot;&gt;
&lt;input type=&quot;hidden&quot; name=&quot;action&quot; value=&quot;lookup&quot;&gt;
&lt;input type=&quot;text&quot; name=&quot;domain&quot;&gt;
&lt;input type=&quot;submit&quot; name=&quot;Submit2&quot; value=&quot;Buscar&quot;&gt;
&lt;/form&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;</textarea>
            </form>
          </td>
        </tr>
        <tr> 
          <td align="center" colspan="2"> 
            <hr width="100%" color="orange">
          </td>
        </tr>
        <tr> 
          <td align="center" width="300"> 
            <table border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td><img name="izs" src="/imagenes/marco/izs.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/mea.gif"><img name="mea" src="/imagenes/marco/mea.gif" width="82" height="9" border="0"></td>
                <td><img name="des" src="/imagenes/marco/des.gif" width="9" height="9" border="0"></td>
              </tr>
              <tr> 
                <td background="/imagenes/marco/izm.gif" width="9"><img name="izm" src="/imagenes/marco/izm.gif" width="9" height="43" border="0"></td>
                <td> 
                  <form name='formulario' method='post' action='/dominator.php'>
                    <table border='0' cellspacing='0' cellpadding='2'>
                      <tr> 
                        <td align="center"><font color="#000099" style='font-size:9px; font-family:Verdana'>Registra 
                          tu dominio:</font></td>
                      </tr>
                      <tr> 
                        <td valign='middle' align="center" class="body_10"> 
                          <input type="hidden" name="affiliate_id" value="<? echo $affiliate_id?>">
                          <input type="hidden" name="action" value="lookup">
                          <input type='text' name='domain' size='10' maxlength=67 style='font-size:10px; font-family:Verdana'>
                          <input type="image" src='/imagenes/buscarpeque.gif' align='middle' border='0' name='buscar'>
                          <br>
                          <img src="/imagenes/logomaschico.gif" width="61" height="10" align="left" hspace="5"> 
                        </td>
                      </tr>
                    </table>
                  </form>
                </td>
                <td background="/imagenes/marco/dem.gif"><img name="dem" src="/imagenes/marco/dem.gif" width="9" height="43" border="0"></td>
              </tr>
              <tr> 
                <td><img name="izb" src="/imagenes/marco/izb.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/meb.gif"><img name="meb" src="/imagenes/marco/meb.gif" width="82" height="9" border="0"></td>
                <td><img name="deb" src="/imagenes/marco/deb.gif" width="9" height="9" border="0"></td>
              </tr>
            </table>
          </td>
          <td align="center" class="body_10" background="/imagenes/punto.gif"> 
            <TEXTAREA name="elcodigo" cols="35" rows="8" wrap="VIRTUAL">&lt;form name='formulario' method=post action='/dominator.php'&gt;&lt;table border=0 cellspacing=0 cellpadding=2&gt;&lt;tr&gt;&lt;td align=center&gt;&lt;font color="#000099" style='font-size:9px; font-family:Verdana'&gt;Registra tu dominio:&lt;/font&gt;&lt;/td&gt;&lt;/tr&gt;&lt;tr&gt;&lt;td valign=middle align=center class=body_10&gt;&lt;input type=hidden name="affiliate_id" value="&lt;? echo $affiliate_id?&gt;"&gt; &lt;input type=hidden name="action" value=lookup&gt; &lt;input type=text name='domain' size=10 maxlength=67 style='font-size:10px; font-family:Verdana'&gt; &lt;input type=image src='/imagenes/buscarpeque.gif' align=middle border=0 name='buscar'&gt;&lt;br&gt;&lt;img src="/imagenes/logomaschico.gif" width=61 height=10 align=left hspace=5&gt;&lt;/td&gt;&lt;/tr&gt;&lt;/table&gt;&lt;/form&gt;</TEXTAREA>
          </td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000099"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="titu_secciones">2.- 
        USA ALGUNO DE NUESTROS BANNERS EN TUS P&Aacute;GINAS</span></b></font></b></font></p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><span class="body_10">Escoge 
        el banner que m&aacute;s te guste, col&oacute;calo en tu p&aacute;gina 
        y vinc&uacute;lalo a:</span></font></p>
      <p class="body_10"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font color="#CC0000" size="3"><b>http://www.nombremania.com/home.php?affiliate_id= 
        <?=$id_cliente;?>
        </b></font><font color="#CC0000"> <font size="1"><br>
        </font> </font></font><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><span class="body_10">Todos 
        los registros que se hagan a trav&eacute;s de &eacute;l, se contabilizar&aacute;n 
        a tu nombre.</span></font> </p>
      <p align="center"><a href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><img src="/imagenes/nombremania_banner_10.gif" width="125" height="60" border="0" hspace="15"></a></p>
      <p align="center"><a href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><img src="/imagenes/nombremania_banner_01.gif" width="468" height="60" border="0"></a> 
      </p>
      <p align="center"><a href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><img src="/imagenes/nombremania_banner_02.gif" width="468" height="60" border="0"></a></p>
      <p align="center"><a href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><img src="/imagenes/nombremania_banner_03.gif" width="468" height="60" border="0"></a></p>
      <p align="center"><a href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><img src="/promos/banners/renuevate2.gif" width="468" height="60" border="0"></a></p>
      <p align="center"><a href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><img src="/imagenes/nombremania_banner_05.gif" width="468" height="60" border="0"></a></p>
      <p align="center"><A href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><IMG src="/promos/banners/adsl_sinlimites.gif" width="468" height="60" border="0"></A></p>
      <p align="center">&nbsp;</p>
      <p align="center">&nbsp;</p>
      <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><span class="body_10">Para 
        la promoci&oacute;n de REGALEUNDOMINIO.COM utiliza este codigo y alguno 
        de los banners de abajo:</span></font></p>
      <p class="body_10"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><font color="#CC0000" size="3"><b>http://www.regaleundominio.com/index.php?affiliate_id= 
        <?=$id_cliente;?>
        </b></font><font color="#CC0000"> <font size="1"><br>
        </font> </font></font><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><span class="body_10">Todos 
        los registros que se hagan a trav&eacute;s de &eacute;l, se contabilizar&aacute;n 
        a tu nombre.</span></font> </p>
      <p class="body_10" align="center"><a href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><img src="/imagenes/nombremania_banner_07.gif" width="180" height="106" border="0" hspace="15"><img src="/imagenes/nombremania_banner_08.gif" width="180" height="56" border="0" hspace="15"></a></p>
      <p align="center"><a href="http://www.nombremania.com/index.php?affiliate_id=<?=$id_cliente;?>"><img src="/imagenes/nombremania_banner_06.gif" width="468" height="60" border="0"></a></p>
      <p align="center">&nbsp;</p>
      <p></p>
      <!-- #EndEditable -->
    </td>
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
