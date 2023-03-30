<?
$debug=false;
$_test_or_live='LIVE';
//$_test_or_live="test"; //cambiar para pasar a modo TEST
$REG_PERIODS = array (
		1	=> '1 A&ntilde;o',
		2	=> '2 A&ntilde;os',
		3	=> '3 A&ntilde;os',
		4	=> '4 A&ntilde;os',
		5	=> '5 A&ntilde;os',
		6	=> '6 A&ntilde;os',
		7	=> '7 A&ntilde;os',
		8	=> '8 A&ntilde;os',
		9	=> '9 A&ntilde;os'
	);
$REG_PERIODS_tv = array (
    1  => '1 A&ntilde;o',
		2	=> '2 A&ntilde;os',
		3	=> '3 A&ntilde;os',
 		5	=> '5 A&ntilde;os',
 		10	=> '10 A&ntilde;os'
	);

 
$required_contact_fields = array (
			'first_name'	=> 'Nombre',
			'last_name'	=> 'Apellido',
			'org_name'	=> 'Empresa',
			'address1'	=> 'Direcci&oacute;n',
			'city'		=> 'Localidad',
			'country'	=> 'Pa&iacute;s',
			'phone'		=> 'Tel&eacute;fono',
			'email'		=> 'E-Mail'
			);

$contact_fields = array (
			'first_name'	=> 'Nombre',
			'last_name'	=> 'Apellido',
			'org_name'	=> 'Empresa',
			'address1'	=> 'Direcci&oacute;n',
			'city'		=> 'Localidad',
			'country'	=> 'Pa&iacute;s',
			'phone'		=> 'Tel&eacute;fono',
			'email'		=> 'E-Mail',
			'fax'		=> 'FAX',
			'address2' => "Direcci&oacute;n (cont.)",
			'address3' => "Direcci&oacute;n (cont.2)",
			'postal_code'=>'Codigo Postal',
			'state'=>'Provincia'
			);
   
$nm_productos=array(
                    'estandar'=>'B&aacute;sico',
                    'pro2'=>'PRO',
                    'pro3'=>'DNS Avanzada'); //no cambiar el orden afecta a renovaciones
$reg_types= array('new'=>'Registro','transfer'=>'Traslado','renew'=>'Renovaci&oacute;n');
										
$ext_soportadas=array('com','net','org','info','biz','cc','tv','es');
$ext_soportadas_nomulti=array('com','net','org');
//$monedas=array("euros"=>"EUROS","ptas"=>"PESETAS","usd"=>"D&oacute;lares");
$monedas=array('euros'=>'EUROS','ptas'=>'PESETAS');
$simbolos_monedas=array('euros'=>'&euro;','ptas'=>'Ptas','usd'=>"U\$S");
$cambio_monedas=array('euros'=>1,
       'ptas'=>166.386,'usd'=>1.29);

#$dir_logs='/logs/nm';
$dir_logs='/home/webs/nombremania.com/html/logs';
$server_url='http://www.nombremania.com';
//$server_url='http://local.nombremania.com';
$server_path='/home/webs/nombremania.com/html';
                  
?>
