<?

// Easily Simple Calendar
// Version 1.1 (July 27, 2001)
// (c) 2001 Brian E. Nash -- the Panhandle Paradise Network
// http://www.gopapa.com -- calendar@gopapa.com

// If you use this script, please consider emailing me a link to your website.
// Also, feel free to link to my website from yours.

// Use this script at your own risk (cma).

// VERSION HISTORY:

// Version 1.1:
//     Added the md variable
//     Added the ability to change day names
//     Added the ability to change month names

////////////////////////////
// COMMAND LINE VARIABLES //
////////////////////////////

// mo=mm                     MONTH OF THE CALENDAR
// yr=yyyy                   YEAR OF THE CALENDAR
// mrks=yyyy-mm-dd           START DATE OF MARKING (mySQL date format)
// mrke=yyyy-mm-dd           END DATE OF MARKING   (mySQL date format)
// nt=1                      DO NOT MARK TODAYS DATE
// ny=1                      DO NOT DISPLAY THE YEAR
// md=dddddd...              MARK THESE SPECIFIC DAY NUMBERS (in two digit # format)
//                           Example (md=01091329 will mark the 1st, 9th, 13th and 29th)
//                           NOTE: The date numbers can be in any order (i.e.: 0931240128)

// INSTRUCTIONS:
// Simply create a link to this script and pass any of the above variables
// along after the address. Put a ? (question mark) before the first
// variable. Put an & (ampersand) between each of the other variables.

// Example call to this script:
// http://www.yourdomain.com/calendardisplay.php?mo=12&yr=2001&mrks=2001-12-18&mrke=2001-12-20
// This will result in a calendar for December of 2001 with the dates 12-18 through 12-20 marked.

// TO SEE THIS SCRIPT IN ACTION:
// Go to http://www.mybikerclub.com/index.php and view the Events Calendar page.

// To change the look of the calendar, edit the Cascading Style-Sheet.

////////////////
// BEGIN CODE //
////////////////

// EDIT THESE COLORS TO SUIT YOUR NEEDS

// NORMAL BACKGROUND COLOR
$nbc="ccccEE";

// MARKED BACKGROUND COLOR
$abc="E9B4A1";

// TODAY BACKGROUND COLOR
$tbc="CCFFCC";

// DAY NAMES
$day[1]="Dom";
$day[2]="Lun";
$day[3]="Mar";
$day[4]="Mie";
$day[5]="Jue";
$day[6]="Vie";
$day[7]="Sab";

// MONTH NAMES
$mth[1]="Enero";
$mth[2]="Febrero";
$mth[3]="Marzo";
$mth[4]="Abril";
$mth[5]="Mayo";
$mth[6]="Junio";
$mth[7]="Julop";
$mth[8]="Agosto";
$mth[9]="Setiembre";
$mth[10]="Octubre";
$mth[11]="Noviembre";
$mth[12]="Diciembre";

//////////////////////////////////
// DO NOT EDIT BELOW THIS POINT //
//////////////////////////////////

// DETERMINE TODAYS DATE

$tmo=date("m");
$tda=date("j");
$tyr=date("Y");

// CHECK FOR COMMAND LINE DATE VARIABLES

if (!$mo) {$mo=$tmo;$yr=$tyr;}

// MARK START DATE AND END DATE BREAKDOWN
if(isset($mrks) and !isset($mrke)) $mrke=$mrks;
if ($mrks && $mrke) {

list ($ys,$ms,$ds)=explode("-",$mrks);
list ($ye,$me,$de)=explode("-",$mrke);

// MARK DATE BEGINNING & END OF MONTH CHECKS

if ($mo==$ms && $mo!=$me) {$de="32";}
if ($mo==$me && $ms!=$me) {$ds="1";$ms=$mo;}

}

// ON WHAT DAY DOES THE FIRST FALL
$sd = date ("w", mktime(0,0,0,$mo,1,$yr));

// NUMBER OF DAYS IN MONTH
$nd = mktime (0,0,0,$mo+1,0,$yr);
$nd = strftime ("%d",$nd);

// ADJUST VARIABLES FOR FORUMLA
$nd = $nd+1;
$cd = 1-$sd;

// DETERMINE MONTH NAME
$mn = date ("n", mktime(0,0,0,$mo,1,$yr));
$mn = $mth[$mn];

// CHECK FOR ADD YEAR

if (!$ny) {$mn=$mn." ".$yr;}

// CHECK FOR YEAR END SITUATION

if ($mo==$me && $ys!=$ye) {$ys=$ye;}

// CHECK FOR MD VARIABLE

if ($md) {

$lmd = strlen($md);

$chk="0";

DO {

$mdx=substr($md,$chk,2);

if ($mdx<10) $mdx=substr($md,$chk+1,1);

$mdxx[$mdx]=1;

$chk=$chk+2;

} WHILE ($chk<$lmd);

}

// ROUTINE TO SET BACKGROUND COLORS

$chk="1";

DO {

if ($ds<=$chk && $de>=$chk && $mo==$ms && $yr==$ys) {$bgc[$chk]=$abc;}

else {$bgc[$chk]=$nbc;}

if ($mdxx[$chk]) $bgc[$chk]=$abc;

if (!$nt && $mo==$tmo && $chk==$tda && $yr==$tyr && !$mdxx[$chk]) {$bgc[$chk]=$tbc;}

$chk++;

} WHILE ($chk<$nd);

// DISPLAY CALENDAR

?>
<style type="text/css">
<!--
.monthyear {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 18px; font-style: normal; line-height: normal; font-weight: bold; color: #000000; text-decoration: none}
.daynames {  font-family: Arial, Helvetica, sans-serif; font-size: 9px; font-style: normal; font-weight: normal; color: #000000; text-decoration: none}
.dates {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-style: normal; font-weight: normal; color: #000000; text-decoration: none}
-->
</style>

<table width="175" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td class="monthyear">
      <div align="center">
        <?echo "$mn";?>
      </div>
    </td>
  </tr>
</table>

<table width="175" border="0" cellspacing="0" cellpadding="2" class="daynames">
  <tr align="left">
    <td width="25"><?echo$day[1]?></td>
    <td width="25"><?echo$day[2]?></td>
    <td width="25"><?echo$day[3]?></td>
    <td width="25"><?echo$day[4]?></td>
    <td width="25"><?echo$day[5]?></td>
    <td width="25"><?echo$day[6]?></td>
    <td width="25"><?echo$day[7]?></td>
  </tr>
</table>

<table border="0" cellspacing="2" cellpadding="1" width="175" class="dates">
  <tr align="center">
    <td <?if ($cd>0) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd>0) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd>0) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd>0) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd>0) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd>0) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd>0) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd>0) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd>0) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd>0) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd>0) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd>0) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd>0) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd>0) {echo $cd;};$cd++;?>
    </td>
        </tr>

  <tr align="center">
    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>
        </tr>

  <tr align="center">
    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>
        </tr>

  <tr align="center">
    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>

    <td <?echo "bgcolor='#$bgc[$cd]'"?>>
      <?echo $cd;$cd++;?>
    </td>
        </tr>

  <tr align="center">
    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>
        </tr>
        <?if ($cd<$nd) {?>

  <tr align="center">
    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>
      <?if ($cd<$nd) {echo $cd;};$cd++;?>
    </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>&nbsp; </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>&nbsp; </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>&nbsp; </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>&nbsp; </td>

    <td <?if ($cd<$nd) {echo "bgcolor='#$bgc[$cd]'";}?>>&nbsp; </td>
        </tr>
        <?}?>
      </table>


