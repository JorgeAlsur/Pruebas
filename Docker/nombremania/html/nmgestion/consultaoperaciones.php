<HTML>
<HEAD><TITLE>Administracion de Nombremania</TITLE>
<link rel="stylesheet" type="text/css" href="estilo.css">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
</HEAD>
<body>
<?
include("barra.php");
include("basededatos.php");
$conn->debug=0;

if(!isset($tipo))$tipo="codigo";
?>
<center>
  <FORM  action='<?=$PHP_SELF?>' method='get' name='FORMU'>
    <table width="700" border="0" cellspacing="0" cellpadding="2" class="test">
      <tr>
        <td width="210" bgcolor="#000033" align="center">
          <div align="center"><font color="#FFFFFF"><b><font size="2">OPERACIONES
            </font></b></font></div>
        </td>
        <td colspan="2" align="left" bgcolor="#666666"><font color="#FFFFFF" size="2"><b><font size="1">Buscar
          :</font></b></font><font size="2">&nbsp; </font><font size="2">
          <input type='TEXT'  name='buscar' size='20' maxlength='25' value="<?=$buscar?>">
          </font></td>
      </tr>
      <tr>
        <td width="197" height="5">&nbsp;</td>
        <td colspan="2" valign="middle" align="left" height="5" bgcolor="#999999"><font size="2">&nbsp;
          </font><font size="1">&nbsp; </font><font size="2">
          <input type='RADIO'  name='tipo' value='codigo'   title='codigo' <?
   if ($tipo=="codigo") echo "CHECKED";
   ?>>
          </font>
					<font size="1">codigo</font><font size="2"> &nbsp;
	           <input type='RADIO'  name='tipo' value='cliente'   title='cliente'
	  <?
   if ($tipo=="cliente") echo "CHECKED";
   ?>>
          </font>
					
					<font size="1">afiliado(#)</font><font size="2"> &nbsp;
					<br>
          <input type='RADIO'  name='tipo' value='dominio'   title='dominio'
   <?

	 
   if ($tipo=="dominio" || $tipo=='ultimomes') echo "CHECKED";
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
          </font><font size="1">por fecha (d-m-a) o por rango (d-m-a d-m-a).</font>

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
   if(!isset($tipo) or $tipo=="")$tipo="ultimomes";
   $conn->debug=0;
   $buscar=trim($buscar);
   $campos= " id,mid(domain,1,40),DATE_FORMAT(FROM_UNIXTIME(fecha),'%d-%m-%Y'),
   cod_aprob ,tipo, codigo_opensrs,reg_type,period,affiliate_id,precio,CONCAT('<A HREF=fichaoperaciones.php?id=',id,'>FICHA</a>'),CONCAT('<A HREF=enviar_password.php?id=',id,' title=\"enviar password\">PWD</a>'),CONCAT('<A HREF=enviar_factura.php?id=',id,' title=\"enviar factura\">Fac.</a>')";
   $campos2="codigo:domain:<a href={$REQUEST_URI}&orden=fecha>fecha</a><a href={$REQUEST_URI}&orden=fecha+DESC>&gt;</a>:aprobacion:<a href={$REQUEST_URI}&orden=tipo>producto</a>:opensrs:Tipo:periodo:Afiliado:precio:tarea:PWS:FAC";
   switch($tipo)
   {
		case "codigo":
			$titulo="busqueda por codigo = $buscar";
			$sql="select $campos from operaciones where id=$buscar";
		break;
		case "cliente":
			$titulo="busqueda por codigo = $buscar";
			$sql="select $campos from operaciones where affiliate_id=$buscar";
		break;
			
	case "dominio":
		$titulo="busqueda por dominio = $buscar";
		$sql="select $campos from operaciones where domain like \"%$buscar%\" ";
	break;
	case "cod_aprob":
		$titulo = "busqueda por codigo de aprobacion  = $buscar";
		$sql="select $campos from operaciones where cod_aprob = \"$buscar\" ";
	break;
	case "ultimos";
		$titulo="busqueda de los ultimos 25 dominios";
		$sql="select $campos from operaciones order by id DESC limit 0,25 ";
	break;
	case "ultimomes";
		$m=date("m");
		$y=trim(date("Y"));
		if($m==12)
		{
			$m1="01";
			$y1= (int) $y + 1;
				$d1="31";
				}else {
				$d1=1;
				$m1=(int) $m +1;
				$y1=$y;}
         $buscar="1-$m-$y $d1-$m1-$y1";
		//$con->debug=1;
   case "fecha":
      $aux=explode(" ",$buscar);
      if(count($aux)==2)
	  {
			list($dia,$mes,$anio)=split('[/.-]',trim($aux[1]));
			$fecha_hasta=mktime(23,59,59,$mes,$dia,$anio);
			list($dia,$mes,$anio)=split('[/.-]',trim($aux[0]));
         $fecha_desde=mktime(0,0,0,$mes,$dia,$anio);
         $titulo="Busqueda por RANGO de fechas.<br>".$buscar;
          $sql="select $campos from operaciones where fecha >= $fecha_desde and fecha <= $fecha_hasta order by fecha DESC limit 0,500";
      }
      else {
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[0]));
         $fecha_desde=mktime(0,0,0,$mes,$dia,$anio);
         $fecha_hasta=mktime(23,59,59,$mes,$dia,$anio);
         $titulo="Busqueda por igualdad de fecha <br>".$buscar;

         $sql="select $campos from operaciones where fecha >= $fecha_desde and fecha <= $fecha_hasta  order by fecha DESC limit 0,500";
      }
      break;
   }
   if($orden!="")$sql.=" order by $orden";
   $rs=$conn->execute($sql);
   if($conn->affected_rows()>0)
   {
   ?>
   <?=$titulo ?>
   <?
		rs2html($rs," class=\"tabla\" width=\"700\"  ",explode(":",$campos2),false);
			
		$preciototal=0;
		$periods=0; $cantidad_v=0;
			$cantidad_r=0;$cantidad_t=0;
			$impuestos=0;
			$rs=$conn->execute($sql);
			$cantidad_total=0;
			$anios=0;
			$n=0;
			$pro2=0;$pro3=0;$estandar=0;
			while(!$rs->EOF)
			{
			
			if($rs->fields["billing_country"]=="ES")
			{
				$precio=$rs->fields["precio"]/1.21;
				$impuestos+=$precio*.21;
			}
			else
			{
				$precio=$rs->fields["precio"];
			}
			$preciototal+=$precio;
		

						switch($rs->fields["reg_type"]){
									 case "new":
									 		$cantidad_v+= (count(explode("**",$rs->fields["domain"])));
          						$periods_v+=$rs->fields["period"] * (count(explode("**",$rs->fields["domain"])));											
										break;
									 case "transfer":
									 		$cantidad_t+= (count(explode("**",$rs->fields["domain"])));
						$periods_t+=$rs->fields["period"] * (count(explode("**",$rs->fields["domain"])));											
										break;
									 case "renew":
									 		$cantidad_r+= (count(explode("**",$rs->fields["domain"])));
						$periods_r+=$rs->fields["period"] * (count(explode("**",$rs->fields["domain"])));											
										break;
											
						}

						switch (strtolower($rs->fields["tipo"])){
		     						case "pro2":
									   $pro2+=$rs->fields["period"] * (count(explode("**",$rs->fields["domain"])));
									    break;
									case "pro3":
										$pro3+=$rs->fields["period"] * (count(explode("**",$rs->fields["domain"])));
										break;
									default:
										$estandar+=$rs->fields["period"] * (count(explode("**",$rs->fields["domain"])));
						}
						$anios+=$rs->fields["period"]*(count(explode("**",$rs->fields["domain"])));
						$cantidad_total+=(count(explode("**",$rs->fields["domain"])));
						$rs->movenext(); 		
						if($rs->fields["affiliate_id"]>100)$por_afiliado++;
						if(eregi("4b",$rs->fields["cod_aprob"]))$por_4b++;	
		$n++;
		}
		if($cantidad_v>0)$media_ventas= number_format ($periods_v/$cantidad_v,2);
		if($cantidad_r>0)$media_renova=number_format($periods_r/$cantidad_r,2);
		$ratio=@number_format($anios/$cantidad_total,2);
		if($por_afiliado>0)$r_afi=number_format(($por_afiliado*100)/$n,2);
		if($por_4b>0)$r_4b=number_format(($por_4b*100)/$n,2);
		if($pro2>0)$r_pro2=number_format(($pro2*100)/$anios,2);
		if($pro3>0)$r_pro3=number_format(($pro3*100)/$anios,2);
		if($estandar>0)$r_estandar=number_format(($estandar*100)/$anios,2);
?>
<style type="text/css">
<!--
table
{
	border: #000000;
	border-style: solid;
	border-width: 1px;
	font-family:Tahoma,Verdana
}
-->
</style> 

<?
		echo "<br><table bgcolor=#CCCCFF>
		<th colspan=\"2\" bgcolor=#ddddff>Estad&iacute;sticas</th>
		<tr>
			<tr class=\"tabla\">	<td>Cantidad de operaciones;  : </td><td>$n</td></tr>
			<tr>	<td>Total de a&ntilde;os   : </td><td>$anios ($ratio)</td></tr>
		<tr>	
		<td>total en &euro;  : </td><td>$preciototal</td></tr>
				<tr>
		<td>Total dominios nuevos (a&ntilde;os/dominios): </td><td>$cantidad_v ($media_ventas)</td></tr>
		<tr>
		<td>Total dominios transferidos: </td><td>$cantidad_t</td></tr>
		<tr>
		<td>Total dominios renovados (a&ntilde;os/dominios): </td><td>$cantidad_r ($media_renova)</td></tr>
		<tr><td>por 4b $por_4b ($r_4b %)</td><td>por Afiliado $por_afiliado ($r_afi %)</td>
		<tr><td colspan=\"2\" align=\"center\">Estandar: $estandar ($r_estandar %)  &nbsp; &nbsp;&nbsp;PRO basico: $pro2 ($r_pro2 %)   &nbsp; &nbsp; &nbsp; &nbsp; PRO avanzado: $pro3 ($r_pro3 %)</td></tr> 
		</table>";		
	}
	else
	{
		echo "<p class=alerta>Ning&uacute;n registro encontrado.</p>$titulo";
		print mysql_error();
	}


   ?>
</center></BODY></HTML>