<?
function f2array($a) { //saca todas las variables para guardar en la base de datos

$datos=array();
while (list($k,$v)=each($a)){
if (ereg("^f_",$k)){
    $var=substr($k,2);
    $datos[$var]=$v;
}
}
return $datos;
}

?>
<HTML><!-- #BeginTemplate "/Templates/nm2_solophp.dwt" --><!-- DW6 -->
<HEAD>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
<!-- #BeginEditable "doctitle" -->
<TITLE>Nombremania - En tus dominios mandas tu</TITLE>
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
      <p align="center"> 
      <table border="0" cellspacing="0" cellpadding="0" width="710">
        <tr> 
          <td valign="top"> 
            <P>&nbsp;</P>
            </td>
          <td valign="top" width="10"><img src="/imagenes/base2/spacer.gif" width="10" height="8" border="0"></td>
          <td valign="top" width="520"> 
            <?
          $fatal=0;
          $error=array();
          $errores="";
          if (isset($f_nombre)){

          if ($f_nombre=="") $error[]="Falta o error el nombre.";
          if ($f_email=="") $error[]="Falta o error el email.";
          if ($f_direccion=="") $error[]="Falta o error en la direcci&oacute;n.";
          if ($f_contacto=="") $error[]="Falta o error en el contacto.";
          if ($f_telefono=="") $error[]="Falta o error en el tel&eacute;fono.";
          if ($f_poblacion=="") $error[]="Falta o error en la poblacion.";
          if ($f_provincia=="") $error[]="Falta o error en la provincia.";
          if ($f_usuario=="") $error[]="Falta o error en el usuario.";
          if ($f_clave=="") $error[]="Falta o error en la clave.";
					if ($f_afiliadobanner=="") $error[]="Falta indicar si usar&aacute; banners o no.";
					if ($f_afiliadobanner=="S" and $f_URL=="") $error[]="Falta indicar las URL donde piensa utilizar nuestros banners";
          if (!isset($aceptacondiciones) or $aceptacondiciones=="") $error[]="Falta la aceptacion de las condiciones";

          if (count($error)>0) { // presenta los errores y continua con la pagina
           $errores="Se presentaron errores<br>";
           $errores.=join("<br>",$error);
           }
           else {
           include "basededatos.php"; include "hachelib.php";
           $datos=f2array($_POST);
           $datos["activo"]=1;
					 $datos["fecha_alta"]=date("Y-m-d");
           $sql=insertdb("clientes",$datos);
         // $conn->debug=1;
		 
		 /*if($_SERVER['REMOTE_ADDR']=='89.130.215.195')
		 {
		 	echo $sql;
			exit;
		 }*/
		 
            $rs=$conn->execute($sql);
            if ($rs===false ) {
            $errores="Error en el ingreso de sus datos. Posiblemente Ud. ya estaba ingresado como cliente.<br>
            Ante cualquier duda comun&iacute;quese con nuestro <a href=mailto:soporte@nombremania.com>departamento de soporte</a>." ;
            $fatal=1;
            }
            else {
           //envio del email para administracion
           $id_entregado=$conn->Insert_ID();
           $link="www.nombremania.com/admin/clientes.php?tarea=modifica&id=$id_entregado";
           $texto="se ha comunicado un cliente solicitando un registro como cliente Frecuente\n
           sus datos son:".join("\n",$datos)."\n\n"."para acceder a sus datos siga este link.\n".$link;
            mail("administracion@nombremania.com","NM - Cliente Frecuente solicitud de alta ","$texto","From: no_reply@nombremania.com\n");
						// envio de email al cliente

						$ruta=$_SERVER['DOCUMENT_ROOT'];
include "enviar_email.php";
						$dat=array();
						$dat["nombre"]=$datos["nombre"];
					$dat["email_cliente"]=$datos["email"];

if (!envioemail($ruta."/procesos/mails_a_enviar/mail_afiliado_aceptacion.txt",$dat)){
    mostrar_error_nm("Error en el envio del Mail");
    exit;
    }
          }
           }
           }
           if (!isset($f_nombre) or $errores<>"" or $fatal==1){
        ?>
            <P><BR>
              <IMG src="/imagenes/titular_solicitud.gif" width="520" height="32"> 
              <BR>
              Rellena todos los campos del siguiente formulario con tus datos 
              y nos pondremos en contacto con los detalles de acceso una vez aceptada 
              tu solicitud.</P>
            <P><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FF0000"> 
              <?if ($errores<>"") echo $errores; ?>
              </FONT></P>
            <HR width=100% size="1" color="orange" noshade>
            <FORM method="post" action="solicitud_alta.php" name="altacliente" >
              <TABLE border="0" cellspacing="2" cellpadding="2" align="center" width="520">
                <TR> 
                  <TD bgcolor="#003366" colspan="3" class="titu_secciones"><B><FONT size="1" color="#FFFFFF" face="Georgia, Times New Roman, Times, serif">DATOS 
                    DE CONTACTO</FONT></B></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Nombre 
                      o Raz&oacute;n Social:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_nombre" value="<?=$f_nombre; ?>" size="35">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Persona 
                      de contacto:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_contacto"  value="<?=$f_contacto ?>" size="35">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">DNI 
                      o NIF:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_dni_nif" value="<?=$f_dni_nif ?>" size="35">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Direcci&oacute;n 
                      (completa):</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <TEXTAREA name="f_direccion" cols="35" rows="3" wrap="PHYSICAL"><?=$f_direccion; ?></TEXTAREA>
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Poblaci&oacute;n:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_poblacion" value="<?=$f_poblacion; ?>" size="35">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Provincia:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_provincia"
              value="<?=$f_provincia; ?>" size="35">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">CP:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_cp"
              value="<?=$f_cp; ?>" size="10">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Pa&iacute;s:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_pais" value="<?=$f_pais; ?>" size="35">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Tel&eacute;fono:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_telefono"
               value="<?=$f_telefono; ?>" size="30">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Fax:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
                    <INPUT type="text" name="f_fax"
              value="<?=$f_fax; ?>" size="30">
                    <FONT color="#CC0000"> * opcional </FONT></FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Email:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_email" value="<?=$f_email; ?>" size="35">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo">&nbsp;</TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor">&nbsp;</TD>
                </TR>
                <TR> 
                  <TD colspan="3">&nbsp;</TD>
                </TR>
                <TR> 
                  <TD bgcolor="#003366" colspan="3" class="titu_secciones"><B><FONT face="Georgia, Times New Roman, Times, serif" size="1" color="#FFFFFF">ACCESO 
                    </FONT></B></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" colspan="3" class="body_10"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Introduzca 
                    un login y password que le resulten familiares para acceder 
                    posteriormente a su panel de control de afiliado. <B>Le recomendamos 
                    utilizar la misma clave y usuario con la que registra habitualmente 
                    sus dominios.</B></FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Nombre 
                      de usuario:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_usuario" value="<?=$f_usuario?>" size="16">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Clave:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="password" name="f_clave" value="<?=$f_clave ?>" size="16">
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD colspan="3">&nbsp;</TD>
                </TR>
                <TR> 
                  <TD bgcolor="#003366" colspan="3" class="titu_secciones"><B><FONT face="Georgia, Times New Roman, Times, serif" size="1" color="#FFFFFF">PREGUNTAS 
                    DE INTER&Eacute;S</FONT></B></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" colspan="3" class="body_10"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Aunque 
                    son opcionales, nos ayudar&iacute;a mucho conocer su respuesta 
                    a estas preguntas.<BR>
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" valign="middle" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Nos 
                      conoc&iacute;o por:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"> <FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <SELECT name="f_nosconociopor">
                      <OPTION value=" ">Elija una opcion</OPTION>
                      <OPTION value="motor">Motor de busqueda</OPTION>
                      <OPTION value="amigo">Un amigo</OPTION>
                      <OPTION value="prensa">Prensa</OPTION>
                      <OPTION value="banner" selected>Banner en otro site</OPTION>
                      <OPTION value="promo">Hoja Promocional</OPTION>
                      <OPTION value="otro">Otro</OPTION>
                    </SELECT>
                    <FONT color="#CC0000">* opcional </FONT></FONT> </TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" valign="middle" class="td_form_titulo"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Tipo 
                      de actividad:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"> <FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <INPUT type="text" name="f_actividad" size="40" value="<?=$f_actividad ?>">
                    <FONT color="#CC0000">* opcional </FONT></FONT> </TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" valign="middle" class="td_form_titulo" align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">&iquest;Tienes 
                    intenci&oacute;n de participar en nuestro programa de afiliados, 
                    mediante publicidad de NombreMania en tu site/s?:</FONT></TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                    <INPUT  type="radio" <? echo ($f_afiliadobanner=="S") ? " checked ":" "; ?>  name="f_afiliadobanner" value="S">
                    SI 
                    <INPUT type="radio" <? echo ($f_afiliadobanner=="N") ? " checked ":" "; ?> name="f_afiliadobanner" value="N">
                    NO </TD>
                </TR>
                <TR> 
                  <TD bgcolor="#FFFFFF" valign="middle" class="td_form_titulo" align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">En 
                    caso afirmativo, indica URLs <BR>
                    en las que lo har&aacute;s</FONT></TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                    <TEXTAREA name="f_URL" cols="35" rows="2" wrap="PHYSICAL"><?=$f_URL; ?></TEXTAREA>
                    </FONT></TD>
                </TR>
                <TR> 
                  <TD valign="middle" colspan="3">&nbsp;</TD>
                </TR>
                <TR valign="middle"> 
                  <TD bgcolor="#FFFFFF" class="td_form_titulo" height="30"> 
                    <DIV align="right"><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1">Condiciones:</FONT></DIV>
                  </TD>
                  <TD colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                    <P><FONT face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                      <INPUT type="checkbox" name="aceptacondiciones" value="checkbox">
                      <B>Acepto las <A href="#" onClick="MM_openBrWindow('/legales/ventana_cond_afiliados.html','Condiciones','width=400,height=400')">condiciones 
                      de afiliaci&oacute;n</A></B></FONT></P>
                  </TD>
                </TR>
                <TR> 
                  <TD colspan="3"> 
                    <DIV align="center"> 
                      <INPUT type="image" border="0" name="submit" src="/imagenes/continuar.gif">
                    </DIV>
                  </TD>
                </TR>
              </TABLE>
            </FORM>
            <BR>
			<!--Devolucin si es correcto -->
                  <br>
                  <?
									}
              if ( isset($f_nombre) and $errores=="" and $fatal==0){
              ?>
                  <table border=0 width="520" align="left" cellpadding="3">
                    <tr> 
                      <td colspan="3"><img src="/imagenes/titular_solicitud.gif" width="520" height="32"></td>
                    </tr>
                    <tr> 
                      <td colspan="3" class="body_10"> 
                        <p><font size=2 face="Verdana, Arial, Helvetica, sans-serif" color="#000000">Gracias 
                          por su solicitud.</font></p>
                        <p><font size=2 face="Verdana, Arial, Helvetica, sans-serif" color="#000000"> 
                          Nos pondremos en contacto con usted con los detalles 
                          de acceso una vez aceptada su petici&oacute;n.</font></p>
                      </td>
                    </tr>
                  </table>
                  
            <?
                  }
                ?>
            <P>&nbsp;</P>
            <P>&nbsp;</P>
            <P>&nbsp;</P>
            <P>&nbsp;</P>
            <P>&nbsp; </P>
          </td>
        </tr>
      </table>
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
