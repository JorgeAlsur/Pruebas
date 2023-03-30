<?
//programa en php horacio degiorgi
function get_dns($domain) 
{ 
    $end = substr("$domain",-2); 
    if($end==uk) 
		    {$type=uk;} 
    else 
    		 {$type=com;}         
    if($type==uk) 
    {$lookup="whois.nic.uk";} 
    else{$lookup="rs.internic.net";} 

    $fp = fsockopen( "$lookup", 43, &$errno, &$errstr, 10);  
        fputs($fp, "$domain\r\n");  
             $adns=array();           
    while(!feof($fp))  
    {  
        $buf = fgets($fp,128);  
       if (ereg( "Name Server:", $buf)) 
        { 
            $dns = str_replace( "Name Server:",  "", $buf); 
            $dns = str_replace( "Server:",  "", $dns);   
            $adns[] = trim($dns);  
        }   

    } 

    Return $adns; 
}

?>
