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
# FILE: ciao_s02.php
# LOCAL VERSION: 1.0.01
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell (BD) - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.07.05 - BD - Added code for "ALL" category
# 2001.08.04 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#
# 2001.09.03 - Michael Mangeng - edited line 95 changing table lock from _catlist to _category.
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module handles category and database information.
#---------------------------------------------------------
# VARIABLE DEFINITIONS
# f_menu - menu for choosing a pre-existing category id
# f_id - category id
# f_name - category name
# ADD_EDIT - submit button resulting in such action
# DELETE - submit button resulting in such action
# f_process - (true/false) process data request
# x - used to store module id
# u - used to store user id
# p - used to store twice encryt password
?>

<?php 
class module
{
    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $T->head($VARS);
        echo "\n<h2 align=\"center\"><font color=\"" . $T->body_title . "\">CATEGORY ADMINISTRATION</font></h2>\n";
        if ($VARS['f_process'])
        {
############### ERROR HANDLING ###############
            $errors = "";
            if ($VARS['f_id'] == '')
            { $errors .= "\n<br>No 4 letter id for the category was entered."; }
            if ($VARS['f_name'] == '')
            { $errors .= "\n<br>No category name was entered."; }
            if (($errors == '') && ($VARS['f_id'] != "ALL"))
            {
############### UPDATE DATABASE ###############
                if ($VARS['f_menu'] == 0)
                {
############### ADD NEW RECORD ###############
                    $cat_id = $SQL->nid("PREFIX_category->cat_id");
                    $query = "INSERT INTO PREFIX_category VALUES('" . $cat_id . "','" . $VARS['f_id'] . "','" . $VARS['f_name'] . "');";
                    $SQL->locktable("PREFIX_category");
                    $SQL->q($query);
                    $SQL->unlocktable();
                    $VARS['f_nid'] = $cat_id;
                } else {
                    if ($VARS['DELETE'] != '')
                    {
############### DELETE EXISTING RECORD ###############
                        $query = "DELETE FROM PREFIX_category WHERE cat_id = '" . $VARS['f_id'] . "';";
                        $SQL->locktable("PREFIX_category");
                        $SQL->q($query);
                        $SQL->unlocktable();
                        $query = "DELETE FROM PREFIX_catlist WHERE cat_id = '" . $VARS['f_id'] . "';";
                        $SQL->locktable("PREFIX_catlist");
                        $SQL->q($query);
                        $SQL->unlocktable();
                    } else {
############### UPDATE EXISTING RECORD ###############
                        $query = "UPDATE PREFIX_category SET cat_name = '" . $VARS['f_name'] . "' WHERE cat_nid = " . $VARS['f_nid'] . ";";
                        $SQL->locktable("PREFIX_category");
                        $SQL->q($query);
                        $SQL->unlocktable();
                    }
                }
            } else {
############### DISPLAY ERRORS ###############
                $this->HTML_ERRORS($errors);
            }
            $this->HTML_LINK($VARS['u'],$VARS['p']);
        }
############### INTERACTIVE JAVASCRIPT FUNCTIONALITY ###############
?>
<script language="javascript">
<!--
function idChange()
{ // function that pops data into form for editing
    switch(document.category.f_menu.selectedIndex)
    {
        case 0:
            document.category.f_nid.value = "";
            document.category.f_id.value = "";
            document.category.f_name.value = "";
            break;
<?php
        $query = "SELECT * FROM PREFIX_category WHERE cat_id != 'ALL';";
        $SQL->q($query);
        $counter = 1;
        while($SQL->nextrecord())
        {
            $cat_nid[$counter] = trim($SQL->f('cat_nid'));
            $cat_id[$counter] = trim($SQL->f('cat_id'));
            $cat_name[$counter] = trim($SQL->f('cat_name'));
?>
        case <?php echo $counter ?>:
            document.category.f_nid.value = "<?php echo trim($SQL->f('cat_nid')) ?>";
            document.category.f_id.value = "<?php echo trim($SQL->f('cat_id')) ?>";
            document.category.f_name.value = "<?php echo trim($SQL->f('cat_name')) ?>";
            break;
<?php
            $counter = $counter + 1;
        }
?>
    }
} // end of javascript function
//-->
</script>
<?php
        $this->HTML_FORM($VARS,$SYS,$cat_id,$cat_nid,$cat_name);
        $T->tail($VARS);
    }

    function HTML_LINK($u,$p)
    {
?>
<big><b><center><a href="ciaoadm.php?x=s03&u=<?php echo urlencode($u) ?>&p=<?php echo urlencode($p) ?>">Click Here To Continue With Setup</a></center></b></big>
<?php
    }

    function HTML_ERRORS($errors)
    {
?>
<H2 align="center">"CATEGORY SETUP" ERRORS</H2>
<?php echo $errors ?>
<br>
<?php
    }

    function HTML_FORM($VARS,$SYS,$cat_id,$cat_nid,$cat_name)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

?>
<center>

<table border="0" align="center">
<tr><td colspan="2">
After a subscriber has verified, they can choose to join<br>
one or more categories of special topic information you create.<br>
The number of categories you can create is large.
</td></tr>
<tr><td valign="top">EXAMPLE:</td>
<td>
<ul>
<li>Travel - Countries of the World
<li>Sales - Inventory Products
<li>Educational - Courses
</td></tr>
</table>
<form name="category" action="ciaoadm.php" method="post">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td colspan="2" align="center">
<select name="f_menu" onChange="idChange()">
<?php
        echo " <option ";
        if ($VARS['f_menu'] == 0)
        { echo "selected "; }
        echo "value='0'>(New Category)\n";
        $counter = 1;
        while($counter <= sizeof($cat_id))
        {
            echo " <option";
            if ($VARS['f_menu'] == $counter)
            { echo " selected"; }
            echo " value='" . $counter . "'>(" . $cat_id[$counter] . ") " . $cat_name[$counter] . "\n";
            $counter = $counter + 1;
        }
?>
</select>
</td></tr>
<input type="hidden" name="f_nid" value="<?php echo $VARS['f_nid'] ?>">
<tr><td><font color="<?php echo $T->table_Text ?>">Category ID:</font></td><td><input type="text" name="f_id" value="<?php echo $VARS['f_id'] ?>" size="4" maxlength="4"> <font color="<?php echo $T->table_Text ?>">(4 Characters)</font></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Category Name:</font></td><td><input type="text" name="f_name" value="<?php echo $VARS['f_name'] ?>"></td></tr>
<tr><td colspan="2">
<input type="submit" name="ADD_EDIT" value="ADD/EDIT CATEGORY">
&nbsp; &nbsp; &nbsp; &nbsp;
<input type="submit" name="DELETE" value="DELETE CATEGORY">
</td></tr>
</table>
</form>
</center>
<?php
    }
}
?>
