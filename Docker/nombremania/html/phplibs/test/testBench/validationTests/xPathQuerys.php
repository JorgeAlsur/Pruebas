<?php
////////////////////////////////////////////////////////////////////////////
// xPathQuerys.php
//
// Basic tests for the phpXpath machine.  
?>
<html>
<head>
  <link href="../../doc/XPath.css" rel="STYLESHEET" type="text/css">
</head>
<body>
<table border="0" width="100%">
  <tr>
  	<th class="BANNER">Php.XPath Verification Pass</th>
	</tr>
</table>

<? 
echo "Test run date: ".date ("d F Y H:i:s.");

////////////////////////////////////////////////////////////////////////////
// Setup.

// obtain the version of Php.XPath that the user wishes to test.

////////////////////////////////
// Default to the current development version.
$IncludeFileName = '../../../XPath-develop/XPath.class.php';
if (isset($_GET["version"]) 
    && file_exists($_GET["version"])
    && strcasecmp($_GET["version"],$IncludeFileName)) {
  $IncludeFileName = $_GET["version"];
} 

echo "<p>Testing using the $IncludeFileName version</p>\n<hr>";

require_once($IncludeFileName);

////////////////////////////////////////////////////////////////////////////
// Local classes

class PhpXPath_XPathTest {

  ///////////////////////////////
  // Members

  // The xml object that we are currently testing.
  var $xml;

  // The array of xpath queries
  var $xQueryArr = array();

  // The debug output from each successive depth of test grouping.
  var $testRunOutput;
  var $testOutput;
  var $subtestOutput;

  // The time we started the tests.
  var $totalBeginTime;
  var $totalEndTime;

  // Total tests passed and failed.
  var $iPassCount;
  var $iFailCount;
  
  // Keep track of the total error count
  var $errorNumber;

  ///////////////////////////////

  // constructor
  function PhpXPath_XPathTest() {
    $this->testRunOutput = '';
    $this->testOutput = '';
    $this->subtestOutput = '';
    $this->errorNumber = 0;
  }

  ///////////////////////////////
  // public members.

  // Run all the tests.
  function Run() {
    $this->totalBeginTime = microtime();

    $this->testRunOutput = $this->ErrorLinks();

    $this->iPassCount = 0;
    $this->iFailCount = 0;
    for ($testNr=1; $testNr<=24; $testNr++) {
      if ($this->RunTest($testNr)) $this->iPassCount++; else $this->iFailCount++;

      // Add the test output to the output for this test run.
      $this->testRunOutput .= $this->testOutput;
    }

    ///////////////////////////
    // Record Total taken
    $this->totalEndTime = microtime();

    $this->testRunOutput .= "<h1>".($testNr-1)." Test run results</h1>\n";
    $this->testRunOutput .= $this->ResultsSummary();      

    $this->testRunOutput .= $this->ErrorLinks();
  }

  // Run test number 
  function RunTest($testNr) {
    ///////////////////////////
    // Setup for this test
    $this->testOutput = "<h1>$testNr Test</h1>\n";
    // get time
    $beginTime = microtime();
    // New xPath object
    $this->xml = new XPath();

    // get test data
    $xmlData = trim(getXmlData($testNr));
    $this->xml->importFromString($xmlData);
    // get xPath query
    $this->xQueryArr = &getXmlQuery($testNr);

    ///////////////////////////
    // Test x: Loop over the xPath querys for this test
    $iPassCount = 0;
    $iFailCount = 0;
    for ($subTestNr=0; $subTestNr<sizeOf($this->xQueryArr); $subTestNr++) {
      if ($this->RunSubTest($testNr, $subTestNr)) {
        $iPassCount++; 
        $ErrorLog = '';
      } else {
        $iFailCount++;
        $ErrorLog = $this->ErrorLinks();
        $this->testOutput .= $ErrorLog;
      }         

      // Add the sub test output to the output for this test.
      $this->testOutput .= $this->subtestOutput;
    }

    $this->testOutput .= "<h2>Test #{$testNr} results:</h2>\n";
    $this->testOutput .= "Passed ".$iPassCount." of ".($iPassCount + $iFailCount).' sub tests<br>';

    ///////////////////////////
    // take time used
    $totBeginT = explode(' ', $this->totalBeginTime);
    $beginT = explode(' ', $beginTime);
    $endT   = explode(' ',  microtime());
    $delta = (round( (($endT[1] - $beginT[1]) + ($endT[0] - $beginT[0]))*1000 ));
    $deltaTot = (round( (($endT[1] - $totBeginT[1]) + ($endT[0] - $totBeginT[0]))*1000 ));
    // some output
    $this->testOutput .= "Test #{$testNr} used <strong>{$delta} ms</strong>. (Total so far {$deltaTot} ms)\n";

    // If you don't want to output every test then comment out this line.
    //echo $this->testOutput;
    $this->testOutput .='<hr>';
    return ($iFailCount == 0);
  }

  // Runs sub test $subTestNr of test $testNr.  Returns true if the sub test passed, 
  // else returns false.  The "debug" output for the test run will be stored in the 
  // subtestOutput member
  function RunSubTest($testNr, $subTestNr) {
    $this->subtestOutput = "<h2>$testNr.$subTestNr Test</h2>\n";
    $this->subtestOutput .= '<table border="1"  style="font-size:12px;" >' . "\n";
    // evaluate xPathQuery
    $result = $this->xml->evaluate($this->xQueryArr[$subTestNr]);
    // get control results
    $controlResult = &getXmlResult($testNr, $subTestNr);
    // some output
    $xResultData  = nl2br(str_replace('  ', '&nbsp;&nbsp;&nbsp;&nbsp;', $this->xml->exportAsHtml('',$result)));
    $xControlData = nl2br(str_replace('  ', '&nbsp;&nbsp;&nbsp;&nbsp;', $this->xml->exportAsHtml('',$controlResult)));

    $this->subtestOutput .= "<tr bgcolor='#bcd6f1'><td colspan='2' align='center'> <strong>xQuery = </strong>'{$this->xQueryArr[$subTestNr]}' </td></tr>\n";
    $this->subtestOutput .= "<tr bgcolor='#ebf3fe' style='font-size:11px; font-family:verdana'><td> $xResultData </td><td> $xControlData </td></tr>\n";
    $this->subtestOutput .= "<tr bgcolor='#C0C0C0'><td align='center'>Recived</td><td align='center'>Expected</td></tr>\n";
    // Test if result matches expected
    $matchNr = max(sizeOf($result), sizeOf($controlResult));
    $bAllPassed = TRUE;
    for ($i=0; $i<$matchNr; $i++) {
      $match = FALSE;
      if (!isSet($result[$i]))        $result[$i] = '- missing -';
      if (!isSet($controlResult[$i])) $controlResult[$i] = '- missing -';
      if ($result[$i] == $controlResult[$i]) {
         $match = TRUE;
      } else {
        $bAllPassed = FALSE;
      }
      $bg = $match ? "bgcolor='#dffade'" : "bgcolor='#ff9d9d'";
      $this->subtestOutput .= "<tr {$bg}><td>{$result[$i]}</td><td>{$controlResult[$i]}</td></tr>\n";
    }

    $this->subtestOutput .= "</table><br>";

    return $bAllPassed;
  }

  function ResultsSummary() {
    $Result = "<p>Passed $this->iPassCount of ".($this->iPassCount + $this->iFailCount).' tests</p>';

    ///////////////////////////
    // Total time used
    $totBeginT = explode(' ', $this->totalBeginTime);
    $endT   = explode(' ',  $this->totalEndTime);
    $deltaTot = (round( (($endT[1] - $totBeginT[1]) + ($endT[0] - $totBeginT[0]))*1000 ));
    // some output
    $Result .= "Total test run time {$deltaTot}ms\n";  
    return $Result;
  }

  ///////////////////////////////
  // private members.

  // Call every time you have an error.  Allows you to quickly navigate between the errors.
  // in a test log.
  function ErrorLinks() {
    $Result = "<div align=\"right\" style=\"float:right;width=200\">\n";
    if ($this->errorNumber == 0) {
      $Result .= "<a Name=\"Error0\" href=\"#Error".
                              ($this->errorNumber + 1)."\"><NOBR>Goto Next Error (".($this->errorNumber + 1).")</NOBR></a>\n";
    } else {
      $Result .= "<a Name=\"Error$this->errorNumber\" href=\"#Error".
                              ($this->errorNumber - 1)."\"><NOBR>Goto Previous Error (".($this->errorNumber - 1).")</NOBR></a><br>\n";
      $Result .= "<a href=\"#Error".
                              ($this->errorNumber + 1)."\"><NOBR>Goto Next Error (".($this->errorNumber + 1).")</NOBR></a>\n";
    }
    $Result .= "</div>\n";

    $this->errorNumber++;

    return $Result;
  }

}

////////////////////////////////////////////////////////////////////////////

/* Recon we should change to storing tests like this.  What do you think Sam?
 * Would make it easier to manage and add tests I recon.

  $iTestNumber = 1;

  // Test Nr:1 
  $TestData[$iTestNumber]['XmlQuery'][] = '/AAA';
  $TestData[$iTestNumber]['XmlQuery'][] = '/AAA/CCC';
  $TestData[$iTestNumber]['XmlQuery'][] = '/AAA/DDD/BBB';
 
  // xQuery = '/AAA'
  $TestData[$iTestNumber]['XmlResult'][0][] = '/AAA[1]';
  // xQuery = '/AAA/CCC'
  $TestData[$iTestNumber]['XmlResult'][1][] = '/AAA[1]/CCC[1]';
  $TestData[$iTestNumber]['XmlResult'][1][] = '/AAA[1]/CCC[2]';
  // xQuery = '/AAA/DDD/BBB'
  $TestData[$iTestNumber]['XmlResult'][2][] = '/AAA[1]/DDD[1]/BBB[1]';

  $TestData[$iTestNumber]['Xml'] = <<< EOD
    <AAA> 
        <BBB/> 
        <CCC/> 
        <BBB/> 
        <BBB/> 
        <DDD> 
             <BBB/> 
        </DDD> 
        <CCC/> 
   </AAA>
EOD;
*/



// Returns the XML Query for test $testNr
function &getXmlQuery($testNr) {
  static $xQuery = array();
  if (!empty($xQuery)) return $xQuery[$testNr];
  
  $xQuery[1][] = '/AAA';
  $xQuery[1][] = '/AAA/CCC';
  $xQuery[1][] = '/AAA/DDD/BBB';
  
  $xQuery[2][] = '//BBB';
  $xQuery[2][] = '//DDD/BBB';
  
  $xQuery[3][] = '/AAA/CCC/DDD/*';
  $xQuery[3][] = '/*/*/*/BBB';
  $xQuery[3][] = '//*';
  
  $xQuery[4][] = '/AAA/BBB[1]';
  $xQuery[4][] = '/AAA/BBB[last()]';
  
  $xQuery[5][] = '//@id';
  $xQuery[5][] = '//*[@id]';
  $xQuery[5][] = '//BBB[@id]';
  $xQuery[5][] = '//BBB[@name]';
  $xQuery[5][] = '//BBB[@*]';
  $xQuery[5][] = '//BBB[not(@*)]';
  
  $xQuery[6][] = '//BBB[@id=\'b1\']';
  $xQuery[6][] = '//BBB[@name="bbb"]';
//  $xQuery[6][] = '//BBB[normalize-space(@name)=\'bbb\']';
  
  $xQuery[7][] = '//*[count(BBB)=2]';
  $xQuery[7][] = '//*[count(*)=2]';
  $xQuery[7][] = '//*[count(*)=3]';
  
  $xQuery[8][] = '//*[name()=\'BBB\']';
  $xQuery[8][] = '//*[starts-with(name(),\'B\')]';
  $xQuery[8][] = '//*[contains(name(),\'C\')]';
  
  $xQuery[9][] = '//*[string-length(name()) = 3]';
  $xQuery[9][] = '//*[string-length(name()) < 3]';
  $xQuery[9][] = '//*[string-length(name()) > 3]';
  
  $xQuery[10][] = '//CCC | //BBB';
  $xQuery[10][] = '/AAA/EEE | //BBB';
  $xQuery[10][] = '/AAA/EEE | //DDD/CCC | /AAA | //BBB';
  
  $xQuery[11][] = '/AAA';
  $xQuery[11][] = '/child::AAA';
  $xQuery[11][] = '/AAA/BBB';
  $xQuery[11][] = '/child::AAA/child::BBB';
  $xQuery[11][] = '/child::AAA/BBB';
  
  $xQuery[12][] = '/descendant::*';
  $xQuery[12][] = '/AAA/BBB/descendant::*';
  $xQuery[12][] = '//CCC/descendant::*';
  $xQuery[12][] = '//CCC/descendant::DDD';
  
  $xQuery[13][] = '//DDD/parent::*';
  
  $xQuery[14][] = '/AAA/BBB/DDD/CCC/EEE/ancestor::*';
  $xQuery[14][] = '//FFF/ancestor::*';
  
  $xQuery[15][] = '/AAA/BBB/following-sibling::*';
  $xQuery[15][] = '//CCC/following-sibling::*';
  
  $xQuery[16][] = '/AAA/XXX/preceding-sibling::*';
  $xQuery[16][] = '//CCC/preceding-sibling::*';
  
  $xQuery[17][] = '/AAA/XXX/following::*';
  $xQuery[17][] = '//ZZZ/following::*';
  
  $xQuery[18][] = '/AAA/XXX/preceding::*';
  $xQuery[18][] = '//FFF/preceding::*';
  
  $xQuery[19][] = '/AAA/XXX/descendant-or-self::*';
  $xQuery[19][] = '//CCC/descendant-or-self::*';
  
  $xQuery[20][] = '/AAA/XXX/DDD/EEE/ancestor-or-self::*';
  $xQuery[20][] = '//GGG/ancestor-or-self::*';
  
  $xQuery[21][] = '//GGG/ancestor::*';
  $xQuery[21][] = '//GGG/descendant::*';
  $xQuery[21][] = '//GGG/following::*';
  $xQuery[21][] = '//GGG/preceding::*';
  $xQuery[21][] = '//GGG/self::*';
  $xQuery[21][] = '//GGG/ancestor::* | //GGG/descendant::* | //GGG/following::* | //GGG/preceding::* | //GGG/self::*';
  
  $xQuery[22][] = '//BBB[position() mod 2 = 0 ]';
  $xQuery[22][] = '//BBB[ position() = floor(last() div 2 + 0.5) or position() = ceiling(last() div 2 + 0.5) ]';
  $xQuery[22][] = '//CCC[ position() = floor(last() div 2 + 0.5) or position() = ceiling(last() div 2 + 0.5) ]';
  
  $xQuery[23][] = '//person/*[contains(., "Jos")]/..';

  $xQuery[24][] = '/AAA/BBB/CCC';

  return $xQuery[$testNr];
}

////////////////////////////////////////////////////////////////////////////

// Returns the expected results of test $testNr, sub test $subTestNr
function &getXmlResult($testNr, $subTestNr) {
  static $xResult = array();
  if (!empty($xResult)) return $xResult[$testNr][$subTestNr];

  // Test Nr:1 
  // xQuery = '/AAA'
  $xResult[1][0][] = '/AAA[1]';
  // xQuery = '/AAA/CCC'
  $xResult[1][1][] = '/AAA[1]/CCC[1]';
  $xResult[1][1][] = '/AAA[1]/CCC[2]';
  // xQuery = '/AAA/DDD/BBB'
  $xResult[1][2][] = '/AAA[1]/DDD[1]/BBB[1]';
  
  // Test Nr:2 
  // xQuery = '//BBB'
  $xResult[2][0][] = '/AAA[1]/BBB[1]';
  $xResult[2][0][] = '/AAA[1]/BBB[2]';
  $xResult[2][0][] = '/AAA[1]/DDD[1]/BBB[1]';
  $xResult[2][0][] = '/AAA[1]/CCC[2]/DDD[1]/BBB[1]';
  $xResult[2][0][] = '/AAA[1]/CCC[2]/DDD[1]/BBB[2]';
  // xQuery = '//DDD/BBB'
  $xResult[2][1][] = '/AAA[1]/DDD[1]/BBB[1]';
  $xResult[2][1][] = '/AAA[1]/CCC[2]/DDD[1]/BBB[1]';
  $xResult[2][1][] = '/AAA[1]/CCC[2]/DDD[1]/BBB[2]';
  
  // Test Nr:3 
  // xQuery = '/AAA/CCC/DDD/*'
  $xResult[3][0][] = '/AAA[1]/CCC[1]/DDD[1]/BBB[1]';
  $xResult[3][0][] = '/AAA[1]/CCC[1]/DDD[1]/BBB[2]';
  $xResult[3][0][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]';
  $xResult[3][0][] = '/AAA[1]/CCC[1]/DDD[1]/FFF[1]';
  // xQuery = '/*/*/*/BBB'
  $xResult[3][1][] = '/AAA[1]/XXX[1]/DDD[1]/BBB[1]';
  $xResult[3][1][] = '/AAA[1]/XXX[1]/DDD[1]/BBB[2]';
  $xResult[3][1][] = '/AAA[1]/CCC[1]/DDD[1]/BBB[1]';
  $xResult[3][1][] = '/AAA[1]/CCC[1]/DDD[1]/BBB[2]';
  $xResult[3][1][] = '/AAA[1]/CCC[2]/BBB[1]/BBB[1]';
  // xQuery = '//*'
  $xResult[3][2][] = '/AAA[1]';
  $xResult[3][2][] = '/AAA[1]/XXX[1]';
  $xResult[3][2][] = '/AAA[1]/XXX[1]/DDD[1]';
  $xResult[3][2][] = '/AAA[1]/XXX[1]/DDD[1]/BBB[1]';
  $xResult[3][2][] = '/AAA[1]/XXX[1]/DDD[1]/BBB[2]';
  $xResult[3][2][] = '/AAA[1]/XXX[1]/DDD[1]/EEE[1]';
  $xResult[3][2][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]';
  $xResult[3][2][] = '/AAA[1]/CCC[1]';
  $xResult[3][2][] = '/AAA[1]/CCC[1]/DDD[1]';
  $xResult[3][2][] = '/AAA[1]/CCC[1]/DDD[1]/BBB[1]';
  $xResult[3][2][] = '/AAA[1]/CCC[1]/DDD[1]/BBB[2]';
  $xResult[3][2][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]';
  $xResult[3][2][] = '/AAA[1]/CCC[1]/DDD[1]/FFF[1]';
  $xResult[3][2][] = '/AAA[1]/CCC[2]';
  $xResult[3][2][] = '/AAA[1]/CCC[2]/BBB[1]';
  $xResult[3][2][] = '/AAA[1]/CCC[2]/BBB[1]/BBB[1]';
  $xResult[3][2][] = '/AAA[1]/CCC[2]/BBB[1]/BBB[1]/BBB[1]';
  
  // Test Nr:4 
  // xQuery = '/AAA/BBB[1]'
  $xResult[4][0][] = '/AAA[1]/BBB[1]';
  // xQuery = '/AAA/BBB[last()]'
  $xResult[4][1][] = '/AAA[1]/BBB[4]';
  
  // Test Nr:5 
  // xQuery = '//@id'
  $xResult[5][0][] = '/AAA[1]/BBB[1]/attribute::id';
  $xResult[5][0][] = '/AAA[1]/BBB[2]/attribute::id';
  $xResult[5][0][] = '/AAA[1]/CCC[2]/attribute::id';
  // xQuery = '//@id'
  $xResult[5][1][] = '/AAA[1]/BBB[1]';
  $xResult[5][1][] = '/AAA[1]/BBB[2]';
  $xResult[5][1][] = '/AAA[1]/CCC[2]';

  // xQuery = '//BBB[@id]'
  $xResult[5][2][] = '/AAA[1]/BBB[1]';
  $xResult[5][2][] = '/AAA[1]/BBB[2]';
  // xQuery = '//BBB[@name]'
  $xResult[5][3][] = '/AAA[1]/BBB[3]';
  // xQuery = '//BBB[@*]'
  $xResult[5][4][] = '/AAA[1]/BBB[1]';
  $xResult[5][4][] = '/AAA[1]/BBB[2]';
  $xResult[5][4][] = '/AAA[1]/BBB[3]';
  // xQuery = '//BBB[not(@*)]'
  $xResult[5][5][] = '/AAA[1]/BBB[4]';
  
  // Test Nr:6 
  // xQuery = '//BBB[@id='b1']'
  $xResult[6][0][] = '/AAA[1]/BBB[1]';
  // xQuery = '//BBB[@name="bbb"]'
  $xResult[6][1][] = '/AAA[1]/BBB[2]';
  $xResult[6][1][] = '/AAA[1]/BBB[3]';
  // xQuery = '//BBB[normalize-space(@name)='bbb']'
  $xResult[6][2][] = '/AAA[1]/BBB[2]';
  $xResult[6][2][] = '/AAA[1]/BBB[3]';
  
  // Test Nr:7 
  // xQuery = '//*[count(BBB)=2]'
  $xResult[7][0][] = '/AAA[1]/DDD[1]';
  // xQuery = '//*[count(*)=2]'
  $xResult[7][1][] = '/AAA[1]/DDD[1]';
  $xResult[7][1][] = '/AAA[1]/EEE[1]';
  // xQuery = '//*[count(*)=3]'
  $xResult[7][2][] = '/AAA[1]';
  $xResult[7][2][] = '/AAA[1]/CCC[1]';
  
  // Test Nr:8 
  // xQuery = '//*[name()='BBB']'
  $xResult[8][0][] = '/AAA[1]/BCC[1]/BBB[1]';
  $xResult[8][0][] = '/AAA[1]/BCC[1]/BBB[2]';
  $xResult[8][0][] = '/AAA[1]/BCC[1]/BBB[3]';
  $xResult[8][0][] = '/AAA[1]/DDB[1]/BBB[1]';
  $xResult[8][0][] = '/AAA[1]/DDB[1]/BBB[2]';
  // xQuery = '//*[starts-with(name(),'B')]'
  $xResult[8][1][] = '/AAA[1]/BCC[1]';
  $xResult[8][1][] = '/AAA[1]/BCC[1]/BBB[1]';
  $xResult[8][1][] = '/AAA[1]/BCC[1]/BBB[2]';
  $xResult[8][1][] = '/AAA[1]/BCC[1]/BBB[3]';
  $xResult[8][1][] = '/AAA[1]/DDB[1]/BBB[1]';
  $xResult[8][1][] = '/AAA[1]/DDB[1]/BBB[2]';
  $xResult[8][1][] = '/AAA[1]/BEC[1]';
  // xQuery = '//*[contains(name(),'C')]'
  $xResult[8][2][] = '/AAA[1]/BCC[1]';
  $xResult[8][2][] = '/AAA[1]/BEC[1]';
  $xResult[8][2][] = '/AAA[1]/BEC[1]/CCC[1]';
  
  // Test Nr:9 
  // xQuery = '//*[string-length(name()) = 3]'
  $xResult[9][0][] = '/AAA[1]';
  $xResult[9][0][] = '/AAA[1]/CCC[1]';
  // xQuery = '//*[string-length(name()) < 3]'
  $xResult[9][1][] = '/AAA[1]/Q[1]';
  $xResult[9][1][] = '/AAA[1]/BB[1]';
  // xQuery = '//*[string-length(name()) > 3]'
  $xResult[9][2][] = '/AAA[1]/SSSS[1]';
  $xResult[9][2][] = '/AAA[1]/DDDDDDDD[1]';
  $xResult[9][2][] = '/AAA[1]/EEEE[1]';
  
  // Test Nr:10 
  // xQuery = '//CCC | //BBB'
  $xResult[10][0][] = '/AAA[1]/CCC[1]';
  $xResult[10][0][] = '/AAA[1]/DDD[1]/CCC[1]';
  $xResult[10][0][] = '/AAA[1]/BBB[1]';
  // xQuery = '/AAA/EEE | //BBB'
  $xResult[10][1][] = '/AAA[1]/EEE[1]';
  $xResult[10][1][] = '/AAA[1]/BBB[1]';
  // xQuery = '/AAA/EEE | //DDD/CCC | /AAA | //BBB'
  $xResult[10][2][] = '/AAA[1]/EEE[1]';
  $xResult[10][2][] = '/AAA[1]/DDD[1]/CCC[1]';
  $xResult[10][2][] = '/AAA[1]';
  $xResult[10][2][] = '/AAA[1]/BBB[1]';
  
  // Test Nr:11 
  // xQuery = '/AAA'
  $xResult[11][0][] = '/AAA[1]';
  // xQuery = '/child::AAA'
  $xResult[11][1][] = '/AAA[1]';
  // xQuery = '/AAA/BBB'
  $xResult[11][2][] = '/AAA[1]/BBB[1]';
  // xQuery = '/child::AAA/child::BBB'
  $xResult[11][3][] = '/AAA[1]/BBB[1]';
  // xQuery = '/child::AAA/BBB'
  $xResult[11][4][] = '/AAA[1]/BBB[1]';
  
  // Test Nr:12 
  // xQuery = '/descendant::*'
  $xResult[12][0][] = '/AAA[1]';
  $xResult[12][0][] = '/AAA[1]/BBB[1]';
  $xResult[12][0][] = '/AAA[1]/BBB[1]/DDD[1]';
  $xResult[12][0][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]';
  $xResult[12][0][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]/DDD[1]';
  $xResult[12][0][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]/EEE[1]';
  $xResult[12][0][] = '/AAA[1]/CCC[1]';
  $xResult[12][0][] = '/AAA[1]/CCC[1]/DDD[1]';
  $xResult[12][0][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]';
  $xResult[12][0][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]/DDD[1]';
  $xResult[12][0][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]/DDD[1]/FFF[1]';
  // xQuery = '/AAA/BBB/descendant::*'
  $xResult[12][1][] = '/AAA[1]/BBB[1]/DDD[1]';
  $xResult[12][1][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]';
  $xResult[12][1][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]/DDD[1]';
  $xResult[12][1][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]/EEE[1]';
  // xQuery = '//CCC/descendant::*'
  $xResult[12][2][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]/DDD[1]';
  $xResult[12][2][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]/EEE[1]';
  $xResult[12][2][] = '/AAA[1]/CCC[1]/DDD[1]';
  $xResult[12][2][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]';
  $xResult[12][2][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]/DDD[1]';
  $xResult[12][2][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]/DDD[1]/FFF[1]';
  // xQuery = '//CCC/descendant::DDD'
  $xResult[12][3][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]/DDD[1]';
  $xResult[12][3][] = '/AAA[1]/CCC[1]/DDD[1]';
  $xResult[12][3][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]/DDD[1]';
  
  // Test Nr:13 
  // xQuery = '//DDD/parent::*'
  $xResult[13][0][] = '/AAA[1]/BBB[1]';
  $xResult[13][0][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]';
  $xResult[13][0][] = '/AAA[1]/CCC[1]';
  $xResult[13][0][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]';
  
  // Test Nr:14 
  // xQuery = '/AAA/BBB/DDD/CCC/EEE/ancestor::*'
  $xResult[14][0][] = '/AAA[1]/BBB[1]/DDD[1]/CCC[1]';
  $xResult[14][0][] = '/AAA[1]/BBB[1]/DDD[1]';
  $xResult[14][0][] = '/AAA[1]/BBB[1]';
  $xResult[14][0][] = '/AAA[1]';
  // xQuery = '//FFF/ancestor::*'
  $xResult[14][1][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]/DDD[1]';
  $xResult[14][1][] = '/AAA[1]/CCC[1]/DDD[1]/EEE[1]';
  $xResult[14][1][] = '/AAA[1]/CCC[1]/DDD[1]';
  $xResult[14][1][] = '/AAA[1]/CCC[1]';
  $xResult[14][1][] = '/AAA[1]';
  
  // Test Nr:15 
  // xQuery = '/AAA/BBB/following-sibling::*'
  $xResult[15][0][] = '/AAA[1]/XXX[1]';
  $xResult[15][0][] = '/AAA[1]/CCC[1]';

  // xQuery = '//CCC/following-sibling::*'
  $xResult[15][1][] = '/AAA[1]/BBB[1]/DDD[1]';
  $xResult[15][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]';
  $xResult[15][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[2]';
  
  // Test Nr:16 
  // xQuery = '/AAA/XXX/preceding-sibling::*'
  $xResult[16][0][] = '/AAA[1]/BBB[1]';
  // xQuery = '//CCC/preceding-sibling::*'
  $xResult[16][1][] = '/AAA[1]/XXX[1]/DDD[1]/EEE[1]';
  $xResult[16][1][] = '/AAA[1]/XXX[1]/DDD[1]/DDD[1]';
  $xResult[16][1][] = '/AAA[1]/BBB[1]';
  $xResult[16][1][] = '/AAA[1]/XXX[1]';  
  
  // Test Nr:17 
  // xQuery = '/AAA/XXX/following::*'
  $xResult[17][0][] = '/AAA[1]/CCC[1]';
  $xResult[17][0][] = '/AAA[1]/CCC[1]/DDD[1]';
  // xQuery = '//ZZZ/following::*'
  $xResult[17][1][] = '/AAA[1]/BBB[1]/FFF[1]';
  $xResult[17][1][] = '/AAA[1]/BBB[1]/FFF[1]/GGG[1]';
  $xResult[17][1][] = '/AAA[1]/XXX[1]';
  $xResult[17][1][] = '/AAA[1]/XXX[1]/DDD[1]';
  $xResult[17][1][] = '/AAA[1]/XXX[1]/DDD[1]/EEE[1]';
  $xResult[17][1][] = '/AAA[1]/XXX[1]/DDD[1]/DDD[1]';
  $xResult[17][1][] = '/AAA[1]/XXX[1]/DDD[1]/CCC[1]';
  $xResult[17][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]';
  $xResult[17][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[2]';
  $xResult[17][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[2]/GGG[1]';
  $xResult[17][1][] = '/AAA[1]/CCC[1]';
  $xResult[17][1][] = '/AAA[1]/CCC[1]/DDD[1]';  
  
  // Test Nr:18 
  // xQuery = '/AAA/XXX/preceding::*'
  $xResult[18][0][] = '/AAA[1]/BBB[1]';
  $xResult[18][0][] = '/AAA[1]/BBB[1]/CCC[1]';  
  $xResult[18][0][] = '/AAA[1]/BBB[1]/ZZZ[1]';
  $xResult[18][0][] = '/AAA[1]/BBB[1]/ZZZ[1]/DDD[1]';

  // xQuery = '//GGG/preceding::*'
  $xResult[18][1][] = '/AAA[1]/BBB[1]';
  $xResult[18][1][] = '/AAA[1]/BBB[1]/CCC[1]';
  $xResult[18][1][] = '/AAA[1]/BBB[1]/ZZZ[1]';
  $xResult[18][1][] = '/AAA[1]/BBB[1]/ZZZ[1]/DDD[1]';
  $xResult[18][1][] = '/AAA[1]/XXX[1]/DDD[1]/EEE[1]';
  $xResult[18][1][] = '/AAA[1]/XXX[1]/DDD[1]/DDD[1]';
  $xResult[18][1][] = '/AAA[1]/XXX[1]/DDD[1]/CCC[1]';
  $xResult[18][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]';
  
  // Test Nr:19 
  // xQuery = '/AAA/XXX/descendant-or-self::*'
  $xResult[19][0][] = '/AAA[1]/XXX[1]';
  $xResult[19][0][] = '/AAA[1]/XXX[1]/DDD[1]';
  $xResult[19][0][] = '/AAA[1]/XXX[1]/DDD[1]/EEE[1]';
  $xResult[19][0][] = '/AAA[1]/XXX[1]/DDD[1]/DDD[1]';
  $xResult[19][0][] = '/AAA[1]/XXX[1]/DDD[1]/CCC[1]';
  $xResult[19][0][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]';
  $xResult[19][0][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[2]';
  $xResult[19][0][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[2]/GGG[1]';
  // xQuery = '//CCC/descendant-or-self::*'
  $xResult[19][1][] = '/AAA[1]/BBB[1]/CCC[1]';
  $xResult[19][1][] = '/AAA[1]/XXX[1]/DDD[1]/CCC[1]';
  $xResult[19][1][] = '/AAA[1]/CCC[1]';
  $xResult[19][1][] = '/AAA[1]/CCC[1]/DDD[1]';
  
  // Test Nr:20 
  // xQuery = '/AAA/XXX/DDD/EEE/ancestor-or-self::*'
  $xResult[20][0][] = '/AAA[1]/XXX[1]/DDD[1]/EEE[1]';
  $xResult[20][0][] = '/AAA[1]/XXX[1]/DDD[1]';
  $xResult[20][0][] = '/AAA[1]/XXX[1]';
  $xResult[20][0][] = '/AAA[1]';
  // xQuery = '//GGG/ancestor-or-self::*'
  $xResult[20][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[2]/GGG[1]';
  $xResult[20][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[2]';
  $xResult[20][1][] = '/AAA[1]/XXX[1]/DDD[1]';
  $xResult[20][1][] = '/AAA[1]/XXX[1]';
  $xResult[20][1][] = '/AAA[1]';
  
  // Test Nr:21 
  // xQuery = '//GGG/ancestor::*'
  $xResult[21][0][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]';
  $xResult[21][0][] = '/AAA[1]/XXX[1]/DDD[1]';
  $xResult[21][0][] = '/AAA[1]/XXX[1]';
  $xResult[21][0][] = '/AAA[1]';
  // xQuery = '//GGG/descendant::*'
  $xResult[21][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/GGG[1]/JJJ[1]';
  $xResult[21][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/GGG[1]/JJJ[1]/QQQ[1]';
  $xResult[21][1][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/GGG[1]/JJJ[2]';
  // xQuery = '//GGG/following::*'
  $xResult[21][2][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/HHH[2]';
  $xResult[21][2][] = '/AAA[1]/CCC[1]';
  $xResult[21][2][] = '/AAA[1]/CCC[1]/DDD[1]';
  // xQuery = '//GGG/preceding::*'
  $xResult[21][3][] = '/AAA[1]/BBB[1]';
  $xResult[21][3][] = '/AAA[1]/BBB[1]/CCC[1]';
  $xResult[21][3][] = '/AAA[1]/BBB[1]/ZZZ[1]';
  $xResult[21][3][] = '/AAA[1]/XXX[1]/DDD[1]/EEE[1]';
  $xResult[21][3][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/HHH[1]';
  
  
  // xQuery = '//GGG/self::*'
  $xResult[21][4][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/GGG[1]';
  // xQuery = '//GGG/ancestor::* | //GGG/descendant::* | //GGG/following::* | //GGG/preceding::* | //GGG/self::*'
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]';
  $xResult[21][5][] = '/AAA[1]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/GGG[1]/JJJ[1]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/GGG[1]/JJJ[1]/QQQ[1]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/GGG[1]/JJJ[2]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/HHH[2]';
  $xResult[21][5][] = '/AAA[1]/CCC[1]';
  $xResult[21][5][] = '/AAA[1]/CCC[1]/DDD[1]';
  $xResult[21][5][] = '/AAA[1]/BBB[1]';
  $xResult[21][5][] = '/AAA[1]/BBB[1]/CCC[1]';
  $xResult[21][5][] = '/AAA[1]/BBB[1]/ZZZ[1]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]/EEE[1]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/HHH[1]';
  $xResult[21][5][] = '/AAA[1]/XXX[1]/DDD[1]/FFF[1]/GGG[1]';
  
  // Test Nr:22 
  // xQuery = '//BBB[position() mod 2 = 0 ]'
  $xResult[22][0][] = '/AAA[1]/BBB[2]';
  $xResult[22][0][] = '/AAA[1]/BBB[4]';
  $xResult[22][0][] = '/AAA[1]/BBB[6]';
  $xResult[22][0][] = '/AAA[1]/BBB[8]';
  // xQuery = '//BBB[ position() = floor(last() div 2 + 0.5) or position() = ceiling(last() div 2 + 0.5) ]'
  $xResult[22][1][] = '/AAA[1]/BBB[4]';
  $xResult[22][1][] = '/AAA[1]/BBB[5]';  
  // xQuery = '//CCC[ position() = floor(last() div 2 + 0.5) or position() = ceiling(last() div 2 + 0.5) ]'
  $xResult[22][2][] = '/AAA[1]/CCC[2]';
  
  // Test Nr:23
  // xQuery = '//person/*[contains(., "Jos")]/..' 
  $xResult[23][0][] = '/government[1]/person[2]';

  // Test Nr:24
  // xQuery = '/AAA/BBB/CCC'; 
  $xResult[24][0][] = '/AAA[1]/BBB[1]/CCC[1]';
  $xResult[24][0][] = '/AAA[1]/BBB[2]/CCC[1]';
  
  return $xResult[$testNr][$subTestNr];
}

////////////////////////////////////////////////////////////////////////////

// Retursn the xml data to test with for test $testNr
function &getXmlData($testNr) {
  static $xmlData = array();
  if (!empty($xmlData)) return $xmlData[$testNr];
  
  // Sample 1
  $xmlData[] = '';
  $xmlData[] = <<< EOD
    <AAA> 
        <BBB/> 
        <CCC/> 
        <BBB/> 
        <BBB/> 
        <DDD> 
             <BBB/> 
        </DDD> 
        <CCC/> 
   </AAA>
EOD;

  // Sample 2
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB/> 
          <CCC/> 
          <BBB/> 
          <DDD>
               <BBB/>
          </DDD> 
          <CCC>
               <DDD>
                    <BBB/>
                    <BBB/>
               </DDD>
          </CCC> 
     </AAA>
EOD;

  // Sample 3
  $xmlData[] = <<< EOD
     <AAA> 
          <XXX>
               <DDD>
                    <BBB/>
                    <BBB/>
                    <EEE/>
                    <FFF/>
               </DDD>
          </XXX> 
          <CCC>
               <DDD>
                    <BBB/>
                    <BBB/>
                    <EEE/>
                    <FFF/>
               </DDD>
          </CCC> 
          <CCC>
               <BBB>
                    <BBB>
                         <BBB/>
                    </BBB>
               </BBB>
          </CCC> 
     </AAA>
EOD;

  // Sample 4
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB/> 
          <BBB/> 
          <BBB/> 
          <BBB/> 
     </AAA>
EOD;

  // Sample 5
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB id = "b1"/> 
          <BBB id = "b2"/> 
          <BBB name = "bbb"/> 
          <BBB/> 
          <CCC name = "c1"/> 
          <CCC id = "c2"/> 
     </AAA>
EOD;

  // Sample 6
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB id = "b1"/> 
          <BBB name = 'bbb'/> 
          <BBB name = "bbb"/> 
     </AAA>
EOD;

  // Sample 7
  $xmlData[] = <<< EOD
       <AAA> 
          <CCC>
               <BBB/>
               <BBB/>
               <BBB/>
          </CCC> 
          <DDD>
               <BBB/>
               <BBB/>
          </DDD> 
          <EEE>
               <CCC/>
               <DDD/>
          </EEE> 
     </AAA>
EOD;

  // Sample 8
  $xmlData[] = <<< EOD
     <AAA> 
          <BCC>
               <BBB/>
               <BBB/>
               <BBB/>
          </BCC> 
          <DDB>
               <BBB/>
               <BBB/>
          </DDB> 
          <BEC>
               <CCC/>
               <DBD/>
          </BEC> 
     </AAA>
EOD;

  // Sample 9
  $xmlData[] = <<< EOD
     <AAA> 
          <Q/> 
          <SSSS/> 
          <BB/> 
          <CCC/> 
          <DDDDDDDD/> 
          <EEEE/> 
     </AAA>
EOD;

  // Sample 10
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB/> 
          <CCC/> 
          <DDD>
               <CCC/>
          </DDD> 
          <EEE/> 
     </AAA>
EOD;

  // Sample 11
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB/> 
          <CCC/> 
     </AAA>
EOD;

  // Sample 12
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <DDD>
                    <CCC>
                         <DDD/>
                         <EEE/>
                    </CCC>
               </DDD>
          </BBB> 
          <CCC>
               <DDD>
                    <EEE>
                         <DDD>
                              <FFF/>
                         </DDD>
                    </EEE>
               </DDD>
          </CCC> 
     </AAA>
EOD;

  // Sample 13
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <DDD>
                    <CCC>
                         <DDD/>
                         <EEE/>
                    </CCC>
               </DDD>
          </BBB> 
          <CCC>
               <DDD>
                    <EEE>
                         <DDD>
                              <FFF/>
                         </DDD>
                    </EEE>
               </DDD>
          </CCC> 
     </AAA>
EOD;

  // Sample 14
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <DDD>
                    <CCC>
                         <DDD/>
                         <EEE/>
                    </CCC>
               </DDD>
          </BBB> 
          <CCC>
               <DDD>
                    <EEE>
                         <DDD>
                              <FFF/>
                         </DDD>
                    </EEE>
               </DDD>
          </CCC> 
     </AAA>
EOD;

  // Sample 15
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <CCC/>
               <DDD/>
          </BBB> 
          <XXX>
               <DDD>
                    <EEE/>
                    <DDD/>
                    <CCC/>
                    <FFF/>
                    <FFF>
                         <GGG/>
                    </FFF>
               </DDD>
          </XXX> 
          <CCC>
               <DDD/>
          </CCC> 
     </AAA>
EOD;

  // Sample 16
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <CCC/>
               <DDD/>
          </BBB> 
          <XXX>
               <DDD>
                    <EEE/>
                    <DDD/>
                    <CCC/>
                    <FFF/>
                    <FFF>
                         <GGG/>
                    </FFF>
               </DDD>
          </XXX> 
          <CCC>
               <DDD/>
          </CCC> 
     </AAA>
EOD;

  // Sample 17
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <CCC/>
               <ZZZ>
                    <DDD/>
                    <DDD>
                         <EEE/>
                    </DDD>
               </ZZZ>
               <FFF>
                    <GGG/>
               </FFF>
          </BBB> 
          <XXX>
               <DDD>
                    <EEE/>
                    <DDD/>
                    <CCC/>
                    <FFF/>
                    <FFF>
                         <GGG/>
                    </FFF>
               </DDD>
          </XXX> 
          <CCC>
               <DDD/>
          </CCC> 
     </AAA>
EOD;

  // Sample 18
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <CCC/>
               <ZZZ>
                    <DDD/>
               </ZZZ>
          </BBB> 
          <XXX>
               <DDD>
                    <EEE/>
                    <DDD/>
                    <CCC/>
                    <FFF/>
                    <FFF>
                         <GGG/>
                    </FFF>
               </DDD>
          </XXX> 
          <CCC>
               <DDD/>
          </CCC> 
     </AAA>
EOD;

  // Sample 19
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <CCC/>
               <ZZZ>
                    <DDD/>
               </ZZZ>
          </BBB> 
          <XXX>
               <DDD>
                    <EEE/>
                    <DDD/>
                    <CCC/>
                    <FFF/>
                    <FFF>
                         <GGG/>
                    </FFF>
               </DDD>
          </XXX> 
          <CCC>
               <DDD/>
          </CCC> 
     </AAA>
EOD;

  // Sample 20
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <CCC/>
               <ZZZ>
                    <DDD/>
               </ZZZ>
          </BBB> 
          <XXX>
               <DDD>
                    <EEE/>
                    <DDD/>
                    <CCC/>
                    <FFF/>
                    <FFF>
                         <GGG/>
                    </FFF>
               </DDD>
          </XXX> 
          <CCC>
               <DDD/>
          </CCC> 
     </AAA>
EOD;

  // Sample 21
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB>
               <CCC/>
               <ZZZ/>
          </BBB> 
          <XXX>
               <DDD>
                    <EEE/>
                    <FFF>
                         <HHH/>
                         <GGG>
                              <JJJ>
                                   <QQQ/>
                              </JJJ>
                              <JJJ/>
                         </GGG>
                         <HHH/>
                    </FFF>
               </DDD>
          </XXX> 
          <CCC>
               <DDD/>
          </CCC> 
     </AAA>
EOD;

  // Sample 22
  $xmlData[] = <<< EOD
     <AAA> 
          <BBB/> 
          <BBB/> 
          <BBB/> 
          <BBB/> 
          <BBB/> 
          <BBB/> 
          <BBB/> 
          <BBB/> 
          <CCC/> 
          <CCC/> 
          <CCC/> 
     </AAA>
EOD;

  // Sample 23
  $xmlData[] = <<< EOD
    <government>
      <person>
        <prename>Gerhard</prename>
        <surname>Schröder</surname>
        <position>Chancellor of the Federal Republic of Germany</position>
        <party>SPD</party>
      </person>
    
      <person>
        <prename>Josef</prename>
        <surname>Fischer</surname>
        <position>Federal Minister for Foreign Affairs Deputy Chancellor of the Federal Republic of Germany</position>
        <party>Bündnis 90/Die Grünen</party>
      </person>
    </government>
EOD;

  // Sample 24
  $xmlData[] = <<< EOD
  <AAA>
    <BBB>
      <CCC/>
    </BBB>
    <BBB>
      <CCC/>
    </BBB>
  </AAA> 
EOD;
  return $xmlData[$testNr];
}

////////////////////////////////////////////////////////////////////////////
// Run the tests.
  
// $testRunOutput = "<br><strong>xPath check</strong>\n";

$Test = new PhpXPath_XPathTest();
$Test->Run();

// Print out a status report for the complete run.
echo '<h1>Results summary</h1>';
echo $Test->ResultsSummary();
echo '<hr>';

echo $Test->testRunOutput;

?>
