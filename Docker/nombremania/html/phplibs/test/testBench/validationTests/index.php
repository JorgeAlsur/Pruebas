<HTML>
<HEAD>
	<TITLE>Php.XPath test homepage.</TITLE>
  <link href="../../doc/XPath.css" rel="STYLESHEET" type="text/css">
</HEAD>
<BODY>

<table border="0" width="100%">
  <tr>
  	<th class="BANNER">Php.XPath Verification Pass</th>
	</tr>
</table>

<p>This page lists all the validation tests that currently exist for the 
Php.XPath project.</p>

<!-- ################################################################## -->

<hr>
<H1>XPath test.</H1>

<p>This test is used to be sure that the evaluate method returns the correct set
of XML nodes for a given XPath expression.</p>

<FORM method="get" action="xPathQuerys.php">
	<p>Select version to test:
	<select name="version">	
		<option value="../../../XPath-develop/XPath.class.php">Development</option>
		<option value="../../../XPath_V2/XPath.class.php">2.1 Last release</option>
	</select>
	</p>

	<INPUT type="submit" value="Run Xpath test">
</FORM>

<hr>

<H1>DOM test.</H1>

<p>This test is used to be sure that all the methods for manipulating the xml
object using get and set methods perform as expected.</p>

<p><i>Under construction</i></p>

</BODY>
</HTML>