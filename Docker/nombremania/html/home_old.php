<?
if (isset($HTTP_COOKIE_VARS["id_cliente"])){
   $HTTP_GET_VARS["affiliate_id"]=$id_cliente ;
   $HTTP_POST_VARS["affiliate_id"]=$id_cliente;
   $affiliate_id=$id_cliente;
}
if ($HTTP_GET_VARS["affiliate_id"] or $HTTP_POST_VARS["affiliate_id"]){
  $expira=time()+(45*24*60*60);   //45 dias de vencimiento de la cookie de afiliado
  if (!setcookie("affiliate_id",$affiliate_id,$expira,"/",$SERVER_NAME)){
     header("Location: error.php?error=ESTE+SITIO+REQUIERE+COOKIES+PARA+FUNCIONAR");
     exit;
  }
}
?>
<HTML>
<HEAD>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<title>Nombremania - Registro y gestion de dominios</title>
<SCRIPT language="JavaScript">
<!--

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
<meta name="description" content="Nunca fue tan facil registrar y trasladar dominios. En NombreMania ofrecemos registro de dominios al mejor precio, en espaol y con las herramientas ms potentes de administraci†n.">
<meta name="keywords" content="registro de dominios, registrar dominios, dominios, traslado, renovar, registro dominios espaol">
<meta name="language" content="Spanish">
<meta name="author" content="AlSur">
<meta name="copyright" content="El Sur Existe s.l.">
<meta name="revisit-after" content="30 days">
<meta name="document-classification" content="Internet Services">
<meta name="document-rights" content="Copyrighted Work">
<meta name="document-type" content="Public">
<meta name="document-rating" content="General">
<meta name="Abstract" content="Nunca fue tan facil registrar y trasladar dominios. En NombreMania ofrecemos registro de dominios al mejor precio, en espaol y con las herramientas ms potentes de administraci†n.">
<meta name="Target" content="registro de dominios, registrar dominios, dominios, traslado, renovar, registro dominios espaol">
<meta http-equiv="Content-Language" content="ES">
<link rel="stylesheet" href="/Templates/nm2_base_registro.css" type="text/css">
</HEAD>
<body bgcolor="#ffcc00" onLoad="MM_preloadImages('/imagenes/base3/simbolo_f2.gif','/imagenes/base3/logo_f2.gif','/imagenes/base3/registrar_f2.gif','/imagenes/base3/trasladar_f2.gif','/imagenes/base3/administrar_f2.gif','/imagenes/base3/comprar_f2.gif','/imagenes/base3/alquiler_f2.gif','/imagenes/base3/demo_f2.gif','/imagenes/base3/precios_f2.gif','/imagenes/base3/afiliados_f2.gif','/imagenes/base3/ayuda_f2.gif','/imagenes/base3/whois_f2.gif')">
<!-- #BeginLibraryItem "/Library/nm2_navegador.lbi" --><TABLE border="0" cellpadding="0" cellspacing="0" width="740" align="center">
  <TR> 
    <TD><IMG src="/imagenes/base3/spacer.gif" width="12" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="3" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="23" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="3" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="29" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="62" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="55" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="4" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="39" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="8" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="36" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="77" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="77" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="89" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="10" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="73" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="70" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="56" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="14" height="1" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="1" height="1" border="0"></TD>
  </TR>
  <TR> 
    <TD rowspan="2" bgcolor="#ffffff"><IMG src="/imagenes/base3/cortearriba.gif" width="12" height="14" border="0"></TD>
    <TD rowspan="5" colspan="2"><IMG name="simbolo" src="/imagenes/base3/simbolo.gif" width="26" height="42" border="0" alt="registro de dominios"></TD>
    <TD><IMG name="corteb_arriba" src="/imagenes/base3/corteb_arriba.gif" width="3" height="5" border="0" alt="registro de dominios"></TD>
    <TD colspan="3" bgcolor="#ffffff"><IMG src="/imagenes/base3/corteb_abajo.gif" width="146" height="5" border="0"></TD>
    <TD rowspan="2"><IMG name="cortearriba" src="/imagenes/base3/cortearriba.gif" width="4" height="14" border="0" alt="registro de dominios"></TD>
    <TD rowspan="2" colspan="11" bgcolor="#ffffff"><IMG src="/imagenes/base3/cortearriba.gif" width="549" height="14" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="1" height="5" border="0"></TD>
  </TR>
  <TR> 
    <TD rowspan="3" colspan="4"><A href="/home.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('simbolo','','/imagenes/base3/simbolo_f2.gif','logo','','/imagenes/base3/logo_f2.gif',1);" ><IMG name="logo" src="/imagenes/base3/logo.gif" width="149" height="27" border="0" alt="NombreMania - registro de dominios"></A></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="1" height="9" border="0"></TD>
  </TR>
  <TR> 
    <TD><IMG name="slice_r3_c1" src="/imagenes/base3/slice_r3_c1.gif" width="12" height="15" border="0" alt="registrar "></TD>
    <TD><IMG name="cortemedio" src="/imagenes/base3/cortemedio.gif" width="4" height="15" border="0" alt="registro de dominios"></TD>
    <TD colspan="3"><IMG name="slice_r3_c9" src="/imagenes/base3/cortemedio.gif" width="83" height="15" border="0" alt="registro de dominios"></TD>
    <TD><A href="/registrar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('registrar','','/imagenes/base3/registrar_f2.gif',1);" ><IMG name="registrar" src="/imagenes/base3/registrar.gif" width="77" height="15" border="0" alt="Registra uno o varios dominios"></A></TD>
    <TD><A href="/trasladar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('trasladar','','/imagenes/base3/trasladar_f2.gif',1);" ><IMG name="trasladar" src="/imagenes/base3/trasladar.gif" width="77" height="15" border="0" alt="Traslada tu dominio a NombreMania "></A></TD>
    <TD><A href="http://admin.nombremania.com" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('administrar','','/imagenes/base3/administrar_f2.gif',1);" ><IMG name="administrar" src="/imagenes/base3/administrar.gif" width="89" height="15" border="0" alt="Gestiona tus dominios"></A></TD>
    <TD><IMG name="slice_r3_c15" src="/imagenes/base3/cortemedio.gif" width="10" height="15" border="0" alt="registro de dominios"></TD>
    <TD><A href="/comprar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('comprar','','/imagenes/base3/comprar_f2.gif',1);" ><IMG name="comprar" src="/imagenes/base3/comprar.gif" width="73" height="15" border="0" alt="Dominios en venta"></A></TD>
    <TD><A href="/alquilar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('alquiler','','/imagenes/base3/alquiler_f2.gif',1);" ><IMG name="alquiler" src="/imagenes/base3/alquiler.gif" width="70" height="15" border="0" alt="Los dominios tambiÈn se alquilan"></A></TD>
    <TD><A href="/demos/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('demo','','/imagenes/base3/demo_f2.gif',1);" ><IMG name="demo" src="/imagenes/base3/demo.gif" width="56" height="15" border="0" alt="Demos del sistema"></A></TD>
    <TD><IMG name="slice_r3_c19" src="/imagenes/base3/cortemedio.gif" width="14" height="15" border="0" alt="registro de dominios"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="1" height="15" border="0"></TD>
  </TR>
  <TR> 
    <TD rowspan="2" bgcolor="#ffffff"><IMG src="/imagenes/base3/corteabajo.gif" width="12" height="13" border="0" alt="dominios"></TD>
    <TD rowspan="2"><IMG name="corteabajo" src="/imagenes/base3/corteabajo.gif" width="4" height="13" border="0" alt="registro de dominios"></TD>
    <TD rowspan="2" colspan="11" bgcolor="#ffffff"><IMG src="/imagenes/base3/corteabajo.gif" width="549" height="13" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="1" height="3" border="0"></TD>
  </TR>
  <TR> 
    <TD><IMG name="corteb_abajo" src="/imagenes/base3/corteb_abajo.gif" width="3" height="10" border="0" alt="registro de dominios"></TD>
    <TD colspan="3" bgcolor="#ffffff"><IMG src="/imagenes/base3/corteb_abajo.gif" width="146" height="10" border="0"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="1" height="10" border="0"></TD>
  </TR>
  <TR> 
    <TD rowspan="2" bgcolor="#FFFFFF"><IMG name="iz" src="/imagenes/base3/iz.gif" width="12" height="16" border="0" alt="registro de dominios"></TD>
    <TD rowspan="2" colspan="4" bgcolor="#FFFFFF"><A href="/precios.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('precios','','/imagenes/base3/precios_f2.gif',1);" ><IMG name="precios" src="/imagenes/base3/precios.gif" width="58" height="16" border="0" alt="Precios y tarifas"></A></TD>
    <TD rowspan="2" bgcolor="#FFFFFF"><A href="/afiliados.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('afiliados','','/imagenes/base3/afiliados_f2.gif',1);" ><IMG name="afiliados" src="/imagenes/base3/afiliados.gif" width="62" height="16" border="0" alt="Programa de afiliados"></A></TD>
    <TD rowspan="2" bgcolor="#FFFFFF"><A href="/ayuda/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('ayuda','','/imagenes/base3/ayuda_f2.gif',1);" ><IMG name="ayuda" src="/imagenes/base3/ayuda.gif" width="55" height="16" border="0" alt="Ayuda y documentaciÛn"></A></TD>
    <TD rowspan="2" colspan="2" bgcolor="#FFFFFF"><A href="/whois/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('whois','','/imagenes/base3/whois_f2.gif',1);" ><IMG name="whois" src="/imagenes/base3/whois.gif" width="43" height="16" border="0" alt="Consulta nuestro Whois"></A></TD>
    <TD rowspan="2" bgcolor="#FFFFFF"><IMG name="slice_r6_c10" src="/imagenes/base3/slice_r6_c10.gif" width="8" height="16" border="0" alt="registro de dominios"></TD>
    <TD rowspan="2" colspan="8" bgcolor="#FFFFFF"><IMG name="Lineadeinfo" src="/imagenes/base3/spacer.gif" width="488" height="16" border="0" alt="registro de dominios"></TD>
    <TD rowspan="2" bgcolor="#FFFFFF"><IMG name="derecho" src="/imagenes/base3/derecho.gif" width="14" height="16" border="0" alt="registro de dominios"></TD>
    <TD><IMG src="/imagenes/base3/spacer.gif" width="1" height="12" border="0"></TD>
  </TR>
  <TR> 
    <TD><IMG src="/imagenes/base3/spacer.gif" width="1" height="4" border="0"></TD>
  </TR>
</TABLE><!-- #EndLibraryItem -->
<table bgcolor="#ffcc00" border="0" cellpadding="0" cellspacing="0" width="741" align="center">
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
    <td rowspan="3" height="150" bgcolor="#FFFFFF" background="/imagenes/base2/corteizq.gif" valign="bottom" width="15"><img name="esq_izq_abajo" src="/imagenes/base2/esq_izq_abajo.gif" width="15" height="21" border="0" alt="registro de dominios"></td>
    <td colspan="23" height="325" bgcolor="#FFFFFF" valign="top"> <br>
      <table width="711" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="125" valign="top"><img src="/imagenes/base3/spacer.gif" width="8" height="7"> 
            <table border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td> 
                  <table border="0" cellpadding="0" cellspacing="0" width="118">
                    <tr> 
                      <td colspan="4"><img name="cabe" src="imagenes/home/cabe.gif" width="118" height="38" border="0"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
                <td align="left" valign="top"> 
                  <table border="0" cellpadding="0" cellspacing="0" width="108">
                    <tr> 
                      <td><img name="ti" src="imagenes/home/ti.gif" width="6" height="6" border="0"></td>
                      <td background="imagenes/home/ti1.gif"><img name="ti1" src="imagenes/home/ti1.gif" width="96" height="6" border="0"></td>
                      <td><img name="ti3" src="imagenes/home/ti3.gif" width="6" height="6" border="0"></td>
                    </tr>
                    <tr> 
                      <td background="imagenes/home/ti8.gif"><img name="ti8" src="imagenes/home/ti8.gif" width="6" height="78" border="0"></td>
                      <td valign="top" class="ventajas"> 
                        <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#333333">&#149; 
                          El mejor precio<br>
                          <br>
                          <font color="#000033">&#149; Todo en castellano<br>
                          </font> <br>
                          &#149; Gesti&oacute;n f&aacute;cil y r&aacute;pida en 
                          un solo acceso<br>
                          <br>
                          <font color="#000033">&#149; Redirecci&oacute;n de correo 
                          y web</font><br>
                          <br>
                          &middot; Gesti&oacute;n avanzada de DNS</font></p>
                      </td>
                      <td background="imagenes/home/ti4.gif"><img name="ti4" src="imagenes/home/ti4.gif" width="6" height="78" border="0"></td>
                    </tr>
                    <tr> 
                      <td><img name="ti7" src="imagenes/home/ti7.gif" width="6" height="6" border="0"></td>
                      <td background="imagenes/home/ti6.gif"><img name="ti6" src="imagenes/home/ti6.gif" width="96" height="6" border="0"></td>
                      <td><img name="ti5" src="imagenes/home/ti5.gif" width="6" height="6" border="0"></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <BR>
            <BR>
            <HR color="orange" size="1" width="95%" align="left">
            Acceso a<BR>
            <A href="/renovar/" class="body_9"><IMG src="/imagenes/ico_link.gif" width="19" height="18" align="middle" border="0">RENOVACIONES</A> 
            <HR color="orange" size="1" width="95%" align="left">
            <IMG src="/imagenes/ico_up.gif" width="20" height="24" align="right" hspace="5">Conoce 
            las ventajas de nuestro nuevo <A href="/afiliados.php"><b>PROGRAMA 
            DE AFILIADOS</b></A> 
            <HR color="orange" size="1" width="95%" align="left">
            <IMG src="/iconweb/mas.gif" width="21" height="21" align="left">Descubre 
            nuestra <A href="/promos/dns_avanzada.php"><b>DNS AVANZADA</b></A> 
            - <B><FONT color="#990000">control absoluto sobre tu dominio </FONT></B> 
            <br>
            <br>
            <a href="mailto:?Subject=Registrate%20un%20dominio%20en%20NombreMania&body=Visita%20NombreMania%20en%20http://www.nombremania.com/home.php"><img src="/promos/recomiendanos.gif" width="120" height="76" border="0"></a> 
            <p>&nbsp;</p>
            <p>&nbsp;</p>
          </td>
          <td width="10" background="/imagenes/home/lineas.gif"><img name="separadoe2" src="/imagenes/home/spacer.gif" width="10" height="372" border="0" alt="registro de dominios"></td>
          <td rowspan="2" valign="top"> 
            <table border="0" cellpadding="0" cellspacing="0">
              <tr> 
                <td><img name="izs" src="/imagenes/marco/izs.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/mea.gif"><img name="mea" src="/imagenes/marco/mea.gif" width="100%" height="9" border="0"></td>
                <td><img name="des" src="/imagenes/marco/des.gif" width="9" height="9" border="0"></td>
              </tr>
              <tr> 
                <td background="/imagenes/marco/izm.gif"><img name="izm" src="/imagenes/marco/izm.gif" width="9" height="43" border="0"></td>
                <td valign='middle' align="left" bgcolor="#FFFFFF"> 
                  <form name='formulario' method='post' action='/dominator.php'>
                    <input type="hidden" name="affiliate_id" value="<? echo $affiliate_id?>">
                    <input type="hidden" name="action" value="lookup">
                    <input type='text' name='domain' size='25' maxlength=67>
                    <input type="image" src='/imagenes/buscar.gif' align='middle' border='0' name='buscar'>
                  </form>
                </td>
                <td background="/imagenes/marco/dem.gif"><img name="dem" src="/imagenes/marco/dem.gif" width="9" height="43" border="0"></td>
              </tr>
              <tr> 
                <td background="/imagenes/marco/izm.gif"><img name="izm" src="/imagenes/marco/izm.gif" width="9" height="43" border="0"></td>
                <td class="td_form_titulo"> 
                  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Introduce 
                    el nombre en min&uacute;sculas sin www. <br>
                    No olvides la extensi&oacute;n <b>.com .net .org .info .biz 
                    .tv .cc .es</b></font></p>
                  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Generador 
                    de nombres con la <a href="/registrar_avan.php">b&uacute;squeda 
                    avanzada </a></font> </p>
                </td>
                <td background="/imagenes/marco/dem.gif"><img name="dem" src="/imagenes/marco/dem.gif" width="9" height="43" border="0"></td>
              </tr>
              <tr> 
                <td><img name="izb" src="/imagenes/marco/izb.gif" width="9" height="9" border="0"></td>
                <td background="/imagenes/marco/meb.gif"><img name="meb" src="/imagenes/marco/meb.gif" width="100%" height="9" border="0"></td>
                <td><img name="deb" src="/imagenes/marco/deb.gif" width="9" height="9" border="0"></td>
              </tr>
            </table>
            <p><img src="/imagenes/titular_Homepage.gif" width="286" height="15" vspace="7"><br>
              <font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
              <?
              //selecciona la imagen que aparecera
              $imagenes=array("Napoleon.gif","Hippy.gif","Miss.gif","Roquero.gif");
              srand((double)microtime()*1000000);
              $imagen="imagenes/".$imagenes[rand(0,3)];
            ?>
              Nunca fue tan f&aacute;cil registrar y mantener dominios en internet. 
              <img src=<?=$imagen?> align="right" >NombreMan&iacute;a te permite 
              registrar y trasladar tus dominios y administrarlos con suma facilidad 
              y <b>a un precio sin competencia</b>. </font> </p>
            <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><span class="body_11"><font color="#990000">PARA 
              NAVEGANTES SIN EXPERIENCIA.... </font></span></b>registro de dominios 
              <b>r&aacute;pido y f&aacute;cil </b>con la opci&oacute;n de <b>usar 
              tu p&aacute;gina personal </b>o una de reserva autom&aacute;tica 
              y usar correo del tipo <b>minombre@midominio.com</b>. Administrar&aacute;s 
              tu dominio desde un panel de control via web.</font></p>
            <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><span class="body_11"><font color="#990000">PARA 
              PROFESIONALES....</font></span></b> Ahorro econ&oacute;mico con 
              nuestros programas para clientes registrados. Te <b>facilitamos</b> 
              tremendamente la <b>administraci&oacute;n de tus dominios</b>. Con 
              un solo acceso ya no tendr&aacute;s que repetir tus datos para hacer 
              registros y contar&aacute;s con una <b>herramienta r&aacute;pida 
              de administraci&oacute;n de TODOS desde un solo acceso</b>. Cambiar 
              contactos o entradas a las DNS es ahora cuesti&oacute;n de segundos. 
              Visita nuestra <a href="/demos/">demo</a> de administraci&oacute;n.</font></p>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td class="titu_secciones" height="20"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                  &nbsp;N O T I C I A S</font></td>
              </tr>
            </table>
            <table border="0" cellpadding="5" cellspacing="0" width="100%" bgcolor="#F3F3F3">
              <tr valign="top"> 
                <td class="body_10">
								 <p><B><FONT color="#000066">Nueva propuesta para Dominio .ES</FONT>.</B>
								El ministerio espa&ntilde;ol de Ciencia y Tecnolog&iacute;a ha publicado en su web 
								un  <a href="http://www.setsi.mcyt.es/reg_internet/notabplan.htm" target="_blank">borrador</a> del nuevo plan nacional de dominios .es. <br>
							El plan intenta flexibilizar la asignaci&oacute;n de dominios, creando los de tercer nivel que ya existen en muchos pa&iacute;ses, como empresa.com.es u organizaci&oacute;n.org.es. M&aacute;s informaci&oacute;n  
							<a href="http://www.mcyt.es/asp/ministerio_informa/prensa/np12-04-02.htm" target="_blank">aqu&iacute;</a>.
							</p>
                  <p><B><FONT color="#000066">.info Landrush2</FONT>.</B> Afilias 
                    (titular de la extensi&oacute;n .info ha anunciado el pr&oacute;ximo 
                    comienzo de la fase 2 de adjudicaci&oacute;n de dominios que 
                    por alg&uacute;n motivo fuer&oacute;n revocados de la fase 
                    inicial de adjudicaci&oacute;n, bien por ser incorrectos, 
                    bien por no ser marcas formal y legalmente registradas. La 
                    lista de estos dominios estar&aacute; disponible en breve 
                    y podr&aacute;n ser solicitados por cualquier persona o entidad 
                    (tengan o no la marca) por un periodo m&iacute;nimo de 2 a&ntilde;os. 
                    Como en la fase original (Sunrise) se realizar&aacute; un 
                    &#147;preregistro&#148; y una adjudicaci&oacute;n por sorteo 
                    de los nombres. NombreMania informar&aacute; a sus clientes 
                    registrados de los detalles pertinentes y dominios disponibles, 
                    pr&oacute;ximamente.</p>
                  <P><B><FONT color="#000066">CIBEROKUPAS</FONT></B>. Aumentan, 
                    seg&uacute;n la OMPI, un 100% las denuncias por 'ciberokupaci&oacute;n' 
                    de dominios. (&Aacute;rticulo de <A href="http://www.elmundo.es/navegante/2002/03/27/seguridad/1017225164.html" target="_blank">elmundo.es</A>)</P>
                  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">BIZ-mania</font></b>. 
                    Ya puedes registrar extensiones .biz, en tiempo real al mismo 
                    precio que los .com y .net. </font></p>
                  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">Nueva 
                    extensi&oacute;n .EU</font></b> Conoce algunos detalles de 
                    la propuesta para reglamentar el futuro dominio &quot;.eu&quot;. 
                    <a href="/articulos/dominios_eu_cremades.html">Leer art&iacute;culo...</a></font></p>
                  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066"><a href="/registrar.php">Dominios 
                    Multiling&uuml;es</a>. </font></b> Los dominios con &ntilde;, 
                    tildes, cedillas y demas caract&eacute;res extendidos ya se 
                    pueden registrar en NombreMania de forma normal, como el resto. 
                    Los antiguos han quedado renovado 6 meses m&aacute;s de forma 
                    gratuita. </font></p>
                  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">Nueva 
                    opci&oacute;n PRO avanzado en NombreMania</font></b>. Que 
                    permite una impresionante gesti&oacute;n integral de la DNS. 
                    Este producto es &iacute;deal para profesionales o para aquellos 
                    que hagan uso de opciones avanzadas y no quieran depender 
                    de su proveedor de hospedaje o ISP para la gesti&oacute;n 
                    de DNS. Permite redirecciones ilimitadas (max consumo de DNS 
                    200 MB), entradas IP, MX, CNAME, etc.</font></p>
                  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">Compra 
                    y Alquiler. </font></b>Los dominios tambi&eacute;n se alquilan 
                    y venden. Los dominios registrados en NombreMania.com pueden 
                    ponerse en venta y alquiler sin costo alguno.</font></p>
                  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><br>
                    </font></p>
                  <p>&nbsp;</p>
                </td>
              </tr>
            </table>
            <br>
          </td>
          <td width="10"><img src="/imagenes/home/spacer.gif" width="10" height="363" border="0"></td>
          <td valign="top" width="180"> 
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
  include $GLOBALS["DOCUMENT_ROOT"]."/clientes/logueado.php";
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
            <br>
            <br>
            <!-- #BeginLibraryItem "/Library/Banner180.lbi" --><!--banner NMcaja de phpads -->
<iframe src='http://linux.alsur.es/phpads/adframe.php?what=zone:8&target=new&refresh=25' framespacing='0' frameborder='no' scrolling='no' width='180' height='75'><script language='JavaScript' src='http://linux.alsur.es/phpads/adjs.php?what=zone:8&target=new&withText=0'></script><noscript><a href='http://linux.alsur.es/phpads/adclick.php' target='new'><img src='http://linux.alsur.es/phpads/adview.php?what=zone:8&target=new&withText=0' border='0'></a></noscript></iframe>
<!-- #EndLibraryItem --><br>
            <hr>
            <!-- #BeginLibraryItem "/Library/banner180_ 2x.lbi" --><!--banner NMcajadoble de phpads -->
<iframe src='http://linux.alsur.es/phpads/adframe.php?what=zone:5&target=new&refresh=25' framespacing='0' frameborder='no' scrolling='no' width='180' height='150'><script language='JavaScript' src='http://linux.alsur.es/phpads/adjs.php?what=zone:5&target=new&withText=0'></script><noscript><a href='http://linux.alsur.es/phpads/adclick.php' target='new'><img src='http://linux.alsur.es/phpads/adview.php?what=zone:5&target=new&withText=0' border='0'></a></noscript></iframe>
<!-- #EndLibraryItem -->
            <hr>
            <DIV align="center"><a href="precios.php">
              <img src="imagenes/precios_home.gif" width="168" height="95" border="0"></a> 
            </DIV>
          </td>
        </tr>
        <tr> 
          <td width="125" valign="bottom"><font face="Verdana, Arial, Helvetica, sans-serif" class="mini" size="1"><br>
            Este sitio ha sido testeado solo para versiones superiores a 4.5 de 
            Netscape y Explorer.</font></td>
          <td width="10" background="/imagenes/home/lineas.gif">&nbsp;</td>
          <td width="10">&nbsp;</td>
          <td valign="bottom" width="180"> 
            <table width="30" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="2" height="30"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" class="body_9">Pasarela 
                  sumistrada por:</font></td>
              </tr>
              <tr> 
                <td><img src="/imagenes/logo_popular2peq.gif" width="103" height="24" border="0" vspace="1"></td>
                <td valign="bottom"><img src="/imagenes/tarjetas.gif" width="58" height="13" border="0" hspace="4"></td>
              </tr>
            </table>
            <a href="https://www.entrust.net/customer/profile.cfm?ID=000DD210-F5F0-1B84-93BB80A9B0ACFF6C-14502" target="new"><br>
            <img src="/imagenes/home/servidor_seguro.gif" width="117" height="13" border="0" vspace="1" align="bottom"></a></td>
        </tr>
        <tr> 
          <td width="125" valign="top">&nbsp;</td>
          <td width="10" background="/imagenes/home/lineas.gif">&nbsp;</td>
          <td width="385" valign="top">&nbsp;</td>
          <td width="10">&nbsp;</td>
          <td width="180" valign="top">&nbsp;</td>
        </tr>
      </table>
    </td>
    <td rowspan="3" height="150" bgcolor="#FFFFFF" background="/imagenes/base2/corteder.gif" valign="bottom" width="14"><img name="esq_der_abajo" src="/imagenes/base2/esq_der_abajo.gif" width="14" height="21" border="0" alt="registro de dominios"></td>
    <td width="1" height="150"><img src="/imagenes/base2/spacer.gif" width="1" height="8" border="0"></td>
  </tr>
  <tr> 
    <td colspan="23" bgcolor="#FFFFFF" valign="bottom">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td valign="middle" height="14"><img src="/imagenes/desarrollalsur.gif" width="83" height="9"><a href="http://www.elsurexiste.com" target="alsur"><img src="/imagenes/deelsurexiste.gif" width="202" height="9" border="0" alt="el sur existe"></a> 
          </td>
          <td valign="middle" align="right" height="14">&nbsp; </td>
        </tr>
      </table>
    </td>
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
</HTML>
