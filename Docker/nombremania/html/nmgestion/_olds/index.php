<?
if (!isset($tipo)) $tipo="ultimos";
?>
<HTML>
<HEAD><TITLE>Administracion de Nombremania</TITLE>
<link rel="stylesheet" type="text/css" href="estilo.css">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
</HEAD>
<body>
<?

include "barra.php";
   include "basededatos.php";
   $conn->debug=0;

   ?>
<center>
  <FORM  action='<?=$PHP_SELF?>' method='get' name='FORMU'>
    <table width="700" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="210" bgcolor="#000033" align="center">
          <div align="center"><font color="#FFFFFF"><b><font size="2">DOMINIOS
            SOLICITADOS</font></b></font></div>
        </td>
        <td width="17" align="right"><font size="2"> </font></td>
        <td colspan="2" align="left" bgcolor="#666666"><font color="#FFFFFF" size="2"><b><font size="1">Buscar
          :</font></b></font><font size="2"> </font><font size="2">
          <input type='TEXT'  name='buscar' size='20' maxlength='25' VALUE="<?=$buscar?>">
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
          </font><font size="1">por c&oacute;digo</font><font size="2"> &nbsp;
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
          </font><font size="1">por c&oacute;digo de aprob.</font>
          <input type='radio' name=tipo value=fecha title=fecha

   <?
   if ($tipo=="fecha") echo "CHECKED";
   ?>
   >
          </font><font size="1">por fecha <p align=right>(d-m-a) o por rango (d-m-a d-m-a).</p></font>

          </td>

      </tr>
      <tr>
        <td colspan="4" align=right>
          <input type=submit value=consultar>
          <hr>
        </td>
      </tr>
    </table>
    <br>
  </FORM>
<?

   if ($tipo!=""){
   $buscar=trim($buscar);
   $campos= " id,mid(domain,1,30),DATE_FORMAT(FROM_UNIXTIME(date),'%d-%m-%Y'),nm_cod_aprob ,nm_registro_tipo, reg_type,affiliate_id,period,nm_preciototal,id_registrador,CONCAT('<A HREF=ficha.php?id=',id,'>FICHA</a>') ";
   $campos2="<a href={$REQUEST_URI}&orden=id>codigo</a>:<a href={$REQUEST_URI}&orden=domain>dominios</a>:<a href={$REQUEST_URI}&orden=date>fecha</a>:aprobacion:producto:tipo:afiliado:periodos:precio:registrador:tarea";
   switch ($tipo){
   case "codigo":
      $titulo="B&uacute;squeda por codigo = $buscar";
      $sql="select $campos from solicitados where id=$buscar";
if ($orden=="" ) $orden = "id asc ";
      break;
   case "dominio":
      $titulo="B&uacute;squeda por dominio = $buscar";
      $sql="select $campos from solicitados where domain like \"%$buscar%\" ";
if ($orden=="" ) $orden = "domain";
      break;
   case "cod_aprob":
      $titulo = "B&uacute;squeda por codigo de aprobacion  = $buscar";
     $sql="select $campos from solicitados where nm_cod_aprob = \"$buscar\" ";
if ($orden=="" ) $orden = "cod_aprob";
      break;
   case "fecha";
      $aux=split(" ",$buscar);
      if (count($aux)==2){
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[1]));
         $fecha_hasta=mktime(23,59,59,$mes,$dia,$anio);
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[0]));
         $fecha_desde=mktime(0,0,0,$mes,$dia,$anio);
         $titulo="B&uacute;usqueda por RANGO de fechas.<br>".$buscar;
          $sql="select $campos from solicitados where date >= $fecha_desde and date <= $fecha_hasta ";
$orden= "date desc";
     }
      else {
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[0]));
         $fecha_desde=mktime(0,0,0,$mes,$dia,$anio);
         $fecha_hasta=mktime(23,59,59,$mes,$dia,$anio);
         $titulo="B&uacute;squeda por igualdad de fecha <br>".$buscar;

         $sql="select $campos from solicitados where date >= $fecha_desde and date <= $fecha_hasta  ";
         if ($orden=="" ) $orden = "date desc";
      }
      break;
   default :
      $titulo = "B&uacute;squeda los &uacute;ltimos 25 ";
     $sql="select $campos from solicitados ";
if ($orden=="" ) $orden=" id desc limit 0,25" ;
      break;
   }
//$conn->debug=1;
$sql.=" order by $orden";
   $rs=$conn->execute($sql) ;
   if (   $conn->affected_rows()>0) {
   ?>
    <?=$titulo; ?>
   <?
      rs2html($rs," class=tabla width=740 border=1 ",split(":",$campos2));
			$preciototal=0;			$periods=0; $cantidad_v=0;
			$cantidad_r=0;$cantidad_t=0;
			$rs->movefirst();
			while (!$rs->EOF){
					$preciototal+=$rs->fields["nm_preciototal"];
						$periods+=$rs->fields["period"];
						switch ($rs->fields["reg_type"]){
									 case "new":
									 		$cantidad_v+= (count(explode("**",$rs->fields["domain"]))+1);
										break;
									 case "transfer":
									 		$cantidad_t+= (count(explode("**",$rs->fields["domain"]))+1);
										break;
									 case "renew":
									 		$cantidad_r+= (count(explode("**",$rs->fields["domain"]))+1);
										break;
											
						}
						$rs->movenext(); 			
		}
		echo "<table><tr>
		<td>total en &euro;  : </td><td>$preciototal</td></tr>
		<td>Periodos  : </td><td>$periods</td></tr>
		<td>Cantidad de dominios vendidos : </td><td>$cantidad_v</td></tr>
		<td>Cantidad de dominios transferidos  : </td><td>$cantidad_t</td></tr>
		<td>Cantidad de dominios renovados : </td><td>$cantidad_r</td></tr>
		</table>";		
   }
   else {
         echo "<p class=alerta>Ningun registro encontrado.</p>$buscar";
      print mysql_error();
   }
   }

   ?>

</center></BODY></HTML>
