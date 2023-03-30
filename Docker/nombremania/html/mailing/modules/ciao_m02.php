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
# FILE: ciao_m02.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
# 2001.07.29 - BD (Ben Drushell) - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#
# 2001.08.31 - BD - fixed upload file feature
# 2001.09.08 - BD - fixed extra resume send data transfer
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module is an archive of messages saved for future sending.
#---------------------------------------------------------
# FORM VARIABLE DEFINITIONS
# x - used to store module id
# u - used to store user id
# p - used to store twice encryt password
# e - used to store id number of a message to edit
# d - used to store id number of a message to delete
# o - used to store offset value of current page
?>

<?php
class module
{
    var $SIZE = 20; # number of entries displayed on a page

    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $T->head($VARS);
        echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">SAVED MESSAGES/TEMPLATES FOR " . $T->CiaoDecode($VARS['u']) . "</font></h2>";
        if($VARS['m'] != '')
        { # continue sending message
            $query = "SELECT * FROM PREFIX_mail WHERE mail_id = '" . $VARS['m'] . "';";
            $SQL->q($query);
            $SQL->nextrecord();
            $VARS['f_sendtype'] = $T->CiaoDecode($SQL->f('mail_type'));
            $VARS['f_charset'] = $T->CiaoDecode($SQL->f('mail_charset'));
            $VARS['f_replyto'] = $T->CiaoDecode($SQL->f('mail_replyto'));

            if($this->START_SEND($VARS,$SYS))
            { $this->HTML_SUCCESS("WARNING: do not hit reload button on browser or the page will start sending again."); }
            else
            { $this->HTML_ERRORS("ERROR: Could NOT find message!"); }
        }
        elseif($VARS['f'] != '')
        { # forward to finished mail area and add error saying admin stopped early
            $query = "SELECT * FROM PREFIX_mail WHERE mail_id = '" . $VARS['f'] . "';";
            $SQL->q($query);
            $SQL->nextrecord();

            $errors = $T->CiaoEncode($T->CiaoDecode($SQL->f('mail_errors')) . "\n<br>Error: Unfinished Send At Administrator Option");
            $query = "UPDATE PREFIX_mail SET mail_errors = '$errors', mail_finish_dt = '" . date("Y-m-d H:i:s") . "' WHERE mail_id = '" . $VARS['f'] . "';";
            $SQL->locktable("PREFIX_mail");
            $SQL->q($query);
            $SQL->unlocktable();
            $this->HTML_SUCCESS("Message Deleted!");
        }
        elseif($VARS['d'] != '')
        { # delete a message
            $query = "DELETE FROM PREFIX_mail WHERE mail_id = '" . $VARS['d'] . "';";
            $SQL->locktable("PREFIX_mail");
            $SQL->q($query);
            $SQL->unlocktable();
            $this->HTML_SUCCESS("Message Deleted!");
        }
        elseif($VARS['f_process'] != '')
        {
            if($import = @file($VARS['importfile']))
            {
                unlink($VARS['importfile']);
                $import = implode($import," ");

                $cMessage = $T->CiaoEncode($import);
                $cSubject = $T->CiaoEncode($VARS['email_subject']);
                $cMailType = $T->CiaoEncode($VARS['email_type']);

                $mail_id = $SQL->nid("PREFIX_mail->mail_id");
                $query = "INSERT INTO PREFIX_mail VALUES('" . $mail_id . "','" . $VARS['user_id'] . "','" . $VARS['u'] . "','" . $cSubject . "','" . $cMessage . "','','','','0','','','" . $cMailType . "','','');";
                $SQL->locktable("PREFIX_mail");
                $SQL->q($query);
                $SQL->unlocktable();
            }
        }

        $query = "SELECT COUNT(*) FROM PREFIX_mail WHERE mail_finish_dt = '' AND mail_owner_email = '" . $VARS['u'] . "';";
        $SQL->q($query);
        $SQL->nextrecord();
        $totrec = $SQL->f(0);
?>
<center><p align='center'>
<form enctype="multipart/form-data" name="client" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="o" value="<?php echo (1 * $VARS['o']) ?>">
<input type="hidden" name="f_process" value="1">


<table border="0" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0" border="0">
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>">Upload/Import Email Templates</font></td></tr>
<tr>
<td bgcolor="<?php echo $T->table_row ?>"><font color="<?php echo $T->table_row_text ?>">File</font></td>
<td bgcolor="<?php echo $T->table_row ?>"><input type="file" name="importfile"></td>
</tr>
<tr>
<td bgcolor="<?php echo $T->table_row ?>"><font color="<?php echo $T->table_row_text ?>">Subject</font></td>
<td bgcolor="<?php echo $T->table_row ?>"><input type="text" name="email_subject"></td>
</tr>
<tr>
<td bgcolor="<?php echo $T->table_row ?>"><font color="<?php echo $T->table_row_text ?>">Email Type</font></td>
<td bgcolor="<?php echo $T->table_row ?>">
<select name="email_type">
<option>plain/text email
<option>html/text email
<option>multi-part (html/text and plain/text)
</select>
</td>
</tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input type="submit" name="btnUpload" value="Upload/Import File"></td></tr>
</table>

</form>
<br><br>

<table border="1" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0" border="0">
<tr><td align="center" colspan="3"><font color="<?php echo $T->table_Text ?>">
<?php
        if ($VARS['o'] <= 0)
        { echo "\n(Previous Page)"; }
        else
        { echo "\n<a href=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=" . urlencode($VARS['x']) . "&o=" . ($VARS['o'] - 1) . "\" style=\"" . $T->table_Link . "\"><font color=\"" . $T->table_Link . "\">(Previous Page)</font></a>"; }

        echo "\n&nbsp;&nbsp;&nbsp;&nbsp;";
        echo "\nPage " . ($VARS['o'] + 1) . " of " . ceil($totrec/$this->SIZE);
        echo "\n&nbsp;&nbsp;&nbsp;&nbsp;";

        if(($VARS['o']+1) >= ceil($totrec/$this->SIZE))
        { echo "\n(Next Page)"; }
        else
        { echo "\n<a href=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=" . urlencode($VARS['x']) . "&o=" . ($VARS['o'] + 1) . "\" style=\"" . $T->table_Link . "\"><font color=\"" . $T->table_Link . "\">(Next Page)</font></a>"; }
?>
</font></td></tr>
<?php
        $counter = 1;
        $query = "SELECT * FROM PREFIX_mail WHERE mail_finish_dt = '' AND mail_owner_email = '" . $VARS['u'] . "' " . $SQL->limit(($this->SIZE * $VARS['o']),$this->SIZE) . ";";
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
<tr>
<?php
            if(trim($SQL->f('mail_start_dt')) != '' && trim($SQL->f('mail_finish_dt')) == '')
            { # message that is not finished sending
?>
<td bgcolor="<?php echo $color ?>">
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=<?php echo urlencode($VARS['x']) ?>&o=<?php echo $VARS['o'] ?>&m=<?php echo urlencode($SQL->f('mail_id')) ?>" style="color:<?php echo $link ?>"><font color="<?php echo $link ?>">(continue sending)</font></a>
</td><td bgcolor="<?php echo $color ?>">
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=<?php echo urlencode($VARS['x']) ?>&o=<?php echo $VARS['o'] ?>&f=<?php echo urlencode($SQL->f('mail_id')) ?>" style="color:<?php echo $link ?>"><font color="<?php echo $link ?>">(delete)</font></a>
</td>
<?php
            }
            else
            { # message that is awaiting further editing
?>
<td bgcolor="<?php echo $color ?>">
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=m04&e=<?php echo urlencode($SQL->f('mail_id')) ?>&f_process=1" style="color:<?php echo $link ?>"><font color="<?php echo $link ?>">(edit)</font></a>
</td><td bgcolor="<?php echo $color ?>">
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=<?php echo urlencode($VARS['x']) ?>&o=<?php echo $VARS['o'] ?>&d=<?php echo $SQL->f('mail_id') ?>" style="color:<?php echo $link ?>"><font color="<?php echo $link ?>">(delete)</font></a>
</td>
<?php
            }
?>
<td bgcolor="<?php echo $color ?>" width="*">
<font color="<?php echo $text ?>"><?php echo $T->CiaoDecode($SQL->f('mail_subject')) ?></font>
</td></tr>
<?php
            $counter++;
        }
?>
</table>
</center></p>
<?php
        $T->tail($VARS);
    }

    function HTML_SUCCESS($MESSAGE)
    { echo "\n<H2 align=\"center\">$MESSAGE</H2>"; }

    function HTML_ERRORS($errors)
    { echo "\n<H2 align=\"center\">$errors</H2>"; }

    function START_SEND($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];

        $rvalue = 0;
        $query = "SELECT * FROM PREFIX_mail WHERE mail_owner_email = '" . $VARS['u'] . "' AND mail_id = '" . $VARS['m'] . "';";
        $SQL->q($query);
        if($SQL->nextrecord())
        {
?>
<script language="javascript">
<!--

<?php

echo "var win = window.open(\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=m04&f_process=1&m=" . urlencode($VARS['m']);
echo "&sendtype=" . urlencode($T->CiaoEncode($SQL->f('mail_type')));
if(strlen($T->CiaoEncode($SQL->f('mail_replyto'))) > 0)
{ echo "&replyto=" . urlencode($T->CiaoEncode($SQL->f('mail_replyto'))); }
if(strlen($T->CiaoEncode($SQL->f('mail_charset'))) > 0)
{ echo "&charset=" . urlencode($T->CiaoEncode($SQL->f('mail_charset'))); }
echo "\",\"\",\"width=300,height=150,scrollbars\");";

?>

// -->
</script>
<?php
            $rvalue = 1;
        }
        return($rvalue);
    }
}
?>
