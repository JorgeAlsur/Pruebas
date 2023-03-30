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
# FILE: ciao_il.php
# LOCAL VERSION: 1.0.12
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2000.12.22 - contributed in Perl by Bob Hurt & ported to PHP by BD - SENDMAIL and DIG validation routines
# 2000.12.23 - BD - email structure validation routine
# 2001.01.05 - BD - fixed VerifyFields variable error
# 2001.01.20 - BD - modifications for customizable sign-up verification message
# 2001.01.21 - Bret Chrismer - Discovered that popen command had not totally been ported from perl in SENDMAIL and DIG validation routines.
# 2001.01.29 - BD - modified code so "Subscribe" button is hidden when updating info
# 2001.01.30 - BD - modified for magic_quotes PHP settings
# 2001.02.09 - BD - added extra admin notification for editing and unsubscribing; added id field for email
# 2001.03.14 - BD - updated magic-quotes code to utilizing get_magic_quotes_gpc 3.0.6
# 2001.03.21 - BD - added default value capability for optional subscriber data
# 2001.04.05 - BD - modified to "member area"... added passwords...
# 2001.04.13 - BD - removed ending '|' pipe on dig command
# 2001.05.10 - BD - fixed the form not displaying bug by declaring $REMOTE_ADDR as global
# 2001.05.18 - BD - fixed the admin link/ data update problem
# 2001.05.19 - BD - delete (unsubscribe) now requires password, if passwords are being used
# 2001.05.19 - BD - added "category" variable input, so people can tie a template to a category
# 2001.05.20 - BD - Admin notification now contains categories subscribed info
# 2001.05.22 - BD - Added "return-path" field to email headers sent
# 2001.05.22 - BD - added php-include header/footer file capability
# 2001.05.31 - BD - added xml tags for lblRequired and lblOptional to replace hard-text "required" and "optional"
# 2001.06.13 - BD - added "unsubscribe all" button/function for when category-templates are applied
# 2001.06.13 - BD - added code to lower case all email addresses
# 2001.06.16 - BD - added multiple field type capabilities
# 2001.07.05 - BD - Added code for "ALL" category.
# 2001.07.29 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
# 2001.09.20 - BD - added extra trim statements on retrieved database data
#
# 2001.10.17 - BD - changed $VARS['user_id'] to trim($vRecord['email_id'])... fixed bug when verifying with category pre-defined option
#---------------------------------------------------------


class PWmod
{
    function Verify(&$VARS,$SYS,$xml)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $query = "DELETE FROM PREFIX_publicsession WHERE status_dt < '" . date("Y-m-d H:i:s",(time()-21600)) . "';";
        $SQL->locktable("PREFIX_publicsession");
        $SQL->q($query);
        $SQL->unlocktable();

        # REMOVE EXPIRED VERIFICATIONS
        $query = "DELETE FROM PREFIX_verify WHERE signup_dt < '" . date("Y-m-d H:i:s",(time() - 1036800)) . "';";
        $SQL->locktable("PREFIX_verify");
        $SQL->q($query);
        $SQL->unlocktable();

        global $REMOTE_ADDR;
        $query = "SELECT * FROM PREFIX_block WHERE block_value LIKE '%" . $T->CiaoEncode($REMOTE_ADDR) . "%';";
        $SQL->q($query);
        if(($SQL->nextrecord()) && ($REMOTE_ADDR != ''))
        { die($VARS['html_end']); } # user blocked
        elseif(! file_exists("ciaocredits.html"))
        { die("<h1 align='center'>setup incomplete</h1>" . $VARS['html_end']); }
        elseif($VARS['logout'] != '')
        {
            $query = "DELETE FROM PREFIX_publicsession WHERE session_id = '" . $VARS['p'] . "';";
            $SQL->locktable("PREFIX_publicsession");
            $SQL->q($query);
            $SQL->unlocktable();
            $VARS['p'] = '';
            $VARS['f_EMAIL'] = "";
            $VARS['f_PASSWORD'] = "";
            for($counter=1;$counter <= $CFG->optSize;$counter++)
            { $VARS['f_OPTION' . $counter] = ""; }
            $this->showForm("\n" . $xml['okLogout'] . "\n",$VARS,$SYS,$xml);
            if(file_exists($xml['footer']))
            { include($xml['footer']); }
            else
            { echo $VARS['html_end']; }
            die("");
        }
        elseif($VARS['v'] != '')
        {# VERIFY LINK ID AND TRANSFER TO LIST
            $query = "SELECT * FROM PREFIX_verify WHERE verify_id = '" . $VARS['v'] . "';";
            $SQL->q($query);
            if($SQL->nextrecord())
            {
                $VARS['x'] = "il";
                $VARS['p'] = $T->GenerateID(32);
                $vRecord = $SQL->Record;

                $query = "INSERT INTO PREFIX_publicsession VALUES('" . $VARS['p'] .  "','" . $vRecord['email_id'] . "','" . date("Y-m-d H:i:s") . "');";
                $SQL->locktable("PREFIX_publicsession");
                $SQL->q($query);
                $SQL->unlocktable();

                $VARS['f_EMAIL'] = $T->CiaoDecode($SQL->f('email_id'));
                $VARS['f_PASSWORD'] = $T->CiaoDecode($SQL->f('password'));

                if(strlen($VARS['category']) > 0)
                {
                    $query = "SELECT * FROM PREFIX_list WHERE email_id = '" . $vRecord['email_id'] . "'";
                    $SQL->q($query);
                    if(! ($SQL->nextrecord()))
                    { # add to list
                        $email_nid = $SQL->nid("PREFIX_list->email_nid");
                        $query = "INSERT INTO PREFIX_list VALUES('" . $email_nid . "','" . $vRecord['email_id'] . "','" . $vRecord['domain'] . "','" . $vRecord['password'] . "','" . $vRecord['signup_dt'] . "'";
                        for($counter=1;$counter <= $CFG->optSize;$counter++)
                        {
                            $query .= ",'" . $vRecord['option' . $counter] . "'";
                            if($CFG->optType[$counter] != 'checkbox')
                            { $VARS['f_OPTION' . $counter] = $T->CiaoDecode($vRecord['option' . $counter]); }
                        }
                        $query .= ");";
                        $SQL->locktable("PREFIX_list");
                        $SQL->q($query);
                        $SQL->unlocktable();
                        $query = "INSERT INTO PREFIX_catlist VALUES('" . trim($vRecord['email_id']) . "','ALL');";
                        $SQL->locktable("PREFIX_catlist");
                        $SQL->q($query);
                        $SQL->unlocktable();
                    }

                    $query = "SELECT * FROM PREFIX_category WHERE cat_id = '" . $VARS['category'] . "'";
                    $SQL->q($query);
                    if($SQL->nextrecord())
                    { # if category exists add to that category
                        $query = "INSERT INTO PREFIX_catlist VALUES('" . trim($vRecord['email_id']) . "','" . $VARS['category'] . "');";
                        $SQL->locktable("PREFIX_catlist");
                        $SQL->q($query);
                        $SQL->unlocktable();
                    }

                    if($CFG->notifyonverify)
                    { $this->NotifyAdmin($VARS,$SYS,"NEW SUBSCRIBER TO CATEGORY -" . $VARS['category'] . "-"); }
                }
                else
                { # just add to list
                    $email_nid = $SQL->nid("PREFIX_list->email_nid");
                    $query = "INSERT INTO PREFIX_list VALUES('" . $email_nid . "','" . $vRecord['email_id'] . "','" . $vRecord['domain'] . "','" . $vRecord['password'] . "','" . $vRecord['signup_dt'] . "'";
                    for($counter=1;$counter <= $CFG->optSize;$counter++)
                    {
                        $query .= ",'" . $vRecord['option' . $counter] . "'";
                        if($CFG->optType[$counter] != 'checkbox')
                        { $VARS['f_OPTION' . $counter] = $T->CiaoDecode($vRecord['option' . $counter]); }
                    }
                    $query .= ");";
                    $SQL->locktable("PREFIX_list");
                    $SQL->q($query);
                    $SQL->unlocktable();
                    $query = "INSERT INTO PREFIX_catlist VALUES('" . $vRecord['email_id'] . "','ALL');";
                    $SQL->locktable("PREFIX_catlist");
                    $SQL->q($query);
                    $SQL->unlocktable();

                    if($CFG->notifyonverify)
                    { $this->NotifyAdmin($VARS,$SYS,"NEW SUBSCRIBER"); }
                }

                $this->showNavigation($VARS,$xml);
                $this->showForm("\n" . $xml['okVerify'] . "\n",$VARS,$SYS,$xml,1);
                $query = "DELETE FROM PREFIX_verify WHERE verify_id = '" . $VARS['v'] . "';";
                $SQL->locktable("PREFIX_verify");
                $SQL->q($query);
                $SQL->unlocktable();
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            else
            {
                $this->showForm("\n" . $xml['errVerify'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
        }
        elseif($VARS['f_btnADD'] != '')
        {
            if($VARS['f_PASSWORD'] == '')
            { $VARS['f_PASSWORD'] = 'changethis'; }

            $verify = $this->VerifyFields($xml,$VARS,$SYS);
            if($verify == 0)
            { # everything is OK... add record into pending database
                $ID = $T->GenerateID(32);
                $verify_nid = $SQL->nid("PREFIX_verify->verify_nid");
                list($alias,$domain) = split("@",strtolower(trim($VARS['f_EMAIL'])));
                $query = "INSERT INTO PREFIX_verify VALUES(";
                $query .= "'" . $verify_nid . "'";
                $query .= ",'" . $ID . "'";
                $query .= ",'" . $T->CiaoEncode(strtolower($VARS['f_EMAIL'])) . "'";
                $query .= ",'" . $T->CiaoEncode($domain) . "'";
                $query .= ",'" . $T->CiaoEncode($VARS['f_PASSWORD']) . "'";
                $query .= ",'" . date("Y-m-d H:i:s") . "'";
                for($i = 1; $i <= $CFG->optSize; $i++)
                { $query .= ",'" . $T->CiaoEncode($VARS['f_OPTION' . $i]) . "'"; }
                $query .= ");";
                $SQL->locktable("PREFIX_verify");
                $SQL->q($query);
                $SQL->unlocktable();
                $VARS['msgSignup'] = $xml['msgSignup'];
                $this->SendVerify($VARS,$SYS,$ID);
                if($CFG->notifyonrequest)
                { $this->NotifyAdmin($VARS,$SYS,"SUBSCRIBER VERIFICATION PENDING"); }
                $this->showForm("\n" . $xml['okAdd'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            elseif($verify == 1)
            {
                $this->showForm("\n" . $xml['errRequired'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            elseif($verify == 2)
            {
                $this->showForm("\n" . $xml['errAddVerify'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            elseif($verify == 3)
            {
                if(strlen($VARS['category']) > 0)
                {
                    $query = "SELECT * FROM PREFIX_catlist WHERE email_id = '" . $T->CiaoEncode($VARS['f_EMAIL']) . "' AND cat_id = '" . $VARS['category'] . "';";
                    $SQL->q($query);
                    if($SQL->nextrecord())
                    {
                        $this->showForm("\n" . $xml['errAddList'] . "\n",$VARS,$SYS,$xml);
                        if(file_exists($xml['footer']))
                        { include($xml['footer']); }
                        else
                        { echo $VARS['html_end']; }
                        die("");
                    }
                    else
                    {
                        $ID = $T->GenerateID(32);
                        $verify_nid = $SQL->nid("PREFIX_verify->verify_nid");
                        list($alias,$domain) = split("@",strtolower(trim($VARS['f_EMAIL'])));
                        $query = "INSERT INTO PREFIX_verify VALUES(";
                        $query .= "'" . $verify_nid . "'";
                        $query .= ",'" . $ID . "'";
                        $query .= ",'" . $T->CiaoEncode(strtolower($VARS['f_EMAIL'])) . "'";
                        $query .= ",'" . $T->CiaoEncode($domain) . "'";
                        $query .= ",'" . $T->CiaoEncode($VARS['f_PASSWORD']) . "'";
                        $query .= ",'" . date("Y-m-d H:i:s") . "'";
                        for($i = 1; $i <= $CFG->optSize; $i++)
                        { $query .= ",'" . $T->CiaoEncode($VARS['f_OPTION' . $i]) . "'"; }
                        $query .= ");";
                        $SQL->locktable("PREFIX_verify");
                        $SQL->q($query);
                        $SQL->unlocktable();
                        $VARS['msgSignup'] = $xml['msgSignup'];
                        $this->SendVerify($VARS,$SYS,$ID);
                        if($CFG->notifyonrequest)
                        { $this->NotifyAdmin($VARS,$SYS,"SUBSCRIBER VERIFICATION PENDING"); }
                        $this->showForm("\n" . $xml['okAdd'] . "\n",$VARS,$SYS,$xml);
                        if(file_exists($xml['footer']))
                        { include($xml['footer']); }
                        else
                        { echo $VARS['html_end']; }
                        die("");
                    }
                }
                else
                {
                    $this->showForm("\n" . $xml['errAddList'] . "\n",$VARS,$SYS,$xml);
                    if(file_exists($xml['footer']))
                    { include($xml['footer']); }
                    else
                    { echo $VARS['html_end']; }
                    die("");
                }
            }
            elseif($verify == 5) # 4 means blocked... don't display
            {
                $this->showForm("\n" . $xml['errAddEmail'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            else # 4 means blocked... don't display
            {
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
        }
        elseif(($VARS['f_btnFIND'] != '') && ($VARS['f_EMAIL'] != ''))
        {
            $rEmail = $T->CiaoEncode($VARS['f_EMAIL']);
            $lEmail = $T->CiaoEncode(strtolower($VARS['f_EMAIL']));

            if($CFG->usepassword)
            { $query = "SELECT * FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "') AND password = '" . $T->CiaoEncode($VARS['f_PASSWORD']) . "';"; }
            else
            { $query = "SELECT * FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');"; }
            $SQL->q($query);
            if($SQL->nextrecord())
            {
                $VARS['p'] = $T->GenerateID(32);
                $VARS['user_id'] = trim($SQL->f('email_id'));
                $query = "INSERT INTO PREFIX_publicsession VALUES('" . $VARS['p'] .  "','" . $VARS['user_id'] . "','" . date("Y-m-d H:i:s") . "');";
                $SQL->locktable("PREFIX_publicsession");
                $SQL->q($query);
                $SQL->unlocktable();

                for($counter=1;$counter <= $CFG->optSize;$counter++)
                {
                    if($CFG->optType[$counter] != 'checkbox')
                    { $VARS['f_OPTION' . $counter] = $T->CiaoDecode($SQL->f('option' . $counter)); }
                }
                $this->showNavigation($VARS,$xml);
                $this->showForm("\n" . $xml['okFind'] . "\n",$VARS,$SYS,$xml,1);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            else
            {
                $this->showForm("\n" . $xml['errFind'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
        }
        elseif(($VARS['f_btnDELETECAT'] != '') && ($VARS['f_EMAIL'] != ''))
        {
            $rEmail = $T->CiaoEncode($VARS['f_EMAIL']);
            $lEmail = $T->CiaoEncode(strtolower($VARS['f_EMAIL']));

            if($CFG->usepassword)
            { $query = "SELECT * FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "') AND password = '" . $T->CiaoEncode($VARS['f_PASSWORD']) . "';"; }
            else
            { $query = "SELECT * FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');"; }
            $SQL->q($query);
            if($SQL->nextrecord())
            {
                $delete = 0;
                if(strlen($VARS['category'])>0)
                {
                    $query = "SELECT * FROM PREFIX_catlist WHERE cat_id = '" . $VARS['category'] . "';";
                    $SQL->q($query);
                    if($SQL->nextrecord())
                    {
                        $query = "SELECT * FROM PREFIX_catlist WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
                        $SQL->q($query);
                        if($SQL->num_rows() > 2)
                        {
                            $query = "DELETE FROM PREFIX_catlist WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "') AND cat_id = '" . $VARS['category'] . "';";
                            $SQL->locktable("PREFIX_catlist");
                            $SQL->q($query);
                            $SQL->unlocktable();
                            if($CFG->notifyonedit)
                            { $this->NotifyAdmin($VARS,$SYS,"SUBSCRIBER RECORD UPDATED"); }
                        }
                        else
                        { $delete = 1; }
                    }
                    else
                    {
                        $this->showForm("\n" . $xml['errFind'] . "\n",$VARS,$SYS,$xml);
                        if(file_exists($xml['footer']))
                        { include($xml['footer']); }
                        else
                        { echo $VARS['html_end']; }
                        die("");
                    }
                }
                else
                { $delete = 1; }

                if($delete > 0)
                {
                    for($counter=1;$counter <= $CFG->optSize;$counter++)
                    {
                        if($CFG->optType[$counter] != 'checkbox')
                        { $VARS['f_OPTION' . $counter] = $T->CiaoDecode($SQL->f('option' . $counter)); }
                    }

                    $query = "DELETE FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
                    $SQL->locktable("PREFIX_list");
                    $SQL->q($query);
                    $SQL->unlocktable();
                    $query = "DELETE FROM PREFIX_catlist WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
                    $SQL->locktable("PREFIX_catlist");
                    $SQL->q($query);
                    $SQL->unlocktable();
                    if($CFG->notifyondelete)
                    { $this->NotifyAdmin($VARS,$SYS,"SUBSCRIBER RECORD DELETED"); }
                }
                $this->showForm("\n" . $xml['okDelete'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            else
            {
                $this->showForm("\n" . $xml['errFind'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
        }
        elseif(($VARS['f_btnDELETE'] != '') && ($VARS['f_EMAIL'] != ''))
        {
            $rEmail = $T->CiaoEncode($VARS['f_EMAIL']);
            $lEmail = $T->CiaoEncode(strtolower($VARS['f_EMAIL']));

            if($CFG->usepassword)
            { $query = "SELECT * FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "') AND password = '" . $T->CiaoEncode($VARS['f_PASSWORD']) . "';"; }
            else
            { $query = "SELECT * FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');"; }
            $SQL->q($query);
            if($SQL->nextrecord())
            {
                for($counter=1;$counter <= $CFG->optSize;$counter++)
                {
                    if($CFG->optType[$counter] != 'checkbox')
                    { $VARS['f_OPTION' . $counter] = $T->CiaoDecode($SQL->f('option' . $counter)); }
                }

                $query = "DELETE FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
                $SQL->locktable("PREFIX_list");
                $SQL->q($query);
                $SQL->unlocktable();
                $query = "DELETE FROM PREFIX_catlist WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
                $SQL->locktable("PREFIX_catlist");
                $SQL->q($query);
                $SQL->unlocktable();
                if($CFG->notifyondelete)
                { $this->NotifyAdmin($VARS,$SYS,"SUBSCRIBER RECORD DELETED"); }

                $this->showForm("\n" . $xml['okDelete'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            else
            {
                $this->showForm("\n" . $xml['errFind'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
        }
        elseif($VARS['f'] != '')
        {
            $query = "SELECT * FROM PREFIX_list WHERE email_id = '" . $VARS['f'] . "' AND password = '" . $VARS['fp'] . "';";
            $SQL->q($query);
            if($SQL->nextrecord())
            {
                $VARS['p'] = $T->GenerateID(32);
                $VARS['user_id'] = trim($SQL->f('email_id'));
                $query = "INSERT INTO PREFIX_publicsession VALUES('" . $VARS['p'] .  "','" . $VARS['user_id'] . "','" . date("Y-m-d H:i:s") . "');";
                $SQL->locktable("PREFIX_publicsession");
                $SQL->q($query);
                $SQL->unlocktable();

                $VARS['f_EMAIL'] = $T->CiaoDecode($SQL->f('email_id'));
                $VARS['f_PASSWORD'] = $T->CiaoDecode($SQL->f('password'));
                for($counter=1;$counter <= $CFG->optSize;$counter++)
                {
                    if($CFG->optType[$counter] != 'checkbox')
                    { $VARS['f_OPTION' . $counter] = $T->CiaoDecode($SQL->f('option' . $counter)); }
                }
                $this->showForm("\n" . $xml['okFind'] . "\n",$VARS,$SYS,$xml,1);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
            else
            {
                $this->showForm("\n" . $xml['errFind'] . "\n",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
        }
        elseif($CFG->usepassword && $VARS['f_btnPASSWORD'] != '')
        {
            $rEmail = $T->CiaoEncode($VARS['f_EMAIL']);
            $lEmail = $T->CiaoEncode(strtolower($VARS['f_EMAIL']));

            $query = "SELECT * FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
            $SQL->q($query);
            if($SQL->nextrecord())
            { $this->SendPassword($VARS,$SYS,$xml,$T->CiaoDecode($SQL->f('password'))); }
            $this->showForm("\n" . $xml['okPassword'] . "\n",$VARS,$SYS,$xml);
            if(file_exists($xml['footer']))
            { include($xml['footer']); }
            else
            { echo $VARS['html_end']; }
            die("");
        }
        elseif(! file_exists("gpl.txt"))
        {
            echo "<h1 align='center'>setup incomplete</h1>";
            if(file_exists($xml['footer']))
            { include($xml['footer']); }
            else
            { echo $VARS['html_end']; }
            die("");
        }
        elseif ($VARS['p'] != '')
        {
            $query = "SELECT * FROM PREFIX_publicsession WHERE session_id = '" . $VARS['p'] . "';";
            $SQL->q($query);
            if($SQL->nextrecord())
            {
                $VARS['user_id'] = $SQL->f('user_id');
                $query = "UPDATE PREFIX_publicsession SET status_dt = '" . date("Y-m-d H:i:s") . "' WHERE session_id = '" . $VARS['p'] . "';";
                $SQL->locktable("PREFIX_publicsession");
                $SQL->q($query);
                $SQL->unlocktable();
                if($VARS['frame'] == '')
                { $this->showNavigation($VARS,$xml); }
                if(($VARS['f_btnUPDATE'] != '') && ($VARS['f_EMAIL'] != '') && (($VARS['f_PASSWORD'] != '' && $CFG->usepassword) || (! $CFG->usepassword)))
                {
                    $rEmail = $T->CiaoEncode($VARS['f_EMAIL']);
                    $lEmail = $T->CiaoEncode(strtolower($VARS['f_EMAIL']));

                    $verify = $this->VerifyFields($xml,$VARS,$SYS);
                    if($verify != 1)
                    {
                        if($CFG->usepassword)
                        { $setpassword = "password = '" . $T->CiaoEncode($VARS['f_PASSWORD']) . "', "; }
                        $query = "UPDATE PREFIX_list SET " . $setpassword . "option1 = '" . $T->CiaoEncode($VARS['f_OPTION1']) . "'";
                        for($counter=2;$counter <= $CFG->optSize;$counter++)
                        { $query .= ", option" . $counter . " = '" . $T->CiaoEncode($VARS['f_OPTION' . $counter]) . "'"; }
                        $query .= " WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
                        $SQL->locktable("PREFIX_list");
                        $SQL->q($query);
                        $SQL->unlocktable();
                        $query = "SELECT * FROM PREFIX_category;";
                        $SQL->q($query);
                        while($SQL->nextrecord())
                        {
                            $tmp = new CiaoSQL;
                            $tmp->clone($SQL);

                            if(($VARS['f_' . trim($SQL->f('cat_id'))] != '') && ($VARS['f_' . trim($SQL->f('cat_id')) . '_old'] == ''))
                            { # ADD TO NEW CATEGORY
                                $query = "INSERT INTO PREFIX_catlist VALUES('" . $lEmail . "','" . trim($SQL->f('cat_id')) . "');";
                                $tmp->locktable("PREFIX_catlist");
                                $tmp->q($query);
                                $tmp->unlocktable();
                            }
                            elseif(($VARS['f_' . trim($SQL->f('cat_id'))] == '') && ($VARS['f_' . trim($SQL->f('cat_id')) . '_old'] != ''))
                            { # REMOVE FROM CATEGORY
                                $query = "DELETE FROM PREFIX_catlist WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "') AND cat_id = '" . trim($SQL->f('cat_id')) . "';";
                                $tmp->locktable("PREFIX_catlist");
                                $tmp->q($query);
                                $tmp->unlocktable();
                            }
                        }
                        if($CFG->notifyonedit)
                        { $this->NotifyAdmin($VARS,$SYS,"SUBSCRIBER RECORD UPDATED"); }
                        $this->showForm("\n" . $xml['okUpdate'] . "\n",$VARS,$SYS,$xml,1);
                        if(file_exists($xml['footer']))
                        { include($xml['footer']); }
                        else
                        { echo $VARS['html_end']; }
                        die("");
                    }
                    elseif($verify == 1)
                    {
                        $this->showForm("\n" . $xml['errRequired'] . "\n",$VARS,$SYS,$xml,1);
                        if(file_exists($xml['footer']))
                        { include($xml['footer']); }
                        else
                        { echo $VARS['html_end']; }
                        die("");
                    }
                }
                elseif($VARS['x'] == 'il') # display profile
                {
                    $query = "SELECT * FROM PREFIX_list WHERE email_id = '" . $VARS['user_id'] . "';";
                    $SQL->q($query);
                    if($SQL->nextrecord())
                    {
                        $VARS['f_EMAIL'] = $T->CiaoDecode($SQL->f('email_id'));
                        for($counter=1;$counter <= $CFG->optSize;$counter++)
                        {
                            if($CFG->optType[$counter] != 'checkbox')
                            { $VARS['f_OPTION' . $counter] = $T->CiaoDecode($SQL->f('option' . $counter)); }
                        }
                    }
                    $this->showForm("",$VARS,$SYS,$xml,1);
                    if(file_exists($xml['footer']))
                    { include($xml['footer']); }
                    else
                    { echo $VARS['html_end']; }
                    die("");
                }
            }
            else
            {
                $this->showForm("",$VARS,$SYS,$xml);
                if(file_exists($xml['footer']))
                { include($xml['footer']); }
                else
                { echo $VARS['html_end']; }
                die("");
            }
        }
        else
        {
            $this->showForm("",$VARS,$SYS,$xml);
            if(file_exists($xml['footer']))
            { include($xml['footer']); }
            else
            { echo $VARS['html_end']; }
            die("");
        }
    }

    function showForm($message,$VARS,$SYS,$xml,$ShowCategories=0)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        if(strlen($xml['lblRequired']) <= 0)
        { $xml['lblRequired'] = "required"; }
        if(strlen($xml['lblOptional']) <= 0)
        { $xml['lblOptional'] = "optional"; }

        $VARS['f_EMAIL'] = ereg_replace("[[:space:]]","",$this->SECURE_FIELD($VARS['f_EMAIL']));
        for($i=1;$i <= $CFG->optSize;$i++)
        { $VARS['txtOPTION' . $i] = $this->SECURE_FIELD($VARS['f_OPTION' . $i]); }
?>

<form method="post" action="ciao.php">
<?php
        if($VARS['p'] != '')
        { echo "\n<input type='hidden' name='p' value='" . $VARS['p'] . "'>\n"; }
        echo "\n<input type='hidden' name='x' value='xl'>\n";
        if($VARS['template'] != '')
        { echo "\n<input type='hidden' name='template' value='" . $VARS['template'] . "'>\n"; }
        if($VARS['category'] != '')
        { echo "\n<input type='hidden' name='category' value='" . $VARS['category'] . "'>\n"; }
?>
<table bgcolor="<?php echo $xml['rgbBackground'] ?>" cellpadding="5" cellspacing="0" border="1">
<tr><td colspan="2" align="center"><font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>">
<big><?php echo $xml['txtTitle'] ?></big>
</font></td></tr>

<tr><td colspan="2" align="center" bgcolor="<?php echo $xml['rgbText'] ?>">
<font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbBackground'] ?>"><b>

<?php echo $message ?>

</b></font></td></tr>

<tr><td colspan="2" align="center"><font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>">
<?php echo $xml['txtPrompt'] ?>
<input TYPE="TEXT" NAME="f_EMAIL" VALUE="<?php echo $VARS['f_EMAIL'] ?>" SIZE="20" MAXLENGTH="125">
</font></td></tr>

<?php
        if($CFG->usepassword)
        {
?>
<tr><td><font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>" size="-1">
<?php echo $xml['txtPassword'] ?>(<?php echo $xml['lblRequired'] ?>)
</font></td><td>
<input TYPE="password" NAME="f_PASSWORD" VALUE="<?php echo $VARS['f_PASSWORD'] ?>" SIZE="25" MAXLENGTH="25">
</td></tr>
<?php
        }

        for($counter=1;$counter <= $CFG->optSize;$counter++)
        {
            if($CFG->optReq[$counter] != 'n')
            {
?>
<tr><td width="50%">
<font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>" size="-1">
<?php
                echo $CFG->optName[$counter] . ":";
                if($CFG->optReq[$counter] == 'r')
                { echo "(" . $xml['lblRequired'] . ")"; }
                else
                { echo "(" . $xml['lblOptional'] . ")"; }
                if(strlen($VARS['f_OPTION' . $counter]) == 0)
                {
                    if($CFG->optType[$counter] != 'checkbox')
                    { $VARS['f_OPTION' . $counter] = $CFG->optDefault[$counter]; }
                }
?>
</font></td>
<td align="center" width="50%">
<font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>" size="-1">
<?php
                if($CFG->optType[$counter] == 'select')
                {
?>
<SELECT NAME="f_OPTION<?php echo $counter ?>">
<?php
                    for($token=strtok($CFG->optDefault[$counter],";"); $token != ''; $token = strtok(";"))
                    {
                        if($VARS['f_OPTION' . $counter] == $token)
                        { $selected = "SELECTED"; }
                        else
                        { $selected = ""; }

?>

<OPTION VALUE="<?php echo $token ?>" <?php echo $selected ?>><?php echo $token ?>

<?php
                    }
?>
</SELECT>
<?php
                }
                elseif($CFG->optType[$counter] == 'radio')
                {
                    $first = 1;
                    for($token=strtok($CFG->optDefault[$counter],";"); $token != ''; $token = strtok(";"))
                    {
                        if($VARS['f_OPTION' . $counter] == $token)
                        { $selected = "CHECKED"; }
                        elseif(($VARS['f_OPTION' . $counter] == $CFG->optDefault[$counter]) && $first)
                        {
                            $selected = "CHECKED";
                            $first = 0;
                        }
                        else
                        { $selected = ""; }
?>

(<INPUT TYPE="radio" NAME="f_OPTION<?php echo $counter ?>" VALUE="<?php echo $token ?>" <?php echo $selected ?>><?php echo $token ?>)

<?php
                    }
                }
                elseif($CFG->optType[$counter] == 'checkbox')
                {
                    if($VARS['f_OPTION' . $counter] == $CFG->optDefault[$counter])
                    { $checked = "CHECKED"; }
                    else
                    { $checked = ""; }
?>

<INPUT TYPE="CHECKBOX" NAME="f_OPTION<?php echo $counter ?>" VALUE="<?php echo $CFG->optDefault[$counter] ?>" <?php echo $checked ?>>

<?php
                }
                else
                {
?>

<INPUT TYPE="TEXT" NAME="f_OPTION<?php echo $counter ?>" VALUE="<?php echo $VARS['f_OPTION' . $counter] ?>" SIZE="25" MAXLENGTH="125">

<?php
                }
?>
</font>
</td></tr>
<?php
            }
        }
        if($ShowCategories && ($VARS['category'] == ''))
        {
            $query = "SELECT * FROM PREFIX_category WHERE cat_id != '';";
            $SQL->q($query);
            if($SQL->num_rows() > 1)
            {
?>
<tr><td colspan="2" align="center"><font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>">
<?php echo $xml['txtCategory'] ?>
</font></td></tr>
<?php
                while($SQL->nextrecord())
                {
                    $tmp = new CiaoSQL;
                    $tmp->clone($SQL);

                    if(trim($SQL->f('cat_id')) == "ALL")
                    { $SQL->nextrecord(); }
                    if(trim($SQL->f('cat_id')) != "")
                    {
?>
<tr><td width="50%"><font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>">
<?php
                        $query = "SELECT * FROM PREFIX_catlist WHERE cat_id = '" . trim($SQL->f('cat_id')) . "' AND email_id = '" . $T->CiaoEncode($VARS['f_EMAIL']) . "';";
                        $tmp->q($query);
                        if($tmp->nextrecord())
                        {
?>
<input type="hidden" name="f_<?php echo trim($SQL->f('cat_id')) ?>_old" value="1">
<input type="checkbox" name="f_<?php echo trim($SQL->f('cat_id')) ?>" value="1" CHECKED>
<?php
                        }
                        else
                        {
?>
<input type="checkbox" name="f_<?php echo trim($SQL->f('cat_id')) ?>" value="1">
<?php
                        }
                        echo $SQL->f('cat_name');
?>
</font></td><td width="50%"><font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>">
<?php
                        $SQL->nextrecord();
                        if(trim($SQL->f('cat_id')) == "ALL")
                        { $SQL->nextrecord(); }
                        if(trim($SQL->f('cat_id')) != "")
                        {
                            $query = "SELECT * FROM PREFIX_catlist WHERE cat_id = '" . trim($SQL->f('cat_id')) . "' AND email_id = '" . $T->CiaoEncode($VARS['f_EMAIL']) . "';";
                            $tmp->q($query);
                            if($tmp->nextrecord())
                            {
?>
<input type="hidden" name="f_<?php echo trim($SQL->f('cat_id')) ?>_old" value="1">
<input type="checkbox" name="f_<?php echo trim($SQL->f('cat_id')) ?>" value="1" CHECKED>
<?php
                            }
                            else
                            {
?>
<input type="checkbox" name="f_<?php echo trim($SQL->f('cat_id')) ?>" value="1">
<?php
                            }
                            echo trim($SQL->f('cat_name'));
                        }
                        echo "&nbsp;</font></td></tr>";
                    }
                }
            }
        }

?>
<tr><td align="center" colspan="2"><font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>" size="-1">
<?php echo $xml['txtInstructions'] ?>
</font></td></tr>
<tr><td align="center" colspan="2">
<font face="<?php echo $xml['fontFace'] ?>" color="<?php echo $xml['rgbText'] ?>">
<?php
        if(strlen($xml['btnAdd']) <= 0)
        { $xml['btnAdd'] = "Subscribe"; }
        if(strlen($xml['btnDelete']) <= 0)
        { $xml['btnDelete'] = "Unsubscribe"; }
        if(strlen($xml['btnDeleteCat']) <= 0)
        { $xml['btnDeleteCat'] = "Unsubscribe from Category"; }
        if(strlen($xml['btnFind']) <= 0)
        { $xml['btnFind'] = "Logout"; }
        if(strlen($xml['btnUpdate']) <= 0)
        { $xml['btnUpdate'] = "Update Info"; }
        if(strlen($xml['txtLogout']) <= 0)
        { $xml['txtLogout'] = "Logout"; }
        if(strlen($xml['btnPassword']) <= 0)
        { $xml['btnPassword'] = "Forgot Password"; }

        if(! $ShowCategories)
        { echo "<input TYPE=\"SUBMIT\" NAME=\"f_btnADD\" VALUE=\"" . $xml['btnAdd'] . "\">"; }
        echo "<input TYPE=\"SUBMIT\" NAME=\"f_btnDELETE\" VALUE=\"" . $xml['btnDelete'] . "\">";
        if(strlen($VARS['category']) > 0)
        { echo "<input TYPE=\"SUBMIT\" NAME=\"f_btnDELETECAT\" VALUE=\"" . $xml['btnDeleteCat'] . "\">"; }
        if($v == '' && $p == '' && $ShowCategories == '')
        { echo "<input TYPE=\"SUBMIT\" NAME=\"f_btnFIND\" VALUE=\"" . $xml['btnFind'] . "\">"; }

        if($ShowCategories)
        {
            echo "<input TYPE=\"SUBMIT\" NAME=\"f_btnUPDATE\" VALUE=\"" . $xml['btnUpdate'] . "\">";
            echo "<input TYPE=\"SUBMIT\" NAME=\"logout\" VALUE=\"" . $xml['txtLogout'] . "\">";
        }
        elseif($CFG->usepassword)
        { echo "<input TYPE=\"SUBMIT\" NAME=\"f_btnPASSWORD\" VALUE=\"" . $xml['btnPassword'] . "\">"; }
?>
</font>
</td></tr>
</table>
</form>
<?php
    }

    function NotifyAdmin($VARS,$SYS,$SUBJECT)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];
        $MAIL = $SYS['MAIL'];

        $MESSAGE = "\n\n" . $SUBJECT . "\n";
        $MESSAGE .= "\nDATE: " . date("Y-m-d H:i:s");
        $MESSAGE .= "\nE-mail Address: " . $VARS['f_EMAIL'] . "\n\n";
        $MESSAGE .= "Request submitted by\n";
        $MESSAGE .= "Remote IP: " . $VARS['REMOTE_ADDR'] . "\n";
        $MESSAGE .= "Remote Host: " . gethostbyaddr($VARS['REMOTE_ADDR']) . "\n";
        $MESSAGE .= "Browser: " . $VARS['HTTP_USER_AGENT'] . "\n\n";
        $MESSAGE .= "---Subscriber Information---\n";

        for($i=1;$i<$CFG->optSize;$i++)
        {
            if($VARS['f_OPTION' . $i])
            { $MESSAGE .= $CFG->optName[$i] . ": " . $VARS['f_OPTION' . $i] . "\n"; }
        }

        $admin = new CiaoSQL;
        $admin->clone($SQL);

        $query = "SELECT * FROM PREFIX_catlist L, PREFIX_category C WHERE L.email_id = '" . $T->CiaoEncode($VARS['f_EMAIL']) . "' AND L.cat_id = C.cat_id;";
        $admin->q($query);
        if($admin->num_rows()>0)
        {
            $MESSAGE .= "\n---Categories Subscribed---\n";
            for($count=1;$admin->nextrecord();$count++)
            { $MESSAGE .= $count . ": " . trim($admin->f('cat_name')) . "\n"; }
        }

        $MAIL->AddAddress($MAIL->From);
        $MAIL->AddReplyTo($VARS['f_EMAIL']);
        $MAIL->AddCustomHeader("Error-To: <" . $MAIL->From . ">");
        $MAIL->Subject = $SUBJECT;
        $MAIL->Body = "\n" . $MESSAGE . "\n\n";
        $return_value = $MAIL->Send();
        $MAIL->ClearAllRecipients();
        $MAIL->ClearReplyTos();
        $MAIL->ClearCustomHeaders();
        $MAIL->Subject = "";
        $MAIL->Body = "";
        return($return_value);
    }

    function SendPassword($VARS,$SYS,$xml,$PASSWORD)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $MAIL = $SYS['MAIL'];

        $MESSAGE = $xml['msgPassword'];

        if(! eregi("#PASSWORD#",$MESSAGE))
        { $MESSAGE .= "\n" . $PASSWORD; }

        $MESSAGE = eregi_replace("#PASSWORD#",$PASSWORD,$MESSAGE);
        $MESSAGE = eregi_replace("#URL#",$CFG->url,$MESSAGE);
        $MESSAGE = eregi_replace("#EMAIL#",$VARS['f_EMAIL'],$MESSAGE);
        $MESSAGE = eregi_replace("#REMOTE_IP#",$VARS['REMOTE_ADDR'],$MESSAGE);
        $MESSAGE = eregi_replace("#REMOTE_HOST#",gethostbyaddr($VARS['REMOTE_ADDR']),$MESSAGE);
        $MESSAGE = eregi_replace("#BROWSER#",$VARS['HTTP_USER_AGENT'],$MESSAGE);
        $MESSAGE = ereg_replace("--","=",$MESSAGE);

        $MAIL->AddAddress($VARS['f_EMAIL']);
        $MAIL->AddCustomHeader("Error-To: <" . $MAIL->From . ">");
        $MAIL->Subject = "Password Request";
        $MAIL->Body = "\n" . $MESSAGE . "\n\n";
        $return_value = $MAIL->Send();
        $MAIL->ClearAllRecipients();
        $MAIL->ClearCustomHeaders();
        $MAIL->Subject = "";
        $MAIL->Body = "";
        return($return_value);
    }

    function SendVerify($VARS,$SYS,$ID)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];
        $MAIL = $SYS['MAIL'];

        $MESSAGE = $VARS['msgSignup'];
        $VERIFYURL = $CFG->url . "ciao.php?v=" . $ID;
        if($VARS['category'] != "")
        { $VERIFYURL .= "&category=" . $VARS['category']; }
        elseif($VARS['template'] != "template")
        { $VERIFYURL .= "&template=" . $VARS['template']; }

        if(! eregi("#VERIFYURL#",$MESSAGE))
        { $MESSAGE .= "\n" . $VERIFYURL; }

        $MESSAGE = eregi_replace("#VERIFYURL#",$VERIFYURL,$MESSAGE);
        $MESSAGE = eregi_replace("#ID#",$ID,$MESSAGE);
        $MESSAGE = eregi_replace("#URL#",$CFG->url,$MESSAGE);
        $MESSAGE = eregi_replace("#EMAIL#",$VARS['f_EMAIL'],$MESSAGE);
        $MESSAGE = eregi_replace("#REMOTE_IP#",$VARS['REMOTE_ADDR'],$MESSAGE);
        $MESSAGE = eregi_replace("#REMOTE_HOST#",gethostbyaddr($VARS['REMOTE_ADDR']),$MESSAGE);
        $MESSAGE = eregi_replace("#BROWSER#",$VARS['HTTP_USER_AGENT'],$MESSAGE);
        $MESSAGE = ereg_replace("--","=",$MESSAGE);

        $MAIL->AddAddress($VARS['f_EMAIL']);
        $MAIL->AddCustomHeader("Error-To: <" . $MAIL->From . ">");
        $MAIL->Subject = "Request Verification";
        $MAIL->Body = "\n" . $MESSAGE . "\n\n";
        $return_value = $MAIL->Send();
        $MAIL->ClearAllRecipients();
        $MAIL->ClearCustomHeaders();
        $MAIL->Subject = "";
        $MAIL->Body = "";
        return($return_value);
    }

    function VERIFY_VIA_SENDMAIL($sendmail,$address)
    {
    # perl function contributed by Bob Hurt http://www.bobhurt.com/ 2000.12.14
    # ported to PHP by Ben Drushell 2000.12.22
        $good = 1;
        $sendmail = ereg_replace("[[:space:]]+(.*)","\\1",$sendmail);
        $sendmail = ereg_replace("\|","",$sendmail);
        $MAIL = popen("$sendmail -bv $address|","r");
        while(! feof($MAIL))
        {
            $i = fgets($MAIL,255);
            if(ereg("^.+undeliverable.+",$i))
            { $good = 0; } # Sendmail thinks the email address is undeliverable
        }
        pclose($MAIL);
        return($good);
    }

    function VERIFY_VIA_DIG($address)
    {
    # perl function contributed by Bob Hurt http://www.bobhurt.com/ 2000.12.14
    # ported to PHP by Ben Drushell 2000.12.22
        $good = 0;
        list($user,$domain) = split("\@",$address);
        $DIG = popen("dig mx $domain|grep MX|grep -v ';'","r");
        while(! feof($DIG))
        {
            $i = fgets($DIG,255);
            if(ereg("MX",$i))
            { $good++; } # DIG thinks domain is good
        }
        pclose($DIG);
        return($good);
    }

    function VERIFY_EMAIL($EMAIL)
    { # Added 2000.12.22 by Ben Drushell
      # 2001.03.16 - BD - converted to regular expression to fix error where aaa@bbb.ccc.ddd failed
        $valid = 0;
        if(ereg(".+\@.+\..+",$EMAIL))
        { $valid = 1; }
        return($valid);
    }

    function VerifyFields($xml,$VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $rEmail = $T->CiaoEncode($VARS['f_EMAIL']);
        $lEmail = $T->CiaoEncode(strtolower($VARS['f_EMAIL']));

        list($alias,$domain) = split("@",trim($VARS['f_EMAIL']));
        $rDomain = $T->CiaoEncode($domain);
        $lDomain = $T->CiaoEncode(strtolower($domain));

        $tempSQL = new CiaoSQL;
        $tempSQL->clone($SQL);

        $valid = 0;
        for($counter = 1;$counter <= $CFG->optSize;$counter++)
        {
            if(($CFG->optReq[$counter] == 'r') && ($VARS['f_OPTION' . $counter] == ''))
            { $valid = 1; }
        }
        if($valid == 0)
        {
            $query = "SELECT * FROM PREFIX_verify WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
            $tempSQL->q($query);
            if($tempSQL->nextrecord())
            { $valid = 2; }
        }
        if($valid == 0)
        {
            $query = "SELECT * FROM PREFIX_list WHERE (email_id = '" . $lEmail . "' OR email_id = '" . $rEmail . "');";
            $tempSQL->q($query);
            if($tempSQL->nextrecord())
            { $valid = 3; }
        }
        if($valid == 0)
        {
            $query = "SELECT * FROM PREFIX_block WHERE block_value = '" . $lEmail . "' OR block_value = '" . $rEmail . "' OR block_value = '" . $lDomain . "' OR block_value = '" . $rDomain . "';";
            $tempSQL->q($query);
            if($tempSQL->nextrecord())
            { $valid = 4; }
        }
        if($valid == 0)
        {
            if($xml['VERIFY_VIA_SENDMAIL'] > 0 && strlen($xml['SENDMAIL']) > 0)
            { if(! $this->VERIFY_VIA_SENDMAIL($xml['SENDMAIL'],$VARS['f_EMAIL'])){ $valid = 5; } }
            if($xml['VERIFY_VIA_DIG'] > 0)
            { if(! $this->VERIFY_VIA_DIG($VARS['f_EMAIL'])){ $valid = 5; } }
            if(! $this->VERIFY_EMAIL($VARS['f_EMAIL']))
            { $valid = 5; }
        }
        return($valid);
    }

    function SECURE_FIELD($FIELD)
    { return(ereg_replace("[\!\"\#\$\%\&\'\*\+\|\\\=\`]","_",$FIELD)); }

    function showNavigation($VARS,$xml)
    { // researved for adding public (member) modules
        global $PHP_SELF;
?>
<!--p align="center"><center>
<a href="<?php
echo $PHP_SELF . "?logout=1&p=" . urlencode($VARS['p']);
if($VARS['category']!='')
{ echo "&category=" . urlencode($VARS['category']); }
if($VARS['template']!='template')
{ echo "&template=" . urlencode($VARS['template']); }
?>"><?php echo urlencode($xml['txtLogout']) ?></a>
<a href="<?php
echo $PHP_SELF . "?x=il&p=" . urlencode($VARS['p']);
if($VARS['category']!='')
{ echo "&category=" . urlencode($VARS['category']); }
if($VARS['template']!='template')
{ echo "&template=" . urlencode($VARS['template']); }
?>"><?php echo urlencode($xml['txtLink']) ?></a>
</center></p-->
<?php
    }
}
?>
