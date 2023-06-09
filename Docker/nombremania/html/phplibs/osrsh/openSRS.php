<?php
/* 
 **************************************************************************
 *
 * OpenSRS-PHP
 *
 * Copyright (C) 2000-2004 Colin Viebrock
 *
 **************************************************************************
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.   
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 **************************************************************************
 *
 * vim: set expandtab tabstop=4 shiftwidth=4:
 * $Id: openSRS.php.default,v 1.7.2.3 2004/12/07 20:27:22 cviebrock Exp $
 *
 **************************************************************************
 */


/*
 *
 * OpenSRS Protocol Sample Extended Client Class
 *
 */


/* We require the base class */

require_once('openSRS_base.php');

class openSRS extends openSRS_base {

	var $USERNAME				= 'luison';

	var $TEST_PRIVATE_KEY		= '840a9eafa15580ec4dc2c6bf2a8d3366a0d0ab73ec09cd48d528202a0685ac74bdef61e8af79ec7a7717216120562f3683fb02e54b1a6dd8';
	var $LIVE_PRIVATE_KEY		= '8d68cea7401a89f0bef5b66e7d436a4f6b623234c2e5a455b769b59d500110def7dcdbfe90790d35484c041a932f418fd2c4e1246e9a5d0d';

	var $HRS_host				= 'yourOpenHRSname.opensrs.net';
	var $HRS_port				= 54321;
	var $HRS_USERNAME			= 'yourOpenHRSname';
	var $HRS_PRIVATE_KEY		= 'abcdef1234567890';

	var $environment			= 'LIVE';	    # 'TEST' or 'LIVE' or 'HRS'
	var $crypt_type				= 'BLOWFISH';	# 'DES' or 'BLOWFISH' or 'SSL';
	var $protocol				= 'XCP';	    # 'XCP' for domains, 'TPP' for email and certs


	var $connect_timeout		= 20;		# seconds
	var $read_timeout			= 20;		# seconds

	var $RELATED_TLDS = array(
		array( '.ca' ),
		array( '.com', '.net', '.org' ),
		array( '.co.uk', '.org.uk' ),
		array( '.vc' ),
		array( '.cc' ),
		array( '.es' ),
		
	);


/*
 * You can put your own functions here to extend the class
 * (e.g. convert your data structures to OpenSRS's, read
 * your nameserver list out of a database, etc.)
 *
 */

	function myFunction() {
	}


}


