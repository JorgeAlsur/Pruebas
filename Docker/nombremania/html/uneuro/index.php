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
      <table width="710" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="180" valign="top" align="center"> 
            <p><a href="http://www.regaleundominio.com"><img src="/imagenes/regalo.gif" width="180" height="100" border="0"></a></p><!-- #BeginLibraryItem "/Library/Banner180.lbi" --><!--banner NMcaja de phpads -->
<iframe src='http://linux.alsur.es/phpads/adframe.php?what=zone:8&target=new&refresh=25' framespacing='0' frameborder='no' scrolling='no' width='180' height='75'><script language='JavaScript' src='http://linux.alsur.es/phpads/adjs.php?what=zone:8&target=new&withText=0'></script><noscript><a href='http://linux.alsur.es/phpads/adclick.php' target='new'><img src='http://linux.alsur.es/phpads/adview.php?what=zone:8&target=new&withText=0' border='0'></a></noscript></iframe>
<!-- #EndLibraryItem --></td>
          <td width="10" valign="top"><img src="/imagenes/base2/spacer.gif" width="10" height="8" border="0"></td>
          <td width="510" valign="top"> 
            <table border=0 cellpadding="3" height="320">
              <?
          $fatal=0;
          $error=array();
          $errores="";
          if (isset($f_nombre)){

          if ($f_nombre=="") $error[]="Falta o error el nombre.";
          if ($f_email=="") $error[]="Falta o error el email.";
					if ($f_edad=="") $error[]="Falta o error en la edad.";
//					if ($f_=="") $error[]="Falta o error en el contacto.";
    //      if ($f_telefono=="") $error[]="Falta o error en el telfono.";
      //    if ($f_poblacion=="") $error[]="Falta o error en la poblacion.";
//          if ($f_provincia=="") $error[]="Falta o error en la provincia.";
	/*		 if ($f_pagina=="") $error[]="Falta saber si tiene pagina web.";
          if ($f_nosconociopor=="") $error[]="Falta saber donde nos conoci&oacute;.";
		*/			
          if (!isset($aceptacondiciones) or $aceptacondiciones=="") $error[]="Falta la aceptacion de las condiciones";

          if (count($error)>0) { // presenta los errores y continua con la pagina
           $errores="Se presentaron errores<br>";
           $errores.=join("<br>",$error);
           }
           else {
           include "basededatos.php"; include "hachelib.php";
           $datos=f2array($_POST);
//           $datos["activo"]=1;
$datos["fecha"] = str_replace("'","",$conn->DBDate(time()));
           $sql=insertdb("visitantes",$datos);
					 
//					 $conn->debug=1;
            $rs=$conn->execute($sql);
            if ($rs===false ) {
            $errores="Error en el ingreso de sus datos. Posiblemente Ud. ya estaba ingresado como cliente.<br>
            Ante cualquier duda comniquese con nuestro <a href=mailto:soporte@nombremania.com>departamento de soporte</a>." ;
            $fatal=1;
	//					print mysql_error();
            }
            else {
           //envio del email para administracion
           $id_entregado=$conn->Insert_ID();
           $link="www.nombremania.com/uneuro.php?tarea=modifica&id=$id_entregado";
					 $acceso = base64_encode("acceso a la oferta $id_entregado");
           $texto="Estimado Usuario : 
					 Le enviamos la URL de entrada para que acceda a nuestra oferta especial de rebaja de un EURO para la compra de dominios de internet.
					 Para acceder siga esta direccion WEB  :
					 http://www.nombremania.com/uneuro/acceso.php?acceso=$acceso 
					 En caso de tener problemas clickeando directamente sobre la URL intente copiarla y pegarla en la barra de navegacion.
					 Gracias por su interes   
					 ";
                   $envio_email=  mail($datos["email"],"nombremania.com -Acceso a oferta","$texto","From: registros@nombremania.com");
									 if (!$envio_email){
									 		$errores.="error en el envio del mail";
									//		echo "error de envio email";
										//			var_crudas($datos); 								 
									 }									 																	 
           }
           }
           }
           if (!isset($f_nombre) or $errores<>"" or $fatal==1){
        ?>
              <tr> 
                <td colspan="3" class="titu_pags"><font color="#990000">Oferta</font> 
                  -1 Euro menos en t&uacute; registro</td>
              </tr>
              <tr>
                <td colspan="3" class="body_10">
                  <p>Para ahorrar un euro en t&uacute; registro solo tienes que 
                    rellenar este breve cuestionario. Los campos en rojo son los 
                    requeridos, pero te <b>estariamos muy agradecidos</b>, si 
                    rellenaras el resto con datos verdaderos que nos ayudan fundamentalmente 
                    a mejorar el servicio a clientes como t&uacute;.</p>
                  <p>Recibiras en tu buzon el enlace valido para aplicarte la 
                    oferta y tendr&aacute;s <b><font color="#990000">hasta el 
                    31 de Enero para realizar t&uacute; registro.</font></b> </p>
                  <p>A cada nuevo dominio que registres<b> (la oferta no es valida 
                    para traslados)</b> se le descontar&aacute; <b>UN EURO sobre 
                    el PVP</b>. </p>
                </td>
              </tr>
              <tr> 
                <td colspan="3" class="body_10"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FF0000"> 
                  <?if ($errores<>"") echo $errores; ?>
                  </font> </td>
              </tr>
              <tr> 
                <td colspan="3"> 
                  <hr width=100% size="1" color="orange" noshade>
                </td>
              </tr>
              <tr> 
                <td colspan="3"> 
                  <form method="post" action="<?=$PHP_SELF?>" name="cuestionariouneuro"f_clave\" is not valid.','f_direccion','8','1','La direccin no es valida');return document.MM_returnValue">
                    <table border="0" cellspacing="2" cellpadding="2" align="center">
                      <tr> 
                        <td bgcolor="#003366" colspan="3" class="titu_secciones"><b><font size="1" color="#FFFFFF" face="Georgia, Times New Roman, Times, serif">DATOS 
                          DE CONTACTO</font></b></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo"> 
                          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#990000">Nombre: 
                            </font> </b> </font></div>
                        </td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                          <input type="text" name="f_nombre" value="<?=$f_nombre; ?>" size="35">
                          </font></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo"> 
                          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#990000">Profesi&oacute;n: 
                            </font> </b> </font></div>
                        </td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                          <input type="text" name="f_profesion" value="<?=$f_profesion; ?>" size="35">
                          </font></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo" align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#990000"><b>Edad:</b></font></td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                          <select name="f_edad">
                            <option selected value="">Edad</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                            <option value="60">60</option>
                            <option value="61">61</option>
                            <option value="62">62</option>
                            <option value="63">63</option>
                            <option value="64">64</option>
                            <option value="65">65</option>
                            <option value="66">66</option>
                            <option value="67">67</option>
                            <option value="68">68</option>
                            <option value="69">69</option>
                            <option value="70">70</option>
                            <option value="71">71</option>
                            <option value="72">72</option>
                            <option value="73">73</option>
                            <option value="74">74</option>
                            <option value="75">75</option>
                            <option value="76">76</option>
                            <option value="77">77</option>
                            <option value="78">78</option>
                            <option value="79">79</option>
                            <option value="80">80</option>
                            <option value="81">81</option>
                            <option value="82">82</option>
                            <option value="83">83</option>
                            <option value="84">84</option>
                            <option value="85">85</option>
                            <option value="86">86</option>
                            <option value="87">87</option>
                            <option value="88">88</option>
                            <option value="89">89</option>
                            <option value="90">90</option>
                          </select>
                        </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo" align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color=""><b>Sexo:</b></font></td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                          <select name="f_sexo">
                            <option value="m" selected>Hombre</option>
                            <option value="f">Mujer</option>
                          </select>
                        </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo"> 
                          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"><b><font color="#990000">Email:</font></b></font></div>
                        </td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                          <input type="text" name="f_email" value="<?=$f_email; ?>" size="35">
                          </font></td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo">&nbsp;</td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo"> 
                          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&iquest;Has 
                            registrado alg&uacute;n dominio anteriormente?:</font></div>
                        </td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                          <select name="f_cliente_anterior">
                            <option value="Si algunos">SI, alguna vez</option>
                            <option value="Si habitualmente">SI, habitualmente</option>
                            <option value="NO">NO</option>
                            <option selected value="">Elije...</option>
                          </select>
                        </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo"> 
                          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&iquest;Tienes 
                            p&aacute;gina web?</font></div>
                        </td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                          <select name="f_pagina_web">
                            <option selected value="">Elije...</option>
                            <option value="SI personal">SI, personal</option>
                            <option value="Si empresa">SI, de la empresa</option>
                            <option value="NO">NO</option>
                          </select>
                        </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" class="td_form_titulo" align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">En 
                          caso afirmativo... <br>
                          la alojas en:</font></td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                          <select name="f_alojamiento">
                            <option selected value="">Elije una opcion</option>
                            <option value="Espacio gratis">Web de espacio gratuito 
                            (tipo Geocities)</option>
                            <option value="ISP">Espacio personal gratis de mi 
                            ISP</option>
                            <option value="Hospedaje">Hospedaje contratado</option>
                            <option value="Otro">Otros</option>
                          </select>
                        </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" valign="middle" class="td_form_titulo">&nbsp;</td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" valign="middle" class="td_form_titulo"> 
                          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Nos 
                            conociste por:</font></div>
                        </td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                          <font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                          <select name="f_nosconociopor">
                            <option value="" selected>Elije una opcion</option>
                            <option value="motor">Motor de busqueda</option>
                            <option value="amigo">Un amigo</option>
                            <option value="prensa">Prensa</option>
                            <option value="banner">Banner en otro site</option>
                            <option value="promo">Hoja Promocional</option>
                            <option value="otro">Otro</option>
                          </select>
                          </font> </td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" valign="middle" class="td_form_titulo">&nbsp;</td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td valign="middle" colspan="3">&nbsp;</td>
                      </tr>
                      <tr> 
                        <td bgcolor="#FFFFFF" valign="top" class="td_form_titulo"> 
                          <div align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Condiciones:</font></div>
                        </td>
                        <td colspan="2" bgcolor="#FFCC00" class="td_form_valor"> 
                          <p> <font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                            <textarea name="condiciones" cols="40" rows="6" wrap="VIRTUAL">CONDICIONES
Esta oferta es valida al menos hasta el 31 de Enero de 2002, pudiendo quedar anulada en cualquier momento posterior sin previo aviso. 
La oferta consistir&aacute; en el descuento de un euro por cada nuevo dominio registrado independientemente del n&uacute;mero de a&ntilde;os que se soliciten. Esta oferta no es aplicable a traslados.</textarea>
                            </font></p>
                          <p> <font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
                            <input type="checkbox" name="aceptacondiciones" value="checkbox">
                            <b>Acepto las condiciones </b></font></p>
                        </td>
                      </tr>
                      <tr> 
                        <td colspan="3"> 
                          <div align="center"> 
                            <input type="image" border="0" name="submit" src="/imagenes/continuar.gif">
                          </div>
                        </td>
                      </tr>
                    </table>
                  </form>
                </td>
              </tr>
              <?
              } ?>
              <tr> 
                <td colspan="3"> 
                  <!--Devolucin si es correcto -->
                  <br>
                  <?
              if ( isset($f_nombre) and $errores=="" and $fatal==0){
              ?>
                  <table border=0 align="left" cellpadding="3">
                    <tr> 
                      <td colspan="3" class="titu_pags" valign="top">Gracias</td>
                    </tr>
                    <tr> 
                      <td colspan="3" class="body_10" valign="top" height="300"> 
                        <p><font size=2 face="Verdana, Arial, Helvetica, sans-serif" color="#000000">Gracias 
                          por tu solicitud.</font></p>
                        <p><font size=2 face="Verdana, Arial, Helvetica, sans-serif" color="#000000"> 
                          En unos instantes recibir&aacute;s un email con la URL 
                          de acceso.</font></p>
                        <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#000000">Recuerda 
                          que <b>debes registrar t&uacute; dominio antes del 31 
                          de Enero de 2002.</b></font></p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                      </td>
                    </tr>
                  </table>
                  <?
                  }
                ?>
                </td>
              </tr>
            </table>
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