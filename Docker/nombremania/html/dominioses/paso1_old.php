
<?
$disponible=0;

$pasar="tipo=procesar&list=Dominios&key=$domain";

$text=file("http://www.nic.es/cgi-bin/consulta.whois?$pasar");
$mostrar=0 ;
$disponible=0;
while (list($k,$fila)=each($text)){
       if ($mostrar==0 and eregi("<H1> �No se ha encontrado ningun objeto! </H1>",$fila)){
           $disponible=1;
           break;
       }
       if ($mostrar==0  and eregi("<BLOCKQUOTE><TABLE><TR><TD><STRONG>Dominio</STRONG>:",$fila)){
             $mostrar=1;
       }
       if ($mostrar){
       // $filas.=$fila;
              if (eregi("volver",$fila)){
              $mostrar=0;
              break;
              }
        }

     }


?>
<HTML><!-- #BeginTemplate "/Templates/nm2_base.dwt" --><!-- DW6 -->
<HEAD>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<!-- #BeginEditable "doctitle" --> 
<TITLE>NombreMania - Registro y traslado de dominios. Registro de dominios al 
mejor precio. </TITLE>
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
	<!-- #BeginEditable "contenido" --> <br>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="180" valign="top"> <font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
            <!--columna  -->
            </font></td>
          <td width="10" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><img src="/imagenes/base2/spacer.gif" width="10" height="8" border="0"></font></td>
          <td width="520" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp; 
            <?
            if (!$disponible){

            ?>
            </font> 
            <table width="520" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="18"><img src="/imagenes/titular_re_do_es1.gif" width="520" height="32"></td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF" height="18" class="body_10"><br>
                  <div align=center> <font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FF0000"><b><font color="#000000">Los 
                    sentimos... el dominio</font> " 
                    <?=$domain?>
                    " <font color="#000000">no est&aacute; disponible.</font></b></font> 
                    <br>
                    <br>
                    <hr noshade size="1" color="orange">
                    <form action="<?=$PHP_SELF ?>" name="esnic" method=POST >
                      <div align="LEFT"> </div>
                      <div align="LEFT"> 
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr valign="middle"> 
                            <td> 
                              <!--   pone el campo de texto en caso de no tener ya un nombre de dominio  -->
                              <center>
                                <input type="text" name="domain" value="<? echo $domain; ?>">
                                <input type="image" border="0" name="imageField" src="../imagenes/buscar.gif" align="middle">
                              </center>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </form>
                  </div>
                </td>
              </tr>
            </table>
            <font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
            <?
                            }
                            else {
                           ?>
            </font> 
            <table width="520" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td height="18"><img src="/imagenes/titular_re_do_es1.gif" width="520" height="32"></td>
              </tr>
            </table>
            <table border="0" cellpadding="0" align="left" cellspacing="0">
              <tr> 
                <td bgcolor="#FFFFFF">&nbsp;</td>
              </tr>
              <tr> 
                <td align="center" height="22" valign="top" bgcolor="#FFFFFF" class="body_10"><font face="Verdana, Arial, Helvetica, sans-serif" size=2 color=#FF0000><b><font color="#000000">Enhorabuena... 
                  el dominio</font> " 
                  <?=$domain ?>
                  " <font color="#000000">est&aacute; disponible.</font></b></font> 
                </td>
              </tr>
              <tr> 
                <td align="center" height="22" valign="top">&nbsp;</td>
              </tr>
              <tr align="left"> 
                <td bgcolor="#CC0000" height="15"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#CC0000"><b><font color="#FFFFFF">&nbsp;<font size="1">NOTAS 
                  IMPORTANTES PARA DOMINIOS .ES</font></font></b></font> </td>
              </tr>
              <tr align="left"> 
                <td class="body_10"> 
                  <div align="LEFT"> 
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><font size="2" color="#003366"><b><br>
                      1.- NOMBRES QUE SE PUEDEN REGISTRAR:</b></font><b><br>
                      S&oacute;lo podr&aacute; registrar</b> dominios que <b><font color="#CC0000">correspondan 
                      con el nombre legal exacto de su empresa u organizaci&oacute;n</font></b> 
                      o con marcas legalmente registradas (con titulo) en la Oficina 
                      Espa&ntilde;ola de Patentes y Marcas. Datos que deber&aacute; 
                      incluir en la petici&oacute;n.</font></p>
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><font size="2" color="#003366"><b>2.- 
                      GASTOS DE GESTI&Oacute;N:</b></font><b><br>
                      </b>Debido a las caracter&iacute;sticas del sistema de registro 
                      de ES-NIC (el organismo encargado del registro de dominios 
                      .es) y a las dificultades a&ntilde;adidas por este sistema 
                      <b><font color="#CC0000"> NombreMania no puede garantizar 
                      de antemano la consecuci&oacute;n del registro solicitado</font></b>. 
                      Nuestro <a href="../precios.php" target="otrolugare">precio 
                      del registro .es</a> solo corresponde a nuestros honorarios 
                      por los tr&aacute;mites de gesti&oacute;n, cantidad que 
                      <b><font color="#CC0000">en ning&uacute;n caso se devolver&aacute; 
                      </font></b>puesto que los tramites son id&eacute;nticos 
                      finalice o no con &eacute;xito el registro.</font></p>
                    <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000000"><font size="2" color="#003366"><b>3.- 
                      PAGO POR A&Ntilde;OS:</b></font><b><br>
                      </b>Los registros .es (a diferencia de otros dominios) <b><font color="#CC0000">se 
                      abonan por a&ntilde;os naturales</font></b>. Esto quiere 
                      decir que el periodo de registro abonado finaliza al acabar 
                      el a&ntilde;o, con independencia del mes en que realice 
                      el registro. Por ejemplo: un dominio registrado en enero 
                      saldr&iacute;a por el mismo precio y caducar&iacute;a en 
                      la misma fecha que uno registrado en noviembre.</font></p>
                  </div>
                  <blockquote> 
                    <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000"><b>Solo 
                      si acepta y comprende las notas anteriores</b></font><font color="#000000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2"> 
                      y el dominio que desea registrar cumple con los requisitos 
                      legales pulse el <i>siguiente</i> bot&oacute;n para continuar.</font></b></font></div>
                  </blockquote>
                  <table border=0 width="520" align="left" cellpadding="0" cellspacing="0">
                    <tr> 
                      <td valign="top" width="236" bgcolor="#FFFFFF"> 
                        <table border="0" cellpadding="4" bordercolor="#003399" cellspacing="0" width="100%">
                          <tr> 
                            <td width="48%" class="titu_secciones" height="12"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><font size="1">Registro 
                              Estandar</font></b></font></td>
                          </tr>
                        </table>
                        <table border="0" cellpadding="4" bordercolor="#003399" cellspacing="0" width="100%" height="100%">
                          <tr valign="top"> 
                            <td> 
                              <div align="left"> 
                                <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Incluye 
                                  <b> el registro del dominio</b> y las tasas 
                                  por el numero de a&ntilde;os que se contrate.</font><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                                  Deber&aacute;s disponer de los datos de los 
                                  <a href="#" onClick="MM_openBrWindow('','','')">servidores 
                                  DNS</a> a los que apuntar&aacute; el/los dominio/s 
                                  antes de proceder al registro.</font></p>
                              </div>
                              <p align="center">&nbsp;</p>
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td width="8" valign="top"><img src="../imagenes/shim.gif" width="8" height="20"></td>
                      <td valign="top" width="250"> 
                        <table border="0" cellpadding="4" bordercolor="#003366" cellspacing="0" width="100%">
                          <tr> 
                            <td width="50%" class="titu_secciones" height="12"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><font size="1">Registro 
                              Pro </font></b></font></td>
                          </tr>
                        </table>
                        <table width="100%" border="0" cellspacing="0" cellpadding="4">
                          <tr> 
                            <td height="17"> <font face="Verdana, Arial, Helvetica, sans-serif" size="1">Incluye 
                              <b> el registro del dominio</b> y las tasas por 
                              el numero de a&ntilde;os que se contrate, m&aacute;s 
                              el servicio de <b>gesti&oacute;n de DNS</b> que 
                              le permitir&aacute; contar con una p&aacute;gina 
                              de <b>parking</b>, hacer una <b>redirecci&oacute;n 
                              a otra p&aacute;gina web</b> (<a href="#" onClick="MM_openBrWindow('','','')">WebForward</a>) 
                              o <b>redirigir una cuenta de de correo</b> de su 
                              nuevo dominio a su email actual (<a href="#" onClick="MM_openBrWindow('','','')">MailForward</a>).</font></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr> 
                      <td valign="top" width="236" bgcolor="#FFFFFF"> 
                        <table border="0" cellpadding="4" bordercolor="#003399" cellspacing="0" width="100%">
                          <tr valign="top"> 
                            <td valign="top"> 
                              <form method="post" action="paso2.php" name="estandar">
                                <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                                  <input type="hidden" name="Tipodedominio2" value="Estandar">
                                  <input type="image" border="0" name="estandar" src="../imagenes/registrar.gif" align="middle">
                                  <input type="hidden" name="affiliate_id2" value="<? echo $affiliate_id?>">
                                  <input type="hidden" name="dominio2" value="<? echo $domain?>">
                                  </font> </div>
                              </form>
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td width="8" valign="top">&nbsp;</td>
                      <td valign="top" width="250"> 
                        <table border="0" cellpadding="4" bordercolor="#003366" cellspacing="0" width="100%">
                          <tr valign="top"> 
                            <td> 
                              <form method="post" action="paso2.php" name="pro">
                                <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                                  <input type="hidden" name="Tipodedominio" value="Pro">
                                  <input type="image" border="0" name="estandar2" src="../imagenes/registrar.gif" align="middle">
                                  <input type="hidden" name="affiliate_id" value="<? echo $affiliate_id?>">
                                  <input type="hidden" name="dominio" value="<? echo $domain?>">
                                  </font> </div>
                              </form>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr align="left"> 
                <td><img src="../imagenes/shim.gif" width="2" height="10"></td>
              </tr>
            </table>
            <?
               }
              ?>
          </td>
        </tr>
      </table>
      <STYLE type="text/css">
<!--
a:hover {  background-color: #FFCC00; font-family: Verdana, Arial, Helvetica, sans-serif; color: #333333; text-decoration: overline}
a:link {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000099; text-decoration: none}
a:visited {  font-family: Verdana, Arial, Helvetica, sans-serif; font-style: italic; color: #CC6600; text-decoration: none}
-->
</STYLE>
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
