<?

ob_start(); 
print ("esta es una pagina de espera mientras traigo la informacion \n\n\n
");
flush();

header("Location: http://www.nombremania.com/registro/index.php?domain=pop4344.com&action=lookup&affiliate_id=100&sugerencia=0"); 
ob_end_flush();

?> 


