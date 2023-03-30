<?php
/*****************************************************************************
 *                                                                           *
 *                Web Application Development with PHP                       *
 *                                 by                                        *
 *                 Tobias Ratschiller and Till Gerken                        *
 *                                                                           *
 *          Copyright (c) 2000, Tobias Ratschiller and Till Gerken           *
 *                                                                           *
 *****************************************************************************
 *                                                                           *
 * $Title: String validation routines $                                      *
 * $Chapter: Basic Web Application Strategies $                              *
 * $Executable: false $                                                      *
 *                                                                           *
 * $Description:                                                             *
 * This file contains some routines useful for form validation and string    *
 * checking:                                                                 *
 * - is_alpha()                                                              *
 * - is_numeric()                                                            *
 * - is_alphanumeric()                                                       *
 * - is_email()                                                              *
 * - is_clean_text()                                                         *
 * - contains_bad_words()                                                    *
 * - contains_phone_number()                                                 *
 * -  is_date($fecha)
 *                                                                           *
 *****************************************************************************/

function _is_valid($string, $min_length, $max_length, $regex)
{
	$regex=utf8_encode($regex);
    // Check if the string is empty
    $str = trim($string);
    if(empty($str))
    {
		//echo "entra1";exit;
        return(false);
    }

    // Does the string entirely consist of characters of $type?
    if(!eregi("^$regex$", $string))
    {
		mb_internal_encoding('UTF-8');
		$regex=utf8_encode($regex);
		//echo mb_internal_encoding();
		//echo " entra2, string=$string regexp=$regex";exit;
        return(false);
    }

    // Check for the optional length specifiers
    $strlen = strlen($string);
    if(($min_length != 0 && $strlen < $min_length) || ($max_length != 0 && $strlen > $max_length))
    {
		//echo "entra3";exit;
        return(false);
    }

    // Passed all tests
    return(true);

}

/*
 *      bool is_alpha(string string[, int min_length[, int max_length]])
 *      Check if a string consists of alphabetic characters only. Optionally
 *      check if it has a minimum length of min_length characters and/or a
 *      maximum length of max_length characters.
 */
function is_alpha($string, $min_length = 0, $max_length = 0)
{
    $ret = _is_valid($string, $min_length, $max_length, "[[:alpha:]]+");

    return($ret);
}

/*
 *      bool is_numeric(string string[, int min_length[, int max_length]])
 *      Check if a string consists of digits only. Optionally
 *      check if it has a minimum length of min_length characters and/or a
 *      maximum length of max_length characters.
 */
/*function is_numeric($string, $min_length = 0, $max_length = 0)
{
    $ret = _is_valid($string, $min_length, $max_length, "[[:digit:]]+");

    return($ret);
}
  */


/*
 *      bool is_alphanumeric(string string[, int min_length[, int max_length]])
 *      Check if a string consists of alphanumeric characters only. Optionally
 *      check if it has a minimum length of min_length characters and/or a
 *      maximum length of max_length characters.
 */
function is_alphanumeric($string, $min_length = 0, $max_length = 0)
{
    $ret = _is_valid($string, $min_length, $max_length, "[[:alnum:]]+");

    return($ret);
}

/*
 *      bool is_email(string string[, int min_length[, int max_length]])
 *      Check if a string is a syntactically valid mail address. Optionally
 *      check if it has a minimum length of min_length characters and/or a
 *      maximum length of max_length characters.
 */
function is_email($string)
{
    // Remove whitespace
    $string = trim($string);

    $ret = eregi(
                '^([a-z0-9_]|\\-|\\.)+'.
                '@'.
                '(([a-z0-9_]|\\-)+\\.)+'.
                '[a-z]{2,4}$',
                $string);

    return($ret);
}

/*
 *      bool is_clean_text(string string[, int min_length[, int max_length]])
 *      Check if a string contains only a subset of alphanumerics characters
 *      allowed in the Western alphabets. Useful for validation of names.
 *      Optionally check if it has a minimum length of min_length characters and/or a
 *      maximum length of max_length characters.
 */
function is_clean_text($string, $min_length = 0, $max_length = 0)
{
	return true;
	mb_internal_encoding('UTF-8');
    $ret = _is_valid($string, $min_length, $max_length, "[a-zA-Z[:space:]¿¡¬√ƒ≈∆«»… ÀÃÕŒœ–—“”‘’÷ÿŸ⁄€‹›ﬁﬂ‡·‚„‰ÂÊÁËÈÍÎÏÌÓÔÒÚÛÙıˆ¯˘˙˚¸˝˛`¥']+");

    return($ret);
}

/*
 *      bool is_clean_text_strict(string string[, int min_length[, int max_length]])
*lo mismo que el anterior pero solo caracteres , numeros y signos de puntuacion
 */
function is_clean_text_strict($string, $min_length = 0, $max_length = 0)
{
    $ret = _is_valid($string, $min_length, $max_length, "[a-zA-Z[:space:]`¥'_0-9\.;,+@]+");

    return($ret);
}

/*
 *      bool contains_bad_words(string string)
 *      Check if a string contains bad words, as defined in the array below
 */
function contains_bad_words($string)
{
    // This array contains words which trigger the "meep" feature of this function
    // (ie. if one of the array elements is found in the string, the function will
    // return true). Please note that these words do not constitute a rating of their
    // meanings - they're used for a first indication if the string might contain
    // inapropiate language.
    $bad_words = array(
                    'anal',           'ass',        'bastard',
                    'bitch',          'blow',       'butt',
                    'cock',           'clit',       'cock',
                    'cornh',          'cum',        'cunnil',
                    'cunt',           'dago',       'defecat',
                    'dick',           'dildo',      'douche',
                    'erotic',         'fag',        'fart',
                    'felch',          'fellat',     'fuck',
                    'gay',            'genital',    'gosh',
                    'hate',           'homo',       'honkey',
                    'horny',          'jesus',      'jew',
                    'jiz',            'kike',       'kill',
                    'lesbian',        'masoc',      'masturba',
                    'nazi',           'nigger',     'nude',
                    'nudity',         'oral',       'pecker',
                    'penis',          'potty',      'pussy',
                    'rape',           'rimjob',     'satan',
                    'screw',          'semen',      'sex',
                    'shit',           'slut',       'snot',
                    'spew',           'suck',       'tit',
                    'twat',           'urinat',     'vagina',
                    'viag',           'vibrator',   'whore',
                    'xxx'
    );

    // Check for bad words
    for($i=0; $i<count($bad_words); $i++)
    {
        if(strstr(strtoupper($string), strtoupper($bad_words[$i])))
        {
            return(true);
        }
    }

    // Passed the test
    return(false);
}

/*
 *      bool contains_phone_number(string string)
 *      Check if a string contains a phone number (any 10+-digit sequence,
 *      optionally separated by "(", " ", "-" or "/").
 */
function contains_phone_number($string)
{
     // Check for phone number
     if(ereg("[[:digit:]]{3,10}[\. /\)\(-]*[[:digit:]]{6,10}", $string))
     {
        return(true);
     }

     // Passed the test
     return(false);
}

function is_date($string)
{
	$s=trim($string);
	//echo $s;exit;
	list($d,$m,$a)=split('[/.-]',$s);
	return checkdate($m,$d,$a);
}

/* $Id: String_Validation.inc.php,v 1.1 2000/06/15 18:04:19 tobias Exp $ */

?>
