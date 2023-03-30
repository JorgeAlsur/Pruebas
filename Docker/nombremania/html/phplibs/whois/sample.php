<head>
<title>whois2.php -base classes to do whois queries with php</title>
</head>
<body bgcolor="ffffff">
<blockquote>
<pre>
<blockquote>
<?
if(isSet($query)) {
include("main.whois");
$whois = new Whois($query);
$result = $whois->Lookup();
echo "<b>Results for  $query :</b><p>";
if(isSet($whois->Query["errstr"])) {
echo "<b>Errors:</b><br>".implode($whois->Query["errstr"],"<br>");
}
if($output=="object") {
	include("utils.whois");
	$utils = new utils;
	$utils->showObject($result);
} else {
	if(!empty($result["rawdata"])) {
                echo implode($result["rawdata"],"\n");
        } else { echo "<br>No Match"; }       
}

}
?>
</blockquote>
</pre>
</blockquote>
<center>
<table>
<tr><td bgcolor="55aaff">
<form method="post" action="<?echo $SCRIPT_NAME; ?>">
<table>
<tr><td colspan=2><b>Enter any domain name you would like to query whois for<b></td></tr>
<tr><td align=center colspan=2><input name="query">
<input type="submit" value="Whois"></td></tr>
<tr><td colspan=2>
<input type="radio" name="output" value="normal" checked> Show me regular output
</td></tr>
<tr><td>
<input type="radio" name="output" value="object"> Show me the returned PHP object
</td>
<td align=right valign=bottom>
<a href="http://www.easydns.com/~markjr/whois2/">
<img border=0 src="whois2-icon.gif" alt=""><br>
</a>
</td>
</tr>

</table>
</form>
</td></tr>
</table>
</center>
