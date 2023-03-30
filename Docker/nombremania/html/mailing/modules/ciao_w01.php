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
#---------------------------------------------------------
# FILE: ciao_w01.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.12.29
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - means of contacting - brief description of enhancement)
# 2001.06.13 - BD (Ben Drushell) - depricated crypt in favor of md5 function
#
# 2001.08.05 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module is used to edit personal user data, passwords, etc.
#---------------------------------------------------------
# FORM VARIABLE DEFINITIONS
# f_menu - list of user id's
# f_id - user id (computer generated)
# f_email - user email
# f_password - new password or password for changing email
# f_password2 - verifies new password
# f_oldpassword - used for changing passwords
# f_delete - delete user
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
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $T->head($VARS);
        echo "\n<h2 align=\"center\"><font color=\"" . $T->body_title . "\">" . $T->CiaoDecode($VARS['u']) . " PROFILE</font></h2>\n";
        if($VARS['f_process'])
        {
            $errors = "";
            if($VARS['f_btnEdit'] != '')
            {
################# DELETE USER #################
                if($VARS['f_delete'] != '')
                {
                    $query = "DELETE FROM PREFIX_user WHERE user_id = " . $VARS['f_id'] . ";";
                    $SQL->locktable("PREFIX_user");
                    $SQL->q($query);
                    $SQL->unlocktable();
                    $VARS['f_id'] = "";
                    $VARS['f_email'] = "";
                    $this->HTML_SUCCESS('USER DELETED');
                }
################# CHANGE USER'S PASSWORD/EMAIL #################
                else
                {
                    if($VARS['f_email'] == '')
                    { $errors = "\n<br>No user email address was entered."; }
                    if($VARS['f_password'] == '')
                    { $errors .= "\n<br>New password is blank."; }
                    if($VARS['f_password'] != $VARS['f_password2'])
                    { $errors .= "\n<br>New password mismatch. Make sure that the new password and its verification match."; }
                    if($errors == '')
                    {
                        $mPassword = md5(urlencode($T->CiaoEncode($VARS['f_password'])) . urlencode($T->CiaoEncode($VARS['f_email'])));

                        $query = "UPDATE PREFIX_user SET ";
                        $query .= "password = '" . $mPassword . "', ";
                        $query .= "email = '" . $T->CiaoEncode($VARS['f_email']) . "' ";
                        $query .= "WHERE user_id = " . $VARS['f_id'] . ";";
                        $SQL->locktable("PREFIX_user");
                        $SQL->q($query);
                        $SQL->unlocktable();
                        $this->HTML_SUCCESS('PROFILE UPDATED');
                    } else { $this->HTML_ERRORS($errors); }
                }
            }
        }
        $this->HTML_FORM($VARS,$SYS);
        $T->tail($VARS);
    }

    function HTML_SUCCESS($MESSAGE)
    { echo "<H2 align='center'>$MESSAGE SUCCESSFUL</H2>"; }

    function HTML_ERRORS($errors)
    { echo "<H2 align='center'>\"USER SETUP\" ERRORS</H2>$errors<br>"; }

    function HTML_FORM($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        $query = "SELECT * FROM PREFIX_user WHERE email = '" . $VARS['u'] . "';";
        $SQL->q($query);
        $SQL->nextrecord();
        $user = $SQL->Record;
?>
<center>

<table border="0" align="center"><tr><td>
Use this interface to edit your staff profile.
</td></tr></table>

<br>
<form name="useradm" action="ciaoadm.php" method="post">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="f_id" value="<?php echo $user['user_id'] ?>">

<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">User Email:</font></td><td><input type="text" name="f_email" value="<?php echo $T->CiaoDecode($user['email']) ?>"></font></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password:</font></td><td><input type="password" name="f_password" value=""></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password Verification:</font></td><td><input type="password" name="f_password2" value=""></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>">(<input type="checkbox" name="f_delete"> Delete Selected User)</font></td></tr>
<tr><td colspan="2" align="center"><input type="submit" name="f_btnEdit" value="Edit Data"></td></tr>
</table>

</form>
</center>
<?php
    }
}
?>
