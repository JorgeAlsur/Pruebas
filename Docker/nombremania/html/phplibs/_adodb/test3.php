<code>
<?php
/*
  V1.20 25 June 2001 (c) 2000, 2001 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under Lesser GPL library license. See License.txt.
  Set tabs to 8.
 */
#test Move
include("adodb.inc.php");	

$c1 = &ADONewConnection('postgres');
if (!$c1->PConnect("susetikus","tester","test","test")) 
	die("Cannot connect to database");

# select * from last table in DB
$rs = $c1->Execute("select * from adoxyz order by 1"); 

$i = 0;
$max = $rs->RecordCount();
if ($max == -1) "RecordCount returns -1<br>";
while (!$rs->EOF and $i < $max) {
	$rs->Move($i);
	print_r( $rs->fields);
	print '<BR>';
	$i++;
}
?>
</code>