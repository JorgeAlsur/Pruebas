<?
//genera un backup en el directorio raiz de nombremania/_backupbasededatos
$__host="db12.pair.com";
$__usuario="existe_2";
$__clave="qvLbfGYZ";
$__bddatos="existe_nombremania";
$mdump="/usr/local/bin/mysqldump";
$ruta="/usr/www/users/existe/_backupbasededatos";
$date=date("d-m-Y");
$llamada=$mdump." -h$__host -u$__usuario -p$__clave -c $__bddatos >$ruta/$date.sql";
system($llamada,$que);
print "Llamada : $llamada <br>Resultado: (si cero terminó con exito)".$que;
?>