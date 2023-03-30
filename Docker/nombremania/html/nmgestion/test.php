<?
$text=file("form/form_clientes.html");
$text=join("\n",$text);
$text2=ereg_replace("{(.*)}","variable \\1 ",$text);
print $text2;
?>
