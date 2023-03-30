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

# FILE: ciao_m04.php

# LOCAL VERSION: 1.0.02

# CREATED ON: 2000.10.30

# CREATED BY: Ben Drushell - http://www.technobreeze.com/

# CONTRIBUTORS:

#(date - name - brief description of enhancement)

# 2000.12.19 - Wayne Davis - Code changed because of mysql_insert_id bug in PHP4

# 2000.12.22 - BD (Ben Drushell) - Fixed pause/continue; Fixed sending status bar;

# 2000.12.23 - BD - Added customized message with tags like #option1#

# 2001.01.28 - BD - Added HTML email capabilities

# 2001.02.01 - BD - Added codes so <script> tags get removed from email

# 2001.04.22 - BD - Added character-set code... changed test send to test/individual send

# 2001.05.07 - BD - replaced quoted-printable with 8-bit

# 2001.05.08 - BD - fixed link to sending html frame; charset data passing; multi-type send errors

# 2001.05.11 - Gerbrand van Dieijen - added MIME-Version 1.0 to html email header for PINE compatibility

# 2001.05.22 - BD - Added "return-path" field to email headers sent

# 2001.05.24 - Ryan Foster - added MIME-Version 1.0 to multi-part email header for various email client compatibility

# 2001.05.31 - BD - Removed codes so that script and comment tags are permitted again

# 2001.06.16 - BD - Fixed SQL statement "GROUP BY" clause adversly affecting custom categories

# 2001.06.24 - BD - Added Error-To email header for bounce messages on some servers

# 2001.07.05 - BD - Added code for "ALL" category.

# 2001.07.18 - BD - added code to remove "\r" from email messages for Outlook compatibility

# 2001.07.29 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools

# 2001.08.31 - BD - added extra strip slashes code

# 2001.09.06 - BD - added #email#, #domain#, and #date# data-tags for emails sent

# 2001.09.08 - BD - removed ordering by domain to prevent domain bans for sending to many emails to a network at once.

# 2001.09.08 - BD - added time-delay feature so that mail networks do not get bogged down with a send.

#

# 2001.09.22 - BD - fixed send all category bug... replaced sql_stmt with mail_sql_stmt and replace cat_id size 3 with size 4

#---------------------------------------------------------

?>



<?php

# SHORT DESCRIPTION

# This module handles the composing and sending operations.

#---------------------------------------------------------

# FORM VARIABLE DEFINITIONS

# f_process - (true/false) process data request

# x - used to store module id

# u - used to store user id

# p - used to store twice encryt password

# m - used to store mail_id

# f_title - message subject

# f_message - message

# f_menu - custom list or category in which to send message

# f_address - test address to send to

# f_btnSAVE - button to save copy of message for later use.

# f_btnEDIT - command received from saved area to further edit message

# f_btnTEST - send a test e-mail message to see what it looks like

# f_btnSEND - begin sending message to the list

# frame_send - (1|0) frame for sending the message

# frame_pause - (1|0) frame for sending paused message

# frame_toolbar - (1|0) toolbar frame for pausing/resuming sending

?>



<?php

class module

{

    var $BATCH = 20; # number of e-mails to send per batch

    var $TIMEDELAY = 0; # number of seconds between each batch request



    function module($VARS,$SYS)

    {

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $T = $SYS['T'];

        $CFG = $SYS['CFG'];



        if($CFG->BatchSize > 0)

        { $this->BATCH = $CFG->BatchSize; }

        if($CFG->TimeDelay > 0)

        { $this->TIMEDELAY = $CFG->TimeDelay; }



        if($VARS['f_process'] != '')

        { $this->PROCESS_FORM($VARS,$SYS); }

        else

        {

            $T->head($VARS);

            echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">COMPOSE MESSAGE</font></h2>";

            $this->HTML_FORM($VARS,$SYS);

            $T->tail($VARS);

        }

    }



    function PROCESS_FORM($VARS,$SYS)

    {

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $T = $SYS['T'];



        $VARS['f_message'] = ereg_replace("\r"," ",$VARS['f_message']);

        if($VARS['f_btnSAVE'] != '')

        {

            $T->head($VARS);

            echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">COMPOSE MESSAGE</font></h2>";

            $this->SAVE_MESSAGE($VARS,$SYS);

            $this->HTML_FORM($VARS,$SYS);

            $T->tail($VARS);

        }

        elseif($VARS['e'] != '')

        { # edit saved message

            $T->head($VARS);

            echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">COMPOSE MESSAGE</font></h2>";

            $this->RETRIEVE_MESSAGE($VARS,$SYS);

            $this->HTML_FORM($VARS,$SYS);

            $T->tail($VARS);

        }

        elseif($VARS['f_btnTEST'] != '')

        {

            $T->head($VARS);

            echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">COMPOSE MESSAGE</font></h2>";

						echo $this->TEST_SEND($VARS,$SYS);

            $this->HTML_FORM($VARS,$SYS);

            $T->tail($VARS);

        }

        elseif($VARS['f_btnSEND'] != '')

        {

            $T->head($VARS);

            echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">COMPOSE MESSAGE</font></h2>";

            $this->START_SEND($VARS,$SYS);

            $T->tail($VARS);

        }

        elseif($VARS['frame_SEND'] != '')

        { $this->HTML_SEND($VARS,$SYS); }

        elseif($VARS['frame_PAUSE'] != '')

        { $this->HTML_PAUSE(); }

        elseif($VARS['frame_TOOLBAR'] != '')

        { $this->HTML_TOOLBAR($VARS,$SYS); }

        else

        { $this->HTML_FRAMES($VARS); }

    }



    function HTML_FORM($VARS,$SYS)

    {

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $SQL = $SYS['SQL'];

        $T = $SYS['T'];

        $CFG = $SYS['CFG'];



?>



<form method="post" name="emailer" action="ciaoadm.php">

<input type="hidden" name="f_process" value="1">

<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">

<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">

<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">



<script language="JavaScript">

<!--



function MultiCheck()

{

    if(document.emailer.f_sendtype[document.emailer.f_sendtype.selectedIndex].value == "multi")

    {

        var charset = document.emailer.f_charset.value;

        var multitext = "\n-INSERT PLAIN TEXT HERE-\n\n\n";

        multitext = multitext + "#PLAIN-2-HTML-BOUNDARY#\n";

        multitext = multitext + "\n<b>INSERT HTML/TEXT HERE</b>\n\n\n";

        document.emailer.f_message.value = multitext;

    }

    else

    { document.emailer.f_message.value = ""; }

}



// -->

</script>



<br>

<center>

<table align="center" border=1 bgcolor="<?php echo $T->table_bgcolor ?>">

<?php

        if($VARS['f_charset'] == '')

        { $VARS['f_charset'] = 'iso-8859-1'; }

?>

<tr><td align="right"><font color="<?php echo $T->table_Text ?>">Character Set:</font></td><td><input TYPE="TEXT" NAME="f_charset" VALUE="<?php echo stripslashes($VARS['f_charset']) ?>"></td></tr>

<tr><td colspan=2><font color="<?php echo $T->table_Text ?>">

FORMAT:



<SELECT name="f_sendtype" onChange="MultiCheck(this)">



<?php  if($VARS['f_sendtype'] == "text" || $VARS['f_sendtype'] == ""){ ?>

<option value="text" SELECTED>

<?php  } else { ?>

<option value="text">

<?php  } ?>

Send in Plain Text Format



<?php  if($VARS['f_sendtype'] == "html"){ ?>

<option value="html" SELECTED>

<?php  } else { ?>

<option value="html">

<?php  } ?>

Send in HTML Format



<?php  if($VARS['f_sendtype'] == "multi"){ ?>

<option value="multi" SELECTED>

<?php  } else { ?>

<option value="multi">

<?php  } ?>

Send in Multiple Formats (Plain Text & HTML)



</SELECT>



</font></td></tr>



<tr><td align="right"><font color="<?php echo $T->table_Text ?>">Reply-To (optional):</font></td><td><input TYPE="TEXT" NAME="f_replyto" VALUE="<?php echo stripslashes($VARS['f_replyto']) ?>"></td></tr>

<tr><td align="right"><font color="<?php echo $T->table_Text ?>">Subject:</font></td><td><input TYPE="TEXT" NAME="f_title" VALUE="<?php echo stripslashes($VARS['f_title']) ?>"></td></tr>

<tr><td colspan=2 align="center"><TEXTAREA NAME="f_message" COLS="50" ROWS="10" WRAP="VIRTUAL"><?php echo stripslashes($VARS['f_message']) ?></TEXTAREA></td></tr>

<tr><td colspan=2 align="left"><font color="<?php echo $T->table_Text ?>">Attachments: <input TYPE="TEXT" NAME="f_attachments" SIZE="40" VALUE="<?php echo stripslashes($VARS['f_attachments']) ?>"><br>

    (Seperate file attachment links with a semi-colon ";")</font></td></tr>

<tr><td colspan=2 align="left"><font color="<?php echo $T->table_Text ?>">

Sending List:</font>

<select name="f_menu">

 <option value="General">All Subscribers

<?php

        $query = "SELECT * FROM PREFIX_category WHERE cat_id != '';";

        $SQL->q($query);

        while($SQL->nextrecord())

        {

            if(trim($SQL->f('cat_id')) != "ALL")

            { echo "\n <option value=\"" . trim($SQL->f('cat_id')) . "\">(" . trim($SQL->f('cat_id')) . ") " . trim($SQL->f('cat_name')); }

        }

        $query = "SELECT * FROM PREFIX_sql WHERE sql_id != '';";

        $SQL->q($query);

        while($SQL->nextrecord())

        { echo "\n <option value=\"custom_" . trim($SQL->f('sql_id')) . "\">(". trim($SQL->f('sql_id')) .") " . trim($SQL->f('sql_name')); }

?>

</select>

</td></tr>

<tr><td align="center" width="50%" rowspan="2"><font color="<?php echo $T->table_Text ?>">

<input type="submit" name="f_btnTEST" value="TEST/INDIVIDUAL SEND"><br>

TO: <input type="text" name="f_address" value="<?php echo stripslashes($VARS['f_address']) ?>">

</font></td><td align="center" width="50%">

<input type="submit" NAME="f_btnSAVE" VALUE="Save E-mail"></td></tr>

<tr><td align="center" width="50%"><input type="submit" name="f_btnSEND" VALUE="Send E-mail"></td></tr>

<tr><td colspan="2" bgcolor="<?php echo $T->table_Text ?>" align="center">



<table width="400" border="0"><tr><td>



<font color="<?php echo $T->table_bgcolor ?>">

<b>CUSTOM MESSAGE:</b><br><br>

You may want to customize your message based on

subscriber data.  There are two means of referencing

this data.  One method is to insert an #option1# tag.

The other method is to use the name given to the field.

If option1 was defined as "Name", you could use #name#.

<br>

Example Message:<br>

#name#,<br>

The new computers have arrived.  A sales

representative is available at xxx-xxx-xxxx

and will be happy to quote a price for a

#option2# computer.

<br>

LOGIN LINK:<br> #loginlink#<br>

UNSUBSCRIBE:<br> #removelink#

<br><br>

John Doe,<br>

The new computers have arrived.  A sales

representative is available at xxx-xxx-xxxx

and will be happy to quote a price for a

wearable computer.

<br>

LOGIN LINK:<br> <u>http://www.example.com/ciao-elm/ciao.php?f=aaaa&fp=aaaa</u><br>

UNSUBSCRIBE:<br> <u>http://www.example.com/ciao-elm/ciao.php?f=aaaa&fp=aaaa&unsubscribe=1</u>

</font>



</td></tr></table>



</td></tr>

</table>

</center>

</form>

<?php

    }



    function SAVE_MESSAGE($VARS,$SYS)

    {

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $SQL = $SYS['SQL'];

        $T = $SYS['T'];



        $query = "SELECT * FROM PREFIX_user WHERE email = '" . $VARS['u'] . "';";

        $SQL->q($query);

        $SQL->nextrecord();

        $ID = $SQL->nid("PREFIX_mail->mail_id");

        $query = "INSERT INTO PREFIX_mail VALUES('" . $ID . "','" . trim($SQL->f('user_id')) . "','" . $VARS['u'] . "','" . $T->CiaoEncode($VARS['f_title']) . "','" . $T->CiaoEncode($VARS['f_message']) . "','" . $T->CiaoEncode($VARS['f_attachments']) . "','','','0','','','" . $T->CiaoEncode($VARS['f_sendtype']) . "','" . $T->CiaoEncode($VARS['f_charset']) . "','" . $T->CiaoEncode($VARS['f_replyto']) . "');";

        $SQL->locktable("PREFIX_mail");

        $SQL->q($query);

        $SQL->unlocktable();

    }



    function RETRIEVE_MESSAGE(&$VARS,$SYS)

    {

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $SQL = $SYS['SQL'];

        $T = $SYS['T'];



        $query = "SELECT * FROM PREFIX_mail WHERE mail_id = " . $VARS['e'] . ";";

        $SQL->q($query);

        $SQL->nextrecord();

        $VARS['f_title'] = $T->CiaoDecode($SQL->f('mail_subject'));

        $VARS['f_message'] = $T->CiaoDecode($SQL->f('mail_body'));

        $VARS['f_sendtype'] = $T->CiaoDecode($SQL->f('mail_type'));

        $VARS['f_attachments'] = $T->CiaoDecode($SQL->f('mail_attachments'));

        $VARS['f_charset'] = $T->CiaoDecode($SQL->f('mail_charset'));

        $VARS['f_replyto'] = $T->CiaoDecode($SQL->f('mail_replyto'));

    }



    function TEST_SEND($VARS,$SYS)

    {

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $SQL = $SYS['SQL'];

        $T = $SYS['T'];

        $CFG = $SYS['CFG'];

        $MAIL = $SYS['MAIL'];

        $errors = "";

        $query = "SELECT * FROM PREFIX_list WHERE email_id = '" . $T->CiaoEncode($VARS['f_address']) . "';";

        $SQL->q($query);

        if($SQL->nextrecord())

        { # customize email messages

# code for login link

            $loginlink = $CFG->url . "ciao.php?f=" . trim($SQL->f('email_id')) . "&fp=" . trim($SQL->f('password'));

            $VARS['f_message'] = eregi_replace("#loginlink#",$loginlink,$VARS['f_message']);

# code for removal link

            $VARS['f_message'] = eregi_replace("#removelink#",$loginlink . "&unsubscribe=1",$VARS['f_message']);



# code for email data

            $VARS['f_message'] = eregi_replace("#email#",$T->CiaoDecode($SQL->f('email_id')),$VARS['f_message']);

            $VARS['f_title'] = eregi_replace("#email#",$T->CiaoDecode($SQL->f('email_id')),$VARS['f_title']);

# code for domain data

            $VARS['f_message'] = eregi_replace("#domain#",$T->CiaoDecode($SQL->f('domain')),$VARS['f_message']);

            $VARS['f_title'] = eregi_replace("#domain#",$T->CiaoDecode($SQL->f('domain')),$VARS['f_title']);

# code for sign-up date data

            $VARS['f_message'] = eregi_replace("#date#",$SQL->f('signup_dt'),$VARS['f_message']);

            $VARS['f_title'] = eregi_replace("#date#",$SQL->f('signup_dt'),$VARS['f_title']);



            for($i=1; $i <= $CFG->optSize; $i++)

            {

# code for optional data

                $VARS['f_message'] = eregi_replace("#option$i#",$T->CiaoDecode($SQL->f('option' . $i)),$VARS['f_message']);

                $VARS['f_message'] = eregi_replace("#" . $CFG->optName[$i] . "#",$T->CiaoDecode($SQL->f('option' . $i)),$VARS['f_message']);

                $VARS['f_title'] = eregi_replace("#option$i#",$T->CiaoDecode($SQL->f('option' . $i)),$VARS['f_title']);

                $VARS['f_title'] = eregi_replace("#" . $CFG->optName[$i] . "#",$T->CiaoDecode($SQL->f('option' . $i)),$VARS['f_title']);

            }

        }


# code for adding attachments

        for($token = strtok($VARS['f_attachments'],";"); $token != ""; $token = strtok(";"))

        { $MAIL->AddAttachment(str_replace("modules","_",$token)); } # sift out "modules" for safety of config classes.



        if($VARS['f_address'] != '')

        { $error = $this->SEND_EMAIL(stripslashes($VARS['f_address']),stripslashes($VARS['f_title']),stripslashes($VARS['f_message']),stripslashes($VARS['f_replyto']),stripslashes($VARS['f_sendtype']),$MAIL,stripslashes($VARS['f_charset'])); }

        else

        { $error = "\nNO destination address was specified to test send.<br>"; }

        return($error);

    }



    function START_SEND($VARS,$SYS)

    {

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $SQL = $SYS['SQL'];

        $T = $SYS['T'];

        $CFG = $SYS['CFG'];



        $where = " ";

        if($VARS['f_menu'] == "General")

        { $where = ""; }

        elseif(strlen($VARS['f_menu']) <= 4)

        { # list is a category ID

            $where .= "cat_id = '" . $VARS['f_menu'] . "'";

        } else { # list is a custom SQL statement

            $query = "SELECT * FROM PREFIX_sqlstmt WHERE sql_id = '" . ereg_replace("custom_","",$VARS['f_menu']) . "';";

            $SQL->q($query);

            $SQL->nextrecord();

            $where .= "((" . trim($SQL->f('sql_stmt')) . ")";

            while($SQL->nextrecord())

            { $where .= " OR (" . trim($SQL->f('sql_stmt')) . ")"; }

            $where .= ")";

        }

        $query = "SELECT user_id FROM PREFIX_user WHERE email = '" . $VARS['u'] . "';";

        $SQL->q($query);

        $SQL->nextrecord();

        $id = $SQL->Record;



        $mail_id = $SQL->nid("PREFIX_mail->mail_id");

        $query = "INSERT INTO PREFIX_mail VALUES('" . $mail_id . "','" . trim($id['user_id']) . "','" . $VARS['u'] . "','" . $T->CiaoEncode($VARS['f_title']) . "','" . $T->CiaoEncode($VARS['f_message']) . "','" . $T->CiaoEncode($VARS['f_attachments']) . "','" . addslashes($where) . "','','0','" . date("Y-m-d H:i:s") . "','','" . $T->CiaoEncode($VARS['f_sendtype']) . "','" . $T->CiaoEncode($VARS['f_charset']) . "','" . $T->CiaoEncode($VARS['f_replyto']) . "');";

        $SQL->locktable("PREFIX_mail");

        $SQL->q($query);

        $SQL->unlocktable();

?>

<script language="javascript">

<!--



<?php



echo "var win = window.open(\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=" . urlencode($VARS['x']) . "&f_process=1&m=" . urlencode($mail_id);

echo "&sendtype=" . $VARS['f_sendtype'];

if($VARS['f_replyto'])

{ echo "&replyto=" . urlencode($VARS['f_replyto']); }

if($VARS['f_charset'])

{ echo "&charset=" . urlencode($VARS['f_charset']); }

echo "\",\"\",\"width=300,height=150,scrollbars\");";



?>



// -->

</script>

<h2 align="center">WARNING: Do not hit reload button on browser or the page will start sending again.<br>

A pop-up window should appear that initiates the sending.</h2>

<?php

    }



    function HTML_SEND($VARS,$SYS)

    {

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $SQL = $SYS['SQL'];

        $T = $SYS['T'];

        $CFG = $SYS['CFG'];

        $MAIL = $SYS['MAIL'];



        $errors = "";

        $reload = 0;

        $query = "SELECT * FROM PREFIX_mail WHERE mail_id = '" . $VARS['m'] . "';";

        $SQL->q($query);

        $SQL->nextrecord();

        $rmail = $SQL->Record;



# code for adding attachments

        for($token = strtok($T->CiaoDecode($rmail['mail_attachments']),";"); $token != ""; $token = strtok(";"))

        { $MAIL->AddAttachment(str_replace("modules","_",$token)); } # sift out "modules" for config class safety



        if($rmail['mail_sql_stmt'] != "") #fixed error Ben Drushell 2000.12.22

        { $query = "SELECT * FROM PREFIX_list list, PREFIX_catlist catlist WHERE (list.email_id = catlist.email_id) AND " . $rmail['mail_sql_stmt'] . " GROUP BY list.email_id " . $SQL->limit($rmail['mail_offset'],$this->BATCH) . ";"; }

        else

        { $query = "SELECT * FROM PREFIX_list WHERE email_id != '' " . $SQL->limit($rmail['mail_offset'],$this->BATCH) . ";"; }

        $SQL->q($query);

        while($SQL->nextrecord())

        { # customize email messages

            $mail_subject = $T->CiaoDecode($rmail['mail_subject']);

            $mail_body = $T->CiaoDecode($rmail['mail_body']);



# code for login link & removal link

            $loginlink = $CFG->url . "ciao.php?f=" . urlencode(trim($SQL->f('email_id'))) . "&fp=" . urlencode(trim($SQL->f('password')));

            $mail_body = eregi_replace("#loginlink#",$loginlink,$mail_body);

            $mail_body = eregi_replace("#removelink#",$loginlink . "&unsubscribe=1",$mail_body);



# code for email data

            $mail_body = eregi_replace("#email#",$T->CiaoDecode($SQL->f('email_id')),$mail_body);

            $mail_subject = eregi_replace("#email#",$T->CiaoDecode($SQL->f('email_id')),$mail_subject);

# code for domain data

            $mail_body = eregi_replace("#domain#",$T->CiaoDecode($SQL->f('domain')),$mail_body);

            $mail_subject = eregi_replace("#domain#",$T->CiaoDecode($SQL->f('domain')),$mail_subject);

# code for signup date data

            $mail_body = eregi_replace("#date#",$SQL->f('signup_dt'),$mail_body);

            $mail_subject = eregi_replace("#date#",$SQL->f('signup_dt'),$mail_subject);



# code for optional data

            for($i=1; $i <= $CFG->optSize; $i++)

            {

                $mail_body = eregi_replace("#option$i#",$T->CiaoDecode($SQL->f('option' . $i)),$mail_body);

                $mail_body = eregi_replace("#" . $CFG->optName[$i] . "#",$T->CiaoDecode($SQL->f('option' . $i)),$mail_body);

                $mail_subject = eregi_replace("#option$i#",$T->CiaoDecode($SQL->f('option' . $i)),$mail_subject);

                $mail_subject = eregi_replace("#" . $CFG->optName[$i] . "#",$T->CiaoDecode($SQL->f('option' . $i)),$mail_subject);

            }

            $errors .= $this->SEND_EMAIL(stripslashes($T->CiaoDecode($SQL->f('email_id'))),stripslashes($mail_subject),stripslashes($mail_body),stripslashes($VARS['replyto']),stripslashes($VARS['sendtype']),$MAIL,stripslashes($VARS['charset']));

            $reload = 1;

        }

        $query = "UPDATE PREFIX_mail SET mail_errors = '" . $T->CiaoEncode($T->CiaoDecode($rmail['mail_errors']) . $errors) . "', mail_offset = " . ($rmail['mail_offset'] + $this->BATCH) . " WHERE mail_id = " . $VARS['m'] . ";";

        $SQL->locktable("PREFIX_mail");

        $SQL->q($query);

        $SQL->unlocktable();

        if($rmail['mail_sql_stmt'] != "")

        { $query = "SELECT COUNT(DISTINCT list.email_id) FROM PREFIX_list AS list, PREFIX_catlist AS catlist WHERE (list.email_id = catlist.email_id) AND " . trim($rmail['mail_sql_stmt']) . ";"; }

        else

        { $query = "SELECT COUNT(*) FROM PREFIX_list WHERE email_id != '';"; }

        $SQL->q($query);

        $SQL->nextrecord();

        $totrec = $SQL->f(0);



        if($totrec == 0)

        { $totrec = 1; }



        $percent_finished = ceil((($rmail['mail_offset'] + $this->BATCH)/$totrec) * 100); #fixed error Ben Drushell 2000.12.22

        $percent_remain = 100 - $percent_finished;

?>

<html><head><title>send status</title>

<script language="JavaScript">

<!--



<?php

        if($this->TIMEDELAY == 0)

        {

?>



function MyTimedelay()

{ location.reload(); } // no time delay



<?php

        } else {

?>



function MyReload()

{ location.reload(); }



function MyTimedelay()

{ setTimeout("MyReload()",<?php echo (1000 * $this->TIMEDELAY) ?>); }



<?php

        }

?>



// -->

</script>

</head>

<?php

        if($reload)

        {

            echo "<body onLoad=\"MyTimedelay()\">";

            echo "<h2 align=\"center\">SENDING TO LIST</h2>";

        }

        else

        {

            $query = "UPDATE PREFIX_mail SET mail_finish_dt = '" . date("Y-m-d H:i:s") . "', mail_offset = " . $totrec . " WHERE mail_id = " . $VARS['m'] . ";";

            $SQL->locktable("PREFIX_mail");

            $SQL->q($query);

            $SQL->unlocktable();

            echo "<body>";

            echo "<h2 align=\"center\">FINISHED SENDING</h2>";

        }

?>

<table border="1" width="100%"><tr>

<?php

        if($percent_finished > 100)

        {

?>

<td align="center" bgcolor="#0000ff" width="100%">

<font color="#ffffff"><b>100%</b></font>

</td></tr></table>

</body>

</html>

<?php

        }

        else

        {

?>

<td align="center" bgcolor="#0000ff" width="<?php echo $percent_finished ?>%">

<font color="#ffffff"><b><?php echo $percent_finished ?>%</b></font>

</td><td width="<?php echo $percent_remain ?>%">&nbsp;</td>

</tr></table>

</body>

</html>

<?php

        }

    }



    function HTML_PAUSE()

    { echo "\n<h2 align='center'>SENDING PAUSED</h2>"; }



    function HTML_TOOLBAR($VARS)

    {

?>

<html><head><title>toolbar</title></head>

<body>

<center>

<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=m04&m=<?php echo $VARS['m'] ?>&f_process=1&frame_PAUSE=1" target="SEND">(pause sending)</a>

&nbsp; &nbsp;

<?php



echo "<a href=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&x=m04&m=" . $VARS['m'] . "&f_process=1&frame_SEND=1";

echo "&sendtype=" . $VARS['sendtype'];

if($VARS['replyto'])

{ echo "&replyto=" . urlencode($VARS['replyto']); }

if($VARS['charset'])

{ echo "&charset=" . urlencode($VARS['charset']); }

echo "\" target=\"SEND\">(continue sending)</a>\n";



?>

</center>

</body>

</html>

<?php

    }



    function HTML_ERRORS($errors)

    { echo "\nERROR:<br> $errors <br>"; }



    function SEND_EMAIL($TO,$SUBJECT,$MESSAGE,$REPLYTO,$TYPE,$MAIL,$CHARSET='iso-8859-1')

    {

        $error = "";

        if($REPLYTO)

        { $MAIL->AddReplyTo($REPLYTO); }

        if($TYPE == "text")

        {

            $MAIL->CharSet = $CHARSET;

            $MAIL->ContentType = "text/plain";

        }

        elseif($TYPE == "html")

        {

            $MAIL->CharSet = $CHARSET;

            $MAIL->ContentType = "text/html";

        }

        elseif($TYPE == "multi")

        {
            $MAIL->CharSet = $CHARSET;
            $MAIL->ContentType = "multipart/alternative";
						$pos= strpos($MESSAGE,"#PLAIN-2-HTML-BOUNDARY#");
						$plano = substr($MESSAGE,0,$pos);
						$html = substr($MESSAGE,$pos);
//            $plano= $MAIL->boundary . "\nContent-Type: text/plain; charset=\"" . $MAIL->CharSet . "\"; Content-Transfer-Encoding: " . $MAIL->Encoding . "\n" . $plano;

//          $tempBoundary = $MAIL->boundary . "\nContent-Type: text/html; charset=\"" . $MAIL->CharSet . "\"; Content-Transfer-Encoding: " . $MAIL->Encoding . "\n";

            $MESSAGE = str_replace("#PLAIN-2-HTML-BOUNDARY#","",$html);
//						$MESSAGE = $html;
#           $MESSAGE = $MESSAGE . "\n" . $MAIL->boundary . "\n";
						
						$MAIL->AltBody=$plano; 
        }


				$MAIL->From = "horaciod@alsur.es";
        $MAIL->AddAddress($TO);

        //$MAIL->AddCustomHeader("Error-To: <" . $MAIL->From . ">");

        $MAIL->Subject = $SUBJECT;

        $MAIL->Body = $MESSAGE;



        if (!$MAIL->Send())  $error = "\n<br>Error Sending To: $TO";


				
        $MAIL->ClearAllRecipients();

        $MAIL->ClearCustomHeaders();

        $MAIL->Subject = "";

        $MAIL->Body = "";
        return($error);

    }



    function HTML_FRAMES($VARS)

    {

?>

<html>

<head><title></title></head>

<frameset rows="*,50">



<?php

echo "<frame name=\"SEND\" src=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&m=" . $VARS['m'] . "&x=m04&f_process=1&frame_SEND=1";

echo "&sendtype=" . $VARS['sendtype'];

if($VARS['replyto'])

{ echo "&replyto=" . urlencode($VARS['replyto']); }

if($VARS['charset'])

{ echo "&charset=" . urlencode($VARS['charset']); }

echo "\">\n";



echo "<frame name=\"TOOLBAR\" src=\"ciaoadm.php?u=" . urlencode($VARS['u']) . "&p=" . urlencode($VARS['p']) . "&m=" . $VARS['m'] . "&x=m04&f_process=1&frame_TOOLBAR=1";

echo "&sendtype=" . $VARS['sendtype'];

if($VARS['replyto'])

{ echo "&replyto=" . urlencode($VARS['replyto']); }

if($VARS['charset'])

{ echo "&charset=" . urlencode($VARS['charset']); }

echo "\">\n";



?>



</frameset>

</html>

<?php

    }

}

?>

