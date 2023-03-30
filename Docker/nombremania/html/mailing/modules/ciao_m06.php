<?php
# Ciao EmailList Manager - a customizable mass e-mail program that is administrator/subscriber friendly.
# Copyright (C) 2000 Benjamin Drushell
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
# FILE: ciao_m06.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.06.15 - BD - Added search/sort feature
# 2001.07.15 - Alexey Semenovykh - Fixed search-query-string output so that "date" displays "Date of Sign-up"
# 2001.07.31 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
# 2001.09.04 - BD - on line 246 changed email to email_id fixing search bug
# 2001.09.09 - BD - added $CFG copy in VERIFY function... this fixed verify link bug
#
# 2001.10.17 - BD - added $CFG declaration in the function "CreateLink"
#---------------------------------------------------------


# SHORT DESCRIPTION
# This module handles the deletion/viewing of the verification list database.
#---------------------------------------------------------
# VARIABLE DEFINITIONS
# x - used to store current module
# u - used to store user ID
# p - used to store twice encryt password
# n - used to store id number of item
# f_option - used to store first menu option (n|e|d)
# f_name - used to store new name
# f_menu - menu item with id number
# SIZE - global variable setting number of option fields


class module
{
    var $SIZE = 10;
    var $TRANS = array("lk"=>"LIKE", "nl"=>"NOT LIKE", "eq"=>"=", "ne"=>"!=", "lt"=>"<", "gt"=>">", "le"=>"<=", "ge"=>">=");
    var $SORT = 2; # number of sort items available

    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

        $T->head($VARS);
        echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">VIEW PENDING LIST</font></h2>";
        if($VARS['f_process'] != '')
        {
            if($VARS['f_delete'] != '')
            { $this->DELETE($VARS,$SYS); }
            elseif($VARS['f_block'] != '')
            { $this->BLOCK($VARS,$SYS); }
            elseif($VARS['f_verify'] != '')
            { $this->VERIFY($VARS,$SYS); }
            $this->PROCESS_FORM($VARS,$SYS);
        }
        else
        { $this->HTML_FORM($VARS,$SYS); }
        $T->tail($VARS);
    }

    function HTML_FORM($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

?>
<center>
<form action="ciaoadm.php" method="post">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<table border="1" align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0">
<tr><td align="center"><font color="<?php echo $T->table_Text ?>">CHOOSE WHAT ADDITIONAL DATA YOU WANT TO VIEW</font></td></tr>
<tr><td>
<table border="0" width="60%" align="center">
<tr><td><font color="<?php echo $T->table_Text ?>">E-mail Address</font></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">
<input type="checkbox" name="f_UseDate">
Date of Sign-up
</font></td></tr>
<?php
        $i = 1;
        while($i <= $CFG->optSize)
        {
            if($CFG->optReq[$i] != 'n')
            {
?>
<tr><td><font color="<?php echo $T->table_Text ?>">
<input type="checkbox" name="f_UseOpt<?php echo $i ?>">
<?php echo $CFG->optName[$i] ?>
</font></td></tr>
<?php
            }
            $i = $i + 1;
        }
?>
</table>
</td></tr>

<tr><td align="left">
<font color="<?php echo $T->table_Text ?>">
Search Field
<select name="f_searchfield">
 <option value="0" SELECTED>NO SEARCH
 <option value="email">Email Address
 <option value="date">Signup Date
<?php
        $i = 1;
        while($i <= $CFG->optSize)
        {
            if($CFG->optReq[$i] != 'n')
            {
?>
 <option value="<?php echo $i ?>">
<?php
                echo $CFG->optName[$i] . "\n";
            }
            $i = $i + 1;
        }
?>
</select>

<select name="f_searchby">
 <option value="lk">LIKE
 <option value="nl">NOT LIKE
 <option value="eq">EQUAL TO
 <option value="ne">NOT EQUAL TO
 <option value="gt">GREATER THAN
 <option value="lt">LESS THAN
 <option value="ge">&gt;=
 <option value="le">&lt;=
</select>

<input name="f_searchvalue" type="text">

</font>
</td></tr>

<tr><td align="left"><font color="<?php echo $T->table_Text ?>">
Sort by:
<?php
        for($i=1;$i <= $this->SORT; $i++)
        {
?>
<select name="f_sortby<?php echo $i ?>">
<option value="0">Do Not Sort
<option value="email">Email Address
<option value="date">Date
<?php
            for($j = 1; $j <= $CFG->optSize; $j++)
            { echo "\n<option value=\"$j\">" . $CFG->optName[$j]; }
?>
</select>
<?php
        }
?>
</font></td></tr>

<tr><td align="center"><input type="submit" name="f_btnSelect" value="VIEW SELECTED DATA"></td></tr>
</table>
</form>
</center>
<?php
    }

    function PROCESS_FORM($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

?>
<center>
<table border="0"><tr><td valign="top"><b>You have selected to view:</b></td><td>E-mail Address;<br>
<?php
        if($VARS['f_UseDate'] != '')
        { echo " Date of Request; <br>"; }
        for($i = 1; $i <= $CFG->optSize; $i++)
        {
            if($VARS["f_UseOpt" . $i] != '')
            { echo " " . $CFG->optName[$i] . "; <br>"; }
        }
        echo "\n</td></tr></table>\n";

        if($VARS['f_searchfield'] == 'date')
        { $searchvalue = addslashes($VARS['f_searchvalue']); }
        else
        { $searchvalue = $T->CiaoEncode($VARS['f_searchvalue']); }
        if($VARS['f_searchby']=="lk" || $VARS['f_searchby']=="nl")
        { $searchvalue = "%" . $searchvalue . "%"; }

        $addsortcomma = 0;
        $sortby = "";
        $sortstart = "";
        for($i = 1;$i <= $this->SORT; $i++)
        {
            if($VARS['f_sortby' . $i] == 'email')
            {
                $sortstart = " ORDER BY";
                if($addsortcomma)
                { $sortby .= ","; }
                $sortby .= " email_id ASC";
                $addsortcomma = 1;
            }
            elseif($VARS['f_sortby' . $i] == 'date')
            {
                $sortstart = " ORDER BY";
                if($addsortcomma)
                { $sortby .= ","; }
                $sortby .= " signup_dt ASC";
                $addsortcomma = 1;
            }
            elseif($VARS['f_sortby' . $i] > 0)
            {
                $sortstart = " ORDER BY";
                if($addsortcomma)
                { $sortby .= ","; }
                $sortby .= " option" . $VARS['f_sortby' . $i] . " ASC";
                $addsortcomma = 1;
            }
        }
        $sortby = $sortstart . $sortby;

        $where = "";
        if($VARS['f_searchfield'] == 'email')
        { $where .= "email_id " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
        elseif($VARS['f_searchfield'] == 'date')
        { $where .= "signup_dt " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
        elseif($VARS['f_searchfield'] && ($VARS['f_searchfield'] <= $CFG->optSize))
        { $where .= "option" . $VARS['f_searchfield'] . " " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }

        if($where == "")
        { $query = "SELECT COUNT(*) FROM PREFIX_verify WHERE verify_id != '';"; }
        else
        { $query = "SELECT COUNT(*) FROM PREFIX_verify WHERE $where;"; }
        $SQL->q($query);
        $SQL->nextrecord();
        $totrec = $SQL->f(0);

        if($where == "")
        { $query = "SELECT * FROM PREFIX_verify WHERE verify_id != '' $sortby " . $SQL->limit(($VARS['o'] * $this->SIZE),$this->SIZE) . ";"; }
        else
        { $query = "SELECT * FROM PREFIX_verify WHERE $where $sortby " . $SQL->limit(($VARS['o'] * $this->SIZE),$this->SIZE) . ";"; }
        $SQL->q($query);
?>
There are <?php echo $totrec ?> e-mail addresses awaiting verification.<br>
<?php
        if($VARS['f_searchfield'])
        {
            echo "With applied search: ";
            if(is_int($VARS['f_searchfield']))
            { echo $CFG->optName[$VARS['f_searchfield']]; }
            elseif($VARS['f_searchfield'] == "email")
            { echo "Email Address"; }
            elseif($VARS['f_searchfield'] == "date")
            { echo "Date of Sign-up"; }
            echo " " . $this->TRANS[$VARS['f_searchby']] . " '" . $VARS['f_searchvalue'] . "'<br>\n";
        }
?>
<center>
<table border="1" width="98%" cellpadding="5" cellspacing="0" bgcolor="#ffffff">
<?php
        while($SQL->nextrecord())
        {
            $column1 = $SQL->Record;
            $counter = 1;
            while($counter <= $CFG->optSize)
            {
                if($column1['option' . $counter] == '')
                { $column1['option' . $counter] = $CFG->optName[$counter] . " N/A"; }
                else
                { $column1['option' . $counter] = $T->CiaoDecode($column1['option' . $counter]); }
                $counter = $counter + 1;
            }
            if($SQL->nextrecord())
            {
                $column2 = $SQL->Record;
                $counter = 1;
                while($counter <= $CFG->optSize)
                {
                    if($column2['option' . $counter] == '')
                    { $column2['option' . $counter] = $CFG->optName[$counter] . " N/A"; }
                    else
                    { $column2['option' . $counter] = $T->CiaoDecode($column2['option' . $counter]); }
                    $counter = $counter + 1;
                }
            }
            echo "\n<tr><td width=\"50%\"><font color=\"#000000\"><b>\n";
            echo " (" . $this->CreateLink("verify",$VARS,$SYS,"&f_verify=1&v=" . urlencode(trim($column1['verify_id']))) . ") &nbsp; ";
            echo " (" . $this->CreateLink("delete",$VARS,$SYS,"&f_delete=1&m=" . urlencode(trim($column1['email_id']))) . ") &nbsp; ";
            echo " (" . $this->CreateLink("block",$VARS,$SYS,"&f_block=1&m=" . urlencode(trim($column1['email_id']))) . ") ";
            echo "\n</b></font></td><td width=\"50%\"><font color=\"#000000\"><b>";
            if($column2['email_id'] != '')
            {
                echo " (" . $this->CreateLink("verify",$VARS,$SYS,"&f_verify=1&v=" . urlencode(trim($column2['verify_id']))) . ") &nbsp; ";
                echo " (" . $this->CreateLink("delete",$VARS,$SYS,"&f_delete=1&m=" . urlencode(trim($column2['email_id']))) . ") &nbsp; ";
                echo " (" . $this->CreateLink("block",$VARS,$SYS,"&f_block=1&m=" . urlencode(trim($column2['email_id']))) . ") ";
            }
            else { echo " &nbsp; "; }
            echo "</b></font></td></tr>";
            echo "\n<tr><td width=\"50%\"><font color=\"#000000\">\n";
            echo $T->CiaoDecode($column1['email_id']);
            echo "\n</font></td><td width=\"50%\"><font color=\"#000000\">";
            if($column2['email_id'] != '')
            { echo $T->CiaoDecode($column2['email_id']); }
            else { echo " &nbsp; "; }
            echo "</font></td></tr>";
            if($VARS['f_UseDate'] != '')
            {
                echo "\n<tr><td width=\"50%\"><font color=\"#000000\">" . $column1['signup_dt'] . "&nbsp;</font></td>";
                echo "<td width=\"50%\"><font color=\"#000000\">" . $column2['signup_dt'] . "&nbsp;</font></td></tr>";
            }
            $counter = 1;
            while($counter <= $CFG->optSize)
            {
                if($VARS['f_UseOpt' . $counter] != '')
                {
                    echo "\n<tr><td width=\"50%\"><font color=\"#000000\">" . $column1['option' . $counter] . "&nbsp;</font></td>";
                    echo "<td width=\"50%\"><font color=\"#000000\">" . $column2['option' . $counter] . "&nbsp;</font></td></tr>";
                }
                $counter = $counter + 1;
            }
?>
<tr><td width="50%"> &nbsp; </td>
<td width="50%"> &nbsp; </td></tr>
<?php
        }
        echo "\n</table></center>";
        $this->PageSetup($VARS,$SYS,$totrec);
    }

#--------------------------------------------
#This function will create the multiple page
#numbered bar.
#--------------------------------------------
    function PageSetup($VARS,$SYS,$totrec)
    {
?>
<br>
<center>
<b>Page <?php echo ($VARS['o'] + 1) ?> of <?php echo ceil($totrec/$this->SIZE) ?></b>
<br>
<table border="1" bgcolor="#ffffff" width="98%" align="center" cellpadding="5" cellspacing="0"><tr><td align="center">
<font color="#000000">
<?php
        if($VARS['o'] >= 10)
        {
            $VARS['o'] = $VARS['o'] - 10;
            echo $this->CreateLink("(Back 10 Pages)",$VARS,$SYS);
            $VARS['o'] = $VARS['o'] + 10;
        }
        else
        { echo "(Back 10 Pages)"; }
        echo "</font></td><td align=\"center\"><font color=\"#000000\">";
        if($VARS['o'] > 0)
        {
            $VARS['o'] = $VARS['o'] - 1;
            echo $this->CreateLink("(Previous Page)",$VARS,$SYS);
            $VARS['o'] = $VARS['o'] + 1;
        }
        else
        { echo "(Previous Page)"; }
        echo "</font></td><td align=\"center\"><font color=\"#000000\">";
        if(($VARS['o'] + 1) < ceil($totrec/$this->SIZE))
        {
            $VARS['o'] = $VARS['o'] + 1;
            echo $this->CreateLink("(Next Page)",$VARS,$SYS);
            $VARS['o'] = $VARS['o'] - 1;
        }
        else
        { echo "(Next Page)"; }
        echo "</font></td><td align=\"center\"><font color=\"#000000\">";
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
#This function will create the multiple page
#numbered bar.
#--------------------------------------------
    function CreateLink($TEXT,$VARS,$SYS,$EXTRA="")
    {
        $CFG = $SYS['CFG'];

        $link = "<a href=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=" . urlencode($VARS['x']) . "&o=" . (0 + $VARS['o']);
        $link .= "&f_process=1";
        if($VARS['f_UseDate'] != '')
        { $link .= "&f_UseDate=1"; }
        if($VARS['f_searchfield'])
        {
            $link .= "&f_searchfield=" . urlencode($VARS['f_searchfield']);
            $link .= "&f_searchby=" . urlencode($VARS['f_searchby']);
            $link .= "&f_searchvalue=" . urlencode($VARS['f_searchvalue']);
        }
        for($counter=1;$counter <= $CFG->optSize; $counter++)
        {
            if($VARS['f_UseOpt' . $counter] != '')
            { $link .= "&f_UseOpt" . $counter . "=1"; }
        }
        for($i=1;$i <= $this->SORT; $i++)
        {
            if(strlen($VARS['f_sortby' . $i]) > 0)
            { $link .= "&f_sortby$i=" . urlencode($VARS['f_sortby' . $i]); }
        }
        $link .= $EXTRA . "\" style=\"color:#000000\"><font color=\"#000000\">" . $TEXT . "</font></a>";
        return($link);
    }

#--------------------------------------------
#This function will delete a record pending
#verification.
#--------------------------------------------
    function DELETE($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];

        $query = "DELETE FROM PREFIX_verify WHERE email_id = '" . $VARS['m'] . "';";
        $SQL->q($query);
    }

#--------------------------------------------
#This function will purge record from
#verify database, and add their email address
#to the block database.
#--------------------------------------------
    function BLOCK($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];

        $query = "DELETE FROM PREFIX_verify WHERE email_id = '" . $VARS['m'] . "';";
        $SQL->locktable("PREFIX_verify");
        $SQL->q($query);
        $SQL->unlocktable();
        $block_id = $SQL->nid("PREFIX_block->block_id");
        $query = "INSERT INTO PREFIX_block VALUES('" . $block_id . "','" . $VARS['m'] . "','','" . date("Y-m-d H:i:s") . "');";
        $SQL->locktable("PREFIX_block");
        $SQL->q($query);
        $SQL->unlocktable();
    }

#--------------------------------------------
#This function will manually transfer record
#from verify database to the actual database.
#--------------------------------------------
    function VERIFY($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $CFG = $SYS['CFG'];

        $query = "SELECT * FROM PREFIX_verify WHERE verify_id = '" . $VARS['v'] . "';";
        $SQL->q($query);
        $SQL->nextrecord();

        $mEmail = trim($SQL->f('email_id'));

        $email_nid = $SQL->nid("PREFIX_list->email_nid");
        $query = "INSERT INTO PREFIX_list VALUES('" . $email_nid . "','" . $mEmail . "','" . trim($SQL->f('domain')) . "','" . trim($SQL->f('password')) . "','" . date("Y-m-d H:i:s") . "'";
        for($i = 1; $i <= $CFG->optSize; $i++)
        { $query .= ",'" . trim($SQL->f('option' . $i)) . "'"; }
        $query .= ");";
        $SQL->locktable("PREFIX_list");
        $SQL->q($query);
        $SQL->unlocktable();

        $query = "INSERT INTO PREFIX_catlist VALUES('" . $mEmail . "','ALL');";
        $SQL->locktable("PREFIX_catlist");
        $SQL->q($query);
        $SQL->unlocktable();

        $query = "DELETE FROM PREFIX_verify WHERE verify_id = '" . $VARS['v'] . "';";
        $SQL->locktable("PREFIX_verify");
        $SQL->q($query);
        $SQL->unlocktable();
    }
}
?>
