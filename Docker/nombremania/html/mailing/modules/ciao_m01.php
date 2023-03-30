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
# FILE: ciao_m01.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2000.12.22 - BD (Ben Drushell) - added 600 width table for message view in case browser does not wordwrap
# 2000.12.23 - BD - replace two spaces with one space and &nbsp; to fix wordwrap
# 2001.07.29 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#
# 2001.08.31 - BD - added extra stripslashes code
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module is an archive of old messages sent.
#---------------------------------------------------------
# FORM VARIABLE DEFINITIONS
# f_process - (true/false) process data request
# x - used to store module id
# u - used to store user id
# p - used to store twice encryt password
# v - used to store id number of a message to display
# d - used to store id number of a message to delete
# o - used to store offset value of current page
?>

<?php
class module
{
    var $SIZE = 20; # number of entries listed on one page

    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

        if($VARS['v'] != '')
        { $this->VIEW_MESSAGE($VARS,$SYS); }
        else
        {
            $T->head($VARS);
            echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">SENT MESSAGE ARCHIVE</font></h2>";
            if($VARS['d'] != '')
            { $this->DELETE_MESSAGE($VARS,$SYS); }
            $this->PROCESS_FORM($VARS,$SYS);
            $T->tail($VARS);
        }
    }

    function PROCESS_FORM($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $query = "SELECT COUNT(*) FROM PREFIX_mail WHERE mail_finish_dt != '';";
        $SQL->q($query);
        $SQL->nextrecord();
        $totrec = $SQL->f(0);

############### INTERACTIVE JAVASCRIPT FUNCTIONALITY ###############
?>
<script language="javascript">
<!--
function processMessage(messageID)
{ // function that pops data into new window for easy display
    Window.open("ciaoadm.php?x=m01&p=<?php echo urlencode($VARS['p']) ?>&u=<?php echo urlencode($VARS['u']) ?>&v=" + messageID,"Archive Viewer","menubar,scrollbars,width=400,height=200");
} // end of javascript function
//-->
</script>
<center><p align='center'>
<?php echo $totrec ?> records archived.<br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0" border="1" width="98%">
<tr><td width="25%">&nbsp;</td>
<td width="45%"><font color="<?php echo $T->table_Text ?>">Subject</font></td>
<td width="30%" bgcolor="<?php echo $T->table_bgcolor ?>"><font color="<?php echo $T->table_Text ?>">Date</font></td></tr>
<?php
        $counter = 1;
        $query = "SELECT * FROM PREFIX_mail WHERE mail_finish_dt != '' " . $SQL->limit(($this->SIZE * $VARS['o']),$this->SIZE) . ";";
        $SQL->q($query);
        while($SQL->nextrecord())
        {
            if($counter % 2)
            {
                $color = $T->table_altrow;
                $text = $T->table_altrow_text;
                $link = $T->table_altrow_link;
            }
            else
            {
                $color = $T->table_row;
                $text = $T->table_row_text;
                $link = $T->table_row_link;
            }
?>
<tr><td width="25%" bgcolor="<?php echo $color ?>"><b>
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=<?php echo urlencode($VARS['x']) ?>&v=<?php echo $SQL->f('mail_id') ?>" style="color:<?php echo $link ?>" target="_blank"><font color="<?php echo $link ?>">(view)</font></a>
&nbsp; &nbsp;
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=<?php echo urlencode($VARS['x']) ?>&o=<?php echo (0 + $VARS['o']) ?>&d=<?php echo $SQL->f('mail_id') ?>" style="color:<?php echo $link ?>"><font color="<?php echo $link ?>">(delete)</font></a>
</b></td><td width="45%" bgcolor="<?php echo $color ?>">
<font color="<?php echo $text ?>"><?php echo $T->CiaoDecode($SQL->f('mail_subject')) ?></font>
</td><td width="30%" bgcolor="<?php echo $color ?>"><font color="<?php echo $text ?>">
<?php echo $SQL->f('mail_finish_dt') ?>
</font></td></tr>
<?php
            $counter = $counter + 1;
        }
?>
</table>

<br>
<b>Page <?php echo ($VARS['o'] + 1) ?> of <?php echo ceil($totrec/$this->SIZE) ?></b>
<br>

<table border="1" cellpadding="5" cellspacing="0" align="center" bgcolor="<?php echo $T->table_bgcolor ?>">
<tr><td align="center"><font color="<?php echo $T->table_Text ?>">
<?php
        if($VARS['o'] <= 9)
        { echo "\n(Skip Back 10 Pages)"; }
        else
        { echo "\n<a href=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=" . $VARS['x'] . "&o=" . ($VARS['o'] - 10) . "\" style=\"color:" . $T->table_Link . "\"><font color=\"" . $T->table_Link . "\">(Skip Back 10 Pages)</font></a>"; }
?>
</font></td>
<td align="center"><font color="<?php echo $T->table_Text ?>">
<?php
        if($VARS['o'] <= 0)
        { echo "\n(Previous Page)"; }
        else
        { echo "\n<a href=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=" . $VARS['x'] . "&o=" . ($VARS['o'] - 1) . "\" style=\"color:" . $T->table_Link . "\"><font color=\"" . $T->table_Link . "\">(Previous Page)</font></a>"; }
?>
</font></td>
<td align="center"><font color="<?php echo $T->table_Text ?>">
<?php
        if(($VARS['o'] + 1) >= ceil($totrec/$this->SIZE))
        { echo "\n(Next Page)"; }
        else
        { echo "\n<a href=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=" . $VARS['x'] . "&o=" . ($VARS['o'] + 1) . "\" style=\"color:" . $T->table_Link . "\"><font color=\"" . $T->table_Link . "\">(Next Page)</font></a>"; }
?>
</font></td>
<td align="center"><font color="<?php echo $T->table_Text ?>">
<?php
        if($VARS['o'] >= (ceil($totrec/$this->SIZE)-10))
        { echo "\n(Skip Forward 10 Pages)"; }
        else
        { echo "\n<a href=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=" . $VARS['x'] . "&o=" . ($VARS['o'] + 10) . "\" style=\"color:" . $T->table_Link . "\"><font color=\"" . $T->table_Link . "\">(Skip Forward 10 Pages)</font></a>"; }
        echo "\n</font></td></tr>";
?>
</font></td></tr>
</table>
</center></p>
<?php
    }

    function DELETE_MESSAGE($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];

        $query = "DELETE FROM PREFIX_mail WHERE mail_id = '" . $VARS['d'] . "';";
        $SQL->locktable("PREFIX_mail");
        $SQL->q($query);
        $SQL->unlocktable();
        $this->HTML_SUCCESS("Message Deleted!");
    }

    function VIEW_MESSAGE($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $query = "SELECT * FROM PREFIX_mail,PREFIX_user WHERE mail_id = '" . $VARS['v'] . "' AND PREFIX_mail.mail_owner_id = PREFIX_user.user_id;";
        $SQL->q($query);
        $SQL->nextrecord();
        $row['mail_body'] = ereg_replace("  "," &nbsp;",$T->CiaoDecode($SQL->f('mail_body')));
        $row['mail_body'] = ereg_replace("\t"," &nbsp; &nbsp;",$row['mail_body']);
        $row['mail_body'] = ereg_replace("\n"," <br>",$row['mail_body']);
        $row['mail_errors'] = ereg_replace("  "," &nbsp;",$T->CiaoDecode($SQL->f('mail_errors')));
        $row['mail_errors'] = ereg_replace("\t"," &nbsp; &nbsp;",$row['mail_errors']);
        $row['mail_errors'] = ereg_replace("\n"," <br>",$row['mail_errors']);
        $row['mail_attachments'] = str_replace("modules","_",$T->CiaoDecode($SQL->f('mail_attachments')));
# Ben Drushell - added 600 width table for message view in case browser does not wordwrap
?>
<html>
<head><title>CIAO Message Archive Viewer</title></head>
<body bgcolor="#ffffff" text="#000000">
<hr>
Message Information:<br>
Sent By UserID <?php echo "(" . $SQL->f('mail_owner_id') . ") " . $T->CiaoDecode($SQL->f('email')) ?><br>
Send Started On <?php echo $SQL->f('mail_start_dt') ?><br>
Send Finished On <?php echo $SQL->f('mail_finish_dt') ?><br>
Sent <?php echo (0 + $SQL->f('mail_offset')) ?><br>
Email-Type <?php echo $T->CiaoDecode($SQL->f('mail_type')) ?><br>
Email-Attachments <?php echo $row['mail_attachments'] ?><br>
Title "<?php echo stripslashes($T->CiaoDecode($SQL->f('mail_subject'))) ?>"<br>
<hr>
Message Sent:<br>
<table border="0" width="600"><tr><td>
<?php echo stripslashes(trim($row['mail_body'])) ?>
</td></tr>
</table>
<hr>
(NOTE: Many send-errors can be an indication that there is a mail server mis-configuration.)<br>
Sending Errors:<br>
<?php echo stripslashes($row['mail_errors']) ?>
</body>
</html>
<?php
    }

    function HTML_SUCCESS($MESSAGE)
    { echo "<H2 align=\"center\">$MESSAGE SUCCESSFUL</H2>"; }

    function HTML_ERRORS($errors)
    { echo "$errors <br>"; }
}
?>
