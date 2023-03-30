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
# FILE: ciao_s03.php
# LOCAL VERSION: 1.0.00
# CREATED ON: 2000.10.30
# CREATED BY: Ben Drushell - http://www.technobreeze.com/
# CONTRIBUTORS:
#(date - name - brief description of enhancement)
# 2000.12.23 - BD - added code for optional verification routines in template
# 2001.01.20 - BD - Made verification messages customizable.
# 2001.01.29 - BD - Modified the saving mechanism.
# 2001.03.14 - Juliano Zabeo Pessini - Fix on line 153, changed txtInstruction to txtInstructions
# 2001.05.31 - BD - Modified template to include header/footer and required/optional fields
# 2001.06.13 - BD - Modified template for btnDeleteCat
# 2001.06.15 - BD - Modified template generator for working with categories and custom templates.
#
# 2001.08.04 - BD - updated to beta distribution: utilizes phpLib, phpmailer, phpsmtp, Ciao-SQL, and Ciao-Tools
#---------------------------------------------------------
?>

<?php 
# SHORT DESCRIPTION
# This module handles page template generation.
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

        $T->head($VARS);
        echo "\n<h2 align=\"center\"><font color=\"" . $T->body_title . "\">Public Sign-up Page Configuration</font></h2>\n";
        if ($VARS['f_process'])
        {
            if($VARS['template_choice'] == "category")
            { $template = $VARS['template_category']; }
            elseif($VARS['template_choice'] == "custom")
            { $template = $VARS['template_custom']; }
            else
            { $template = "template"; }

            $fw = fopen("modules/" . $template . ".ciao","w");
            fwrite($fw,"\n<HTML>\n<HEAD>\n");
            fwrite($fw,"<TITLE>CIAO EmailList</TITLE>\n</HEAD>\n");
            fwrite($fw,"<BODY ");
            if($VARS['page_background'] != '')
            { fwrite($fw,"BACKGROUND=\"" . trim($VARS['page_background']) . "\" "); }
            if($VARS['page_bgcolor'] != '')
            { fwrite($fw,"BGCOLOR=\"" . trim($VARS['page_bgcolor']) . "\" "); }
            if($VARS['page_text'] != '')
            { fwrite($fw,"TEXT=\"" . trim($VARS['page_text']) . "\" "); }
            if($VARS['page_link'] != '')
            {
                fwrite($fw,"LINK=\"" . trim($VARS['page_link']) . "\" ");
                fwrite($fw,"ALINK=\"" . trim($VARS['page_link']) . "\" ");
                fwrite($fw,"VLINK=\"" . trim($VARS['page_link']) . "\" ");
            }
            fwrite($fw,">\n\n<CENTER>\n");
            if($VARS['page_logo'] != '')
            { fwrite($fw,"<IMG SRC=\"" . trim($VARS['page_logo']) . "\">\n"); }
            fwrite($fw,"<#CIAO\n");

            fwrite($fw,"VERIFY_VIA_SENDMAIL=\"" . trim($VARS['VERIFY_VIA_SENDMAIL']) . "\"\n");
            fwrite($fw,"SENDMAIL=\"" . trim($VARS['SENDMAIL']) . "\"\n");
            fwrite($fw,"VERIFY_VIA_DIG=\"" . trim($VARS['VERIFY_VIA_DIG']) . "\"\n\n");

            fwrite($fw,"header=\"" . trim($VARS['header']) . "\"\n");
            fwrite($fw,"footer=\"" . trim($VARS['footer']) . "\"\n\n");

            fwrite($fw,"rgbBackground=\"" . trim($VARS['rgbBackground']) . "\"\n");
            fwrite($fw,"rgbText=\"" . trim($VARS['rgbText']) . "\"\n\n");

            fwrite($fw,"fontFace=\"" . trim($VARS['fontFace']) . "\"\n\n");

            fwrite($fw,"txtTitle=\"" . trim($VARS['txtTitle']) . "\"\n");
            fwrite($fw,"txtLink=\"" . trim($VARS['txtLink']) . "\"\n");
            fwrite($fw,"txtLogout=\"" . trim($VARS['txtLogout']) . "\"\n");
            fwrite($fw,"txtPrompt=\"" . trim($VARS['txtPrompt']) . "\"\n");
            fwrite($fw,"txtPassword=\"" . trim($VARS['txtPassword']) . "\"\n");
            fwrite($fw,"txtCategory=\"" . trim($VARS['txtCategory']) . "\"\n");
            fwrite($fw,"txtInstructions=\"" . trim($VARS['txtInstructions']) . "\"\n");
            fwrite($fw,"lblRequired=\"" . trim($VARS['lblRequired']) . "\"\n");
            fwrite($fw,"lblOptional=\"" . trim($VARS['lblOptional']) . "\"\n\n");

            fwrite($fw,"btnAdd=\"" . $VARS['btnAdd'] . "\"\n");
            fwrite($fw,"btnDelete=\"" . $VARS['btnDelete'] . "\"\n");
            fwrite($fw,"btnDeleteCat=\"" . $VARS['btnDeleteCat'] . "\"\n");
            fwrite($fw,"btnFind=\"" . $VARS['btnFind'] . "\"\n");
            fwrite($fw,"btnUpdate=\"" . $VARS['btnUpdate'] . "\"\n");
            fwrite($fw,"btnPassword=\"" . $VARS['btnPassword'] . "\"\n\n");

            fwrite($fw,"errRequired=\"" . trim($VARS['errRequired']) . "\"\n");
            fwrite($fw,"errVerify=\"" . trim($VARS['errVerify']) . "\"\n");
            fwrite($fw,"errFind=\"" . trim($VARS['errFind']) . "\"\n");
            fwrite($fw,"errAddVerify=\"" . trim($VARS['errAddVerify']) . "\"\n");
            fwrite($fw,"errAddList=\"" . trim($VARS['errAddList']) . "\"\n");
            fwrite($fw,"errAddEmail=\"" . trim($VARS['errAddEmail']) . "\"\n\n");

            fwrite($fw,"okVerify=\"" . trim($VARS['okVerify']) . "\"\n");
            fwrite($fw,"okUpdate=\"" . trim($VARS['okUpdate']) . "\"\n");
            fwrite($fw,"okDelete=\"" . trim($VARS['okDelete']) . "\"\n");
            fwrite($fw,"okFind=\"" . trim($VARS['okFind']) . "\"\n");
            fwrite($fw,"okAdd=\"" . trim($VARS['okAdd']) . "\"\n");
            fwrite($fw,"okLogout=\"" . trim($VARS['okLogout']) . "\"\n");
            fwrite($fw,"okPassword=\"" . trim($VARS['okPassword']) . "\"\n\n");

            fwrite($fw,"msgSignup=\"" . ereg_replace("=","--",ereg_replace("\"","'",trim($VARS['msgSignup']))) . "\"\n");
            fwrite($fw,"msgImport=\"" . ereg_replace("=","--",ereg_replace("\"","'",trim($VARS['msgImport']))) . "\"\n");
            fwrite($fw,"msgPassword=\"" . ereg_replace("=","--",ereg_replace("\"","'",trim($VARS['msgPassword']))) . "\"\n\n");

            fwrite($fw,"#>\n</CENTER>\n</BODY>\n</HTML>\n");

            fclose($fw);
            $this->HTML_SUCCESS($VARS);
        } else {
            $this->HTML_FORM($VARS,$SYS);
        }
        $T->tail($VARS);
    }

    function HTML_SUCCESS($VARS)
    {
?>
<H2 align="center">"PAGE SETUP" SUCCESSFUL</H2>
<big><b><center><a href="ciaoadm.php?x=s04&u=<?php echo urlencode($VARS['u']) ?>&p=<?php echo urlencode($VARS['p']) ?>">Click Here To Continue With Setup</a></center></b></big>
<?php
        if($VARS['template_choice'] == "category")
        {
?>
<br><br><big><b><center>NOTE: Link to category-template via "ciao.php?category=<?php echo $VARS['template_category'] ?>"</center></b></big>
<?php
        }
        elseif($VARS['template_choice'] == "custom")
        {
?>
<br><br><big><b><center>NOTE: Link to custom-template via "ciao.php?template=<?php echo $VARS['template_custom'] ?>"</center></b></big>
<?php
        }
    }

    function HTML_ERRORS($errors)
    {
?>
<H2 align="center">"PAGE SETUP" ERRORS</H2>
<?php echo $errors ?><br>
<?php
    }

    function HTML_FORM($VARS,$SYS)
    {
        # PHP3 compatibility; $array[]->method() calls are not PHP3 compatible
        $SQL = $SYS['SQL'];
        $T = $SYS['T'];
        $CFG = $SYS['CFG'];

        list($html_start, $html_end) = $T->PARSE_XML_DOC("template",$xml);

        if($xml['rgbBackground'] == "")
        { $xml['rgbBackground'] = "#ffffff"; }
        if($xml['rgbText'] == "")
        { $xml['rgbText'] = "#000000"; }

        if($xml['txtTitle'] == "")
        { $xml['txtTitle'] = "Welcome to the email list."; }
        if($xml['txtLink'] == "")
        { $xml['txtLink'] = "My Profile"; }
        if($xml['txtLogout'] == "")
        { $xml['txtLogout'] = "Logout"; }
        if($xml['txtPrompt'] == "")
        { $xml['txtPrompt'] = "Please enter your e-mail address here:"; }
        if($xml['txtPassword'] == "")
        { $xml['txtPassword'] = "Password:"; }
        if($xml['txtCategory'] == "")
        { $xml['txtCategory'] = "CATEGORIES"; }
        if($xml['txtInstructions'] == "")
        { $xml['txtInstructions'] = "(ONLY E-MAIL ADDRESS & PASSWORD ARE NEEDED FOR LOGGING-IN)"; }
        if($xml['lblRequired'] == "")
        { $xml['lblRequired'] = "required"; }
        if($xml['lblOptional'] == "")
        { $xml['lblOptional'] = "optional"; }

        if($xml['btnAdd'] == "")
        { $xml['btnAdd'] = " Subscribe  "; }
        if($xml['btnDelete'] == "")
        { $xml['btnDelete'] = "Unsubscribe"; }
        if($xml['btnDeleteCat'] == "")
        { $xml['btnDeleteCat'] = "Unsubscribe from Category"; }
        if($xml['btnFind'] == "")
        { $xml['btnFind'] = "  Login  "; }
        if($xml['btnUpdate'] == "")
        { $xml['btnUpdate'] = "Update Info"; }
        if($xml['btnPassword'] == "")
        { $xml['btnPassword'] = "Forgot Password"; }

        if($xml['errRequired'] == "")
        { $xml['errRequired'] = "ERROR: Please check that all required fields are filled."; }
        if($xml['errVerify'] == "")
        { $xml['errVerify'] = "ERROR: Unable to verify.<br>Notify site administrator."; }
        if($xml['errFind'] == "")
        { $xml['errFind'] = "ERROR: E-mail address was not found."; }
        if($xml['errAddVerify'] == "")
        { $xml['errAddVerify'] = "ERROR: This e-mail address is waiting to be verified.<br>Please check your e-mail."; }
        if($xml['errAddList'] == "")
        { $xml['errAddList'] = "ERROR: This e-mail address is already subscribed."; }
        if($xml['errAddEmail'] == "")
        { $xml['errAddEmail'] = "ERROR: This is an invalid e-mail address."; }

        if($xml['okVerify'] == "")
        { $xml['okVerify'] = "Your e-mail address has been verified and added to the email list."; }
        if($xml['okUpdate'] == "")
        { $xml['okUpdate'] = "Data has been updated."; }
        if($xml['okDelete'] == "")
        { $xml['okDelete'] = "The e-mail address has been unsubscribed."; }
        if($xml['okFind'] == "")
        { $xml['okFind'] = "You have successfully logged-in."; }
        if($xml['okAdd'] == "")
        { $xml['okAdd'] = "Request received. Please check your e-mail for a verification link code."; }
        if($xml['okLogout'] == "")
        { $xml['okLogout'] = "You have successfully logged-out."; }
        if($xml['okPassword'] == "")
        { $xml['okPassword'] = "Your password is being emailed to you."; }

        if($xml['msgSignup'] == "")
        {
            $xml['msgSignup'] = "E-mail Address: #EMAIL#\n\n";
            $xml['msgSignup'] .= "Request submitted by\n";
            $xml['msgSignup'] .= "Remote IP: #REMOTE_IP#\n";
            $xml['msgSignup'] .= "Remote Host: #REMOTE_HOST#\n";
            $xml['msgSignup'] .= "Browser: #BROWSER#\n\n";
            $xml['msgSignup'] .= "RE: Request Verification\n\n";
            $xml['msgSignup'] .= "This is an automated response to verify a request for \n";
            $xml['msgSignup'] .= "joining the e-mail list at: #URL#\n";
            $xml['msgSignup'] .= "If this message has been sent in error, please disregard.\n\n";
            $xml['msgSignup'] .= "TO JOIN THE E-MAIL LIST, USE THE FOLLOWING LINK.\n";
            $xml['msgSignup'] .= "#VERIFYURL#";
            $xml['msgSignup'] .= "\n\n";
        }
        if($xml['msgImport'] == "")
        {
            $xml['msgImport'] = "E-mail Address: #EMAIL#\n\n";
            $xml['msgImport'] .= "RE: Request Verification\n\n";
            $xml['msgImport'] .= "This is an automated notification.\n";
            $xml['msgImport'] .= "#URL#\nis updating to a new e-mail list system.\n";
            $xml['msgImport'] .= "If you want to continue being on this e-mail list,\n";
            $xml['msgImport'] .= "click on the following link.\n";
            $xml['msgImport'] .= "#VERIFYURL#";
            $xml['msgImport'] .= "\n\nIf you do NOT want to be on this e-mail list,";
            $xml['msgImport'] .= "\ndisregard message. Be assured that your e-mail";
            $xml['msgImport'] .= "\naddress will not be transfered to the new list,";
            $xml['msgImport'] .= "\nunless you click on the verification link.";
            $xml['msgImport'] .= "\n\n";
        }
        if($xml['msgPassword'] == "")
        {
            $xml['msgPassword'] = "RE: Password Request\n\n";
            $xml['msgPassword'] .= "Your password has been sent to you by the request of:\n";
            $xml['msgPassword'] .= "Remote IP: #REMOTE_IP#\n";
            $xml['msgPassword'] .= "Remote Host: #REMOTE_HOST#\n";
            $xml['msgPassword'] .= "Browser: #BROWSER#\n\n";
            $xml['msgPassword'] .= "E-mail Address: #EMAIL#\n";
            $xml['msgPassword'] .= "Password: #PASSWORD#\n\n";
            $xml['msgPassword'] .= "\n";
        }
?>
<script language="javascript">
<!--
function colorchart()
{ var win = window.open("ciaocolor.html","","width=150,height=300,scrollbars"); }
// -->
</script>

<center>
<table align="center" border="0" width="70%"><tr><td>
This creates ".ciao" template files that is used to generate the
sign-up page the public views on your website.  If you want
a more advanced customization, you can download the ".ciao" file,
add/edit html code, and re-upload the file.<br>
<b><small>(NOTE: Category list will NOT appear until a subscriber has verified.)</small></b>
</td></tr></table>
</center>

<form action="ciaoadm.php" method="post">
<input type="hidden" name="f_process" value="1">
<input type="hidden" name="u" value="<?php echo $VARS['u'] ?>">
<input type="hidden" name="p" value="<?php echo $VARS['p'] ?>">
<input type="hidden" name="x" value="<?php echo $VARS['x'] ?>">
<center>
<?php
        if(file_exists("modules/template.ciao"))
        {
?>

<h3 align="center"><font color="<?php echo $T->body_title ?>">File Name</font></h3>
<br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">
<input type="radio" name="template_choice" value="template" CHECKED>Main "template.ciao" File
</font></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">
<input type="radio" name="template_choice" value="category">Category:
<select name="template_category">

<?php
            $query = "SELECT * FROM PREFIX_category WHERE cat_id != '';";
            $SQL->q($query);
            while($SQL->nextrecord())
            {
?>

<option value="<?php echo trim($SQL->f('cat_id')) ?>">(<?php echo trim($SQL->f('cat_id')) ?>) <?php echo trim($SQL->f('cat_name')) ?>

<?php
            }
?>

</select>
</font></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">
<input type="radio" name="template_choice" value="custom">Custom File:
modules/<input type="text" name="template_custom">.ciao
</font></td></tr>
</table>
<br><br><br>

<?php
        }
?>

<h3 align="center"><font color="<?php echo $T->body_title ?>">Page Properties</font></h3>
<br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">Header File:</td>
<td><input type="text" name="header" value="<?php echo $xml['header'] ?>">
</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Footer File:</td>
<td><input type="text" name="footer" value="<?php echo $xml['footer'] ?>">
</td></tr>

<tr><td colspan="2"><font color="<?php echo $T->table_Text ?>">OR USE</font></td></tr>

<tr><td><font color="<?php echo $T->table_Text ?>">Background Color:</td>
<td><input type="text" name="page_bgcolor" size="7" maxlength="7">
(<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Background Image URL:</td><td><input type="text" name="page_background"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Text Color:</td>
<td><input type="text" name="page_text" size="7" maxlength="7">
(<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Link Color:</td>
<td><input type="text" name="page_link" size="7" maxlength="7">
(<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Image Logo URL:</td><td><input type="text" name="page_logo"></td></tr>
</table>
<br><br><br>
<h3 align="center"><font color="<?php echo $T->body_title ?>">Table Properties</font></h3>
<br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="5" cellspacing="0">
<tr><td><font color="<?php echo $T->table_Text ?>">Background Color:</font></td>
<td><input type="text" name="rgbBackground" value="<?php echo $xml['rgbBackground'] ?>" size="7" maxlength="7">
(<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Text Color:</font></td>
<td><input type="text" name="rgbText" value="<?php echo $xml['rgbText'] ?>" size="7" maxlength="7">
(<a href="javascript:colorchart()" style="color:<?php echo $T->table_Link ?>"><font color="<?php echo $T->table_Link ?>">View Color Chart</font></a>)
</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Font Type:</font></td>
<td><input type="text" name="fontFace" value="<?php echo $xml['fontFace'] ?>">
</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">
<?php  if($xml['VERIFY_VIA_SENDMAIL']) { ?>
<input type="checkbox" name="VERIFY_VIA_SENDMAIL" VALUE="1" CHECKED>
<?php  } else { ?>
<input type="checkbox" name="VERIFY_VIA_SENDMAIL" VALUE="1">
<?php  } ?>
<font color="<?php echo $T->table_Text ?>">Verify email address via SENDMAIL (*nix OS's only)</font>
</td></tr>
<tr><td>&nbsp;</td>
<td>
<font color="<?php echo $T->table_Text ?>">Sendmail Location:</font>
<input type="text" name="SENDMAIL" value="<?php echo $xml['SENDMAIL'] ?>">
</td></tr>
<tr><td colspan="2">
<?php  if($xml['VERIFY_VIA_DIG']) { ?>
<input type="checkbox" name="VERIFY_VIA_DIG" VALUE="1" CHECKED>
<?php  } else { ?>
<input type="checkbox" name="VERIFY_VIA_DIG" VALUE="1">
<?php  } ?>
<font color="<?php echo $T->table_Text ?>">Verify email address domain via DIG (*nix OS's only)</font>
</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Welcome Message:</font></td><td><input type="text" name="txtTitle" value="<?php echo $xml['txtTitle'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Profile Link:</font></td><td><input type="text" name="txtLink" value="<?php echo $xml['txtLink'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Email Prompt:</font></td><td><input type="text" name="txtPrompt" value="<?php echo $xml['txtPrompt'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Password Prompt:</font></td><td><input type="text" name="txtPassword" value="<?php echo $xml['txtPassword'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Category Title:</font></td><td><input type="text" name="txtCategory" value="<?php echo $xml['txtCategory'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Instructions:</font></td><td><input type="text" name="txtInstructions" value="<?php echo $xml['txtInstructions'] ?>"></td></tr>

<tr><td><font color="<?php echo $T->table_Text ?>">"require" label:</font></td><td><input type="text" name="lblRequired" value="<?php echo $xml['lblRequired'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">"optional" label:</font></td><td><input type="text" name="lblOptional" value="<?php echo $xml['lblOptional'] ?>"></td></tr>

<tr><td colspan="2" align="center"><b><font color="<?php echo $T->table_Text ?>">Button Labels</font></b></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Subscribe Button:</font></td><td><input type="text" name="btnAdd" value="<?php echo $xml['btnAdd'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Unsubscribe Button:</font></td><td><input type="text" name="btnDelete" value="<?php echo $xml['btnDelete'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Unsubscribe from Category Button:</font></td><td><input type="text" name="btnDeleteCat" value="<?php echo $xml['btnDeleteCat'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Login Button:</font></td><td><input type="text" name="btnFind" value="<?php echo $xml['btnFind'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Logout Button:</font></td><td><input type="text" name="txtLogout" value="<?php echo $xml['txtLogout'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Update Button:</font></td><td><input type="text" name="btnUpdate" value="<?php echo $xml['btnUpdate'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Forgotten Password Button:</font></td><td><input type="text" name="btnPassword" value="<?php echo $xml['btnPassword'] ?>"></td></tr>
<tr><td colspan="2" align="center"><b><font color="<?php echo $T->table_Text ?>">Error Messages</font></b></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Required Fields:</font></td><td><input type="text" name="errRequired" value="<?php echo $xml['errRequired'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Verification Error:</font></td><td><input type="text" name="errVerify" value="<?php echo $xml['errVerify'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Find Error:</font></td><td><input type="text" name="errFind" value="<?php echo $xml['errFind'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Waiting Verification:</font></td><td><input type="text" name="errAddVerify" value="<?php echo $xml['errAddVerify'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Already In List:</font></td><td><input type="text" name="errAddList" value="<?php echo $xml['errAddList'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">Invalid Email Address:</font></td><td><input type="text" name="errAddEmail" value="<?php echo $xml['errAddEmail'] ?>"></td></tr>
<tr><td colspan="2" align="center"><b><font color="<?php echo $T->table_Text ?>">OK Messages</font></b></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">OK Verified:</font></td><td><input type="text" name="okVerify" value="<?php echo $xml['okVerify'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">OK Updated:</font></td><td><input type="text" name="okUpdate" value="<?php echo $xml['okUpdate'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">OK Unsubscribe:</font></td><td><input type="text" name="okDelete" value="<?php echo $xml['okDelete'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">OK Login:</font></td><td><input type="text" name="okFind" value="<?php echo $xml['okFind'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">OK Subscribe:</font></td><td><input type="text" name="okAdd" value="<?php echo $xml['okAdd'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">OK Logout:</font></td><td><input type="text" name="okLogout" value="<?php echo $xml['okLogout'] ?>"></td></tr>
<tr><td><font color="<?php echo $T->table_Text ?>">OK Forgotten Password:</font></td><td><input type="text" name="okPassword" value="<?php echo $xml['okPassword'] ?>"></td></tr>
<tr><td colspan="2" align="center"><b><font color="<?php echo $T->table_Text ?>">E-mail Verification Messages</font></b></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Sign-up Message:</font></td><td><textarea name="msgSignup" rows="10" cols="50"><?php echo $xml['msgSignup'] ?></textarea></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Import Message:</font></td><td><textarea name="msgImport" rows="10" cols="50"><?php echo $xml['msgImport'] ?></textarea></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Forgotten Password Message:</font></td><td><textarea name="msgPassword" rows="10" cols="50"><?php echo $xml['msgPassword'] ?></textarea></td></tr>
<tr><td valign="top"><font color="<?php echo $T->table_Text ?>">Data Inserts:</font></td><td><font color="<?php echo $T->table_Text ?>">
#VERIFYURL# => Verify URL link subscriber uses to be added to list. (required)<br>
#URL# => URL to directory Ciao EmaiList Manager resides in.<br>
#EMAIL# => Email address being subscribed.<br>
#REMOTE_IP# => IP address of viewer submitting request.<br>
#REMOTE_HOST# => Remote host address of viewer submitting request.<br>
#BROWSER# => Browser information of the viewer submitting request.<br>
#PASSWORD# => Password for forgotten password message.
</font></td></tr>
</table>
<br><br>
<table align="center" bgcolor="<?php echo $T->table_bgcolor ?>" cellpadding="1" cellspacing="0">
<tr><td><input type="submit" value="SUBMIT DATA"></td></tr>
</table>
</form>
</center>
<?php
    }
}
?>
