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
# FILE: ciao_s08.php
# LOCAL VERSION: 1.0.11
# CREATED ON: 2001.06.14
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.06.16 - BD (Ben Drushell) - Fixed SQL statement "GROUP BY" clause adversly affecting custom categories
# 2001.07.05 - BD - Added code for "ALL" category.
# 2001.08.05 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#
# 2001.10.16 - BD - added some labeling and defaulted the first datafield to "email" for those that click export without reading
#---------------------------------------------------------


# SHORT DESCRIPTION
# This module handles export of subscribers.
#---------------------------------------------------------


class module
{
    var $BATCH = 1000;
    var $TRANS = array("lk"=>"LIKE", "nl"=>"NOT LIKE", "eq"=>"=", "ne"=>"!=", "lt"=>"<", "gt"=>">", "le"=>"<=", "ge"=>">=");
    var $SORT = 3;

    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

        if($VARS['f_process'] == '')
        {
            $T->head($VARS);
            echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">E-MAIL LIST EXPORT</font></h2>";
            $this->HTML_FORM($VARS,$SYS);
            $T->tail($VARS);
        }
        else
        {
            if($VARS['f_btnEXPORT'] != '')
            {
                $T->head($VARS);
                echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">EXPORT</font></h2>";
                $this->START_EXPORT($VARS,$SYS);
                $this->HTML_FORM($VARS,$SYS);
                $T->tail($VARS);
            }
            elseif($VARS['frame_EXPORT'] != '')
            { $this->HTML_EXPORT($VARS,$SYS); }
            else
            { $this->HTML_FRAMES($VARS,$SYS); }
        }
    }

    function HTML_FORM($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

?>

<center>
<table border="0" width="70%" align="center"><tr><td>
This export utility facilitates those wanting to sync Ciao EmailList Manager
information with other databases.  Be sure that PHP has write access to the
directory the export file will be written to.
</td></tr></table>
</center>

<form name="client" method="post">

<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="o" value="<?php echo (1 * $VARS['o']) ?>">
<input type="hidden" name="f_process" value="1">

<center>
<table border="1" bgcolor="<?php echo $T->table_bgcolor ?>" align="center">
<tr><td align="left">

<table border="0">
<tr>
<td align="left">
<font color="<?php echo $T->table_Text ?>">Fields To Export:</font>
<select size="1" name="first">
<option value="0">Do Not Use
<option value="email" SELECTED>Email Address
<option value="date">Date
<?php
        for($i = 1; $i <= $CFG->optSize; $i++)
        {
            if($CFG->optReq[$i] != "n")
            { echo "\n<option value=\"$i\">" . $CFG->optName[$i]; }
        }
?>
</select>
</td>
<td>
<select size="1" name="second">
<option value="0">Do Not Use
<option value="email">Email Address
<option value="date">Date
<?php
        for($i = 1; $i <= $CFG->optSize; $i++)
        {
            if($CFG->optReq[$i] != "n")
            { echo "\n<option value=\"$i\">" . $CFG->optName[$i]; }
        }
?>
</select>
</td>
<?php
        for($i = 1; $i <= $CFG->optSize; $i++)
        {
            if($CFG->optReq[$i] != "n")
            {
?>
<td>
<select size="1" name="<?php echo "opt" . $i ?>">
<option value="0">Do Not Use
<option value="email">Email Address
<option value="date">Date
<?php
                for($j = 1; $j <= $CFG->optSize; $j++)
                {
                    if($CFG->optReq[$j] != "n")
                    { echo "\n<option value=\"$j\">" . $CFG->optName[$j]; }
                }
?>
</select>
</td>
<?php
            }
        }
?>
</td></tr>
</table>

</td></tr>
<tr><td align="left"><font color="<?php echo $T->table_Text ?>">
Sort by:
<?php
        for($i=1;$i <= $this->SORT;$i++)
        {
?>
<select name="f_sortby<?php echo $i ?>">
<option value="0">Do Not Sort
<option value="email">Email Address
<option value="date">Date
<?php
            for($j = 1; $j <= $CFG->optSize; $j++)
            {
                if($CFG->optReq[$j] != "n")
                { echo "\n<option value=\"$j\">" . $CFG->optName[$j]; }
            }
?>
</select>
<?php
        }
?>
</font></td></tr>
<tr><td align="left">
<font color="<?php echo $T->table_Text ?>">
Category:
<select name="f_category">
 <option value="General">All Subscribers
<?php
        $query = "SELECT * FROM PREFIX_category WHERE cat_nid != 0;";
        $SQL->q($query);
        while($SQL->nextrecord())
        {
            if(trim($SQL->f('cat_id')) != "ALL")
            { echo "\n <option value=\"" . trim($SQL->f('cat_id')) . "\">(" . trim($SQL->f('cat_id')) . ") " . trim($SQL->f('cat_name')); }
        }
        $query = "SELECT * FROM PREFIX_sql WHERE sql_id != '';";
        $SQL->q($query);
        while($SQL->nextrecord())
        { echo "\n <option value=\"custom_" . trim($SQL->f('sql_id')) . "\">(" . trim($SQL->f('sql_id')) . ") " . trim($SQL->f('sql_name')); }
?>
</select>
</font></td></tr>
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

</font></td></tr>
<tr><td align="left">
<font color="<?php echo $T->table_Text ?>">
Delimiter (text used to seperate information)
<input type="text" name="delimiter" value=",">
</font></td></tr>
<tr><td align="left">
<font color="<?php echo $T->table_Text ?>">
File Name/Location (PHP needs write access to this)
modules/<input type="text" name="exportfile" value="listexport">.export
</font></td></tr>
<tr><td align="left">
<input type="submit" name="f_btnEXPORT" value="Export File">
</td></tr>
</table>
</form>

<?php
    }

    function START_EXPORT($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        if($fw = fopen("modules/" . $VARS['exportfile'] . ".export","w"))
        {
            fwrite($fw,"");
            fclose($fw);

            $link = "ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=s08&f_process=1";
            $link .= "&delimiter=" . urlencode($VARS['delimiter']) . "&exportfile=" . urlencode($VARS['exportfile']);
            if(strlen($VARS['first']) > 0)
            { $link .= "&first=" . urlencode($VARS['first']); }
            if(strlen($VARS['second']) > 0)
            { $link .= "&second=" . urlencode($VARS['second']); }
            for($i=1;$i <= $CFG->optSize; $i++)
            {
                if(strlen($VARS['opt' . $i]) > 0)
                { $link .= "&opt$i=" . urlencode($VARS['opt' . $i]); }
            }
            if(strlen($VARS['f_category']) > 0)
            { $link .= "&f_category=" . urlencode($VARS['f_category']); }
            if(strlen($VARS['f_searchfield']) > 0)
            { $link .= "&f_searchfield=" . urlencode($VARS['f_searchfield']); }
            if(strlen($VARS['f_searchby']) > 0)
            { $link .= "&f_searchby=" . urlencode($VARS['f_searchby']); }
            if(strlen($VARS['f_searchvalue']) > 0)
            { $link .= "&f_searchvalue=" . urlencode($VARS['f_searchvalue']); }
            for($i=1;$i <= $this->SORT; $i++)
            {
                if(strlen($VARS['f_sortby' . $i]) > 0)
                { $link .= "&f_sortby$i=" . urlencode($VARS['f_sortby' . $i]); }
            }
?>
<script language="javascript">
<!--
var win = window.open("<?php echo $link ?>","","width=300,height=100,scrollbars");
// -->
</script>
<h2 align="center">WARNING: do NOT hit reload button on browser or the page will start exporting again.</h2>
<?php
        }
        else
        {
?>
<h2 align="center">
ERROR: could NOT access file "<?php echo $VARS['exportfile'] ?>"!<br>
Please check that PHP has file write access to that location.<br>
Then press the browser reload button to attempt export again.
</h2>
<?php
        }
    }

    function HTML_EXPORT($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $reload=0;
        $errors = "";

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
                $sortby .= " list.email_id ASC";
                $addsortcomma = 1;
            }
            elseif($VARS['f_sortby' . $i] == 'date')
            {
                $sortstart = " ORDER BY";
                if($addsortcomma)
                { $sortby .= ","; }
                $sortby .= " list.signup_dt ASC";
                $addsortcomma = 1;
            }
            elseif($VARS['f_sortby' . $i] > 0)
            {
                $sortstart = " ORDER BY";
                if($addsortcomma)
                { $sortby .= ","; }
                $sortby .= " list.option" . $VARS['f_sortby' . $i] . " ASC";
                $addsortcomma = 1;
            }
        }
        if($VARS['f_category'] == "General")
        { $sortby = eregi_replace("list.","",$sortby); }
        $sortby = $sortstart . $sortby;

        $where = "";
        if($VARS['f_category'] == "General")
        {
            if($VARS['f_searchfield'] == 'email')
            { $where .= "email_id " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
            elseif($VARS['f_searchfield'] == 'date')
            { $where .= "signup_dt " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
            elseif($VARS['f_searchfield'] && ($VARS['f_searchfield'] <= $CFG->optSize))
            { $where .= "option" . $VARS['f_searchfield'] . " " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
        }
        elseif(strlen($VARS['f_category']) <= 4)
        { # list is a category ID
            $where .= "cat_id = '" . $VARS['f_category'] . "'";
            if($VARS['f_searchfield'] == 'email')
            { $where .= " AND list.email_id " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
            elseif($VARS['f_searchfield'] == 'date')
            { $where .= " AND signup_dt " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
            elseif($VARS['f_searchfield'] && ($VARS['f_searchfield'] <= $CFG->optSize))
            { $where .= " AND option" . $VARS['f_searchfield'] . " " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
        } else { # list is a custom SQL statement
            $query = "SELECT * FROM " . $VARS['DB_TablePrefix'] . "_sqlstmt WHERE sql_id = '" . ereg_replace("custom_","",$VARS['f_category']) . "'";
            $result = mysql_query($query,$VARS['DB']);
            $row = mysql_fetch_array($result,1);
            $where .= "((" . $row['sql_stmt'] . ")";
            while($row = mysql_fetch_array($result,1))
            { $where .= " OR (" . $row['sql_stmt'] . ")"; }
            $where .= ")";
            if($VARS['f_searchfield'] == 'email')
            { $where .= " AND list.email_id " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
            elseif($VARS['f_searchfield'] == 'date')
            { $where .= " AND signup_dt " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
            elseif($VARS['f_searchfield'] && ($VARS['f_searchfield'] <= $CFG->optSize))
            { $where .= " AND option" . $VARS['f_searchfield'] . " " . $this->TRANS[$VARS['f_searchby']] . " '" . $searchvalue . "'"; }
        }
        if($where == "")
        {
            $query = "SELECT COUNT(*) FROM PREFIX_list WHERE email_id != '';";
        }
        else
        {
            if($VARS['f_category'] == "General")
            {
                $query = "SELECT COUNT(*) FROM PREFIX_list WHERE " . $where . ";";
            }
            elseif(strlen($VARS['f_category']) <= 4)
            {
                $query = "SELECT COUNT(*) FROM PREFIX_list AS list, PREFIX_catlist AS catlist WHERE ((list.email_id = catlist.email_id) AND (" . $where . "));";
            }
            else
            {
                $query = "SELECT COUNT(DISTINCT list.email_id) FROM PREFIX_list AS list, PREFIX_catlist AS catlist WHERE ((list.email_id = catlist.email_id) AND (" . $where . "));";
            }
        }
        $SQL->q($query);
        $SQL->nextrecord();
        $totrec = $SQL->f(0);
        if($totrec == 0)
        { $totrec = 1; }

        if($where == "")
        { $query = "SELECT * FROM PREFIX_list WHERE email_id != '' $sortby " . $SQL->limit($VARS['o'],$this->BATCH) . ";"; }
        else
        {
            if($VARS['f_category'] == "General")
            { $query = "SELECT * FROM PREFIX_list WHERE $where $sortby " . $SQL->limit($VARS['o'],$this->BATCH) . ";"; }
            elseif(strlen($VARS['f_category']) <= 4)
            { $query = "SELECT * FROM PREFIX_list AS list, PREFIX_catlist AS catlist WHERE (list.email_id = catlist.email_id) AND ($where) $sortby " . $SQL->limit($VARS['o'],$this->BATCH) . ";"; }
            else
            { $query = "SELECT * FROM PREFIX_list AS list, PREFIX_catlist AS catlist WHERE (list.email_id = catlist.email_id) AND ($where) GROUP BY list.email_id $sortby " . $SQL->limit($VARS['o'],$this->BATCH) . ";"; }
        }
        $SQL->q($query);

        if($fw = fopen("modules/" . $VARS['exportfile'] . ".export","a"))
        {
            while($SQL->nextrecord())
            {
                if($VARS['first'])
                {
                    if($VARS['first'] == 'email')
                    { fwrite($fw,$T->CiaoDecode($SQL->f('email_id')) . $VARS['delimiter']); }
                    elseif($VARS['first'] == 'date')
                    { fwrite($fw,$SQL->f('signup_dt') . $VARS['delimiter']); }
                    elseif($VARS['first'] > 0)
                    { fwrite($fw,$T->CiaoDecode($SQL->f('option' . $VARS['first'])) . $VARS['delimiter']); }
                }
                if($VARS['second'])
                {
                    if($VARS['second'] == 'email')
                    { fwrite($fw,$T->CiaoDecode($row['email_id']) . $VARS['delimiter']); }
                    elseif($VARS['second'] == 'date')
                    { fwrite($fw,$SQL->f('signup_dt') . $VARS['delimiter']); }
                    elseif($VARS['second'] > 0)
                    { fwrite($fw,$T->CiaoDecode($SQL->f('option' . $VARS['second'])) . $VARS['delimiter']); }
                }
                for($i = 1;$i <= $CFG->optSize; $i++)
                {
                    if($VARS['opt' . $i] == 'email')
                    { fwrite($fw,$T->CiaoDecode($SQL->f('email_id')) . $VARS['delimiter']); }
                    elseif($VARS['opt' . $i] == 'date')
                    { fwrite($fw,$SQL->f('signup_dt') . $VARS['delimiter']); }
                    elseif($VARS['opt' . $i] > 0)
                    { fwrite($fw,$T->CiaoDecode($SQL->f('option' . $VARS['opt' . $i])) . $VARS['delimiter']); }
                }
                fwrite($fw,"\n");
                $reload=1;
            }
            fclose($fw);
        }
        $VARS['o'] += $this->BATCH;
?>
<html><head><title>export status</title>
<script language="JavaScript">
<!--
function MySubmit()
{ document.form_export.submit(); }
// -->
</script>
</head>
<?php
        if($reload)
        { echo "<body onLoad=\"MySubmit()\">\n"; }
        else
        { echo "<body>\n"; }
?>
<form name="form_export">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="o" value="<?php echo (1 * $VARS['o']) ?>">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="frame_EXPORT" value="1">
<input type="hidden" name="delimiter" value="<?php echo $VARS['delimiter'] ?>">
<input type="hidden" name="exportfile" value="<?php echo $VARS['exportfile'] ?>">

<input type="hidden" name="first" value="<?php echo $VARS['first'] ?>">
<input type="hidden" name="second" value="<?php echo $VARS['second'] ?>">
<?php
        for($i = 1;$i <= $CFG->optSize; $i++)
        {
?>
<input type="hidden" name="opt<?php echo $i ?>" value="<?php echo $VARS['opt' . $i] ?>">
<?php
        }

        for($i = 1;$i <= $this->SORT; $i++)
        {
?>
<input type="hidden" name="f_sortby<?php echo $this->SORT ?>" value="<?php echo $VARS['f_sortby' . $i] ?>">
<?php
        }
?>

<input type="hidden" name="f_category" value="<?php echo $VARS['f_category'] ?>">
<input type="hidden" name="f_searchfield" value="<?php echo $VARS['f_searchfield'] ?>">
<input type="hidden" name="f_searchby" value="<?php echo $VARS['f_searchby'] ?>">
<input type="hidden" name="f_searchvalue" value="<?php echo $VARS['f_searchvalue'] ?>">

</form>
<h2 align="center">
<?php
        if($reload)
        { echo "Export In Progress"; }
        else
        { echo "Export Finished"; }

        $percent_finished = ceil(($VARS['o']/$totrec) * 100);
        $percent_remain = 100 - $percent_finished;
?>
</h2>

<table border="1" width="100%"><tr>
<?php
        if($percent_finished > 100)
        {
?>
<td align="center" bgcolor="#0000ff" width="100%">
<font color="#ffffff"><b>100%</b></font>
</td></tr></table>
<?php
        }
        else
        {
?>
<td align="center" bgcolor="#0000ff" width="<?php echo $percent_finished ?>%">
<font color="#ffffff"><b><?php echo $percent_finished ?>%</b></font>
</td><td width="<?php echo $percent_remain ?>%">&nbsp;</td>
</tr></table>
<?php
        }
?>

</body>
</html>
<?php
    }

    function HTML_FRAMES($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

?>
<html>
<head><title></title></head>
<frameset rows="1,*">
<frame name="filler" noresize scrolling="no">
<?php
        echo "\n<frame name=\"EXPORT\" src=\"ciaoadm.php?";
        echo "u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=s08&f_process=1&frame_EXPORT=1";
        echo "&delimiter=" . urlencode($VARS['delimiter']) . "&exportfile=" . urlencode($VARS['exportfile']);
        if(strlen($VARS['first']) > 0)
        { echo "&first=" . urlencode($VARS['first']); }
        if(strlen($VARS['second']) > 0)
        { echo "&second=" . urlencode($VARS['second']); }
        for($i=1;$i <= $CFG->optSize; $i++)
        {
            if(strlen($VARS['opt' . $i]) > 0)
            { echo "&opt$i=" . urlencode($VARS['opt' . $i]); }
        }
        if(strlen($VARS['f_category']) > 0)
        { echo "&f_category=" . urlencode($VARS['f_category']); }
        if(strlen($VARS['f_searchfield']) > 0)
        { echo "&f_searchfield=" . urlencode($VARS['f_searchfield']); }
        if(strlen($VARS['f_searchby']) > 0)
        { echo "&f_searchby=" . urlencode($VARS['f_searchby']); }
        if(strlen($VARS['f_searchvalue']) > 0)
        { echo "&f_searchvalue=" . urlencode($VARS['f_searchvalue']); }
        for($i=1;$i <= $this->SORT; $i++)
        {
            if(strlen($VARS['f_sortby' . $i]) > 0)
            { echo "&f_sortby$i=" . urlencode($VARS['f_sortby' . $i]); }
        }
        echo "\">\n";
?>
</frameset>
</html>
<?php
    }
}
?>
