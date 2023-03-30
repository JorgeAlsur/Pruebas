<HTML>
<HEAD><TITLE>Administracion de Nombremania</TITLE>
<link rel="stylesheet" type="text/css" href="../estilo.css">
</HEAD>
<body>
<?

include "barra.php";
   include "basededatos.php";
   $conn->debug=0;

   if (!isset($tipo)) $tipo="codigo";
   ?>
<center>
  <FORM  action='<?=$PHP_SELF?>' method='get' name='FORMU'>
    <table width="700" border="0" cellspacing="0" cellpadding="2">
      <tr> 
        <td width="197" bgcolor="#000033" align="center"> 
          <div align="center"><font color="#FFFFFF"><b><font size="2">DOMINIOS 
            SOLICITADOS</font></b></font></div>
        </td>
        <td width="17" align="right"><font size="2"> </font></td>
        <td colspan="2" align="left" bgcolor="#666666"><font color="#FFFFFF" size="2"><b><font size="1">Buscar 
          :</font></b></font><font size="2"> </font><font size="2"> 
          <input type='TEXT'  name='buscar' size='20' maxlength='20'>
          </font></td>
      </tr>
      <tr> 
        <td width="197" height="5">&nbsp;</td>
        <td width="17" height="5"> 
          <div align="right"><font size="1"></font></div>
        </td>
        <td colspan="2" valign="middle" align="left" height="5" bgcolor="#999999"><font size="2"> 
          </font><font size="1"> </font><font size="2"> 
          <input type='RADIO'  name='tipo' value='codigo'   title='codigo' <?
   if ($tipo=="codigo") echo "CHECKED";
   ?>>
          </font><font size="1">por código</font><font size="2"> &nbsp; 
          <input type='RADIO'  name='tipo' value='dominio'   title='dominio'
   <?
   if ($tipo=="dominio") echo "CHECKED";
   ?>
   >
          </font><font size="1">por dominio</font><font size="2"> &nbsp; 
          <input type='radio' name=tipo value=cod_aprob title=cod_aprob

   <?
   if ($tipo=="cod_aprob") echo "CHECKED";
   ?>
   >
          </font><font size="1">por código de aprob.</font></td>
      </tr>
      <tr> 
        <td colspan="4" height="5">
          <hr>
        </td>
      </tr>
    </table>
    <br>
  </FORM>
   
<?
   if (isset($buscar) and $buscar!=""){
   $buscar=trim($buscar);
   $campos= " id,domain,DATE_FORMAT(FROM_UNIXTIME(date),'%d-%m-%Y'),cod_aprob ,tipo, CONCAT('<A HREF=ficha.php?id=',id,'>ficha</a>') ";
   $campos2="codigo:dominios:fecha:aprobacion:tipo:tarea";
   switch ($tipo){
   case "codigo":
      $titulo="búsqueda por codigo = $buscar";
      $sql="select $campos from dominios where id=$buscar";
      break;
   case "dominio":
      $titulo="búsqueda por dominio = $buscar";
      $sql="select $campos from dominios where domain like \"%$buscar%\" ";
      break;
   case "cod_aprob":
      $titulo = "búsqueda por codigo de aprobacion  = $buscar";
     $sql="select $campos from dominios where cod_aprob = \"$buscar\" ";
      break;
   default :
   echo "nada";
   }

   $rs=$conn->execute($sql) ;
   if (   $conn->affected_rows()>0) {
      rs2html($rs," class=tabla width=600 border=1 ",split(":",$campos2));
   }
   else {
         echo "<p class=alerta>Ningun registro encontrado.</p>";
      print mysql_error();
   }
   }

   ?>

</center></BODY></HTML>
