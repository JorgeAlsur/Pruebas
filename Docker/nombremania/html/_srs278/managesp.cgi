#!/usr/bin/perl

#       .Copyright (C)  1999-2000 TUCOWS.com Inc.
#       .Created:       11/19/1999
#       .Contactid:     <admin@opensrs.org>
#       .Url:           http://www.opensrs.org
#       .Originally Developed by:
#                       VPOP Technologies, Inc. for Tucows/OpenSRS
#       .Authors:       Joe McDonald, Tom McDonald, Matt Reimer, Brad Hilton,
#                       Daniel Manley, Gennady Krizhevsky, John Jerkovic
#
#
#       This program is free software; you can redistribute it and/or
#       modify it under the terms of the GNU Lesser General Public
#       License as published by the Free Software Foundation; either
#       version 2.1 of the License, or (at your option) any later version.
#
#       This program is distributed in the hope that it will be useful, but
#       WITHOUT ANY WARRANTY; without even the implied warranty of
#       MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
#       Lesser General Public License for more details.
#
#       You should have received a copy of the GNU Lesser General Public
#       License along with this program; if not, write to the Free Software
#       Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA



# global defines
use vars qw(
	    %in %contact_types %actions $XML_Client %cookies $action 
	    $authentication $cgi $path_templates $flag_header_sent
	    $reg_username $reg_password $reg_domain $cookie $domain_count 
	    $reg_permission $reg_f_owner $expiredate $last_access_time 
	    $last_ip %contact_keys $waiting_request $dns_errors
            $COOKIE_KEY $reg_domain_race_obj %enctypes $T_EXPIRED $T_EXPIRING $t_mode $notice_days
            $UNIVERSAL_ENCODING_TYPE %whois_rsp_info $capabilities %unauthenticated_actions
	   );
( %in, %contact_types, %actions, $XML_Client, %cookies, $action, 
	    $authentication, $cgi, $path_templates, $flag_header_sent,
	    $reg_username, $reg_password, $reg_domain, $cookie, $domain_count, 
	    $reg_permission, $reg_f_owner, $expiredate, $last_access_time, 
	    $last_ip, %contact_keys, $waiting_request, $reg_domain_race_obj,
	    %whois_rsp_info, $capabilities, $dns_errors
	   ) = ();

# pull in conf file with defined values
# XXX NOTE XXX Update this configuration file
BEGIN {
    do "/home/webs/nombremania.com/opensrs-client-2.78/etc/OpenSRS.conf";

#    do "<path_to_conf_file>/OpenSRS.conf";
}

use lib $PATH_LIB;
use CGI ':cgi-lib';
use strict;

use Time::Local;
use OpenSRS::XML_Client qw(:default);
use OpenSRS::Util::America qw(build_app_purpose_list);
use RACE;
RACE::Initialise(%RACESETTINGS);
RACE::UseRace($USE_RACE);
use Data::Dumper;
use OpenSRS::Util::Common qw(make_navbar);

# initialize global defines
$cgi = $ENV{SCRIPT_NAME};
$path_templates = "$PATH_TEMPLATES/manage";
$COOKIE_KEY = $TEST_SERVER?"REGISTRANT_KEY":"REGISTRANT_LIVE_KEY";
$flag_header_sent = 0;	# whether html header has been sent
%in = ();
$reg_username = "";
$reg_password = "";


$reg_domain = "";
$reg_domain_race_obj = undef;
$cookie = "";
$domain_count = undef;
$reg_permission = undef;
$reg_f_owner = undef;
$expiredate = undef;
$last_access_time = undef;
$last_ip = undef;
$waiting_request = "";
$capabilities = undef;
$dns_errors = undef;

$T_EXPIRING = 1; # there are domains to expire in $notice_days days
$T_EXPIRED  = 2; # there are expired domains
$t_mode = undef; # can be 0, $T_EXPIRING, $T_EXPIRED or ($T_EXPIRING | $T_EXPIRED)
$notice_days  = $MANAGE{ notice_days } ? $MANAGE{ notice_days } : 60;

# list of contact types

# horaciod
%contact_types = (
		  owner => 'Organizaci&oacute;n',
		  admin => 'Administraci&oacute;n',
		  billing => 'Facturaci&oacute;n',
		  tech => 'T&eacute;cnico',
		 );         

%contact_keys = (
    	    	 first_name => undef,
    	    	 last_name => undef,
    	    	 address1 => undef,
    	    	 address2 => undef,
    	    	 address3 => undef,
    	    	 city => undef,
    	    	 state => undef,
    	    	 postal_code => undef,
    	    	 country => undef,
    	    	 email => undef,
    	    	 url => undef,
    	    	 fax => undef,
    	    	 phone => undef,
    	    	 org_name => undef,
    	    	);

# secure actions; require valid cookie
%actions = (
	    modify_contact => undef,
	    do_modify_contact => undef,
	    do_modify_org_contact_de => undef,
	    revoke_registrant_changes => undef,
	    
	    modify_nameservers => undef,
	    do_modify_nameservers => undef,
	    add_nameserver => undef,
	    
	    manage_nameservers => undef,
	    do_manage_nameserver => undef,
	    
	    do_create_nameserver => undef,
	    
	    manage_subuser => undef,
	    do_manage_subuser => undef,
	    delete_subuser => undef,
	    
	    view_domains => undef,
	    manage_domain => undef,
	    
	    manage_profile => undef,
	    
	    change_password => undef,
	    do_change_password => undef,
	    change_ownership => undef,
	    do_change_ownership => undef,
	    
	    view_waiting_history => undef,
            get_expire_domains => undef,

	    whois_rsp_info => undef,
	    set_whois_rsp_info => undef,

	    send_password => undef,

	    domain_locking => undef,

	    modify_domain_extras => undef,
	    do_modify_domain_extras => undef,
	   );

%unauthenticated_actions = (
	    login	    => undef,
	    logout	    => undef,
	    send_password   => undef,
);


start_up();

$XML_Client = new OpenSRS::XML_Client(%OPENSRS);
$XML_Client->login;

# read in the form data
ReadParse(\%in);

%cookies = GetCookies();

$action = $in{action};

#-----------------------------------------------------
# perform necessary actions

# a few actions are allowed without authentication.
if ( $action and exists $unauthenticated_actions{$action} ) {
    no strict 'refs';

    &$action();
    exit;
}

# for all other actions, do validate() (grab cookie if it exists)
# if validate() fails, send them to the low-access menu
$authentication = validate();

################################################
### At this point, the following variables will be set if they logged in:
### $user_object,$user_id,$profile,$post_permission

# show them the login page if they don't have a valid cookie
if (not $authentication) {
    show_login();

# no action was passed but they have a valid cookie
} elsif (not $action) {
    main_menu();

# they asked for a valid action
} elsif (exists $actions{$action}) {
    if(($action eq "get_expire_domains") && ($MANAGE{allow_renewals} == 0)) {
	main_menu("Invalid action: $action");
        exit;
    }    
    no strict "refs";
    &$action();
    use strict;

# they gave us an invalid command
} else {
    main_menu("Invalid action: $action");
}

$XML_Client->logout;

exit;

sub start_up {

    if ($MANAGE{debug}) {
	# print error to the page
	select (STDOUT); $| = 1;
	open (STDERR, ">&STDOUT") or die "Can't dump stdout: $!\n";
	select (STDERR); $| = 1;
	select (STDOUT);
    }
}

# show login page for non-secure users
sub show_login {
    my $message = shift;

    my (%HTML);
    $HTML{CGI} = $cgi;
    if ( defined $message and $message ) {
	$HTML{MESSAGE} = qq(<font color="red">$message</font><br><br>);
    } else {
	$HTML{MESSAGE} = "";
    }

    print_form("$path_templates/login.html",\%HTML,'single');

}

# show main page for secure users
sub main_menu {

    my (%HTML, $key);
    my $message = shift;
    my $billing_con_name = "Billing";
    if($reg_domain =~ /de$/) {
	$billing_con_name = "Zone";
    }

    # build front page per user's permissions

    my %GRANT = (
	f_modify_nameservers	=> "<a href=\"$cgi?action=modify_nameservers\">Manage Name Servers</a>",
	f_modify_owner		=> "<a href=\"$cgi?action=modify_contact&type=owner\">Organization Contact</a>",
	f_modify_admin		=> "<a href=\"$cgi?action=modify_contact&type=admin\">Admin Contact</a>",
	f_modify_billing	=> "<a href=\"$cgi?action=modify_contact&type=billing\">$billing_con_name Contact</a>",
	f_modify_tech		=> "<a href=\"$cgi?action=modify_contact&type=tech\">Technical Contact</a>",
	sub_user		=> "<a href=\"$cgi?action=manage_profile\">Manage Profile</a>",
	f_modify_whois_rsp_info	=> "<a href=\"$cgi?action=whois_rsp_info\">Reseller Contact</a>",
	domain_locking		=> "<a href=\"$cgi?action=domain_locking\">Domain Locking</a>",
	f_modify_domain_extras	=> "<a href=\"$cgi?action=modify_domain_extras\">Domain Extras</a>",
    );
    
    my %DENY = (
		f_modify_nameservers => "Manage Name Servers",
		f_modify_owner => "Organization Contact",
		f_modify_admin => "Admin Contact",
		f_modify_billing => "$billing_con_name Contact",
		f_modify_tech => "Technical Contact",
		sub_user => "Manage Profile",
		f_modify_whois_rsp_info	=> "Reseller Contact",
		domain_locking => "Domain Locking",
		f_modify_domain_extras => "Domain Extras",
	       );

    # if user is the owner of the domain, give them full permissions
    if ($reg_f_owner) {
	foreach $key (keys %GRANT) {
	    $HTML{$key} = $GRANT{$key};
	}
	# otherwise, check their permission level against %PERMISSIONS
    } else {
	foreach $key (keys %GRANT) {
	    if ($reg_f_owner or ($reg_permission & $PERMISSIONS{$key})) {
		$HTML{$key} = $GRANT{$key};
	    } else {
		$HTML{$key} = $DENY{$key};
	    }
	}
    }

    $HTML{whois_rsp_info} = $GRANT{whois_rsp_info};
    $HTML{domain_locking} = $GRANT{domain_locking};
    
    #
    # .ca domains don't have a billing contact.
    #
    if ($reg_domain =~ /ca$/)
    {
    	$HTML{f_modify_billing} = "$DENY{f_modify_billing} (CIRA uses the Administrative Contact for Billing)";
    }

    # if no extra domain info. to be displayed, do not show the link
    #for .de there are dns errors also
    if ( ! $capabilities->{domain_extras}  and ! $dns_errors) {
	$HTML{ f_modify_domain_extras } = "$DENY{ f_modify_domain_extras }"; 
    }

    # not all TLDs support locking
    if ( not $reg_domain =~ /$OPENSRS{ TLDS_SUPPORTING_LOCKING  }/i) {
	$HTML{ domain_locking } = "$DENY{ domain_locking } (TLD does not support locking)";
    } elsif ( not $reg_f_owner ) {
	$HTML{ domain_locking } = "$DENY{ domain_locking } (Can only be modified by the owner of the domain)";
    }

    # .uk domains can't have their owner information changed,
    # so only show the organization paragraph if the domain
    # is not .uk
    if ( $reg_domain !~ /uk$/ ) {
	$HTML{ f_modify_owner } = <<EOF;
<STRONG>$HTML{ f_modify_owner }</STRONG>
<BR>
This is information about the company or entity which owns the domain name
you are managing.  Change company information here.
<BR><BR>
EOF

    } else {
        $HTML{f_modify_owner} = <<EOF;
<STRONG>$DENY{f_modify_owner}</STRONG>
<BR>
This is information about the company or entity which owns the domain name
you are managing.  Change company information here. <strong>NOTE </strong>(for .uk domains): 
An Organization name change is effectively a Registrant Name Change; to do 
this, please refer to your Nominet Domain Certificate.
<BR><BR>
EOF
    }

    if ($last_access_time) {
	my $human_time = scalar localtime($last_access_time);
	$HTML{LAST_ACCESS} = "<br>&Uacute;ltimo acceso: $human_time";
	if ($last_ip) {
	    $HTML{LAST_ACCESS} .= " desde $last_ip";
	}
    }

    if ( not $reg_f_owner ) {
        $HTML{SUB_USER} = '<br><font color="red">Logueado como un Sub-Usuario . Cambios de Propiedad Deshabilitados.</font>';
    }
    
    $HTML{MESSAGE} = $message ? "<font color=red><b><br>$message<br></b></font>\n" : "";
    $HTML{DNS_ERRORS} = $dns_errors ? "<font color=red><b><br>This domain is under a 30 day restriction and needs to be validated.<br></b></font>\n" : "";
    $HTML{CGI} = $cgi;
    $HTML{reg_username} = $reg_username;

    print_form("$path_templates/main_menu.html",\%HTML);
}

# show subuser info
sub manage_subuser {

    my (%HTML,$perm);

    my ($sub_id,$sub_username,$sub_permission) = get_subuser();
    $HTML{CGI} = $cgi;
    $HTML{sub_id} = $sub_id;
    $HTML{sub_username} = $sub_username;

    foreach $perm (keys %PERMISSIONS) {
	if ($sub_permission & $PERMISSIONS{$perm}) {
	    $HTML{"${perm}_1"} = "CHECKED";
	} else {
	    $HTML{"${perm}_0"} = "CHECKED";
	}
    }

    print_form("$path_templates/manage_subuser.html",\%HTML);

}

# process data for subuser modifications
sub do_manage_subuser {

    my ($response,$perm);

    my $sub_username = $in{sub_username};
    my $sub_password = $in{sub_password};
    my $sub_password2 = $in{sub_password2};
    my $sub_id = $in{sub_id};
    #horaciod
    if (not $sub_username) {
	error_out("No especific&oacute; un usuario.<br>\n");
	exit;
    } elsif ($sub_password ne $sub_password2) {
	error_out("Error en password.<br>\n");
	exit;
    } elsif (not $sub_password and not $sub_id) {
	error_out("No especific&oacute; un password.<br>\n");
	exit;
    }
 

    
    my $sub_permission = 0;

    foreach $perm (keys %PERMISSIONS) {
	if ($in{$perm}) {
	    $sub_permission |= $PERMISSIONS{$perm};
	}
    }

    my $xcp_request = {
    	    	action => ( $sub_id ? "modify" : "add" ),
		object => "subuser",
		cookie => $cookie,
		attributes => {
		    sub_id => $sub_id,
		    sub_username => $sub_username,
		    sub_password => $sub_password,
		    sub_permission => $sub_permission,
		    }
	       };
    
    $response = $XML_Client->send_cmd( $xcp_request );
    
    if (not $response->{is_success}) {
	error_out("Command failed: $response->{response_text}\n");
	exit;
    }
    
    main_menu("Subuser Changes Successful");
}

sub delete_subuser {

    my $sub_id = $in{sub_id};

    if (not $reg_f_owner) {
	error_out("Only domain owner can delete subuser.<br>\n");
	exit;
    } elsif (not $sub_id) {
	error_out("Subuser's id not supplied.<br>\n");
	exit;
    }

    my $xcp_request = {
    	    	    action => "delete",
		    object => "subuser",
		    cookie => $cookie,
		    attributes => {
			sub_id => $sub_id,
			}
		   };
    
    my $response = $XML_Client->send_cmd( $xcp_request );

    if (not $response->{is_success}) {
	error_out("Command failed: $response->{response_text}\n");
	exit;
    }

    main_menu("Subuser deleted");
}

sub change_password {

    my (%HTML);

    if (not $reg_f_owner) {
	error_out("Permission denied: not owner.\n");
	exit;
    }

    $HTML{CGI} = $cgi;
    print_form("$path_templates/change_password.html",\%HTML);
}

sub do_change_password {

    my $new_password = $in{password};
    my $confirm_password = $in{confirm_password};

    # validate password
    if ($new_password =~ /^\s*$/) {
	error_out("No password was given.<br>\n");
	exit;
    } elsif ($new_password ne $confirm_password) {
	error_out("Password mismatch.<br>\n");
	exit;
    } elsif (length $new_password < 3 || length $new_password > 20) {
	error_out("Password should have at least 3 and at most 20 characters.<br>\n");
	exit;
    } elsif ($new_password !~ /^[A-Za-z0-9\[\]\(\)!@\$\^,\.~\|=\-\+_\{\}\#]+$/) {
	error_out("Invalid syntax for password '$new_password'.\n\n
		  Allowed characters are all alphanumerics (A-Z, a-z, 0-9) and symbols []()!@\$^,.~|=-+_{}#\n");
	exit;
    }
    my $xcp_request = {
		    action => "change",
		    object => "password",
		    cookie => $cookie,
		    attributes => {
			reg_password => $new_password,
			}
		    };

    my $response = $XML_Client->send_cmd( $xcp_request );
    
    if (not $response->{is_success}) {
	error_out("Failed attempt: $response->{response_text}\n");
	exit;
    }
    
    main_menu("Password successfully changed.");
}

sub revoke_registrant_changes{
    my ($error);

    my $xcp_request = {
	action => "modify",
	object => "domain",
	cookie => $cookie,
	attributes => {
	    data => "contact_info",
	    contact_set => {
		'owner' => {"revoke_registrant_changes"=>1},
	    },
	},
    };
    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	$error = "Failed attempt: $response->{response_text}<br>\n";
	error_out($error);
	exit;
    }
    main_menu($response->{response_text});
}

# show contact info for specified domain and contact type
sub modify_contact {

    my ($error);

    my $type = $in{type};
    my $xcp_request = {
		action => "get",
		object => "domain",
		cookie => $cookie,
		attributes => {
		    type => $type,
		    }
		};
    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	$error = "Failed attempt: $response->{response_text}<br>\n";
	error_out($error);
	exit;
    }

    # process this through escape() to account for " and ' in the data
    escape_hash_values( $response );
    my %HTML = ();

    if (($type =~ /owner/i) && ($reg_domain =~ /de$/)) {
	$HTML{GLOBAL_CHANGE_MENU} = make_de_org_change_menu();
	$HTML{descr} = $response->{attributes}->{contact_set}->{owner}->{descr};
	$HTML{type} = $type;
	$HTML{reg_domain} = $reg_domain;
	print_form("$path_templates/modify_de_org_contact.html",\%HTML);
	return;
    }

    # put the contact keys/values into %HTML
    foreach my $aKey ( keys %{$response->{attributes}->{contact_set}->{$type}} ) {
	next unless exists $contact_keys{$aKey};
	$HTML{$aKey} = $response->{attributes}->{contact_set}->{$type}->{$aKey};
    }

    #
    # If the change is for the Org and the ccTLD is .ca
    # then we need only display a wee little bit of info.
    #
    if (($type =~ /owner/i) && ($reg_domain =~ /ca$/))
    {
	my %short_way = %{$response->{attributes}->{contact_set}->{$type}};
	if ((defined $short_way{member}) && ($short_way{member} eq "Y"))
	    
	{
	    $HTML{member_field} = "<INPUT TYPE=\"radio\" NAME=\"member\" VALUE=\"Y\" CHECKED>Yes\n
				<INPUT TYPE=\"radio\" NAME=\"member\" VALUE=\"N\">No\n";
	}
	else
	{
          $HTML{member_field} = "<INPUT TYPE=\"radio\" NAME=\"member\" VALUE=\"Y\">Yes\n
    				<INPUT TYPE=\"radio\" NAME=\"member\" VALUE=\"N\" CHECKED>No\n";
       }


       $HTML{legal_type_field} = build_ca_domain_legal_types ($short_way{legal_type});
       $HTML{reg_domain} = $reg_domain;
       $HTML{contact_type} = $contact_types{$type};
       $HTML{type} = $type;
       $HTML{description} = $short_way{description};
       $HTML{CGI} = $cgi;
       print_form("$path_templates/modify_ca_org_contact.html",\%HTML);
       return;
    }

    #
    # .ca is, as always, different....
    #
    if ($reg_domain =~ /ca$/)
    {
        foreach my $item (@CA_EXTRA_FIELDS)
        {
           $HTML{$item} = $response->{attributes}->{contact_set}->{$type}->{$item};
        }

        $HTML{language_type_field} = build_ca_language_preferences ($HTML{language});

        $HTML{nationality_field} = build_ca_nationality_pulldown ($HTML{nationality});
	if ( $in{ type } eq 'admin' ) {
	    $HTML{ cc_warning } = <<EOF;
<TABLE WIDTH="550" BORDER="0">
<TR><TD>
<FONT COLOR="red">Note: </FONT>Modifications to the admin contact info has been
deemed a 'critical change' by CIRA, and any changes to the contact information
will not take affect unless also confirmed at the CIRA site.
</TD></TR>
</TABLE>
<BR><BR>
EOF
	} elsif ( $in{ type } eq 'tech' ) {
	    $HTML{ cc_warning } = <<EOF;
<TABLE WIDTH="550" BORDER="0"><TR><TD>
<FONT COLOR="red">Note: </FONT>If the technical contact info is the same as
that for the admin contact, changes to the information below will be deemed
a 'critical change' by CIRA, and will not take affect unless the changes are
also confirmed at the CIRA site.
</TD></TR></TABLE>
<BR><BR>
EOF
	}
    }
    else
    {
       $HTML{language_type} = "";
       $HTML{middle_name} = "";
       $HTML{job_title} = "";
       $HTML{nationality} = "";
    }

    $HTML{org_comment} = '';
    $HTML{org_comment_close} = '';
    $HTML{uk_org_comment} = '!--';
    $HTML{uk_org_comment_close} = '--';
    # uk domains don't have 'Organization'.  Not a very nice way of hiding
    # the org name, but better to keep the template whole.  Turn the
    # organization line into an HTML comment.
    # the exception is uk domain owner contact, we use the Organization as registrant
    if ( $reg_domain =~ /\.uk$/ ) {
	$HTML{org_comment} = '!--';
	$HTML{org_comment_close} = '--';
    }
    if ( $reg_domain =~ /\.uk$/ && $type =~ /owner/i ) {
	$HTML{uk_org_comment} = '';
	$HTML{uk_org_comment_close} = '';
    }

    if ( $reg_domain =~ /\.de$/ && (($type  eq "billing" ) or ($type eq "tech"))) {
	$HTML{fax_opt_comment} = '!--';
	$HTML{fax_opt_comment_close} = '--';
    }

    $HTML{reg_domain} = $reg_domain;
    if($reg_domain =~ /de$/ and $type eq 'billing') {
	$HTML{contact_type} = 'Zone';
    } else {
	$HTML{contact_type} = $contact_types{$type};
    }
    $HTML{type} = $type;
    $HTML{CGI} = $cgi;
    $HTML{COUNTRY_LIST} = build_country_list($HTML{country});

    my $template="modify_contact.html";

    if ($reg_domain =~ /ca$/)   { 
	$template="modify_contact_ca.html";
	$HTML{GLOBAL_CHANGE_MENU} =
		make_global_menu($reg_f_owner,$reg_permission,$type)."<br><i>Only .ca will be affected</i><br>\n";
    } else {
	$HTML{GLOBAL_CHANGE_MENU} =
		make_global_menu($reg_f_owner,$reg_permission,$type);
    }


    if ($type eq 'owner' and 
	$response->{attributes}->{contact_set}->{'owner'}->{ownership_changes_request}){
	my $shortcut=$response->{attributes}->{contact_set}->{'owner'}->{ownership_changes_request};
	$HTML{revoke_registrant_changes}=<<EOF;
There is a pending registrant change request for this domain.<br> The new registrant name will be '$shortcut'<br><a href="$cgi?action=revoke_registrant_changes">Click Here</a> if you want to revoke this request.<br>
EOF

    }

    print_form("$path_templates/$template",\%HTML, 'single');
}

sub do_modify_org_contact_de {
    my $descr = $in{descr};
    my $orig_descr = $in{orig_descr};
    my $affect_domains = $in{affect_domains};
    if(($descr eq $orig_descr) and !$affect_domains ) {
	return undef;
    }

    my $xcp_request = {
                action => "modify",
                object => "domain",
                cookie => $cookie,
                attributes => {
                    data => "descr",
                    affect_domains => $affect_domains,
		    domainname => $reg_domain,
                    contact_set => {
                        owner => {descr => $descr },
                        },
                    }
                };

    return $xcp_request;
}

# process data to modify contact info
sub do_modify_contact {
    
    my ($key, $error, $type);
    my $result_domain_race_obj;
    my $resultString;

    if ($in{submit} =~ /cancel/i) {
	main_menu("Changes cancelled");
	exit;
    }

    $type = $in{type};
    delete $in{type};
    my $xcp_request;

    if($type eq "owner" and $reg_domain =~ /de$/) {
	$xcp_request =  do_modify_org_contact_de();
	unless($xcp_request) {
	    main_menu("Old contact is the same as the new one. No changes have been submitted.");
	}
    } else {
	$xcp_request = {
		action => "modify",
		object => "domain",
		cookie => $cookie,
		attributes => {
		    data => "contact_info",
		    affect_domains => $in{affect_domains},
		    contact_set => {
		    	$type => {},
			also_apply_to => [],
			},
		    }
		};

	foreach $key ( keys %in ) {
	    next unless exists $contact_keys{$key};
	    $xcp_request->{attributes}->{contact_set}->{$type}->{$key} = $in{$key};
	}

	if ($reg_domain =~ /ca$/) {
	    foreach $key (@CA_EXTRA_FIELDS) {
		$xcp_request->{attributes}->{contact_set}->{$type}->{$key} = $in{$key} if defined $in{$key};
	    }
	}

	# basic error checking on request vs user permissions
	my $affect_domains = $in{affect_domains};

	foreach $key (keys %contact_types) {
	    if ($in{"affect_$key"}) {
	    
		if ((not $reg_f_owner) and (not $reg_permission & $PERMISSIONS{"F_MODIFY_$key"})) {
		    error_out("No permission to modify contact type: $contact_types{$key}.<br>\n");
		    exit;
		}
	    
	    	push @{$xcp_request->{attributes}->{contact_set}->{also_apply_to}}, $key;
	    }
	}

	if ($affect_domains and (not $reg_f_owner)) {
	    error_out("Only the domain owner can apply changes to multiple domains.<br>\n");
	    exit;
	}
    }

    my $response = $XML_Client->send_cmd( $xcp_request );

    if (not $response->{is_success}) {
        # only go into the total failure page if there
        # are not any details, because OpenSRS will
        # will return not is_success if none
        # of the contacts were succesfully modified
        # for reasons particular to each domain applied to.
	if ( ( not exists $response->{attributes} ) &&
	     ( not exists $response->{attributes}->{details} ) ) {
            $error .= "Failed attempt: $response->{response_text}.<br>\n";
	    if ($response->{attributes}->{error}) {
	        $response->{attributes}->{error} =~ s/\n/<br>\n/g;
	        $error .= $response->{attributes}->{error};
	    }
	    error_out($error);
	    exit;
        }
    }

    # response_code of 250 indicates that an asynchronous registry has
    # received the request and the modification completion will
    # occur later.
    if ( $response->{response_code} == 250 )
    {
	$waiting_request = $response->{attributes}->{waiting_request};
    	main_menu($resultString."Contact modification submitted, could take up to ".time_to_wait().".");
    }
    else
    {
	my $domainResult;
	
	if ( exists $response->{attributes} &&
	     exists $response->{attributes}->{details} )
	{
	    $resultString .= $response->{attributes}->{response_text};
	    $resultString .= "<BR>";
	    my $tempDetailHash;
	    foreach $domainResult ( keys %{$response->{attributes}->{details}} )
	    {
	    	$tempDetailHash = $response->{attributes}->{details}->{$domainResult};
		$result_domain_race_obj = RACE::UndoRACE(Domain => pack('A*',$domainResult),
    	    	    	    	    	       EncodingType => $UNIVERSAL_ENCODING_TYPE);

		$resultString = sprintf( '%s%s : %s<BR>',
		    	    		 $resultString,
					 $result_domain_race_obj->{OriginalDomain},
					 $tempDetailHash->{response_text} );
		if ( $domainResult eq $reg_domain &&
		     exists $tempDetailHash->{waiting_request})
		{
		    $waiting_request = $tempDetailHash->{waiting_request};
		}
	    }
	}
	else
	{
	    $resultString .= $response->{response_text};
	}
    	main_menu($resultString);
    }

}

# show domain tld-specific info
sub modify_domain_extras {

    my ($error);

    my $rsp_auth_info;
    if ($capabilities->{domain_auth_info}) {
        my $xcp_auth_info = {
		action => "get",
		object => "domain",
		cookie => $cookie,
		attributes => {
		    type => "domain_auth_info",
		    }
		};
        $rsp_auth_info = $XML_Client->send_cmd( $xcp_auth_info );
        if (not $rsp_auth_info->{is_success}) {
		$error = "Failed attempt: $rsp_auth_info->{response_text}<br>\n";
		error_out($error);
		exit;
    	}
	escape_hash_values( $rsp_auth_info );
    }

    my $rsp_forwarding_email;
    if ($capabilities->{forwarding_email}) {
        my $xcp_forwarding_email = {
		action => "get",
		object => "domain",
		cookie => $cookie,
		attributes => {
		    type => "forwarding_email",
		    }
		};
        $rsp_forwarding_email = $XML_Client->send_cmd( $xcp_forwarding_email );
        if (not $rsp_forwarding_email->{is_success}) {
		$error = "Failed attempt: $rsp_forwarding_email->{response_text}<br>\n";
		error_out($error);
		exit;
    	}
	escape_hash_values( $rsp_forwarding_email );
    }

    my $rsp_nexus_info;
    if ($capabilities->{nexus_info}) {
        my $xcp_nexus_info = {
                action => "get",
                object => "domain",
                cookie => $cookie,
                attributes => {
                    type => "nexus_info",
                    }
                };
        $rsp_nexus_info = $XML_Client->send_cmd( $xcp_nexus_info );
        if (not $rsp_nexus_info->{is_success}) {
                $error = "Failed attempt: $rsp_nexus_info->{response_text}<br>\n";
                error_out($error);
                exit;
        }
        escape_hash_values( $rsp_nexus_info );
    }

    my $rsp_trademark;
    if ($capabilities->{trademark}) {
        my $xcp_trademark = {
                action => "get",
                object => "domain",
                cookie => $cookie,
                attributes => {
                    type => "trademark",
                    }
                };
        $rsp_trademark = $XML_Client->send_cmd( $xcp_trademark );
        if (not $rsp_trademark->{is_success}) {
                $error = "Failed attempt: $rsp_trademark->{response_text}<br>\n";
                error_out($error);
                exit;
        }
        escape_hash_values( $rsp_trademark );
    }

    my %HTML = ();

    $HTML{domain_auth_info} = $rsp_auth_info->{attributes}->{domain_auth_info} if ($rsp_auth_info);
    $HTML{forwarding_email} = $rsp_forwarding_email->{attributes}->{forwarding_email} if ($rsp_forwarding_email);
     
    if ($rsp_nexus_info) {
	$HTML{old_app_purpose} = $rsp_nexus_info->{attributes}->{nexus}->{app_purpose};
	$HTML{old_nexus_category} = $rsp_nexus_info->{attributes}->{nexus}->{category};
	$HTML{old_nexus_validator} = $rsp_nexus_info->{attributes}->{nexus}->{validator};
	$HTML{old_app_purpose} =~ tr/a-z/A-Z/;
	$HTML{old_nexus_category} =~ tr/a-z/A-Z/;
	$HTML{old_nexus_validator} =~ tr/a-z/A-Z/;
	$HTML{"category_" . $HTML{old_nexus_category}} = "checked";
    }
	 
    if ($HTML{forwarding_email}) {
	$HTML{text_comment} = '!--';
	$HTML{text_comment_close} = '--';
    } else {
	$HTML{email_comment} = '!--';
	$HTML{email_comment_close} = '--';
    }
	
    $HTML{CGI} = $cgi;


    # include domain auth code form in the main html page if domain auth code is avaliable
    if ($rsp_auth_info) { 
        $HTML{domain_auth_code_form} = get_content("$path_templates/domain_auth_code_form.html", \%HTML);
    }

    # include forwarding email form in the main html page if it is capable for forwarding email modification
    if ($rsp_forwarding_email) {
	$HTML{forwarding_email_form} = get_content("$path_templates/forwarding_email_form.html", \%HTML);
    }  
      
    # include trademark form in the main html page if it is capable for trademark modification
    if ($rsp_trademark) {
        if ( $rsp_trademark->{attributes}->{trademark} eq "Y" ) {
    	    $HTML{trademark_enabled}='checked';
	    $HTML{trademark_disabled}='';
        } else {
            $HTML{trademark_enabled}='';
            $HTML{trademark_disabled}='checked';
        }
	$HTML{trademark} = $rsp_trademark->{attributes}->{trademark};
	$HTML{trademark_form} = get_content("$path_templates/trademark_form.html", \%HTML);
    } 
    # include nexus data form in the main html page if it is capable for .us nexus data modification
    if ($rsp_nexus_info) {
	$HTML{app_purpose_menu} = build_app_purpose_list($HTML{old_app_purpose});
	$HTML{citizen_country_list} = build_country_list($HTML{old_nexus_validator}?$HTML{old_nexus_validator}:'--');
	$HTML{us_nexus_form} = get_content("$path_templates/us_nexus_form.html", \%HTML);
    }
    
    if($dns_errors) {
	$HTML{full_dns_error} = $dns_errors;
	$HTML{dns_error_form} = get_content("$path_templates/dns_error_form.html", \%HTML);
    }
    
    if ( $capabilities->{cira_email_pwd} and $MANAGE{enable_cira_email_pwd} ) {
	$HTML{cira_email_pwd} = get_content("$path_templates/cira_email_pwd.html", \%HTML);
    }

    my $template="modify_domain_extras.html";
    print_form("$path_templates/$template",\%HTML);
}

# process data to modify domain extras
sub do_modify_domain_extras {
    
    my ($ok_flag, $do_flag, $resultString);

    if ($in{submit} =~ /cancel/i) {
	main_menu("Changes cancelled");
	exit;
    }
    if ($in{domain_auth_info} && $in{domain_auth_info} ne $in{old_domain_auth}) {
	$do_flag = 1;
        my $xcp_auth_info = {
                action => "modify",
                object => "domain",
                cookie => $cookie,
                attributes => {
                    data => "domain_auth_info",
		    domain_auth_info => $in{domain_auth_info},
                    }
                };
        my $rsp_auth_info = $XML_Client->send_cmd( $xcp_auth_info );
        if (not $rsp_auth_info->{is_success}) {
                $resultString .= "Failed to modify domain auth code for $reg_domain : $rsp_auth_info->{response_text}<br>";
        } else {
		$resultString .= "Domain auth code modification successful for $reg_domain<br>";
		$ok_flag = 1;
	}
    }
    if ($in{trademark} && $in{trademark} ne $in{old_trademark}) {
	$do_flag = 1;
        my $xcp_trademark = {
                action => "modify",
                object => "domain",
                cookie => $cookie,
                attributes => {
                    data => "trademark",
		    trademark => $in{trademark},
                    }
                };
        my $rsp_trademark = $XML_Client->send_cmd( $xcp_trademark );
        if (not $rsp_trademark->{is_success}) {
                $resultString .= "Failed to modify domain trademark for $reg_domain : $rsp_trademark->{response_text}<br>";
        } else {
		$resultString .= "Domain trademark modification successful for $reg_domain<br>";
		$ok_flag = 1;
	}
    }
    if ($in{forwarding_email} && $in{forwarding_email} ne $in{old_forwarding_email}) {
	$do_flag = 1;
        my $xcp_forwarding_email = {
                action => "modify",
                object => "domain",
                cookie => $cookie,
                attributes => {
                    data => "forwarding_email",
		    forwarding_email => $in{forwarding_email},
                    }
                };
        my $rsp_forwarding_email = $XML_Client->send_cmd( $xcp_forwarding_email );
        if (not $rsp_forwarding_email->{is_success}) {
                $resultString .= "Failed to modify forwarding email for $reg_domain : $rsp_forwarding_email->{response_text}<br>";
        } elsif ($rsp_forwarding_email->{response_code} == 250) {
		$resultString .= "Forwarding email modification successfully submitted, could take up to ".time_to_wait().".<br>";
		$ok_flag = 1;
        } else {
		$resultString .= "Forwarding email modification successful for $reg_domain<br>";
		$ok_flag = 1;
	}
    }

    if ($capabilities->{nexus_info}) {
        my $xcp_nexus_info = {
                action => "modify",
                object => "domain",
                cookie => $cookie,
                attributes => {
                    data => "nexus_info",
		    nexus => {
                        app_purpose => $in{app_purpose},
                        category => $in{nexus_category},
			}
                    }
                };
        my $mod_flag = 0;
        $mod_flag =1 if ($in{app_purpose} ne $in{old_app_purpose});
        $mod_flag =1 if ($in{nexus_category} ne $in{old_nexus_category});
        if ($in{nexus_category} =~ /^C3/) {
                $xcp_nexus_info->{attributes}->{nexus}->{validator} = $in{nexus_validator};
                $mod_flag =1 if ($in{nexus_validator} ne $in{old_nexus_validator});
        }

        if ($mod_flag) {
                $do_flag = 1;
                my $rsp_nexus_info = $XML_Client->send_cmd( $xcp_nexus_info );
                if (not $rsp_nexus_info->{is_success}) {
                        $resultString .= "Failed to modify nexus info for $reg_domain : $rsp_nexus_info->{response_text}<br>";
                } else {
                        $resultString .= "Nexus info modification successful for $reg_domain<br>";
                        $ok_flag = 1;
                }
        }
    }

    if($in{flag_do_validate_domain} and $dns_errors) {
	 $do_flag = 1;	
	my $validate_command = {
			action => "activate",
			object => "domain",
			cookie => $cookie,
			attributes => {
				domainname => $reg_domain,
				      }
	    
			       };
	my $val_res = $XML_Client->send_cmd($validate_command);
	if (not $val_res->{is_success}) {
	    $resultString .= "Failed to submit domain validation for $reg_domain : $val_res->{response_code} $val_res->{response_text}<br>"; 
	} else {
	    $resultString .= "Domain validation successfully submitted to registry. Please review your changes in 15" .
			    "minutes to verify that they were accepted.<br>";
	    $ok_flag = 1;
	}
	
		
    }
    
    if( $in{cira_email_pwd} and $MANAGE{enable_cira_email_pwd} ) {
        $do_flag = 1;
	my $xcp_cira_email_pwd = {
	    action => "cira_email_pwd",
	    object => "domain",
		attributes => {
	            domain => $reg_domain,
	    }
	};
	
	my $cira_email_pwd = $XML_Client->send_cmd( $xcp_cira_email_pwd );
	
	if (not $cira_email_pwd->{is_success}) {
	    $resultString .=  "Failed attempt: $cira_email_pwd->{response_text}<br>";
    	} else {
	    $resultString .= "CIRA password sent to admin contact.<br> You can check email address in domain notes.<br>";
	    $ok_flag = 1;
	}
    }

    if (not $do_flag) {
	main_menu("Domain Extras Data modification successful<br>");
    } elsif ($ok_flag == 1) {
        main_menu($resultString);
    } else {
        error_out($resultString);
    }

}


# display domains a user owns
sub view_domains {

    my (%HTML,$domain_name,$domain_html,$next_page,$previous_page);

    my $page = $in{ page };
    if ( not $page ) { $page = 0 }

    my $order_by =  $in{ orderby };
    if ( not $order_by ) { $order_by = 'name' }
    
    my $limit = $in{ limit };
    if( not $limit ) { $limit = 40 }

    $in{ sort_by } = 'DESC'
        if not $in{ sort_by } or $in{ sort_by } !~ /^(ASC|DESC)$/;

    my $sort = $in{sort_by};

    my $domain = lc $in{domain};
    $domain  = trim($domain);

    my $domain_search = $in{domain_search};
    $domain_search  = trim($domain_search);
 
    if($domain_search =~ /[^\*a-zA-Z0-9_.-]/i){
        if($domain =~ /\*/g){
            error_out("Inavlid syntax! (cannot use wildcard with multilingual domains)");
            exit;
        }else{
            my $local_domain_race_obj = RACE::DoRACE( Domain => $domain_search,
                                              EncodingType => $UNIVERSAL_ENCODING_TYPE );
            if ( not $local_domain_race_obj ||
                $local_domain_race_obj->{Error} ){
                error_out("Failed to race encode the domain: [".$local_domain_race_obj->{Error}."]");
                exit;
            }
            $domain_search = $local_domain_race_obj->{ConvertedDomain};
        }
    }

    if( not $domain_search ) { $domain_search = '*' }

    # get domains for a given user
    my $xcp_request = {
    	    	action => "get",
		object => "domain",
		cookie => $cookie,
		attributes => {
		    page => $page,
		    type => "list",
		    with_encoding_types => 1,
		    domain => $domain,  
		    domain_search => $domain_search,    
		    expiry_date => $in{ expiry_date },	
		    auto_renew => $in{ auto_renew },
		    order_by => $order_by,
		    sort_by => $sort,
		    limit => $in{ limit },
		    }
	    };

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Failed attempt: $response->{response_text}\n");
	exit;
    }

    my $remainder = $response->{attributes}->{remainder}; # are there more domains to show?
    my @test_array = @{ $response->{ attributes }{ ext_results } };
    my %domains = map { %{ $_ } } @{ $response->{ attributes }{ ext_results } };

    foreach my $domain ( keys %domains ) {
		my $domain_name_race_obj = RACE::UndoRACE(
	        Domain	    => pack('A*', $domain ),
		EncodingType    => $UNIVERSAL_ENCODING_TYPE
	    );

	    $domains{ $domain }{ RACE } = $domain_name_race_obj->{ OriginalDomain };
	    $domains{ $domain }{ auto_renew } = $domains{ $domain }{ auto_renew } ? "Y" : "N";
	    $domains{ $domain }{ expiredate } =~ s/\s.*//;	# get rid of the time
    }

    foreach my $test ( @test_array ){
	foreach my $domain ( keys %$test ){
	    my $domain_link;
	    if ( $reg_domain eq $domain ) {
		$domain_link = $domains{ $domain }{ RACE };
	    } else {
		$domain_link = qq(<A HREF="$cgi?action=manage_domain&domain=$domain">$domains{$domain}{RACE}</A>);
	    }

	    $domain_html .= <<EOROW;
<TR>
<TD>$domain_link</TD>
<TD ALIGN="CENTER">$domains{ $domain }{ expiredate }</TD>
<TD ALIGN="CENTER">$domains{ $domain }{ auto_renew }</TD>
</TR>
EOROW
	}
    }

    my $num_page_links = 10;
    $HTML{page} = $page;
    my $navbar = make_navbar(
		    "view_domains&limit=$in{limit}&domain_search=$in{domain_search}&domain=$in{domain}&expiry_date=$in{expiry_date}&auto_renew=$in{auto_renew}&orderby=$order_by&sort_by=$sort",
		    $response->{ attributes }{ count }, $limit, $num_page_links, $HTML{page}
    );

    $navbar .= "<br><br>\n";

    $HTML{DOMAIN_COUNT} = $response->{ attributes }{ count };
    $HTML{DOMAINS} = $domain_html;
    $HTML{domain} = $in{domain};
    $HTML{domain_search} = $in{domain_search};	
    $HTML{page} = $page;
    $HTML{limit} = $limit; 
    $HTML{auto_renew} = $in{auto_renew};
    $HTML{cgi} = $cgi;
    $HTML{NAVBAR} = $navbar;
    $HTML{expiry_date} = $in{expiry_date};
    $HTML{new_sort} = $sort eq 'ASC' ? 'DESC' : 'ASC';
    print_form("$path_templates/view_domains.html",\%HTML);
}



# switches authentication to specified domain (updates cookie)
sub manage_domain {
    my $domain = lc $in{domain};
    $domain =~ s/(^\s+)|(\s+$)//g;

    my $local_domain_race_obj = RACE::DoRACE( Domain => $domain,
    	    	    	    	    	      EncodingType => $UNIVERSAL_ENCODING_TYPE );

    if ( not $local_domain_race_obj ||
    	 $local_domain_race_obj->{Error} )
    {
    	error_out("Failed to race encode the domain: [".$local_domain_race_obj->{Error}."]");
	exit;
    }
    
    my ($tld) = $local_domain_race_obj->{ConvertedDomain} =~ /$OPENSRS{OPENSRS_TLDS_REGEX}$/;
    if ( exists $CANT_SUPPORT{$tld} )
    {
    	my $message = <<EOF;
You cannot currently make changes to $tld domains through this<BR>
interface. We will have a $tld enabled Manage Domain interface in place as<BR>
soon as possible.<BR>
If need to make emergency nameserver changes to your domain, please contact
<a href="mailto:support\@opensrs.org">support\@opensrs.org</a>.
EOF
    	error_out($message);
	exit;
    }

    my $xcp_request = {
    	    	action => "update",
		object => "cookie",
		cookie => $cookie,
		attributes => {
		    reg_username => $reg_username,
		    reg_password => $reg_password,
		    domain => $reg_domain,
		    domain_new => $local_domain_race_obj->{ConvertedDomain},
		    }
		};

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Failed attempt: $response->{response_text}\n");
	exit;
    }

    $reg_domain = $domain;
    $reg_f_owner = $response->{attributes}->{f_owner};
    $reg_permission = $response->{attributes}->{permission};
    $domain_count = $response->{attributes}->{domain_count};
    $expiredate = $response->{attributes}->{expiredate};
    $waiting_request = $response->{attributes}->{waiting_request};
    my $managed_domain;
    {
	my $race_obj = RACE::UndoRACE(
		Domain => pack("A*",$reg_domain),
		EncodingType => $UNIVERSAL_ENCODING_TYPE
	);

	$managed_domain = $race_obj->{OriginalDomain};
    }

    validate();
    main_menu("Now managing $managed_domain.");        

}

sub make_de_org_change_menu {
    my $html;

    $html = <<EOF;
<table border=0 cellpadding=3 cellspacing=0>
<tr><td colspan=3 align=center><font face="verdana, arial" size=2>
<b>Also Apply these changes to:</b></font></td>
</tr>
<tr>
<td align=center>
<font face="verdana, arial" size=2><b></b></font></td>
<td align=center><font face="verdana, arial" size=2><b>YES</b></font></td>
<td align=center><font face="verdana, arial" size=2><b>NO</b></font></td>
</tr>
<tr>
<td align=right><font face="verdana, arial" size=2>All Domains (<font color="blue">$domain_count</font>)</font></td>
<td align=center>
<input type=radio name=affect_domains value="1"></td>
<td align=center>
<input type=radio name=affect_domains value="0" checked></td>
</tr>
<tr>
<td colspan=3 align=right><font face="verdana, arial" size=-1>(only .de domains will be modified)</font></td>
</tr>
</table>
EOF

    return $html;
    
}
sub make_global_menu {

    my ($type,$html);

    my ($f_owner,$permission,$current_type) = @_;

    my $table_start = <<EOF;
<table border=0 cellpadding=3 cellspacing=0>
<tr>
<td colspan=3 align=center><font face="verdana, arial" size=2>
<b>Tambi&eacute;n aplicar estos cambios a:</b></font></td>
</tr>
<tr>
<td align=center>
<font face="verdana, arial" size=2><b></b></font></td>
<td align=center><font face="verdana, arial" size=2><b>SI</b></font></td>
<td align=center><font face="verdana, arial" size=2><b>NO</b></font></td>
</tr>
EOF


    foreach $type (sort keys %contact_types) {
    	# The owner contact type cannot be modified if the
	# domain ends with uk
    	if ((($type =~ /owner/i ) && ( $reg_domain =~ /uk$/ )) ||
		(($type =~ /billing/i ) && ( $reg_domain =~ /ca$/ )))
    	{
	    next;
	}
	if (($f_owner or $permission & $PERMISSIONS{"F_MODIFY_$type"}) and ($type ne $current_type)) {
	    $html .= <<EOF;
<tr>
<td align=right><font face="verdana, arial" size=2>$contact_types{$type} Contacto</font></td>
<td align=center>
<input type=radio name="affect_$type" value="1"></td>
<td align=center>
<input type=radio name="affect_$type" value="0" checked></td>
</tr>
EOF
	}

    }

    #
    # We can't normalize the data with .ca domains so we don't allow
    # for universal changes with .ca domains.
    #
    if ($reg_f_owner && ($domain_count > 1) && ($reg_domain !~ /ca$/))
    {
	$html .= <<EOF;
<tr>
<td align=right><font face="verdana, arial" size=2>Todos los dominios (<font color="blue">$domain_count</font>)</font></td>
<td align=center>
<input type=radio name=affect_domains value="1"></td>
<td align=center>
<input type=radio name=affect_domains value="0" checked></td>
</tr>
EOF
    }

    my $table_end = "</table>\n";

    my ($menu);

    if ($html) {
#$table_start
#$table_end
	$menu = <<EOF;
$html
EOF
	return $menu;
    } else {
	return "";
    }

}
# generaste menu for applying contact changes to other types/domains
sub make_global_menu_new {

    my ($type,$html);

    my ($f_owner,$permission,$current_type) = @_;
    
    my $table_start = <<EOF;
<table border=0 cellpadding=3 cellspacing=0>
<tr><td colspan=3 align=center><font face="verdana, arial" size=2>
<b>Also Apply these changes to:</b></font></td>
</tr>
<tr>
<td align=center>
<font face="verdana, arial" size=2><b></b></font></td>
<td align=center><font face="verdana, arial" size=2><b>YES</b></font></td>
<td align=center><font face="verdana, arial" size=2><b>NO</b></font></td>
</tr>
EOF

    if($reg_domain =~ /de$/) { 
	$contact_types{billing} = "Zone";
    } else {
	$contact_types{billing} = "Billing";
    }

    foreach $type (sort keys %contact_types) {
    	if ((($type =~ /owner/i ) && ( $reg_domain =~ /ca$/ || $reg_domain =~ /uk$/ || $reg_domain =~ /de$/)) ||
		(($type =~ /billing/i ) && ( $reg_domain =~ /ca$/ )))
    	{
	    next;
	}
	if (($f_owner or $permission & $PERMISSIONS{"F_MODIFY_$type"}) and ($type ne $current_type)) {
	    $html .= <<EOF;
<tr>
<td align=right><font face="verdana, arial" size=2>$contact_types{$type} Contact</font></td>
<td align=center>
<input type=radio name="affect_$type" value="1"></td>
<td align=center>
<input type=radio name="affect_$type" value="0" checked></td>
</tr>
EOF
	}
	
    }

    #
    # We can't normalize the data with .ca domains so we don't allow
    # for universal changes with .ca domains.
    #
    # If it is in the organization contact page and if it is .uk domains,
    # we do not allow for universal changes with .uk domains right now.
    #

     if ($reg_f_owner && ($domain_count > 1) && ! ($reg_domain =~ /uk$/ && $current_type=~ /owner/i) ) {
	$html .= <<EOF;
<tr>
<td align=right><font face="verdana, arial" size=2>All Domains (<font color="blue">$domain_count</font>)</font></td>
<td align=center>
<input type=radio name=affect_domains value="1"></td>
<td align=center>
<input type=radio name=affect_domains value="0" checked></td>
</tr>
EOF
    }

    my $table_end = "</table>\n";

    my ($menu);

    if ($html) {
	$menu = <<EOF;
$table_start

$html

$table_end
EOF
	return $menu;
    } else {
	return "";
    }

}

sub manage_nameservers {

    my (%HTML,$ns,$key,$fqdn,$ip,$delete,$message,$fqdn_race_obj);

    if (@_) {
	$message = shift;
    }

    # retrieve nameserver info
    my $xcp_request = {
		action => "get",
		object => "nameserver",
		cookie => $cookie,
		attributes => {
		    type => "all",
		    },
	    };

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Unable to retrieve nameservers: $response->{response_text}\n");
	exit;
    }

    foreach $key ( @{$response->{attributes}->{nameserver_list}} ) {
	$fqdn = $key->{name};
	$fqdn_race_obj = RACE::UndoRACE(Domain => pack('A*',$fqdn), EncodingType => $UNIVERSAL_ENCODING_TYPE);
	$ip = $key->{ipaddress};
	if ( $key->{can_delete} ) {
	    $delete = <<EOF;
<input type=submit name=submit value="Delete" onClick="return confirm('Are you sure?')";>
EOF
	} else {
	    $delete = "";
	}
	$HTML{nameservers} .= <<EOF;
<tr>
<form method=post action="$cgi">
<input type=hidden name=action value="do_manage_nameserver">
<input type=hidden name=fqdn value="$fqdn">
<td align=right><b>$fqdn_race_obj->{OriginalDomain}</b></td>
<td><input type=text name=new_fqdn value="$fqdn_race_obj->{OriginalDomain}" size=20></td>
EOF
	$HTML{nameservers} .= <<EOF;
<td><input type=text name=ip value="$ip" size=20></td>
<td>
<input type=submit name=submit value="Modify">
$delete
</td>
</form>
</tr>
EOF
    }

    $HTML{DOMAIN_NAME} = $reg_domain;
    $HTML{CGI} = $cgi;
    $HTML{MESSAGE} = $message ? "<font color=red>$message</font><br><br>" : "";
    print_form("$path_templates/manage_nameservers.html",\%HTML);

}

# change ip address for a given nameserver
sub do_manage_nameserver {

    my $fqdn = $in{fqdn};
    my $fqdn_race_obj = RACE::UndoRACE(Domain => pack('A*',$fqdn), EncodingType => $UNIVERSAL_ENCODING_TYPE);
    my $new_fqdn = $in{new_fqdn};
    my $new_fqdn_race_obj = RACE::DoRACE(Domain => $new_fqdn, EncodingType => $UNIVERSAL_ENCODING_TYPE);
    if ( ( not $new_fqdn_race_obj )  || ($new_fqdn_race_obj->{Error}))
    {
    	error_out("Could not encode the new nameserver name [". $new_fqdn_race_obj->{Error} ."]");
	exit;
    }
    my $ip = $in{ip};
    
    my $xcp_request = {
		action => "",
		object => "nameserver",
		cookie => $cookie,
		attributes => {
		    name => $fqdn,
		    ipaddress => $ip,
		    }
	    };

    if ($in{submit} =~ /delete/i) {

    	$xcp_request->{action} = "delete";
	my $response = $XML_Client->send_cmd( $xcp_request );
	if (not $response->{is_success}) {
	    my $error = "Unable to delete nameserver: $response->{response_text}";

	    # check to see why nameservers can't be modified.  If because
	    # domain is locked, return a message to that affect.
	    my $resp = $XML_Client->send_cmd(
		{
		    action      => 'get',
		    object      => 'domain',
		    cookie      => $cookie,
		    attributes  => {
			type    => 'status',
		    }
		}
	    );

	    if ( $resp->{ attributes }{ lock_state } ) {
		$error = "This domain is currently locked. The lock must be removed to allow nameservers to be modified."
	    }

	    error_out( $error );
	    exit;
	}
	
	# response_code of 250 indicates that an asynchronous registry has
	# received the request and the completion of the request will
	# occur later.
	if ( $response->{response_code} == 250 )
	{
	    $waiting_request = $response->{attributes}->{waiting_request};
    	    manage_nameservers("Nameserver deletion submitted, could take up to ".time_to_wait().".");
	}
	else
	{
	    manage_nameservers("Nameserver $fqdn_race_obj->{OriginalDomain} deleted");
	}

    } else {

	# only pass the new_fqdn param if it is changing
	if ($fqdn ne $new_fqdn_race_obj->{ConvertedDomain}) 
	{
	    $xcp_request->{attributes}->{new_name} = $new_fqdn_race_obj->{ConvertedDomain};
	}
	
	$xcp_request->{action} = "modify";
	my $response = $XML_Client->send_cmd( $xcp_request );
	if (not $response->{is_success}) {
	    my $error = "Unable to modify nameserver: $response->{response_text}";

	    # if reason can't add is because domain is locked, return message
	    # to that affect.
	    my $resp = $XML_Client->send_cmd(
		{
		    action      => 'get',
		    object      => 'domain',
		    cookie      => $cookie,
		    attributes  => {
			type    => 'status',
		    }
		}
	    );

	    if ( $resp->{ attributes }{ lock_state } ) {
		$error = "This domain is currently locked. The lock must be removed to allow nameservers to be modified.";
	    }

	    error_out( $error );
	    exit;
	}
	
	# response_code of 250 indicates that an asynchronous registry has
	# received the request and the completion of the request will
	# occur later.
	if ( $response->{response_code} == 250 )
	{
	    $waiting_request = $response->{attributes}->{waiting_request};
	    if ($fqdn ne $new_fqdn_race_obj->{ConvertedDomain}) {
		manage_nameservers("Nameserver rename modification submitted, could take up to ".time_to_wait().".");
	    } else {
		manage_nameservers("Nameserver modification submitted to registry for processing. " .
				    "Please review your changes in 15 minutes to verify that they were accepted.");
	    }
	}
	else
	{
	    if ($fqdn ne $new_fqdn_race_obj->{ConvertedDomain} ) {
		manage_nameservers("Nameserver $fqdn_race_obj->{OriginalDomain} renamed to $new_fqdn");
	    } else {
		manage_nameservers("Nameserver $fqdn_race_obj->{OriginalDomain} successfully modified");
	    }
	}
	
    }
}

# display nameserver information for the current domain
sub modify_nameservers {

    my (%fqdns,$fqdn,$ip,$key,$num,$ns_html,%HTML,$title,$add_ns,$fqdn_race_obj);

    my $message = shift;

    # retrieve nameserver info
    my $xcp_request = {
    	    	action => "get",
		object => "domain",
		cookie => $cookie,
		attributes => {
		    type => 'nameservers',
		    }
	    };
    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Unable to retrieve nameserver information: $response->{response_text}\n");
	exit;
    }
    $HTML{CGI} = $cgi;

    foreach $key ( @{$response->{attributes}->{nameserver_list}} ) {

	$fqdns{$key->{sortorder}} = 1;
    }

    my $count = 1;
    foreach $key ( @{$response->{attributes}->{nameserver_list}} ) {
	if ($count == 1) {
	    $title = "Primary";
	} elsif ($count == 2) {
	    $title = "Secondary";
	} else {
	    $title = "Nameserver $count";
	}
	$fqdn = $key->{name};
	$ip = $key->{ipaddress};
	$num = $key->{sortorder};
	$fqdn_race_obj = RACE::UndoRACE(Domain => pack('A*',$fqdn), EncodingType => $UNIVERSAL_ENCODING_TYPE);
	$ns_html .= <<EOF;
<tr>
<td align=right nowrap><font face="verdana, arial" size=2>
<strong>$title: </strong></font></td>

<td><input type=text name="fqdn$num" value="$fqdn_race_obj->{OriginalDomain}" size=25></td>
EOF
        # Add blanks to line up the 'remove checkbox' beside this ip
	my $ip_nice = $ip . '&nbsp;' x (16 - length($ip));

	$ns_html .= <<EOF;
<td nowrap><font face="courier">$ip_nice</font>
<input type=checkbox name="remove_$num" value="1"> <font size=2>Remove</font>
</td>
</tr>

EOF
	$count++;
    }

    # only show the option to create new nameservers for domain owners
    if (($reg_f_owner) || ($reg_permission & $PERMISSIONS{f_modify_nameservers})) {
	$HTML{CREATE_NAMESERVERS} = <<EOF;
    
<br><br>
<b>If you want to create or modify a nameserver which is based on $reg_domain_race_obj->{OriginalDomain} <a href="$cgi?action=manage_nameservers">click here.</a>
EOF
}

    $HTML{NAMESERVERS} = $ns_html;
    $HTML{MESSAGE} = $message ? "<font color=red>$message</font>" : "";
    print_form("$path_templates/modify_nameservers.html",\%HTML);

}


# process data to modify nameservers for the current domain
sub do_modify_nameservers {

    my ($sortorder,$key,%remove_ids,$ns_data,$response,$fqdn_race_obj);

    if ($in{submit} =~ /cancel/i) {
	modify_nameservers("Changes cancelled\n");
	exit;
    }
	

    $ns_data = [];

    foreach $key (keys %in) {
	if ($key =~ /^fqdn(\d+)$/) {
	    if (not exists $remove_ids{$1}) {
		# remove blank nameserver entries
		if (not $in{$key}) {
		    $remove_ids{$1} = 1;
		    next;
		}
		
		# don't include this fqdn if it's begin removed
		next if $in{"remove_".$1};
		
		$fqdn_race_obj = RACE::DoRACE (
		    	    Domain => $in{$key},
    	    	    	    EncodingType => $UNIVERSAL_ENCODING_TYPE
			    );
		push @{$ns_data}, { name => $fqdn_race_obj->{ConvertedDomain},
		    	    	    sortorder => $1,
				    action => "update" };
	    }

	} elsif ($key =~ /^remove_(\d+)$/) {
	    push @{$ns_data}, { sortorder => $1,
				action => "remove" };
	}
    }

    my $xcp_request = {
		action => "modify",
		object => "domain",
		cookie => $cookie,
		attributes => {
		    data => "nameserver_list",
		    nameserver_list => [ ],
		    },
	       };


    if ( scalar @{$ns_data} )
    {

    	# need to set the action and object to lower case because
	# send_cmd() transforms them up.
	$xcp_request->{action} = lc $xcp_request->{action};
    	$xcp_request->{object} = lc $xcp_request->{object};
	$xcp_request->{attributes}->{nameserver_list} = $ns_data;

	$response = $XML_Client->send_cmd( $xcp_request );
	if (not $response->{is_success}) {
	    # check to see why nameservers can't be modified.  If because
	    # domain is locked, return a message to that affect.
	    my $resp = $XML_Client->send_cmd(
		{
		    action      => 'get',
		    object      => 'domain',
		    cookie      => $cookie,
		    attributes  => {
			type    => 'status',
		    }
		}
	    );

	    my $error = "Unable to update nameservers: $response->{response_text}";

	    if ( $resp->{ attributes }{ lock_state } ) {
		$error = "This domain is currently locked.  The lock must be removed to make DNS changes.";
	    }

	    error_out( $error );
	    exit;
	}
    }
    
    # response_code of 250 indicates that an asynchronous registry has
    # received the request and the completion of the request will
    # occur later.
    if ( $response->{response_code} == 250 ) {
	$waiting_request = $response->{attributes}->{waiting_request};
    	modify_nameservers("Nameservers update for $reg_domain_race_obj->{OriginalDomain} successfully submitted, could take up to ".time_to_wait().".");
    } elsif ( $response->{response_code} == 251 ) {
	# removing a nameserver from a UK domain which is based upon that
	# domain will cause any other domains using that nameserver to not
	# function properly.  In this case, send back a message to that affect.

	# This applies at the moment to .uk nameservers, due to the way
	# Nominet handles glue records.
	modify_nameserver( $response->{ response_text } );
    } else {
    	modify_nameservers("Nameservers modification for $reg_domain_race_obj->{OriginalDomain} successfully submitted to registry. Please review your changes in 15 minutes to verify that they were accepted");
    }
}

# add a nameserver for the current domain
sub add_nameserver {

    my $fqdn_race_obj;
    my @fqdn = map {$in{$_}} 
		  grep {$in{$_} ne ''} 
		    grep /^fqdn/, 
		      keys %in;
    
    if (not scalar @fqdn) {
	    error_out("No valid hostnames.<br>\n");
	    exit;
    }
    # add nameserver to domain
    my $xcp_request = {
	    action => "modify",
	    object => "domain",
	    cookie => $cookie,
	    attributes => {
		data => "nameserver_list",
		nameserver_list => []
	    }
    };

    foreach my $fqdn (@fqdn) {
	$fqdn_race_obj = RACE::DoRACE (Domain => $fqdn,
    	    	    	    	   EncodingType => $UNIVERSAL_ENCODING_TYPE);
	push @{$xcp_request->{attributes}{nameserver_list}},
		{   name => $fqdn_race_obj->{ConvertedDomain}, 
		    action => "add" 
		};
    }

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	# check to see why nameservers can't be modified.  If because
	# domain is locked, return a message to that affect.
	my $resp = $XML_Client->send_cmd(
	    {
		action      => 'get',
		object      => 'domain',
		cookie      => $cookie,
		attributes  => {
		    type    => 'status',
		}
	    }
	);

	my $error = "Unable to add nameservers: $response->{response_text}";     

	if ( $resp->{ attributes }{ lock_state } ) {
	    $error = "This domain is currently locked.  The lock must be removed to make DNS changes.";
	}

	error_out( $error );
	exit;
    }

    # response_code of 250 indicates that an asynchronous registry has
    # received the request and the completion of the request will
    # occur later.
    if ( $response->{response_code} == 250 )
    {
	$waiting_request = $response->{attributes}->{waiting_request};
    	modify_nameservers("Nameserver addition successfully submitted, could take up to ".time_to_wait().".");
    }
    else
    {
    	modify_nameservers("Nameserver added.");
    }
    
}

sub do_create_nameserver {

    my $domain = $in{domain};
    my $hostname = $in{hostname};
    my $ip = $in{ip};

    my $fqdn = "$hostname.$domain";

    my $new_fqdn_race_obj = RACE::DoRACE( Domain => $fqdn,
    	    	    	    	    	  EncodingType => $UNIVERSAL_ENCODING_TYPE );
    if ( not $new_fqdn_race_obj  || $new_fqdn_race_obj->{Error})
    {
    	error_out("Could not RACE nameserver [". $new_fqdn_race_obj->{Error}  ."]");
	exit;
    }
	
    my $xcp_request = {
		action => "create",
		object => "nameserver",
		cookie => $cookie,
		attributes => {
		    name => $new_fqdn_race_obj->{ConvertedDomain},
		    ipaddress => $ip,
		    }
		};

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	my $error = "Unable to create nameserver: $response->{response_text}";

	# check to see why nameservers can't be modified.  If because
	# domain is locked, return a message to that affect.
	my $resp = $XML_Client->send_cmd(
	    {
		action      => 'get',
		object      => 'domain',
		cookie      => $cookie,
		attributes  => {
		    type    => 'status',
		}
	    }
	);

	if ( $resp->{ attributes }{ lock_state } ) {
	    $error = "This domain is currently locked. The lock must be removed to allow nameservers to be modified."
	}

	error_out( $error );
	exit;
    }

    # response_code of 250 indicates that an asynchronous registry has
    # received the request and the completion of the request will
    # occur later.
    #
    # A response_code of 251 indicated that the nameserver has been created
    # in the OSRS database, but will not be usable by other domains until it
    # is attached to the parent domain.
    if ( $response->{response_code} == 250 ) {
	$waiting_request = $response->{attributes}->{waiting_request};
    	manage_nameservers("Name Server Creation successfully submitted, could take up to ".time_to_wait().".");
    } elsif ( $response->{ response_code } == 251 ) {
	manage_nameservers( $response->{ response_text } );
    } else {
    	manage_nameservers("Name Server Created");
    }
    
}

sub manage_profile {

    my (%HTML);
	
    # only allow the domain owner to access this routine
    if (not $reg_f_owner) {
	error_out("You do not have permission to access this feature.\n");
	exit;
    }

    $HTML{CGI} = $cgi;
    print_form("$path_templates/manage_profile.html",\%HTML);

}

sub change_ownership {

    my (%HTML);

    # only allow the domain owner to access this routine
    if (not $reg_f_owner) {
	error_out("You do not have permission to access this feature.\n");
	exit;
    }

    $HTML{CGI} = $cgi;
    print_form("$path_templates/change_ownership.html",\%HTML);

}

sub do_change_ownership {

    # only allow the domain owner to access this routine
    if (not $reg_f_owner) {
	error_out("You do not have permission to access this feature.\n");
	exit;
    }

    my $username = lc $in{reg_username};
    my $password = $in{reg_password};
    my $confirm_password = $in{confirm_password};
    my $flag_use_profile = $in{flag_use_profile};
    my $flag_move_all_domains = $in{flag_move_all_domains};
    my $domain = $in{domain};
    my ($xcp_request, $response);

    if (not $username) {
	error_out("Please provide a username.\n");
	exit;
    } elsif ($username !~ /^[a-z0-9]+$/) {
	error_out("Invalid syntax for new username.\n");
	exit;
    } elsif ($password ne $confirm_password) {
	error_out("Password mismatch.\n");
	exit;
    } elsif (not $password) {
	error_out("Please provide a password.\n");
	exit;
    } elsif ($password !~ /^[A-Za-z0-9\[\]\(\)!@\$\^,\.~\|=\-\+_\{\}\#]+$/) {
	error_out("Invalid syntax for new passsword.  The only allowed characters are all alphanumerics (A-Z, a-z, 0-9) and symbols []()!@\$^,.~|=-+_{}#\n");
	exit;	
    } elsif ($flag_use_profile and not $domain) {
	error_out("Please provide a domain to match the profile with.\n");
	exit;
    }

    my $domain_race_obj = RACE::DoRACE( Domain => $domain,
    	    	    	    	    	EncodingType => $UNIVERSAL_ENCODING_TYPE );
    
    if ( ( not $domain_race_obj ) ||
         ( $domain_race_obj->{Error} ) )
    {
    	error("Could not race encode the domain [".
	    	    $domain_race_obj->{Error}."]");
	exit;
    }

    $xcp_request = {
                action => "change",
                object => "ownership",
                cookie => $cookie,
                attributes => {
                    username => $username,
                    password => $password,
                    }
                };

    if ($flag_move_all_domains) {
        $xcp_request->{attributes}->{move_all} = 1;
    }

    if ($flag_use_profile) {
	$xcp_request->{attributes}->{reg_domain} = $domain_race_obj->{ConvertedDomain};
    }

    $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Unable to change domain's ownership: $response->{response_text}.\n");
	exit;
    }

    # make them logout
    
    # note that the cookie here is both needed for authentication and
    # for the command itself, hence why it appears twice in the request data
    $XML_Client->send_cmd( {
    	    	    action => "delete",
		    object => "cookie",
		    cookie => $cookie,
		    attributes => {
		    	cookie => $cookie,
		    	},
		    } );

    # make them login again so they are managing the domain under the new
    # profile
    $in{reg_domain} = $reg_domain;
    login("Ownership change successful.  Now logged in as new owner.\n");
}

# retrieve subuser information
sub get_subuser {

    my ($sub_id,$sub_username,$sub_permission);

    # get subuser for a given user
    my $xcp_request = {
		action => "get",
		object => "subuser",
		cookie => $cookie,
	    	};
    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Unable to retrieve subuser information: $response->{response_text}\n");
	exit;
    }

    $sub_id = $response->{attributes}->{id};
    $sub_username = $response->{attributes}->{username};
    $sub_permission = $response->{attributes}->{permission};

    return($sub_id,$sub_username,$sub_permission);
}

# display waiting request history for this domain
sub view_waiting_history {

    my (%HTML,$record);

    my $waiting_actions = {
    	    	    enhanced_update_nameservers => "Nameserver Update",
    	    	    update_nameservers => "Nameserver Update",
    	    	    add_nameserver => "Nameserver Update",
    	    	    remove_nameserver => "Nameserver Update",
    	    	    modify_contact_info => "Modify Contact Info",
		    sw_register => "Registration",
		    register_domain => "Registration",
		    process_sw_order => "Registration",
		    ukstatus => "Transfer",
		    renew_domain => "Renewal",
    	    	    };
    my $stati = {
    	    	};
    my $page = $in{page};
    if (not $page) { $page = 0 }

    # get domains for a given user
    my $xcp_request = {
    	    	action => "get",
		object => "domain",
		cookie => $cookie,
		attributes => {
		    type => "waiting_history",
		    }
	    };

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Failed attempt: $response->{response_text}\n");
	exit;
    }

    my $record_count = $response->{attributes}->{record_count};
    my @records = @{$response->{attributes}->{waiting_history}};

    $HTML{waiting_history} = "";
    if ( not scalar @records )
    {
    	$HTML{waiting_history} .= <<EOF
<TR bgcolor="#e0e0e0">
<TD colspan=5 align=center>No history found</TD>
</TR>
EOF
    }
    else
    {
	foreach $record (@records) {
	    my $w_action = $waiting_actions->{$record->{action}};
	    $w_action||=$record->{action}; # if undefined or new action

    	    $HTML{waiting_history} .= <<EOF;
<TR bgcolor="#e0e0e0">
    <TD align=center>$w_action&nbsp;</TD>
    <TD align=center>$record->{request_status}&nbsp;</TD>
    <TD>$record->{response_time}&nbsp;</TD>
    <TD align=center>$record->{response_code}&nbsp;</TD>
    <TD>$record->{response_text}&nbsp;</TD>
</TR>
EOF

	}
    }

    $HTML{CGI} = $cgi;
    print_form("$path_templates/waiting_history.html",\%HTML);
}


###########################################################################
# print a html header
sub print_header {
    if (not $flag_header_sent) {
	print "Content-type:  text/html\n\n";
	$flag_header_sent = 1;
    } 
}

##########################################################################
# substitute values on the specified template and print it to the client

# an optional 'type' arg can be passed: 'framed' specifies to pull in base.html
# as the outer frame and the given template as the inner frame
# 'single' specifies to use the given template alone
# the default behavior is 'framed'
sub print_form {
    
    my ($type,$content,$template_html);

    print_header();

    my @args = @_;
    my ($template,$HTML) = @args[0,1];
    if ($args[2]) { $type = $args[2] }
    else { $type = 'framed' }
$type='single'; #cambio horaciod
    if (not $domain_count) {
	$domain_count = 0;
    }
    
    my $action;
    # show domain search box if they have multiple domains
    if ($reg_f_owner and $domain_count > 1) {
	my $link;
	if ( $MANAGE{ allow_renewals } ) {
	    $link = qq(<a href ="$cgi?action=get_expire_domains&type=all">$domain_count Total</a>);
	    $action = "get_expire_domains";
	} else {
	    $link = qq(<a href="$cgi?action=view_domains">$domain_count Total</a><BR>);
	    $action = "view_domains";
	}

	if (not $HTML->{ auto_renew }) { $HTML->{auto_renew} = '*'};
	my @selected;
	my %selected_show = ( '*' => "All", Y => "Yes", N => "No" );

	foreach my $select (keys %selected_show){
	    if ( $select ne $HTML->{ auto_renew } ){
               push @selected, $select;
           }

	}

	$HTML->{SEARCH_BOX} = <<EOF;
 <form method=post action=\"$cgi\">
                    <font face=\"verdana, arial\" size=1> </font>
                    <input type=hidden name=action value=\"manage_domain\">
                    <span class=\"body_9\">Dominios en este perfil:</span> <b class=\"body_11\">$domain_count</b>
                    &nbsp;&nbsp;&nbsp;
                    <input type=text name=domain value=\"\" size=18>
                    <input type=submit value=\"buscar\">
                    <a href=\"$cgi?action=view_domains\"><span class=\"body_10\">(Ver
                    todos)</span></a>
                  </form>
EOF
}


    $reg_domain_race_obj = RACE::UndoRACE (Domain => pack('A*',$reg_domain),
                                           EncodingType => $UNIVERSAL_ENCODING_TYPE);	
    $HTML->{DOMAIN_NAME} = $reg_domain_race_obj->{OriginalDomain};
    $HTML->{EXPIREDATE} = $expiredate;
    $HTML->{WAITING_REQUEST} = $waiting_request;
    $HTML->{TOP_NAVBAR} = make_top_navbar();

    if ($type eq 'framed') {
	$HTML->{AUTORENDATA} = "";
	if ($MANAGE{allow_auto_renewal_message}){
	    
     	    my $xcp_request = {
     		action => "get",
     		object => "domain",
     		cookie => $cookie,
     		attributes => { 
     		    type => "expire_action",
     		}
	    };
	    
	    my $response = $XML_Client->send_cmd( $xcp_request );
	    my $flag = $response->{attributes}->{auto_renew};
	    if($flag){
		my $expiry_epoch = get_expiry_epoch_time($expiredate);
		my $new_epoch = $expiry_epoch - 30 * 86400;
		$HTML->{AUTORENDATA} = get_date_from_epoch($new_epoch, "stripped"); 
		$HTML->{AUTORENDATA} = get_content("$path_templates/base_autoren.html", $HTML);
	    }
	}
	$HTML->{CONTENT} = get_content("$template",$HTML);

        if($MANAGE{allow_renewals}){ get_warning_type(); }
	
        if ((defined $t_mode) and $t_mode) {
	    $template_html = "base2.html";
	    
	    if (($t_mode == $T_EXPIRED) || ($t_mode == ($T_EXPIRED + $T_EXPIRING))) {
	       $HTML->{EXPIRED} = "<a href=\"$cgi?action=get_expire_domains&type=expired\">Click here to see the list of names that will be deleted if not renewed.</a>";
	   } 

	    if (($t_mode == $T_EXPIRING) || ($t_mode == ($T_EXPIRED + $T_EXPIRING))) {
		$HTML->{EXPIRING} = "<a href=\"$cgi?action=get_expire_domains&type=expiring&\">Click here to see the list of names expiring within the next $notice_days days.</a>";      
	    } 
	} else {
	    $template_html = "base.html"; 
	}
 
        $content .= get_content("$path_templates/$template_html",$HTML);
    } else {
        $content .= get_content("$template", $HTML);
    }

    print $content;
    print 'ok';
}

sub make_top_navbar {

    my ($navbar);

    #for .de we mask billing contact into zone contact
    my $billing_con_name = "Billing";
    if($reg_domain =~ /de$/) {
	$billing_con_name = "Zone";
    }

    if ($reg_f_owner) {
	$navbar = "<a href=\"$cgi?action=manage_profile\">Profile</a>";
	$navbar .= <<EOF;
| <a href="$cgi?action=modify_contact&type=owner">Organization</a>
EOF

        #
        # .ca does not have a billing contact.
        #
        if ($reg_domain =~ /ca$/)
        {
	   $navbar .= <<EOF;
| <a href="$cgi?action=modify_contact&type=admin">Admin</a>
| <a href="$cgi?action=modify_contact&type=tech">Technical</a>
| <a href="$cgi?action=modify_nameservers">Name Servers</a>
| <a href="$cgi?action=whois_rsp_info">Reseller Contact</a>
| <a href="$cgi?action=logout">Logout</a>
EOF
        } elsif ( $reg_domain =~ /$OPENSRS{ TLDS_SUPPORTING_LOCKING }/ ) {
	   $navbar .= <<EOF;
| <a href="$cgi?action=modify_contact&type=admin">Admin</a>
| <a href="$cgi?action=modify_contact&type=billing">$billing_con_name</a>
| <a href="$cgi?action=modify_contact&type=tech">Technical</a>
| <a href="$cgi?action=modify_nameservers">Name Servers</a>
| <a href="$cgi?action=whois_rsp_info">Reseller Contact</a>
<BR>
<a href="$cgi?action=domain_locking">Domain Locking</a>
| <a href="$cgi?action=logout">Logout</a>
EOF
        } else {
	   $navbar .= <<EOF;
| <a href="$cgi?action=modify_contact&type=admin">Admin</a>
| <a href="$cgi?action=modify_contact&type=billing">$billing_con_name</a>
| <a href="$cgi?action=modify_contact&type=tech">Technical</a>
| <a href="$cgi?action=modify_nameservers">Name Servers</a>
| <a href="$cgi?action=whois_rsp_info">Reseller Contact</a>
<BR>
Domain Locking
| <a href="$cgi?action=logout">Logout</a>
EOF
	}

        $navbar =~ /(.+Name Servers<\/a>)(.*)/s;
        if ($capabilities->{domain_extras} or $dns_errors) {
           $navbar = $1 . " | <a href=\"$cgi?action=modify_domain_extras\">Domain Extras<\/a>\n" . $2;
    	} else {
           $navbar = $1 . " | Domain Extras\n" . $2;
        }

	return $navbar;
    } else {

	# these first two are never available for sub-users
	$navbar .= "Profile\n";

    	# The owner contact type cannot be modified if the 
	# domain ends with uk
#	if ( ( $reg_permission & $PERMISSIONS{f_modify_owner} ) &&
#	     ( $reg_domain !~ /uk$/ ) ) {
	if ($reg_permission & $PERMISSIONS{f_modify_owner}) {
	    $navbar .= <<EOF;
| <a href="$cgi?action=modify_contact&type=owner">Organization</a>
EOF
        } else {
	    $navbar .= "| Organization\n";
	}
	if ($reg_permission & $PERMISSIONS{f_modify_admin}) {
	    $navbar .= <<EOF;
| <a href="$cgi?action=modify_contact&type=admin">Admin</a>
EOF
        } else {
	    $navbar .= "| Admin\n";
	}
	if (($reg_permission & $PERMISSIONS{f_modify_billing}) && ($reg_domain !~ /ca$/)) {
	    $navbar .= <<EOF;
| <a href="$cgi?action=modify_contact&type=billing">$billing_con_name</a>
EOF
        } else {
	    $navbar .= "| $billing_con_name\n";
	}
	if ($reg_permission & $PERMISSIONS{f_modify_tech}) {
	    $navbar .= <<EOF;
| <a href="$cgi?action=modify_contact&type=tech">Technical</a>
EOF
        } else {
	    $navbar .= "| Technical\n";
	}

	if ($reg_permission & $PERMISSIONS{f_modify_nameservers}) {
	    $navbar .= <<EOF;
| <a href="$cgi?action=modify_nameservers">Manage Name Servers</a>
EOF
        } else {
	    $navbar .= "| Manage Name Servers\n";
	}

        if (($reg_permission & $PERMISSIONS{f_modify_domain_extras}) && $capabilities->{domain_extras}) {
            $navbar .= <<EOF;
| <a href="$cgi?action=modify_domain_extras">Domain Extras</a>
EOF
        } else {
            $navbar .= "| Domain Extras\n";
        }

	if ($reg_permission & $PERMISSIONS{f_modify_whois_rsp_info}) {
	     $navbar .= <<EOF
| <a href="$cgi?action=whois_rsp_info">Reseller Contact</a>
EOF
	} else {
	    $navbar .= "| Reseller Contact";
	}

	$navbar .= <<EOF;
<BR>
Domain Locking
| <a href="$cgi?action=logout">Logout</a>
EOF

	return $navbar;

    }
}

# handy method to show default error page
sub error_out {
    my ( $error_msg, $domain ) = @_;
    my (%HTML);

    $HTML{CGI} = $cgi;
    $HTML{ERROR} = $error_msg;
    $HTML{SHOW_PASS} = "";
    
    if ( defined $domain and defined $MANAGE{allow_password_requests} and $MANAGE{allow_password_requests} ) {
    
        $domain = RACE::DoRACE( Domain => $domain, EncodingType => $UNIVERSAL_ENCODING_TYPE );
	my $race_domain = $domain->{ConvertedDomain};
    
        if ( $MANAGE{password_send_to_admin} ) {
	    $HTML{SHOW_PASS} =  qq(Click <a href="$cgi?action=send_password&domain=$race_domain&user=admin">here</a> to have lost password sent to admin.);
	} 
	if ( $MANAGE{password_send_to_owner} ) {
	    $HTML{SHOW_PASS} .= qq(<br>Click <a href="$cgi?action=send_password&domain=$race_domain&user=owner">here</a> to have lost password sent to owner.);
	}
    }

    print_form("$path_templates/error.html",\%HTML,'single');
}

sub escape_hash_values {

    my $hash_ref = shift;
    foreach my $hash_key ( keys %$hash_ref )
    {
    	if ( ref( $hash_ref->{$hash_key} ) eq "HASH" )
	{
	    escape_hash_values( $hash_ref->{$hash_key} );
	}
    	elsif ( ref( $hash_ref->{$hash_key} ) eq "ARRAY" )
	{
	    escape_array_values( $hash_ref->{$hash_key} );
	}
	else
	{
    	    $hash_ref->{$hash_key} = escape( $hash_ref->{$hash_key} );
	}
    }
}

sub escape_array_values {

    my $array_ref = shift;
    foreach my $array_element ( @$array_ref )
    {
    	if ( ref( $array_element ) eq "HASH" )
	{
	    escape_hash_values( $array_element );
	}
    	elsif ( ref( $array_element ) eq "ARRAY" )
	{
	    escape_array_values( $array_element );
	}
	else
	{
    	    $array_element = escape( $array_element );
	}
    }
}

sub escape {
    my $string = shift;
    $string =~ s/\"/&quot;/g;
    return $string;
}

####################################################
# grab the contents of a template, substitute any supplied values, and return
# the results
sub get_content {
    
    my $content;
    
    my ($template,$HTML) = @_;
    open (FILE, "<$template") or die "Couldn't open $template: $!\n";
    while (<FILE>) {
	s/{{(.*?)}}/pack('A*',$HTML->{$1})/eg;
	$content .= $_;
    } 
    close FILE;
    
    return $content;
    
}

# attempt to validate a user's cookie
sub validate {

    my ($expire,$response);
    $reg_username = "";
    if (exists $cookies{$COOKIE_KEY}) {

	$cookie = $cookies{$COOKIE_KEY};

	my $xcp_request = {
		    action => "get",
		    object => "userinfo",
		    cookie => $cookie,
		    };

	$response = $XML_Client->send_cmd( $xcp_request );
	if (not $response->{is_success}) {
	    return undef;
	}
	$reg_username = $response->{attributes}->{username};
	$reg_domain = $response->{attributes}->{domain};
	$reg_domain_race_obj = RACE::UndoRACE (Domain => pack('A*',$reg_domain),
    	    	    	    	    	       EncodingType => $UNIVERSAL_ENCODING_TYPE);
	$reg_f_owner = $response->{attributes}->{f_owner};
	$reg_permission = $response->{attributes}->{permission};
	$domain_count = $response->{attributes}->{domain_count};
	$expiredate = $response->{attributes}->{expiredate};
	$waiting_request = $response->{attributes}->{waiting_request};
	$capabilities = $response->{attributes}->{capabilities};
	if($SHOW_DNS_ERRORS) {
	    $dns_errors = $response->{attributes}->{dns_errors};
	}
	
	my $domain_extras = 0;
	while (my($k,$v) = each %$capabilities) {
	    $domain_extras += $v;
	}
	$capabilities->{domain_extras} = $domain_extras;

	return 1;
	
    } else {
	return undef;
    }
    
}

# get cookies from the client
sub GetCookies {

    my ($cookie, %cookies,$key,$value);

    foreach $cookie (split /\; /, $ENV{HTTP_COOKIE})	{
	($key, $value) = (split /=/, $cookie)[0,1];
	$value =~ s/\\0/\n/g;
	$cookies{$key} = $value;
    }
    return %cookies;
}

#####################################################################
# authenticate user
sub login {
    
    my $message = shift;

    $reg_username = $in{reg_username};
    $reg_password = $in{reg_password}."2008";
    $reg_domain = $in{reg_domain};
    if ( not $in{reg_domain} )
    {
      	error_out("Ingrese un nombre de dominio.");
	exit()
    }
   
    if ( $in{reg_domain} =~ /^www\..*$OPENSRS{OPENSRS_TLDS_REGEX}$/i   )
    {
        error_out("Por favor no coloque www como parte del dominio.");
        exit()
    }
 
    $reg_domain_race_obj = RACE::DoRACE (Domain => $reg_domain,
    	    	    	    	    	 EncodingType => $UNIVERSAL_ENCODING_TYPE);

    if($reg_domain_race_obj->{Error})
    {
error_out("No puedo codificar la p&aacute;gina web. Aseg&uacute;rese de que la codificaci&oacute;n de su navegador es UTF-8. ");
	exit;
    }
    
    my ($tld) = $reg_domain_race_obj->{ConvertedDomain} =~ /$OPENSRS{OPENSRS_TLDS_REGEX}$/;
    if ( exists $CANT_SUPPORT{$tld} )
    {
    	my $message = <<EOF;
You cannot currently make changes to $tld domains through this<BR>
interface. We will have a $tld enabled Manage Domain interface in place as<BR>
soon as possible.</P>
If need to make emergency nameserver changes to your domain, please contact
<a href="mailto:support\@opensrs.org">support\@opensrs.org</a>.
EOF
    	error_out($message);
	exit;
    }

    # get permissions for a given user
    my $xcp_request = {
    	    	action => "set",
		object => "cookie",
		attributes => {
    		    domain => $reg_domain_race_obj->{ConvertedDomain},
		    reg_username => $reg_username,
		    reg_password => $reg_password,
		    }
	    };

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	my $error;
	if ( $response->{response_code} == 415 ) {    # bad un/pw
	
	    $error = "Error: Combinaci&oacute;n de usuario/password inv&aacute;lidas para $reg_domain.<br><br>";
	
	    $response->{response_text} =~ s/\n/<br>\n/g;
	    $error .= $response->{response_text};    
   
	    error_out( $error, $reg_domain );
	    exit;
	
	} else {				    # any other error
	    $error = "$response->{response_text}<br>Contacte a soporte para asistencia.";
	    
	    error_out( $error );
	    exit;
	}
    }
    
    if ( $response->{redirect_url} ) {
	print "Location: ".$response->{redirect_url}."\n\n";
    }
    
    $domain_count = $response->{attributes}->{domain_count};
    $reg_domain_race_obj = RACE::UndoRACE (Domain => pack('A*',$reg_domain), EncodingType => $UNIVERSAL_ENCODING_TYPE);

    $reg_permission = $response->{attributes}->{permission};
    $reg_f_owner = $response->{attributes}->{f_owner};
    $expiredate = $response->{attributes}->{expiredate};
    $last_access_time = $response->{attributes}->{last_access_time};
    $last_ip = $response->{attributes}->{last_ip};
    # XXX what about waiting request stuff???
    $waiting_request = $response->{attributes}->{waiting_request};
    $cookie = $response->{attributes}->{cookie};

    #run validate() here to get capabilities, which is used to decide
    #how to diplay the "Domain Extras" page.
    $cookies{$COOKIE_KEY} = $cookie;
    validate();

    my $path = "";

    print "Content-type:  text/html\n";
    print "Set-Cookie: $COOKIE_KEY=$cookie; PATH=$path\n";
    print "\n";
    $flag_header_sent = 1;

    #print "reg_user => $reg_user; reg_pass => $reg_pass;domain => $reg_domain<br>"; 
    main_menu($message);
      
}

#############################################################################
# logout user (delete cookie)
sub logout {
    
    my ($cookie);
    
    if (exists($cookies{$COOKIE_KEY})) {
	$cookie = $cookies{$COOKIE_KEY};

	my $xcp_request = {
		    action => "delete",
		    object => "cookie",
		    cookie => $cookie,
		    attributes => {
			cookie => $cookie,
			}
		   };

	$XML_Client->send_cmd( $xcp_request );

    }
    
    show_login();
}

########################################################
# dynamically build a country list
sub build_country_list {
    
    my ($id,$title,$html);
    
    my ($default_id) = @_; 
    
    if (not $default_id) { $default_id = 'US' }
    else {
	$default_id =~ tr/a-z/A-Z/;
    }
    
    open (FILE, "<$PATH_TEMPLATES/countries") or die "Can't open $PATH_TEMPLATES/countries: $!\n";
    while (<FILE>) {
	
	chomp;
	if (/^(\w{2})\s+(.+)/) {
	    $id = $1;
	    $title = $2;
	} else {
	    next;
	}
	
	if ($id eq $default_id) {
	    $html .= <<EOF;
<option value="$id" selected>$title
EOF
	} else {
	    $html .= <<EOF;
<option value="$id">$title
EOF
	}
    }
    close(FILE);
    return $html;
}

########################################################
# dynamically build all .ca legal types.
sub build_ca_domain_legal_types
{
   my $type	= shift;
   my $string	= "<select name=legal_type>\n";
   my ($selected, $key);

   foreach $key (@CA_LEGAL_TYPES_ORDER)
   {
      $selected	= "";
      $selected	= " selected " if ($type =~ /$key/i);
      $string	.= "   <option value=\"$key\" $selected>$CA_LEGAL_TYPES{$key}\n";
   }
   $string .= "</select>";
   return $string;
}

sub build_ca_language_preferences
{
   my $type	= shift;
   my $string	= "<TR>\n<TD ALIGN=right bgcolor=\"#d0d0d0\">\n<font face=\"verdana, arial\" size=2><B>Preferred Language:</B></font></TD>\n<TD>\n<select name=language>\n";
   my ($selected, $key);

   foreach $key (keys %CA_LANGUAGE_TYPES)
   {
      $selected = "";
      $selected = "SELECTED" if ($type =~ /$key/i);
      $string   .= "   <option value=\"$key\" $selected>$CA_LANGUAGE_TYPES{$key}\n";
   }
   $string .= "</select>\n</TD></TR>";
   return $string;

}

sub build_ca_nationality_pulldown
{
   my $type	= shift;
   my $string	= "<TR>\n<TD ALIGN=right bgcolor=\"#d0d0d0\">\n<font face=\"verdana, arial\" size=2><B>Nationality:</B></font></TD>\n<TD>\n<select name=nationality>\n";
   my ($selected, $key);

   foreach $key (keys %CA_NATIONALITIES)
   {
      $selected = "";
      $selected = "SELECTED" if ($type =~ /$key/i);
      $string   .= "   <option value=\"$key\" $selected>$CA_NATIONALITIES{$key}\n";
   }
   $string .= "</select>\n</TD></TR>";
   return $string;

}

sub get_expiry_epoch_time {
    my $tmptime = $_[0];
    my @db = $tmptime =~ /^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;
    return timelocal($db[5], $db[4], $db[3], $db[2], $db[1]-1, $db[0]);
}

sub get_date_from_epoch {
    my ($ampm);
    my $time = shift;
    my $flag = shift;
    my @months = qw( Ene Feb Mar Abr May Jun Jul Ago Sep Oct Nov Dic );
    my ($min,$hour,$day,$month,$year) = (localtime($time))[1,2,3,4,5];
    $year += 1900;
    if ($hour > 12) {
        $ampm = "pm";
        $hour -= 12;
    } else {
        $ampm = "am";
    }
    if ($flag eq 'stripped') {
        return sprintf("%3s %2d, %4d",
                       $months[$month],
                       $day,
                       $year);
    } else {
        return sprintf("%2d:%02d %2s %3s %2d, %4d",
                       $hour,
                       $min,
                       $ampm,
                       $months[$month],
                       $day,
                       $year);
    }
}  


sub get_expire_domains {
    #get list of expired domains or ones to expire within $notice_days days
    #/manage?action=get_expire_domains&type={expired/expiring}

    my ($error,$sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst);
    my (%HTML,$domain_name,$domain_html,$next_page,$previous_page);
    my @domains = () ;
    my $title = "List of domains due to expire within next $notice_days days";
    my @auto_renew = ();
    my @expiredate = ();
    my @sponsoring = ();
    my @expired_index = (); # keep array of expired domain indexes
    my @expiring_index = (); # array of domain indexes with date whithin $notice_days days
    my $type = $in{type};
    my $xcp_request = undef;
    my $response = undef;
    my $option = "";
    my $type_string = "";
    my $SELECT_ALL = "Select All";
    my $DESELECT_ALL = "De-select All";
    my $select_all_mode = $in{select_all_mode} || $SELECT_ALL ;
    my $select_all_renew_mode = $in{select_all_renew_mode} || $SELECT_ALL ;
    my $submitted = $in{submitted}; # flag to indicate if the user actually submitted request
    my $prev_submitted = $submitted;
    my $not_first_time = $in{not_first_time};
    my $cb_auto_set = $in{cb_auto_set} || "0";
    my $cb_renew_set = $in{cb_renew_set} || "0";
    my $first_reg_domain = $in{first_reg_domain} || $reg_domain;
    my $auto_update_status = 0;
    my $updated_domain_html = ""; 
    my $page = $in{page};
    my $hpage = $in{hpage} || $page;
    my $select_all_autorenew = $in{select_all_autorenew};
    my $select_all_renew = $in{select_all_renew};
    my $submit_renewals = $in{submit_renewals};
    my $dlterm0 = $in{"dlterm-0"};
    my %hterm = {};
    my %hauto = {};
    my %hrenew = {};
    my @hdomain= ();
    my $rtmp;
    my $i=0;
    my @status_msg = ();
    my $diff_rsp = 0;
    my $with_encoding_types=1;

    if (not $page) { $page = 0 }
    if ($submit_renewals) { # user submitted the request
        $submitted = 1;
    }
    $in{ sort_by } = 'ASC'
        if not $in{ sort_by } or $in{ sort_by } !~ /^(ASC|DESC)$/;

    my $sort = $in{sort_by};

    my $order_by =  $in{ orderby };
    if ( not $order_by ) { $order_by = 'name' }

    my $limit = $in{ limit };
    if( not $limit ) { $limit = 40 }

    my $auto_renew = $in{ auto_renew };
    if( not $auto_renew ) { $auto_renew = '*' }

    my $domain = lc $in{domain};
    $domain  = trim($domain); 

    my $domain_search = $in{domain_search};
    $domain_search  = trim($domain_search);

    if($domain_search =~ /[^\*a-zA-Z0-9_.-]/i){
	if($domain_search =~ /\*/g){
	    error_out("Inavlid syntax! (cannot use wildcard with multilingual domains)");
	    exit;
	}else{
	    my $local_domain_race_obj = RACE::DoRACE( Domain => $domain_search,
                                              EncodingType => $UNIVERSAL_ENCODING_TYPE );
	    if ( not $local_domain_race_obj ||
		$local_domain_race_obj->{Error} ){
		error_out("Failed to race encode the domain: [".$local_domain_race_obj->{Error}."]");
		exit;
	    }
	    $domain_search = $local_domain_race_obj->{ConvertedDomain};
	}
    }

    if( not $domain ) { $domain = '*' }
    if( not $domain_search ) { $domain_search = '*' }
    my $expiry_date = $in{ expiry_date };
    
    foreach my $key (keys %in){
       $rtmp = $key;
       if ($rtmp =~ /^domain-/) {
          $hdomain[$i++]=$in{"$rtmp"};
       }
    }
    my $arraycnt = @hdomain; 
    foreach my $key (0..$arraycnt){
       $hterm{$hdomain[$key]} = $in{"dlterm-$hdomain[$key]"};
       $hauto{$hdomain[$key]} = $in{"autorenew-$hdomain[$key]"};
       $hrenew{$hdomain[$key]} = $in{"renew-$hdomain[$key]"};
    }
    if ($type eq "") { error_out("Missing type for $action"); return; };
    if ((lc $type) eq "expired") {
	$response = do_expired_domains($page, $with_encoding_types, $sort);
	$type_string = "&type=expired";
	$title = "List of domains that will be deleted if not renewed";
    } elsif ((lc $type) eq "expiring") {
	$response = do_expiring_domains($page, $with_encoding_types, $sort);
	$type_string = "&type=expiring";
	$title = "List of domains expiring within the next $notice_days days";
    } elsif ((lc $type) eq "all") {
	$response = do_all_domains($page, $with_encoding_types, $sort, $domain, $domain_search, $auto_renew, $limit, $order_by, $expiry_date);
	$type_string = "&type=all";
	$title = "List of domains in profile";
    } else {
        error_out("<br><font size=+4><STRONG>Wrong type used</STRONG></font><br>");
        return;
    }
    my $remainder = $response->{attributes}->{remainder}; # are there more domains to show?

    # Get domains: separate domain names from enctypes 
    @domains = get_domains_store_enctypes($response);

    if (defined $domains[0]){
	#Get expiredate & auto_renew arrays:
	for (my $i=0; $i<@domains; $i++){
	    $auto_renew[$i] = $response->{attributes}->{ext_results}->[$i]->{$domains[$i]}->{auto_renew};
	    $expiredate[$i] = $response->{attributes}->{ext_results}->[$i]->{$domains[$i]}->{expiredate};
	    $sponsoring[$i] = $response->{attributes}->{ext_results}->[$i]->{$domains[$i]}->{sponsoring_rsp};
	}
    }
    
    my $ref = ref ($response->{attributes}->{domain_list});
    if ($ref eq "ARRAY" and (defined $domains[0])) {
       for my $i ( 0..$#expiredate) {
	   my $cb_auto="";
	   $status_msg[$i] = "";
	   my $dn_race_obj = RACE::UndoRACE( Domain => pack('A*',$domains[$i]), 
                                             EncodingType => $UNIVERSAL_ENCODING_TYPE );
	   my $orig_dom_name = $dn_race_obj->{OriginalDomain};

	   if ($select_all_autorenew) { # user pressed SELECT_ALL for auto renew this time
	       if ($select_all_mode eq $SELECT_ALL){
		   $cb_auto="CHECKED";
	       } elsif ($select_all_mode eq $DESELECT_ALL) {
		   $cb_auto="";
	       }
	   } else { # user did not press SELECT_ALL for auto renew this time
	       if (($auto_renew[$i] == 1) and !$not_first_time) {
		   $cb_auto="CHECKED";
	       } else { # set preserved state:
		   $cb_auto=$hauto{$domains[$i]};
	       }
	   }
	   my $cb_renew =$hrenew{$domains[$i]}; # set preserved state
	   if ( $select_all_renew)  { # user pressed SELECT_ALL for  renew this time:
	       if ($select_all_renew_mode eq $SELECT_ALL) {
		   $cb_renew = "CHECKED";
	       }  elsif ($select_all_renew_mode eq $DESELECT_ALL) {
		   $cb_renew = "";
	       }
	   }
	   $auto_update_status = 0;
	   if ($submitted and $sponsoring[$i]) { # process domains if user submitted the request:
	       if ( $cb_auto eq "CHECKED") {
		   # change auto-renew:
		   if (!$auto_renew[$i]) {
		       change_profile($domains[$i]);
		       $status_msg[$i] = renewals_autorenew(1);
		       $auto_update_status = 1;
		   }
	       } else { 
		   if ($auto_renew[$i] ) {
		       change_profile($domains[$i]);
		       $status_msg[$i] = renewals_autorenew(0);
		       $auto_update_status = 1;
		   }
	       }
	       if ($i == $#expiredate) { # if this is the last one - change the profile back:
		   if ($reg_domain ne $first_reg_domain) {
		       change_profile($first_reg_domain);
		   }
	       }
	       if ( $cb_renew eq "CHECKED") { # renew submitted domains:
		   my ($exp_year) = $expiredate[$i] =~ m/^(\d+)/;
		   if ($status_msg[$i]){ 
		       $status_msg[$i] = $status_msg[$i] . ", " . renewals_renew($domains[$i], $exp_year, $hterm{$domains[$i]});
		   } else {
		       $status_msg[$i] = renewals_renew($domains[$i], $exp_year, $hterm{$domains[$i]});
		   }
	       }
	       if (($cb_renew eq "CHECKED") or $auto_update_status){
		   $updated_domain_html .= "<tr><td>$orig_dom_name</td>";
		   $updated_domain_html .= "<td>$status_msg[$i]</td></tr>";
	       }
	   }
	   my %termlist= ( '1' => ' 1 year',  '2' => ' 2 years', '3' => ' 3 years', '4' => ' 4 years',
                           '5' => ' 5 years', '6' => ' 6 years', '7' => ' 7 years', '8' => ' 8 years',
                           '9' => ' 9 years', '10' => '10 years',
			   );
           my $option_data = "";  
	   if ($hterm{$domains[$i]}) {
	       $option_data = get_select_content($hterm{$domains[$i]}, \%termlist);
	   }
	   else {
	       $option_data = get_select_content('1', \%termlist);
	   } 

	   my $domain_link;
	   if ( $type eq 'all' and $domains[ $i ] ne $reg_domain ) {
	       $domain_link = qq(<a href="$cgi?action=manage_domain&domain=$domains[ $i ]">$orig_dom_name</a>);
	   } else {
	       $domain_link = $orig_dom_name;
	   }

	   # only show renewal options for domains sponsored by this RSP
	   if ( $sponsoring[ $i ] ) {
	       $domain_html .= <<EOF;
<tr NOSAVE>
    <td>
	<input type="hidden" name="enctype-$in{enctype}" value="$in{enctype}"> 
	<input type="hidden" name="domain-$domains[$i]" value="$domains[$i]">
	$domain_link
    </td>
    <td align="center" nowrap>$expiredate[$i]</td>
    <td align="center" nowrap bgcolor="#9A9AFF"><input type=checkbox name="autorenew-$domains[$i]" value="CHECKED" $cb_auto >&nbsp;</td>
    <td align="center" nowrap bgcolor="#AEAEFF"><input type=checkbox name="renew-$domains[$i]" value="CHECKED" $cb_renew >&nbsp;</td>
    <td align="center" nowrap bgcolor="#AEAEFF">
	<input type="hidden" name="hterm-$domains[$i]" value="hterm-$domains[$i]">
	<select name="dlterm-$domains[$i]"> 
	    $option_data
	</select>
    </td>
</tr>
EOF
	   } else {
	       $domain_html .= <<EOF;
<tr NOSAVE>
    <td>$domain_link</td>
    <td align="center" nowrap>$expiredate[$i]</td>
    <td align="center" nowrap bgcolor="#9A9AFF" colspan="3">
	Can't renew this domain. See below.
    </td>
<tr>
EOF
	       $diff_rsp = 1;
	   }
       } # end of the for loop

       if ($updated_domain_html) {
	   $HTML{DOMAINS} = $updated_domain_html;
	   $HTML{TITLE} = "Update Status";
	   $HTML{CGI} = $cgi;
	   print_form("$path_templates/expire_domains_result.html",\%HTML);
	   exit;                       
       }
       # change names of submit buttons acording to the user's choice:
       if ( $select_all_autorenew ) {
	   if ($select_all_mode eq $SELECT_ALL) {
	       $select_all_mode = $DESELECT_ALL;
	   } else {
	       $select_all_mode = $SELECT_ALL;
	   }
	   $cb_auto_set = "1";
       }
       if ( $select_all_renew) {
	   if ($select_all_renew_mode eq $SELECT_ALL) {
	       $select_all_renew_mode = $DESELECT_ALL;
	   }  else {
	       $select_all_renew_mode = $SELECT_ALL;
	   }
	   $cb_renew_set = "1";
       }
       $prev_submitted = $submitted;
       if ($submitted) {
	   $submitted =0;
       }
       my (%HTML); 
       $HTML{select_all_mode}= $select_all_mode;
       $HTML{select_all_renew_mode}= $select_all_renew_mode;

       $domain_html .= get_content("$path_templates/manage_rview_btns.html", \%HTML);
   } 
    
    my $num_page_links = 10;	
    my $navbar = make_navbar(
                    "get_expire_domains&type=$in{type}&sort_by=$sort&expiry_date=$in{expiry_date}&limit=$limit&orderby=$in{orderby}&domain=$in{domain}&domain_search=$in{domain_search}&auto_renew=$in{auto_renew}", $response->{ attributes }{ count }, $limit, $num_page_links, $page);

    $navbar .= "<br><br>\n";

    if ( $diff_rsp ) {
       $HTML{diff_rsp_msg} = <<EOF;
<tr>
    <td colspan="5" bgcolor="#e0e0e0">
	<FONT COLOR="RED">Note:</FONT>
	Some domains are not sponsored by this domain provider, and can't be
	renewed/auto_renewed from this interface.
    </td>
</tr>
EOF
    }
    $not_first_time =1;
    $HTML{DOMAINS} = $domain_html;
    $HTML{TITLE} = $title;
    $HTML{CGI} = $cgi;
    $HTML{ACTION_VALUE} = "get_expire_domains";
    $HTML{TYPE} = $type;
    $HTML{SELECT_ALL_MODE} = $select_all_mode;
    $HTML{SELECT_ALL_RENEW_MODE} = $select_all_renew_mode;
    $HTML{CB_AUTO_SET} = $cb_auto_set;
    $HTML{CB_RENEW_SET} = $cb_renew_set;
    $HTML{SUBMITTED} = $submitted;
    $HTML{NOT_FIRST_TIME} = $not_first_time;
    $HTML{NAVBAR} = $navbar;
    $HTML{HPAGE} = $hpage;
    $HTML{PAGE} = $page;
    $HTML{FIRST_REG_DOMAIN} = $first_reg_domain;
    $HTML{domain} = $in{domain};
    $HTML{domain_search} = $in{domain_search};
    $HTML{limit} = $limit;
    $HTML{auto_renew} = $in{auto_renew};
    $HTML{expiry_date} = $in{expiry_date};
    $HTML{DOMAIN_COUNT} = $response->{ attributes }{ count };
    $HTML{new_sort} = $sort eq 'ASC' ? 'DESC' : 'ASC';
    $HTML{TYPE} = $in{type};

    print_form("$path_templates/view_expire_domains.html",\%HTML);
}

sub get_select_content {
    my $sel_opt = shift;
    my $hashptr = shift;  
    my $htmldata = "";
    foreach my $item (sort {$a<=>$b} keys %$hashptr){
	if ($item eq $sel_opt){
	    $htmldata .= "<option value=\"$item\" SELECTED> $$hashptr{$item}\n";	    
	}
	else{
	    $htmldata .= "<option value=\"$item\"> $$hashptr{$item}\n";	    
	}
    }
    return $htmldata;
}

sub get_warning_type {
    # ret: $T_EXPIRED, $T_EXPIRING, or both, or 0 or undef
    if (! $MANAGE{allow_renewals}){return 0;}

    my @domains=();
    my $rc_1 = 0;
    my $rc_2 = 0;
    my $error;

    ### Get expired domains:
    my $response = do_expired_domains();
    if ((!$response->{is_success}) or (!defined $response)) {
        return undef;
    } 
    @domains = do_get_actual_domains($response);
    if (defined $domains[0]) {$rc_1 = $T_EXPIRED;}

    ### Get expiring domains:
    $response = do_expiring_domains();
    if (not $response->{is_success} or (!defined $response)) {
       return undef;    
    } 
    @domains = do_get_actual_domains($response);
    if (defined $domains[0]) {$rc_2 = $T_EXPIRING;}    
    $t_mode = $rc_1 + $rc_2; 
    return ($t_mode); 
}

# Separetes pairs 'domain and enctype', stored in 'domain_list' of the $response.
# Returns @domains, containing domain names and stores enctypes of these domains
# in hash 'enctypes', which is a global variable.
sub get_domains_store_enctypes {
    my ($domain_name, @domains);
    my $response = shift;

    my @domain_names = @{$response->{attributes}->{domain_list}};
    my $keytmp = "";
    my $valtmp = "";

    my $i = 0;
    foreach $domain_name (@domain_names) {
	my $keytmp = $domain_name->{domain};
	my $valtmp = $domain_name->{encoding_type};
	$enctypes{$keytmp} = $valtmp;
	$domains[$i]=$keytmp;
	$i++;
    }
    return @domains;
}

sub get_specific_enctypes {
   my ($domain_name,$domain_html);

   my $page = $in{page};
   if (not $page) { $page = 0 }

   # get domains for a given user
   my $xcp_request = {
	      action => "get",
	      object => "domain",
	      cookie => $cookie,
	      attributes => {
		  page => $page,
		  type => "list",
		  with_encoding_types => 1,
		  }
	  };

   my $response = $XML_Client->send_cmd( $xcp_request );
   if (not $response->{is_success}) {
      error_out("Failed attempt: $response->{response_text}\n");
      exit;
   }
   my $remainder = $response->{attributes}->{remainder}; # are there more domains to show?
   my @domain_names = @{$response->{attributes}->{domain_list}};
   my $domain_name_race_obj = undef;
   my $keytmp = "";
   my $valtmp = "";

  foreach $domain_name (@domain_names) {
	my $keytmp = $domain_name->{domain};
	my $valtmp = $domain_name->{encoding_type};
	$enctypes{$keytmp} = $valtmp;
  }
}

sub do_get_actual_domains{
   my ($response) = $_[0];
   my @domains = ();

   my $ref = ref ($response->{attributes}->{domain_list});    
   if ($ref eq "ARRAY") {
       if (defined $response->{attributes}->{domain_list}->[0]){
	  @domains = @{$response->{attributes}->{domain_list}};
      }else{
	  my $indicate = $response->{attributes}->{domain_list}->[0]->{'domain'};
	  if (!(defined $indicate) || ($indicate eq "")){
	      $domains[0] = undef;
	  } 
	  else{
	      my $max=@{$response->{attributes}->{domain_list}};
	      for(my $i=0; $i<$max; $i++){
		  $domains[$i] = $response->{attributes}->{domain_list}->[$i]->{'domain'};
	      } 
	  }
	  
      }
   }
   return @domains;
}

sub do_expiring_domains {
    my ($error);
    my $type = "list";
    my $xcp_request = undef;
    my ($page,$with_encoding_types)= @_;
    
    if ((!defined $page) or (!$page)) {
       $page = 0;
    }

    # Get server response:
    if (defined $cookie) {
        $xcp_request = {
	    action => "get",
	    object => "domain",
	    cookie => $cookie,
	    attributes => {
		type => "$type",
		max_to_expiry => $notice_days,
		min_to_expiry => '0',
		page => "$page",
		order_by => 'expiredate',
		with_encoding_types => $with_encoding_types,
	    }
	};
    } elsif ( (defined $reg_username) && (defined $reg_password) && (defined $reg_domain)) {
	$xcp_request = {
	    action => "get",
	    object => "domain",
	    cookie => $cookie,
	    attributes => {
		type => "$type",
		max_to_expiry => $notice_days,
		min_to_expiry => '0',
		reg_username => $reg_username,
		reg_password => $reg_password,
		domain => $reg_domain,
		page => $page,
		sort_by	=> 'expiredate',
		with_encoding_types => $with_encoding_types,
	    }
	};
    } else {
	return undef;
    }
    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
        $error = "Failed attempt: $response->{response_text}<br>\n";
        error_out($error);
        return undef;
    }
    return $response;
}

sub do_expired_domains {   
    my ($page,$with_encoding_types)= @_;
    my ($error);
    my $type = "list";
    my $xcp_request = undef;

    if ((!defined $page) or (!$page)) {
       $page = 0;
    }
    # Get server response:
    if (defined $cookie) {
        $xcp_request = {
	    action => "get",
	    object => "domain",
	    cookie => $cookie,
	    attributes => {
		type => $type,
		max_to_expiry => '0',
		page => $page,
		order_by => 'expiredate',
		with_encoding_types => $with_encoding_types,
	    }
	} ;
    } elsif ( (defined $reg_username) && (defined $reg_password) && (defined $reg_domain)) {
	$xcp_request = {
	    action => "get",
	    object => "domain",
	    cookie => $cookie,
	    attributes => {
		type => $type,
		max_to_expiry => '0',
		reg_username => $reg_username,
		reg_password => $reg_password,
		domain => $reg_domain,
		order_by => 'expiredate',
		with_encoding_types => $with_encoding_types,
	    }
	} ;
    } else {
	return undef;
    }

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
#        $error = "Failed attempt: $response->{response_text}<br>\n";
#        error_out($error);
        return undef;
    }
    return $response;
}

sub do_all_domains {
    my ($error);
    my $type = "list";
    my $xcp_request = undef;
    my ($page,$with_encoding_types, $sort, $domain, $domain_search, $auto_renew, $limit, $order_by, $expiry_date)= @_;
    
    if ((!defined $page) or (!$page)) {
       $page = 0;
    }
    # Get server response:
    if (defined $cookie) {
        $xcp_request = {
	    action => "get",
	    object => "domain",
	    cookie => $cookie,
	    attributes => {
		type => $type,
		page => $page,
		with_encoding_types => $with_encoding_types,
		domain => $domain,
		domain_search => $domain_search,
                auto_renew => $auto_renew,
                expiry_date => $expiry_date,
                limit => $limit,
                order_by => $order_by,
                sort_by => $sort,
	    }
	} ;
    } elsif ( (defined $reg_username) && (defined $reg_password) && (defined $reg_domain)) {
	$xcp_request = {
	    action => "get",
	    object => "domain",
	    cookie => $cookie,
	    attributes => {
		type => $type,
		reg_username => $reg_username,
		reg_password => $reg_password,
		domain => $reg_domain,
		domain_search => $domain_search,
                expiry_date => $expiry_date,
                auto_renew => $auto_renew,
                order_by => $order_by,
                sort_by => $sort,
		with_encoding_types => $with_encoding_types,
	    }
	} ;
    } else {
	return undef;
    }

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
        return undef;
    }
    return $response;
}


sub change_profile {
    my $domain = shift;
    my $local_domain_race_obj = RACE::DoRACE( Domain => $domain,
                                              EncodingType => $UNIVERSAL_ENCODING_TYPE ); 

    if ( not $local_domain_race_obj || $local_domain_race_obj->{Error} ){
        error_out("Failed to race encode the domain: [".$local_domain_race_obj->{Error}."]");
        exit;
    }
    my ($tld) = $local_domain_race_obj->{ConvertedDomain} =~ /$OPENSRS{OPENSRS_TLDS_REGEX}$/;

    if ( exists $CANT_SUPPORT{$tld} ) {
        my $message = <<EOF;
You cannot currently make changes to $tld domains through this<BR>
interface. We will have a $tld enabled Manage Domain interface in place as<BR>
soon as possible.</P>
If need to make emergency nameserver changes to your domain, please contact
<a href="mailto:support\@opensrs.org">support\@opensrs.org</a>.
EOF
        error_out($message);
        exit;
    }
    my $xcp_request = {
	action => "update",
	object => "cookie",
	cookie => $cookie,
	attributes => {
	    reg_username => $reg_username,
	    reg_password => $reg_password,
	    domain => $reg_domain,
	    domain_new => $local_domain_race_obj->{ConvertedDomain},
	}
    };
    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
        error_out("Failed attempt to change profile: $response->{response_text}\n");
        exit;
    }
    $reg_domain = $domain;
    $reg_domain_race_obj = RACE::UndoRACE( Domain => pack('A*',$reg_domain),
                                           EncodingType => $UNIVERSAL_ENCODING_TYPE );
    $reg_f_owner = $response->{attributes}->{f_owner};
    $reg_permission = $response->{attributes}->{permission};
    $domain_count = $response->{attributes}->{domain_count};
    $expiredate = $response->{attributes}->{expiredate};
    $waiting_request = $response->{attributes}->{waiting_request}; 
}

sub renewals_autorenew{
    my $auto_flag = $_[0]; 
    my $xcp_request = {
	action => "modify",
	object => "domain",
	cookie => $cookie,
	attributes => {
	    data => "expire_action",
	    let_expire => 0,
	    auto_renew => $auto_flag,
	    affect_domains => 0,
	}
    };
    my $response = $XML_Client->send_cmd( $xcp_request );
    if ($response->{is_success}) {
	return "Set Auto-Renew Request: <B>Success</B>";
    }
    else {
	return "Set Auto-Renew Request <b>Failure!</B> Reason:" . $response->{response_text};
    }

}

sub renewals_renew{
    my ($domain, $exp_year, $period) = @_;
    my $xcp_request = {
	action => 'renew',
	object => 'domain',
	attributes => {
# Uncomment one of the string or pass a specific value of parameter
# If not passed or value not save|process then settings from RSP 
# profile will be used
#	    handle => 'save',  #save order only regardless RSP settings
#	    handle => 'process', #process order always regardless RSP settings 
	    'domain' => $domain,
	    'currentexpirationyear' => $exp_year,
	    'period' => $period,
	},
    };
    my $response = $XML_Client->send_cmd( $xcp_request );

    if ($response->{is_success}) {
	return "Renewal Request: <B>Success</B>";
    } elsif (  $F_QUEUE_SUPPLIER_UNAVAILABLE and 
	       not $response->{is_success} and 
	       $response->{attributes}->{queue_request_id} ){
	
	return "Renewal Request has been placed in a registrar's queue";
	
    } else {
	return "Renewal Request <B>Failure!</B> Reason:" . $response->{response_text};
    }
} 

sub get_whois_rsp_info {

    my $xcp_request = {
	    action	=> "get",
	    object	=> "domain",
	    cookie	=> $cookie,
	    attributes	=> {
		    type => 'rsp_whois_info',
		    domain  => $reg_domain,
	    }
    };

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Failed attempt: $response->{response_text}\n");
	exit;
    }

    %whois_rsp_info = %{$response->{attributes}};
}

sub whois_rsp_info {

    my (%HTML, $template);

    get_whois_rsp_info();

    if ( $whois_rsp_info{rsp_enabled} eq 'Y' ) {

	$template = "whois_rsp_info_on.html";

	if ( $reg_f_owner ) {
	    $HTML{alldom} = '(Apply to all domains in profile <input type=checkbox name="all" value="1">)';
	}

	if ( $whois_rsp_info{domain_enabled} eq 'Y' ) {
	    $HTML{domain_enabled_yes} = "checked";
        } else {
	    $HTML{domain_enabled_no} = "checked";
	}

    } else {

	$template = "whois_rsp_info_off.html";

    }


    $HTML{CGI} = $cgi;

    my $rsp_info = '';
    my ( $bus, $phone, $fax, $email, $url, $opt_info ) = @whois_rsp_info{ qw(
	business
	phone
	fax
	email
	url
	opt_info
    )};

    if ( $bus ) {
	$rsp_info .= $email ? "    $bus, $email\n" : "    $bus\n";
    } elsif ( $email ) {
	$rsp_info .= "    $email\n";
    }

    $rsp_info .= "    $phone\n" if $phone;
    $rsp_info .= "    $fax (fax)\n" if $fax;
    $rsp_info .= "    $url\n" if $url;

    if ( $opt_info ) {
	$opt_info =~ s/(^|\n)/$1    /sg;
	$rsp_info .= "$opt_info\n";
    }

    $HTML{ rsp_info } = $rsp_info;

    print_form("$path_templates/$template",\%HTML);
}

sub set_whois_rsp_info {

    my $xcp_request = {
	    action	=> 'modify',
	    object	=> 'domain',
	    cookie	=> $cookie,
	    attributes	=> {
		    data => 'rsp_whois_info',
		    flag    => $in{domain_enabled},
		    all	    => $in{all} || 0,
		    domain  => $reg_domain,
	    }
    };

    my $response = $XML_Client->send_cmd( $xcp_request );
    if (not $response->{is_success}) {
	error_out("Failed attempt: $response->{response_text}\n");
	exit;
    }

    $whois_rsp_info{domain_enabled} = $in{domain_enabled};

    whois_rsp_info();
}

sub send_password {
    if ( not exists $MANAGE{allow_password_requests} or
	    not $MANAGE{allow_password_requests} ) {
	show_login("Invalid action: $action");
	exit;
    }

    my $domain = $in{domain};
    $domain =~ s/^\s+|\s+$//g;
    if ( not $domain ) {
	error_out( "No domain specified");
	exit;
    }

    my $xcp_request = {
	action	    => 'send_password',
	object	    => 'domain',
	attributes  => {
	    domain_name	=> $domain,
	    send_to	=> $in{user},
	    sub_user	=> $MANAGE{ password_send_subuser } ? 1 : 0,
	}
    };
    my $response = $XML_Client->send_cmd( $xcp_request );

    if ( $response->{is_success} ) {
	show_login( "Password has been sent." );
    } else {
	error_out("Couldn't send password: $response->{response_text}\n");
	exit;
    }
}

sub time_to_wait {
    my $msg;
    if ($reg_domain =~ /\.name$/i ) {
	$msg = "21 days";
    } else {
	$msg = "48 hours";
    }

    return $msg;
}

sub domain_locking {
    my %HTML;

    if ( not $reg_domain =~ /$OPENSRS{ TLDS_SUPPORTING_LOCKING }$/i ) {
	main_menu( "Domain does not support locking" );
	exit;
    } elsif ( not $reg_f_owner ) {
	main_menu( "Locking is not accessible to domain sub-user" );
    }

    if ( exists $in{ new_state } ) {
	if ( not exists $MANAGE{ allow_domain_locking } or
		not $MANAGE{ allow_domain_locking } ) {
	    main_menu( "Can't modify lock state" );
	    exit;
	}

	my $resp = $XML_Client->send_cmd(
	    {
		action	    => 'modify',
		object	    => 'domain',
		cookie	    => $cookie,
		attributes  => {
		    data	=> 'status',
		    lock_state	=> $in{ new_state },
		}
	    }
	);

	main_menu( $resp->{ response_text } );
	exit;
    } else {
	my $form = '';

	# find the current state.
	my $resp = $XML_Client->send_cmd(
	    {
		action	    => 'get',
		object	    => 'domain',
		cookie	    => $cookie,
		attributes  => {
		    type    => 'status',
		}
	    }
	);

	if ( not $resp->{ is_success } ) {
	    main_menu( $resp->{ response_text } );
	    exit;
	}

	if ( not $resp->{ attributes }{ domain_supports } ) {
	    main_menu( "Domain does not support locking" );
	    exit;
	}

	my ( $state, $enabled, $disabled );
	if ( $resp->{ attributes }{ lock_state } ) {
	    $state = 'Enabled';
	    $enabled = 'CHECKED';
	    $disabled = '';
	} else {
	    $state = 'Disabled';
	    $enabled = '';
	    $disabled = 'CHECKED';
	}

	$form .= "<FONT COLOR=RED>Locking currently <B>$state</B></FONT><BR><BR>";

	if ( $resp->{ attributes }{ can_modify } and
		$MANAGE{ allow_domain_locking } ) {
	    $form .= <<EOFORM;
<TABLE WIDTH="550">
    <TR>
	<TD ALIGN="RIGHT" WIDTH="50%">Enable: </TD>
	<TD><INPUT TYPE="RADIO" NAME="new_state" VALUE="1" $enabled>
    </TR>
    <TR>
	<TD ALIGN="RIGHT">Disable: </TD>
	<TD><INPUT TYPE="RADIO" NAME="new_state" VALUE="0" $disabled>
    </TR>
    <TR>
	<TD COLSPAN="2" ALIGN="CENTER">
	    <INPUT TYPE="SUBMIT" VALUE="Submit">
	</TD>
    </TR>
</TABLE>
EOFORM
	} else {
	    $form .= <<EOFORM;
<B>NOTE:</B> Locking cannot be enabled/disabled from this interface.  Please
contact your domain supplier for assistance.
EOFORM
	}

	$HTML{ FORM } = $form;
	$HTML{ CGI } = $cgi;

	print_form("$path_templates/domain_locking.html",\%HTML);
    }
}
