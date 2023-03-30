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
# FILE: ciao_s06.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.11.16
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2000.12.23 - BD (Ben Drushell) - Increased import file size to 30kb
# 2001.01.20 - BD - Modifications for customizable verification message
# 2001.01.29 - BD - Modification for handling equals in xml parsed field
# 2001.01.30 - BD - Modification for handling magic equals
# 2001.03.07 - BD - Modification for inability to upload
# 2001.04.05 - BD - Modified to handle extra modules
# 2001.05.22 - BD - Added "return-path" field to email headers sent
# 2001.06.13 - BD - Added duplication verification checking to import
# 2001.06.22 - BD - Added feature so comma is no longer required following the email address that has no extra import data.
# 2001.06.24 - BD - Added Error-To email header for bounce messages on some servers
# 2001.07.05 - BD - Moved email retrieval statement so that import next line bug is fixed.
# 2001.07.18 - BD - added code to remove "\r" from email messages for Outlook compatibility
# 2001.08.05 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#
# 2001.09.08 - BD - added time-delay feature so that mail networks do not get bogged down with a send.
#---------------------------------------------------------

# SHORT DESCRIPTION
# This module handles import of subscribers.
#---------------------------------------------------------

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

        if($VARS['f_process'] == '')
        {
            $T->head($VARS);
            echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">E-MAIL LIST IMPORT</font></h2>";
            $this->HTML_FORM($VARS,$SYS);
            $T->tail($VARS);
        }
        else
        {
            if($VARS['f_btnUPLOAD'] != '' || $VARS['f_btnIMPORT'] != '')
            {
                $T->head($VARS);
                echo "<h2 align=\"center\"><font color=\"" . $T->body_title . "\">IMPORT</font></h2>";
                $this->START_IMPORT($VARS,$SYS);
                $this->HTML_FORM($VARS,$SYS);
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
To facilitate those with existing e-mail lists, this import utility enables
list transfer to Ciao-ELM.  Anti-spam features (that are subscriber friendly) have
been added to deter misuse of this utility.  It is advised that you utilize a
test-import-list before utilizing your actual import-list.
</td></tr></table>
</center>

<center>
<form enctype="multipart/form-data" name="client" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="30000">

<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="o" value="<?php echo (1 * $VARS['o']) ?>">
<input type="hidden" name="f_process" value="1">

<table border="1" bgcolor="<?php echo $T->table_bgcolor ?>" width="90%" align="center">
<tr><td align="center"><big><b><font color="<?php echo $T->table_Text ?>">FILE TO UPLOAD & IMPORT FROM</font></b></big></td></tr>
<tr><td align="center">
<input name="importfile" type="file">
<input type="submit" name="f_btnUPLOAD" value="Upload & Import File">
</td></tr>
</table>
</form>

<br><br>
<b>NOTE: UPLOAD LIMIT OF 30KB</b>
<br><br>

<form name="client" method="post">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="o" value="<?php echo (1 * $VARS['o']) ?>">
<input type="hidden" name="f_process" value="1">

<center>
<table border="1" bgcolor="<?php echo $T->table_bgcolor ?>" width="90%" align="center">
<tr><td align="center"><big><b><font color="<?php echo $T->table_Text ?>">LOCAL FILE TO IMPORT FROM</font></b></big></td></tr>
<tr><td align="center"><font color="<?php echo $T->table_Text ?>">
<input name="importfile" type="text">.dat
<input type="submit" name="f_btnIMPORT" value="Import Local DAT File">
</font></td></tr>
</table>
</form>

<br>

<b>Import file is a comma-delimitered text file with order of fields being:</b>
<br><br>E-mail Address
<?php
        for($i = 1; $i <= $CFG->optSize; $i++)
        { echo ",\nOption" . $i; }
?>

<?php
    }

    function START_IMPORT($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        if($VARS['f_btnIMPORT'])
        { $VARS['importfile'] .= ".dat"; }
        if($import = @file($VARS['importfile']))
        {
            unlink($VARS['importfile']);

            while(list($num,$rec) = each($import))
            {
                $ok2insert = 1;
                $fields = split(",",$rec,($CFG->optSize + 2));
                $rEmail = $T->CiaoEncode(trim($fields[0]));
                $lEmail = $T->CiaoEncode(strtolower(trim($fields[0])));
                list($alias,$domain) = split("@",strtolower(trim($fields[0])));
                $lDomain = $T->CiaoEncode($domain);

                $query = "SELECT * FROM PREFIX_verify WHERE (email_id = '$lEmail' OR email_id = '$rEmail');";
                $SQL->q($query);
                if($SQL->nextrecord())
                { $ok2insert = 0; }

                if($ok2insert)
                {
                    $query = "SELECT * FROM PREFIX_list WHERE (email_id = '$lEmail' OR email_id = '$rEmail');";
                    $SQL->q($query);
                    if($SQL->nextrecord())
                    { $ok2insert = 0; }

                    if($ok2insert)
                    {
                        $verify_nid = $SQL->nid("PREFIX_verify->verify_nid");
                        $query = "INSERT INTO PREFIX_verify VALUES(";
                        $query .= "'$verify_nid','";
                        $query .= $num . substr($T->GenerateID(32),5) . "','" . $lEmail . "','" . $lDomain . "','";
                        $query .= $T->CiaoEncode($num . $T->GenerateID(5)) . "','5000-11-11 11:11:11'";
                        for($i = 1; $i <= $CFG->optSize; $i++)
                        { $query .= ",'" . $T->CiaoEncode($fields[$i]) . "'"; }
                        $query .= ");";
                        $SQL->locktable("PREFIX_verify");
                        $SQL->q($query);
                        $SQL->unlocktable();
                    }
                }
            }

?>
<script language="javascript">
<!--
var win = window.open("ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=s06&f_process=1","","width=300,height=125,scrollbars");
// -->
</script>
<h2 align="center">WARNING: Do not hit reload button on browser or the page will start importing again.<br>
A pop-up window should appear.</h2>
<?php
        }
    }

    function HTML_SEND($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];

# modifications for magic quotes
        $xml = array();
        $xml['DB_magic_quotes_runtime'] = $VARS['DB_magic_quotes_runtime'];
        $xml['DB_magic_quotes_sybase'] = $VARS['DB_magic_quotes_sybase'];
# end modifications

        $tempSQL = new CiaoSQL;
        $tempSQL->clone($SQL);

        $T->PARSE_XML_DOC("modules/template",$xml);
        $VARS['msgImport'] = ereg_replace("\r"," ",$xml['msgImport']);

        $reload=0;
        $errors = "";
        $query = "SELECT * FROM PREFIX_verify WHERE signup_dt = '5000-11-11 11:11:11' " . $SQL->limit(0,$this->BATCH) . ";";
        $SQL->q($query);
        while($SQL->nextrecord())
        {
            $query = "UPDATE PREFIX_verify SET signup_dt = '" . date("Y-m-d H:i:s") . "' WHERE verify_id = '" . $SQL->f('verify_id') . "';";
            $tempSQL->locktable("PREFIX_verify");
            $tempSQL->q($query);
            $tempSQL->unlocktable();
            $errors .= $this->SEND_EMAIL($T->CiaoDecode($SQL->f('email_id')),$SQL->f('verify_id'),$T->CiaoDecode($SQL->f('password')),$SYS,$VARS);
            $reload=1;
        }
?>
<html><head><title>import status</title>
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
        { echo "<body onLoad=\"MyTimedelay()\">"; }
        else
        { echo "<body>"; }
?>

<h2 align="center">
<?php
        if($reload)
        { echo "Import In Progress"; }
        else
        { echo "Import Finished"; }
?>
</h2>

</body>
</html>
<?php
    }

    function HTML_PAUSE()
    { echo "\n<h2 align='center'>IMPORT PAUSED</h2>"; }

    function HTML_TOOLBAR($VARS)
    {
?>
<html><head><title>toolbar</title></head>
<body>
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=s06&f_process=1&frame_PAUSE=1" target="SEND">(pause import)</a>
<a href="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=s06&f_process=1&frame_SEND=1" target="SEND">(continue import)</a>
</body>
</html>
<?php
    }

    function SEND_EMAIL($TO,$ID,$PASSWORD,$SYS,$VARS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $CFG = $SYS['CFG'];
        $MAIL = $SYS['MAIL'];

        $error = "";
        $MESSAGE = $VARS['msgImport'];

        $VERIFYURL = $CFG->url . "ciao.php?v=" . $ID;
        if(! eregi("#VERIFYURL#",$MESSAGE))
        { $MESSAGE .= "\n" . $VERIFYURL; }

        $MESSAGE = eregi_replace("#PASSWORD#",$PASSWORD,$MESSAGE);
        $MESSAGE = eregi_replace("#VERIFYURL#",$VERIFYURL,$MESSAGE);
        $MESSAGE = eregi_replace("#URL#",$CFG->url,$MESSAGE);
        $MESSAGE = eregi_replace("#EMAIL#",$TO,$MESSAGE);
        $MESSAGE = eregi_replace("#REMOTE_IP#",$VARS['REMOTE_ADDR'],$MESSAGE);
        $MESSAGE = eregi_replace("#REMOTE_HOST#",$VARS['REMOTE_HOST'],$MESSAGE);
        $MESSAGE = eregi_replace("#BROWSER#",$VARS['HTTP_USER_AGENT'],$MESSAGE);
        $MESSAGE = ereg_replace("--","=",$MESSAGE);

        $MAIL->AddAddress($TO);
        $MAIL->AddCustomHeader("Error-To: <" . $MAIL->From . ">");
        $MAIL->Subject = "Request Verification";
        $MAIL->Body = "\n" . $MESSAGE . "\n\n";

        $MAIL->Send() or $error = "\n<br>Error Sending To: $TO";

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

<?php
        if($VARS['f_btnSEND'] != '')
        {
?>

<frameset rows="*,50">
<frame name="SEND" src="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=s06&f_process=1&frame_SEND=1">
<frame name="TOOLBAR" src="ciaoadm.php?u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>&x=s06&f_process=1&frame_TOOLBAR=1">
</frameset>

<?php
        }
        elseif($VARS['f_btnCLOSE'] != '')
        {
?>

<script language="JavaScript">
<!--
    self.close();
// -->
</script>

<?php
        }
        else
        {
?>

<body>
<form METHOD="POST" ACTION="ciaoadm.php">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="f_process" value="1">
<big><b>
The imported list has been placed into the "Pending Database".
Do you want Ciao-ELM to send a verification email request to each imported person?
</b></big>
<table border="0"><tr>
<td><input type="submit" name="f_btnSEND" value="OK, Send Notification"></td>
<td><input type="submit" name="f_btnCLOSE"  value="Stop! Do NOT Send"></td>
</tr></table>
</form>
</body>

<?php
        }
?>

</html>

<?php
    }
}
?>
