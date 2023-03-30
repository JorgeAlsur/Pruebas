<?php
# Ciao EmailList Manager - a customizable mass e-mail program that is administrator/subscriber friendly.
# Copyright (C) 2000,2001 Benjamin Drushell
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
#---------------------------------------------------------
# FILE: ciao_w.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2001.03.16
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - means of contacting - brief description of enhancement)
# 2001.05.22 - BD - Fixed stats listing for custom categories
#
# 2001.08.05 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#---------------------------------------------------------
?>

<?php
class module
{
    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];

        $T->head($VARS);
?>
<p align="center"><center>
<table align="center" border="1" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="5" width="400"><tr>
<td colspan="2" align="center">
<font color="<?php echo $T->table_Text ?>"><b>
<BIG>Ciao-ELM (Stats)</BIG><br>
</b></font>
</td>
</tr><tr>
<td>
<font color="<?php echo $T->table_Text ?>"><b>
Total Number of Administrators:
</b></font>
</td><td>
<font color="<?php echo $T->table_Text ?>"><b>
<?php
        $query = "SELECT COUNT(*) FROM PREFIX_user WHERE email != '';";
        $SQL->q($query);
        $SQL->nextrecord();
        $totaladmin = $SQL->f(0);
        echo (0 + $totaladmin);
?>
</font>
</td>
</tr><tr>
<td bgcolor="<?php echo $T->table_row ?>">
<font color="<?php echo $T->table_row_text ?>"><b>
Full Administrators:
</b></font>
</td><td bgcolor="<?php echo $T->table_row ?>">
<font color="<?php echo $T->table_row_text ?>"><b>
<?php
        $query = "SELECT COUNT(*) FROM PREFIX_user WHERE access = 1;";
        $SQL->q($query);
        $SQL->nextrecord();
        $totalfull = $SQL->f(0);
        echo (0 + $totalfull);
?>
</font>
</td>
</tr><tr>
<td bgcolor="<?php echo $T->table_row ?>">
<font color="<?php echo $T->table_row_text ?>"><b>
General/Mail Administrators:
</b></font>
</td><td bgcolor="<?php echo $T->table_row ?>">
<font color="<?php echo $T->table_row_text ?>"><b>
<?php
        echo (0 + $totaladmin - $totalfull);
?>
</font>
</td>
</tr><tr>
<td>
<font color="<?php echo $T->table_Text ?>"><b>
Total Number of Subscribers:
</b></font>
</td><td>
<font color="<?php echo $T->table_Text ?>"><b>
<?php
        $query = "SELECT COUNT(*) FROM PREFIX_list WHERE email_id != '';";
        $SQL->q($query);
        $SQL->nextrecord();
        $totalsubscribers = $SQL->f(0);
        echo (0 + $totalsubscribers);
?>
</font>
</td>
</tr><tr>
<td>
<font color="<?php echo $T->table_Text ?>"><b>
Number of Categories:
</b></font>
</td><td>
<font color="<?php echo $T->table_Text ?>"><b>
<?php
        $query = "SELECT COUNT(*) FROM PREFIX_category WHERE cat_id != '';";
        $SQL->q($query);
        $SQL->nextrecord();
        $totalcategories = $SQL->f(0);
        echo (0 + $totalcategories);
?>
</font>
</td></tr>

<?php
        $tempSQL = new CiaoSQL;
        $tempSQL->clone($SQL);

        $color = 0;
        $query = "SELECT * FROM PREFIX_category WHERE cat_id != '';";
        $SQL->q($query);
        while($SQL->nextrecord())
        {
            if($color)
            {
                $row = $T->table_altrow;
                $text = $T->table_altrow_text;
                $color = 0;
            }
            else
            {
                $row = $T->table_row;
                $text = $T->table_row_text;
                $color = 1;
            }
?>
<tr><td bgcolor="<?php echo $row ?>">
<font color="<?php echo $text ?>"><b>
<?php echo $SQL->f('cat_name') ?>:
</font>
</td><td bgcolor="<?php echo $row ?>">
<font color="<?php echo $text ?>"><b>
<?php
            $query = "SELECT COUNT(*) FROM PREFIX_catlist WHERE cat_id = '" . $SQL->f("cat_id") . "';";
            $tempSQL->q($query);
            $tempSQL->nextrecord();
            $total = $tempSQL->f(0);
            echo (0 + $total);
?>
</font>
</td></tr>
<?php
        }
?>

<tr><td>
<font color="<?php echo $T->table_Text ?>"><b>
Number of Custom Categories:
</b></font>
</td><td>
<font color="<?php echo $T->table_Text ?>"><b>
<?php
        $query = "SELECT COUNT(*) FROM PREFIX_sql WHERE sql_id != '';";
        $SQL->q($query);
        $SQL->nextrecord();
        $totalcategories = $SQL->f(0);
        echo (0 + $totalcategories);
?>
</font>
</td></tr>

<?php
        $color = 0;
        $query = "SELECT * FROM PREFIX_sql WHERE sql_id != '';";
        $SQL->q($query);
        while($SQL->nextrecord())
        {
            if($color)
            {
                $row = $T->table_altrow;
                $text = $T->table_altrow_text;
                $color = 0;
            }
            else
            {
                $row = $T->table_row;
                $text = $T->table_row_text;
                $color = 1;
            }
            $category = $SQL->Record;
?>
<tr><td bgcolor="<?php echo $row ?>">
<font color="<?php echo $text ?>"><b>
<?php echo $category['sql_name'] ?>:
</font>
</td><td bgcolor="<?php echo $row ?>">
<font color="<?php echo $text ?>"><b>
<?php
            $where = " ";
            $query = "SELECT * FROM PREFIX_sqlstmt WHERE sql_id = '" . $category['sql_id'] . "';";
            $tempSQL->q($query);
            $tempSQL->nextrecord();
            $where .= "((" . $tempSQL->f('sql_stmt') . ")";
            while($tempSQL->nextrecord())
            { $where .= " OR (" . $tempSQL->f('sql_stmt') . ")"; }
            $where .= ")";
            $query = "SELECT COUNT(DISTINCT list.email_id) FROM PREFIX_list AS list, PREFIX_catlist AS catlist WHERE list.email_id = catlist.email_id AND $where;";
            $tempSQL->q($query);
            $tempSQL->nextrecord();
            $total = $tempSQL->f(0);
            echo (0 + $total);
?>
</font>
</td></tr>
<?php
        }
?>

</table>

<br><br>
<h2 align="center">Personal Access Statistics</h2>
<small>(30-day old entries are automatically deleted)</small><br>

<table align="center" border="1" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="5" width="400">
<tr><td><font color="<?php echo $T->table_Text ?>"><b>DATE-TIME</b></font></td><td><font color="<?php echo $T->table_Text ?>"><b>MESSAGE</b></font></td><td><font color="<?php echo $T->table_Text ?>"><b>IP ADDRESS</b></font></td><td><font color="<?php echo $T->table_Text ?>"><b>SERVER NAME</b></font></td></tr>
<?php
        $color = 0;
        $query = "SELECT * FROM PREFIX_session WHERE session_id = '" . $VARS['p'] . "';";
        $SQL->q($query);
        $SQL->nextrecord();

        $query = "SELECT * FROM PREFIX_accesslog WHERE user_id = '" . $SQL->f('user_id') . "' ORDER BY accesslog_dt;";
        $SQL->q($query);
        while($SQL->nextrecord())
        {
            if($color)
            {
                $row = $T->table_altrow;
                $text = $T->table_altrow_text;
                $color = 0;
            }
            else
            {
                $row = $T->table_row;
                $text = $T->table_row_text;
                $color = 1;
            }
?>

<tr>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $SQL->f('accesslog_dt') ?></font></td>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $T->CiaoDecode(trim($SQL->f('accesslog_message'))) ?></font></td>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $SQL->f('accesslog_ip') ?></font></td>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $T->CiaoDecode(trim($SQL->f('accesslog_host'))) ?></font></td>
</tr>

<?php
        }
?>

</table>

</center></p>
<?php
        $T->tail($VARS);
    }
}
?>
