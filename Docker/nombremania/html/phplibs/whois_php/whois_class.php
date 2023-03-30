<?php
/************************************************************************
Whois domain - v1.11
Check domain names for different TLD's

Copyright (C) 2004 - Olaf Lederer

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

_________________________________________________________________________
available at http://www.finalwebsites.com 
Comments & suggestions: http://www.finalwebsites.com/contact.php

Updates:

version 1.10 - In this version is it possible to check domain names for 
all tld's which are configured inside the server list. 
New Files: 
"multi_whois_list.php" this file is an example for using the new functions
and is also an extended version of the main class.
"multi_whois_detail.php" you need this file to get detail information on
domain names checked with the list file.
version 1.11 - I added the var $whois_param, use this var via the serverlist
to use extra (required) parameters for some whois servers.
*************************************************************************/

class Whois_domain {
	
	var $possible_tlds;
	var $whois_server;
	var $free_string;
	var $whois_param;
	var $domain;
	var $tld;
	var $compl_domain;
	var $full_info = "no";
	var $msg;
	var $info;
	 
	function Whois_domain() {
		$this->info = "";
		$this->msg = "";
	}
	function process() {
		if ($this->full_info == "yes") {
			$this->get_domain_info();
		} else {
			$this->check_only();
		}
	}
	function check_entry() {
		if (preg_match("/^[A-Za-z0-9]+(\-?[A-za-z0-9]*){1,63}$/", $this->domain)) {
			return true;
		} else {
			return false;
		}
	}
	function create_tld_select() {
		$menu = "<select name=\"tld\" style=\"margin-left:0;\">\n";
		foreach ($this->possible_tlds as $val) {
			$menu .= "  <option value=\"".$val."\"";
			$menu .= (isset($_POST['tld']) && $_POST['tld'] == $val) ? " selected>" : ">";
			$menu .= $val."\n";
		}
		$menu .= "</select>\n";
		return $menu;
	}
	function create_domain() {
		if ($this->check_entry()) {
			$this->domain = strtolower($this->domain);
			$this->compl_domain = $this->domain.".".$this->tld;
			return true;
		} else {
			return false;
		}
	}
	function check_only() {
		if ($this->create_domain()) {
			if ($this->get_whois_data()) {
				$this->msg = "The domain name: <b>".$this->compl_domain."</b> is registered";
			} else {
				$this->msg = "The domain name: <b>".$this->compl_domain."</b> is free.";
			}
		} else {
			$this->msg = "Only letters, numbers and hyphens (-) are valid!";
		}
	}
	function get_domain_info() {
		if ($this->create_domain()) {
			if ($this->get_whois_data()) {
				$info_array = split("\n", $this->get_whois_data()); 
				for ($i = 0; $i < count($info_array); $i++) {
					$this->info .= $info_array[$i]."\n";
				}	
			}
		} else {
			$this->msg = "Only letters, numbers and hyphens (-) are valid!";
		}
	}
	function get_whois_data() {
		$buffer = "";
		$connection = fsockopen($this->whois_server, 43, $errno, $errstr, 10);
		if (!$connection) {
			$this->msg = "Can't connect to the server!";
			break;
		} else {
			sleep(2);
			fputs($connection, $this->whois_param.$this->compl_domain."\r\n"); 
			while (!feof($connection)) {
				$buffer .= fgets($connection, 4096);
			}
			fclose($connection);
		}
		if ($this->full_info == "no") {
			if (eregi($this->free_string, $buffer)) {
				return false;
			} else {
				return true;
			}
		} else {
			if (eregi($this->free_string, $buffer)) {
				$this->msg = "The domain name: <b>".$this->compl_domain."</b> is free.";
				return false;
			} else {
				return $buffer;
			}
		}
	}
}
?>