<HTML>
<HEAD><TITLE>Administracion de Nombremania</TITLE>
<link rel="stylesheet" type="text/css" href="estilo.css">
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
</HEAD>
<body>
<?
if (!isset($sin_titulo)) include "barra.php";
   include "basededatos.php";
   $conn->debug=0;
if (!isset($sin_titulo)){
?>
<center>
  <FORM  action='<?=$PHP_SELF?>' method='get' name='FORMU'>
    <table width="700" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td width="210" bgcolor="#000033" align="center">
          <div align="center"><font color="#FFFFFF"><b><font size="2">FACTURAS</font></b></font>
					<br>
					<font color="#FFFFFF" size=1>para no generar facturas usar nm_nofactura</font>
</div>
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
          <input type='radio' name=tipo value=nif title=nif

   <?
   if ($tipo=="nif") echo "CHECKED";
   ?>
   >
          </font><font size="1">por nif.</font>
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
orden:
<select name=orden>
<option>fecha</option>
<option>id</option>
<option>concepto</option>
</select>

tipo orden=
<select name=tipo_orden>
<option value="ASC">Ascendente</option>
<option value="DESC">Descendente</option>
</select>

<input type=checkbox name=sin_titulo value=1> sin titulos
          <input type=submit value=consultar>
          <hr>
        </td>
      </tr>
    </table>
    <br>
  </FORM>
<?
}
//   if ($tipo!=""){
   $buscar=trim($buscar);
   $campos= " id,nombre,id_solicitud,concepto,DATE_FORMAT(FROM_UNIXTIME(fecha),'%d-%m-%y') as fecha2,nif,precio,CONCAT('<A HREF=ficha.php?id=',id_solicitud,'>solicitud</a>'),CONCAT('<A HREF=i_factura.php?id=',id,'>factura</a>')";
   $campos2="codigo:concepto:fecha:nif:precio:tarea:factura";
	 
   switch ($tipo){
   case "codigo":
      $titulo="Busqueda por codigo = $buscar";
      $sql="select $campos from facturas where id=$buscar";
      break;
   case "dominio":
      $titulo="Busqueda por dominio = $buscar";
      $sql="select $campos from facturas where concepto like \"%$buscar%\" ";
      break;
   case "nif":
      $titulo = "Busqueda por nif = $buscar";
     $sql="select $campos from facturas where nif = \"$buscar\" ";
      break;
   case "fecha":
      $aux=split(" ",$buscar);
      if (count($aux)==2){
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[1]));
         $fecha_hasta=mktime(23,59,59,$mes,$dia,$anio);
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[0]));
         $fecha_desde=mktime(0,0,0,$mes,$dia,$anio);
         $titulo="Busqueda por RANGO de fechas.<br>".$buscar;
          $sql="select $campos from facturas where fecha >= $fecha_desde and fecha <= $fecha_hasta ";
      }
      else {
         list($dia,$mes,$anio)=split('[/.-]',trim($aux[0]));
         $fecha_desde=mktime(0,0,0,$mes,$dia,$anio);
         $fecha_hasta=mktime(23,59,59,$mes,$dia,$anio);
         $titulo="Busqueda por igualdad de fecha <br>".$buscar;
         $sql="select $campos from facturas where fecha >= $fecha_desde and fecha <= $fecha_hasta  ";
      }
			break;
      default:
      $titulo = "mostrando el ultimo mes";
      $fi = time()-(30*24*60*60) ; // ultimo mes
      $sql="select $campos from facturas where fecha >= $fi";
    }
//$conn->debug=1;
   if(!isset($tipo_orden) ) $tipo_orden=" ASC ";
if (isset($orden)){
   $orden = " order by $orden $tipo_orden ";
}else {
   $orden = " order by fecha $tipo_orden ";
}

   $rs=$conn->execute($sql.$orden) ;
   if (   $conn->affected_rows()>0) {

     // rs2html($rs," class=tabla width=740 border=1 ",split(":",$campos2));
include "class.templateh.php";
$t= new Template(".");
$t->set_file("lista","list_facturas.html");
      $total_siniva=0;
            $total_bruto=0;
            $total=0;
$cantidad=0;
	if(!isset($sin_titulo))
	{
		$t->parse("TAREAS_TIT","TAREAS_TIT");
	}
	while (!$rs->EOF)
	{
		$cantidad++;
	$t->set_var("codigo",$rs->fields["id"]);
		$t->set_var("nombre",htmlentities(   $rs->fields["nombre"]));
		$t->set_var("concepto", str_replace("**",", ",$rs->fields["concepto"]));
		$t->set_var("fecha",$rs->fields["fecha2"]);
		$t->set_var("nif",$rs->fields["nif"]);
		$t->set_var("precio",$rs->fields["precio"]);
		$total_siniva+=($rs->fields["precio"]*0.84);
		$total_bruto+=$rs->fields["precio"];
		if(!isset($sin_titulo))
		{
			$t->set_var("tarea","<A HREF=ficha.php?id=".$rs->fields['id_solicitud'].">solicitud</a>");
			$t->set_var("factura","<A HREF=/clientes/pago/i_factura.php?id=".base64_encode($rs->fields["id"]."-nm").">factura</a>");
			//$t->parse("TAREAS","TAREAS",false);
		}
		$t->parse("FACTURAS","FACTURAS",true);
		$rs->movenext();
	}
	$t->set_var("titulo",$titulo);
	$t->set_var("total_iva",$total_bruto-$total_siniva);
	$t->set_var("total_bruto",$total_bruto);
	$t->set_var("cantidad",$cantidad);
	
	print $t->parse("lista");
}
else
{
	echo "<p class=alerta>Ningun registro encontrado.</p>$buscar";
	print mysql_error();
}
// }
?>

