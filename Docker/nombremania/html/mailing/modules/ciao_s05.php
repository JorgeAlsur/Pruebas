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
# FILE: ciao_s05.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
#
# 2001.08.05 - BD (Ben Drushell) - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module handles the adding/deletion/viewing of the blocking database.
#---------------------------------------------------------
?>

<?php 
class module
{
    var $SIZE = 10;

    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

        $T->head($VARS);
        echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">BLOCKING LIST</font></h2>";
        if($VARS['f_process'] != '')
        {
            if($VARS['f_btnAdd'] != '')
            { $this->ADD($VARS,$SYS); }
            elseif($VARS['f_delete'] != '')
            { $this->DELETE($VARS,$SYS); }
        }
        $this->HTML_FORM($VARS,$SYS);
        $T->tail($VARS);
    }

    function HTML_FORM($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];

        $query = "SELECT COUNT(block_id) FROM PREFIX_block WHERE block_id != 0;";
        $SQL->q($query);
        $SQL->nextrecord();
        $totrec = $SQL->f(0);
?>
<form name="client" method="post">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="o" value="<?php echo (1 * $VARS['o']) ?>">
<input type="hidden" name="f_process" value="1">

<center>
<table border="1" bgcolor="<?php echo $T->table_bgcolor ?>" width="90%" align="center">
<tr><td align="center" colspan="3"><big><b><font color="<?php echo $T->table_Text ?>">ADD ADDRESS TO BLOCK</font></b></big></td></tr>
<tr><td width="20%">&nbsp;</td><td width="30%"><font color="<?php echo $T->table_Text ?>">E-mail Address/IP</font></td><td width="50%"><font color="<?php echo $T->table_Text ?>">Reason For Block</font></td></tr>
<tr><td width="20%"><input type="submit" name="f_btnAdd" value="New Block"></td>
<td width="30%"><input type="text" name="f_Value" size="18"></td>
<td width="50%"><input type="text" name="f_Reason" size="26"></td></tr>
</table>

<br>

(E-mail address or domain is blocked from being added to your list
<br>OR... a specific IP address is blocked from accessing the program.)

<br><br>

<table border="1" bgcolor="<?php echo $T->table_bgcolor ?>" width="98%" align="center">
<tr><td width="5">&nbsp;</td><td width="70"><font color="<?php echo $T->table_Text ?>">Date</font></td><td width="*"><font color="<?php echo $T->table_Text ?>">E-mail Address/IP - Reason for Block</font></td></tr>
<?php
        $query = "SELECT * FROM PREFIX_block WHERE block_id != 0 " . $SQL->limit(($VARS['o'] * $this->SIZE),$this->SIZE) . ";";
        $SQL->q($query);
        while($SQL->nextrecord())
        {
?>
<tr><td nowrap>
(<?php echo $this->CreateLink("delete",$VARS,$SYS,"&f_process=1&f_delete=1&m=" . urlencode(trim($SQL->f('block_id')))) ?>)
</td>
<td nowrap>
<font color="<?php echo $T->table_Text ?>"><?php echo $SQL->f('block_dt') ?></font>
</td>
<td width="*"><font color="<?php echo $T->table_Text ?>"><?php echo $T->CiaoDecode($SQL->f('block_value')) ?></font></td></tr>
<tr><td colspan="2"></td><td width="*"><font color="<?php echo $T->table_Text ?>"><?php echo $T->CiaoDecode($SQL->f('block_reason')) ?> &nbsp;</font></td></tr>
<?php
        }
?>
</table>
</center>

<br><br>

<?php echo $this->PageSetup($VARS,$SYS,$totrec) ?>
</form>
<?php
    }

#--------------------------------------------
#This function will create the multiple page
#numbered bar.
#--------------------------------------------
    function PageSetup($VARS,$SYS,$totrec)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

?>
<br>
<center>
<table width="98%" align="center" bgcolor="<?php echo $T->table_bgcolor ?>"><tr><td align="center"><font color="<?php echo $T->table_Text ?>">
<?php
        if($VARS['o'] >= 10)
        {
            $VARS['o'] = $VARS['o'] - 10;
            echo $this->CreateLink("(Back 10 Pages)",$VARS,$SYS);
            $VARS['o'] = $VARS['o'] + 10;
        }
        else
        { echo "(Back 10 Pages)"; }
        echo "</font></td><td align=\"center\"><font color=\"" . $T->table_Text . "\">";
        if($VARS['o'] > 0)
        {
            $VARS['o'] = $VARS['o'] - 1;
            echo $this->CreateLink("(Previous Page)",$VARS,$SYS);
            $VARS['o'] = $VARS['o'] + 1;
        }
        else
        { echo "(Previous Page)"; }
        echo "</font></td><td align=\"center\"><font color=\"" . $T->table_Text . "\">";
        if(($VARS['o'] + 1) < ceil($totrec/$this->SIZE))
        {
            $VARS['o'] = $VARS['o'] + 1;
            echo $this->CreateLink("(Next Page)",$VARS,$SYS);
            $VARS['o'] = $VARS['o'] - 1;
        }
        else
        { echo "(Next Page)"; }
        echo "</font></td><td align=\"center\"><font color=\"" . $T->table_Text . "\">";
        if(($VARS['o'] + 10) < ceil($totrec/$this->SIZE))
        {
            $VARS['o'] = $VARS['o'] + 10;
            echo $this->CreateLink("(Forward 10 Pages)",$VARS,$SYS);
            $VARS['o'] = $VARS['o'] - 10;
        }
        else
        { echo "(Forward 10 Pages)"; }
?>
</font></td></tr></table></center>
<br>
<?php
    }

#--------------------------------------------
#
#--------------------------------------------
    function CreateLink($TEXT,$VARS,$SYS,$EXTRA="")
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

        $link = "<a href=\"ciaoadm.php?x=" . urlencode($VARS['x']) . "&u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&o=" . urlencode($VARS['o']);
        $link .= $EXTRA . "\" style=\"color:" . $T->table_Link . "\"><font color=\"" . $T->table_Link . "\">" . $TEXT . "</font></a>";
        return($link);
    }

#--------------------------------------------
#
#--------------------------------------------
    function DELETE($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];

        $query = "DELETE FROM PREFIX_block WHERE block_id = '" . $VARS['m'] . "';";
        $SQL->q($query);
    }

#--------------------------------------------
#
#--------------------------------------------
    function ADD($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];

        $query = "DELETE FROM PREFIX_verify WHERE email_id LIKE '%" . $T->CiaoEncode($VARS['f_Value']) . "%';";
        $SQL->locktable("PREFIX_verify");
        $SQL->q($query);
        $SQL->unlocktable();
        $query = "DELETE FROM PREFIX_catlist WHERE email_id LIKE '%" . $T->CiaoEncode($VARS['f_Value']) . "%';";
        $SQL->locktable("PREFIX_catlist");
        $SQL->q($query);
        $SQL->unlocktable();
        $query = "DELETE FROM PREFIX_list WHERE email_id LIKE '%" . $T->CiaoEncode($VARS['f_Value']) . "%';";
        $SQL->locktable("PREFIX_list");
        $SQL->q($query);
        $SQL->unlocktable();
        $block_id = $SQL->nid("PREFIX_block->block_id");
        $query = "INSERT INTO PREFIX_block VALUES('" . $block_id . "','" . $T->CiaoEncode($VARS['f_Value']) . "','" . $T->CiaoEncode($VARS['f_Reason']) . "','" . date("Y-m-d H:i:s") . "');";
        $SQL->locktable("PREFIX_block");
        $SQL->q($query);
        $SQL->unlocktable();
    }
}
?>
