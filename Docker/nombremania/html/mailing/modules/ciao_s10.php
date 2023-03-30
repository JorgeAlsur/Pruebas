<?php
# Ciao EmailList Manager - a customizable mass e-mail program that is administrator/subscriber friendly.
# Copyright (C) 2000, 2001 Benjamin Drushell
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; version 2 of the License.
#
# This program is distributed in the hope that it will be useful.
# There is NO WARRANTY.  NO implied warranty of MERCHANTABILITY.
# NO implied warranty of FITNESS FOR A PARTICULAR PURPOSE.
# The entire risk is with you.
# See the GNU General Public License for more details.
#
# A copy of the GNU General Public License is included with this program.
#
# For questions regarding the Ciao EmailList Manager license,
# contact Ben Drushell: http://www.technobreeze.com/
#---------------------------------------------------------
# FILE: ciao_s10.php
# LOCAL VERSION: 1.0.09
# CREATED ON: 2001.08.11
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.08.11 - BD (Ben Drushell) - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
# 2001.09.02 - BD - changed pconnect to connect for MySQL to MySQL (different databases) to work
# 2001.10.01 - BD - re-located some of the table locking code so that MySQL works correctly
# 2001.10.02 - BD - fixed the fast-upgrade code by adding a value for the email_nid field... also fixed table-locking in MySQL
#---------------------------------------------------------


# SHORT DESCRIPTION
# This module is used for upgrading from alpha versions of Ciao-ELM
#---------------------------------------------------------


class module
{
    var $BATCH = 23; # number of records to process per cycle of duplication-check-upgrade
    var $ALTBATCH = 61; # number of records to process per cycle of fast-upgrade

    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        if($VARS['frame'] != '')
        {
            $this->HTML_FRAMES($VARS);
        }
        elseif($VARS['frame_UPGRADE'] != '')
        {
            $this->HTML_UPGRADE01($VARS,$SYS);
        }
        elseif($VARS['frame_UPGRADE02'] != '')
        {
            $this->HTML_UPGRADE02($VARS,$SYS);
        }
        elseif($VARS['frame_TOOLBAR'] != '')
        {
            $this->HTML_TOOLBAR();
        }
        else
        {
            $T->head($VARS);
?>
<H2 align="center"><font color="<?php echo $T->body_title ?>">
Ciao-ELM Upgrade Utility
</font></H2>
<?php
            if ($VARS['f_process'] != '')
            {
# connect to old database
                $old_db = mysql_connect($VARS['f_server'],$VARS['f_userid'],$VARS['f_password']);
                mysql_select_db($VARS['f_dbname'],$old_db);

# prepare to validate user
                $tEmail = $T->CiaoEncode($VARS['f2_email'],$VARS['f_shift']);
                $mPassword = md5(urlencode($T->CiaoEncode($VARS['f2_password'],$VARS['f_shift'])) . urlencode($tEmail));
# crypt (*nix) specific code
                $cPassword = crypt($VARS['f2_password'],$tEmail); # crypt-version-replacement
# w!nd*ws specific code (minus the crypt)
#               $cPassword = $T->CiaoEncode($VARS['f2_password'],$VARS['f_shift']); # w!nd*ws-version-replacement

# $mPassword must be 32-char size
                if(strlen($mPassword) > 32)
                { $mPassword = substr($mPassword,0,32); }

# $cPassword must be 16-char size
                if(strlen($cPassword) > 16)
                { $cPassword = substr($cPassword,0,16); }

                $query = "SELECT * FROM " . $VARS['f_tableprefix'] . "_user WHERE email = '" . $tEmail . "' AND (password = '" . $mPassword . "' OR password = '" . $cPassword . "') AND access = 1;";
                $result = mysql_query($query,$old_db);
                if($validadmin = mysql_fetch_array($result,1))
                {
# create another instance of CiaoSQL
                    $tempSQL = new CiaoSQL;
                    $tempSQL->clone($SQL);

                    $query = "SELECT * FROM " . $VARS['f_tableprefix'] . "_mail WHERE finish_dt != '0000-00-00 00:00:00'";
                    $result = mysql_query($query,$old_db);
                    while($mail = mysql_fetch_array($result,1))
                    {
                        $query = "INSERT INTO PREFIX_mail VALUES(";
                        $mail_id = $tempSQL->nid("PREFIX_mail->mail_id");
                        $query .= "'" . $mail_id . "',";
                        $query .= "'0',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($mail['owner_email'],$VARS['f_shift'])) . "',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($mail['subject'],$VARS['f_shift'])) . "',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($mail['body'],$VARS['f_shift'])) . "',";
                        $query .= "'',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($mail['sql_stmt'],$VARS['f_shift'])) . "',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($mail['errors'],$VARS['f_shift'])) . "',";
                        $query .= "'" . $mail['offset'] . "',";
                        $query .= "'" . $mail['start_dt'] . "',";
                        $query .= "'" . $mail['finish_dt'] . "',";
                        $query .= "'',";
                        $query .= "'',";
                        $query .= "''";
                        $query .= ");";
                        $tempSQL->locktable("PREFIX_mail");
                        $tempSQL->q($query);
                        $tempSQL->unlocktable();
                    }

                    $query = "SELECT * FROM " . $VARS['f_tableprefix'] . "_category WHERE cat_id != ''";
                    $result = mysql_query($query,$old_db);
                    while($category = mysql_fetch_array($result,1))
                    {
                        $query = "SELECT * FROM PREFIX_category WHERE cat_id = '" . $category['cat_id'] . "';";
                        $tempSQL->locktable("PREFIX_category");
                        $tempSQL->q($query);
                        $tempSQL->unlocktable();
                        if(! $tempSQL->nextrecord())
                        {
                            $query = "INSERT INTO PREFIX_category VALUES(";
                            $cat_nid = $tempSQL->nid("PREFIX_category->cat_nid");
                            $query .= "'" . $cat_nid . "',";
                            $query .= "'" . $category['cat_id'] . "',";
                            $query .= "'" . $category['cat_name'] . "');";
                            $tempSQL->locktable("PREFIX_category");
                            $tempSQL->q($query);
                            $tempSQL->unlocktable();
                        }
                    }

                    $query = "SELECT * FROM " . $VARS['f_tableprefix'] . "_block WHERE block_id != ''";
                    $result = mysql_query($query,$old_db);
                    while($block = mysql_fetch_array($result,1))
                    {
                        $query = "INSERT INTO PREFIX_block VALUES(";
                        $block_id = $tempSQL->nid("PREFIX_block->block_id");
                        $query .= "'" . $block_id . "',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($block['block_value'],$VARS['f_shift'])) . "',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($block['block_reason'],$VARS['f_shift'])) . "',";
                        $query .= "'" . $block['block_dt'] . "'";
                        $query .= ");";
                        $tempSQL->locktable("PREFIX_block");
                        $tempSQL->q($query);
                        $tempSQL->unlocktable();
                    }

                    $query = "SELECT * FROM " . $VARS['f_tableprefix'] . "_verify WHERE verify_id != ''";
                    $result = mysql_query($query,$old_db);
                    while($verify = mysql_fetch_array($result,1))
                    {
                        $verify_nid = $tempSQL->nid("PREFIX_verify->verify_nid");
                        $query = "INSERT INTO PREFIX_verify VALUES(";
                        $query .= "'" . $verify_nid . "',";
                        $query .= "'" . $verify['verify_id'] . "',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($verify['email_id'],$VARS['f_shift'])) . "',";
                        list($alias,$domain) = split("@",strtolower(trim($T->CiaoDecode($verify['email_id'],$VARS['f_shift']))));
                        $query .= "'" . $T->CiaoEncode($domain) . "',";
                        $password = $T->CiaoDecode($verify['password'],$VARS['f_shift']);
                        if($password == "")
                        { $password = "changethis"; }
                        $query .= "'" . $T->CiaoEncode($password) . "',";
                        $query .= "'" . $verify['signup_dt'] . "'";
                        for($i=1;$i <= (0 + $CFG->optSize);$i++)
                        { $query .= ",'" . $T->CiaoEncode($T->CiaoDecode($verify['option' . $i],$VARS['f_shift'])) . "'"; }
                        $query .= ");";
                        $tempSQL->locktable("PREFIX_verify");
                        $tempSQL->q($query);
                        $tempSQL->unlocktable();
                    }

                    $query = "&f_tableprefix=" . urlencode($T->CiaoEncode($VARS['f_tableprefix']));
                    $query .= "&f_server=" . urlencode($T->CiaoEncode($VARS['f_server']));
                    $query .= "&f_userid=" . urlencode($T->CiaoEncode($VARS['f_userid']));
                    $query .= "&f_password=" . urlencode($T->CiaoEncode($VARS['f_password']));
                    $query .= "&f_dbname=" . urlencode($T->CiaoEncode($VARS['f_dbname']));
                    $query .= "&f2_email=" . urlencode($T->CiaoEncode($VARS['f2_email']));
                    $query .= "&f2_password=" . urlencode($T->CiaoEncode($VARS['f2_password']));
                    $query .= "&f_shift=" . urlencode($VARS['f_shift']);

?>
<script language="javascript">
<!--
var win = window.open("ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=s10&frame=<?php echo $VARS['method'] ?>&o=0<?php echo $query ?>","","width=300,height=175,scrollbars");
// -->
</script>
<h2 align="center">WARNING: do not hit reload button on browser or Ciao-ELM will start upgrading again.<br>
A pop-up window should appear initiating the data transfer.</h2>
<?php

                    mysql_close($old_db);
                    $this->HTML_SETUP("",$VARS,$SYS);
                }
                else
                {
                    $this->HTML_SETUP("ERROR: UNABLE TO VERIFY USER",$VARS,$SYS);
                }

            }
            else
            { $this->HTML_SETUP("",$VARS,$SYS); }
            $T->tail($VARS);
        }
    }

    function HTML_SETUP($ERROR,$VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

?>
<center>

<form method="post" action="ciaoadm.php">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">

<H2 align="center"><font color="<?php echo $T->body_title ?>">
MySQL DATABASE INFORMATION
</font></H2>

<?php
        if(strlen($ERROR) > 0)
        {
?>
<table align="center" bgcolor=""><tr><td>
<?php echo $ERROR ?>
</td></tr></table>
<?php
        }
?>

<table align="center" bgcolor=""><tr><td>
Upgrade requires access to a MySQL server that contains Ciao-ELM (alpha).<br>
If you do not know the following information,<br>
contact your website administrator or provider.<br>
</td></tr></table>

<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">Server:</font></td><td><input type="text" name="f_server" value="<?php echo $VARS['f_server'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">User ID:</font></td><td><input type="text" name="f_userid" value="<?php echo $VARS['f_userid'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password:</font></td><td><input type="password" name="f_password" value="<?php echo $VARS['f_password'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password Verification:</font></td><td><input type="password" name="f_password2" value="<?php echo $VARS['f_password2'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Database Name:</font></td><td><input type="text" name="f_dbname" value="<?php echo $VARS['f_dbname'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Data Encode (optional safety):</font></td><td><input type="text" name="f_shift" value="<?php echo (0 + $VARS['f_shift']) ?>"></td></tr>
<?php
    if (! $VARS['f_tableprefix'])
    { $VARS['f_tableprefix'] = "ciao"; }
?>
<tr><td><font color="<?php echo $T->table_Text ?>">Database Table Prefix:</font></td><td><input type="text" name="f_tableprefix" value="<?php echo $VARS['f_tableprefix'] ?>"></td></tr>
</table>
<br><br><br>
<H2 align="center"><font color="<?php echo $T->body_title ?>">
USER INFORMATION
<?php echo $ERROR ?>
</font></H2>

This validates that you have administrative access to the old database information.
<br><br>

<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">User Email:</font></td><td><input type="text" name="f2_email" value="<?php echo $VARS['f2_email'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password:</font></td><td><input type="password" name="f2_password" value="<?php echo $VARS['f2_password'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password Verification:</font></td><td><input type="password" name="f2_password2" value="<?php echo $VARS['f2_password2'] ?>"></td></tr>
</table>

<br><br>

<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><input type="radio" name="method" value="1" CHECKED>
<font color="<?php echo $T->table_Text ?>">
Upgrade using duplication checks (this can take considerable time)
</font>
</td></tr>
<tr><td><input type="radio" name="method" value="2">
<font color="<?php echo $T->table_Text ?>">
Upgrade using fast method (this would allow duplicate records to get inserted)
</font>
</td></tr>
<tr><td align="center"><input type="submit" value="UPGRADE"></td></tr>
</table>
</form>
</center>
<?php
    }

    function HTML_UPGRADE01($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        if($VARS['frame_UPGRADE'] != 1)
        { $this->BATCH = $this->ALTBATCH; }

# connect to old database
        $old_db = mysql_connect($T->CiaoDecode($VARS['f_server']),$T->CiaoDecode($VARS['f_userid']),$T->CiaoDecode($VARS['f_password']));
        mysql_select_db($T->CiaoDecode($VARS['f_dbname']),$old_db);

# prepare to validate user
        $tEmail = $T->CiaoEncode($T->CiaoDecode($VARS['f2_email']),$VARS['f_shift']);
        $mPassword = md5(urlencode($T->CiaoEncode($T->CiaoDecode($VARS['f2_password']),$VARS['f_shift'])) . urlencode($tEmail));
# crypt (*nix) specific code
        $cPassword = crypt($T->CiaoDecode($VARS['f2_password']),$tEmail); # crypt-version-replacement
# w!nd*ws specific code (minus the crypt)
#       $cPassword = $T->CiaoEncode($T->CiaoDecode($VARS['f2_password']),$VARS['f_shift']); # w!nd*ws-version-replacement

# $mPassword must be 32-char size
        if(strlen($mPassword) > 32)
        { $mPassword = substr($mPassword,0,32); }

# $cPassword must be 16-char size
        if(strlen($cPassword) > 16)
        { $cPassword = substr($cPassword,0,16); }

        $query = "SELECT * FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_user WHERE email = '" . $tEmail . "' AND (password = '" . $mPassword . "' OR password = '" . $cPassword . "') AND access = 1;";
        $result = mysql_query($query,$old_db);
        if($validadmin = mysql_fetch_array($result,1))
        {
# create another instance of CiaoSQL
            $tempSQL = new CiaoSQL;
            $tempSQL->clone($SQL);

            $reload=0;

            $query = "SELECT COUNT(*) FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_catlist WHERE cat_id != '';";
            $result = mysql_query($query,$old_db);
            $catlist_count = mysql_fetch_array($result,1);
            if($catlist_count["COUNT(*)"] >= (1 * $VARS['o']))
            {
                $query = "SELECT * FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_catlist WHERE cat_id != '' LIMIT " . (1 * $VARS['o']) . "," . $this->BATCH . ";";
                $result = mysql_query($query,$old_db);
                $tempSQL->locktable("PREFIX_catlist");
                while($catlist = mysql_fetch_array($result,1))
                {
                    if($VARS['frame_UPGRADE'] == 1)
                    {
                        $query = "SELECT * FROM PREFIX_catlist WHERE cat_id = '" . $catlist['cat_id'] . "' AND email_id = '" . $T->CiaoEncode($T->CiaoDecode($catlist['email_id'],$VARS['f_shift'])) . "';";
                        $tempSQL->q($query);
                        if(! $tempSQL->nextrecord())
                        {
                            $query = "INSERT INTO PREFIX_catlist VALUES(";
                            $query .= "'" . $T->CiaoEncode($T->CiaoDecode($catlist['email_id'],$VARS['f_shift'])) . "',";
                            $query .= "'" . $catlist['cat_id'] . "');";
                            $tempSQL->q($query);
                        }
                    }
                    else
                    {
                        $query = "INSERT INTO PREFIX_catlist VALUES(";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($catlist['email_id'],$VARS['f_shift'])) . "',";
                        $query .= "'" . $catlist['cat_id'] . "');";
                        $tempSQL->q($query);
                    }
                    $reload=1;
                }
                $tempSQL->unlocktable();
            }

            $query = "SELECT COUNT(*) FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_list WHERE email_id != '';";
            $result = mysql_query($query,$old_db);
            $list_count = mysql_fetch_array($result,1);
            if($list_count["COUNT(*)"] >= (1 * $VARS['o']))
            {
                $query = "SELECT * FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_list WHERE email_id != '' LIMIT " . (1 * $VARS['o']) . "," . $this->BATCH . ";";
                $result = mysql_query($query,$old_db);
                while($list = mysql_fetch_array($result,1))
                {
                    if($VARS['frame_UPGRADE'] == 1)
                    {
                        $query = "SELECT * FROM PREFIX_list WHERE email_id = '" . $T->CiaoEncode($T->CiaoDecode($list['email_id'],$VARS['f_shift'])) . "';";
                        $tempSQL->q($query);
                        if(! $tempSQL->nextrecord())
                        {
                            $email_nid = $SQL->nid("PREFIX_list->email_nid");
                            $query = "INSERT INTO PREFIX_list VALUES(";
                            $query .= "'" . $email_nid . "',";
                            $query .= "'" . $T->CiaoEncode($T->CiaoDecode($list['email_id'],$VARS['f_shift'])) . "',";
                            list($alias,$domain) = split("@",strtolower(trim($T->CiaoDecode($list['email_id'],$VARS['f_shift']))));
                            $query .= "'" . $T->CiaoEncode($domain) . "',";
                            $password = $T->CiaoDecode($list['password'],$VARS['f_shift']);
                            if($password == "")
                            { $password = "changethis"; }
                            $query .= "'" . $T->CiaoEncode($password) . "',";
                            $query .= "'" . $list['signup_dt'] . "'";
                            for($i=1;$i <= (0 + $CFG->optSize);$i++)
                            { $query .= ",'" . $T->CiaoEncode($T->CiaoDecode($list['option' . $i],$VARS['f_shift'])) . "'"; }
                            $query .= ");";
                            $tempSQL->locktable("PREFIX_list");
                            $tempSQL->q($query);
                            $tempSQL->unlocktable();
                        }
                    }
                    else
                    {
                        $email_nid = $SQL->nid("PREFIX_list->email_nid");
                        $query = "INSERT INTO PREFIX_list VALUES(";
                        $query .= "'" . $email_nid . "',";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($list['email_id'],$VARS['f_shift'])) . "',";
                        list($alias,$domain) = split("@",strtolower(trim($T->CiaoDecode($list['email_id'],$VARS['f_shift']))));
                        $query .= "'" . $T->CiaoEncode($domain) . "',";
                        $password = $T->CiaoDecode($list['password'],$VARS['f_shift']);
                        if($password == "")
                        { $password = "changethis"; }
                        $query .= "'" . $T->CiaoEncode($password) . "',";
                        $query .= "'" . $list['signup_dt'] . "'";
                        for($i=1;$i <= (0 + $CFG->optSize);$i++)
                        { $query .= ",'" . $T->CiaoEncode($T->CiaoDecode($list['option' . $i],$VARS['f_shift'])) . "'"; }
                        $query .= ");";
                        $tempSQL->locktable("PREFIX_list");
                        $tempSQL->q($query);
                        $tempSQL->unlocktable();
                    }
                    $reload=1;
                }
            }
            $VARS['o'] = $VARS['o'] + $this->BATCH;
        }
?>
<html><head><title>import status</title>
<script language="JavaScript">
<!--
function MySubmit()
{ document.upgrade.submit(); }
// -->
</script>
</head>
<?php
        echo "<body onLoad=\"MySubmit()\">";

        if($catlist_count["COUNT(*)"] < $VARS['o'] || $catlist_count["COUNT(*)"] == 0)
        { $finished01 = 100; $unfinished01 = 0; }
        else
        {
            $finished01 = floor(($VARS['o'] / $catlist_count["COUNT(*)"]) * 10000) / 100;
            $unfinished01 = 100 - $finished01;
        }

        if($list_count["COUNT(*)"] < $VARS['o'] || $list_count["COUNT(*)"] == 0)
        { $finished02 = 100; $unfinished02 = 0; }
        else
        {
            $finished02 = floor(($VARS['o'] / $list_count["COUNT(*)"]) * 10000) / 100;
            $unfinished02 = 100 - $finished02;
        }
?>

<form name="upgrade" action="ciaoadm.php" method="POST">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">

<?php
        if($reload)
        {
?>
<input type="hidden" name="frame_UPGRADE" value="<?php echo $VARS['frame_UPGRADE'] ?>">
<input type="hidden" name="o" value="<?php echo (1 * $VARS['o']) ?>">
<?php
        }
        else
        {
?>
<input type="hidden" name="frame_UPGRADE02" value="<?php echo $VARS['frame_UPGRADE'] ?>">
<input type="hidden" name="o" value="0">
<?
        }
?>

<input type="hidden" name="f_tableprefix" value="<?php echo $VARS['f_tableprefix'] ?>">
<input type="hidden" name="f_server" value="<?php echo $VARS['f_server'] ?>">
<input type="hidden" name="f_userid" value="<?php echo $VARS['f_userid'] ?>">
<input type="hidden" name="f_password" value="<?php echo $VARS['f_password'] ?>">
<input type="hidden" name="f_dbname" value="<?php echo $VARS['f_dbname'] ?>">
<input type="hidden" name="f2_email" value="<?php echo $VARS['f2_email'] ?>">
<input type="hidden" name="f2_password" value="<?php echo $VARS['f2_password'] ?>">
<input type="hidden" name="f_shift" value="<?php echo $VARS['f_shift'] ?>">
</form>

<table width="100%" bgcolor="#000099">
<tr><td colspan="2" align="center"><b><font color="#ffffff">Subscriber Category List</font></b></td></tr>
<tr>
<td width="<?php echo $finished01 . "%" ?>" NOWRAP><b><font color="#ffffff">
<?php echo $finished01 . "% of " . (1 * $catlist_count["COUNT(*)"]) ?>
</font></b></td><td width="<?php echo $unfinished01 . "%" ?>" bgcolor="#ffffff">
&nbsp;
</td>
</tr>
</table>

<br><br><br>

<table width="100%" bgcolor="#000099">
<tr><td colspan="2" align="center"><b><font color="#ffffff">Subscriber Information List</font></b></td></tr>
<tr>
<td width="<?php echo $finished02 . "%" ?>" NOWRAP><b><font color="#ffffff">
<?php echo $finished02 . "% of " . (1 * $list_count["COUNT(*)"]) ?>
</font></b></td><td width="<?php echo $unfinished02 . "%" ?>" bgcolor="#ffffff">
&nbsp;
</td>
</tr>
</table>
</body>
</html>
<?php
    }

    function HTML_UPGRADE02($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

# connect to old database
        $old_db = mysql_connect($T->CiaoDecode($VARS['f_server']),$T->CiaoDecode($VARS['f_userid']),$T->CiaoDecode($VARS['f_password']));
        mysql_select_db($T->CiaoDecode($VARS['f_dbname']),$old_db);

# prepare to validate user
        $tEmail = $T->CiaoEncode($T->CiaoDecode($VARS['f2_email']),$VARS['f_shift']);
        $mPassword = md5(urlencode($T->CiaoEncode($T->CiaoDecode($VARS['f2_password']),$VARS['f_shift'])) . urlencode($tEmail));
# crypt (*nix) specific code
        $cPassword = crypt($T->CiaoDecode($VARS['f2_password']),$tEmail); # crypt-version-replacement
# w!nd*ws specific code (minus the crypt)
#       $cPassword = $T->CiaoEncode($T->CiaoDecode($VARS['f2_password']),$VARS['f_shift']); # w!nd*ws-version-replacement

# $mPassword must be 32-char size
        if(strlen($mPassword) > 32)
        { $mPassword = substr($mPassword,0,32); }

# $cPassword must be 16-char size
        if(strlen($cPassword) > 16)
        { $cPassword = substr($cPassword,0,16); }

        $query = "SELECT * FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_user WHERE email = '" . $tEmail . "' AND (password = '" . $mPassword . "' OR password = '" . $cPassword . "') AND access = 1;";
        $result = mysql_query($query,$old_db);
        if($validadmin = mysql_fetch_array($result,1))
        {
# create another instance of CiaoSQL
            $tempSQL = new CiaoSQL;
            $tempSQL->clone($SQL);

            $reload=0;

            $query = "SELECT COUNT(*) FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_catlist WHERE cat_id != '';";
            $result = mysql_query($query,$old_db);
            $catlist_count = mysql_fetch_array($result,1);

            $query = "SELECT COUNT(*) FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_list WHERE email_id != '';";
            $result = mysql_query($query,$old_db);
            $list_count = mysql_fetch_array($result,1);

            if($list_count["COUNT(*)"] >= (1 * $VARS['o']))
            {
                $query = "SELECT * FROM " . $T->CiaoDecode($VARS['f_tableprefix']) . "_list WHERE email_id != '' LIMIT " . (1 * $VARS['o']) . "," . $this->BATCH . ";";
                $result = mysql_query($query,$old_db);
                while($list = mysql_fetch_array($result,1))
                {
                    $query = "SELECT * FROM PREFIX_catlist WHERE email_id = '" . $T->CiaoEncode($T->CiaoDecode($list['email_id'],$VARS['f_shift'])) . "' AND cat_id = 'ALL';";
                    $tempSQL->q($query);
                    if(! $tempSQL->nextrecord())
                    {
                        $query = "INSERT INTO PREFIX_catlist VALUES(";
                        $query .= "'" . $T->CiaoEncode($T->CiaoDecode($list['email_id'],$VARS['f_shift'])) . "',";
                        $query .= "'ALL');";
                        $tempSQL->locktable("PREFIX_catlist");
                        $tempSQL->q($query);
                        $tempSQL->unlocktable();
                    }
                    $reload=1;
                }
            }
            $VARS['o'] = $VARS['o'] + $this->BATCH;
        }
?>
<html><head><title>import status</title>
<script language="JavaScript">
<!--
function MySubmit()
{ document.upgrade.submit(); }
// -->
</script>
</head>
<?php
        if($reload)
        { echo "<body onLoad=\"MySubmit()\">"; }
        else
        { echo "<body>"; }

        if($list_count["COUNT(*)"] < $VARS['o'] || $list_count["COUNT(*)"] == 0)
        { $finished = 100; $unfinished = 0; }
        else
        {
            $finished = floor(($VARS['o'] / $list_count["COUNT(*)"]) * 10000) / 100;
            $unfinished = 100 - $finished;
        }
?>

<form name="upgrade" action="ciaoadm.php" method="POST">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">

<input type="hidden" name="o" value="<?php echo (1 * $VARS['o']) ?>">
<input type="hidden" name="frame_UPGRADE02" value="1">

<input type="hidden" name="f_tableprefix" value="<?php echo $VARS['f_tableprefix'] ?>">
<input type="hidden" name="f_server" value="<?php echo $VARS['f_server'] ?>">
<input type="hidden" name="f_userid" value="<?php echo $VARS['f_userid'] ?>">
<input type="hidden" name="f_password" value="<?php echo $VARS['f_password'] ?>">
<input type="hidden" name="f_dbname" value="<?php echo $VARS['f_dbname'] ?>">
<input type="hidden" name="f2_email" value="<?php echo $VARS['f2_email'] ?>">
<input type="hidden" name="f2_password" value="<?php echo $VARS['f2_password'] ?>">
<input type="hidden" name="f_shift" value="<?php echo $VARS['f_shift'] ?>">
</form>

<table width="100%" bgcolor="#000099">
<tr><td align="center"><b><font color="#ffffff">Subscriber Category List</font></b></td></tr>
<tr>
<td NOWRAP><b><font color="#ffffff">
100% of <?php echo (1 * $catlist_count["COUNT(*)"]) ?>
</font></b></td>
</tr>
</table>



<table width="100%" bgcolor="#000099">
<tr><td align="center"><b><font color="#ffffff">Subscriber Information List</font></b></td></tr>
<tr>
<td NOWRAP><b><font color="#ffffff">
100% of <?php echo (1 * $list_count["COUNT(*)"]) ?>
</font></b></td>
</tr>
</table>

<br>

<table width="100%" bgcolor="#000099">
<tr><td colspan="2" align="center"><b><font color="#ffffff">Data Transfer Check</font></b></td></tr>
<tr>
<td width="<?php echo $finished . "%" ?>" NOWRAP><b><font color="#ffffff">
<?php echo $finished . "% finished" ?>
</font></b></td><td width="<?php echo $unfinished . "%" ?>" bgcolor="#ffffff">
&nbsp;
</td>
</tr>
</table>
</body>
</html>
<?php
    }

    function HTML_TOOLBAR($VARS)
    {
?>
<html><head><title>toolbar</title></head>
<body>
&nbsp;
</body>
</html>
<?php
    }

    function HTML_FRAMES($VARS)
    {
        $query = "&f_tableprefix=" . urlencode($VARS['f_tableprefix']);
        $query .= "&f_server=" . urlencode($VARS['f_server']);
        $query .= "&f_userid=" . urlencode($VARS['f_userid']);
        $query .= "&f_password=" . urlencode($VARS['f_password']);
        $query .= "&f_dbname=" . urlencode($VARS['f_dbname']);
        $query .= "&f2_email=" . urlencode($VARS['f2_email']);
        $query .= "&f2_password=" . urlencode($VARS['f2_password']);
        $query .= "&f_shift=" . urlencode($VARS['f_shift']);
?>

<html>
<head><title></title></head>

<frameset rows="*,1">
<frame name="UPGRADE" src="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=s10&f_process=1&frame_UPGRADE=<?php echo $VARS['frame'] . $query ?>">
<frame name="TOOLBAR" src="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=s10&frame_TOOLBAR=1">
</frameset>

</html>

<?php
    }
}
?>
