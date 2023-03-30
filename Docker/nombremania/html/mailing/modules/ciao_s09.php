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
# FILE: ciao_s09.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2001.08.09
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - means of contacting - brief description of enhancement)
#
# 2001.08.09 - BD (Ben Drushell) - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
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

        $tempSQL = new CiaoSQL;
        $tempSQL->clone($SQL);

?>
<p align="center"><center>
<table align="center" border="1" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="5" width="400"><tr>
<td colspan="5" align="center">
<font color="<?php echo $T->table_Text ?>"><b>
<BIG>Ciao-ELM Complete Admin Access Log</BIG><br>
</b></font>
</td>
</tr>
<tr><td><font color="<?php echo $T->table_Text ?>"><b>DATE-TIME</b></font></td><td><font color="<?php echo $T->table_Text ?>"><b>MESSAGE</b></font></td><td><font color="<?php echo $T->table_Text ?>"><b>USER ID</b></font></td><td><font color="<?php echo $T->table_Text ?>"><b>IP ADDRESS</b></font></td><td><font color="<?php echo $T->table_Text ?>"><b>SERVER NAME</b></font></td></tr>
<?php 
        $color = 0;
        $query = "SELECT * FROM PREFIX_accesslog WHERE accesslog_id != '' ORDER BY accesslog_dt;";
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

            if($SQL->f('user_id') > 0)
            {
                $query="SELECT * FROM PREFIX_user WHERE user_id = '" . $SQL->f('user_id') . "';";
                $tempSQL->q($query);
                $tempSQL->nextrecord();
            }
            else
            { $tempSQL->Record = array(); }
?>

<tr>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $SQL->f('accesslog_dt') ?></font></td>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $T->CiaoDecode($SQL->f('accesslog_message')) ?></font></td>
<?php
            if($SQL->f('user_id') != '')
            { $output = "(" . $SQL->f('user_id') . ") " . $T->CiaoDecode($tempSQL->f('email')); }
            else
            { $output = " &nbsp; "; }
?>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $output ?></font></td>
<?php
            if($SQL->f('accesslog_ip') != '')
            { $output = $SQL->f('accesslog_ip'); }
            else
            { $output = " &nbsp; "; }
?>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $output ?></font></td>
<?php
            if($SQL->f('accesslog_host') != '')
            { $output = $T->CiaoDecode($SQL->f('accesslog_host')); }
            else
            { $output = " &nbsp; "; }
?>
<td bgcolor="<?php echo $row ?>"><font color="<?php echo $text ?>"><?php echo $output ?></font></td>
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

