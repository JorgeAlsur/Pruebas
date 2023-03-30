<?

function updatedb($t,$f,$where)
{
	$sql="update $t set ";
	while (list($k,$v)=each($f))
	{
		$sql2[]= "$k = \"$v\"  ";
	}
	return $sql. join(",",$sql2) . " $where";
}

function insertdb($t,$f)
{

	$sql="insert into $t  ";
	$campos= array_keys($f);
	$valores=array_values($f);
	array_walk($valores,"entrecomillas") ;
	$sql.= "(".join(",",$campos).") values (".join(",",$valores).")";
	return $sql;
}

function insertdb1($t,$f)
{
	$sql="insert into $t ";
	$campos= array_keys($f);
	$valores=array_values($f);
	$sql.="(".implode(",",$campos).") values(";
	$vv = array();
	foreach($valores as $v)
	{
		if ($v <>"NOW()")
		{
			$vv[]= "'$v'"; 
		}
		else
		{
			$vv[]= "$v";		
		}
	}
	$sql.=  implode(",",$vv)." )";
	return $sql;
}

function entrecomillas(&$tabla, $clave)
{
   $tabla = "'$tabla'";
}

function fecha2mysql($fecha)
{
	//devuelve fecha para ser usada por mysql

	if(strpos($fecha, '/')) list($d,$m,$y)=explode('/',trim($fecha));
	else list($d,$m,$y)=explode('-',trim($fecha));
	
	//list($d,$m,$y)=explode('[/.-]',trim($fecha));

	return "$y-".str_pad($m,2,'0')."-".str_pad($d,2,'0',STR_PAD_LEFT);
}

function encripta($datos)
{
	$d=serialize($datos);

	$d=base64_encode($d);

	return $d;
}

function descripta($clave)
{

	$d=base64_decode($clave);

	return unserialize($d);

}

function arr2html2($tabla)
{
	echo "<table border=1>";

	while (list($k,$v)=each($tabla))
	{
		print "<tr><td>$k</td><td>$v</td></tr>";
	}

	print "</table>";
}

function redirige($donde)
{
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$donde\">";
}

function genera_password($largo=8)
{
	// Create an array of valid password characters.

	$the_char = array(

     "a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",

     "k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",

     "u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8",

     "9","0"

);

// Set var to number of elements in the array minus 1, since arrays begin at 0

// and the count() function returns beginning the count at 1.

	$max_elements = count($the_char) - 1;

// Now we set our random vars using the rand() function with 0 and the

// array count number as our arguments. Thus returning $the_char[randnum].

	srand((double)microtime()*1000000);

	$_password="";

	for($i=0;$i<$largo;$i++)
	{
		$_password.= $the_char[rand(0,$max_elements)];
	}
// Finally, echo the password.
	return $_password;
}

function var_crudas($var,$nombre="")
{
	if(!is_array($var) && !is_object($var))
	{ 
		print $nombre."=".$var;
		return; 
	} 
	echo "\n$nombre<ul>\n"; 
	while(list($key, $val) = @each($var))
	{ 
		if(is_array($val) || is_object($val))
		{ 
			var_crudas($val); 
		} 
		else echo "<LI>$key = '$val'\n"; 
	} 
	echo "</ul>\n"; 
}

function mostrar_error($texto)
{
?>

<table bgcolor=red width=50% align=center>

<tr><td align=Center><font color=#fffff FACE=verdana size=3 ><b><?=$texto?> </b></td></tr>

</table>

<?
}
/*function form_select($datos,$name,$selecto="",$sin_select=false){
if (!$sin_select) $r= "<select name=$name >";

   $r="";
while (list($k,$v)=each($datos)){
    $r.= "<option value=\"$k\"";
    if ($selecto !="" and $selecto==$k){
     $r.= " selected ";
    
    }

    $r.= ">$v</option>";
}
if (!$sin_select) $r.="</select>";
return $r;
}
*/
function form_select($datos,$name,$selecto="",$sin_select=false,$texto_alternativo='')
{
	
	
	if(!$sin_select)$r="<select name=\"$name\">";

	//print_r($datos);echo "<br/><br/>name=$name<br/>selecto=$selecto<br/>";
	$selectoSI=false;
	$r="";
	if(is_array($datos))
	
	
	while(list($k,$v)=each($datos))
	{
		if(is_array($v) && count($v)==2) list($v, $class) = $v;
	// @todo si hay 3a columna del array lo consideramos un class y lo pintamos en c.u.,
		$r.= "<option value=\"$k\"";
		if(is_array($selecto))
		{ 
			if(in_array($k,$selecto)) {
				$selectoSI=true;
				$r.=" selected='selected' ";
				}
		}
		else
		{
			if($selecto !="" and $selecto==$k)
			{
				$selectoSI=true;
				$r.= " selected='selected' ";
			}
		}
		
		
		if($selectoSI && $class) $r.= " class='selecto $class' ";
		elseif($selectoSI) $r.= " class='selecto' ";
		elseif($class) $r.= " class='$class' ";

		// if selectoSI o si estilos
		// class="if selctoSI"selected " 3a columna"
		$r.= ">$v</option>";
	}
	if(!$selectoSI && $texto_alternativo!=''){
		$r.="<option value=\"$selecto\" selected='selected'>$texto_alternativo</option>";
	}
	if(!$sin_select)$r.="</select>";
	return $r;
}

function getmicrotime()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

function limpia_rs($datos)
{
	while (list($k,$v) = each($datos))
	{
		if(is_int($k))unset($datos[$k]);
	}
	return $datos;
}

function ie()
{
	return stristr( getenv('HTTP_USER_AGENT'),'MSIE');
}
?>
