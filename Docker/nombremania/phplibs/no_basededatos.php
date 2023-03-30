<?
//base de datos
function mostrar_seccion($ruta){
Global $conn,$CONF;
$sql="select * from secciones where ruta='$ruta'";
$conn->debug=0;
$rs=$conn->execute($sql);
if ($rs===false or $conn->affected_rows()==0){
echo "Error pagina no encontrada ";
if ($CONF["admin"]==1)
ECHO "<br> <hr color=red><a href=admin.php?op=agregar&ruta=$ruta target=edicion>Agregar nueva</>";
}
else {
echo "<h2 class=titulo_seccion>".$rs->fields["titulo"]."</h2>";

echo "<p class=contenido>".$rs->fields["contenido"]."</p>";
if ($CONF["admin"]==1)
print "<br><hr><a href=\"admin.php?op=editar&ruta=$ruta\" target=edicion>administrar esta noticia</a> |

 | <a href=\"admin.php?op=borrar&ruta=$ruta\" target=edicion>Borrar</a>";

}

return ;
}
?>