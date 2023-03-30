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

if(isset($modeofform) || isset($preciototal)){
        include("mysql.inc");
        connect();
}
?>
<HTML><HEAD>
<SCRIPT>
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
     if(CheckRequiredFields(theForm,  0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54))
          return true;
  return false;
}
</SCRIPT>
</HEAD>
<?
    if(($modeofform=="u") || ($modeofform=="i")) {
        $notes=addslashes($notes);
        $reg_domain=addslashes($reg_domain);
        $domain=addslashes($domain);
        $owner_first_name=addslashes($owner_first_name);
        $owner_last_name=addslashes($owner_last_name);
        $owner_org_name=addslashes($owner_org_name);
        $owner_address1=addslashes($owner_address1);
        $owner_address2=addslashes($owner_address2);
        $owner_address3=addslashes($owner_address3);
        $owner_city=addslashes($owner_city);
        $owner_state=addslashes($owner_state);
        $owner_country=addslashes($owner_country);
        $owner_postal_code=addslashes($owner_postal_code);
        $owner_email=addslashes($owner_email);
        $owner_phone=addslashes($owner_phone);
        $owner_fax=addslashes($owner_fax);
        $billing_first_name=addslashes($billing_first_name);
        $billing_last_name=addslashes($billing_last_name);
        $billing_org_name=addslashes($billing_org_name);
        $billing_address1=addslashes($billing_address1);
        $billing_address2=addslashes($billing_address2);
        $billing_address3=addslashes($billing_address3);
        $billing_city=addslashes($billing_city);
        $billing_state=addslashes($billing_state);
        $billing_country=addslashes($billing_country);
        $billing_postal_code=addslashes($billing_postal_code);
        $billing_email=addslashes($billing_email);
        $billing_phone=addslashes($billing_phone);
        $billing_fax=addslashes($billing_fax);
        $tech_first_name=addslashes($tech_first_name);
        $tech_last_name=addslashes($tech_last_name);
        $tech_org_name=addslashes($tech_org_name);
        $tech_address1=addslashes($tech_address1);
        $tech_address2=addslashes($tech_address2);
        $tech_address3=addslashes($tech_address3);
        $tech_city=addslashes($tech_city);
        $tech_state=addslashes($tech_state);
        $tech_country=addslashes($tech_country);
        $tech_postal_code=addslashes($tech_postal_code);
        $tech_email=addslashes($tech_email);
        $tech_phone=addslashes($tech_phone);
        $tech_fax=addslashes($tech_fax);
        $fqdn1=addslashes($fqdn1);
        $fqdn2=addslashes($fqdn2);
        $fqdn3=addslashes($fqdn3);
        $fqdn4=addslashes($fqdn4);
        $fqdn5=addslashes($fqdn5);
        $fqdn6=addslashes($fqdn6);
        $tipo=addslashes($tipo);
        $cod_aprob=addslashes($cod_aprob);
        $fechatrans=addslashes($fechatrans);
        $idtrans=addslashes($idtrans);

        if($modeofform=="u"){
            $sql="update dominios
                    set
                       preciototal=$preciototal,
                       notes='$notes',
                       id=$id,
                       date=$date,
                       reg_domain='$reg_domain',
                       domain='$domain',
                       owner_first_name='$owner_first_name',
                       owner_last_name='$owner_last_name',
                       owner_org_name='$owner_org_name',
                       owner_address1='$owner_address1',
                       owner_address2='$owner_address2',
                       owner_address3='$owner_address3',
                       owner_city='$owner_city',
                       owner_state='$owner_state',
                       owner_country='$owner_country',
                       owner_postal_code='$owner_postal_code',
                       owner_email='$owner_email',
                       owner_phone='$owner_phone',
                       owner_fax='$owner_fax',
                       billing_first_name='$billing_first_name',
                       billing_last_name='$billing_last_name',
                       billing_org_name='$billing_org_name',
                       billing_address1='$billing_address1',
                       billing_address2='$billing_address2',
                       billing_address3='$billing_address3',
                       billing_city='$billing_city',
                       billing_state='$billing_state',
                       billing_country='$billing_country',
                       billing_postal_code='$billing_postal_code',
                       billing_email='$billing_email',
                       billing_phone='$billing_phone',
                       billing_fax='$billing_fax',
                       tech_first_name='$tech_first_name',
                       tech_last_name='$tech_last_name',
                       tech_org_name='$tech_org_name',
                       tech_address1='$tech_address1',
                       tech_address2='$tech_address2',
                       tech_address3='$tech_address3',
                       tech_city='$tech_city',
                       tech_state='$tech_state',
                       tech_country='$tech_country',
                       tech_postal_code='$tech_postal_code',
                       tech_email='$tech_email',
                       tech_phone='$tech_phone',
                       tech_fax='$tech_fax',
                       fqdn1='$fqdn1',
                       fqdn2='$fqdn2',
                       fqdn3='$fqdn3',
                       fqdn4='$fqdn4',
                       fqdn5='$fqdn5',
                       fqdn6='$fqdn6',
                       tipo='$tipo',
                       cod_aprob='$cod_aprob',
                       fechatrans='$fechatrans',
                       idtrans='$idtrans'
                    where preciototal=$preciototal";
             $db->query($sql);
             echo $updated_msg;
             exit;
        }
        else {
            $sql="insert into dominios
                 (preciototal,notes,id,date,reg_domain,domain,owner_first_name,owner_last_name,owner_org_name,owner_address1,owner_address2,owner_address3,owner_city,owner_state,owner_country,owner_postal_code,owner_email,owner_phone,owner_fax,billing_first_name,billing_last_name,billing_org_name,billing_address1,billing_address2,billing_address3,billing_city,billing_state,billing_country,billing_postal_code,billing_email,billing_phone,billing_fax,tech_first_name,tech_last_name,tech_org_name,tech_address1,tech_address2,tech_address3,tech_city,tech_state,tech_country,tech_postal_code,tech_email,tech_phone,tech_fax,fqdn1,fqdn2,fqdn3,fqdn4,fqdn5,fqdn6,tipo,cod_aprob,fechatrans,idtrans)
                 Values
                 ($preciototal,'$notes',$id,$date,'$reg_domain','$domain','$owner_first_name','$owner_last_name','$owner_org_name','$owner_address1','$owner_address2','$owner_address3','$owner_city','$owner_state','$owner_country','$owner_postal_code','$owner_email','$owner_phone','$owner_fax','$billing_first_name','$billing_last_name','$billing_org_name','$billing_address1','$billing_address2','$billing_address3','$billing_city','$billing_state','$billing_country','$billing_postal_code','$billing_email','$billing_phone','$billing_fax','$tech_first_name','$tech_last_name','$tech_org_name','$tech_address1','$tech_address2','$tech_address3','$tech_city','$tech_state','$tech_country','$tech_postal_code','$tech_email','$tech_phone','$tech_fax','$fqdn1','$fqdn2','$fqdn3','$fqdn4','$fqdn5','$fqdn6','$tipo','$cod_aprob','$fechatrans','$idtrans')";
             $db->query($sql);
             echo $inserted_msg;
             exit;
        }
    }
    elseif(isset($preciototal)){
            $modeofform="u";
            $sql="select preciototal,notes,id,date,reg_domain,domain,owner_first_name,owner_last_name,owner_org_name,owner_address1,owner_address2,owner_address3,owner_city,owner_state,owner_country,owner_postal_code,owner_email,owner_phone,owner_fax,billing_first_name,billing_last_name,billing_org_name,billing_address1,billing_address2,billing_address3,billing_city,billing_state,billing_country,billing_postal_code,billing_email,billing_phone,billing_fax,tech_first_name,tech_last_name,tech_org_name,tech_address1,tech_address2,tech_address3,tech_city,tech_state,tech_country,tech_postal_code,tech_email,tech_phone,tech_fax,fqdn1,fqdn2,fqdn3,fqdn4,fqdn5,fqdn6,tipo,cod_aprob,fechatrans,idtrans from dominios
                    where preciototal=$preciototal";
            $db->query($sql);
            $db->fetch();

         $preciototal=stripslashes($db->row["preciototal"]);
         $notes=stripslashes($db->row["notes"]);
         $id=stripslashes($db->row["id"]);
         $date=stripslashes($db->row["date"]);
         $reg_domain=stripslashes($db->row["reg_domain"]);
         $domain=stripslashes($db->row["domain"]);
         $owner_first_name=stripslashes($db->row["owner_first_name"]);
         $owner_last_name=stripslashes($db->row["owner_last_name"]);
         $owner_org_name=stripslashes($db->row["owner_org_name"]);
         $owner_address1=stripslashes($db->row["owner_address1"]);
         $owner_address2=stripslashes($db->row["owner_address2"]);
         $owner_address3=stripslashes($db->row["owner_address3"]);
         $owner_city=stripslashes($db->row["owner_city"]);
         $owner_state=stripslashes($db->row["owner_state"]);
         $owner_country=stripslashes($db->row["owner_country"]);
         $owner_postal_code=stripslashes($db->row["owner_postal_code"]);
         $owner_email=stripslashes($db->row["owner_email"]);
         $owner_phone=stripslashes($db->row["owner_phone"]);
         $owner_fax=stripslashes($db->row["owner_fax"]);
         $billing_first_name=stripslashes($db->row["billing_first_name"]);
         $billing_last_name=stripslashes($db->row["billing_last_name"]);
         $billing_org_name=stripslashes($db->row["billing_org_name"]);
         $billing_address1=stripslashes($db->row["billing_address1"]);
         $billing_address2=stripslashes($db->row["billing_address2"]);
         $billing_address3=stripslashes($db->row["billing_address3"]);
         $billing_city=stripslashes($db->row["billing_city"]);
         $billing_state=stripslashes($db->row["billing_state"]);
         $billing_country=stripslashes($db->row["billing_country"]);
         $billing_postal_code=stripslashes($db->row["billing_postal_code"]);
         $billing_email=stripslashes($db->row["billing_email"]);
         $billing_phone=stripslashes($db->row["billing_phone"]);
         $billing_fax=stripslashes($db->row["billing_fax"]);
         $tech_first_name=stripslashes($db->row["tech_first_name"]);
         $tech_last_name=stripslashes($db->row["tech_last_name"]);
         $tech_org_name=stripslashes($db->row["tech_org_name"]);
         $tech_address1=stripslashes($db->row["tech_address1"]);
         $tech_address2=stripslashes($db->row["tech_address2"]);
         $tech_address3=stripslashes($db->row["tech_address3"]);
         $tech_city=stripslashes($db->row["tech_city"]);
         $tech_state=stripslashes($db->row["tech_state"]);
         $tech_country=stripslashes($db->row["tech_country"]);
         $tech_postal_code=stripslashes($db->row["tech_postal_code"]);
         $tech_email=stripslashes($db->row["tech_email"]);
         $tech_phone=stripslashes($db->row["tech_phone"]);
         $tech_fax=stripslashes($db->row["tech_fax"]);
         $fqdn1=stripslashes($db->row["fqdn1"]);
         $fqdn2=stripslashes($db->row["fqdn2"]);
         $fqdn3=stripslashes($db->row["fqdn3"]);
         $fqdn4=stripslashes($db->row["fqdn4"]);
         $fqdn5=stripslashes($db->row["fqdn5"]);
         $fqdn6=stripslashes($db->row["fqdn6"]);
         $tipo=stripslashes($db->row["tipo"]);
         $cod_aprob=stripslashes($db->row["cod_aprob"]);
         $fechatrans=stripslashes($db->row["fechatrans"]);
         $idtrans=stripslashes($db->row["idtrans"]);
    }
    else {
            $modeofform="i";
    }
?>

<BODY>
<FORM name="dominiosForm" method="post" enctype="multipart-form-data"
      action="<? echo $PHP_SELF?>" onsubmit="return CheckForm(this)">
<TABLE border=0>
<TR>
   <TD>preciototal</TD>
   <TD><input name="preciototal" type="text" size=20 maxlen=4 value="<? echo $preciototal; ?>"></TD>
</TR>
<TR>
   <TD>notes</TD>
   <TD><input name="notes" type="file" value="<? echo $notes; ?>"></TD>
</TR>
<TR>
   <TD>id</TD>
   <TD><input name="id" type="text" size=20 maxlen=4 value="<? echo $id; ?>"></TD>
</TR>
<TR>
   <TD>date</TD>
   <TD><input name="date" type="text" size=20 maxlen=4 value="<? echo $date; ?>"></TD>
</TR>
<TR>
   <TD>reg_domain</TD>
   <TD><input name="reg_domain" type="text" size=20 maxlen=13 value="<? echo $reg_domain; ?>"></TD>
</TR>
<TR>
   <TD>domain</TD>
   <TD><input name="domain" type="text" size=20 maxlen=256 value="<? echo $domain; ?>"></TD>
</TR>
<TR>
   <TD>owner_first_name</TD>
   <TD><input name="owner_first_name" type="text" size=20 maxlen=16 value="<? echo $owner_first_name; ?>"></TD>
</TR>
<TR>
   <TD>owner_last_name</TD>
   <TD><input name="owner_last_name" type="text" size=20 maxlen=16 value="<? echo $owner_last_name; ?>"></TD>
</TR>
<TR>
   <TD>owner_org_name</TD>
   <TD><input name="owner_org_name" type="text" size=20 maxlen=40 value="<? echo $owner_org_name; ?>"></TD>
</TR>
<TR>
   <TD>owner_address1</TD>
   <TD><input name="owner_address1" type="text" size=20 maxlen=40 value="<? echo $owner_address1; ?>"></TD>
</TR>
<TR>
   <TD>owner_address2</TD>
   <TD><input name="owner_address2" type="text" size=20 maxlen=40 value="<? echo $owner_address2; ?>"></TD>
</TR>
<TR>
   <TD>owner_address3</TD>
   <TD><input name="owner_address3" type="text" size=20 maxlen=40 value="<? echo $owner_address3; ?>"></TD>
</TR>
<TR>
   <TD>owner_city</TD>
   <TD><input name="owner_city" type="text" size=20 maxlen=16 value="<? echo $owner_city; ?>"></TD>
</TR>
<TR>
   <TD>owner_state</TD>
   <TD><input name="owner_state" type="text" size=20 maxlen=16 value="<? echo $owner_state; ?>"></TD>
</TR>
<TR>
   <TD>owner_country</TD>
   <TD><input name="owner_country" type="text" size=20 maxlen=3 value="<? echo $owner_country; ?>"></TD>
</TR>
<TR>
   <TD>owner_postal_code</TD>
   <TD><input name="owner_postal_code" type="text" size=20 maxlen=16 value="<? echo $owner_postal_code; ?>"></TD>
</TR>
<TR>
   <TD>owner_email</TD>
   <TD><input name="owner_email" type="text" size=20 maxlen=40 value="<? echo $owner_email; ?>"></TD>
</TR>
<TR>
   <TD>owner_phone</TD>
   <TD><input name="owner_phone" type="text" size=20 maxlen=21 value="<? echo $owner_phone; ?>"></TD>
</TR>
<TR>
   <TD>owner_fax</TD>
   <TD><input name="owner_fax" type="text" size=20 maxlen=21 value="<? echo $owner_fax; ?>"></TD>
</TR>
<TR>
   <TD>billing_first_name</TD>
   <TD><input name="billing_first_name" type="text" size=20 maxlen=16 value="<? echo $billing_first_name; ?>"></TD>
</TR>
<TR>
   <TD>billing_last_name</TD>
   <TD><input name="billing_last_name" type="text" size=20 maxlen=16 value="<? echo $billing_last_name; ?>"></TD>
</TR>
<TR>
   <TD>billing_org_name</TD>
   <TD><input name="billing_org_name" type="text" size=20 maxlen=40 value="<? echo $billing_org_name; ?>"></TD>
</TR>
<TR>
   <TD>billing_address1</TD>
   <TD><input name="billing_address1" type="text" size=20 maxlen=40 value="<? echo $billing_address1; ?>"></TD>
</TR>
<TR>
   <TD>billing_address2</TD>
   <TD><input name="billing_address2" type="text" size=20 maxlen=40 value="<? echo $billing_address2; ?>"></TD>
</TR>
<TR>
   <TD>billing_address3</TD>
   <TD><input name="billing_address3" type="text" size=20 maxlen=40 value="<? echo $billing_address3; ?>"></TD>
</TR>
<TR>
   <TD>billing_city</TD>
   <TD><input name="billing_city" type="text" size=20 maxlen=16 value="<? echo $billing_city; ?>"></TD>
</TR>
<TR>
   <TD>billing_state</TD>
   <TD><input name="billing_state" type="text" size=20 maxlen=16 value="<? echo $billing_state; ?>"></TD>
</TR>
<TR>
   <TD>billing_country</TD>
   <TD><input name="billing_country" type="text" size=20 maxlen=3 value="<? echo $billing_country; ?>"></TD>
</TR>
<TR>
   <TD>billing_postal_code</TD>
   <TD><input name="billing_postal_code" type="text" size=20 maxlen=16 value="<? echo $billing_postal_code; ?>"></TD>
</TR>
<TR>
   <TD>billing_email</TD>
   <TD><input name="billing_email" type="text" size=20 maxlen=40 value="<? echo $billing_email; ?>"></TD>
</TR>
<TR>
   <TD>billing_phone</TD>
   <TD><input name="billing_phone" type="text" size=20 maxlen=21 value="<? echo $billing_phone; ?>"></TD>
</TR>
<TR>
   <TD>billing_fax</TD>
   <TD><input name="billing_fax" type="text" size=20 maxlen=21 value="<? echo $billing_fax; ?>"></TD>
</TR>
<TR>
   <TD>tech_first_name</TD>
   <TD><input name="tech_first_name" type="text" size=20 maxlen=16 value="<? echo $tech_first_name; ?>"></TD>
</TR>
<TR>
   <TD>tech_last_name</TD>
   <TD><input name="tech_last_name" type="text" size=20 maxlen=16 value="<? echo $tech_last_name; ?>"></TD>
</TR>
<TR>
   <TD>tech_org_name</TD>
   <TD><input name="tech_org_name" type="text" size=20 maxlen=40 value="<? echo $tech_org_name; ?>"></TD>
</TR>
<TR>
   <TD>tech_address1</TD>
   <TD><input name="tech_address1" type="text" size=20 maxlen=40 value="<? echo $tech_address1; ?>"></TD>
</TR>
<TR>
   <TD>tech_address2</TD>
   <TD><input name="tech_address2" type="text" size=20 maxlen=40 value="<? echo $tech_address2; ?>"></TD>
</TR>
<TR>
   <TD>tech_address3</TD>
   <TD><input name="tech_address3" type="text" size=20 maxlen=40 value="<? echo $tech_address3; ?>"></TD>
</TR>
<TR>
   <TD>tech_city</TD>
   <TD><input name="tech_city" type="text" size=20 maxlen=16 value="<? echo $tech_city; ?>"></TD>
</TR>
<TR>
   <TD>tech_state</TD>
   <TD><input name="tech_state" type="text" size=20 maxlen=16 value="<? echo $tech_state; ?>"></TD>
</TR>
<TR>
   <TD>tech_country</TD>
   <TD><input name="tech_country" type="text" size=20 maxlen=3 value="<? echo $tech_country; ?>"></TD>
</TR>
<TR>
   <TD>tech_postal_code</TD>
   <TD><input name="tech_postal_code" type="text" size=20 maxlen=16 value="<? echo $tech_postal_code; ?>"></TD>
</TR>
<TR>
   <TD>tech_email</TD>
   <TD><input name="tech_email" type="text" size=20 maxlen=40 value="<? echo $tech_email; ?>"></TD>
</TR>
<TR>
   <TD>tech_phone</TD>
   <TD><input name="tech_phone" type="text" size=20 maxlen=21 value="<? echo $tech_phone; ?>"></TD>
</TR>
<TR>
   <TD>tech_fax</TD>
   <TD><input name="tech_fax" type="text" size=20 maxlen=21 value="<? echo $tech_fax; ?>"></TD>
</TR>
<TR>
   <TD>fqdn1</TD>
   <TD><input name="fqdn1" type="text" size=20 maxlen=40 value="<? echo $fqdn1; ?>"></TD>
</TR>
<TR>
   <TD>fqdn2</TD>
   <TD><input name="fqdn2" type="text" size=20 maxlen=40 value="<? echo $fqdn2; ?>"></TD>
</TR>
<TR>
   <TD>fqdn3</TD>
   <TD><input name="fqdn3" type="text" size=20 maxlen=40 value="<? echo $fqdn3; ?>"></TD>
</TR>
<TR>
   <TD>fqdn4</TD>
   <TD><input name="fqdn4" type="text" size=20 maxlen=40 value="<? echo $fqdn4; ?>"></TD>
</TR>
<TR>
   <TD>fqdn5</TD>
   <TD><input name="fqdn5" type="text" size=20 maxlen=40 value="<? echo $fqdn5; ?>"></TD>
</TR>
<TR>
   <TD>fqdn6</TD>
   <TD><input name="fqdn6" type="text" size=20 maxlen=40 value="<? echo $fqdn6; ?>"></TD>
</TR>
<TR>
   <TD>tipo</TD>
   <TD><input name="tipo" type="text" size=20 maxlen=8 value="<? echo $tipo; ?>"></TD>
</TR>
<TR>
   <TD>cod_aprob</TD>
   <TD><input name="cod_aprob" type="text" size=20 maxlen=26 value="<? echo $cod_aprob; ?>"></TD>
</TR>
<TR>
   <TD>fechatrans</TD>
   <TD><input name="fechatrans" type="text" size=20 maxlen=4 value="<? echo $fechatrans; ?>"></TD>
</TR>
<TR>
   <TD>idtrans</TD>
   <TD><input name="idtrans" type="text" size=20 maxlen=26 value="<? echo $idtrans; ?>"></TD>
</TR><TR>
   <TD>&nbsp;</TD>
   <TD><INPUT TYPE=Submit value="Save"></TD>
</TR>
<TABLE>
<input type=hidden name=modeofform value="<? echo $modeofform;?>">
</FORM>
</BODY>
</HTML>