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

   if (!isset($tipo)) $tipo="codigo";
   ?>
<center>
  <FORM  action='<?=$PHP_SELF?>' method='get' name='FORMU'>
    <table width="700" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="210" bgcolor="#000033" align="center">
          <div align="center"><font color="#FFFFFF"><b><font size="2">OPERACIONES
            </font></b></font></div>
        </td>
        <td width="17" align="right"><font size="2"> </font></td>
        <td colspan="2" align="left" bgcolor="#666666"><font color="#FFFFFF" size="2"><b><font size="1">Buscar
          :</font></b></font><font size="2"> </font><font size="2">
          <input type='TEXT'  name='buscar' size='20' maxlength='25' value="<?=$buscar?>">
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
          </font><font size="1">por codigo</font><font size="2"> &nbsp;
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
          </font><font size="1">por codigo de aprob.</font>
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
   if (!isset($tipo) or $tipo=="") $tipo="ultimos";
   $conn->debug=0;
   $buscar=trim($buscar);
   $campos= " id,mid(domain,1,40),DATE_FORMAT(FROM_UNIXTIME(fecha),'%d-%m-%Y'),
   cod_aprob ,tipo, codigo_opensrs,reg_type,period,affiliate_id,precio,CONCAT('<A HREF=fichaoperaciones.php?id=',id,'>FICHA</a>')";
   $campos2="codigo:domain:<a href={$REQUEST_URI}&orden=fecha>fecha</a><a href={$REQUEST_URI}&orden=fecha+DESC>&gt;</a>:aprobacion:<a href={$REQUEST_URI}&orden=tipo>producto</a>:opensrs:Tipo:periodo:Afiliado:precio:tarea";
   switch ($tipo){
   case "codigo":
      $titulo="búsqueda por codigo = $buscar";
      $sql="select $campos from operaciones where id=$buscar";
      break;
   case "dominio":
      $titulo="búsqueda por dominio = $buscar";
      $sql="select $campos from operaciones where domain like \"%$buscar%\" ";
      break;
   case "cod_aprob":
      $titulo = "búsqueda por codigo de aprobacion  = $buscar";
     $sql="select $campos from operaciones where cod_aprob = \"$buscar\" ";
     break;
   case "ultimos";
         $titulo="busqueda de los ultimos 25 dominios";
         $sql="select $campos from operaciones order by id DESC limit 0,25 ";
         break;
   case "fecha":
      $aux=split(" ",$buscar);
      if (count($aux)==2){
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[1]));
         $fecha_hasta=mktime(23,59,59,$mes,$dia,$anio);
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[0]));
         $fecha_desde=mktime(0,0,0,$mes,$dia,$anio);
         $titulo="Busqueda por RANGO de fechas.<br>".$buscar;
          $sql="select $campos from operaciones where fecha >= $fecha_desde and fecha <= $fecha_hasta order by fecha DESC limit 0,100";
      }
      else {
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[0]));
         $fecha_desde=mktime(0,0,0,$mes,$dia,$anio);
         $fecha_hasta=mktime(23,59,59,$mes,$dia,$anio);
         $titulo="Busqueda por igualdad de fecha <br>".$buscar;

         $sql="select $campos from operaciones where fecha >= $fecha_desde and fecha <= $fecha_hasta  order by fecha DESC limit 0,100";
      }
      break;
   }
   if ($orden !="") $sql.=" order by $orden";
   $rs=$conn->execute($sql) ;
   if (   $conn->affected_rows()>0) {
   ?>
   <?=$titulo ?>
   <?
      rs2html($rs," class=tabla width=700 border=1 ",split(":",$campos2));
			
			$preciototal=0;			$periods=0; $cantidad_v=0;
			$cantidad_r=0;$cantidad_t=0;
			$rs->movefirst();
			$impuestos=0;
			while (!$rs->EOF){
									$preciototal+=$rs->fields["precio"];
										
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
		$pt=$preciototal+$impuestos;
		echo "<table><tr>
		<td>Total en &euro;  : </td><td>$pt</td></tr>
		<tr>
		<td>Total impuestos &euro;  : </td><td>$impuestos</td></tr>
		<tr>
		<td>Total neto &euro;  : </td><td>$preciototal</td></tr>

		<tr>
		<td>Periodos  : </td><td>$periods</td></tr>
				<tr>
		<td>Cantidad de dominios vendidos : </td><td>$cantidad_v</td></tr>
				<tr>
		<td>Cantidad de dominios transferidos  : </td><td>$cantidad_t</td></tr>
				<tr>
		<td>Cantidad de dominios renovados : </td><td>$cantidad_r</td></tr>
		</table>";		
   }
   else {

         echo "<p class=alerta>Ningun registro encontrado.</p>$titulo";
      print mysql_error();
   }


   ?>

</center></BODY></HTML>
