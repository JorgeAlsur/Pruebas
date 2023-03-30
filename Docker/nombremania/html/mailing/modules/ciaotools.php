<?php
# Ciao-Tools - a group of miscelaneous function utilities.
# Copyright (C) 2000,2001 Benjamin Drushell
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published
# by the Free Software Foundation; version 2.1 of the License.
#
# This program is distributed in the hope that it will be useful.
# There is NO WARRANTY.  NO implied warranty of MERCHANTABILITY.
# NO implied warranty of FITNESS FOR A PARTICULAR PURPOSE.
# The entire risk is with you.
# See the GNU Lesser General Public License for more details.
#
# A copy of the GNU Lesser General Public License is included with this program
# and is also available at http://www.technobreeze.com/license/lgpl.txt
#---------------------------------------------------------
# FILE: ciaotools.php
# VERSION: 0.0.06
# LAST UPDATED ON: 2000.10.30
# CONTRIBUTORS:
# 2000.12.19 - Wayne Davis - Removed empty array [] in code for PHP4 compatability
# 2001.01.29 - Ben Drushell (BD) - Removed some PHP3.09 specific code with PHP3/PHP4 compatable code
# 2001.01.30 - Tom Sokolis - Modified XML parser functions for use with "magic_quotes" settings.
# 2001.03.18 - BD - Updated the encode/decode functions and added shift variable
# 2001.06.13 - BD - Updated the random generator
# 2001.06.15 - BD - Updated to include new list export module
#---------------------------------------------------------
?>

<?php
class CiaoTools
{
    var $shift = 0;
    var $body_background = "";
    var $body_bgcolor = "#ffcc00";
    var $body_Text = "#000000";
    var $body_Link = "#666666";
    var $body_title = "#000099";
    var $table_bgcolor = "#CCCCCC";
    var $table_row = "#ff6600";
    var $table_row_text = "#000099";
    var $table_row_link = "#660000";
    var $table_altrow = "#99cc66";
    var $table_altrow_text = "#000099";
    var $table_altrow_link = "#660000";
    var $table_Text = "#000000";
    var $table_Link = "#666666";
    var $menu_bgcolor = "#ffffcc";
    var $menu_Link = "#000099";

    function head(&$VARS)
    {
        if(! file_exists("gpl.txt"))
        { die(""); }
        if(! file_exists("ciaocredits.html"))
        { die(""); }
?>
<html>
<head><title>Ciao-ELM (C) Ben Drushell</title></head>
<body bgcolor="<?php echo $this->body_bgcolor ?>" background="<?php echo $this->body_background ?>" text="<?php echo $this->body_Text ?>" link="<?php echo $this->body_Link ?>" alink="<?php echo $this->body_CurrentLink ?>" vlink="<?php echo $this->body_Link ?>">
<?php 
        if (file_exists('modules/ciaodb.php') && file_exists('modules/ciaocfg.php') && file_exists('modules/template.ciao'))
        {
?>
<table width="100%" border="0" cellpadding="5" cellspacing="0">
<tr><td width="100%" colspan="2" bgcolor="<?php echo $this->table_bgcolor ?>" align="center">
<b>
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&logout=1" style="color:<?php echo $this->table_Link ?>;text-decoration: none"><font color="<?php echo $this->table_Link ?>">Log Out</font></a>
&nbsp; &nbsp;
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=w" style="color:<?php echo $this->table_Link ?>;text-decoration: none"><font color="<?php echo $this->table_Link ?>">Stats</font></a>
&nbsp; &nbsp;
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=w01" style="color:<?php echo $this->table_Link ?>;text-decoration: none"><font color="<?php echo $this->table_Link ?>">My Profile</font></a>
&nbsp; &nbsp;
<a href="ciaohelp.html" style="color:<?php echo $this->table_Link ?>;text-decoration: none" target="_blank"><font color="<?php echo $this->table_Link ?>">Help</font></a>
</b>
&nbsp; &nbsp;
<a href="gpl.txt" style="color:<?php echo $this->table_Link ?>;text-decoration: none" target="_blank"><font color="<?php echo $this->table_Link ?>">GNU GPL License</font></a>
&nbsp; &nbsp;
<a href="ciaocredits.html" style="color:<?php echo $this->table_Link ?>;text-decoration: none" target="_blank"><font color="<?php echo $this->table_Link ?>">Credits</font></a>
&nbsp; &nbsp;
&nbsp; &nbsp;
<font color="<?php echo $this->table_Text ?>" size="-1"><i>Ciao ELM (C) Ben Drushell</i></font>
</td></tr>
<tr><td width="200" valign="top">

<br>
<table border="1" width="100%" bgcolor="<?php echo $this->table_bgcolor ?>"><tr><td align="center"><font color="<?php echo $this->table_Text ?>" size="+2">
Mail Utilities
</font></td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=m01&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Sent Message Archive</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=m02&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Saved Messages/Templates</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=m03&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Custom Lists</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=m04&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Compose Message</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=m05&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">View/Edit/Delete Subscribers</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=m06&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">View Pending List</font></a><br>
</td></tr>
</table>
<?php
            if($VARS['s'])
            {
?>
<br>
<table border="1" width="100%" bgcolor="<?php echo $this->table_bgcolor ?>"><tr><td align="center"><font color="<?php echo $this->table_Text ?>" size="+2">
Admin Utilities
</font></td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s00&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Database Setup</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s10&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">DB Upgrade Utility</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s01&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">General Configuration</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s02&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Category Administration</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s03&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Public Sign-up Page Configuration</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s04&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">User Administration</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s05&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Blocking List</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s09&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Admin Access Log</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s06&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">E-mail List Import</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s08&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">E-mail List Export</font></a><br>
</td></tr>
<tr><td align="center" bgcolor="<?php echo $this->menu_bgcolor ?>">
<a href="ciaoadm.php?x=s07&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>" style="color:<?php echo $this->menu_Link ?>;text-decoration: none"><font color="<?php echo $this->menu_Link ?>">Admin Color Settings</font></a><br>
</td></tr>
</table>
<?php 
            }
?>
</td><td valign="top"><br><br>
<?php 
            $VARS['ShowTail'] = 1;
        }
    }

    function tail($VARS)
    {
        if($VARS['ShowTail'] != '')
        {
?>
</td></tr>
</table>
<?php 
        }
?>
</body>
</html>
<?php
    }

    function CiaoEncode($input,$shift='')
    {
        $output = "";
        if($shift == '')
        { $shift = $this->shift; }

        $output = $input;
        $output2 = "";
        if($shift == 0)
        { $output = base64_encode($input); }
        elseif($shift == 1)
        { $output = addslashes($input); }
        else
        {
            for($j=0;$j < strlen($output);$j++)
            {
                $decimal = ord(substr($output,$j,1)) + ($shift % 50);
                if($decimal >= 255)
                { $decimal = $decimal - 254; }
                $output2 .= chr($decimal);
            }
            $output = base64_encode($output2);
            if($shift % 2)
            { $output = strrev($output); }
        }
        return($output);
    }

    function CiaoDecode($input,$shift='')
    {
        if($shift == '')
        { $shift = $this->shift; }

        if($shift == 1)
        { $output = $input; }
        else
        { $output = trim($input); }

        if($shift == 0)
        { $output = base64_decode($output); }
        elseif($shift == 1)
        { $output = stripslashes($output); }
        else
        {
            if($shift % 2)
            { $output = strrev($output); }
            $output = base64_decode($output);
            $output2 = "";
            for($j=0;$j < strlen($output);$j++)
            {
                $decimal = ord(substr($output,$j,1)) - ($shift % 50);
                if($decimal < 1)
                { $decimal = $decimal + 254; }
                $output2 .= chr($decimal);
            }
            $output = $output2;
        }
        return($output);
    }

    function GenerateID($length = 32)
    {
        $pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $pool .= "abcdefghijklmnopqrstuvwxyz";
        $pool .= "0123456789";
        for($index = 0; $index < $length; $index++)
        {
            $id .= substr($pool,(rand(0,1000)%(strlen($pool))),1);
            srand(time() + $index);
        }
        return($id);
    }

    function PARSE_XML_STATEMENT($parameters)
    {
        $contents = array(); # line change contributed by Wayne Davis for PHP4
        $xml = array(); # line change contributed by Wayne Davis for PHP4

        $contents = split("=[[:space:]]*",$parameters);

        $temp1 = trim(ereg_replace("[[:space:]]","",reset($contents)));
        $last = end($contents);

        reset($contents); next($contents);
        while (list($KEY,$VALUE) = each($contents))
        {
            $temp = split("\"",$VALUE);
            $xml[$temp1] = trim($temp[1]);
            $temp1 = trim(ereg_replace("[[:space:]]","",$temp[2]));
        }

        $temp = split("\"",$last);

        $xml[$temp1] = trim(ereg_replace("[[:space:]]","",$temp[1]));
        return($xml);
    }

    function PARSE_XML_DOC($filename,&$xml)
    {
        if(! file_exists($filename . ".ciao"))
        { return(array("","")); }
    # reads contents of file
        $raw_doc = "";
        $html = array(); # line change contributed by Wayne Davis for PHP4
        $XML_DOC = fopen($filename . ".ciao","r");
        while(!feof($XML_DOC))
        { $raw_doc .= fgets($XML_DOC,255); }
        fclose($XML_DOC);
    # finished reading contents of the file

    # make compliable with magic quotes PHP server settings - Tom Sokolis

    if($xml['DB_magic_quotes_runtime'] && $xml['DB_magic_quotes_sybase'])
    { $raw_doc = stripslashes(ereg_replace("''","'",$raw_doc)); }
    elseif($xml['DB_magic_quotes_runtime'])
    { $raw_doc = stripslashes($raw_doc); }

    # end magic quotes section

    # parses contents of CIAO tag
        $start_point = strpos($raw_doc,"<#CIAO");
        if (! is_int($start_point))
        { $start_point = strpos($raw_doc,"<" . chr(37) . "CIAO"); }
        $start_point += 6;
        $xml_doc = substr($raw_doc,$start_point,strlen($raw_doc) - $start_point);
        $html[0] = substr($raw_doc,0,$start_point - 6);
        $end_point = strpos($xml_doc,"#>",0);
        if (! is_int($end_point))
        { $end_point = strpos($xml_doc,chr(37) . ">"); }
        $html[1] = substr($xml_doc,$end_point + 2,strlen($xml_doc) - ($end_point + 2));
        $xml_doc = substr($xml_doc,0,$end_point);
        $xml = $this->PARSE_XML_STATEMENT($xml_doc);
    # finished parsing CIAO tag

        return($html);
    }

# Function "getpost_vars" retrieves all get/post form variables and performs
# actions on them based on input. The total affect is that "magic_quotes_gpc"
# is always "on", "magic_quotes_sybase" is only on when needed.
    function getpost_vars($mq_gpc,$mq_sybase,$sybase)
    {
        $VARS = array();
        global $_POST;
        global $_GET;

    # read in variables
        if(gettype($_POST) == 'array')
        {
            while(list($key,$value) = each($_POST))
            { $VARS[$key] = $value; }
        }
        if(gettype($_GET) == 'array')
        {
            while(list($key,$value) = each($_GET))
            { $VARS[$key] = $value; }
        }

    ## MAGIC_QUOTES_GPC && MAGIC_QUOTES_SYBASE ##
    ## start 8 condition test for all situations based on 3 true/false variables for each condition
        if($mq_gpc && (! $mq_sybase) && (! $sybase))
        { # true; false; false; => do not need to do anything
            # while(list($key,$value) = each($VARS))
            # { $VARS[$key] = $value; }
        }
        elseif($mq_gpc && (! $mq_sybase) && $sybase)
        { # true; false; true; => need to replace \' with ''
            while(list($key,$value) = each($VARS))
            { $VARS[$key] = str_replace("\'","''",$value); }
        }
        elseif($mq_gpc && $mq_sybase && (! $sybase))
        { # true; true; false; => need to remove extra single quotes
            while(list($key,$value) = each($VARS))
            { $VARS[$key] = str_replace("''","'",$value); }
        }
        elseif($mq_gpc && $mq_sybase && $sybase)
        { # true; true; true; => do not need to do anything
            # while(list($key,$value) = each($VARS))
            # { $VARS[$key] = $value; }
        }
        elseif((! $mq_gpc) && (! $sybase))
        { # false; true/false; false; => need to add slashes
            while(list($key,$value) = each($VARS))
            { $VARS[$key] = addslashes($value); }
        }
        else  #  elseif((! $mq_gpc) && $sybase)
        { # false; true/false; true; => need to add slashes and then replace \' with ''
            while(list($key,$value) = each($VARS))
            { $VARS[$key] = str_replace("\'","''",addslashes($value)); }
        }
    ## end 8 condition test... last 2 conditions count as 4 in true/false logic
    #############################################
        return($VARS);
    }
}
?>
