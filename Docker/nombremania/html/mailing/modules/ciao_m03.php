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
# FILE: ciao_m03.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.05.31 - BD (Ben Drushell) - Added "%" category option as a short cut for all categories
# 2001.06.19 - BD - Added "%" before and after search value when performing LIKE or NOT LIKE
# 2001.07.01 - BD - Fixed "OR/AND" error so that multiple items can be selected.
# 2001.07.05 - BD - Added select based on email address feature
# 2001.07.09 - Alexey Semenovykh - Fixed a line error that would adversely affect custom categories based on email address in certain circumstances.
#
# 2001.07.29 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module handles the setting up of custom list operations.
#---------------------------------------------------------
# FORM VARIABLE DEFINITIONS
# x - used to store current module
# p - used to store twice encryt password
# u - used to store user ID
# f_option - used to store first menu option (n|e|d)
# f_name - used to store new name
# f_menu - menu item with id number
# n - used to store id number of item
# f2_name - used to store name
# f2_categ - used to store category id
# f2_sql - used to store sql statement id
# f2_date_a - (true, false)
# f2_date_c - (lt,gt,le,ge)
# f2_date_d - comparison value (YYYYMMDD)
# f2_date_d2 - comparison value (HH:MM:SS)
# f2_opt#_a - (true, false)
# f2_opt#_b - (AND, OR)
# f2_opt#_c - (lk,nl,eq,ne,lt,gt,le,ge)
# f2_opt#_d - comparison value
?>

<?php
class module
{
    var $TRANS = array("lk"=>"LIKE", "nl"=>"NOT LIKE", "eq"=>"=", "ne"=>"!=", "lt"=>"<", "gt"=>">", "le"=>"<=", "ge"=>">=");

    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

        $T->head($VARS);
?>
<h2 align="center"><font color="<?php echo $T->body_title ?>">CUSTOM LISTS</font></h2>
<center><table align="center" border="0" width="70%"><tr><td colspan="2">
Custom temporary e-mail lists can be created from a
combination of categories and subscriber information.
</td></tr><tr><td valign="top">Exmaple:</td><td>
<ul>
<li>Send to subscribers since a certain date.
<li>Send to subscribers with a particular name.
<li>Send to a combination of categories.
<li>etc.
</ul>
</td></tr></table></center>
<br><br>
<?php
        if($VARS['f_process'])
        {
            if($VARS['n'] != '')
            {
                $this->PROCESS_FORM02($VARS,$SYS);
                $this->HTML_FORM02($VARS,$SYS);
            }
            else
            {
                $this->PROCESS_FORM01($VARS,$SYS);
                if($VARS['n'] != '')
                {
                    $this->PROCESS_FORM02($VARS,$SYS);
                    $this->HTML_FORM02($VARS,$SYS);
                }
                else
                { $this->HTML_FORM01($VARS,$SYS); }
            }
        }
        else
        { $this->HTML_FORM01($VARS,$SYS); }
        $T->tail($VARS);
    }

    function PROCESS_FORM01(&$VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];

        if ($VARS['f_option'] == 'n')
        {
            $errors = "";
            if($VARS['f_id'] == '')
            { $errors .= "ERROR - List ID field is required for new list!<br>"; }
            if($VARS['f_name'] == '')
            { $errors .= "ERROR - List Name field is required for new list!<br>"; }
            if($errors == '')
            {
                $VARS['n'] = $VARS['f_id'];
                $sql_nid = $SQL->nid("PREFIX_sql->sql_nid");
                $query = "INSERT INTO PREFIX_sql VALUES('" . $sql_nid . "','" . $VARS['n'] . "','" . $VARS['f_name'] . "');";
                $SQL->locktable("PREFIX_sql");
                $SQL->q($query);
                $SQL->unlocktable();
            }
            else
            { $this->HTML_ERRORS($errors); }
        }
        elseif($VARS['f_option'] == 'e')
        { $VARS['n'] = $VARS['f_menu']; }
        elseif($VARS['f_option'] == 'd')
        {
            $query = "DELETE FROM PREFIX_sql WHERE sql_id = '" . $VARS['f_menu'] . "';";
            $SQL->locktable("PREFIX_sql");
            $SQL->q($query);
            $SQL->unlocktable();
            $query = "DELETE FROM PREFIX_sqlstmt WHERE sql_id = '" . $VARS['f_menu'] . "';";
            $SQL->locktable("PREFIX_sqlstmt");
            $SQL->q($query);
            $SQL->unlocktable();
        }
    }

    function PROCESS_FORM02($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        if($VARS['f2_btnADD'] != '')
        {
            $notfirst = 0;
            if($VARS['f2_categ'] == "%")
            { $sqlstmt = "((cat_id LIKE '%'"; }
            else
            { $sqlstmt = "((cat_id = '" . $VARS['f2_categ'] . "'"; }
            if($VARS['f2_date_a'])
            {
                if(! $notfirst)
                { $sqlstmt .= ") AND ("; }
                $sqlstmt .= "(signup_dt " . $this->TRANS[$VARS['f2_date_c']] . " '" . $VARS['f2_date_d'] . " " . $VARS['f2_date_d2'] . "') ";
                $notfirst=1;
            }
            if($VARS['f2_email_a'])
            {
                if($notfirst)
                { $sqlstmt .= $VARS["f2_email_b"]; }
                else
                { $sqlstmt .= ") AND ("; }
                if($VARS["f2_email_c"] == "lk" || $VARS["f2_email_c"] == "nl")
                { $matchvalue = "%" . $T->CiaoEncode(strtolower($VARS["f2_email_d"])) . "%"; }
                else
                { $matchvalue = $T->CiaoEncode(strtolower($VARS["f2_email_d"])); }
                $sqlstmt .= "(list.email_id " . $this->TRANS[$VARS['f2_email_c']] . " '" . $matchvalue . "') ";
                $notfirst=1;
            }
            if($VARS['f2_domain_a'])
            {
                if($notfirst)
                { $sqlstmt .= $VARS["f2_domain_b"]; }
                else
                { $sqlstmt .= ") AND ("; }
                if($VARS["f2_domain_c"] == "lk" || $VARS["f2_domain_c"] == "nl")
                { $matchvalue = "%" . $T->CiaoEncode(strtolower($VARS["f2_domain_d"])) . "%"; }
                else
                { $matchvalue = $T->CiaoEncode(strtolower($VARS["f2_domain_d"])); }
                $sqlstmt .= "(list.domain " . $this->TRANS[$VARS['f2_domain_c']] . " '" . $matchvalue . "') ";
                $notfirst=1;
            }
            for($counter=1; $counter <= $CFG->optSize; $counter++)
            {
                if($VARS["f2_" . $counter . "_a"])
                {
                    if($notfirst)
                    { $sqlstmt .= $VARS["f2_" . $counter . "_b"]; }
                    else
                    { $sqlstmt .= ") AND ("; }
                    if($VARS["f2_" . $counter . "_c"] == "lk" || $VARS["f2_" . $counter . "_c"] == "nl")
                    { $matchvalue = "%" . $T->CiaoEncode($VARS["f2_" . $counter . "_d"]) . "%"; }
                    else
                    { $matchvalue = $T->CiaoEncode($VARS["f2_" . $counter . "_d"]); }

                    $sqlstmt .= " (option" . $counter . " " . $this->TRANS[$VARS["f2_" . $counter . "_c"]] . " '" . $matchvalue . "') ";
                    $notfirst = 1;
                }
            }
            $sqlstmt .= "))";
            $ID = $SQL->nid("PREFIX_sqlstmt->stmt_id");
            $query = "INSERT INTO PREFIX_sqlstmt VALUES('" . $ID . "','" . $VARS['f2_name'] . "','" . $VARS['n'] . "','" . addslashes($sqlstmt) . "');";
            $SQL->locktable("PREFIX_sqlstmt");
            $SQL->q($query);
            $SQL->unlocktable();
        }
        elseif($VARS['f2_btnREMOVE'] != '')
        {
            $query = "DELETE FROM PREFIX_sqlstmt WHERE stmt_id = '" . $VARS['f2_sql'] . "';";
            $SQL->locktable("PREFIX_sqlstmt");
            $SQL->q($query);
            $SQL->unlocktable();
        }
    }

    function HTML_FORM01($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

?>
<center>
<form name="f01" action="ciaoadm.php" method="post">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">
<input type="radio" name="f_option" value="n" CHECKED>New List<br>
<input type="radio" name="f_option" value="e">Edit List<br>
<input type="radio" name="f_option" value="d">Delete List
</font></td><td>
<table border="0" cellpadding="0" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">List ID (4 Characters):</font></td><td><input type="text" name="f_id" maxlength="4" size="4"></td></tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>">List Name (200 Characters):</font></td></tr>
<tr><td colspan="2"><input type="text" name="f_name" maxlength="200" size="30"></td></tr>
<tr><td colspan="2"><select name="f_menu">
<?php
        $query = "SELECT sql_id FROM PREFIX_sql WHERE sql_id != '';";
        $SQL->q($query);
        while($SQL->nextrecord())
        { echo "\n<option value=\"" . trim($SQL->f('sql_id')) . "\">(" . trim($SQL->f('sql_id')) . ") " . trim($SQL->f('sql_name')); }
?>
</select></td></tr>
</table>
</td></tr>
<tr><td colspan="2">
<input type="submit" value="Process Request">
</td></tr>
</table>
</form>
</center>
<?php
    }

    function HTML_FORM02($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

?>
<center>
<form name="f02" action="ciaoadm.php" method="post">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="n" value="<?php echo $VARS['n'] ?>">
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">
Categories<br>
<select name="f2_categ" size="5">
<?php
        echo "\n<option value=\"%\" SELECTED> All Categories";
        $query = "SELECT * FROM PREFIX_category WHERE cat_id != '';";
        $SQL->q($query);
        while($SQL->nextrecord())
        { echo "\n<option value=\"" . trim($SQL->f('cat_id')) . "\"> (" . trim($SQL->f('cat_id')) . ") " . trim($SQL->f('cat_name')); }
?>
</select>
</font></td>
<td>
<input type="submit" name="f2_btnADD" value="--&gt;"><br>
<input type="submit" name="f2_btnREMOVE" value="&lt;--">
</td><td><font color="<?php echo $T->table_Text ?>">
Customized List Parameters<br>
<select name="f2_sql" size="5">
<?php
        $query = "SELECT * FROM PREFIX_sqlstmt WHERE sql_id = '" . $VARS['n'] . "';";
        $SQL->q($query);
        while($SQL->nextrecord())
        { echo "\n<option value=\"" . trim($SQL->f('stmt_id')) . "\"> " . trim($SQL->f('stmt_name')); }
?>

</select>
</font></td></tr>
<tr><td colspan="3"><font color="<?php echo $T->table_Text ?>">
Statement Name/Description: <input type="text" size="30" maxlength="125" name="f2_name">
<br><br>
<input type="checkbox" name="f2_date_a">Date/Time of Signup
<select name="f2_date_c">
<option value="ge">GREATER THAN OR EQUAL TO
<option value="le">LESS THAN OR EQUAL TO
<option value="gt">GREATER THAN
<option value="lt">LESS THAN
</select>
<br>
<input type="text" name="f2_date_d" size="10" maxlength="10"> format(YYYY-MM-DD) <input type="text" name="f2_date_d2" size="8" maxlength="8"> format(HH:MM:SS)
<br><br>
<select name="f2_email_b">
<option value="OR">OR
<option value="AND">AND
</select>
<input type="checkbox" name="f2_email_a">
Email Address
<select name="f2_email_c">
<option value="lk">LIKE
<option value="nl">NOT LIKE
<option value="eq">EQUAL TO
<option value="ne">NOT EQUAL TO
<option value="ge">GREATER THAN OR EQUAL TO
<option value="le">LESS THAN OR EQUAL TO
<option value="gt">GREATER THAN
<option value="lt">LESS THAN
</select>
<br><input type="text" size="30" maxlength="125" name="f2_email_d">
<br><br>
<select name="f2_domain_b">
<option value="OR">OR
<option value="AND">AND
</select>
<input type="checkbox" name="f2_domain_a">
Email Domain
<select name="f2_domain_c">
<option value="lk">LIKE
<option value="nl">NOT LIKE
<option value="eq">EQUAL TO
<option value="ne">NOT EQUAL TO
<option value="ge">GREATER THAN OR EQUAL TO
<option value="le">LESS THAN OR EQUAL TO
<option value="gt">GREATER THAN
<option value="lt">LESS THAN
</select>
<br><input type="text" size="30" maxlength="125" name="f2_domain_d">
<?php
        $counter = 1;
        while($counter <= $CFG->optSize)
        {
            if($CFG->optReq[$counter] != "n")
            {
?>
<br><br>
<select name="f2_<?php echo $counter ?>_b">
<option value="OR">OR
<option value="AND">AND
</select>
<input type="checkbox" name="f2_<?php echo $counter ?>_a">
<?php
                if($CFG->optName[$counter] != "")
                { echo $CFG->optName[$counter]; }
                else
                { echo "Option" . $counter; }
?>
<select name="f2_<?php echo $counter ?>_c">
<option value="lk">LIKE
<option value="nl">NOT LIKE
<option value="eq">EQUAL TO
<option value="ne">NOT EQUAL TO
<option value="ge">GREATER THAN OR EQUAL TO
<option value="le">LESS THAN OR EQUAL TO
<option value="gt">GREATER THAN
<option value="lt">LESS THAN
</select>
<br><input type="text" size="30" maxlength="125" name="f2_<?php echo $counter ?>_d">
<?php
            }
            $counter = $counter + 1;
        }
?>
</font>
</td></tr>
</table>
</form>
</center>
<?php
    }

    function HTML_ERRORS($errors)
    { echo "\n$errors <br>"; }
}
?>
