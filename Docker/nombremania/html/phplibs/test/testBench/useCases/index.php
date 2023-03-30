<HTML>

<HEAD>

  <META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">

  <TITLE>Php.XPath Interactive Test Suite</TITLE>

  <link href="../../doc/XPath.css" rel="STYLESHEET" type="text/css">

</HEAD>

<BODY>



<table border="0" width="100%">

  <tr>

  	<th class="BANNER">Php.XPath Interactive Test Suite</th>

	</tr>

</table>



<!--#################### Start Content ######################### -->

<?





// ################### Globals ########################### //

$xmlDirectory = getcwd().'/xml/';



if (!isset($_GET['HideDescription']) && !isset($_POST['HideDescription'])) {

?>

<p class="description">

This example is written to show you how you can use the various

XPath.class.php functions.  You need to have a basic grasp of the

<a href="http://www.w3.org/TR/xpath" target="_blank">XPath</a>

language to access and even modify XML documents. Please take a look

at the official <a href="http://www.w3.org/TR/xpath" target="_blank">

XPath Recommendation</a> or - which may be better - at the

<a href="http://www.zvon.org/xxl/XPathTutorial/General/examples.html"

target="_blank">ZVON XPath Tutorial</a>. Without knowing the XPath

syntax this example may be very boring... ;-)

</p>

    

<p class="description">

To test first select the XML file you want to

use for your testing. You can view the content of an XML file by

clicking the <i>View All</i> button. After that insert your XPath

expression and click on the <i>Search</i> link.

</p>



<p class="description">

For enhanced testing try the other buttons.  The use of the <i>Value</i>

field will depend on which button you test, and it's usage should be

obvious.

</p>



<p align="right">

<A HREF="<?=basename(__FILE__)?>?HideDescription=1">[Hide Description]</A>

</p>

<?

} else {

?>

<p align="right">

<A HREF="<?=basename(__FILE__)?>">[Show Description]</A>

</p>

<?

}

?>



<CENTER>

<FORM ACTION="<?=basename(__FILE__)?>" METHOD="POST">

	<?

		if (isset($_GET['HideDescription']) || isset($_POST['HideDescription'])) {

	?><INPUT NAME="HideDescription" TYPE="hidden" VALUE="1"><?

		}

	?>

	<TABLE border=1 CLASS="INPUTFORM" WIDTH="300" CELLPADDING="10">

		<tr>

			<th CLASS="INPUTFORM">

				<nobr>XML File Name</nobr></th>

			<td CLASS="INPUTFORM">



				<select name="XmlFile" style="width:80%">        

				<?php

					$file = '';

					// Create an object to access the current directory.

					$directory = dir($xmlDirectory);

            

					// Run through all files of the current directory.

					while ( $entry = $directory->read() )	{

						// Check whether it's an XML file.

						if ( eregi("\.xml$", $entry) )	{

							// Add an entry for this file.

							echo "<option value=\"$entry\"";

                    

							// Check whether this file is selected.

							if ( $entry == $file )	{

								// Select the entry.

								echo " selected";

							}

                    

							// Close the option tag.

							echo ">$entry</option>\n";

						}

					}

            

					// Close the directory.

					$directory->close();

        

					if (isset($_POST['XmlFile'])) {

						$entry = stripslashes($_POST['XmlFile']);

						echo "<option selected value=\"$entry\">$entry</option>";

					}

				?>

        

				</select>

			</td>

		</tr>

		<TR>

			<TH CLASS="INPUTFORM" ALIGN="RIGHT">

				XPath expression</TH>

			<TD CLASS="INPUTFORM"><INPUT NAME="XPath" SIZE="80" VALUE="<?=(isset($_POST['XPath'])) ? stripslashes($_POST['XPath']) : '//*'?>"></TD>

		</tr>

		<tr>

			<th CLASS="INPUTFORM" ALIGN="RIGHT">

				Value</th>

			<TD CLASS="INPUTFORM">

				<INPUT NAME="Value" SIZE="80" VALUE="<?=(isset($_POST['Value'])) ? stripslashes($_POST['Value']) : 'Some string (only require for some tests)'?>"></TD>

		</tr>

	</table>

	<table>

		<tr>

			<TD CLASS="INPUTFORM" ROWSPAN="2" ALIGN="CENTER">

				<INPUT TYPE="Submit" NAME="Action" VALUE="View All"></TD>

			<TD CLASS="INPUTFORM" ROWSPAN="2" ALIGN="CENTER">

				<INPUT TYPE="Submit" NAME="Action" VALUE="Search"></TD>

			<TD CLASS="INPUTFORM" ROWSPAN="2" ALIGN="CENTER">

				<INPUT TYPE="Submit" NAME="Action" VALUE="Replace Content"></TD>

			<TD CLASS="INPUTFORM" ROWSPAN="2" ALIGN="CENTER">

				<INPUT TYPE="Submit" NAME="Action" VALUE="Remove Child"></TD>				

		</TR>

	</TABLE>

</FORM>

</CENTER>



<?php

// If they have filled in the form yet, then give them the output of the search

if (count($_POST)) {

  // If there is input, then we have to add the record to the database and give them the file.

  

  require_once("test/XPath.class.php");

  

  // Wrap this code in a function to protect against global variables.

  function Main() {

  	global $_POST;	

  

  	echo "<h2>Configuration</h2>\n";

  	print_r($_POST);

  

  	//////////////////////////////////////////////

  	// Construct the array for the entry we are to add to the database.

  

  	$xPath = new XPath();



	global $xmlDirectory;

  

  	$xPath->importFromFile($xmlDirectory.$_POST['XmlFile']);


    switch ($_POST['Action']) {

  	  case 'View All':

    		echo "<h2 CLASS=\"TestName\">Viewing</h2>\n";

  		  echo '<hr><pre>';

  		  echo $xPath->exportAsHtml();

  		  echo '</pre>';

        break;

      case 'Search': 

  		  echo "<h2 CLASS=\"TestName\">Searching</h2>\n";

    		$aResult = $xPath->evaluate(stripslashes($_POST['XPath']));

    		echo count($aResult).' nodes matching xpath query:'.stripslashes($_POST['XPath']).':<hr><pre>';

    		print_r($aResult);

    		echo '<hr>';

    		echo $xPath->exportAsHtml('', $aResult);

    		echo '</pre>';

        break;

      case 'Replace Content':

    		echo "<h2 CLASS=\"TestName\">Replacing data</h2>\n";

    		echo '<p>Replacing data of "'.stripslashes($_POST['XPath']);

    		echo '" with "'.stripslashes($_POST['Value'])."\"</p>\n";

    		$aResult = $xPath->replaceData(stripslashes($_POST['XPath']), stripslashes($_POST['Value']));

    		echo '<hr><pre>';

    		echo $xPath->exportAsHtml('', array($_POST['XPath']));

    		echo '</pre>';

        break;

      case 'Remove Child':

    		echo "<h2 CLASS=\"TestName\">Remove</h2>\n";

    		$xPath->removeChild(stripslashes($_POST['XPath']));

    		echo '<hr><pre>';

    		echo $xPath->exportAsHtml();

    		echo '</pre>';

        break;

  	  default:

  		  echo "<h2>Error, function test not written yet</h2>";

  	} // END switch

  }

  

  Main();



} // if (!count($_GET))



?>



<!--#################### End Content ######################### -->		



</BODY>

</HTML>

