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
# FILE: ciao.php
# LOCAL VERSION: 1.0.11
# CREATED ON: 2001.04.05
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
# 2001.05.19 - BD - added "category" variable input, so people can tie a template to a category
# 2001.05.22 - BD - added php-include header/footer file capability
# 2001.06.20 - BD - compensation for PHP4.0.5 magic_quotes_runtime detection bug
# 2001.07.01 - BD - enhanced magic_quotes_runtime fix
# 2001.07.18 - BD - added code to remove "\r" from email messages for Outlook compatibility
# 2001.07.29 - BD - updated to beta distribution and added phpLib support
# 2001.08.31 - BD - fixed sybase detection
#
# 2001.10.16 - BD - replaced "include_once" with "include" for PHP3 compatability
#---------------------------------------------------------
?>

<?php

####### CHECK IF SETUP IS COMPLETE ##########
if(! file_exists('modules/ciaodb.php'))
{ include("ciaoadm.php"); die(); }
if(! file_exists('modules/ciaocfg.php'))
{ include("ciaoadm.php"); die(); }
if(! file_exists('modules/template.ciao'))
{ include("ciaoadm.php"); die(); }
#############################################

# $DB   => Database Configurations
# $SQL  => Current SQL Database Interface
# $T    => Misc Tools
# $CFG  => General Configurations
# $MAIL => Sending Mail Tools

$VARS = array();
$SYS = array();
$SYS['DB_magic_quotes_gpc'] = get_magic_quotes_gpc();

if(! file_exists("gpl.txt"))
{ die(""); }

# create instance of screen template module
include("modules/ciaotools.php");
$T = new CiaoTools;

if(file_exists('modules/ciaodb.php'))
{# if database/tables and module have been created
# first create instance of db module
    include("modules/ciaodb.php");
    $DB = new DBmod;
    $SYS['DB_magic_quotes_sybase'] = $DB->magic_quotes_sybase;
    $T->shift = $DB->shift;

# then include sql modules and create instance
    include("modules/phplib_" . $DB->sql_type . ".php");
    include("modules/ciaosql.php");
    $SQL = new CiaoSQL;

# pass variables to new SQL object
    $SQL->Host = $T->CiaoDecode($DB->host);
    $SQL->User = $T->CiaoDecode($DB->user);
    $SQL->Password = $T->CiaoDecode($DB->password);
    $SQL->Database = $T->CiaoDecode($DB->database);
    $SQL->tableprefix = $T->CiaoDecode($DB->tableprefix);
    $SQL->Seq_Table = $SQL->tableprefix . "db_sequence";

# then open connection to database
    $SQL->connect();

    # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
    $SYS['SQL'] = $SQL;
    $SYS['T'] = $T;
    $SYS['DB'] = $DB;

# read in variables

    if(floor(phpversion) <= 3)
    { # PHP3 - use get_magic_quotes_runtime function
        $runtime_handle = "\$SYS['DB_magic_quotes_runtime'] = get_magic_quotes_runtime();";
    }
    else
    { # PHP4 - avoid buggy get_magic_quotes_runtime function
      # PHP4 - turn off magic_quotes_runtime using ini_set
        $runtime_handle = "\$SYS['DB_magic_quotes_runtime'] = 0;\n";
        $runtime_handle .= "ini_set(\"magic_quotes_runtime\",0);";
    }
    eval($runtime_handle);

# test if sybase is used as database
    if($DB->sql_type == "sybase" || $DB->sql_type == "odbc-sybase")
    { $sybase_is_used = 1; }
    else
    { $sybase_is_used = 0; }

# retrieve input variables in proper safe-format
    $VARS = $T->getpost_vars($SYS['DB_magic_quotes_gpc'],$SYS['DB_magic_quotes_sybase'],$sybase_is_used);

    $VARS['REMOTE_ADDR'] = $REMOTE_ADDR;
    $VARS['REMOTE_HOST'] = $REMOTE_HOST;
    $VARS['HTTP_USER_AGENT'] = $HTTP_USER_AGENT;

    if($VARS['category'] != '')
    { $VARS['template'] = $VARS['category']; }
    if($VARS['template'] == '')
    { $VARS['template'] = "template"; }
    list($VARS['html_start'], $VARS['html_end']) = $T->PARSE_XML_DOC("modules/" . $VARS['template'],$xml);
    if($frame == '')
    {
        if(file_exists($xml['header']))
        { include($xml['header']); }
        else
        { echo $VARS['html_start']; }
    }

    if($xml['txtProfile'] != '')
    { $xml['txtProfile'] = ereg_replace("--","=",$xml['txtProfile']); }
    if($xml['txtLink'] != '')
    { $xml['txtLink'] = ereg_replace("--","=",$xml['txtLink']); }
    if($xml['txtLogout'] != '')
    { $xml['txtLogout'] = ereg_replace("--","=",$xml['txtLogout']); }

    $xml['msgSignup'] = ereg_replace("\r"," ",$xml['msgSignup']);
    $xml['msgPassword'] = ereg_replace("\r"," ",$xml['msgPassword']);
?>

<!--
# Ciao-ELM - a customizable mass e-mail program that is administrator/subscriber friendly.
# Copyright (C) 2000,2001 Ben Drushell
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
# Some modules and classes that are distributed with Ciao-ELM are
# licensed under the terms of the Lesser General Public License.
#
# Copies of the GNU General Public License and GNU Lesser General Public License
# are included with this program.  They are also available for download at:
# http://www.fsf.org/ and http://www.technobreeze.com/license/
#
# For questions regarding the Ciao EmailList Manager,
# contact Ben Drushell: http://www.technobreeze.com/contact.html
-->

<?php
    if(! file_exists("ciaocredits.html"))
    { die("Setup Incomplete" . $VARS['html_end']); }

    include("modules/ciaocfg.php");
    $CFG = new CFGmod;

    include("modules/phpmailer.php");
    $MAIL = new phpmailer;
    $MAIL->Version = "Ciao ELM [v1.0.11] / phpmailer [v1.25.01]";
    $MAIL->boundary = "----=_Content.Boundary_" . md5(microtime());
    $MAIL->From = $CFG->email;
    $MAIL->FromName = $T->CiaoDecode($CFG->mail_fromname);
    $MAIL->Mailer = $T->CiaoDecode($CFG->mail_mailer);
    $MAIL->Sendmail = $T->CiaoDecode($CFG->mail_sendmail);
    $MAIL->Host = $T->CiaoDecode($CFG->mail_host);
    $MAIL->Port = $T->CiaoDecode($CFG->mail_port);
    $MAIL->Timeout = $T->CiaoDecode($CFG->mail_timeout);
    $MAIL->Helo = $T->CiaoDecode($CFG->mail_helo);

    # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
    $SYS['MAIL'] = $MAIL;
    $SYS['CFG'] = $CFG;

# create instance of login module
    include("modules/ciao_il.php");
    $PW = new PWmod;
    $PW->Verify($VARS,$SYS,$xml);
}

#for future public modules
#if($VARS['x'] == "" || substr($VARS['x'],0,1) != 'i')
#{ $VARS['x'] = "i00"; }

# create instance of dynamic worker module and run it
#require("modules/ciao_" . $VARS['x'] . ".php");
#$ciaomod = new module($VARS,$SYS,$xml);

if($VARS['frame'] == '')
{
    if(file_exists($xml['footer']))
    { include($xml['footer']); }
    else
    { echo $VARS['html_end']; }
}

if(is_object($SQL))
{ $SQL->unlocktables(); }
?>
