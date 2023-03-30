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
# FILE: ciao_s07.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2001.03.07
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - means of contacting - brief description of enhancement)
#
# 2001.08.05 - BD (Ben Drushell) - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module modifies the "modules/ciaotools.php" file which defines appearance
# of administration system.
#---------------------------------------------------------
?>

<?php
class module
{
    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

        $T->head($VARS);
?>
<H2 align="center"><font color="<?php echo $T->body_title ?>">
Ciao EmailList Manager Admin System Colors
</font></H2>
<?php
        if ($VARS['f_process'])
        {
            $fin = file("modules/ciaotools.php");
            $output = "";
            while(list($key,$value) = each($fin))
            {
                if(strpos($value,"var \$body_background"))
                { $output .= "    var \$body_background = \"" . $VARS['body_background'] . "\";\n"; }
                elseif(strpos($value,"var \$body_bgcolor"))
                { $output .= "    var \$body_bgcolor = \"" . $VARS['body_bgcolor'] . "\";\n"; }
                elseif(strpos($value,"var \$body_Text"))
                { $output .= "    var \$body_Text = \"" . $VARS['body_Text'] . "\";\n"; }
                elseif(strpos($value,"var \$body_Link"))
                { $output .= "    var \$body_Link = \"" . $VARS['body_Link'] . "\";\n"; }
                elseif(strpos($value,"var \$body_title"))
                { $output .= "    var \$body_title = \"" . $VARS['body_title'] . "\";\n"; }
                elseif(strpos($value,"var \$table_bgcolor"))
                { $output .= "    var \$table_bgcolor = \"" . $VARS['table_bgcolor'] . "\";\n"; }
                elseif(strpos($value,"var \$table_row "))
                { $output .= "    var \$table_row = \"" . $VARS['table_row'] . "\";\n"; }
                elseif(strpos($value,"var \$table_row_text"))
                { $output .= "    var \$table_row_text = \"" . $VARS['table_row_text'] . "\";\n"; }
                elseif(strpos($value,"var \$table_row_link"))
                { $output .= "    var \$table_row_link = \"" . $VARS['table_row_link'] . "\";\n"; }
                elseif(strpos($value,"var \$table_altrow "))
                { $output .= "    var \$table_altrow = \"" . $VARS['table_altrow'] . "\";\n"; }
                elseif(strpos($value,"var \$table_altrow_text"))
                { $output .= "    var \$table_altrow_text = \"" . $VARS['table_altrow_text'] . "\";\n"; }
                elseif(strpos($value,"var \$table_altrow_link"))
                { $output .= "    var \$table_altrow_link = \"" . $VARS['table_altrow_link'] . "\";\n"; }
                elseif(strpos($value,"var \$table_Text"))
                { $output .= "    var \$table_Text = \"" . $VARS['table_Text'] . "\";\n"; }
                elseif(strpos($value,"var \$table_Link"))
                { $output .= "    var \$table_Link = \"" . $VARS['table_Link'] . "\";\n"; }
                elseif(strpos($value,"var \$menu_bgcolor"))
                { $output .= "    var \$menu_bgcolor = \"" . $VARS['menu_bgcolor'] . "\";\n"; }
                elseif(strpos($value,"var \$menu_Link"))
                { $output .= "    var \$menu_Link = \"" . $VARS['menu_Link'] . "\";\n"; }
                else
                { $output .= $value; }
            }
            unset($fin);
            if($fout = fopen("modules/ciaotools.php","w"))
            {
                fputs($fout,$output);
                fclose($fout);
            }
            unset($output);
?>

<script language="JavaScript">
<!--

top.location = "ciaoadm.php?x=<?php echo urlencode($VARS['x']) ?>&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>";

// -->
</script>

<?php
        }
        $this->HTML_SETUP("",$VARS,$SYS);
        $T->tail($VARS);
    }

    function HTML_SETUP($ERROR,$VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

?>

<script language="javascript">
<!--
function colorchart()
{ var win = window.open("ciaocolor.html","","width=150,height=300,scrollbars"); }

function predefined()
{
    switch(document.color.menu.selectedIndex)
    {
        case 1:
            document.color.body_background.value = "background.gif";
            document.color.body_bgcolor.value = "#ffffcc";
            document.color.body_Text.value = "#000099";
            document.color.body_Link.value = "#000099";
            document.color.body_title.value = "#000099";
            document.color.table_bgcolor.value = "#000099";
            document.color.table_row.value = "#cccccc";
            document.color.table_row_text.value = "#000099";
            document.color.table_row_link.value = "#660000";
            document.color.table_altrow.value = "#ffffff";
            document.color.table_altrow_text.value = "#000099";
            document.color.table_altrow_link.value = "#660000";
            document.color.table_Text.value = "#ffffcc";
            document.color.table_Link.value = "#ffffcc";
            document.color.menu_bgcolor.value = "#ffffcc";
            document.color.menu_Link.value = "#000099";
            break;
        case 2:
            document.color.body_background.value = "";
            document.color.body_bgcolor.value = "#000000";
            document.color.body_Text.value = "#cccccc";
            document.color.body_Link.value = "#cccccc";
            document.color.body_title.value = "#cccccc";
            document.color.table_bgcolor.value = "#000099";
            document.color.table_row.value = "#666666";
            document.color.table_row_text.value = "#ffffff";
            document.color.table_row_link.value = "#ffffff";
            document.color.table_altrow.value = "#ffffff";
            document.color.table_altrow_text.value = "#000099";
            document.color.table_altrow_link.value = "#660000";
            document.color.table_Text.value = "#cccccc";
            document.color.table_Link.value = "#cccccc";
            document.color.menu_bgcolor.value = "#666666";
            document.color.menu_Link.value = "#ffffff";
            break;
    }
}

// -->
</script>

<center>
<form method="post" action="ciaoadm.php" name="color">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="f_process" value="1">

<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">Predefined Settings:</font></td><td>
<select name="menu" onChange="predefined()">
<option SELECTED>Current (custom)
<option>(default)
<option>(default 2)
</select>
</td></tr>
<tr><td colspan="2" bgcolor="<?php echo $T->table_row ?>">&nbsp;</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Background Image:</font></td><td><input type="text" name="body_background" value="<?php echo trim($T->body_background) ?>" size="20"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Background Color:</font></td><td><input type="text" name="body_bgcolor" value="<?php echo trim($T->body_bgcolor) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td colspan="2" bgcolor="<?php echo $T->table_row ?>">&nbsp;</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Page Text:</font></td><td><input type="text" name="body_Text" value="<?php echo trim($T->body_Text) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Page Link:</font></td><td><input type="text" name="body_Link" value="<?php echo trim($T->body_Link) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Page Title:</font></td><td><input type="text" name="body_title" value="<?php echo trim($T->body_title) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td colspan="2" bgcolor="<?php echo $T->table_row ?>">&nbsp;</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Color:</font></td><td><input type="text" name="table_bgcolor" value="<?php echo trim($T->table_bgcolor) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Text:</font></td><td><input type="text" name="table_Text" value="<?php echo trim($T->table_Text) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Link:</font></td><td><input type="text" name="table_Link" value="<?php echo trim($T->table_Link) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td colspan="2" bgcolor="<?php echo $T->table_row ?>">&nbsp;</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Row:</font></td><td><input type="text" name="table_row" value="<?php echo trim($T->table_row) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Row Text:</font></td><td><input type="text" name="table_row_text" value="<?php echo trim($T->table_row_text) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Row Link:</font></td><td><input type="text" name="table_row_link" value="<?php echo trim($T->table_row_link) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td colspan="2" bgcolor="<?php echo $T->table_row ?>">&nbsp;</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Alternate Row :</font></td><td><input type="text" name="table_altrow" value="<?php echo trim($T->table_altrow) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Alternate Row Text:</font></td><td><input type="text" name="table_altrow_text" value="<?php echo trim($T->table_altrow_text) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Table Alternate Row Link:</font></td><td><input type="text" name="table_altrow_link" value="<?php echo trim($T->table_altrow_link) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td colspan="2" bgcolor="<?php echo $T->table_row ?>">&nbsp;</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Menu Color:</font></td><td><input type="text" name="menu_bgcolor" value="<?php echo trim($T->menu_bgcolor) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Menu Link:</font></td><td><input type="text" name="menu_Link" value="<?php echo trim($T->menu_Link) ?>" size="7" maxlength="7">
  (<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
  </td></tr>
</table>
<br><br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><input type="submit" value="SAVE COLOR SETTINGS"></td></tr>
</table>
</form>
</center>
<?php
    }
}
?>
