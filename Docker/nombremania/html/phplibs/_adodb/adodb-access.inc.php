<?php
/* 
V1.20 25 June 2001 (c) 2000, 2001 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under Lesser GPL library license. See License.txt. 
  Set tabs to 4 for best viewing.
  
  Latest version is available at http://php.weblogs.com/
  
  Microsoft Access data driver. Requires ODBC. Works only on MS Windows.
*/
if (!defined('_ADODB_ODBC_LAYER')) {
	include(ADODB_DIR."/adodb-odbc.inc.php");
}
 if (!defined('_ADODB_ACCESS')) {
 	define('_ADODB_ACCESS',1);
class  ADODB_access extends ADODB_odbc {	
	var $databaseType = 'access';
	var $hasTop = true;		// support mssql SELECT TOP 10 * FROM TABLE
	var $fmtDate = "#Y-m-d#";
	var $fmtTimeStamp = "#Y-m-d h:i:sA#"; // note not comma
	var $_bindInputArray = false;

	function BeginTrans() { return false;}
	
	function &MetaTables()
	{
		$qid = odbc_tables($this->_connectionID);
		$rs = new ADORecordSet_odbc($qid);
		//print_r($rs);
		$arr = &$rs->GetArray();
		
		$arr2 = array();
		for ($i=0; $i < sizeof($arr); $i++) {
			if ($arr[$i][2] && substr($arr[$i][2],0,4) != 'MSys')
				$arr2[] = $arr[$i][2];
		}
		return $arr2;
	}
}

 
class  ADORecordSet_access extends ADORecordSet_odbc {	
	
	var $databaseType = "access";		
	
	function ADORecordSet_access($id)
	{
		return $this->ADORecordSet_odbc($id);
	}
}
} // class
?>