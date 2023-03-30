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

# FILE: ciaoadm.php

# LOCAL VERSION: 1.0.11

# CREATED ON: 2000.10.30

# CREATED BY: Ben Drushell - http://www.technobreeze.com/

# CONTRIBUTORS:

# 2001.01.20 - BD - added variable $DB-size to VARS

# 2001.01.30 - BD - added line of code suggested by Tom Sokolis for use with "magic_quotes" settings

# 2001.01.31 - BD - added extra "magic_quotes" detection

# 2001.03.14 - BD - modified "magic_quotes" detection by using get method 3.0.6

# 2001.06.13 - BD - added db update for verison 0.90

# 2001.06.20 - BD - compensation for PHP4.0.5 magic_quotes_runtime detection bug

# 2001.07.01 - BD - enhanced magic_quotes_runtime fix

# 2001.07.05 - BD - added db update for version 0.98

# 2001.07.29 - BD - updated to beta distribution and added phpLib support

# 2001.08.31 - BD - fixed sybase detection

# 2001.09.22 - BD - added database update fix code

# 2001.09.29 - BD - moved import retrieval code so import data does not get erased

#

# 2001.10.16 - BD - replaced "include_once" with "include" for PHP3 compatability

#---------------------------------------------------------



# $DB   => Database Configurations

# $SQL  => Current SQL Database Interface

# $T    => Misc Tools

# $CFG  => General Configurations

# $MAIL => Sending Mail Tools



$VARS = array();

$SYS = array();

$SYS['DB_magic_quotes_gpc'] = get_magic_quotes_gpc();



if(floor(phpversion()) <= 3)

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



if(! file_exists("gpl.txt"))

{ die(""); }



# create instance of screen template module

include("modules/ciaotools.php");

$T = new CiaoTools;



if(! file_exists("ciaocredits.html"))

{ die(""); }



if (file_exists("modules/ciaodb.php"))

{# if database/tables and module have been created

# first create instance of db module

    include("modules/ciaodb.php");

    $DB = new DBmod;

    $SYS['DB_magic_quotes_sybase'] = $DB->magic_quotes_sybase;

    $T->shift = $DB->shift;



# test if sybase is used as database

    if($DB->sql_type == "sybase" || $DB->sql_type == "odbc-sybase")

    { $sybase_is_used = 1; }

    else

    { $sybase_is_used = 0; }



# retrieve input variables in proper safe-format

    $VARS = $T->getpost_vars($SYS['DB_magic_quotes_gpc'],$SYS['DB_magic_quotes_sybase'],$sybase_is_used);


# retrieve file-import variables

    if($HTTP_POST_FILES['importfile']['tmp_name'] != '')

    {# USED WITH IMPORT FEATURE FOR PHP4 AND GREATER

        $VARS['importfile'] = $HTTP_POST_FILES['importfile']['tmp_name'];

        $VARS['importfile_name'] = $HTTP_POST_FILES['importfile']['name'];

        $VARS['importfile_type'] = $HTTP_POST_FILES['importfile']['type'];

        $VARS['importfile_size'] = $HTTP_POST_FILES['importfile']['size'];

    }

    if($importfile != '')

    {# USED WITH IMPORT FEATURE FOR LESS THAN PHP4

        $VARS['importfile'] = $importfile;

        $VARS['importfile_name'] = $importfile_name;

        $VARS['importfile_type'] = $importfile_type;

        $VARS['importfile_size'] = $importfile_size;

    }



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



# create instance of password module

    include("modules/ciaopass.php");

    $PW = new CiaoPass;

    $PW->Verify($VARS,$SYS);

    if(file_exists("modules/ciaocfg.php"))

    {# create instance of list configuration module

        include("modules/ciaocfg.php");

        $CFG = new CFGmod;

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $SYS['CFG'] = $CFG;



        include("modules/phpmailer.php");

        $MAIL = new phpmailer;

//        $MAIL->Version = "Ciao ELM [v1.0.11] / phpmailer [v1.25.01]";

        $MAIL->boundary = "----=_Content.Boundary_" . md5(microtime());

        $MAIL->From = $CFG->email;

        $MAIL->FromName = $T->CiaoDecode($CFG->mail_fromname);

        $MAIL->Mailer = $T->CiaoDecode($CFG->mail_mailer);

        $MAIL->Sendmail = $T->CiaoDecode($CFG->mail_sendmail);

        $MAIL->Host = $T->CiaoDecode($CFG->mail_host);

        $MAIL->Port = $T->CiaoDecode($CFG->mail_port);

        $MAIL->Timeout = $T->CiaoDecode($CFG->mail_timeout);

        $MAIL->Helo = $T->CiaoDecode($CFG->mail_helo);
				$MAIL->UseMSMailHeaders=false;


				include "hachelib.php";
//				var_crudas($MAIL);

        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible

        $SYS['MAIL'] = $MAIL;

    }



    if($VARS['x'] == "")

    { $VARS['x'] = "w"; }

    elseif((! $VARS['s']) && (substr($VARS['x'],0,1) == 's'))

    { $VARS['x'] = "w"; }



# create instance of update module

    include("modules/ciaoupdate.php");

    $fix = new update;



    if(! file_exists("modules/ciao_1_0_07.txt"))

    { $fix->update_1_0_07($VARS,$SYS); }

}

else # create database/tables and module

{

    $SYS['T'] = $T;



    if(gettype($_POST) == 'array')

    {

        while(list($key,$value) = each($_POST))

        { $VARS[$key] = $value; }

    }

    if(gettype($_GET) == 'array')

    {

        while(list($key,$value) = each($_GET))

        { $VARS[$key] = $value; }

    }

}



# check that configuration was completed

if(! file_exists("modules/template.ciao"))

{ $VARS['x'] = "s03"; }

if(! file_exists("modules/ciaocfg.php"))

{ $VARS['x'] = "s01"; }

if(! file_exists("modules/ciaodb.php"))

{ $VARS['x'] = "s00"; }

# end configuration check



# create instance of dynamic worker module and run it

require("modules/ciao_" . $VARS['x'] . ".php");

$ciaomod = new module($VARS,$SYS);

if(is_object($SQL))

{ $SQL->unlocktables(); }

?>

