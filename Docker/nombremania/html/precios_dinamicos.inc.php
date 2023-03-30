<?
function tabla_precios($moneda,$cgi)
{
	// moneda: moneda a mostrar a mostrar , cgi : link para el cambio de moneda 
	if(!isset($moneda))
	{
		header("Location: /error.php?error=Falta+especificar+la+moneda");
		exit;
	}
	include('basededatos.php');
	include('hachelib.php');
	$sql="select * from precios order by producto";
	$rs=$conn->execute($sql);
	$datos=array();
	if($moneda=='euros')
	{
		$simbolo=' &euro;';
		$multi=1;
		$redondeo=2;
		$moneda_text='EUROS'; //usada para los titulos
		$convert='ptas';
	}
	else
	{
		$simbolo=' Ptas ';
		$multi=166.386;
		$redondeo=0;								 
		$moneda_text='PESETAS';	//usada para los titulos
		$convert='euros';
	}
 
	while(!$rs->EOF)
	{
		$datos[$rs->fields['producto'].'_pvp_1']=round(($multi * $rs->fields['pxrango1']),$redondeo).$simbolo;
		$datos[$rs->fields['producto'].'_pvp_2']=round(($multi * $rs->fields['pxrango2']),$redondeo).$simbolo;
		$datos[$rs->fields['producto'].'_pvp_3']=round(($multi * $rs->fields['pxrango3']),$redondeo).$simbolo;						
		$rs->movenext();
	}

	include('class.templateh.php');
	$tpl='precios_tabla.inc';
	$t1=new Template('.','keep');
	$t1->set_file('template',$tpl);
	$t1->set_var('moneda_text',$moneda_text);
	$t1->set_var('convert',$convert);
	$t1->set_var('CGI',$cgi);
	reset($datos);
	while(list($k,$v)=each($datos))
	{
		$t1->set_var($k,$v); 
	}
	$ret=$t1->parse('template');
	//var_crudas($datos);
	return $ret;
}
?>