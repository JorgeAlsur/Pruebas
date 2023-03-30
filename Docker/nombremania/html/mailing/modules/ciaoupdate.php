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
# FILE: ciaoupdate.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2001.04.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.08.05 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#
# 2001.09.22 - BD - included database fix so that cat_id in catlist matches cat_id in _category
#---------------------------------------------------------

# SHORT DESCRIPTION
# This module is used to update old email list database to
# new design. It should only be run once.
#---------------------------------------------------------

class update
{
    function update_1_0_07($VARS,$SYS)
    {
       $SQL = $SYS['SQL'];
       $query = "ALTER TABLE PREFIX_catlist MODIFY cat_id CHAR(4);";
       $SQL->locktable("PREFIX_catlist");
       $SQL->translate($query);
       $SQL->unlocktable();

       $fw = fopen("modules/ciao_1_0_07.txt","w");
       fwrite($fw,"Ciao EmailList Manager v1.0.07 (beta) - database updated\n");
       fclose($fw);
    }
}
?>
