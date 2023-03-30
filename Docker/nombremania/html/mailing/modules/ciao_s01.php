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
# FILE: ciao_s01.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2000.12.23 - BD - Added comment about custom messages
# 2001.01.20 - BD - Altered code to work with new custom quantity of optional data fields
# 2001.03.21 - BD - Added default value capabilities to optional data fields
# 2001.04.30 - BD - Made password optional.
# 2001.06.16 - BD - Added multiple field type capabilities
# 2001.08.04 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
# 2001.09.02 - BD - changed smtp port to 25... 110 is pop3 port
#
# 2001.09.09 - BD - added BatchSize and TimeDelay fields for customization of sending emails
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module handles sending e-mail address information.
#---------------------------------------------------------
# FORM VARIABLE DEFINITIONS
# f_email - email address to be used as "FROM" for all sent messages
# f_url - url to directory where Ciao EmailList Manager will reside
#         Format: "http://somedomain/optionaldirectory/"
# f_option1 to f_option16 - used to store requirement status of fields
#         Values: ( n = "not used" | r = "required" | o = "optional" )
# f_option1n to f_option16n - used to store output name of field
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
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];
        $DB = $SYS['DB'];

        $T->head($VARS);
        if ($VARS['f_process'])
        {
            $errors = "";
            if ($VARS['f_email'] == '')
            { $errors .= "\n<br>No from email address was entered."; }
            if ($VARS['f_url'] == '')
            { $errors .= "\n<br>No URL was entered."; }
            if ($errors == '')
            {
                $fw = fopen("modules/ciaocfg.php","w");
                fwrite($fw,"\n<" . "?\n");
                fwrite($fw,"class CFGmod {\n");
                fwrite($fw," var \$email = \"" . $VARS['f_email'] . "\";\n");

                if(substr($VARS['f_url'],strlen($VARS['f_url'])-1,1) != "/")
                { $VARS['f_url'] .= "/"; }
                fwrite($fw," var \$url = \"" . $VARS['f_url'] . "\";\n");

                if($VARS['f_notifyonrequest'] == "CHECKED")
                { fwrite($fw," var \$notifyonrequest = \"1\";\n"); }
                else
                { fwrite($fw," var \$notifyonrequest = \"0\";\n"); }
                if($VARS['f_notifyonverify'] == "CHECKED")
                { fwrite($fw," var \$notifyonverify = \"1\";\n"); }
                else
                { fwrite($fw," var \$notifyonverify = \"0\";\n"); }
                if($VARS['f_notifyonedit'] == "CHECKED")
                { fwrite($fw," var \$notifyonedit = \"1\";\n"); }
                else
                { fwrite($fw," var \$notifyonedit = \"0\";\n"); }
                if($VARS['f_notifyondelete'] == "CHECKED")
                { fwrite($fw," var \$notifyondelete = \"1\";\n"); }
                else
                { fwrite($fw," var \$notifyondelete = \"0\";\n"); }
                if($VARS['f_usepassword'] == "CHECKED")
                { fwrite($fw," var \$usepassword = \"1\";\n"); }
                else
                { fwrite($fw," var \$usepassword = \"0\";\n"); }

                fwrite($fw," var \$BatchSize = \"" . (0 + $VARS['f_batchsize']) . "\";\n");
                fwrite($fw," var \$TimeDelay = \"" . (0 + $VARS['f_timedelay']) . "\";\n");

                fwrite($fw," var \$mail_fromname = \"" . $T->CiaoEncode($VARS['f_mail_fromname']) . "\";\n");
                fwrite($fw," var \$mail_mailer = \"" . $T->CiaoEncode($VARS['f_mail_mailer']) . "\";\n");
                fwrite($fw," var \$mail_sendmail = \"" . $T->CiaoEncode($VARS['f_mail_sendmail']) . "\";\n");
                fwrite($fw," var \$mail_host = \"" . $T->CiaoEncode($VARS['f_mail_host']) . "\";\n");
                fwrite($fw," var \$mail_port = \"" . $T->CiaoEncode($VARS['f_mail_port']) . "\";\n");
                fwrite($fw," var \$mail_timeout = \"" . $T->CiaoEncode($VARS['f_mail_timeout']) . "\";\n");
                fwrite($fw," var \$mail_helo = \"" . $T->CiaoEncode($VARS['f_mail_helo']) . "\";\n");

                fwrite($fw," var \$optSize = \"" . (0 + $DB->size) . "\";\n");
                $optReq = " var \$optReq = array(";
                $optName = " var \$optName = array(";
                $optDefault = " var \$optDefault = array(";
                $optType = " var \$optType = array(";
                $optReq .= "\"1\"=>\"" . $VARS['f_option1'] . "\"";
                $optName .= "\"1\"=>\"" . $VARS['f_option1n'] . "\"";
                $optDefault .= "\"1\"=>\"" . $VARS['f_option1d'] . "\"";
                $optType .= "\"1\"=>\"" . $VARS['f_option1t'] . "\"";
                $i = 2;
                while($i <= (0 + $DB->size))
                {
                    $optReq .= ",\"" . $i . "\"=>\"" . $VARS['f_option' . $i] . "\"";
                    $optName .= ",\"" . $i . "\"=>\"" . addslashes($VARS['f_option' . $i . 'n']) . "\"";
                    $optDefault .= ",\"" . $i . "\"=>\"" . addslashes($VARS['f_option' . $i . 'd']) . "\"";
                    $optType .= ",\"" . $i . "\"=>\"" . $VARS['f_option' . $i . 't'] . "\"";
                    $i = $i + 1;
                }
                fwrite($fw,$optReq . "  );\n");
                fwrite($fw,$optName . "  );\n");
                fwrite($fw,$optDefault . " );\n");
                fwrite($fw,$optType . " );\n");
                fwrite($fw,"}\n?" . ">\n\n");
                fclose($fw);
                $this->HTML_SUCCESS($VARS);
            } else {
                $this->HTML_ERRORS($errors);
                $this->HTML_FORM($VARS,$SYS);
            }
        } else {
            $this->HTML_FORM($VARS,$SYS);
        }
        $T->tail($VARS);
    }

    function HTML_SUCCESS($VARS)
    {
?>
<H2 align="center">"EMAIL SETUP" SUCCESSFUL</H2>
<big><b><center><a href="ciaoadm.php?x=s02&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>">Click Here To Continue With Setup</a></center></b></big>
<?php
    }

    function HTML_ERRORS($errors)
    {
?>
<H2 align="center">"EMAIL SETUP" ERRORS</H2>
<?php echo $errors ?><br>
<?php
    }

    function HTML_FORM($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];
        $DB = $SYS['DB'];

        if(gettype($CFG) == 'object')
        {
            if($VARS['f_email'] == '')
            { $VARS['f_email'] = $CFG->email; }
            if($VARS['f_url'] == '')
            { $VARS['f_url'] = $CFG->url; }

            if($VARS['f_batchsize'] == '')
            { $VARS['f_batchsize'] = $CFG->BatchSize; }
            if($VARS['f_timedelay'] == '')
            { $VARS['f_timedelay'] = $CFG->TimeDelay; }

            if($VARS['f_mail_fromname'] == '')
            { $VARS['f_mail_fromname'] = $T->CiaoDecode($CFG->mail_fromname); }
            if($VARS['f_mail_mailer'] == '')
            { $VARS['f_mail_mailer'] = $T->CiaoDecode($CFG->mail_mailer); }
            if($VARS['f_mail_sendmail'] == '')
            { $VARS['f_mail_sendmail'] = $T->CiaoDecode($CFG->mail_sendmail); }
            if($VARS['f_mail_host'] == '')
            { $VARS['f_mail_host'] = $T->CiaoDecode($CFG->mail_host); }
            if($VARS['f_mail_port'] == '')
            { $VARS['f_mail_port'] = $T->CiaoDecode($CFG->mail_port); }
            if($VARS['f_mail_timeout'] == '')
            { $VARS['f_mail_timeout'] = $T->CiaoDecode($CFG->mail_timeout); }
            if($VARS['f_mail_helo'] == '')
            { $VARS['f_mail_helo'] = $T->CiaoDecode($CFG->mail_helo); }

            if($CFG->notifyonrequest)
            { $VARS['f_notifyonrequest'] = "CHECKED"; }
            if($CFG->notifyonverify)
            { $VARS['f_notifyonverify'] = "CHECKED"; }
            if($CFG->notifyonedit)
            { $VARS['f_notifyonedit'] = "CHECKED"; }
            if($CFG->notifyondelete)
            { $VARS['f_notifyondelete'] = "CHECKED"; }
            if($CFG->usepassword)
            { $VARS['f_usepassword'] = "CHECKED"; }
        }
?>
<form action="ciaoadm.php" method="post">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<center>
<h2 align="center"><font color="<?php echo $T->body_title ?>">CONFIGURATION</font></h2>
<br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0" width="450">
<tr><td><font color="<?php echo $T->table_Text ?>">URL To Directory:</font></td><td><input type="text" name="f_url" value="<?php echo $VARS['f_url'] ?>"></td></tr>
<tr><td>&nbsp;</td><td><font color="<?php echo $T->table_Text ?>">Example: http://yourdomain/directory/</font></td></tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input type="checkbox" name="f_notifyonrequest" value="CHECKED" <?php echo $VARS['f_notifyonrequest'] ?>>Notify me when request to join e-mail list is made.</td></tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input type="checkbox" name="f_notifyonverify" value="CHECKED" <?php echo $VARS['f_notifyonverify'] ?>>Notify me when e-mail address is verified and added to the list.</td></tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input type="checkbox" name="f_notifyonedit" value="CHECKED" <?php echo $VARS['f_notifyonedit'] ?>>Notify me when subscriber edits data.</td></tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input type="checkbox" name="f_notifyondelete" value="CHECKED" <?php echo $VARS['f_notifyondelete'] ?>>Notify me when person unsubscribes from list.</td></tr>

<tr><td><font color="<?php echo $T->table_Text ?>">From E-mail Address:</font></td><td><input type="text" name="f_email" value="<?php echo $VARS['f_email'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">From Name:</font></td><td><input type="text" name="f_mail_fromname" value="<?php echo $VARS['f_mail_fromname'] ?>"></td></tr>
<?php
        if($VARS['f_mail_mailer'] == "sendmail" || $VARS['f_mail_mailer'] == "")
        { $SENDMAIL = "CHECKED"; }
?>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input name="f_mail_mailer" type="radio" value="sendmail" <?php echo $SENDMAIL ?>>Use Sendmail To Send Email (or mail program with a sendmail interface)</font></td></tr>
<tr><td> &nbsp; &nbsp; <font color="<?php echo $T->table_Text ?>">Sendmail Location: </font></td><td><input type="text" name="f_mail_sendmail" value="<?php echo $VARS['f_mail_sendmail'] ?>"></td></tr>
<?php
        if($VARS['f_mail_mailer'] == "smtp")
        { $SMTP = "CHECKED"; }
?>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input name="f_mail_mailer" type="radio" value="smtp" <?php echo $SMTP ?>>Use SMTP To Send Email (suggested for win32 systems)</font></td></tr>
<tr><td> &nbsp; &nbsp; <font color="<?php echo $T->table_Text ?>">SMTP HOST: </font></td><td><input type="text" name="f_mail_host" value="<?php echo $VARS['f_mail_host'] ?>"></td></tr>
<?php
        if($VARS['f_mail_port'] == "")
        { $VARS['f_mail_port'] = "25"; }
?>
<tr><td> &nbsp; &nbsp; <font color="<?php echo $T->table_Text ?>">SMTP PORT: </font></td><td><input type="text" name="f_mail_port" value="<?php echo $VARS['f_mail_port'] ?>"></td></tr>
<?php
        if($VARS['f_mail_timeout'] == "")
        { $VARS['f_mail_timeout'] = "10"; }
?>
<tr><td> &nbsp; &nbsp; <font color="<?php echo $T->table_Text ?>">SMTP TIMEOUT (in seconds): </font></td><td><input type="text" name="f_mail_timeout" value="<?php echo $VARS['f_mail_timeout'] ?>"></td></tr>
<tr><td> &nbsp; &nbsp; <font color="<?php echo $T->table_Text ?>">ACCOUNT NAME: </font></td><td><input type="text" name="f_mail_helo" value="<?php echo $VARS['f_mail_helo'] ?>"></td></tr>
<?php
        if($VARS['f_mail_mailer'] == "mail")
        { $PHPMAIL = "CHECKED"; }
?>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input name="f_mail_mailer" type="radio" value="mail" <?php echo $PHPMAIL ?>>Use PHP Mail Function To Send Email</font></td></tr>

<?php
        if($VARS['f_batchsize'] == "")
        { $VARS['f_batchsize'] = "20"; }
        if($VARS['f_timedelay'] == "")
        { $VARS['f_timedelay'] = "0"; }
?>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>">List Sending Configurations</font></td></tr>
<tr><td> &nbsp; &nbsp; <font color="<?php echo $T->table_Text ?>">BATCH SIZE: </font></td><td><input type="text" name="f_batchsize" value="<?php echo $VARS['f_batchsize'] ?>"></td></tr>
<tr><td>&nbsp;</td><td><font color="<?php echo $T->table_Text ?>">(Batch size is the quantity of emails Ciao-ELM will process and send at a time.  Too large of a batch size can cause PHP time-outs to occur during a send.  If a timeout does occur, reduce this value to a smaller number.)</font></td></tr>
<tr><td> &nbsp; &nbsp; <font color="<?php echo $T->table_Text ?>">TIME DELAY (in seconds): </font></td><td><input type="text" name="f_timedelay" value="<?php echo $VARS['f_timedelay'] ?>"></td></tr>
<tr><td>&nbsp;</td><td><font color="<?php echo $T->table_Text ?>">(This is a time delay that occurs between batches. It reduces resource demand of a network or server by spreading out the sending process over a larger period of time.)</font></td></tr>
</table>

<br><br><br>
<h2 align="center"><font color="<?php echo $T->body_title ?>">OPTIONAL SUBSCRIBER DATA</font></h2>

<table align="center" width="50%" border="0"><tr><td>
Automatically, the program requests subscriber e-mail address.
Use below form for requesting additional information
(such as: name, city, whatever) from subscribers.
This information is used to create custom mailing
lists. It can also be used for creating custom messages
by inserting tags like #option1# into your message.
If option1 was defined as "Name", the tag #name# would
also work.
In the sending process, the tag will be replaced with the subscriber data.
</td></tr>
</table>
<br>
<br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td colspan="5"><font color="<?php echo $T->table_Text ?>"><input type="checkbox" name="f_usepassword" value="CHECKED" <?php echo $VARS['f_usepassword'] ?>>Use the subscriber password option.</td></tr>
<tr>
    <td> &nbsp; </td>
    <td> &nbsp; </td>
    <td><font color="<?php echo $T->table_Text ?>">Field Type</font></td>
    <td><font color="<?php echo $T->table_Text ?>">Field Name</font></td>
    <td><font color="<?php echo $T->table_Text ?>">Default Value</font></td>
</tr>

<?php
        $i = 1;
        while($i <= $DB->size)
        {
            if(gettype($CFG) == 'object')
            {
                if($VARS['f_option' . $i] == '')
                { $VARS['f_option' . $i] = $CFG->optReq[$i]; }
                if($VARS['f_option' . $i . 'n'] == '')
                { $VARS['f_option' . $i . 'n'] = $CFG->optName[$i]; }
                if($VARS['f_option' . $i . 'd'] == '')
                { $VARS['f_option' . $i . 'd'] = $CFG->optDefault[$i]; }
                if($VARS['f_option' . $i . 't'] == '')
                { $VARS['f_option' . $i . 't'] = $CFG->optType[$i]; }
            }

            $optR = "";
            $optO = "";
            $optN = "";
            if($VARS['f_option' . $i] == 'r')
            { $optR = "SELECTED"; }
            elseif($VARS['f_option' . $i] == 'o')
            { $optO = "SELECTED"; }
            else
            { $optN = "SELECTED"; }

            $optText = "";
            $optSelect = "";
            $optRadio = "";
            $optCheckbox = "";
            if($VARS['f_option' . $i . 't'] == 'select')
            { $optSelect = "SELECTED"; }
            elseif($VARS['f_option' . $i . 't'] == 'radio')
            { $optRadio = "SELECTED"; }
            elseif($VARS['f_option' . $i . 't'] == 'checkbox')
            { $optCheckbox = "SELECTED"; }
            else
            { $optText = "SELECTED"; }

            if($i % 2)
            {
                $color = $T->table_row;
                $text = $T->table_row_text;
            }
            else
            {
                $color = $T->table_altrow;
                $text = $T->table_altrow_text;
            }
?>
<tr><td bgcolor="<?php echo $color ?>" align="right"><font color="<?php echo $text ?>"><?php echo $i ?></font>
&nbsp;&nbsp;</td><td bgcolor="<?php echo $color ?>"><font color="<?php echo $text ?>">

<select name="f_option<?php echo $i ?>">
<option value="r" <?php echo $optR ?>>required
<option value="o" <?php echo $optO ?>>optional
<option value="n" <?php echo $optN ?>>not used
</select>

</font>
</td><td bgcolor="<?php echo $color ?>">
<font color="<?php echo $text ?>">

<select name="f_option<?php echo $i ?>t">
<option value="text" <?php echo $optText ?>>text field
<option value="select" <?php echo $optSelect ?>>select field
<option value="radio" <?php echo $optRadio ?>>radio field
<option value="checkbox" <?php echo $optCheckbox ?>>checkbox field
</select>

</font>
</td><td bgcolor="<?php echo $color ?>">
<input type="text" name="f_option<?php echo $i ?>n" value="<?php echo $VARS['f_option' . $i . 'n'] ?>">
</td><td bgcolor="<?php echo $color ?>">
<input type="text" name="f_option<?php echo $i ?>d" value="<?php echo $VARS['f_option' . $i . 'd'] ?>">
</td></tr>
<?php
            $i = $i + 1;
        }
?>
</table>
<br>

<p align="center">
<b><big>
Use semicolons to seperate default options for "select" and "radio" type fields.
<br>The option selected by default should be listed first.
</big></b>
</p>

<br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><input type="submit" value="SUBMIT DATA"></td></tr>
</table>
</form>
</center>
<?php
    }
}
?>
