<?php
/*******************************************************************************
	phpSniff: HTTP_USER_AGENT Client Sniffer for PHP
	Copyright (C) 2001 Roger Raymond ~ epsilon7@users.sourceforge.net

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*******************************************************************************/

if(!defined('_PHP_SNIFF_INCLUDED')) include('phpSniff.class.php');
if(!defined('_PHP_TIMER_INCLUDED')) include('phpTimer.class.php');
// initialize some vars
if(!isset($UA)) $UA = '';
if(!isset($cc)) $cc = '';
if(!isset($dl)) $dl = '';
if(!isset($am)) $am = '';

$timer = new phpTimer();

$timer->start('main');

$timer->start('client1');

    $client = new phpSniff($UA,0);
    if($cc) $client->_check_cookies = $cc;
    if($dl) $client->_default_language = $dl;
    if($am) $client->_allow_masquerading = $am;
    $client->init();

$timer->stop('client1');

$c1_bg = '#cccccc';
$c2_bg = '#ffffff';
$c3_bg = '#000000';

function makeSelectOption ($link,$text)
{   global $client;
    $o  = "<option value=\"$link\"";
    $o .= $client->property('ua') == strtolower($link) ? ' selected' : '';
    $o .= ">$text</option>";
    print $o;
}

function example ($search,$output)
{   global $c2_bg, $c1_bg, $client;
    ?>
    <tr>
        <td bgcolor="<?php print $c1_bg; ?>"><?php print $search; ?></td>
        <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $output ? 'true' : 'false'; ?></td>
    </tr>
    <?php
}

function is ($search)
{	global $client;
	example($search,$client->is($search));
}
function language_is ($search)
{   global $client;
	example($search,$client->language_is($search));
}

function browser_is ($search)
{   global $client;
	example($search,$client->browser_is($search));
}

?>
<html>
<head><title>phpSniff <?php print $client->_version; ?> on SourceForge</title></head>
<body>
<?php
//  fix for cgi versions of php ~ 6/28/2001 ~ RR
$script_path = getenv('PATH_INFO') ? getenv('PATH_INFO') : getenv('SCRIPT_NAME');
?>
<form name="user_agent_string" method="get" action="<?php print $script_path; ?>">
<p><a href="http://sourceforge.net/project/showfiles.php?group_id=26044">Download</a> |
<a href="http://sourceforge.net/projects/phpsniff/">SourceForge Project Page</a> |
<a href="index.phps">Index Source Code</a> |
<a href="phpSniff.core.phps">phpSniff.core Source Code</a> |
<a href="phpSniff.class.phps">phpSniff.class Source Code</a> |
<a href="CHANGES">CHANGE LOG</a>
</p>
<table border="0" cellpadding="3" cellspacing="0" bgcolor="<?php print $c3_bg; ?>" width="100%">
<tr>
<td align="left" valign="top">
    <font color="#ffffff"><b>CURRENT BROWSER INFORMATION</b></font><br>
    <font color="#ffffff" size="-1">
    <?php printf('phpSniff version : %s - php version : %s</font>',$client->_version, PHP_VERSION); ?>
    </font>
</td>
<td align="right" valign="top">
    <font color="#ffffff">
    <select name="UA">
    <?php
    makeSelectOption('','Your current browser');
    include('user_agent.inc');
    while(list(,$v) = each($user_agent))
    {   makeSelectOption($v,$v);
    }
    ?>
    </select><br>
    <input type="checkbox" name="cc" <?php if($client->_check_cookies) print 'checked'; ?> > Check For Cookies
    <input type="checkbox" name="am" <?php if($client->_allow_masquerading) print 'checked'; ?> > Allow Masquerading
    <input type="text" name="dl" size="7" value="<?php print $client->_default_language; ?>"> Default Language
    <input type="submit" name="submit" value="submit">
    </font>
</td>
</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="<?php print $c3_bg; ?>"><tr>
<td align="right" valign="top">
    <table border="0" cellpadding="3" cellspacing="1" width="100%">
        <tr><td colspan="2" nowrap><font color="#ffcc00">Current Configuration</font></td></tr>
        <tr>
            <td colspan="2"bgcolor="<?php print $c1_bg; ?>"><b>regex used to search HTTP_USER_AGENT string</b><br>
            preg_match_all(&quot;<?php print $client->_browser_regex; ?>&quot;);</td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">$_check_cookies</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->_check_cookies ? 'true' : 'false'; ?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">$_default_language</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->_default_language; ?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">$_allow_masquerading</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->_allow_masquerading ? 'true' : 'false'; ?></td>
        </tr>

        <tr><td colspan="2" nowrap><font color="#ffcc00">$client-&gt;property(<i>property_name</i>);</font></td></tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>"><b>property_name</b></td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><b>return value</b></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">ua</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->get_property('ua');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">browser</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('browser'); ?></td>
        </tr>
		<tr>
            <td bgcolor="<?php print $c1_bg; ?>">long_name</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('long_name');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">version</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('version');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">maj_ver</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('maj_ver');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">min_ver</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('min_ver');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">letter_ver</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('letter_ver');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">javascript</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('javascript');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">platform</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('platform');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">os</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('os');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">cookies</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('cookies') ? 'true' : 'false';?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">ip</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('ip');?></td>
        </tr>
        <tr>
            <td bgcolor="<?php print $c1_bg; ?>">language</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('language');?></td>
        </tr>
		<tr>
            <td bgcolor="<?php print $c1_bg; ?>">gecko</td>
            <td width="100%" bgcolor="<?php print $c2_bg; ?>"><?php print $client->property('gecko');?></td>
        </tr>
    </table>
</td>
<td align="left" valign="top">
    <table border="0" cellpadding="3" cellspacing="1" width="100%">
    <tr><td colspan="2" nowrap><font color="#ffcc00">&nbsp;</font></td></tr>
    <tr>
        <td bgcolor="<?php print $c1_bg; ?>" nowrap><b>search_phrase</b></td>
        <td width="100%" bgcolor="<?php print $c2_bg; ?>" nowrap><b>return boolean</b></td>
    </tr>
    <tr>
        <td bgcolor="<?php print $c3_bg; ?>" colspan="2" nowrap><font color="#ffcc00">$client->browser_is(<i>browser</i>)</font></td>
    </tr>
    <?php
        browser_is('aol');
        browser_is('webtv');
        browser_is('ie');
        browser_is('ie5.5');
        browser_is('ie5up');
        browser_is('ns');
        browser_is('ns5up');
        browser_is('op5up');
        browser_is('ow4');
    ?>
    <tr>
        <td bgcolor="<?php print $c3_bg; ?>" colspan="2" nowrap><font color="#ffcc00">$client->language_is(<i>language</i>)</font></td>
    </tr>
    <?php
        language_is('en');
        language_is('en-us');
        language_is('monkey-us');
        language_is('fr-ca');
        language_is('cz');
    ?>
    <tr>
	<tr>
        <td bgcolor="<?php print $c3_bg; ?>" colspan="2" nowrap><font color="#ffcc00">$client->is(<i>search</i>)</font></td>
    </tr>
    <?php
		is('b:ie6up');
		is('b:ns5up');
		is('l:en');
        is('l:fr-ca');
    ?>
    <tr>
    </table>
</td></tr></table>
</form>
<p>
<?php
$timer->stop('main');
printf("<pre>\n".
       "client instantiation time : %s\n" .
       "page execution time       : %s\n" .
       "</pre>" ,
       $timer->get_current('client1'),
       $timer->get_current('main'));
?>
</p>
<?php
print ('<p align="left"><font size="-2">_______________________________<br>');
print ('&copy;2001 asphyxia fabrications<br></font></p>');
?>
<A href="http://sourceforge.net"> <IMG src="http://sourceforge.net/sflogo.php?group_id=26044" width="88" height="31" border="0" alt="SourceForge Logo"></A>
</body>
</html>