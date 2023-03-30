<?
function editor(){
?>
 <script language="JavaScript"> <!-- Begin hiding from older browsers
function isEditor(whoAmI)
{
var ztxt = new String(whoAmI)
ztxt = ztxt.substr(ztxt,8)
return ztxt == 'John Lim';
}

function editorLink(whoAmI,zurl,ztxt)
{
 if (isEditor(whoAmI))
   document.write(' <a href="http://'+zurl+'" target='+ztxt+'>'+ztxt+'</a> ');
}

function hitboxer(whoAmI,zpgname)
{
var bn=navigator.appName;
var dt=0;
var bv =Math.round(parseFloat(navigator.appVersion)*100);
if(bn!="Netscape"){dt=(new Date()).getHours();};if(bn.substring(0,9)=="Microsoft"){bn="MSIE";}
 var pgname = new String(zpgname);
 if (pgname == '') pgname = 'Home';
var rf=escape(document.referrer)+"";
 if (!isEditor(whoAmI)) {
document.write('<!-- BEGIN WEBSIDESTORY CODE V5. COPYRIGHT 1998-2000 WEBSIDESTORY, INC. ALL RIGHTS RESERVED. U.S. PATENT PENDING. -->');
document.write('<p align="center"><A HREF="http://rd1.hitbox.com/rd?acct=WQ500820LAEM61EN0&p=s">');
document.write('<img src="http://hg1.hitbox.com/HG?hc=w101&cd=1&hb=WQ500820LAEM61EN0&cd=1&dt='+dt+'&bv='+bv+'&bn='+bn+'&rf='+rf+'&n='+escape(pgname)+'" height=62 width=88 ALT="Click Here!" border=0></A></p><!-- webbot bot="HTMLMarkup" endspan --><!-- END WEBSIDESTORY CODE  -->');
}
}//End hiding-->
  </script>
<script language='JavaScript'>
<!--

var rightCaret = String.fromCharCode (62);

function isEditable (selectionRange) {

        //Return true if the selection is in an editable part of the page -- in the textarea.

        if (selectionRange.parentElement ().tagName == "TEXTAREA")
                return (true);

        return (false);
        } //isEditable


function getSelectionRange () {

        //Get a reference to the current selection range.
        //If there is no selection range, or it's not in a textarea, return null.

        var selectionRange;
        selectionRange = document.selection.createRange ();

        if (isEditable (selectionRange)) {

                var currentText = selectionRange.text;
                if (currentText != "")
                        return (selectionRange);
                }

        return (null);
        } //getSelectionRange


function highlightButton (buttonRef) {

        //Highlight the button only if there is currently editable text.

        var selectionRange;
        selectionRange = getSelectionRange ();

        if (selectionRange == null) {

                buttonRef.style.cursor = "default";
                return (false);
                }

        buttonRef.style.color = "maroon";
        //buttonRef.style.cursor = "hand";

        buttonRef.style.cursor = "default";

        return (true);
        } //highlightButton


function unHighlightButton (buttonRef) {

        //Unhighlight a button.

        buttonRef.style.color = "black";

        return (true);
        } //unHighlightButton


function urlPrompt () {

        //Prompt the user via dialog box for a URL.

        var url;
        url = prompt ("URL:", "http://");
        return (url);
        } //urlPrompt


function replaceText (selectionRange, preText, postText, currentText) {

        //Enclose the selected text with preText and postText.

        selectionRange.text = preText + currentText + postText;

        selectionRange.parentElement ().focus ();
        } //replaceText


function simpleEnclose (tagName) {

        //Enclose selected text with a tag and closing tag.

        if (tagName == "")
                return (false);

        var selectionRange;
        selectionRange = getSelectionRange ();

        if (selectionRange != null) {

                var currentText;
                currentText = selectionRange.text;

                var preText, postText;
                preText = "<" + tagName + rightCaret;
                postText = "</" + tagName + rightCaret;

                replaceText (selectionRange, preText, postText, currentText);
                }
        } //simpleEnclose


function addFormat (tagName) {

        //Handle tags in the Format menu.

        if (tagName == "")
                return (false);

        var selectionRange;
        selectionRange = getSelectionRange ();

        if (selectionRange != null) {

                var currentText;
                currentText = selectionRange.text;

                var preText, postText;
                preText = "<" + tagName + rightCaret;
                postText = "</" + tagName + rightCaret;

                if (tagName == "blockquote") {

                        preText = "\r\n\t" + preText;
                        postText = postText + "\r\n";
                        }

                if (tagName == "li") {

                        preText = "\r\n<li" + rightCaret;
                        postText = "";
                        }

                replaceText (selectionRange, preText, postText, currentText);
                }
        } //addFormat


function addFontColor (colorName) {

        //Enclose the selected text with a font tag that specifies the color

        if (colorName == "")
                return (false);

        var selectionRange;
        selectionRange = getSelectionRange ();

        if (selectionRange != null) {

                var currentText;
                currentText = selectionRange.text;

                var preText, postText;
                preText = "<font color=\"" + colorName + "\"" + rightCaret;
                postText = "</font" + rightCaret;

                replaceText (selectionRange, preText, postText, currentText);
                }
        } //addFontColor


function addFontFace (faceName) {

        //Enclose the selected text with a font tag that specifies the font face.

        if (faceName == "")
                return (false);

        var selectionRange;
        selectionRange = getSelectionRange ();

        if (selectionRange != null) {

                var currentText;
                currentText = selectionRange.text;

                var preText, postText;
                preText = "<font face=\"" + faceName + "\"" + rightCaret;
                postText = "</font" + rightCaret;

                replaceText (selectionRange, preText, postText, currentText);
                }
        } //addFontFace


function addAlignment (alignment) {

        //Enclose the selected text with a font tag that specifies the color

        if (alignment == "")
                return (false);

        var selectionRange;
        selectionRange = getSelectionRange ();

        if (selectionRange != null) {

                var currentText;
                currentText = selectionRange.text;

                var preText, postText;
                preText = "<p align=\"" + alignment + "\"" + rightCaret;
                postText = "</p" + rightCaret + "\r\n";

                replaceText (selectionRange, preText, postText, currentText);
                }
        } //addAlignment


function addLink () {

        //Prompt the user for a URL and make the selection a link.

        var selectionRange;
        selectionRange = getSelectionRange ();

        if (selectionRange != null) {

                var currentText;
                currentText = selectionRange.text;

                var url;
                url = urlPrompt ();

                if ((url == "") || (url == "http://") || (url == null))
                        return;

                var preText, postText;
                preText = "<a href=\"" + url + "\"" + rightCaret;
                postText = "</a" + rightCaret;

                replaceText (selectionRange, preText, postText, currentText);
                }
         } //addLink
//-->
</script>
<table border="1" cellpadding="3" bgcolor="gainsboro" width="580" style="border-color:black">
<tr>
<td><a href="javascript:simpleEnclose ('b')" style="background-color:gainsboro;color:black;text-decoration:none;font-size:9pt" onmouseover="highlightButton (this)" onmouseout="unHighlightButton (this)">&nbsp;&nbsp;<b>B</b>&nbsp;&nbsp;</a></td><p>

<td><a href="javascript:simpleEnclose ('i')" style="background-color:gainsboro;color:black;text-decoration:none;font-size:9pt" onmouseover="highlightButton (this)" onmouseout="unHighlightButton (this)"><i>&nbsp;&nbsp;<b>I</b>&nbsp;&nbsp;</i></a></td><p>

<td><a href="javascript:simpleEnclose ('u')" style="background-color:gainsboro;color:black;text-decoration:none;font-size:9pt" onmouseover="highlightButton (this)" onmouseout="unHighlightButton (this)">&nbsp;&nbsp;<u><b>U</b></u>&nbsp;&nbsp;</a></td><p>

<td><select name="Color" onchange="addFontColor (this.options [this.selectedIndex].value);this.options [0].selected = true" style="background-color:ivory;font-size:9pt">
        <option value="" style="color:blue;background-color:gainsboro">Color...</option>
        <option value="black" style="color:black">black</option>
        <option color="darkslategray" style="color:darkslategray">darkslategray</option>
        <option value="red" style="color:red">red</option>
        <option value="maroon" style="color:maroon">maroon</option>
        <option value="lightpink" style="color:lightpink">lightpink</option>
        <option value="purple" style="color:purple">purple</option>
        <option value="blue" style="color:blue">blue</option>
        <option value="darkblue" style="color:darkblue">darkblue</option>
        <option value="teal" style="color:teal">teal</option>
        <option value="skyblue" style="color:skyblue">skyblue</option>
        <option value="green" style="color:green">green</option>
        <option value="seagreen" style="color:seagreen">seagreen</option>
        <option value="olive" style="color:olive">olive</option>
        <option value="orange" style="color:orange">orange</option>
        <option value="darkgoldenrod" style="color:darkgoldenrod">darkgoldenrod</option>
        <option value="gray" style="color:gray">gray</option>
        </select></td><p>

<td><select name="Font" onchange="addFontFace (this.options [this.selectedIndex].value);this.options [0].selected = true" style="background-color:ivory;font-size:9pt">
        <option value="" style="color:blue;background-color:gainsboro">Font...</option>
        <option value="Verdana" style="font-family:Verdana">Verdana</option>
        <option value="Arial" style="font-family:Arial">Arial</option>
        <option value="Helvetica" style="font-family:Helvetica">Helvetica</option>
        <option value="Trebuchet MS" style="font-family:Trebuchet MS">Trebuchet MS</option>
        <option value="Courier New" style="font-family:Courier New">Courier</option>
        <option value="Times New Roman" style="font-family:Verdana">Times</option>
        </select></td><p>


<td><select name="Align" onchange="addAlignment (this.options [this.selectedIndex].value);this.options [0].selected = true" style="background-color:ivory;font-size:9pt">
        <option value="" style="color:blue;background-color:gainsboro">Alignment...</option>
        <option value="left">Left</option>
        <option value="center">Center</option>
        <option value="right">Right</option>
        <option value="justify">Justify</option>
        </select></td><p>

<td><select name="Format" onchange="addFormat (this.options [this.selectedIndex].value);this.options [0].selected = true" style="background-color:ivory;font-size:9pt">
        <option value="" style="color:blue;background-color:gainsboro">Format...</option>
        <option value="pre">Preformatted</option>
        <option value="code">Code</option>
        <option value="blockquote">Blockquote</option>
        <option value="">------------</option>
        <option value="ul">Bulleted List</option>
        <option value="ol">Numbered List</option>
        <option value="li">List Item</option>
        <option value="">------------</option>
        <option value="table">Table</option>
        <option value="tr">Table Row</option>
        <option value="td">Table Cell</option>
        </select></td><p>

<td><a href="javascript:addLink ()" style="background-color:gainsboro;color:black;text-decoration:none;font-size:9pt" onmouseover="highlightButton (this)" onmouseout="unHighlightButton (this)">&nbsp;&nbsp;<b>Add Link</b>...&nbsp;&nbsp;</a></td>
</td></tr></table>


<?

}
?>