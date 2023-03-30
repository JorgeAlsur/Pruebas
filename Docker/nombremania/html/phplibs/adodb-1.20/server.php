<?php
/** 
 * (c)2001 John Lim (jlim@natsoft.com.my). All rights reserved.
 * Released under Lesser GPL library license. See License.txt. 
 */
 
/* Documentation on usage is at http://php.weblogs.com/adodb_csv
 *
 * Legal query string parameters:
 * 
 * sql = holds sql string
 * nrows = number of rows to return 
 * offset = skip offset rows of data
 * 
 * example:
 *
 * http://localhost/php/server.php?select+*+from+table&nrows=10&offset=2
 */


/* 
 * Define the IP address you want to accept requests from 
 * as a security measure.
 */
$ACCEPTIP = '';

/*
 * Connection parameters
 */
$driver = 'mysql';
$host = 'mangrove'; // DSN for odbc
$uid = 'root';
$pwd = '';
$database = 'xphplens';

/*============================ DO NOT MODIFY BELOW HERE =================================*/
// $sep must match csv2rs() in adodb.inc.php
$sep = ' :::: ';

include('./adodb.inc.php');

function err($s)
{
	die('**** '.$s.' ');
}

// undo stupid magic quotes
function undomq(&$m) 
{
	if (get_magic_quotes_gpc()) {
		// undo the damage
		$m = str_replace('\"','"',$m);
		$m = str_replace('\\\'','\'',$m);
		
	}
	return $m;
}

///////////////////////////////////////// DEFINITIONS


if (isset($REMOTE_ADDR)) $remote = $REMOTE_ADDR; // Apache
else $remote = $_SERVER["REMOTE_ADDR"]; // IIS
 
if (empty($_GET['sql'])) err('No SQL');

if (!empty($ACCEPTIP))
 if ($remote != '127.0.0.1' && $remote != $ACCEPTIP) 
 	err("Unauthorised client: '$remote'");


$conn = &ADONewConnection($driver);
if (!$conn->Connect($host,$uid,$pwd,$database)) err($conn->ErrorNo(). $sep . $conn->ErrorMsg());
$sql = undomq($_GET['sql']);

if (isset($_GET['nrows'])) {
	$nrows = $_GET['nrows'];
	$offset = isset($_GET['offset']) ? $_GET['offset'] : -1;
	$rs = $conn->SelectLimit($sql,$nrows,$offset);
} else 
	$rs = $conn->Execute($sql);
if ($rs){ 
	//$rs->timeToLive = 1;
	print rs2csv($rs,$conn,$sql);
	$rs->Close();
} else
	err($conn->ErrorNo(). $sep .$conn->ErrorMsg());

?>