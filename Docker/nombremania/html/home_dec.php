<?
if (isset($_COOKIE["id_cliente"])){
   $_GET["affiliate_id"]=$id_cliente ;
   $_POST["affiliate_id"]=$id_cliente;
   $affiliate_id=$id_cliente;
}
if ($_GET["affiliate_id"] or $_POST["affiliate_id"]){
  $expira=time()+(45*24*60*60);   //45 dias de vencimiento de la cookie de afiliado
  if (!setcookie("affiliate_id",$affiliate_id,$expira,"/",$SERVER_NAME)){
     header("Location: error.php?error=ESTE+SITIO+REQUIERE+COOKIES+PARA+FUNCIONAR");
     exit;
  }
}
?>
<html>
<head>
<TITLE>NombreMania. Registro de dominios, gesti&oacute;n y traslado mejor precio.</TITLE>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<script language="JavaScript">
<!--
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
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
//-->
</script>
<meta name="title" content="NombreMania - registro y traslado de dominios - en tus dominios solo mandas tu">
<meta name="description" content="Nunca fue tan facil registrar y trasladar dominios. En NombreMania ofrecemos registro de dominios al mejor precio, en espaol y con las herramientas ms potentes de administracin.">
<meta name="keywords" content="registro de dominios, registrar dominios, dominios, traslado, renovar, registro dominios espaol">
<meta name="language" content="Spanish">
<meta name="author" content="AlSur">
<meta name="copyright" content="El Sur Existe s.l.">
<meta name="revisit-after" content="30 days">
<meta name="document-classification" content="Internet Services">
<meta name="document-rights" content="Copyrighted Work">
<meta name="document-type" content="Public">
<meta name="document-rating" content="General">
<meta name="Abstract" content="Nunca fue tan facil registrar y trasladar dominios. En NombreMania ofrecemos registro de dominios al mejor precio, en espaol y con las herramientas ms potentes de administracin.">
<meta name="Target" content="registro de dominios, registrar dominios, dominios, traslado, renovar, registro dominios espaol">
<meta http-equiv="Content-Language" content="ES">
<link rel="stylesheet" href="/Templates/nm2_base_registro.css" type="text/css">
</head>
<body bgcolor="#ffcc00" onLoad="MM_preloadImages('/imagenes/home/simbolo_f2.gif','/imagenes/home/logo_f2.gif','/imagenes/home/registrar_f2.gif','/imagenes/home/trasladar_f2.gif','/imagenes/home/Administrar_f2.gif','/imagenes/home/comprar_f2.gif','/imagenes/home/alquilar_f2.gif','/imagenes/home/demos_f2.gif','/imagenes/home/precios_f2.gif','/imagenes/home/afiliados_f2.gif','/imagenes/home/ayuda_f2.gif','/imagenes/home/whois_f2.gif','/imagenes/home/visor_f2.gif','/imagenes/home/visor_f3.gif','/imagenes/home/visor_f4.gif','/imagenes/home/visor_f5.gif','/imagenes/home/visor_f6.gif','/imagenes/home/visor_f7.gif')">
<div align=center>
  <table border="0" cellpadding="0" cellspacing="0" width="742">
    <tr> 
      <td width="12"><img src="/imagenes/home/spacer.gif" width="12" height="1" border="0"></td>
      <td width="2"><img src="/imagenes/home/spacer.gif" width="2" height="1" border="0"></td>
      <td width="1"><img src="/imagenes/home/spacer.gif" width="1" height="1" border="0"></td>
      <td width="6"><img src="/imagenes/home/spacer.gif" width="6" height="1" border="0"></td>
      <td width="8"><img src="/imagenes/home/spacer.gif" width="8" height="1" border="0"></td>
      <td width="9"><img src="/imagenes/home/spacer.gif" width="9" height="1" border="0"></td>
      <td width="102"><img src="/imagenes/home/spacer.gif" width="102" height="1" border="0"></td>
      <td width="10"><img src="/imagenes/home/spacer.gif" width="10" height="1" border="0"></td>
      <td width="37"><img src="/imagenes/home/spacer.gif" width="37" height="1" border="0"></td>
      <td width="262"><img src="/imagenes/home/spacer.gif" width="262" height="1" border="0"></td>
      <td width="86"><img src="/imagenes/home/spacer.gif" width="86" height="1" border="0"></td>
      <td width="10"><img src="/imagenes/home/spacer.gif" width="10" height="1" border="0"></td>
      <td width="6"><img src="/imagenes/home/spacer.gif" width="6" height="1" border="0"></td>
      <td width="153"><img src="/imagenes/home/spacer.gif" width="153" height="1" border="0"></td>
      <td width="21"><img src="/imagenes/home/spacer.gif" width="21" height="1" border="0"></td>
      <td width="1"><img src="/imagenes/home/spacer.gif" width="1" height="1" border="0"></td>
      <td width="14"><img src="/imagenes/home/spacer.gif" width="14" height="1" border="0"></td>
      <td width="1"><img src="/imagenes/home/spacer.gif" width="1" height="1" border="0"></td>
      <td width="1"></td>
    </tr>
    <tr> 
      <td rowspan="3" background="/imagenes/home/tirasu_de.gif"><img name="tirasu_de" src="/imagenes/home/tirasu_de.gif" width="12" height="42" border="0" alt="registro de dominios"></td>
      <td rowspan="3" colspan="5" background="/imagenes/home/tirasu_de.gif"><img name="simbolo" src="/imagenes/home/simbolo.gif" width="26" height="42" border="0" alt="registro de dominios"></td>
      <td colspan="3" background="/imagenes/home/tira.gif"><img name="slice_r1_c7" src="/imagenes/home/tira.gif" width="149" height="5" border="0" alt="registro de dominios"></td>
      <td rowspan="3" colspan="4" background="/imagenes/home/tirasu_de.gif"><img name="tirasu_de" src="/imagenes/home/tirasu_de.gif" width="364" height="42" border="0" alt="registro de dominios"></td>
      <td rowspan="3" background="/imagenes/home/tirasu_de.gif"><img name="cuestion" src="/imagenes/home/cuestion.gif" width="153" height="42" border="0" alt="registro de dominios"></td>
      <td rowspan="3" background="/imagenes/home/tirasu_de.gif"><img name="tirasu_de" src="/imagenes/home/tirasu_de.gif" width="21" height="42" border="0" alt="registro de dominios"></td>
      <td rowspan="3" colspan="2"><img name="tirasu_de" src="/imagenes/home/tirasu_de.gif" width="15" height="42" border="0" alt="registro de dominios"></td>
      <td><img src="/imagenes/home/spacer.gif" width="1" height="5" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td colspan="3" background="/imagenes/home/tirasu_de.gif"><a href="#" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('simbolo','','/imagenes/home/simbolo_f2.gif','logo','','/imagenes/home/logo_f2.gif',1);" ><img name="logo" src="/imagenes/home/logo.gif" width="149" height="27" border="0" alt="registro de dominios"></a></td>
      <td><img src="/imagenes/home/spacer.gif" width="1" height="27" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td colspan="3" background="/imagenes/home/tira2.gif" height="6"><img name="slice_r3_c7" src="/imagenes/home/tira2.gif" width="149" height="10" border="0" alt="registro de dominios"></td>
      <td height="6"><img src="/imagenes/home/spacer.gif" width="1" height="10" border="0"></td>
      <td height="6"></td>
    </tr>
    <tr> 
      <td colspan="10" bgcolor="#FFFFFF"><img name="visor" src="/imagenes/home/visor.gif" width="449" height="13" border="0" alt="registro de dominios"></td>
      <td colspan="5" bgcolor="#FFFFFF"><img src="/imagenes/home/spacer.gif" width="276" height="13" border="0"></td>
      <td colspan="2"><img name="corteder" src="/imagenes/home/corteder.gif" width="15" height="13" border="0" alt="registro de dominios"></td>
      <td><img src="/imagenes/home/spacer.gif" width="1" height="13" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td colspan="2"><img name="slice_r5_c1" src="/imagenes/home/slice_r5_c1.gif" width="14" height="13" border="0" alt="registro de dominios"></td>
      <td colspan="13" bgcolor="#FFFFFF"><img src="/imagenes/home/spacer.gif" width="711" height="13" border="0"></td>
      <td colspan="2"><img name="corteder" src="/imagenes/home/corteder.gif" width="15" height="13" border="0" alt="registro de dominios"></td>
      <td><img src="/imagenes/home/spacer.gif" width="1" height="13" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td colspan="7" rowspan="11" valign="top" bgcolor="#FFFFFF"> 
        <table border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td colspan="7"><a href="registrar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('visor','','/imagenes/home/visor_f2.gif','registrar','','/imagenes/home/registrar_f2.gif',1)" ><img name="registrar" src="/imagenes/home/registrar.gif" width="140" height="15" border="0" alt="Registrar"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="trasladar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('visor','','/imagenes/home/visor_f3.gif','trasladar','','/imagenes/home/trasladar_f2.gif',1)" ><img name="trasladar" src="/imagenes/home/trasladar.gif" width="140" height="15" border="0" alt="Trasladar"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="http://admin.nombremania.com" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('visor','','/imagenes/home/visor_f4.gif','Administrar','','/imagenes/home/Administrar_f2.gif',1)" ><img name="Administrar" src="/imagenes/home/Administrar.gif" width="140" height="15" border="0" alt="Administrar"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="comprar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('visor','','/imagenes/home/visor_f5.gif','comprar','','/imagenes/home/comprar_f2.gif',1)" ><img name="comprar" src="/imagenes/home/comprar.gif" width="140" height="15" border="0" alt="Comprar"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="alquilar.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('visor','','/imagenes/home/visor_f6.gif','alquilar','','/imagenes/home/alquilar_f2.gif',1)" ><img name="alquilar" src="/imagenes/home/alquilar.gif" width="140" height="15" border="0" alt="Alquilar"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="/demos/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('visor','','/imagenes/home/visor_f7.gif','demos','','/imagenes/home/demos_f2.gif',1)" ><img name="demos" src="/imagenes/home/demos.gif" width="140" height="15" border="0" alt="Demos"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><img name="spacio2" src="/imagenes/home/spacio2.gif" width="140" height="12" border="0" alt="registro de dominios"></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="precios.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('precios','','/imagenes/home/precios_f2.gif',1);" ><img name="precios" src="/imagenes/home/precios.gif" width="140" height="15" border="0" alt="Precios"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="afiliados.php" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('afiliados','','/imagenes/home/afiliados_f2.gif',1);" ><img name="afiliados" src="/imagenes/home/afiliados.gif" width="140" height="15" border="0" alt="Afiliados"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="/ayuda/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('ayuda','','/imagenes/home/ayuda_f2.gif',1);" ><img name="ayuda" src="/imagenes/home/ayuda.gif" width="140" height="15" border="0" alt="Ayuda"></a></td>
          </tr>
          <tr> 
            <td colspan="7"><a href="/whois/" onMouseOut="MM_swapImgRestore()"  onMouseOver="MM_swapImage('whois','','/imagenes/home/whois_f2.gif',1);" ><img name="whois" src="/imagenes/home/whois.gif" width="140" height="20" border="0" alt="Whois"></a></td>
          </tr>
        </table>
      </td>
      <td rowspan="14" bgcolor="#FFFFFF" valign="top" background="/imagenes/home/lineas.gif"><img name="separadoe2" src="/imagenes/home/spacer.gif" width="10" height="372" border="0" alt="registro de dominios"></td>
      <td rowspan="14" colspan="3" bgcolor="#FFFFFF" valign="top"> 
        <div align="center"> 
          <div align="left"></div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td valign="top" class="body_10"> 
              <table border="0" cellpadding="0" cellspacing="0" align="left">
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
                      No olvides la extensi&oacute;n <b>.com .net .org .tv .cc 
                      .es</b></font></p>
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
              <p>&nbsp; </p>
</td>
          </tr>
          <tr> 
            <td valign="top" class="body_10"> <img src="/imagenes/titular_Homepage.gif" width="286" height="15" vspace="7"> 
              <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                <?
              //selecciona la imagen que aparecera
              $imagenes=array("Napoleon.gif","Hippy.gif","Miss.gif","Roquero.gif");
              srand((double)microtime()*1000000);
              $imagen="imagenes/".$imagenes[rand(0,3)];
            ?>
                Nunca fue tan f&aacute;cil registrar y mantener dominios en internet. 
                <img src=<?=$imagen?> align="right" >NombreMan&iacute;a te permite 
                registrar y trasladar tus dominios y administrarlos con suma facilidad 
                y a un precio sin competencia. </font> </p>
              <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><span class="body_11"><font color="#000099">PARA 
                NAVEGANTES SIN EXPERIENCIA.... </font></span></b>NombreMan&iacute;a 
                te permite un registro de dominios r&aacute;pido y f&aacute;cil 
                con la opci&oacute;n de usar tu p&aacute;gina personal o una de 
                reserva autom&aacute;tica y usar correo del tipo minombre@midominio.com. 
                Podr&aacute;s administrar tu dominio desde un panel de control 
                via web.</font></p>
              <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><span class="body_11"><font color="#000099">PARA 
                PROFESIONALES....</font></span></b> Adem&aacute;s del ahorro econ&oacute;mico 
                con nuestros programas para clientes registrados, te facilitamos 
                tremendamente el registro y administraci&oacute;n de tus dominios. 
                Con solo tu clave y password personal, ya no tendr&aacute;s que 
                repetir tus datos para hacer registros y contar&aacute;s con una 
                herramienta f&aacute;cil y r&aacute;pida de administraci&oacute;n 
                de TODOS tus dominios desde un solo acceso y perfil. Cambiar contactos 
                o entradas a las DNS es ahora cuesti&oacute;n de segundos.<br>
                Visita nuestra <a href="/demos/">demo</a> de administraci&oacute;n.</font></p>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td class="titu_secciones"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">NOVEDADES 
                    y NOTICIAS</font></td>
                </tr>
              </table>
              <table border="0" cellpadding="2" cellspacing="0" width="100%" bgcolor="#F3F3F3">
                <tr valign="top"> 
                  <td width="50%" class="body_9"> 
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">&#149; 
                      <a href="/registrar.php">Dominios Multiling&uuml;es</a>. 
                      </font></b>Ya puedes registrar dominios con &ntilde;, tildes, 
                      cedillas, etc.</font></p>
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">&#149; 
                      <a href="/precios.php">Nuevos Precios</a></font></b>. El 
                      Euro ya esta aqu&iacute;, y nuestros precios tambi&eacute;n.</font></p>
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">&#149; 
                      Nueva opci&oacute;n PRO avanzado</font></b>. Que permite 
                      una impresionante gesti&oacute;n integral de DNS profesional.<br>
                      </font></p>
                  </td>
                  <td width="50%" class="body_9"> 
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">&#149; 
                      Mejoras de seguridad.</font></b> Hemos mejorado a&uacute;n 
                      m&aacute;s la seguridad de nuestros sistemas, con m&aacute;s 
                      zonas operadas desde SSL 128bits</font></p>
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#000066">&#149; 
                      Compra y Alquiler. </font></b>Los dominios tambi&eacute;n 
                      se alquilan y venden.</font></p>
                  </td>
                </tr>
              </table>
              <br>
            </td>
            <br>
          </tr>
        </table>
      </td>
      <td rowspan="14" bgcolor="#FFFFFF" valign="top"><img src="/imagenes/home/spacer.gif" width="10" height="363" border="0"></td>
      <td valign="top" colspan="3" rowspan="13" bgcolor="#FFFFFF" align="center"> 
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
        <br>
        <br><!-- #BeginLibraryItem "/Library/Banner180.lbi" --><!--banner NMcaja de phpads -->
<iframe src='http://linux.alsur.es/phpads/adframe.php?what=zone:8&target=new&refresh=25' framespacing='0' frameborder='no' scrolling='no' width='180' height='75'><script language='JavaScript' src='http://linux.alsur.es/phpads/adjs.php?what=zone:8&target=new&withText=0'></script><noscript><a href='http://linux.alsur.es/phpads/adclick.php' target='new'><img src='http://linux.alsur.es/phpads/adview.php?what=zone:8&target=new&withText=0' border='0'></a></noscript></iframe>
<!-- #EndLibraryItem --><br>
        <hr>
        <!-- #BeginLibraryItem "/Library/banner180_ 2x.lbi" --><!--banner NMcajadoble de phpads -->
<iframe src='http://linux.alsur.es/phpads/adframe.php?what=zone:5&target=new&refresh=25' framespacing='0' frameborder='no' scrolling='no' width='180' height='150'><script language='JavaScript' src='http://linux.alsur.es/phpads/adjs.php?what=zone:5&target=new&withText=0'></script><noscript><a href='http://linux.alsur.es/phpads/adclick.php' target='new'><img src='http://linux.alsur.es/phpads/adview.php?what=zone:5&target=new&withText=0' border='0'></a></noscript></iframe><!-- #EndLibraryItem --><p><br>
        </p>
        <hr>
        <p><a href="precios.php"><img src="imagenes/precios_home.gif" width="168" height="95" border="0"></a></p>
        <p><a href="http://www.regaleundominio.com"><img src="/imagenes/regalo.gif" width="180" height="100" border="0"></a> 
        </p>
      </td>
      <td rowspan="16" colspan="2" background="/imagenes/home/corteder.gif" valign="bottom"><img name="esq_der_abajo" src="/imagenes/home/esq_der_abajo.gif" width="15" height="21" border="0" alt="registro de dominios"></td>
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="12"><img src="/imagenes/home/spacer.gif" width="1" height="12" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="15"><img src="/imagenes/home/spacer.gif" width="1" height="15" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td height="20"><img src="/imagenes/home/spacer.gif" width="1" height="20" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td colspan="3" background="/imagenes/home/corteizq.gif" height="9"><img name="corteizq" src="/imagenes/home/corteizq.gif" width="15" height="9" border="0" alt="registro de dominios"></td>
      <td rowspan="3" colspan="4" bgcolor="#FFFFFF" valign="top"><br>
        <!-- #BeginLibraryItem "/Library/promo_multilingue.lbi" --><a href="/registrar.php"><img src="/imagenes/multiligue.gif" width="125" height="60" border="0"></a><!-- #EndLibraryItem --><br>
        <br>
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
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&#149; 
                      El mejor precio<br>
                      <br>
                      &#149; Siempre en castellano<br>
                      <br>
                      &#149; Gesti&oacute;n f&aacute;cil y r&aacute;pida<br>
                      <br>
                      &#149;Todos tus dominios en un solo acceso.<br>
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
        <font face="Verdana, Arial, Helvetica, sans-serif" class="mini" size="1"><br>
        Este sitio ha sido testeado solo para versiones superiores a 4.5 de Netscape 
        y Explorer.</font></td>
      <td><img src="/imagenes/home/spacer.gif" width="1" height="9" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td colspan="3" background="/imagenes/home/corteizq.gif" rowspan="4" valign="bottom"><img name="esq_izq_abajo" src="/imagenes/home/esq_izq_abajo.gif" width="15" height="21" border="0" alt="registro de dominios"></td>
      <td rowspan="2"><img src="/imagenes/home/spacer.gif" width="1" height="363" border="0"></td>
      <td height="329"></td>
    </tr>
    <tr> 
      <td height="100" colspan="3" valign="bottom" bgcolor="#FFFFFF"><br>
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
        <a href="https://smarticon.geotrust.com/smarticonprofile?Referer=https://ssl.alsur.es" target="new"><br>
        <img src="/imagenes/home/servidor_seguro.gif" width="117" height="13" border="0" vspace="1" align="bottom"></a></td>
      <td></td>
    </tr>
    <tr> 
      <td colspan="12" bgcolor="#FFFFFF"><img src="/imagenes/desarrollalsur.gif" width="83" height="9"><a href="http://www.elsurexiste.com" target="alsur"><img src="/imagenes/deelsurexiste.gif" width="202" height="9" border="0" alt="el sur existe"></a></td>
      <td><img src="/imagenes/home/spacer.gif" width="1" height="13" border="0"></td>
      <td></td>
    </tr>
    <tr> 
      <td><img name="abajo1" src="/imagenes/home/abajo1.gif" width="6" height="8" border="0" alt="registro de dominios"></td>
      <td><img name="abajo" src="/imagenes/home/abajo.gif" width="8" height="8" border="0" alt="registro de dominios"></td>
      <td colspan="10" background="/imagenes/home/abajo.gif"><img name="abajo" src="/imagenes/home/abajo.gif" width="8" height="8" border="0" alt="registro de dominios"></td>
      <td><img src="/imagenes/home/spacer.gif" width="1" height="8" border="0"></td>
      <td></td>
    </tr>
  </table>
</div>
</body>
</html>
