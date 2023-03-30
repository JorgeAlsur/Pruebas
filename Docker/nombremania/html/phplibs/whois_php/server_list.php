<?php 
// this ist list with servers which are used with this class
// info about the values for the key "free":
// this is the string (answer) what the server returns if there is no match.

// the value's for the key 'param' is mostly empty. I you have some suggestions for diff. 
// whois servers, then let me know...

$servers['nl']['address'] = "whois.domain-registry.nl";
$servers['nl']['free'] = "is free";
$servers['nl']['param'] = "";

$servers['be']['address'] = "whois.dns.be"; 
$servers['be']['free'] = "free";
$servers['be']['param'] = "";

$servers['de']['address'] = "whois.denic.de";
$servers['de']['free'] = "Can't get information on non-local domain";
$servers['de']['param'] = "-T dn ";

$servers['com']['address'] = "whois.opensrs.net";
$servers['com']['free'] = "Can't get information on non-local domain";
$servers['com']['param'] = "";

$servers['net']['address'] = "whois.opensrs.net";
$servers['net']['free'] = "Can't get information on non-local domain";
$servers['net']['param'] = "";

$servers['org']['address'] = "whois.pir.org";
$servers['org']['free'] = "NOT FOUND";
$servers['org']['param'] = "";

$servers['name']['address'] = "whois.nic.name";
$servers['name']['free'] = "No match";
$servers['name']['param'] = "";

$servers['biz']['address'] = "whois.nic.biz";
$servers['biz']['free'] = "Not found";
$servers['biz']['param'] = "";

$servers['info']['address'] = "whois.afilias.net";
$servers['info']['free'] = "NOT FOUND";
$servers['info']['param'] = "";

$servers['cc']['address'] = "whois.nic.cc";
$servers['cc']['free'] = "Free";
$servers['cc']['param'] = "";

// add here more servers if you like
?>
