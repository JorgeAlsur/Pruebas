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
# FILE: ciao_s00.php
# LOCAL VERSION: 1.0.11 for PostgreSQL & MySQL
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.01.19 - Maxim Maletsky - isolated code error; error limited database password to 8 characters
# 2001.01.20 - BD - added customizable quantity of additional subscriber fields
# 2001.01.30 - BD - modified for compatibility with PHP magic_quotes
# 2001.03.14 - BD - modified for get_magic_quotes_gpc/runtime 3.0.6
# 2001.03.18 - BD - modified for capturing shift value for new encode/decode
# 2001.05.31 - BD - added code so that install does not require creation of new tables
# 2001.07.31 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
# 2001.09.22 - BD - corrected an error changing the cat_id field size in _catlist from 3 to 4
#
# 2001.10.16 - BD - replaced "include_once" with "include" for PHP3 compatability
#---------------------------------------------------------


# SHORT DESCRIPTION
# This module is used the first time the program is launched.
# It is the interface for inputing the very first password and user id.
#---------------------------------------------------------
# FORM VARIABLE DEFINITIONS
# f_server - "domain:port" or "localhost"
# f_userid - user identification used to access mysql server
# f_password - password used to access mysql server
# f_password2 - verifies password was entered correctly
# f_dbname - name of the mysql database
# f_dbtableprefix - prefix appended to the begining of table names
# f2_email - email address of 1st user
# f2_password - password user will use to access Ciao EmailList
# f2_password2 - verifies password was entered correctly
# f_process - (true/false) process data request
# x - used to store module id
# u - used to store user id
# p - used to store twice encryt password


class module
{
    var $SIZE = 5; # number of optional data fields (default)

    function module($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

        $T->head($VARS);
?>
<H2 align="center"><font color="<?php echo $T->body_title ?>">
Ciao EmailList Manager
</font></H2>
<p align="center">IMPORTANT:<br>
CONFIGURE DIRECTORY TO GIVE PHP READ/WRITE ACCESS<br>
AND READ HELP FILE
"<a href="ciaohelp.html" target="_blank">ciaohelp.html</a>"
BEFORE PROCEEDING</p>
<br>

<?php
        $VAR_TRANS = array("0"=>0,"off"=>0,""=>0,"1"=>1,"on"=>1);
        if(get_cfg_var("track_vars") == '')
        { $d_track_vars = "uncertain"; }
        else
        { $d_track_vars = get_cfg_var("track_vars"); }
        if(get_cfg_var("magic_quotes_runtime") == '')
        { $d_magic_quotes_runtime = "uncertain"; }
        else
        { $d_magic_quotes_runtime = get_cfg_var("magic_quotes_runtime"); }

        $magic_quotes_sybase = $VARS_TRANS[strtolower(get_cfg_var("magic_quotes_sybase"))];

        if(get_cfg_var("arg_separator") == '')
        { $d_arg_separator = "&"; }
        else
        { $d_arg_separator = get_cfg_var("arg_separator"); }
?>

<center><table border="0" align="center" width="400">
<tr><td>PHP Setting</td><td>Required</td><td>Current</td></tr>
<tr><td>track_vars</td><td>On or 1</td><td><?php echo $d_track_vars ?></td></tr>
<tr><td>magic_quotes_runtime</td><td>Off or 0</td><td><?php echo $d_magic_quotes_runtime ?></td></tr>
<tr><td>arg_separator</td><td>&</td><td><?php echo $d_arg_separator ?></td></tr>
<tr><td colspan=3>(This reflects php.ini and php3.ini settings.  Alterations via .htaccess files are not reflected above.)</td></tr>
</table></center>
<br>

<?php
        if ($VARS['f_process'])
        {
            $magic_quotes_gpc = get_magic_quotes_gpc();
            $magic_quotes_sybase = 0;

#            $testread = fopen("ciaocredits.html","r");
#            fgets($testread,256);
#            fgets($testread,256);
#            fgets($testread,256);
#            $testvalue = trim(fgets($testread,256)); # <meta name='Author' content="Ben Drushell">
#            fclose($testread);
#
#            if(ereg("''",$testvalue))
#            { $magic_quotes_sybase = 1; }
#            if(ereg("''",$VARS['f_magic_quotes_sybase']))
#            { $magic_quotes_sybase = 1; }
#            if(ereg('\\"',$testvalue))
#            { $magic_quotes_runtime = 1; }
#
#            if($magic_quotes_runtime)
#            { $error .= "\n<br>ERROR - The PHP setting \"magic_quotes_runtime\" needs to be turned \"off\"!"; }
## detection process is not working... use get_cfg_var function

## some sql servers do not require these variables
#            if(strlen($VARS['f_host']) <= 0)
#            { $error .= "\n<br>ERROR - Host/Home NOT specified!"; }
#            if(strlen($VARS['f_user']) <= 0)
#            { $error .= "\n<br>ERROR - User ID NOT specified"; }

            if(strlen($VARS['f_database']) <= 0)
            { $error .= "\n<br>ERROR - Database name NOT specified!"; }
            if($VARS['f_password'] != $VARS['f_password2'])
            { $error .= "\n<br>ERROR - Database password mismatch! Please type it again."; }

            if(strlen($VARS['f2_email']) <= 0)
            { $error .= "\n<br>ERROR - No user email address was specified."; }
            if(strlen($VARS['f2_password']) <= 0)
            { $error .= "\n<br>ERROR - No user password was specified."; }
            if($VARS['f2_password'] != $VARS['f2_password2'])
            { $error .= "\n<br>ERROR - User password mismatch! Please type it again."; }

# then include sql modules and create instance
            include("modules/phplib_" . $VARS['f_sql_type'] . ".php");
            include("modules/ciaosql.php");
            $SQL = new CiaoSQL;

# pass variables to new SQL object
            $SQL->tableprefix = $VARS['f_tableprefix'];
            $SQL->Seq_Table = $SQL->tableprefix . "db_sequence";
            $SQL->Host = $VARS['f_host'];
            $SQL->User = $VARS['f_user'];
            $SQL->Password = $VARS['f_password'];
            $SQL->Database = $VARS['f_database'];

# then attempt opening connection to database
            if(strlen($error) <= 0)
            {
                if(! ($SQL->connect()))
                { $error .= "\n<br>ERROR - Could NOT connect to the database you specified!"; }
            }

            if(strlen($error) <= 0)
            {
                if(! ($fw = @fopen("modules/ciaodb.php","w")))
                {
                    $error .= "\n<br>ERROR - Could not write to file \"modules/ciaodb.php\"!";
                    $error .= "\n<br>Please verify that PHP has read/write access for the modules directory.";
                }
            }

            if(strlen($error) <= 0)
            {
                $fupdate = fopen("modules/ciao_1_0_07.txt","w");
                fwrite($fupdate,"Ciao-ELM (EmailList Manager) v1.0.07 (beta) - database updated\n");
                fclose($fupdate);

                fwrite($fw,"\n<" . "?\n");
                fwrite($fw,"class DBmod {\n");
                fwrite($fw," var \$magic_quotes_runtime = \"" . (0 + $magic_quotes_runtime) . "\";\n");
                fwrite($fw," var \$magic_quotes_gpc = \"" . (0 + $magic_quotes_gpc) . "\";\n");
                fwrite($fw," var \$magic_quotes_sybase = \"" . (0 + $magic_quotes_sybase) . "\";\n");
                fwrite($fw," var \$size = \"" . (0 + $VARS['f3_quantity']) . "\";\n");
                fwrite($fw," var \$shift = \"" . (0 + $VARS['f_shift']) . "\";\n");
                fwrite($fw," var \$sql_type = \"" . $VARS['f_sql_type'] . "\";\n");
                fwrite($fw," var \$host = \"" . $T->CiaoEncode($VARS['f_host'],$VARS['f_shift']) . "\";\n");
                fwrite($fw," var \$user = \"" . $T->CiaoEncode($VARS['f_user'],$VARS['f_shift']) . "\";\n");
                fwrite($fw," var \$password = \"" . $T->CiaoEncode($VARS['f_password'],$VARS['f_shift']) . "\";\n");
                fwrite($fw," var \$database = \"" . $T->CiaoEncode($VARS['f_database'],$VARS['f_shift']) . "\";\n");
                fwrite($fw," var \$tableprefix = \"" . $T->CiaoEncode($VARS['f_tableprefix'],$VARS['f_shift']) . "\";\n");
                fwrite($fw,"}\n?" . ">\n\n");
                fclose($fw);

                if(strlen($VARS['f_skipcreate']) > 0)
                { # don't create tables! just link to existing database!
                    $tEmail = $T->CiaoEncode($VARS['f2_email'],$VARS['f_shift']);

                    $mPassword = md5(urlencode($T->CiaoEncode($VARS['f2_password'],$VARS['f_shift'])) . urlencode($tEmail));
                    $query = "SELECT * FROM PREFIX_user WHERE email = '" . $tEmail . "' AND password = '" . $mPassword . "' AND access = 1;";
                    $SQL->q($query);
                    if($SQL->nextrecord())
                    {
                        $tSession = $T->GenerateID(32);
                        $query = "INSERT INTO PREFIX_session VALUES(";
                        $query .= "'" . $tSession . "','" . date("Y-m-d H:i:s") . "',1);";
                        $SQL->q($query);
                    }
                }
                else
                {
                    $query = "CREATE TABLE PREFIXdb_sequence (";
                    $query .= "seq_name CHAR(32),";
                    $query .= "nextid UINT";
                    $query .= ");";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_mail (";
                    $query .= "mail_id UINT,";
                    $query .= "mail_owner_id UINT,";
                    $query .= "mail_owner_email CHAR(200),";
                    $query .= "mail_subject CHAR(200),";
                    $query .= "mail_body MEMO,";
                    $query .= "mail_attachments MEMO,";
                    $query .= "mail_sql_stmt MEMO,";
                    $query .= "mail_errors MEMO,";
                    $query .= "mail_offset UINT,";
                    $query .= "mail_start_dt DATETIME,";
                    $query .= "mail_finish_dt DATETIME,";
                    $query .= "mail_type CHAR(20),";
                    $query .= "mail_charset CHAR(40),";
                    $query .= "mail_replyto CHAR(200));";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_user (";
                    $query .= "user_id UINT,";
                    $query .= "email CHAR(200),";
                    $query .= "password CHAR(32),";
                    $query .= "access CHAR(1)";
                    $query .= ");";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_accesslog (";
                    $query .= "accesslog_id UINT,";
                    $query .= "user_id UINT,";
                    $query .= "accesslog_dt DATETIME,";
                    $query .= "accesslog_ip CHAR(15),";
                    $query .= "accesslog_host CHAR(200),";
                    $query .= "accesslog_message CHAR(200)";
                    $query .= ");";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_session (";
                    $query .= "session_id CHAR(32),";
                    $query .= "user_id UINT,";
                    $query .= "status_dt DATETIME,";
                    $query .= "access CHAR(1)";
                    $query .= ");";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_publicsession (";
                    $query .= "session_id CHAR(32),";
                    $query .= "user_id CHAR(200),";
                    $query .= "status_dt DATETIME";
                    $query .= ");";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_sqlstmt (";
                    $query .= "stmt_id UINT,";
                    $query .= "stmt_name CHAR(200),";
                    $query .= "sql_id CHAR(4),";
                    $query .= "sql_stmt MEMO);";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_sql (";
                    $query .= "sql_nid UINT,";
                    $query .= "sql_id CHAR(4),";
                    $query .= "sql_name CHAR(200));";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_catlist (";
                    $query .= "email_id CHAR(200),";
                    $query .= "cat_id CHAR(4));";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_category (";
                    $query .= "cat_nid UINT,";
                    $query .= "cat_id CHAR(4),";
                    $query .= "cat_name CHAR(80));";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_block (";
                    $query .= "block_id UINT,";
                    $query .= "block_value CHAR(200),";
                    $query .= "block_reason MEMO,";
                    $query .= "block_dt DATETIME);";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_list (";
                    $query .= "email_nid UINT,";
                    $query .= "email_id CHAR(200),";
                    $query .= "domain CHAR(200),";
                    $query .= "password CHAR(200),";
                    $query .= "signup_dt DATETIME";
                    $i = 1;
                    while ($i <= (0 + $VARS['f3_quantity']))
                    { $query .= ", option" . $i . " CHAR(200)"; $i = $i + 1; }
                    $query .= ");";
                    $SQL->translate($query);

                    $query = "CREATE TABLE PREFIX_verify (";
                    $query .= "verify_nid UINT,";
                    $query .= "verify_id CHAR(32),";
                    $query .= "email_id CHAR(200),";
                    $query .= "domain CHAR(200),";
                    $query .= "password CHAR(200),";
                    $query .= "signup_dt DATETIME";
                    $i = 1;
                    while ($i <= (0 + $VARS['f3_quantity']))
                    { $query .= ", option" . $i . " CHAR(200)"; $i = $i + 1; }
                    $query .= ");";
                    $SQL->translate($query);

                    $tEmail = $T->CiaoEncode($VARS['f2_email'],$VARS['f_shift']);

                    $mPassword = md5(urlencode($T->CiaoEncode($VARS['f2_password'],$VARS['f_shift'])) . urlencode($tEmail));
                    $user_id = $SQL->nid("PREFIX_user->user_id");
                    $query = "INSERT INTO PREFIX_user VALUES(";
                    $query .= "'" . $user_id . "','" . $tEmail . "','" . $mPassword . "','1');";
                    $SQL->locktable("PREFIX_user");
                    $SQL->q($query);
                    $SQL->unlocktable();

                    $accesslog_id = $SQL->nid("PREFIX_accesslog->accesslog_id");
                    $query = "INSERT INTO PREFIX_accesslog VALUES(";
                    $query .= "'" . $accesslog_id . "',";
                    $query .= "'" . $user_id . "',";
                    $query .= "'" . date("Y-m-d H:i:s") . "',";
                    $query .= "'" . getenv('REMOTE_ADDR') . "',";
                    $query .= "'" . $T->CiaoEncode(gethostbyaddr(getenv('REMOTE_ADDR')),$VARS['f_shift']) . "',";
                    $query .= "'" . $T->CiaoEncode("LOGIN OK",$VARS['f_shift']) . "'";
                    $query .= ");";
                    $SQL->locktable("PREFIX_accesslog");
                    $SQL->q($query);
                    $SQL->unlocktable();

                    $tSession = $T->GenerateID(32);
                    $query = "INSERT INTO PREFIX_session VALUES(";
                    $query .= "'" . $tSession . "','" . $user_id . "','" . date("Y-m-d H:i:s") . "',1);";
                    $SQL->locktable("PREFIX_session");
                    $SQL->q($query);
                    $SQL->unlocktable();

                    $cat_id = $SQL->nid("PREFIX_category->cat_id");
                    $query = "INSERT INTO PREFIX_category VALUES('" . $cat_id . "','ALL','All Subscribers');";
                    $SQL->locktable("PREFIX_category");
                    $SQL->q($query);
                    $SQL->unlocktable();
                }

                $this->HTML_SUCCESS($tEmail,$tSession,$magic_quotes_runtime,$magic_quotes_gpc,$magic_quotes_sybase);
            }
            else
            { $this->HTML_SETUP($error,$VARS,$SYS); }
        }
        else
        { $this->HTML_SETUP("",$VARS,$SYS); }
        $T->tail($VARS);
    }

    function HTML_SETUP($ERROR,$VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

?>
<center>

<form method="post" action="ciaoadm.php">
<input type="hidden" name="f_process" value="1">

<!-- test for magic_quotes -->
<input type="hidden" name="f_magic_quotes_gpc" value='"'>
<input type="hidden" name="f_magic_quotes_sybase" value="'">
<!-- end test -->

<?php
        if(file_exists("modules/ciaodb.php"))
        { $skipcreatechecked = "CHECKED"; }
?>

<table width="400" align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>"><input type="checkbox" name="f_skipcreate" value="1" <?php echo $skipcreatechecked ?>>Click here if you do NOT want to create new tables. This would be advantageous when linking to existing Ciao EmailList Manager database tables.</font></td></tr>
</table>

<H2 align="center"><font color="<?php echo $T->body_title ?>">
SQL DATABASE INFORMATION
</font></H2>

<?php
        if(strlen($ERROR) > 0)
        {
?>
<table align="center" border="0" bgcolor=""><tr><td align="left"><big><big><b>
<?php echo $ERROR ?>
</b></big></big></td></tr></table>
<br><br>
<?php
        }
?>

Requires access to an SQL server.<br>
If you do not know the following information,<br>
contact your website administrator or provider.<br>

<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">Server Type:</font></td>
<td><select name="f_sql_type">
<option value="mysql" SELECTED>MySQL (3.23.xx or higher)
<option value="postgres">PostgreSQL (7.0.2 or higher)
</select></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Server Location:</font></td>
    <td><input type="text" name="f_host" value="<?php echo $VARS['f_host'] ?>"></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">User ID:</font></td>
    <td><input type="text" name="f_user" value="<?php echo $VARS['f_user'] ?>"></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Password:</font></td>
    <td><input type="password" name="f_password" value="<?php echo $VARS['f_password'] ?>"></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Password Verification:</font></td>
    <td><input type="password" name="f_password2" value="<?php echo $VARS['f_password2'] ?>"></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Database Name:</font></td>
    <td><input type="text" name="f_database" value="<?php echo $VARS['f_database'] ?>"></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Data Encode:</font></td>
    <td><input type="text" name="f_shift" value="<?php echo (0 + $VARS['f_shift']) ?>"><br>
        <font color="<?php echo $T->table_Text ?>">("Data Encode" is an optional safety feature to protect your data.<br>
        Use the value "1" to disable this feature and save as plain text.)</font></td></tr>
<?php
    if (! $VARS['f_tableprefix'])
    { $VARS['f_tableprefix'] = "elm"; }
?>
<tr><td><font color="<?php echo $T->table_Text ?>">Database Table Prefix:</font></td><td><input type="text" name="f_tableprefix" value="<?php echo $VARS['f_tableprefix'] ?>"></td></tr>
</table>
<br><br><br>
<H2 align="center"><font color="<?php echo $T->body_title ?>">
USER INFORMATION
</font></H2>

This creates an administrative user for accessing CIAO.<br>
Additional general or administrative users can be added later.
<br><br>

<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">User Email:</font></td><td><input type="text" name="f2_email" value="<?php echo $VARS['f2_email'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password:</font></td><td><input type="password" name="f2_password" value="<?php echo $VARS['f2_password'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password Verification:</font></td><td><input type="password" name="f2_password2" value="<?php echo $VARS['f2_password2'] ?>"></td></tr>
</table>

<br><br><br>
<H2 align="center"><font color="<?php echo $T->body_title ?>">
ADDITIONAL SUBSCRIBER DATA
</font></H2>

<?php
    if (! $VARS['f3_quantity'])
    { $VARS['f3_quantity'] = $this->SIZE; }
?>

<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">Quantity of additional subscriber fields:</font></td><td><input type="text" name="f3_quantity" value="<?php echo (0 + $VARS['f3_quantity']) ?>"></td></tr>
</table>
<br><br>

<p align="center"><center>
<table align="center" border="1" bgcolor="#ffffff" cellpadding="15" cellspacing="0" width="90%"><tr><td>
Ciao-ELM (EmailList Manager) - a customizable mass e-mail program that is administrator/subscriber friendly.<br>
Copyright (C) 2000, 2001 Ben Drushell
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
<a href="gpl.txt" target="_blank">GNU General Public License</a>
for more details.
<br><br>
Some modules and classes that are distributed with Ciao-ELM are licensed under the terms of the Lesser General Public License.
<br><br>
Copies of the GNU General Public License and GNU Lesser General Public License are included with this program.
They are also available for download at: http://www.fsf.org/ and http://www.technobreeze.com/license/
</td></tr></table>
</center></p>

<br><br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><input type="submit" value="SETUP/AGREE TO TERMS"></td></tr>
</table>
</form>
</center>
<?php
    }

    function HTML_SUCCESS($u,$p,$magic_quotes_runtime,$magic_quotes_gpc,$magic_quotes_sybase)
    {
?>
<H2 align="center">INITIAL SETUP SUCCESSFUL</H2>

<center>
<table width="400" border="0" align="center"><tr><td>
    PROBING FOR USE OF "magic_quotes_" PHP SERVER SETTINGS...<br><br>
<b>
    "magic_quotes_gpc": <?php  if($magic_quotes_gpc){ echo "ON"; }else{ echo "OFF"; } ?><br>
    "magic_quotes_sybase": <?php  if($magic_quotes_sybase){ echo "ON"; }else{ echo "OFF"; } ?>
</b>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
"magic_quotes_runtime" needs to be turned "Off" for Ciao-ELM to function properly.
"magic_quotes_gpc" and "magic_quotes_sybase" are compensated for in Ciao-ELM software.
If "magic_quotes_sybase" is detected inacurrately or the setting changes,
you will need to edit the file "modules/ciaodb.php" and alter the setting
for "$magic_quotes_sybase".
</td></tr></table>
<br><br>
<big><b><a href="ciaoadm.php?x=s01&u=<?php echo urlencode($u) ?>&p=<?php echo urlencode($p) ?>">Continue With Setup</a></b></big>
</center>

<?php
    }
}
?>
