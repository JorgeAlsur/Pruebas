<HTML>
<HEAD>
	<TITLE>Php.XPath Documentation.</TITLE>
  <link href="../doc/XPath.css" rel="STYLESHEET" type="text/css">
</HEAD>
<BODY>

<table border="0" width="100%">
	<tr bgcolor="#000000">
		 <th><FONT size=5><FONT color=#ffffff 
      face=Arial>Php.XPath documentation</FONT></FONT>
		</th>
	</tr>
</table>

<p>This page lists the automatically generated documentation for the phpxml module.</p>
<p>Last updated: 
<?php
$xmlFile="Php.XPathDocumentation.xml"; 
$xslFile="Php.XPathDocumentation.xsl"; 

$aAttrib = stat($xmlFile);
if ($aAttrib) {
	 echo date ("d F Y H:i:s.", $aAttrib[9]);
}
?>
</p>

<p>To update the documentation, run the GeneratePhpDocumentation.pl script on your 
copy of XPath.class.php and pipe the output to <?=$xmlFile?>.  Reloading this page 
will then show uptodate documentation for your version of Php.XPath.</p>

<!-- ################################################################## -->

<hr>

<?php

function GetFile($FileName) {
	// Open the file and we'll read it's contents
	$hFile = fopen($FileName, "r");

	// Did we open the file ok?
	if (!$hFile) {
		trigger_error("Failed to open the $fileName database file.");
		return '';		
	}

	// Get the relevant object as a string and write it to file
	if (!$Result = fread($hFile, filesize($FileName))) {
		trigger_error("Write error when writing back the $fileName file.");
		$Result = '';		
	}

	if (!fclose($hFile)) {
		trigger_error("Failed to close the $fileNamefile.");
		$Result = '';		
	}

	return $Result;
}

$xslData = GetFile($xslFile);
$xmlData = GetFile($xmlFile);

if (xslt_process($xslData, $xmlData, $result)) {
    echo $result;
} else {
    echo "There was an error that occurred in the XSL transformation...\n";
    echo "\tError number: " . xslt_errno() . "\n";
    echo "\tError string: " . xslt_error() . "\n";
    exit;
}
?>
      
<hr>

</BODY>
</HTML>