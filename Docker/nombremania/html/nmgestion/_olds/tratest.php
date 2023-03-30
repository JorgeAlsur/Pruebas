<?
$db=null;

$updated_msg="Record has been updated successfully.
         Click <a href='/index.php'>here</A> to return homepage.";
$inserted_msg="Record has been added successfully.
         Click <a href='/index.php'>here</A> to return homepage.";

function connect() {
    global $db;

     $db = new db_class;
     $db->host="db12.pair.com";
     $db->login="existe_2";
     $db->password="qvLbfGYZ";
     $db->database="existe_nombremania";

     $db->connect();

}

if(isset($modeofform) || isset($id)){
        include("mysql.inc");
        connect();
}
?>
<HTML><HEAD>
<SCRIPT>
function eCheckDate(sn) {
      s= sn.value;
      var i=0;
      tm= new Array();
      tm[0]= '';tm[1]= '';tm[2]='';tm[3]='';
      sep='';
      md = 0 ;

      while (i< s.length) {
          ch= s.substring(i, i + 1);
          if( ch<='9' && ch>='0')
              tm[md] += ch;
          else if (sep=='') {
              if(ch=='.' || ch=='/' || ch=='-') {
                   md++;
                   sep=ch;
              }
          }
          else if (ch==sep) {
             md++;
             if(md>2) return false;
          }
          else return false;

          i++;
      }

      if(tm[3]!='') return false;
      if(tm[0].valueOf()<1 || tm[0].valueOf()>12) return false; //Month
      if(tm[1].valueOf()<1 || tm[1].valueOf()>31) return false;
      if(tm[2].valueOf()<100) return false;

      if ((tm[0]==4 || tm[0]==6 || tm[0]==9 || tm[0]==11) && tm[1]==31)
          return false;
      if (tm[0]==2) {
           isleap = (tm[2] % 4 == 0 && (tm[2] % 100 != 0 || tm[2] % 400 == 0));
           if (tm[1]>29 || (tm[1]==29 && !isleap))
                return false;
      }

      return (tm[3]=='');
}

function CheckDate(theForm){
   for(var i=1; i<CheckDate.arguments.length; i++)
         if (!eCheckDate(theForm.elements[CheckDate.arguments[i]])){
            alert("Invalid Date");
            theForm.elements[CheckDate.arguments[i]].focus();
            return false;
        }
   return true;
}

function eCheckAlpha(sn){
    s= sn.value;
    for (var i = 0; i < s.length; i++) {
        ch = s.substring(i, i + 1)
        if (!((ch >= "A" && ch <= "Z") || (ch >= "a" && ch <= "z")))
             return false;
        }
   return true;
}

function CheckAlpha(theForm){
   for(var i=1; i<CheckAlpha.arguments.length; i++)
         if (!eCheckAlpha(theForm.elements[CheckAlpha.arguments[i]])){
            alert("Field entry is not valid");
            theForm.elements[CheckAlpha.arguments[i]].focus();
            return false;
        }
   return true;
}

function eCheckNum(sn){
    s= sn.value;
    for (var i = 0; i < s.length; i++) {
        ch = s.substring(i, i + 1)
        if (!(ch >= "0" && ch <= "9"))
             return false;
        }
   return true;
}
function CheckNum(theForm){
   for(var i=1; i<CheckNum.arguments.length; i++)
         if (!eCheckNum(theForm.elements[CheckNum.arguments[i]])){
            alert("Invalid number");
            theForm.elements[CheckNum.arguments[i]].focus();
            return false;
        }
   return true;
}

function CheckRequiredFields(theForm)
{
   for(i=1; i<CheckRequiredFields.arguments.length; i++)
        if(theForm.elements[CheckRequiredFields.arguments[i]].value==""){
            alert("This field is required");
            theForm.elements[CheckRequiredFields.arguments[i]].focus();
            return false;
        }
   return true;
}

function CheckForm(theForm){
     if(CheckRequiredFields(theForm,  1, 2, 3, 4, 5))
     if(CheckNum(theForm,  1, 2))
     if(CheckAlpha(theForm,  3))
     if(CheckDate(theForm,  4))
          return true;
  return false;
}
</SCRIPT>
</HEAD>
<?
    if(($modeofform=="u") || ($modeofform=="i")) {
        $tipo=addslashes($tipo);
        $fecha=addslashes($fecha);
        $observacion=addslashes($observacion);

        if($modeofform=="u"){
            $sql="update transacciones
                    set
                       id=$id,
                       id_cliente=$id_cliente,
                       importe=$importe,
                       tipo='$tipo',
                       fecha='$fecha',
                       observacion='$observacion'
                    where id=$id";
             $db->query($sql);
             echo $updated_msg;
             exit;
        }
        else {
            $sql="insert into transacciones
                 (id,id_cliente,importe,tipo,fecha,observacion)
                 Values
                 ($id,$id_cliente,$importe,'$tipo','$fecha','$observacion')";
             $db->query($sql);
             echo $inserted_msg;
             exit;
        }
    }
    elseif(isset($id)){
            $modeofform="u";
            $sql="select id,id_cliente,importe,tipo,fecha,observacion from transacciones
                    where id=$id";
            $db->query($sql);
            $db->fetch();

         $id=stripslashes($db->row["id"]);
         $id_cliente=stripslashes($db->row["id_cliente"]);
         $importe=stripslashes($db->row["importe"]);
         $tipo=stripslashes($db->row["tipo"]);
         $fecha=stripslashes($db->row["fecha"]);
         $observacion=stripslashes($db->row["observacion"]);
    }
    else {
            $modeofform="i";
    }
?>

<BODY>
<FORM name="transaccionesForm" method="post" enctype="multipart-form-data"
      action="<? echo $PHP_SELF?>" onsubmit="return CheckForm(this)">
<TABLE border=0>
<TR>
   <TD>id</TD>
   <TD><input name="id" type="hidden" value="<? echo $id; ?>"></TD>
</TR>
<TR>
   <TD>id_cliente</TD>
   <TD><input name="id_cliente" type="text" size=20 maxlen=4 value="<? echo $id_cliente; ?>"></TD>
</TR>
<TR>
   <TD>importe</TD>
   <TD><input name="importe" type="text" size=20 maxlen=8 value="<? echo $importe; ?>"></TD>
</TR>
<TR>
   <TD>tipo</TD>
   <TD><input name="tipo" type="text" size=20 maxlen=2 value="<? echo $tipo; ?>"></TD>
</TR>
<TR>
   <TD>fecha</TD>
   <TD><input name="fecha" type="text" size=20 maxlen=4 value="<? echo $fecha; ?>"></TD>
</TR>
<TR>
   <TD>observacion</TD>
   <TD><textarea name="observacion"><? echo $observacion?></textarea></TD>
</TR><TR>
   <TD>&nbsp;</TD>
   <TD><INPUT TYPE=Submit value="Save"></TD>
</TR>
<TABLE>
<input type=hidden name=modeofform value="<? echo $modeofform;?>">
</FORM>
</BODY>
</HTML>