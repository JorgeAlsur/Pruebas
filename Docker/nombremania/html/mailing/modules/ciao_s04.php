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
# FILE: ciao_s04.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2001.01.20 - BD (Ben Drushell) - Fixed javascript error
#
# 2001.08.04 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#---------------------------------------------------------
?>

<?php
# SHORT DESCRIPTION
# This module is used to add and delete users.  It also
# facilitates changing passwords.
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

        $T->head($VARS);
        echo "\n<h2 align=\"center\"><font color=\"" . $T->body_title . "\">USER ADMINISTRATION</font></h2>\n";
        if($VARS['f_process'])
        {
            $errors = "";
################# NEW USER #################
            if ($VARS['f_menu'] == 0)
            {
                if ($VARS['f_email'] == '')
                { $errors = "\n<br>No user identification was entered."; }
                if ($VARS['f_password'] == '')
                { $errors = "\n<br>No user password was entered."; }
                if ($VARS['f_password'] != $VARS['f_password2'])
                { $errors = "\n<br>Password and verification password does not match."; }
                if ($errors == '')
                {
                    $tEmail = $T->CiaoEncode($VARS['f_email']);
                    $mPassword = md5(urlencode($T->CiaoEncode($VARS['f_password'])) . urlencode($tEmail));
                    $user_id = $SQL->nid("PREFIX_user->user_id");
                    $query = "INSERT INTO PREFIX_user VALUES('" . $user_id . "','" . $tEmail . "','" . $mPassword . "','" . (0 + $VARS['f_access']) . "');";
                    $SQL->locktable("PREFIX_user");
                    $SQL->q($query);
                    $SQL->unlocktable();
                    $this->HTML_SUCCESS("USER ADDED");
                } else {
                    $this->HTML_ERRORS($errors);
                }
            }
################# DELETE USER #################
            elseif($VARS['f_delete'] != '')
            {
                if($VARS['f_menu'] < 1)
                { $errors = "\n<br>One user is required."; }
                if($errors == '')
                {
                    $query = "DELETE FROM PREFIX_user WHERE user_id = " . $VARS['f_id'] . ";";
                    $SQL->locktable("PREFIX_user");
                    $SQL->q($query);
                    $SQL->unlocktable();
                    $VARS['f_menu'] = "";
                    $VARS['f_id'] = "";
                    $VARS['f_email'] = "";
                    $this->HTML_SUCCESS('USER DELETED');
                } else {
                    $this->HTML_ERRORS($errors);
                }
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

                    $query = "UPDATE PREFIX_user SET password = '" . $mPassword . "', email = '" . $T->CiaoEncode($VARS['f_email']) . "', access = " . (0 + $VARS['f_access']) . " WHERE user_id = " . $VARS['f_id'] . ";";
                    $SQL->locktable("PREFIX_user");
                    $SQL->q($query);
                    $SQL->unlocktable();

                    $this->HTML_SUCCESS('USER ID/PASSWORD UPDATED');
                } else { $this->HTML_ERRORS($errors); }
            }
        }
############### INTERACTIVE JAVASCRIPT FUNCTIONALITY ###############
?>

<script language="javascript">
<!--

function idChange()
{ // function that pops data into form for editing
    switch(document.useradm.f_menu.selectedIndex)
    {
        case 0:
            document.useradm.f_id.value = "";
            document.useradm.f_email.value = "";
            document.useradm.f_password.value = "";
            document.useradm.f_password2.value = "";
            document.useradm.f_access.checked = false;
            document.useradm.f_access_old.value = "0";
            break;
<?php
        $query = "SELECT * FROM PREFIX_user WHERE user_id != '';";
        $SQL->q($query);
        $counter = 1;
        while($SQL->nextrecord())
        {
            $user_id[$counter] = trim($SQL->f('user_id'));
            $user_email[$counter] = $T->CiaoDecode($SQL->f('email'));
?>
        case <?php echo $counter ?>:
            document.useradm.f_id.value = "<?php echo trim($SQL->f('user_id')) ?>";
            document.useradm.f_email.value = "<?php echo $user_email[$counter] ?>";
            document.useradm.f_password.value = "";
            document.useradm.f_password2.value = "";
            document.useradm.f_access_old.value = "<?php echo $SQL->f('access') ?>";
<?php
            if($SQL->f('access'))
            { echo "            document.useradm.f_access.checked = true;"; }
            else
            { echo "            document.useradm.f_access.checked = false;"; }
            echo "\n            break;";
            $counter = $counter + 1;
        }
?>

    }
} // end of javascript function

//-->
</script>

<?php
        $this->HTML_FORM($VARS,$SYS,$user_id,$user_email);
        $T->tail($VARS);
    }

    function HTML_SUCCESS($MESSAGE)
    { echo "<H2 align='center'>$MESSAGE SUCCESSFUL</H2>"; }

    function HTML_ERRORS($errors)
    { echo "<H2 align='center'>\"USER SETUP\" ERRORS</H2>$errors<br>"; }

    function HTML_FORM($VARS,$SYS,$user_id,$user_email)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $T = $SYS['T'];

?>
<center>

<table border="0" width="70%"><tr><td>
If additional personel need access to CIAO, use this
utility to create new user accounts and edit/delete
accounts.  There are two types of accounts:
<ul>
<li>Admin account
<ul>
<li>gives access to Admin Utilities + Mail Utilities.
</ul>
<li>General account
<ul><li>only gives access to Mail Utilities</ul>
</ul>
</td></tr></table>

<br>
<form name="useradm" action="ciaoadm.php" method="post">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<input type="hidden" name="f_id" value="<?php echo $VARS['f_id'] ?>">
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td colspan="2" align="center">
<select name="f_menu" onChange="idChange()">
<?php
        echo "\n <option";
        if ($VARS['f_menu'] == 0)
        { echo " selected"; }
        echo " value='0'>(New User)";
        $counter = 1;
        while ($counter <= sizeof($user_id))
        {
            echo "\n <option";
            if ($VARS['f_menu'] == $counter)
            { echo " selected"; }
            echo " value='$counter'>(" . $user_id[$counter] . ") " . $user_email[$counter];
            $counter = $counter + 1;
        }
?>

</select>
</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">User Email:</font></td><td><input type="text" name="f_email" value=""></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password:</font></td><td><input type="password" name="f_password" value="" size="8" maxlength="8"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password Verification:</font></td><td><input type="password" name="f_password2" value="" size="8" maxlength="8"></td></tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>"><input type="hidden" name="f_access_old" value="1"><input type="checkbox" name="f_access" value="1">Administrative Access</font></td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>">(<input type="checkbox" name="f_delete"> Delete Selected User)</font></td></tr>
<tr><td colspan="2" align="center"><input type="submit"></td></tr>
</table>
</form>
</center>
<?php
    }
}
?>
