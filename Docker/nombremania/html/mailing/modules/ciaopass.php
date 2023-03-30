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
# FILE: ciaopass.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.06.13 - BD (Ben Drushell) - depricated crypt in favor of md5 function
#
# 2001.08.05 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#---------------------------------------------------------
?>

<?php
class CiaoPass
{
    function Verify(&$VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];

        $SQL->unlocktables();

        $query = "DELETE FROM PREFIX_session WHERE status_dt < '" . date("Y-m-d H:i:s",(time()-7200)) . "';";
        $SQL->locktable("PREFIX_session","write");
        $SQL->q($query);
        $SQL->unlocktable();

        $affected_rows = $SQL->affected_rows();
        if($affected_rows > 0)
        {
            $accesslog_id = $SQL->nid("PREFIX_accesslog->accesslog_id");
            $query = "INSERT INTO PREFIX_accesslog VALUES(";
            $query .= "'" . $accesslog_id . "',";
            $query .= "'0',";
            $query .= "'" . date("Y-m-d H:i:s") . "',";
            $query .= "'',";
            $query .= "'',";
            $query .= "'" . $T->CiaoEncode($affected_rows . " SESSIONS TIMED-OUT") . "'";
            $query .= ");";
            $SQL->locktable("PREFIX_accesslog","write");
            $SQL->q($query);
            $SQL->unlocktable();
        }

        $query = "SELECT * FROM PREFIX_session WHERE session_id = '" . $VARS['p'] . "';";
        $SQL->q($query);
        $SQL->nextrecord();
        $sRecord = $SQL->Record;

        if($VARS['logout'] != '' && $sRecord['user_id'] != '')
        {
            $accesslog_id = $SQL->nid("PREFIX_accesslog->accesslog_id");
            $query = "INSERT INTO PREFIX_accesslog VALUES(";
            $query .= "'" . $accesslog_id . "',";
            $query .= "'" . $sRecord['user_id'] . "',";
            $query .= "'" . date("Y-m-d H:i:s") . "',";
            $query .= "'" . getenv('REMOTE_ADDR') . "',";
            $query .= "'" . $T->CiaoEncode(gethostbyaddr(getenv('REMOTE_ADDR'))) . "',";
            $query .= "'" . $T->CiaoEncode("LOGOUT OK") . "'";
            $query .= ");";
            $SQL->locktable("PREFIX_accesslog","write");
            $SQL->q($query);

            $query = "DELETE FROM PREFIX_accesslog WHERE accesslog_dt < '" . date("Y-m-d H:i:s",(time()-2592000)) . "';";
            $SQL->q($query);

            $SQL->unlocktable();

            $query = "DELETE FROM PREFIX_session WHERE session_id = '" . $VARS['p'] . "';";
            $SQL->locktable("PREFIX_session","write");
            $SQL->q($query);
            $SQL->unlocktable();
            $VARS['p'] = '';

            echo "\n<h2 align=\"center\">" . $T->CiaoDecode($VARS['u']) . " has successfully logged out.</h2>\n";
        }

        if(! file_exists("gpl.txt"))
        { $SQL->unlocktables(); die(""); }

        if ($VARS['f_user'] != '')
        {
            $VARS['u'] = $T->CiaoEncode($VARS['f_user']);

            $mPassword = md5(urlencode($T->CiaoEncode($VARS['f_password'])) . urlencode($VARS['u']));

            if(strlen($mPassword) > 32) // just in case
            { $mPassword = substr($mPassword,0,32); }

            $query = "SELECT * FROM PREFIX_user WHERE email = '" . $VARS['u'] . "';";
            $SQL->q($query);
            if($SQL->nextrecord())
            {
                if($SQL->f('password') == $mPassword)
                {
                    $user_id = $SQL->f('user_id');

                    $accesslog_id = $SQL->nid("PREFIX_accesslog->accesslog_id");
                    $query = "INSERT INTO PREFIX_accesslog VALUES(";
                    $query .= "'" . $accesslog_id . "',";
                    $query .= "'" . $user_id . "',";
                    $query .= "'" . date("Y-m-d H:i:s") . "',";
                    $query .= "'" . getenv('REMOTE_ADDR') . "',";
                    $query .= "'" . $T->CiaoEncode(gethostbyaddr(getenv('REMOTE_ADDR')),$VARS['f_shift']) . "',";
                    $query .= "'" . $T->CiaoEncode("LOGIN OK") . "'";
                    $query .= ");";
                    $SQL->locktable("PREFIX_accesslog","write");
                    $SQL->q($query);
                    $SQL->unlocktable();

                    $VARS['p'] = $T->GenerateID(32);
                    $VARS['s'] = trim($SQL->f('access'));
                    $query = "INSERT INTO PREFIX_session VALUES('" . $VARS['p'] .  "','" . $user_id . "','" . date("Y-m-d H:i:s") . "'," . $SQL->f('access') . ");";
                    $SQL->locktable("PREFIX_session","write");
                    $SQL->q($query);
                    $SQL->unlocktable();
                }
                else
                {
                    $accesslog_id = $SQL->nid("PREFIX_accesslog->accesslog_id");
                    $query = "INSERT INTO PREFIX_accesslog VALUES(";
                    $query .= "'" . $accesslog_id . "',";
                    $query .= "'" . $SQL->f('user_id') . "',";
                    $query .= "'" . date("Y-m-d H:i:s") . "',";
                    $query .= "'" . getenv('REMOTE_ADDR') . "',";
                    $query .= "'" . $T->CiaoEncode(gethostbyaddr(getenv('REMOTE_ADDR')),$VARS['f_shift']) . "',";
                    $query .= "'" . $T->CiaoEncode("LOGIN ERROR: WRONG PASSWORD") . "'";
                    $query .= ");";
                    $SQL->locktable("PREFIX_accesslog","write");
                    $SQL->q($query);
                    $SQL->unlocktable();
                }
            }
            else
            {
                $accesslog_id = $SQL->nid("PREFIX_accesslog->accesslog_id");
                $query = "INSERT INTO PREFIX_accesslog VALUES(";
                $query .= "'" . $accesslog_id . "',";
                $query .= "'0',";
                $query .= "'" . date("Y-m-d H:i:s") . "',";
                $query .= "'" . getenv('REMOTE_ADDR') . "',";
                $query .= "'" . $T->CiaoEncode(gethostbyaddr(getenv('REMOTE_ADDR')),$VARS['f_shift']) . "',";
                $query .= "'" . $T->CiaoEncode("LOGIN ERROR: NO SUCH USER") . "'";
                $query .= ");";
                $SQL->locktable("PREFIX_accesslog","write");
                $SQL->q($query);
                $SQL->unlocktable();
            }
        }
        if(! file_exists("ciaocredits.html"))
        { $SQL->unlocktables(); die(""); }
        if ($VARS['p'] == '')
        {
            $this->HTML_PASSWORD();
            $SQL->unlocktables();
            die("");
        }
        else
        {
            $query = "SELECT * FROM PREFIX_session WHERE session_id = '" . $VARS['p'] . "';";
            $SQL->q($query);
            if($SQL->nextrecord())
            {
                $VARS['s'] = $SQL->f('access');
                $query = "UPDATE PREFIX_session SET status_dt = '" . date("Y-m-d H:i:s") . "' WHERE session_id = '" . $VARS['p'] . "';";
                $SQL->locktable("PREFIX_session","write");
                $SQL->q($query);
                $SQL->unlocktable();
            }
            else
            { $SQL->unlocktables(); $this->HTML_PASSWORD(); die(""); }
        }
        $SQL->unlocktables();
    }

    function HTML_PASSWORD()
    {
?>
<html>
<head><title>Ciao-ELM (EmailList Manager) (C) Ben Drushell</title></head>
<body bgcolor="#ffffcc" background="background.gif" text="#000000" link="#000099" alink="#000099" vlink="#000099">
<H1 align="center">CIAO-ELM LOGIN</H1>
<center>
<form method="post" action="ciaoadm.php">
<table align="center" border="0">
<tr><td>User ID:</td><td><input type="text" name="f_user"></td></tr>
<tr><td>Password:</td><td><input type="password" name="f_password"></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="LOGIN"></td></tr>
</table>
</form>
</center>
<br>
<p align="center"><center>
<table align="center" border="1" bgcolor="#ffffff" cellpadding="15" cellspacing="0" width="90%"><tr><td>
<font color="#000000">
Ciao-ELM (EmailList Manager) - a customizable mass e-mail program that is administrator/subscriber friendly.<br>
Copyright (C) 2000, 2001 Benjamin Drushell
<br><br>
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License.
<br><br>
This program is distributed in the hope that it will be useful.
There is NO WARRANTY.  NO implied warranty of MERCHANTABILITY.
NO implied warranty of FITNESS FOR A PARTICULAR PURPOSE.
The entire risk is with you.
See the
<a href="gpl.txt" target="_blank" style="color:#000000"><font color="#000000">GNU General Public License</font></a>
for more details.
<br><br>
Some modules and classes that are distributed with Ciao-ELM are licensed under the terms of the Lesser General Public License.
<br><br>
Copies of the GNU General Public License and GNU Lesser General Public License are included with this program.
They are also available for download at: http://www.fsf.org/ and http://www.technobreeze.com/license/
</font>
</td></tr></table>
</center></p>

</BODY>
</HTML>
<?php
    }
}
?>
