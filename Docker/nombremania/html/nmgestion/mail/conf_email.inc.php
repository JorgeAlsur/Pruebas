<?
//horaciod

$destinos = array("nombremania_clientes"=>"nombremania clientes",
					"nombremania_solicitados"=>"nombremania solicitados",
					"nombremania_registrados"=>"nombremania registrados",
					"nombremania_solo_alsur"=>"nombremania solo_alsur",
					"solo_horacio_webmaster"=>"solo horaciod via sql");
$sqls=array("nombremania_clientes"=>"select contacto as nombre, nombre as empresa, direccion as campo1, poblacion as campo2,email as email from clientes where activo=1 group by email",
"nombremania_solicitados"=>"select owner_email as email, concat(owner_last_name,', ',owner_first_name) as nombre, domain as campo1 , date_format(from_unixtime(date),\"%d-%m-%Y\") as  campo2  from solicitados where nm_cod_aprob<>\"\"  group by email",
"nombremania_registrados"=>"select owner_email as email, concat(owner_last_name,', ',owner_first_name) as nombre, domain as campo1 , date_format(from_unixtime(fecha),\"%d-%m-%Y\") as  campo2  from operaciones  group by email order by owner_last_name ",
"nombremania_solo_alsur"=>"select owner_email as email, concat(owner_last_name,', ',owner_first_name) as nombre, domain as campo1 , date_format(from_unixtime(fecha),\"%d-%m-%Y\") as  campo2  from operaciones  where owner_email like \"%alsur.es%\" group by email order by owner_last_name",
"solo_horacio_webmaster"=>"select owner_email as email, concat(owner_last_name,', ',owner_first_name) as nombre, domain as campo1 , date_format(from_unixtime(fecha),\"%d-%m-%Y\") as  campo2  from operaciones  where owner_email = \"horaciod@alsur.es\" or owner_email = \"webmaster@alsur.es\" or owner_email=\"correo@alsur.es\" or owner_email=\"joseluis@alsur.es\" group by email order by owner_last_name ");

$campos_reemplazo=array("nombre","empresa","campo1","campo2","campo3","email");

?>