<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META http-equiv="Content-type" content="text/html; charset=UTF-8">
<TITLE>PHP Race</TITLE>
</HEAD>
<BODY>

<?PHP

function CheckForSTD13Name ($IN_ARRAY)
{
    # The input is an array of Unicode characters
    $STD13 = true;
    $STD13Chars = array(
        "0030","0031","0032","0033","0034","0035","0036","0037","0038","0039", # 0/9
        "0041","0042","0043","0044","0045","0046","0047","0048","0049","004a","004b","004c","004d","004e","004f", # A/O
        "0050","0051","0052","0053","0054","0055","0056","0057","0058","0059","005a", # P/Z
        "005f", # -
        "0061","0062","0063","0064","0065","0066","0067","0068","0069","006a","006b","006c","006d","006e","006f", # a/o
        "0070","0071","0072","0073","0074","0075","0076","0077","0078","0079","007a", # p/z
    );

    $check_array = array_unique ($IN_ARRAY); # no need to double check :]

    foreach($IN_ARRAY as $char)
    {
        if (!in_array(strtolower($char),$STD13Chars))
        {
            $STD13 = false;
        }
    }

    return $STD13;
}

function UCS4toRACE ($IN_ARRAY, &$DEST_RESULT)
{
    # The input is an array of the UTF16 characters in hexstrings ("006c")

    $RACEPrefix = "bq--";
    $InArr = array();
    $UpperSeen = array();
    $UpperUniq = array();
    $DoStep3 = false;
    $U1 = "";
    $U2 = "";
    $N1 = "";
    $CompString = "";

    # Make an array of the UTF16 octets
    foreach($IN_ARRAY as $key => $char)
    {
        $char = strtolower($char);
        array_push ($InArr,chr(hexdec($char[0].$char[1])),chr(hexdec($char[2].$char[3])));
    }

    if(CheckForSTD13Name($IN_ARRAY))
    {
        # The name contains only ascii chars
        $DEST_RESULT["result"] = join("",$InArr); # TD: UCS4toUTF8($IN_ARRAY);
        $DEST_RESULT["DomainConverted"] = false;
        return true;
    }else
    {
        # Prepare for steps 1 and 2 by making an array of the upper octets
        for($InputPointer = 0; $InputPointer <= (count($InArr)-1); $InputPointer += 2)
        {
                if (!isset($UpperSeen[$InArr[$InputPointer]]))
                {
                        $UpperSeen[$InArr[$InputPointer]] = true;
                        if (!isset($UpperUniq[$InArr[$InputPointer]]))
                            array_push ($UpperUniq, $InArr[$InputPointer]);
                }
        }

        if(count($UpperUniq) == 1) # Step 1
        {
            $U1 = $UpperUniq[0];
            $DoStep3 = false;
        } else
        if(count($UpperUniq) == 2) # Step 2
        {
            if($UpperUniq[0] == "\x00")
            {
                $U1 = $UpperUniq[1];
                $DoStep3 = false;
            } else
            if($UpperUniq[1] == "\x00")
            {
                $U1 = $UpperUniq[0];
                $DoStep3 = false;
            } else { $DoStep3 = true; }
        } else { $DoStep3 = true; }

        # Now output based on the value of $DoStep3
        if($DoStep3) # Step 3
        {
            $CompString = "\xd8" . join('', $InArr);
        } else
        {
            if((ord($U1)>215)&&(ord($U1)<223)) # Step 4a
            {
                $DieOrd = sprintf("%04lX", ord($U1));
                $DEST_RESULT["error"] = "Found invalid input to UCS4toRACE step 4a: $DieOrd.";
                return false;
            }
            $CompString = $U1;  # Step 4b
            $InputPointer = 0;
            while($InputPointer <= (count($InArr)-1)) # Step 5a
            {
                $U2 = $InArr[$InputPointer++]; $N1 = $InArr[$InputPointer++];  # Step 5b
                if(($U2 == "\x00")&&($N1 == "\x99"))  # Step 5c
                {
                    $DEST_RESULT["error"] = "Found U+0099 in input stream to UCS4toRACE step 5c.";
                    return false;
                }
                if(($U2 == $U1)&&($N1 != "\xff"))  # Step 6
                    $CompString .= $N1;
                elseif(($U2 == $U1)&&($N1 == "\xff"))  # Step 7
                    $CompString .= "\xff\x99";
                else
                    $CompString .= "\xff" . $N1; # Step 8
            }
            if(strlen($CompString) >= 37)
            {
                $DEST_RESULT["error"] = "Lenth of compressed string was >= 37 in UCS4toRACE.";
                return false;
            }
            $PostBase32 = Base32Encode($CompString);
            $DEST_RESULT["DomainConverted"] = true;
            $DEST_RESULT["result"] = "$RACEPrefix$PostBase32";
            return true;
        }
    }
}

function Base32Encode ($IN_STRING)
{
    $OutString = "";
    $CompBits = "";
    $BASE32_TABLE = array(
        "00000" => 0x61,
        "00001" => 0x62,
        "00010" => 0x63,
        "00011" => 0x64,
        "00100" => 0x65,
        "00101" => 0x66,
        "00110" => 0x67,
        "00111" => 0x68,
        "01000" => 0x69,
        "01001" => 0x6a,
        "01010" => 0x6b,
        "01011" => 0x6c,
        "01100" => 0x6d,
        "01101" => 0x6e,
        "01110" => 0x6f,
        "01111" => 0x70,
        "10000" => 0x71,
        "10001" => 0x72,
        "10010" => 0x73,
        "10011" => 0x74,
        "10100" => 0x75,
        "10101" => 0x76,
        "10110" => 0x77,
        "10111" => 0x78,
        "11000" => 0x79,
        "11001" => 0x7a,
        "11010" => 0x32,
        "11011" => 0x33,
        "11100" => 0x34,
        "11101" => 0x35,
        "11110" => 0x36,
        "11111" => 0x37,
    );

    # Turn the compressed string into a string that represents the bits as
    # 0 and 1. This is wasteful of space but easy to read and debug.

    for ($i = 0; $i < strlen($IN_STRING); $i++)
        $CompBits .= str_pad(decbin(ord($IN_STRING[$i])), 8, "0", STR_PAD_LEFT);

    # Pad the value with enough 0's to make it a multiple of 5
    if((strlen($CompBits) % 5) != 0)
        $CompBits = str_pad($CompBits, strlen($CompBits)+(5-(strlen($CompBits)%5)), "0", STR_PAD_RIGHT);

    # Create an array by chunking it every 5 chars
    $FiveBitsArray = split("\n",rtrim(chunk_split($CompBits, 5, "\n")));

    # Look-up each chunk and add it to $outstring (thank God for arrays ;)
    foreach($FiveBitsArray as $FiveBitsString)
    {
        $OutString .= chr($BASE32_TABLE[$FiveBitsString]);
    }

    return $OutString;
}

function UCS4toUTF8 ($IN_ARRAY, &$DEST_STRING)
{
    $return = true;
    if(!empty($IN_ARRAY))
    {
        $char = ord(array_shift($IN_ARRAY));
        if ($char < 0x80) {
                $DEST_STRING .= chr($char);
                $DEST_STRING .= "\0";
        }
        else if ($char < 0x800) {
                $DEST_STRING .= chr(0xC0 | ($char >>  6));
                $DEST_STRING .= chr(0x80 | (($char) & 0x3F));
                $DEST_STRING .= "\0";
        }
        else if ($char < 0x10000) {
                $DEST_STRING .= chr(0xE0 | ($char >> 12));
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 6);
                $DEST_STRING .= chr(0x80 | (($char) & 0x3F));
                $DEST_STRING .= "\0";
        }
        else if ($char < 0x200000) {
                $DEST_STRING .= chr(0xF0 | ($char >> 18));
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 12);
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 6);
                $DEST_STRING .= chr(0x80 | (($char) & 0x3F));
                $DEST_STRING .= "\0";
        }
        else if ($char < 0x400000) {
                $DEST_STRING .= chr(0xF8 | ($char >> 24));
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 18);
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 12);
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 6);
                $DEST_STRING .= chr(0x80 | (($char) & 0x3F));
                $DEST_STRING .= "\0";
        }
        else if ($char < 0x80000000) {
                $DEST_STRING .= chr(0xFC | ($char >> 30));
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 24);
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 18);
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 12);
                $DEST_STRING .= ((0x80 | (($char) & 0x3F)) >> 6);
                $DEST_STRING .= chr(0x80 | (($char) & 0x3F));
                $DEST_STRING .= "\0";
        }
        else {  /* Not a valid Unicode "character" */
                $DEST_STRING .= "\0";
                $return = false;
        }
    }

    if(!empty($IN_ARRAY))
        $return = UCS4toUTF8 ($IN_ARRAY, $DEST_STRING);

    if ($return)
        return true;
    else
        return false;
}

function UTF8toUCS4 ($IN_STRING, &$DEST_ARRAY)
{
    $return = true;

    for ($i = 0; $i < strlen($IN_STRING); $i++)
        $inputarray[$i] = $IN_STRING[$i];

    $char = ord(array_shift($inputarray));

    if (($char & 0xFE) == 0xFC) {
        $char &= 0x01;
        $bytes = 5;
    }
    else if (($char & 0xFC) == 0xF8) {
        $char &= 0x03;
        $bytes = 4;
    }
    else if (($char & 0xF8) == 0xF0) {
        $char &= 0x07;
        $bytes = 3;
    }
    else if (($char & 0xF0) == 0xE0) {
        $char &= 0x0F;
        $bytes = 2;
    }
    else if (($char & 0xE0) == 0xC0) {
        $char &= 0x1F;
        $bytes = 1;
    }
    else if (($char & 0x80) == 0x80)
    {
        // invalid UTF-8 encoding
        $bytes = 0;
        return false;
        array_push($DEST_ARRAY, -1);
    }
    else
    {
        // no multi-byte (ASCII)
        $bytes = 0;
        array_push($DEST_ARRAY, str_pad(dechex($char), 4, "0", STR_PAD_LEFT));
    }

    $charbuff = "";
    for ($i = 0; $i < $bytes; $i++) {
        $byte = ord(array_shift($inputarray));
        if (($byte & 0xC0) != 0x80)
            return false;
        $char <<= 6;
        $char |= $byte & 0x3F;
        $charbuff = dechex($char);
    }

    if (!empty($charbuff))
        array_push($DEST_ARRAY, str_pad($charbuff, 4, "0", STR_PAD_LEFT));

    if (!empty($inputarray))
        $return = UTF8toUCS4(join(NULL,$inputarray),$DEST_ARRAY);

    if ($return)
        return true;
    else
        return false;
}

if (!isset($action)) $action = false;

switch($action)
{
    case "race_me":
        print "<PRE>";

        $DEST_ARRAY = array();

        $TEMP_ARRAY = explode(".",$original_domain_value);

        UTF8toUCS4($TEMP_ARRAY[0], $DEST_ARRAY);

        if(UCS4toRACE($DEST_ARRAY, $DEST_RESULT))
        {
            if($DEST_RESULT["DomainConverted"])
            {
                print "Original domain:     $original_domain_value\n";
                print "Race encoded domain: {$DEST_RESULT["result"]}.{$TEMP_ARRAY[1]}\n";
            }
            else
            {
                print "Original domain:     $original_domain_value\n";
                print "Domain does not need RACE encoding!\n";
            }
        }else
        {
            print "An error occured: {$DEST_RESULT["error"]}\n";
        }

        print "</PRE>";
        break;
    default
?>
<P><B>PHP RACE</B> - plain php (no required non-standard modules) - <a href="mailto:a.wekking@synantic.nl">Arjan Wekking</a>
<P>Just enter the multi-lingual domain here as you would at <a href="http://www.opensrs.org/cgi-bin/RACE/index.cgi">OpenSRS</a> and it will return the RACE encoded domain (bq-xxx.com). Note that there is no nameprepping or character validity checking (yet), so all unicode characters can be converted even though it may not be possible with the perl OpenSRS client!
<P>This is all the code and functionality I have ported at the moment. It consists of UTF8toUCS4(), UCS4toUTF8() (since there are no proper standard PHP functions, apart from the GNU Recode module) and the RACE perl package functions Base32Encode(), CheckForSTD13Name() and UCS4toRACE() of the OpenSRS perl client, v2.33.
<P>Multi-lingual Domain Name (anything©.com):
<form method="POST" action="<?=$PHP_SELF?>">
<input type="text" name="original_domain_value" value="" size=20><input type="submit" name="race_me" value="Encode">
<input type="HIDDEN" name="action" value="race_me">
</form>
<P>Note: This page uses charset UTF-8, and thus should submit with UTF-8 encoding.
<?PHP
        break;
}

?>

</BODY>
