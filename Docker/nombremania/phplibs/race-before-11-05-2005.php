<?
//
// +----------------------------------------------------------------------+
// | PHP version 4.0                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2001 The PHP Group                                     |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Arjan Wekking <a.wekking@synantics.nl>                      |
// +----------------------------------------------------------------------+
//
// $Id$

/**
* Net::RACE
*
* What is 'RACE'?
*
*   RACE stands for Row-based ASCII Compatible Encoding, a transformation
*   method for representing non-ASCII characters in host name parts in a
*   fashion that is completely compatible with the current DNS.
*
*   Although still an 'internet-draft' to my knowledge, this method is
*   now the standard used by all the registrars that can register
*   internationalized domains containing more than just the western
*   ASCII characters.
*
*   An example of a RACE encoded domain:
*
*       'hellô.com' <- de/en-codes to -> 'bq--abugk3dm6q.com'
*
*   This encoding is done at the client, for example a user's browser
*   which encodes the international domain into it's bq--***.tld
*   counterpart and actually requests bq--***.tld from the DNS and
*   for instance the webserver.
*
*   The RACE method is described in this official ietf draft (3):
*   http://www.ietf.org/internet-drafts/draft-ietf-idn-race-03.txt
*
*   The prohibited and unassigned characters, and Unicode folding/mapping
*   is according to the following ietf idn draft (3):
*   http://www.ietf.org/internet-drafts/draft-ietf-idn-nameprep-03.txt
*
* Purpose of this class:
*
*   This class can encode international domains into RACE compliant
*   domains and vice versa.
*
* Example:
*
*   $RACE = new Net_RACE("UTF-8");
*
*   $RACE->doRace("hellô");
*
*   if($RACE->raceConverted) {
*       echo "Domain converted to:\n";
*       echo $RACE->raceResult;
*   } else {
*       echo "Domain was not converted:\n";
*       echo $RACE->raceError;
*   }
*
* NOTE:
*
*   This class depends on the multibyte character support that is
*   included with PHP, and is currently (as of PHP version 4.06) subject
*   to change and not enabled by default.
*
*   When compiling PHP from the sourcecode, add the --enable-mbstring
*   ./configure directive to enable the multibyte string support to PHP.
*
* @author   Arjan Wekking <a.wekking@synantics.nl>
**/
require_once 'PEAR.php';


class Net_RACE extends PEAR {

    /* valid character encodings (that are supported by PHP) */

    var $raceKnownCE = array (
        'UCS-4',
        'UCS-4BE',
        'UCS-4LE',
        'UCS-2',
        'UCS-2BE',
        'UCS-2LE',
        'UTF-32',
        'UTF-32BE',
        'UTF-32LE',
        'UCS-2LE',
        'UTF-16',
        'UTF-16BE',
        'UTF-16LE',
        'UTF-8',
        'UTF-7',
        'ASCII',
        'EUC-JP',
        'SJIS',
        'eucJP-win',
        'SJIS-win',
        'ISO-2022-JP(JIS)',
        'ISO-8859-1',
        'ISO-8859-2',
        'ISO-8859-3',
        'ISO-8859-4',
        'ISO-8859-5',
        'ISO-8859-6',
        'ISO-8859-7',
        'ISO-8859-8',
        'ISO-8859-9',
        'ISO-8859-10',
        'ISO-8859-13',
        'ISO-8859-14',
        'ISO-8859-15',
    );


    /* following idn name preparation tables according to ietf idn draft 3, 24-02-2001 */
    /* http://www.ietf.org/internet-drafts/draft-ietf-idn-nameprep-03.txt */
   /* array of prohibited characters */
    var $racePC = array(
        0x3000,
        0x3002,
        0x00A0,
        0x1680,
        0x2000,
        0x2001,
        0x2002,
        0x2003,
        0x2004,
        0x2005,
        0x2006,
        0x2007,
        0x2008,
        0x2009,
        0x200A,
        0x200B,
        0x200E,
        0x200F,
        0x2028,
        0x2029,
        0x202A,
        0x202B,
        0x202C,
        0x202D,
        0x202E,
        0x202F,
        0x206A,
        0x206B,
        0x206C,
        0x206D,
        0x206E,
        0x206F,
        0xFFF9,
        0xFFFA,
        0xFFFB,
        0xFFFC,
        0xFFFD
    );
    /* array of prohibited character ranges */
    var $racePCR = array(
        array(0x0000,0x002C),
        array(0x002E,0x002F),
        array(0x003A,0x0040),
        array(0x005B,0x0060),
        array(0x007B,0x007F),
        array(0x0080,0x009F),
        array(0x2FF0,0x2FFF),
        array(0xD800,0xDFFF),
        array(0xE000,0xF8FF),
        array(0xFFFE,0xFFFF),
        array(0x1FFFE,0x1FFFF),
        array(0x2FFFE,0x2FFFF),
        array(0x3FFFE,0x3FFFF),
        array(0x4FFFE,0x4FFFF),
        array(0x5FFFE,0x5FFFF),
        array(0x6FFFE,0x6FFFF),
        array(0x7FFFE,0x7FFFF),
        array(0x8FFFE,0x8FFFF),
        array(0x9FFFE,0x9FFFF),
        array(0xAFFFE,0xAFFFF),
        array(0xBFFFE,0xBFFFF),
        array(0xCFFFE,0xCFFFF),
        array(0xDFFFE,0xDFFFF),
        array(0xEFFFE,0xEFFFF),
        array(0xF0000,0xFFFFD),
        array(0xFFFFE,0xFFFFF),
        array(0x100000,0x10FFFD),
        array(0x10FFFE,0x10FFFF)
    );




    /* array of unassigned characters */

    var $raceUC = array(

        0x038B,

        0x038D,

        0x03A2,

        0x03CF,

        0x0487,

        0x0560,

        0x0588,

        0x05A2,

        0x05BA,

        0x0620,

        0x06FF,

        0x070E,

        0x0904,

        0x0984,

        0x09A9,

        0x09B1,

        0x09BD,

        0x09DE,

        0x0A29,

        0x0A31,

        0x0A34,

        0x0A37,

        0x0A3D,

        0x0A5D,

        0x0A84,

        0x0A8C,

        0x0A8E,

        0x0A92,

        0x0AA9,

        0x0AB1,

        0x0AB4,

        0x0AC6,

        0x0ACA,

        0x0B04,

        0x0B29,

        0x0B31,

        0x0B5E,

        0x0B84,

        0x0B91,

        0x0B9B,

        0x0B9D,

        0x0BB6,

        0x0BC9,

        0x0C04,

        0x0C0D,

        0x0C11,

        0x0C29,

        0x0C34,

        0x0C45,

        0x0C49,

        0x0C84,

        0x0C8D,

        0x0C91,

        0x0CA9,

        0x0CB4,

        0x0CC5,

        0x0CC9,

        0x0CDF,

        0x0D04,

        0x0D0D,

        0x0D11,

        0x0D29,

        0x0D49,

        0x0D84,

        0x0DB2,

        0x0DBC,

        0x0DD5,

        0x0DD7,

        0x0E83,

        0x0E89,

        0x0E98,

        0x0EA0,

        0x0EA4,

        0x0EA6,

        0x0EAC,

        0x0EBA,

        0x0EC5,

        0x0EC7,

        0x0F48,

        0x0F98,

        0x0FBD,

        0x1022,

        0x1028,

        0x102B,

        0x1207,

        0x1247,

        0x1249,

        0x1257,

        0x1259,

        0x1287,

        0x1289,

        0x12AF,

        0x12B1,

        0x12BF,

        0x12C1,

        0x12CF,

        0x12D7,

        0x12EF,

        0x130F,

        0x1311,

        0x131F,

        0x1347,

        0x180F,

        0x1F58,

        0x1F5A,

        0x1F5C,

        0x1F5E,

        0x1FB5,

        0x1FC5,

        0x1FDC,

        0x1FF5,

        0x1FFF,

        0x2047,

        0x237C,

        0x2705,

        0x2728,

        0x274C,

        0x274E,

        0x2757,

        0x2E9A,

        0x27B0,

        0x3040,

        0x318F,

        0x32FF,

        0x33FF,

        0xA4B4,

        0xA4C1,

        0xA4C5,

        0xFB37,

        0xFB3D,

        0xFB3F,

        0xFB42,

        0xFB45,

        0xFE53,

        0xFE67,

        0xFE73,

        0xFE75,

        0xFF00,

        0xFFE7

    );

    /* array of unassigned character ranges */

    var $raceUCR = array(

        array(0x0220,0x0221),

        array(0x0234,0x024F),

        array(0x02AE,0x02AF),

        array(0x02EF,0x02FF),

        array(0x034F,0x035F),

        array(0x0363,0x0373),

        array(0x0376,0x0379),

        array(0x037B,0x037D),

        array(0x037F,0x0383),

        array(0x03D8,0x03D9),

        array(0x03F4,0x03FF),

        array(0x048A,0x048B),

        array(0x04C5,0x04C6),

        array(0x04C9,0x04CA),

        array(0x04CD,0x04CF),

        array(0x04F6,0x04F7),

        array(0x04FA,0x0530),

        array(0x0557,0x0558),

        array(0x058B,0x0590),

        array(0x05C5,0x05CF),

        array(0x05EB,0x05EF),

        array(0x05F5,0x060B),

        array(0x060D,0x061A),

        array(0x061C,0x061E),

        array(0x063B,0x063F),

        array(0x0656,0x065F),

        array(0x066E,0x066F),

        array(0x06EE,0x06EF),

        array(0x072D,0x072F),

        array(0x074B,0x077F),

        array(0x07B1,0x0900),

        array(0x093A,0x093B),

        array(0x094E,0x094F),

        array(0x0955,0x0957),

        array(0x0971,0x0980),

        array(0x098D,0x098E),

        array(0x0991,0x0992),

        array(0x09B3,0x09B5),

        array(0x09BA,0x09BB),

        array(0x09C5,0x09C6),

        array(0x09C9,0x09CA),

        array(0x09CE,0x09D6),

        array(0x09D8,0x09DB),

        array(0x09E4,0x09E5),

        array(0x09FB,0x0A01),

        array(0x0A03,0x0A04),

        array(0x0A0B,0x0A0E),

        array(0x0A11,0x0A12),

        array(0x0A3A,0x0A3B),

        array(0x0A43,0x0A46),

        array(0x0A49,0x0A4A),

        array(0x0A4E,0x0A58),

        array(0x0A5F,0x0A65),

        array(0x0A75,0x0A80),

        array(0x0ABA,0x0ABB),

        array(0x0ACE,0x0ACF),

        array(0x0AD1,0x0ADF),

        array(0x0AE1,0x0AE5),

        array(0x0AF0,0x0B00),

        array(0x0B0D,0x0B0E),

        array(0x0B11,0x0B12),

        array(0x0B34,0x0B35),

        array(0x0B3A,0x0B3B),

        array(0x0B44,0x0B46),

        array(0x0B49,0x0B4A),

        array(0x0B4E,0x0B55),

        array(0x0B58,0x0B5B),

        array(0x0B62,0x0B65),

        array(0x0B71,0x0B81),

        array(0x0B8B,0x0B8D),

        array(0x0B96,0x0B98),

        array(0x0BA0,0x0BA2),

        array(0x0BA5,0x0BA7),

        array(0x0BAB,0x0BAD),

        array(0x0BBA,0x0BBD),

        array(0x0BC3,0x0BC5),

        array(0x0BCE,0x0BD6),

        array(0x0BD8,0x0BE6),

        array(0x0BF3,0x0C00),

        array(0x0C3A,0x0C3D),

        array(0x0C4E,0x0C54),

        array(0x0C57,0x0C5F),

        array(0x0C62,0x0C65),

        array(0x0C70,0x0C81),

        array(0x0CBA,0x0CBD),

        array(0x0CCE,0x0CD4),

        array(0x0CD7,0x0CDD),

        array(0x0CE2,0x0CE5),

        array(0x0CF0,0x0D01),

        array(0x0D3A,0x0D3D),

        array(0x0D44,0x0D45),

        array(0x0D4E,0x0D56),

        array(0x0D58,0x0D5F),

        array(0x0D62,0x0D65),

        array(0x0D70,0x0D81),

        array(0x0D97,0x0D99),

        array(0x0DBE,0x0DBF),

        array(0x0DC7,0x0DC9),

        array(0x0DCB,0x0DCE),

        array(0x0DE0,0x0DF1),

        array(0x0DF5,0x0E00),

        array(0x0E3B,0x0E3E),

        array(0x0E5C,0x0E80),

        array(0x0E85,0x0E86),

        array(0x0E8B,0x0E8C),

        array(0x0E8E,0x0E93),

        array(0x0EA8,0x0EA9),

        array(0x0EBE,0x0EBF),

        array(0x0ECE,0x0ECF),

        array(0x0EDA,0x0EDB),

        array(0x0EDE,0x0EFF),

        array(0x0F6B,0x0F70),

        array(0x0F8C,0x0F8F),

        array(0x0FCD,0x0FCE),

        array(0x0FD0,0x0FFF),

        array(0x1033,0x1035),

        array(0x103A,0x103F),

        array(0x105A,0x109F),

        array(0x10C6,0x10CF),

        array(0x10F7,0x10FA),

        array(0x10FC,0x10FF),

        array(0x115A,0x115E),

        array(0x11A3,0x11A7),

        array(0x11FA,0x11FF),

        array(0x124E,0x124F),

        array(0x125E,0x125F),

        array(0x128E,0x128F),

        array(0x12B6,0x12B7),

        array(0x12C6,0x12C7),

        array(0x1316,0x1317),

        array(0x135B,0x1360),

        array(0x137D,0x139F),

        array(0x13F5,0x1400),

        array(0x1677,0x167F),

        array(0x169D,0x169F),

        array(0x16F1,0x177F),

        array(0x17DD,0x17DF),

        array(0x17EA,0x17FF),

        array(0x181A,0x181F),

        array(0x1878,0x187F),

        array(0x18AA,0x1DFF),

        array(0x1E9C,0x1E9F),

        array(0x1EFA,0x1EFF),

        array(0x1F16,0x1F17),

        array(0x1F1E,0x1F1F),

        array(0x1F46,0x1F47),

        array(0x1F4E,0x1F4F),

        array(0x1F7E,0x1F7F),

        array(0x1FD4,0x1FD5),

        array(0x1FF0,0x1FF1),

        array(0x204E,0x2069),

        array(0x2071,0x2073),

        array(0x208F,0x209F),

        array(0x20B0,0x20CF),

        array(0x20E4,0x20FF),

        array(0x213B,0x2152),

        array(0x2184,0x218F),

        array(0x21F4,0x21FF),

        array(0x22F2,0x22FF),

        array(0x239B,0x23FF),

        array(0x2427,0x243F),

        array(0x244B,0x245F),

        array(0x24EB,0x24FF),

        array(0x2596,0x259F),

        array(0x25F8,0x25FF),

        array(0x2614,0x2618),

        array(0x2672,0x2700),

        array(0x270A,0x270B),

        array(0x2753,0x2755),

        array(0x275F,0x2760),

        array(0x2768,0x2775),

        array(0x2795,0x2797),

        array(0x27BF,0x27FF),

        array(0x2900,0x2E7F),

        array(0x2EF4,0x2EFF),

        array(0x2FD6,0x2FEF),

        array(0x2FFC,0x2FFF),

        array(0x303B,0x303D),

        array(0x3095,0x3098),

        array(0x309F,0x30A0),

        array(0x30FF,0x3104),

        array(0x312D,0x3130),

        array(0x31B8,0x31FF),

        array(0x321D,0x321F),

        array(0x3244,0x325F),

        array(0x327C,0x327E),

        array(0x32B1,0x32BF),

        array(0x32CC,0x32CF),

        array(0x3377,0x337A),

        array(0x33DE,0x33DF),

        array(0x4DB6,0x4DFF),

        array(0x9FA6,0x9FFF),

        array(0xA48D,0xA48F),

        array(0xA4A2,0xA4A3),

        array(0xA4C7,0xABFF),

        array(0xD7A4,0xD7FF),

        array(0xFA2E,0xFAFF),

        array(0xFB07,0xFB12),

        array(0xFB18,0xFB1C),

        array(0xFBB2,0xFBD2),

        array(0xFD40,0xFD4F),

        array(0xFD90,0xFD91),

        array(0xFDC8,0xFDEF),

        array(0xFDFC,0xFE1F),

        array(0xFE24,0xFE2F),

        array(0xFE45,0xFE48),

        array(0xFE6C,0xFE6F),

        array(0xFEFD,0xFEFE),

        array(0xFF5F,0xFF60),

        array(0xFFBF,0xFFC1),

        array(0xFFC8,0xFFC9),

        array(0xFFD0,0xFFD1),

        array(0xFFD8,0xFFD9),

        array(0xFFDD,0xFFDF),

        array(0xFFEF,0xFFF8),

        array(0x10000,0x1FFFD),

        array(0x20000,0x2FFFD),

        array(0x30000,0x3FFFD),

        array(0x40000,0x4FFFD),

        array(0x50000,0x5FFFD),

        array(0x60000,0x6FFFD),

        array(0x70000,0x7FFFD),

        array(0x80000,0x8FFFD),

        array(0x90000,0x9FFFD),

        array(0xA0000,0xAFFFD),

        array(0xB0000,0xBFFFD),

        array(0xC0000,0xCFFFD),

        array(0xD0000,0xDFFFD),

        array(0xE0000,0xEFFFD)

    );

   /* unicode character mapping - (converted to UCS-4 encoding) */

    var $raceUMap = array(

        0x0041 => array("\x00\x00\x00\x61", 'Case map'),

        0x0042 => array("\x00\x00\x00\x62", 'Case map'),

        0x0043 => array("\x00\x00\x00\x63", 'Case map'),

        0x0044 => array("\x00\x00\x00\x64", 'Case map'),

        0x0045 => array("\x00\x00\x00\x65", 'Case map'),

        0x0046 => array("\x00\x00\x00\x66", 'Case map'),

        0x0047 => array("\x00\x00\x00\x67", 'Case map'),

        0x0048 => array("\x00\x00\x00\x68", 'Case map'),

        0x0049 => array("\x00\x00\x00\x69", 'Case map'),

        0x004A => array("\x00\x00\x00\x6A", 'Case map'),

        0x004B => array("\x00\x00\x00\x6B", 'Case map'),

        0x004C => array("\x00\x00\x00\x6C", 'Case map'),

        0x004D => array("\x00\x00\x00\x6D", 'Case map'),

        0x004E => array("\x00\x00\x00\x6E", 'Case map'),

        0x004F => array("\x00\x00\x00\x6F", 'Case map'),

        0x0050 => array("\x00\x00\x00\x70", 'Case map'),

        0x0051 => array("\x00\x00\x00\x71", 'Case map'),

        0x0052 => array("\x00\x00\x00\x72", 'Case map'),

        0x0053 => array("\x00\x00\x00\x73", 'Case map'),

        0x0054 => array("\x00\x00\x00\x74", 'Case map'),

        0x0055 => array("\x00\x00\x00\x75", 'Case map'),

        0x0056 => array("\x00\x00\x00\x76", 'Case map'),

        0x0057 => array("\x00\x00\x00\x77", 'Case map'),

        0x0058 => array("\x00\x00\x00\x78", 'Case map'),

        0x0059 => array("\x00\x00\x00\x79", 'Case map'),

        0x005A => array("\x00\x00\x00\x7A", 'Case map'),

        0x00AD => array('', 'Map out'),

        0x00B5 => array("\x00\x00\x03\xBC", 'Case map'),

        0x00C0 => array("\x00\x00\x00\xE0", 'Case map'),

        0x00C1 => array("\x00\x00\x00\xE1", 'Case map'),

        0x00C2 => array("\x00\x00\x00\xE2", 'Case map'),

        0x00C3 => array("\x00\x00\x00\xE3", 'Case map'),

        0x00C4 => array("\x00\x00\x00\xE4", 'Case map'),

        0x00C5 => array("\x00\x00\x00\xE5", 'Case map'),

        0x00C6 => array("\x00\x00\x00\xE6", 'Case map'),

        0x00C7 => array("\x00\x00\x00\xE7", 'Case map'),

        0x00C8 => array("\x00\x00\x00\xE8", 'Case map'),

        0x00C9 => array("\x00\x00\x00\xE9", 'Case map'),

        0x00CA => array("\x00\x00\x00\xEA", 'Case map'),

        0x00CB => array("\x00\x00\x00\xEB", 'Case map'),

        0x00CC => array("\x00\x00\x00\xEC", 'Case map'),

        0x00CD => array("\x00\x00\x00\xED", 'Case map'),

        0x00CE => array("\x00\x00\x00\xEE", 'Case map'),

        0x00CF => array("\x00\x00\x00\xEF", 'Case map'),

        0x00D0 => array("\x00\x00\x00\xF0", 'Case map'),

        0x00D1 => array("\x00\x00\x00\xF1", 'Case map'),

        0x00D2 => array("\x00\x00\x00\xF2", 'Case map'),

        0x00D3 => array("\x00\x00\x00\xF3", 'Case map'),

        0x00D4 => array("\x00\x00\x00\xF4", 'Case map'),

        0x00D5 => array("\x00\x00\x00\xF5", 'Case map'),

        0x00D6 => array("\x00\x00\x00\xF6", 'Case map'),

        0x00D8 => array("\x00\x00\x00\xF8", 'Case map'),

        0x00D9 => array("\x00\x00\x00\xF9", 'Case map'),

        0x00DA => array("\x00\x00\x00\xFA", 'Case map'),

        0x00DB => array("\x00\x00\x00\xFB", 'Case map'),

        0x00DC => array("\x00\x00\x00\xFC", 'Case map'),

        0x00DD => array("\x00\x00\x00\xFD", 'Case map'),

        0x00DE => array("\x00\x00\x00\xFE", 'Case map'),

        0x00DF => array("\x00\x00\x00\x73\x00\x00\x00\x73", '73'),

        0x0100 => array("\x00\x00\x01\x01", 'Case map'),

        0x0102 => array("\x00\x00\x01\x03", 'Case map'),

        0x0104 => array("\x00\x00\x01\x05", 'Case map'),

        0x0106 => array("\x00\x00\x01\x07", 'Case map'),

        0x0108 => array("\x00\x00\x01\x09", 'Case map'),

        0x010A => array("\x00\x00\x01\x0B", 'Case map'),

        0x010C => array("\x00\x00\x01\x0D", 'Case map'),

        0x010E => array("\x00\x00\x01\x0F", 'Case map'),

        0x0110 => array("\x00\x00\x01\x11", 'Case map'),

        0x0112 => array("\x00\x00\x01\x13", 'Case map'),

        0x0114 => array("\x00\x00\x01\x15", 'Case map'),

        0x0116 => array("\x00\x00\x01\x17", 'Case map'),

        0x0118 => array("\x00\x00\x01\x19", 'Case map'),

        0x011A => array("\x00\x00\x01\x1B", 'Case map'),

        0x011C => array("\x00\x00\x01\x1D", 'Case map'),

        0x011E => array("\x00\x00\x01\x1F", 'Case map'),

        0x0120 => array("\x00\x00\x01\x21", 'Case map'),

        0x0122 => array("\x00\x00\x01\x23", 'Case map'),

        0x0124 => array("\x00\x00\x01\x25", 'Case map'),

        0x0126 => array("\x00\x00\x01\x27", 'Case map'),

        0x0128 => array("\x00\x00\x01\x29", 'Case map'),

        0x012A => array("\x00\x00\x01\x2B", 'Case map'),

        0x012C => array("\x00\x00\x01\x2D", 'Case map'),

        0x012E => array("\x00\x00\x01\x2F", 'Case map'),

        0x0130 => array("\x00\x00\x00\x69", 'Case map'),

        0x0131 => array("\x00\x00\x00\x69", 'Case map'),

        0x0132 => array("\x00\x00\x01\x33", 'Case map'),

        0x0134 => array("\x00\x00\x01\x35", 'Case map'),

        0x0136 => array("\x00\x00\x01\x37", 'Case map'),

        0x0139 => array("\x00\x00\x01\x3A", 'Case map'),

        0x013B => array("\x00\x00\x01\x3C", 'Case map'),

        0x013D => array("\x00\x00\x01\x3E", 'Case map'),

        0x013F => array("\x00\x00\x01\x40", 'Case map'),

        0x0141 => array("\x00\x00\x01\x42", 'Case map'),

        0x0143 => array("\x00\x00\x01\x44", 'Case map'),

        0x0145 => array("\x00\x00\x01\x46", 'Case map'),

        0x0147 => array("\x00\x00\x01\x48", 'Case map'),

        0x0149 => array("\x00\x00\x02\xBC\x00\x00\x00\x6E", '6E'),

        0x014A => array("\x00\x00\x01\x4B", 'Case map'),

        0x014C => array("\x00\x00\x01\x4D", 'Case map'),

        0x014E => array("\x00\x00\x01\x4F", 'Case map'),

        0x0150 => array("\x00\x00\x01\x51", 'Case map'),

        0x0152 => array("\x00\x00\x01\x53", 'Case map'),

        0x0154 => array("\x00\x00\x01\x55", 'Case map'),

        0x0156 => array("\x00\x00\x01\x57", 'Case map'),

        0x0158 => array("\x00\x00\x01\x59", 'Case map'),

        0x015A => array("\x00\x00\x01\x5B", 'Case map'),

        0x015C => array("\x00\x00\x01\x5D", 'Case map'),

        0x015E => array("\x00\x00\x01\x5F", 'Case map'),

        0x0160 => array("\x00\x00\x01\x61", 'Case map'),

        0x0162 => array("\x00\x00\x01\x63", 'Case map'),

        0x0164 => array("\x00\x00\x01\x65", 'Case map'),

        0x0166 => array("\x00\x00\x01\x67", 'Case map'),

        0x0168 => array("\x00\x00\x01\x69", 'Case map'),

        0x016A => array("\x00\x00\x01\x6B", 'Case map'),

        0x016C => array("\x00\x00\x01\x6D", 'Case map'),

        0x016E => array("\x00\x00\x01\x6F", 'Case map'),

        0x0170 => array("\x00\x00\x01\x71", 'Case map'),

        0x0172 => array("\x00\x00\x01\x73", 'Case map'),

        0x0174 => array("\x00\x00\x01\x75", 'Case map'),

        0x0176 => array("\x00\x00\x01\x77", 'Case map'),

        0x0178 => array("\x00\x00\x00\xFF", 'Case map'),

        0x0179 => array("\x00\x00\x01\x7A", 'Case map'),

        0x017B => array("\x00\x00\x01\x7C", 'Case map'),

        0x017D => array("\x00\x00\x01\x7E", 'Case map'),

        0x017F => array("\x00\x00\x00\x73", 'Case map'),

        0x0181 => array("\x00\x00\x02\x53", 'Case map'),

        0x0182 => array("\x00\x00\x01\x83", 'Case map'),

        0x0184 => array("\x00\x00\x01\x85", 'Case map'),

        0x0186 => array("\x00\x00\x02\x54", 'Case map'),

        0x0187 => array("\x00\x00\x01\x88", 'Case map'),

        0x0189 => array("\x00\x00\x02\x56", 'Case map'),

        0x018A => array("\x00\x00\x02\x57", 'Case map'),

        0x018B => array("\x00\x00\x01\x8C", 'Case map'),

        0x018E => array("\x00\x00\x01\xDD", 'Case map'),

        0x018F => array("\x00\x00\x02\x59", 'Case map'),

        0x0190 => array("\x00\x00\x02\x5B", 'Case map'),

        0x0191 => array("\x00\x00\x01\x92", 'Case map'),

        0x0193 => array("\x00\x00\x02\x60", 'Case map'),

        0x0194 => array("\x00\x00\x02\x63", 'Case map'),

        0x0196 => array("\x00\x00\x02\x69", 'Case map'),

        0x0197 => array("\x00\x00\x02\x68", 'Case map'),

        0x0198 => array("\x00\x00\x01\x99", 'Case map'),

        0x019C => array("\x00\x00\x02\x6F", 'Case map'),

        0x019D => array("\x00\x00\x02\x72", 'Case map'),

        0x019F => array("\x00\x00\x02\x75", 'Case map'),

        0x01A0 => array("\x00\x00\x01\xA1", 'Case map'),

        0x01A2 => array("\x00\x00\x01\xA3", 'Case map'),

        0x01A4 => array("\x00\x00\x01\xA5", 'Case map'),

        0x01A6 => array("\x00\x00\x02\x80", 'Case map'),

        0x01A7 => array("\x00\x00\x01\xA8", 'Case map'),

        0x01A9 => array("\x00\x00\x02\x83", 'Case map'),

        0x01AC => array("\x00\x00\x01\xAD", 'Case map'),

        0x01AE => array("\x00\x00\x02\x88", 'Case map'),

        0x01AF => array("\x00\x00\x01\xB0", 'Case map'),

        0x01B1 => array("\x00\x00\x02\x8A", 'Case map'),

        0x01B2 => array("\x00\x00\x02\x8B", 'Case map'),

        0x01B3 => array("\x00\x00\x01\xB4", 'Case map'),

        0x01B5 => array("\x00\x00\x01\xB6", 'Case map'),

        0x01B7 => array("\x00\x00\x02\x92", 'Case map'),

        0x01B8 => array("\x00\x00\x01\xB9", 'Case map'),

        0x01BC => array("\x00\x00\x01\xBD", 'Case map'),

        0x01C4 => array("\x00\x00\x01\xC6", 'Case map'),

        0x01C5 => array("\x00\x00\x01\xC6", 'Case map'),

        0x01C7 => array("\x00\x00\x01\xC9", 'Case map'),

        0x01C8 => array("\x00\x00\x01\xC9", 'Case map'),

        0x01CA => array("\x00\x00\x01\xCC", 'Case map'),

        0x01CB => array("\x00\x00\x01\xCC", 'Case map'),

        0x01CD => array("\x00\x00\x01\xCE", 'Case map'),

        0x01CF => array("\x00\x00\x01\xD0", 'Case map'),

        0x01D1 => array("\x00\x00\x01\xD2", 'Case map'),

        0x01D3 => array("\x00\x00\x01\xD4", 'Case map'),

        0x01D5 => array("\x00\x00\x01\xD6", 'Case map'),

        0x01D7 => array("\x00\x00\x01\xD8", 'Case map'),

        0x01D9 => array("\x00\x00\x01\xDA", 'Case map'),

        0x01DB => array("\x00\x00\x01\xDC", 'Case map'),

        0x01DE => array("\x00\x00\x01\xDF", 'Case map'),

        0x01E0 => array("\x00\x00\x01\xE1", 'Case map'),

        0x01E2 => array("\x00\x00\x01\xE3", 'Case map'),

        0x01E4 => array("\x00\x00\x01\xE5", 'Case map'),

        0x01E6 => array("\x00\x00\x01\xE7", 'Case map'),

        0x01E8 => array("\x00\x00\x01\xE9", 'Case map'),

        0x01EA => array("\x00\x00\x01\xEB", 'Case map'),

        0x01EC => array("\x00\x00\x01\xED", 'Case map'),

        0x01EE => array("\x00\x00\x01\xEF", 'Case map'),

        0x01F0 => array("\x00\x00\x00\x6A\x00\x00\x03\x0C", '0C'),

        0x01F1 => array("\x00\x00\x01\xF3", 'Case map'),

        0x01F2 => array("\x00\x00\x01\xF3", 'Case map'),

        0x01F4 => array("\x00\x00\x01\xF5", 'Case map'),

        0x01F6 => array("\x00\x00\x01\x95", 'Case map'),

        0x01F7 => array("\x00\x00\x01\xBF", 'Case map'),

        0x01F8 => array("\x00\x00\x01\xF9", 'Case map'),

        0x01FA => array("\x00\x00\x01\xFB", 'Case map'),

        0x01FC => array("\x00\x00\x01\xFD", 'Case map'),

        0x01FE => array("\x00\x00\x01\xFF", 'Case map'),

        0x0200 => array("\x00\x00\x02\x01", 'Case map'),

        0x0202 => array("\x00\x00\x02\x03", 'Case map'),

        0x0204 => array("\x00\x00\x02\x05", 'Case map'),

        0x0206 => array("\x00\x00\x02\x07", 'Case map'),

        0x0208 => array("\x00\x00\x02\x09", 'Case map'),

        0x020A => array("\x00\x00\x02\x0B", 'Case map'),

        0x020C => array("\x00\x00\x02\x0D", 'Case map'),

        0x020E => array("\x00\x00\x02\x0F", 'Case map'),

        0x0210 => array("\x00\x00\x02\x11", 'Case map'),

        0x0212 => array("\x00\x00\x02\x13", 'Case map'),

        0x0214 => array("\x00\x00\x02\x15", 'Case map'),

        0x0216 => array("\x00\x00\x02\x17", 'Case map'),

        0x0218 => array("\x00\x00\x02\x19", 'Case map'),

        0x021A => array("\x00\x00\x02\x1B", 'Case map'),

        0x021C => array("\x00\x00\x02\x1D", 'Case map'),

        0x021E => array("\x00\x00\x02\x1F", 'Case map'),

        0x0222 => array("\x00\x00\x02\x23", 'Case map'),

        0x0224 => array("\x00\x00\x02\x25", 'Case map'),

        0x0226 => array("\x00\x00\x02\x27", 'Case map'),

        0x0228 => array("\x00\x00\x02\x29", 'Case map'),

        0x022A => array("\x00\x00\x02\x2B", 'Case map'),

        0x022C => array("\x00\x00\x02\x2D", 'Case map'),

        0x022E => array("\x00\x00\x02\x2F", 'Case map'),

        0x0230 => array("\x00\x00\x02\x31", 'Case map'),

        0x0232 => array("\x00\x00\x02\x33", 'Case map'),

        0x0345 => array("\x00\x00\x03\xB9", 'Case map'),

        0x037A => array("\x00\x00\x00\x20\x00\x00\x03\xB9", 'B9'),

        0x0386 => array("\x00\x00\x03\xAC", 'Case map'),

        0x0388 => array("\x00\x00\x03\xAD", 'Case map'),

        0x0389 => array("\x00\x00\x03\xAE", 'Case map'),

        0x038A => array("\x00\x00\x03\xAF", 'Case map'),

        0x038C => array("\x00\x00\x03\xCC", 'Case map'),

        0x038E => array("\x00\x00\x03\xCD", 'Case map'),

        0x038F => array("\x00\x00\x03\xCE", 'Case map'),

        0x0390 => array("\x00\x00\x03\xB9\x00\x00\x03\x08\x00\x00\x03\x01", 'Case map'),

        0x0391 => array("\x00\x00\x03\xB1", 'Case map'),

        0x0392 => array("\x00\x00\x03\xB2", 'Case map'),

        0x0393 => array("\x00\x00\x03\xB3", 'Case map'),

        0x0394 => array("\x00\x00\x03\xB4", 'Case map'),

        0x0395 => array("\x00\x00\x03\xB5", 'Case map'),

        0x0396 => array("\x00\x00\x03\xB6", 'Case map'),

        0x0397 => array("\x00\x00\x03\xB7", 'Case map'),

        0x0398 => array("\x00\x00\x03\xB8", 'Case map'),

        0x0399 => array("\x00\x00\x03\xB9", 'Case map'),

        0x039A => array("\x00\x00\x03\xBA", 'Case map'),

        0x039B => array("\x00\x00\x03\xBB", 'Case map'),

        0x039C => array("\x00\x00\x03\xBC", 'Case map'),

        0x039D => array("\x00\x00\x03\xBD", 'Case map'),

        0x039E => array("\x00\x00\x03\xBE", 'Case map'),

        0x039F => array("\x00\x00\x03\xBF", 'Case map'),

        0x03A0 => array("\x00\x00\x03\xC0", 'Case map'),

        0x03A1 => array("\x00\x00\x03\xC1", 'Case map'),

        0x03A3 => array("\x00\x00\x03\xC2", 'Case map'),

        0x03A4 => array("\x00\x00\x03\xC4", 'Case map'),

        0x03A5 => array("\x00\x00\x03\xC5", 'Case map'),

        0x03A6 => array("\x00\x00\x03\xC6", 'Case map'),

        0x03A7 => array("\x00\x00\x03\xC7", 'Case map'),

        0x03A8 => array("\x00\x00\x03\xC8", 'Case map'),

        0x03A9 => array("\x00\x00\x03\xC9", 'Case map'),

        0x03AA => array("\x00\x00\x03\xCA", 'Case map'),

        0x03AB => array("\x00\x00\x03\xCB", 'Case map'),

        0x03B0 => array("\x00\x00\x03\xC5\x00\x00\x03\x08\x00\x00\x03\x01", 'Case map'),

        0x03C2 => array("\x00\x00\x03\xC2", 'Case map'),

        0x03C3 => array("\x00\x00\x03\xC2", 'Case map'),

        0x03D0 => array("\x00\x00\x03\xB2", 'Case map'),

        0x03D1 => array("\x00\x00\x03\xB8", 'Case map'),

        0x03D2 => array("\x00\x00\x03\xC5", 'Additional folding'),

        0x03D3 => array("\x00\x00\x03\xCD", 'Additional folding'),

        0x03D4 => array("\x00\x00\x03\xCB", 'Additional folding'),

        0x03D5 => array("\x00\x00\x03\xC6", 'Case map'),

        0x03D6 => array("\x00\x00\x03\xC0", 'Case map'),

        0x03DA => array("\x00\x00\x03\xDB", 'Case map'),

        0x03DC => array("\x00\x00\x03\xDD", 'Case map'),

        0x03DE => array("\x00\x00\x03\xDF", 'Case map'),

        0x03E0 => array("\x00\x00\x03\xE1", 'Case map'),

        0x03E2 => array("\x00\x00\x03\xE3", 'Case map'),

        0x03E4 => array("\x00\x00\x03\xE5", 'Case map'),

        0x03E6 => array("\x00\x00\x03\xE7", 'Case map'),

        0x03E8 => array("\x00\x00\x03\xE9", 'Case map'),

        0x03EA => array("\x00\x00\x03\xEB", 'Case map'),

        0x03EC => array("\x00\x00\x03\xED", 'Case map'),

        0x03EE => array("\x00\x00\x03\xEF", 'Case map'),

        0x03F0 => array("\x00\x00\x03\xBA", 'Case map'),

        0x03F1 => array("\x00\x00\x03\xC1", 'Case map'),

        0x03F2 => array("\x00\x00\x03\xC2", 'Case map'),

        0x0400 => array("\x00\x00\x04\x50", 'Case map'),

        0x0401 => array("\x00\x00\x04\x51", 'Case map'),

        0x0402 => array("\x00\x00\x04\x52", 'Case map'),

        0x0403 => array("\x00\x00\x04\x53", 'Case map'),

        0x0404 => array("\x00\x00\x04\x54", 'Case map'),

        0x0405 => array("\x00\x00\x04\x55", 'Case map'),

        0x0406 => array("\x00\x00\x04\x56", 'Case map'),

        0x0407 => array("\x00\x00\x04\x57", 'Case map'),

        0x0408 => array("\x00\x00\x04\x58", 'Case map'),

        0x0409 => array("\x00\x00\x04\x59", 'Case map'),

        0x040A => array("\x00\x00\x04\x5A", 'Case map'),

        0x040B => array("\x00\x00\x04\x5B", 'Case map'),

        0x040C => array("\x00\x00\x04\x5C", 'Case map'),

        0x040D => array("\x00\x00\x04\x5D", 'Case map'),

        0x040E => array("\x00\x00\x04\x5E", 'Case map'),

        0x040F => array("\x00\x00\x04\x5F", 'Case map'),

        0x0410 => array("\x00\x00\x04\x30", 'Case map'),

        0x0411 => array("\x00\x00\x04\x31", 'Case map'),

        0x0412 => array("\x00\x00\x04\x32", 'Case map'),

        0x0413 => array("\x00\x00\x04\x33", 'Case map'),

        0x0414 => array("\x00\x00\x04\x34", 'Case map'),

        0x0415 => array("\x00\x00\x04\x35", 'Case map'),

        0x0416 => array("\x00\x00\x04\x36", 'Case map'),

        0x0417 => array("\x00\x00\x04\x37", 'Case map'),

        0x0418 => array("\x00\x00\x04\x38", 'Case map'),

        0x0419 => array("\x00\x00\x04\x39", 'Case map'),

        0x041A => array("\x00\x00\x04\x3A", 'Case map'),

        0x041B => array("\x00\x00\x04\x3B", 'Case map'),

        0x041C => array("\x00\x00\x04\x3C", 'Case map'),

        0x041D => array("\x00\x00\x04\x3D", 'Case map'),

        0x041E => array("\x00\x00\x04\x3E", 'Case map'),

        0x041F => array("\x00\x00\x04\x3F", 'Case map'),

        0x0420 => array("\x00\x00\x04\x40", 'Case map'),

        0x0421 => array("\x00\x00\x04\x41", 'Case map'),

        0x0422 => array("\x00\x00\x04\x42", 'Case map'),

        0x0423 => array("\x00\x00\x04\x43", 'Case map'),

        0x0424 => array("\x00\x00\x04\x44", 'Case map'),

        0x0425 => array("\x00\x00\x04\x45", 'Case map'),

        0x0426 => array("\x00\x00\x04\x46", 'Case map'),

        0x0427 => array("\x00\x00\x04\x47", 'Case map'),

        0x0428 => array("\x00\x00\x04\x48", 'Case map'),

        0x0429 => array("\x00\x00\x04\x49", 'Case map'),

        0x042A => array("\x00\x00\x04\x4A", 'Case map'),

        0x042B => array("\x00\x00\x04\x4B", 'Case map'),

        0x042C => array("\x00\x00\x04\x4C", 'Case map'),

        0x042D => array("\x00\x00\x04\x4D", 'Case map'),

        0x042E => array("\x00\x00\x04\x4E", 'Case map'),

        0x042F => array("\x00\x00\x04\x4F", 'Case map'),

        0x0460 => array("\x00\x00\x04\x61", 'Case map'),

        0x0462 => array("\x00\x00\x04\x63", 'Case map'),

        0x0464 => array("\x00\x00\x04\x65", 'Case map'),

        0x0466 => array("\x00\x00\x04\x67", 'Case map'),

        0x0468 => array("\x00\x00\x04\x69", 'Case map'),

        0x046A => array("\x00\x00\x04\x6B", 'Case map'),

        0x046C => array("\x00\x00\x04\x6D", 'Case map'),

        0x046E => array("\x00\x00\x04\x6F", 'Case map'),

        0x0470 => array("\x00\x00\x04\x71", 'Case map'),

        0x0472 => array("\x00\x00\x04\x73", 'Case map'),

        0x0474 => array("\x00\x00\x04\x75", 'Case map'),

        0x0476 => array("\x00\x00\x04\x77", 'Case map'),

        0x0478 => array("\x00\x00\x04\x79", 'Case map'),

        0x047A => array("\x00\x00\x04\x7B", 'Case map'),

        0x047C => array("\x00\x00\x04\x7D", 'Case map'),

        0x047E => array("\x00\x00\x04\x7F", 'Case map'),

        0x0480 => array("\x00\x00\x04\x81", 'Case map'),

        0x048C => array("\x00\x00\x04\x8D", 'Case map'),

        0x048E => array("\x00\x00\x04\x8F", 'Case map'),

        0x0490 => array("\x00\x00\x04\x91", 'Case map'),

        0x0492 => array("\x00\x00\x04\x93", 'Case map'),

        0x0494 => array("\x00\x00\x04\x95", 'Case map'),

        0x0496 => array("\x00\x00\x04\x97", 'Case map'),

        0x0498 => array("\x00\x00\x04\x99", 'Case map'),

        0x049A => array("\x00\x00\x04\x9B", 'Case map'),

        0x049C => array("\x00\x00\x04\x9D", 'Case map'),

        0x049E => array("\x00\x00\x04\x9F", 'Case map'),

        0x04A0 => array("\x00\x00\x04\xA1", 'Case map'),

        0x04A2 => array("\x00\x00\x04\xA3", 'Case map'),

        0x04A4 => array("\x00\x00\x04\xA5", 'Case map'),

        0x04A6 => array("\x00\x00\x04\xA7", 'Case map'),

        0x04A8 => array("\x00\x00\x04\xA9", 'Case map'),

        0x04AA => array("\x00\x00\x04\xAB", 'Case map'),

        0x04AC => array("\x00\x00\x04\xAD", 'Case map'),

        0x04AE => array("\x00\x00\x04\xAF", 'Case map'),

        0x04B0 => array("\x00\x00\x04\xB1", 'Case map'),

        0x04B2 => array("\x00\x00\x04\xB3", 'Case map'),

        0x04B4 => array("\x00\x00\x04\xB5", 'Case map'),

        0x04B6 => array("\x00\x00\x04\xB7", 'Case map'),

        0x04B8 => array("\x00\x00\x04\xB9", 'Case map'),

        0x04BA => array("\x00\x00\x04\xBB", 'Case map'),

        0x04BC => array("\x00\x00\x04\xBD", 'Case map'),

        0x04BE => array("\x00\x00\x04\xBF", 'Case map'),

        0x04C1 => array("\x00\x00\x04\xC2", 'Case map'),

        0x04C3 => array("\x00\x00\x04\xC4", 'Case map'),

        0x04C7 => array("\x00\x00\x04\xC8", 'Case map'),

        0x04CB => array("\x00\x00\x04\xCC", 'Case map'),

        0x04D0 => array("\x00\x00\x04\xD1", 'Case map'),

        0x04D2 => array("\x00\x00\x04\xD3", 'Case map'),

        0x04D4 => array("\x00\x00\x04\xD5", 'Case map'),

        0x04D6 => array("\x00\x00\x04\xD7", 'Case map'),

        0x04D8 => array("\x00\x00\x04\xD9", 'Case map'),

        0x04DA => array("\x00\x00\x04\xDB", 'Case map'),

        0x04DC => array("\x00\x00\x04\xDD", 'Case map'),

        0x04DE => array("\x00\x00\x04\xDF", 'Case map'),

        0x04E0 => array("\x00\x00\x04\xE1", 'Case map'),

        0x04E2 => array("\x00\x00\x04\xE3", 'Case map'),

        0x04E4 => array("\x00\x00\x04\xE5", 'Case map'),

        0x04E6 => array("\x00\x00\x04\xE7", 'Case map'),

        0x04E8 => array("\x00\x00\x04\xE9", 'Case map'),

        0x04EA => array("\x00\x00\x04\xEB", 'Case map'),

        0x04EC => array("\x00\x00\x04\xED", 'Case map'),

        0x04EE => array("\x00\x00\x04\xEF", 'Case map'),

        0x04F0 => array("\x00\x00\x04\xF1", 'Case map'),

        0x04F2 => array("\x00\x00\x04\xF3", 'Case map'),

        0x04F4 => array("\x00\x00\x04\xF5", 'Case map'),

        0x04F8 => array("\x00\x00\x04\xF9", 'Case map'),

        0x0531 => array("\x00\x00\x05\x61", 'Case map'),

        0x0532 => array("\x00\x00\x05\x62", 'Case map'),

        0x0533 => array("\x00\x00\x05\x63", 'Case map'),

        0x0534 => array("\x00\x00\x05\x64", 'Case map'),

        0x0535 => array("\x00\x00\x05\x65", 'Case map'),

        0x0536 => array("\x00\x00\x05\x66", 'Case map'),

        0x0537 => array("\x00\x00\x05\x67", 'Case map'),

        0x0538 => array("\x00\x00\x05\x68", 'Case map'),

        0x0539 => array("\x00\x00\x05\x69", 'Case map'),

        0x053A => array("\x00\x00\x05\x6A", 'Case map'),

        0x053B => array("\x00\x00\x05\x6B", 'Case map'),

        0x053C => array("\x00\x00\x05\x6C", 'Case map'),

        0x053D => array("\x00\x00\x05\x6D", 'Case map'),

        0x053E => array("\x00\x00\x05\x6E", 'Case map'),

        0x053F => array("\x00\x00\x05\x6F", 'Case map'),

        0x0540 => array("\x00\x00\x05\x70", 'Case map'),

        0x0541 => array("\x00\x00\x05\x71", 'Case map'),

        0x0542 => array("\x00\x00\x05\x72", 'Case map'),

        0x0543 => array("\x00\x00\x05\x73", 'Case map'),

        0x0544 => array("\x00\x00\x05\x74", 'Case map'),

        0x0545 => array("\x00\x00\x05\x75", 'Case map'),

        0x0546 => array("\x00\x00\x05\x76", 'Case map'),

        0x0547 => array("\x00\x00\x05\x77", 'Case map'),

        0x0548 => array("\x00\x00\x05\x78", 'Case map'),

        0x0549 => array("\x00\x00\x05\x79", 'Case map'),

        0x054A => array("\x00\x00\x05\x7A", 'Case map'),

        0x054B => array("\x00\x00\x05\x7B", 'Case map'),

        0x054C => array("\x00\x00\x05\x7C", 'Case map'),

        0x054D => array("\x00\x00\x05\x7D", 'Case map'),

        0x054E => array("\x00\x00\x05\x7E", 'Case map'),

        0x054F => array("\x00\x00\x05\x7F", 'Case map'),

        0x0550 => array("\x00\x00\x05\x80", 'Case map'),

        0x0551 => array("\x00\x00\x05\x81", 'Case map'),

        0x0552 => array("\x00\x00\x05\x82", 'Case map'),

        0x0553 => array("\x00\x00\x05\x83", 'Case map'),

        0x0554 => array("\x00\x00\x05\x84", 'Case map'),

        0x0555 => array("\x00\x00\x05\x85", 'Case map'),

        0x0556 => array("\x00\x00\x05\x86", 'Case map'),

        0x0587 => array("\x00\x00\x05\x65\x00\x00\x05\x82", '82'),

       0x1806 => array('', 'Map out'),

        0x180B => array('', 'Map out'),

        0x180C => array('', 'Map out'),

        0x180D => array('', 'Map out'),

        0x1E00 => array("\x00\x00\x1E\x01", 'Case map'),

        0x1E02 => array("\x00\x00\x1E\x03", 'Case map'),

        0x1E04 => array("\x00\x00\x1E\x05", 'Case map'),

        0x1E06 => array("\x00\x00\x1E\x07", 'Case map'),

        0x1E08 => array("\x00\x00\x1E\x09", 'Case map'),

        0x1E0A => array("\x00\x00\x1E\x0B", 'Case map'),

        0x1E0C => array("\x00\x00\x1E\x0D", 'Case map'),

        0x1E0E => array("\x00\x00\x1E\x0F", 'Case map'),

        0x1E10 => array("\x00\x00\x1E\x11", 'Case map'),

        0x1E12 => array("\x00\x00\x1E\x13", 'Case map'),

        0x1E14 => array("\x00\x00\x1E\x15", 'Case map'),

        0x1E16 => array("\x00\x00\x1E\x17", 'Case map'),

        0x1E18 => array("\x00\x00\x1E\x19", 'Case map'),

        0x1E1A => array("\x00\x00\x1E\x1B", 'Case map'),

        0x1E1C => array("\x00\x00\x1E\x1D", 'Case map'),

        0x1E1E => array("\x00\x00\x1E\x1F", 'Case map'),

        0x1E20 => array("\x00\x00\x1E\x21", 'Case map'),

        0x1E22 => array("\x00\x00\x1E\x23", 'Case map'),

        0x1E24 => array("\x00\x00\x1E\x25", 'Case map'),

        0x1E26 => array("\x00\x00\x1E\x27", 'Case map'),

        0x1E28 => array("\x00\x00\x1E\x29", 'Case map'),

        0x1E2A => array("\x00\x00\x1E\x2B", 'Case map'),

        0x1E2C => array("\x00\x00\x1E\x2D", 'Case map'),

        0x1E2E => array("\x00\x00\x1E\x2F", 'Case map'),

        0x1E30 => array("\x00\x00\x1E\x31", 'Case map'),

        0x1E32 => array("\x00\x00\x1E\x33", 'Case map'),

        0x1E34 => array("\x00\x00\x1E\x35", 'Case map'),

        0x1E36 => array("\x00\x00\x1E\x37", 'Case map'),

        0x1E38 => array("\x00\x00\x1E\x39", 'Case map'),

        0x1E3A => array("\x00\x00\x1E\x3B", 'Case map'),

        0x1E3C => array("\x00\x00\x1E\x3D", 'Case map'),

        0x1E3E => array("\x00\x00\x1E\x3F", 'Case map'),

        0x1E40 => array("\x00\x00\x1E\x41", 'Case map'),

        0x1E42 => array("\x00\x00\x1E\x43", 'Case map'),

        0x1E44 => array("\x00\x00\x1E\x45", 'Case map'),

        0x1E46 => array("\x00\x00\x1E\x47", 'Case map'),

        0x1E48 => array("\x00\x00\x1E\x49", 'Case map'),

        0x1E4A => array("\x00\x00\x1E\x4B", 'Case map'),

        0x1E4C => array("\x00\x00\x1E\x4D", 'Case map'),

        0x1E4E => array("\x00\x00\x1E\x4F", 'Case map'),

        0x1E50 => array("\x00\x00\x1E\x51", 'Case map'),

        0x1E52 => array("\x00\x00\x1E\x53", 'Case map'),

        0x1E54 => array("\x00\x00\x1E\x55", 'Case map'),

        0x1E56 => array("\x00\x00\x1E\x57", 'Case map'),

        0x1E58 => array("\x00\x00\x1E\x59", 'Case map'),

        0x1E5A => array("\x00\x00\x1E\x5B", 'Case map'),

        0x1E5C => array("\x00\x00\x1E\x5D", 'Case map'),

        0x1E5E => array("\x00\x00\x1E\x5F", 'Case map'),

        0x1E60 => array("\x00\x00\x1E\x61", 'Case map'),

        0x1E62 => array("\x00\x00\x1E\x63", 'Case map'),

        0x1E64 => array("\x00\x00\x1E\x65", 'Case map'),

        0x1E66 => array("\x00\x00\x1E\x67", 'Case map'),

        0x1E68 => array("\x00\x00\x1E\x69", 'Case map'),

        0x1E6A => array("\x00\x00\x1E\x6B", 'Case map'),

        0x1E6C => array("\x00\x00\x1E\x6D", 'Case map'),

        0x1E6E => array("\x00\x00\x1E\x6F", 'Case map'),

        0x1E70 => array("\x00\x00\x1E\x71", 'Case map'),

        0x1E72 => array("\x00\x00\x1E\x73", 'Case map'),

        0x1E74 => array("\x00\x00\x1E\x75", 'Case map'),

        0x1E76 => array("\x00\x00\x1E\x77", 'Case map'),

        0x1E78 => array("\x00\x00\x1E\x79", 'Case map'),

        0x1E7A => array("\x00\x00\x1E\x7B", 'Case map'),

        0x1E7C => array("\x00\x00\x1E\x7D", 'Case map'),

        0x1E7E => array("\x00\x00\x1E\x7F", 'Case map'),

        0x1E80 => array("\x00\x00\x1E\x81", 'Case map'),

        0x1E82 => array("\x00\x00\x1E\x83", 'Case map'),

        0x1E84 => array("\x00\x00\x1E\x85", 'Case map'),

        0x1E86 => array("\x00\x00\x1E\x87", 'Case map'),

        0x1E88 => array("\x00\x00\x1E\x89", 'Case map'),

        0x1E8A => array("\x00\x00\x1E\x8B", 'Case map'),

        0x1E8C => array("\x00\x00\x1E\x8D", 'Case map'),

        0x1E8E => array("\x00\x00\x1E\x8F", 'Case map'),

        0x1E90 => array("\x00\x00\x1E\x91", 'Case map'),

        0x1E92 => array("\x00\x00\x1E\x93", 'Case map'),

        0x1E94 => array("\x00\x00\x1E\x95", 'Case map'),

        0x1E96 => array("\x00\x00\x00\x68\x00\x00\x03\x31", '31'),

        0x1E97 => array("\x00\x00\x00\x74\x00\x00\x03\x08", '08'),

        0x1E98 => array("\x00\x00\x00\x77\x00\x00\x03\x0A", '0A'),

        0x1E99 => array("\x00\x00\x00\x79\x00\x00\x03\x0A", '0A'),

        0x1E9A => array("\x00\x00\x00\x61\x00\x00\x02\xBE", 'BE'),	

 0x1E9B => array("\x00\x00\x1E\x61", 'Case map'),

        0x1EA0 => array("\x00\x00\x1E\xA1", 'Case map'),

        0x1EA2 => array("\x00\x00\x1E\xA3", 'Case map'),

        0x1EA4 => array("\x00\x00\x1E\xA5", 'Case map'),

        0x1EA6 => array("\x00\x00\x1E\xA7", 'Case map'),

        0x1EA8 => array("\x00\x00\x1E\xA9", 'Case map'),

        0x1EAA => array("\x00\x00\x1E\xAB", 'Case map'),

        0x1EAC => array("\x00\x00\x1E\xAD", 'Case map'),

        0x1EAE => array("\x00\x00\x1E\xAF", 'Case map'),

        0x1EB0 => array("\x00\x00\x1E\xB1", 'Case map'),

        0x1EB2 => array("\x00\x00\x1E\xB3", 'Case map'),

        0x1EB4 => array("\x00\x00\x1E\xB5", 'Case map'),

        0x1EB6 => array("\x00\x00\x1E\xB7", 'Case map'),

        0x1EB8 => array("\x00\x00\x1E\xB9", 'Case map'),

        0x1EBA => array("\x00\x00\x1E\xBB", 'Case map'),

        0x1EBC => array("\x00\x00\x1E\xBD", 'Case map'),

        0x1EBE => array("\x00\x00\x1E\xBF", 'Case map'),

        0x1EC0 => array("\x00\x00\x1E\xC1", 'Case map'),

        0x1EC2 => array("\x00\x00\x1E\xC3", 'Case map'),

        0x1EC4 => array("\x00\x00\x1E\xC5", 'Case map'),

        0x1EC6 => array("\x00\x00\x1E\xC7", 'Case map'),

        0x1EC8 => array("\x00\x00\x1E\xC9", 'Case map'),

        0x1ECA => array("\x00\x00\x1E\xCB", 'Case map'),

        0x1ECC => array("\x00\x00\x1E\xCD", 'Case map'),

        0x1ECE => array("\x00\x00\x1E\xCF", 'Case map'),

        0x1ED0 => array("\x00\x00\x1E\xD1", 'Case map'),

        0x1ED2 => array("\x00\x00\x1E\xD3", 'Case map'),

        0x1ED4 => array("\x00\x00\x1E\xD5", 'Case map'),

        0x1ED6 => array("\x00\x00\x1E\xD7", 'Case map'),

        0x1ED8 => array("\x00\x00\x1E\xD9", 'Case map'),

        0x1EDA => array("\x00\x00\x1E\xDB", 'Case map'),

        0x1EDC => array("\x00\x00\x1E\xDD", 'Case map'),

        0x1EDE => array("\x00\x00\x1E\xDF", 'Case map'),

        0x1EE0 => array("\x00\x00\x1E\xE1", 'Case map'),

        0x1EE2 => array("\x00\x00\x1E\xE3", 'Case map'),

        0x1EE4 => array("\x00\x00\x1E\xE5", 'Case map'),

        0x1EE6 => array("\x00\x00\x1E\xE7", 'Case map'),

        0x1EE8 => array("\x00\x00\x1E\xE9", 'Case map'),

        0x1EEA => array("\x00\x00\x1E\xEB", 'Case map'),

        0x1EEC => array("\x00\x00\x1E\xED", 'Case map'),

        0x1EEE => array("\x00\x00\x1E\xEF", 'Case map'),

        0x1EF0 => array("\x00\x00\x1E\xF1", 'Case map'),

        0x1EF2 => array("\x00\x00\x1E\xF3", 'Case map'),

        0x1EF4 => array("\x00\x00\x1E\xF5", 'Case map'),

        0x1EF6 => array("\x00\x00\x1E\xF7", 'Case map'),

        0x1EF8 => array("\x00\x00\x1E\xF9", 'Case map'),

        0x1F08 => array("\x00\x00\x1F\x00", 'Case map'),

        0x1F09 => array("\x00\x00\x1F\x01", 'Case map'),

        0x1F0A => array("\x00\x00\x1F\x02", 'Case map'),

        0x1F0B => array("\x00\x00\x1F\x03", 'Case map'),

        0x1F0C => array("\x00\x00\x1F\x04", 'Case map'),

        0x1F0D => array("\x00\x00\x1F\x05", 'Case map'),

        0x1F0E => array("\x00\x00\x1F\x06", 'Case map'),

        0x1F0F => array("\x00\x00\x1F\x07", 'Case map'),

        0x1F18 => array("\x00\x00\x1F\x10", 'Case map'),

        0x1F19 => array("\x00\x00\x1F\x11", 'Case map'),

        0x1F1A => array("\x00\x00\x1F\x12", 'Case map'),

        0x1F1B => array("\x00\x00\x1F\x13", 'Case map'),

        0x1F1C => array("\x00\x00\x1F\x14", 'Case map'),

        0x1F1D => array("\x00\x00\x1F\x15", 'Case map'),

        0x1F28 => array("\x00\x00\x1F\x20", 'Case map'),

        0x1F29 => array("\x00\x00\x1F\x21", 'Case map'),

        0x1F2A => array("\x00\x00\x1F\x22", 'Case map'),

        0x1F2B => array("\x00\x00\x1F\x23", 'Case map'),

        0x1F2C => array("\x00\x00\x1F\x24", 'Case map'),

        0x1F2D => array("\x00\x00\x1F\x25", 'Case map'),

        0x1F2E => array("\x00\x00\x1F\x26", 'Case map'),

        0x1F2F => array("\x00\x00\x1F\x27", 'Case map'),

        0x1F38 => array("\x00\x00\x1F\x30", 'Case map'),

        0x1F39 => array("\x00\x00\x1F\x31", 'Case map'),

        0x1F3A => array("\x00\x00\x1F\x32", 'Case map'),

        0x1F3B => array("\x00\x00\x1F\x33", 'Case map'),

        0x1F3C => array("\x00\x00\x1F\x34", 'Case map'),

        0x1F3D => array("\x00\x00\x1F\x35", 'Case map'),

        0x1F3E => array("\x00\x00\x1F\x36", 'Case map'),

        0x1F3F => array("\x00\x00\x1F\x37", 'Case map'),

        0x1F48 => array("\x00\x00\x1F\x40", 'Case map'),

        0x1F49 => array("\x00\x00\x1F\x41", 'Case map'),

        0x1F4A => array("\x00\x00\x1F\x42", 'Case map'),

        0x1F4B => array("\x00\x00\x1F\x43", 'Case map'),

        0x1F4C => array("\x00\x00\x1F\x44", 'Case map'),

        0x1F4D => array("\x00\x00\x1F\x45", 'Case map'),

        0x1F50 => array("\x00\x00\x03\xC5\x00\x00\x03\x13", '13'),

        0x1F52 => array("\x00\x00\x03\xC5\x00\x00\x03\x13\x00\x00\x03\x00", 'Case map'),

        0x1F54 => array("\x00\x00\x03\xC5\x00\x00\x03\x13\x00\x00\x03\x01", 'Case map'),

        0x1F56 => array("\x00\x00\x03\xC5\x00\x00\x03\x13\x00\x00\x03\x42", 'Case map'),

        0x1F59 => array("\x00\x00\x1F\x51", 'Case map'),

        0x1F5B => array("\x00\x00\x1F\x53", 'Case map'),

        0x1F5D => array("\x00\x00\x1F\x55", 'Case map'),

        0x1F5F => array("\x00\x00\x1F\x57", 'Case map'),

        0x1F68 => array("\x00\x00\x1F\x60", 'Case map'),

        0x1F69 => array("\x00\x00\x1F\x61", 'Case map'),

        0x1F6A => array("\x00\x00\x1F\x62", 'Case map'),

        0x1F6B => array("\x00\x00\x1F\x63", 'Case map'),

        0x1F6C => array("\x00\x00\x1F\x64", 'Case map'),

        0x1F6D => array("\x00\x00\x1F\x65", 'Case map'),

        0x1F6E => array("\x00\x00\x1F\x66", 'Case map'),

        0x1F6F => array("\x00\x00\x1F\x67", 'Case map'),

        0x1F80 => array("\x00\x00\x1F\x00\x00\x00\x03\xB9", 'B9'),

        0x1F81 => array("\x00\x00\x1F\x01\x00\x00\x03\xB9", 'B9'),

        0x1F82 => array("\x00\x00\x1F\x02\x00\x00\x03\xB9", 'B9'),

        0x1F83 => array("\x00\x00\x1F\x03\x00\x00\x03\xB9", 'B9'),

        0x1F84 => array("\x00\x00\x1F\x04\x00\x00\x03\xB9", 'B9'),

        0x1F85 => array("\x00\x00\x1F\x05\x00\x00\x03\xB9", 'B9'),

        0x1F86 => array("\x00\x00\x1F\x06\x00\x00\x03\xB9", 'B9'),

        0x1F87 => array("\x00\x00\x1F\x07\x00\x00\x03\xB9", 'B9'),

        0x1F88 => array("\x00\x00\x1F\x00\x00\x00\x03\xB9", 'B9'),

        0x1F89 => array("\x00\x00\x1F\x01\x00\x00\x03\xB9", 'B9'),

        0x1F8A => array("\x00\x00\x1F\x02\x00\x00\x03\xB9", 'B9'),

        0x1F8B => array("\x00\x00\x1F\x03\x00\x00\x03\xB9", 'B9'),

        0x1F8C => array("\x00\x00\x1F\x04\x00\x00\x03\xB9", 'B9'),

        0x1F8D => array("\x00\x00\x1F\x05\x00\x00\x03\xB9", 'B9'),

        0x1F8E => array("\x00\x00\x1F\x06\x00\x00\x03\xB9", 'B9'),

        0x1F8F => array("\x00\x00\x1F\x07\x00\x00\x03\xB9", 'B9'),

        0x1F90 => array("\x00\x00\x1F\x20\x00\x00\x03\xB9", 'B9'),

        0x1F91 => array("\x00\x00\x1F\x21\x00\x00\x03\xB9", 'B9'),

        0x1F92 => array("\x00\x00\x1F\x22\x00\x00\x03\xB9", 'B9'),

        0x1F93 => array("\x00\x00\x1F\x23\x00\x00\x03\xB9", 'B9'),

        0x1F94 => array("\x00\x00\x1F\x24\x00\x00\x03\xB9", 'B9'),

        0x1F95 => array("\x00\x00\x1F\x25\x00\x00\x03\xB9", 'B9'),

        0x1F96 => array("\x00\x00\x1F\x26\x00\x00\x03\xB9", 'B9'),

        0x1F97 => array("\x00\x00\x1F\x27\x00\x00\x03\xB9", 'B9'),

        0x1F98 => array("\x00\x00\x1F\x20\x00\x00\x03\xB9", 'B9'),

        0x1F99 => array("\x00\x00\x1F\x21\x00\x00\x03\xB9", 'B9'),

        0x1F9A => array("\x00\x00\x1F\x22\x00\x00\x03\xB9", 'B9'),

        0x1F9B => array("\x00\x00\x1F\x23\x00\x00\x03\xB9", 'B9'),

        0x1F9C => array("\x00\x00\x1F\x24\x00\x00\x03\xB9", 'B9'),

        0x1F9D => array("\x00\x00\x1F\x25\x00\x00\x03\xB9", 'B9'),

        0x1F9E => array("\x00\x00\x1F\x26\x00\x00\x03\xB9", 'B9'),

        0x1F9F => array("\x00\x00\x1F\x27\x00\x00\x03\xB9", 'B9'),

        0x1FA0 => array("\x00\x00\x1F\x60\x00\x00\x03\xB9", 'B9'),

        0x1FA1 => array("\x00\x00\x1F\x61\x00\x00\x03\xB9", 'B9'),

        0x1FA2 => array("\x00\x00\x1F\x62\x00\x00\x03\xB9", 'B9'),

        0x1FA3 => array("\x00\x00\x1F\x63\x00\x00\x03\xB9", 'B9'),

        0x1FA4 => array("\x00\x00\x1F\x64\x00\x00\x03\xB9", 'B9'),

        0x1FA5 => array("\x00\x00\x1F\x65\x00\x00\x03\xB9", 'B9'),

        0x1FA6 => array("\x00\x00\x1F\x66\x00\x00\x03\xB9", 'B9'),

        0x1FA7 => array("\x00\x00\x1F\x67\x00\x00\x03\xB9", 'B9'),

        0x1FA8 => array("\x00\x00\x1F\x60\x00\x00\x03\xB9", 'B9'),

        0x1FA9 => array("\x00\x00\x1F\x61\x00\x00\x03\xB9", 'B9'),

        0x1FAA => array("\x00\x00\x1F\x62\x00\x00\x03\xB9", 'B9'),

        0x1FAB => array("\x00\x00\x1F\x63\x00\x00\x03\xB9", 'B9'),

        0x1FAC => array("\x00\x00\x1F\x64\x00\x00\x03\xB9", 'B9'),

        0x1FAD => array("\x00\x00\x1F\x65\x00\x00\x03\xB9", 'B9'),

        0x1FAE => array("\x00\x00\x1F\x66\x00\x00\x03\xB9", 'B9'),

        0x1FAF => array("\x00\x00\x1F\x67\x00\x00\x03\xB9", 'B9'),

        0x1FB2 => array("\x00\x00\x1F\x70\x00\x00\x03\xB9", 'B9'),

        0x1FB3 => array("\x00\x00\x03\xB1\x00\x00\x03\xB9", 'B9'),

        0x1FB4 => array("\x00\x00\x03\xAC\x00\x00\x03\xB9", 'B9'),

        0x1FB6 => array("\x00\x00\x03\xB1\x00\x00\x03\x42", '42'),

        0x1FB7 => array("\x00\x00\x03\xB1\x00\x00\x03\x42\x00\x00\x03\xB9", 'Case map'),

        0x1FB8 => array("\x00\x00\x1F\xB0", 'Case map'),

        0x1FB9 => array("\x00\x00\x1F\xB1", 'Case map'),

        0x1FBA => array("\x00\x00\x1F\x70", 'Case map'),

        0x1FBB => array("\x00\x00\x1F\x71", 'Case map'),

        0x1FBC => array("\x00\x00\x03\xB1\x00\x00\x03\xB9", 'B9'),

        0x1FBE => array("\x00\x00\x03\xB9", 'Case map'),

        0x1FC2 => array("\x00\x00\x1F\x74\x00\x00\x03\xB9", 'B9'),

        0x1FC3 => array("\x00\x00\x03\xB7\x00\x00\x03\xB9", 'B9'),

        0x1FC4 => array("\x00\x00\x03\xAE\x00\x00\x03\xB9", 'B9'),

        0x1FC6 => array("\x00\x00\x03\xB7\x00\x00\x03\x42", '42'),

        0x1FC7 => array("\x00\x00\x03\xB7\x00\x00\x03\x42\x00\x00\x03\xB9", 'Case map'),

        0x1FC8 => array("\x00\x00\x1F\x72", 'Case map'),

        0x1FC9 => array("\x00\x00\x1F\x73", 'Case map'),

        0x1FCA => array("\x00\x00\x1F\x74", 'Case map'),

        0x1FCB => array("\x00\x00\x1F\x75", 'Case map'),

        0x1FCC => array("\x00\x00\x03\xB7\x00\x00\x03\xB9", 'B9'),

        0x1FD2 => array("\x00\x00\x03\xB9\x00\x00\x03\x08\x00\x00\x03\x00", 'Case map'),

        0x1FD3 => array("\x00\x00\x03\xB9\x00\x00\x03\x08\x00\x00\x03\x01", 'Case map'),

        0x1FD6 => array("\x00\x00\x03\xB9\x00\x00\x03\x42", '42'),

        0x1FD7 => array("\x00\x00\x03\xB9\x00\x00\x03\x08\x00\x00\x03\x42", 'Case map'),

        0x1FD8 => array("\x00\x00\x1F\xD0", 'Case map'),

        0x1FD9 => array("\x00\x00\x1F\xD1", 'Case map'),

        0x1FDA => array("\x00\x00\x1F\x76", 'Case map'),

        0x1FDB => array("\x00\x00\x1F\x77", 'Case map'),

        0x1FE2 => array("\x00\x00\x03\xC5\x00\x00\x03\x08\x00\x00\x03\x00", 'Case map'),

        0x1FE3 => array("\x00\x00\x03\xC5\x00\x00\x03\x08\x00\x00\x03\x01", 'Case map'),

        0x1FE4 => array("\x00\x00\x03\xC1\x00\x00\x03\x13", '13'),

        0x1FE6 => array("\x00\x00\x03\xC5\x00\x00\x03\x42", '42'),

        0x1FE7 => array("\x00\x00\x03\xC5\x00\x00\x03\x08\x00\x00\x03\x42", 'Case map'),

        0x1FE8 => array("\x00\x00\x1F\xE0", 'Case map'),

        0x1FE9 => array("\x00\x00\x1F\xE1", 'Case map'),

        0x1FEA => array("\x00\x00\x1F\x7A", 'Case map'),

        0x1FEB => array("\x00\x00\x1F\x7B", 'Case map'),

        0x1FEC => array("\x00\x00\x1F\xE5", 'Case map'),

        0x1FF2 => array("\x00\x00\x1F\x7C\x00\x00\x03\xB9", 'B9'),

        0x1FF3 => array("\x00\x00\x03\xC9\x00\x00\x03\xB9", 'B9'),

        0x1FF4 => array("\x00\x00\x03\xCE\x00\x00\x03\xB9", 'B9'),

        0x1FF6 => array("\x00\x00\x03\xC9\x00\x00\x03\x42", '42'),

        0x1FF7 => array("\x00\x00\x03\xC9\x00\x00\x03\x42\x00\x00\x03\xB9", 'Case map'),

        0x1FF8 => array("\x00\x00\x1F\x78", 'Case map'),

        0x1FF9 => array("\x00\x00\x1F\x79", 'Case map'),

        0x1FFA => array("\x00\x00\x1F\x7C", 'Case map'),

        0x1FFB => array("\x00\x00\x1F\x7D", 'Case map'),

        0x1FFC => array("\x00\x00\x03\xC9\x00\x00\x03\xB9", 'B9'),

        0x200B => array('', 'Map out'),

        0x200C => array('', 'Map out'),

        0x200D => array('', 'Map out'),

        0x20A8 => array("\x00\x00\x00\x72\x00\x00\x00\x73", '73'),

        0x2102 => array("\x00\x00\x00\x63", 'Additional folding'),

        0x2103 => array("\x00\x00\x00\xB0\x00\x00\x00\x63", '63'),

        0x2107 => array("\x00\x00\x02\x5B", 'Additional folding'),

        0x2109 => array("\x00\x00\x00\xB0\x00\x00\x00\x66", '66'),

        0x210B => array("\x00\x00\x00\x68", 'Additional folding'),

        0x210C => array("\x00\x00\x00\x68", 'Additional folding'),

        0x210D => array("\x00\x00\x00\x68", 'Additional folding'),

        0x2110 => array("\x00\x00\x00\x69", 'Additional folding'),

        0x2111 => array("\x00\x00\x00\x69", 'Additional folding'),

        0x2112 => array("\x00\x00\x00\x6C", 'Additional folding'),

        0x2115 => array("\x00\x00\x00\x6E", 'Additional folding'),

        0x2116 => array("\x00\x00\x00\x6E\x00\x00\x00\x6F", '6F'),

        0x2119 => array("\x00\x00\x00\x70", 'Additional folding'),

        0x211A => array("\x00\x00\x00\x71", 'Additional folding'),

        0x211B => array("\x00\x00\x00\x72", 'Additional folding'),

        0x211C => array("\x00\x00\x00\x72", 'Additional folding'),

        0x211D => array("\x00\x00\x00\x72", 'Additional folding'),

        0x2120 => array("\x00\x00\x00\x73\x00\x00\x00\x6D", '6D'),

        0x2121 => array("\x00\x00\x00\x74\x00\x00\x00\x65\x00\x00\x00\x6C", 'Additional folding'),

        0x2122 => array("\x00\x00\x00\x74\x00\x00\x00\x6D", '6D'),

        0x2124 => array("\x00\x00\x00\x7A", 'Additional folding'),

        0x2126 => array("\x00\x00\x03\xC9", 'Case map'),

        0x2128 => array("\x00\x00\x00\x7A", 'Additional folding'),

        0x212A => array("\x00\x00\x00\x6B", 'Case map'),

        0x212B => array("\x00\x00\x00\xE5", 'Case map'),

        0x212C => array("\x00\x00\x00\x62", 'Additional folding'),

        0x212D => array("\x00\x00\x00\x63", 'Additional folding'),

        0x2130 => array("\x00\x00\x00\x65", 'Additional folding'),

        0x2131 => array("\x00\x00\x00\x66", 'Additional folding'),

        0x2133 => array("\x00\x00\x00\x6D", 'Additional folding'),

        0x2160 => array("\x00\x00\x21\x70", 'Case map'),

        0x2161 => array("\x00\x00\x21\x71", 'Case map'),

        0x2162 => array("\x00\x00\x21\x72", 'Case map'),

        0x2163 => array("\x00\x00\x21\x73", 'Case map'),

        0x2164 => array("\x00\x00\x21\x74", 'Case map'),

        0x2165 => array("\x00\x00\x21\x75", 'Case map'),

        0x2166 => array("\x00\x00\x21\x76", 'Case map'),

        0x2167 => array("\x00\x00\x21\x77", 'Case map'),

        0x2168 => array("\x00\x00\x21\x78", 'Case map'),

        0x2169 => array("\x00\x00\x21\x79", 'Case map'),

        0x216A => array("\x00\x00\x21\x7A", 'Case map'),

        0x216B => array("\x00\x00\x21\x7B", 'Case map'),

        0x216C => array("\x00\x00\x21\x7C", 'Case map'),

        0x216D => array("\x00\x00\x21\x7D", 'Case map'),

        0x216E => array("\x00\x00\x21\x7E", 'Case map'),

        0x216F => array("\x00\x00\x21\x7F", 'Case map'),

        0x24B6 => array("\x00\x00\x24\xD0", 'Case map'),

        0x24B7 => array("\x00\x00\x24\xD1", 'Case map'),

        0x24B8 => array("\x00\x00\x24\xD2", 'Case map'),

        0x24B9 => array("\x00\x00\x24\xD3", 'Case map'),

        0x24BA => array("\x00\x00\x24\xD4", 'Case map'),

        0x24BB => array("\x00\x00\x24\xD5", 'Case map'),

        0x24BC => array("\x00\x00\x24\xD6", 'Case map'),

        0x24BD => array("\x00\x00\x24\xD7", 'Case map'),

        0x24BE => array("\x00\x00\x24\xD8", 'Case map'),

        0x24BF => array("\x00\x00\x24\xD9", 'Case map'),

        0x24C0 => array("\x00\x00\x24\xDA", 'Case map'),

        0x24C1 => array("\x00\x00\x24\xDB", 'Case map'),

        0x24C2 => array("\x00\x00\x24\xDC", 'Case map'),

        0x24C3 => array("\x00\x00\x24\xDD", 'Case map'),

        0x24C4 => array("\x00\x00\x24\xDE", 'Case map'),

        0x24C5 => array("\x00\x00\x24\xDF", 'Case map'),

        0x24C6 => array("\x00\x00\x24\xE0", 'Case map'),

        0x24C7 => array("\x00\x00\x24\xE1", 'Case map'),

        0x24C8 => array("\x00\x00\x24\xE2", 'Case map'),

        0x24C9 => array("\x00\x00\x24\xE3", 'Case map'),

        0x24CA => array("\x00\x00\x24\xE4", 'Case map'),

        0x24CB => array("\x00\x00\x24\xE5", 'Case map'),

        0x24CC => array("\x00\x00\x24\xE6", 'Case map'),

        0x24CD => array("\x00\x00\x24\xE7", 'Case map'),

        0x24CE => array("\x00\x00\x24\xE8", 'Case map'),

        0x24CF => array("\x00\x00\x24\xE9", 'Case map'),

        0x3371 => array("\x00\x00\x00\x68\x00\x00\x00\x70\x00\x00\x00\x61", 'Additional folding'),

        0x3373 => array("\x00\x00\x00\x61\x00\x00\x00\x75", '75'),

        0x3375 => array("\x00\x00\x00\x6F\x00\x00\x00\x76", '76'),

        0x3380 => array("\x00\x00\x00\x70\x00\x00\x00\x61", '61'),

        0x3381 => array("\x00\x00\x00\x6E\x00\x00\x00\x61", '61'),

        0x3382 => array("\x00\x00\x03\xBC\x00\x00\x00\x61", '61'),

        0x3383 => array("\x00\x00\x00\x6D\x00\x00\x00\x61", '61'),

        0x3384 => array("\x00\x00\x00\x6B\x00\x00\x00\x61", '61'),

        0x3385 => array("\x00\x00\x00\x6B\x00\x00\x00\x62", '62'),

        0x3386 => array("\x00\x00\x00\x6D\x00\x00\x00\x62", '62'),

        0x3387 => array("\x00\x00\x00\x67\x00\x00\x00\x62", '62'),

        0x338A => array("\x00\x00\x00\x70\x00\x00\x00\x66", '66'),

        0x338B => array("\x00\x00\x00\x6E\x00\x00\x00\x66", '66'),

        0x338C => array("\x00\x00\x03\xBC\x00\x00\x00\x66", '66'),

        0x3390 => array("\x00\x00\x00\x68\x00\x00\x00\x7A", '7A'),

        0x3391 => array("\x00\x00\x00\x6B\x00\x00\x00\x68\x00\x00\x00\x7A", 'Additional folding'),

        0x3392 => array("\x00\x00\x00\x6D\x00\x00\x00\x68\x00\x00\x00\x7A", 'Additional folding'),

        0x3393 => array("\x00\x00\x00\x67\x00\x00\x00\x68\x00\x00\x00\x7A", 'Additional folding'),

        0x3394 => array("\x00\x00\x00\x74\x00\x00\x00\x68\x00\x00\x00\x7A", 'Additional folding'),

        0x33A9 => array("\x00\x00\x00\x70\x00\x00\x00\x61", '61'),

        0x33AA => array("\x00\x00\x00\x6B\x00\x00\x00\x70\x00\x00\x00\x61", 'Additional folding'),

        0x33AB => array("\x00\x00\x00\x6D\x00\x00\x00\x70\x00\x00\x00\x61", 'Additional folding'),

        0x33AC => array("\x00\x00\x00\x67\x00\x00\x00\x70\x00\x00\x00\x61", 'Additional folding'),

        0x33B4 => array("\x00\x00\x00\x70\x00\x00\x00\x76", '76'),

        0x33B5 => array("\x00\x00\x00\x6E\x00\x00\x00\x76", '76'),

        0x33B6 => array("\x00\x00\x03\xBC\x00\x00\x00\x76", '76'),

        0x33B7 => array("\x00\x00\x00\x6D\x00\x00\x00\x76", '76'),

        0x33B8 => array("\x00\x00\x00\x6B\x00\x00\x00\x76", '76'),

        0x33B9 => array("\x00\x00\x00\x6D\x00\x00\x00\x76", '76'),

        0x33BA => array("\x00\x00\x00\x70\x00\x00\x00\x77", '77'),

        0x33BB => array("\x00\x00\x00\x6E\x00\x00\x00\x77", '77'),

        0x33BC => array("\x00\x00\x03\xBC\x00\x00\x00\x77", '77'),

        0x33BD => array("\x00\x00\x00\x6D\x00\x00\x00\x77", '77'),

        0x33BE => array("\x00\x00\x00\x6B\x00\x00\x00\x77", '77'),

        0x33BF => array("\x00\x00\x00\x6D\x00\x00\x00\x77", '77'),

        0x33C0 => array("\x00\x00\x00\x6B\x00\x00\x03\xC9", 'C9'),

        0x33C1 => array("\x00\x00\x00\x6D\x00\x00\x03\xC9", 'C9'),

        0x33C3 => array("\x00\x00\x00\x62\x00\x00\x00\x71", '71'),

        0x33C6 => array("\x00\x00\x00\x63\x00\x00\x22\x15\x00\x00\x00\x6B\x00\x00\x00\x67", 'Additional folding'),

        0x33C7 => array("\x00\x00\x00\x63\x00\x00\x00\x6F\x00\x00\x00\x2E", 'Additional folding'),

        0x33C8 => array("\x00\x00\x00\x64\x00\x00\x00\x62", '62'),

        0x33C9 => array("\x00\x00\x00\x67\x00\x00\x00\x79", '79'),

        0x33CB => array("\x00\x00\x00\x68\x00\x00\x00\x70", '70'),

        0x33CD => array("\x00\x00\x00\x6B\x00\x00\x00\x6B", '6B'),

        0x33CE => array("\x00\x00\x00\x6B\x00\x00\x00\x6D", '6D'),

        0x33D7 => array("\x00\x00\x00\x70\x00\x00\x00\x68", '68'),

        0x33D9 => array("\x00\x00\x00\x70\x00\x00\x00\x70\x00\x00\x00\x6D", 'Additional folding'),

        0x33DA => array("\x00\x00\x00\x70\x00\x00\x00\x72", '72'),

        0x33DC => array("\x00\x00\x00\x73\x00\x00\x00\x76", '76'),

        0x33DD => array("\x00\x00\x00\x77\x00\x00\x00\x62", '62'),

        0xFB00 => array("\x00\x00\x00\x66\x00\x00\x00\x66", '66'),

        0xFB01 => array("\x00\x00\x00\x66\x00\x00\x00\x69", '69'),

        0xFB02 => array("\x00\x00\x00\x66\x00\x00\x00\x6C", '6C'),

        0xFB03 => array("\x00\x00\x00\x66\x00\x00\x00\x66\x00\x00\x00\x69", 'Case map'),

        0xFB04 => array("\x00\x00\x00\x66\x00\x00\x00\x66\x00\x00\x00\x6C", 'Case map'),

        0xFB05 => array("\x00\x00\x00\x73\x00\x00\x00\x74", '74'),

        0xFB06 => array("\x00\x00\x00\x73\x00\x00\x00\x74", '74'),

        0xFB13 => array("\x00\x00\x05\x74\x00\x00\x05\x76", '76'),

        0xFB14 => array("\x00\x00\x05\x74\x00\x00\x05\x65", '65'),

        0xFB15 => array("\x00\x00\x05\x74\x00\x00\x05\x6B", '6B'),

        0xFB16 => array("\x00\x00\x05\x7E\x00\x00\x05\x76", '76'),

        0xFB17 => array("\x00\x00\x05\x74\x00\x00\x05\x6D", '6D'),

        0xFEFF => array('', 'Map out'),

        0xFF21 => array("\x00\x00\xFF\x41", 'Case map'),

        0xFF22 => array("\x00\x00\xFF\x42", 'Case map'),

        0xFF23 => array("\x00\x00\xFF\x43", 'Case map'),

        0xFF24 => array("\x00\x00\xFF\x44", 'Case map'),

        0xFF25 => array("\x00\x00\xFF\x45", 'Case map'),

        0xFF26 => array("\x00\x00\xFF\x46", 'Case map'),

        0xFF27 => array("\x00\x00\xFF\x47", 'Case map'),

        0xFF28 => array("\x00\x00\xFF\x48", 'Case map'),

        0xFF29 => array("\x00\x00\xFF\x49", 'Case map'),

        0xFF2A => array("\x00\x00\xFF\x4A", 'Case map'),

        0xFF2B => array("\x00\x00\xFF\x4B", 'Case map'),

        0xFF2C => array("\x00\x00\xFF\x4C", 'Case map'),

        0xFF2D => array("\x00\x00\xFF\x4D", 'Case map'),

        0xFF2E => array("\x00\x00\xFF\x4E", 'Case map'),

        0xFF2F => array("\x00\x00\xFF\x4F", 'Case map'),

        0xFF30 => array("\x00\x00\xFF\x50", 'Case map'),

        0xFF31 => array("\x00\x00\xFF\x51", 'Case map'),

        0xFF32 => array("\x00\x00\xFF\x52", 'Case map'),

        0xFF33 => array("\x00\x00\xFF\x53", 'Case map'),

        0xFF34 => array("\x00\x00\xFF\x54", 'Case map'),

        0xFF35 => array("\x00\x00\xFF\x55", 'Case map'),

        0xFF36 => array("\x00\x00\xFF\x56", 'Case map'),

        0xFF37 => array("\x00\x00\xFF\x57", 'Case map'),

        0xFF38 => array("\x00\x00\xFF\x58", 'Case map'),

        0xFF39 => array("\x00\x00\xFF\x59", 'Case map'),

        0xFF3A => array("\x00\x00\xFF\x5A", 'Case map')

    );



    /* RACE domain prefix */

    var $racePrefix = 'bq--';



    var $raceMapped;                # bool      true if the domain has been altered/mapped

    var $raceMappedChars = array(); # array     information of mapped characters (orginal character, mapped to character(s), reason)

    var $raceProhibChars = array(); # array     information of prohibited characters

    var $raceConverted;             # bool      true if the domain was RACE en/decoded

    var $raceWrongPrefix;            # bool        true if the RACE encoded domain had the wrong prefix

    var $raceResult;                # string    converted RACE domain, or original domain if conversion failed

    var $raceError;                 # string    descriptive error if conversion failed

    





    /**

    * Constructor

    *

    * @param    $CE         character encoding to use for international strings, default is to UTF-8

    *

    * @return   $return     true or a PEAR error

    *

    * @access   public

    *

    */



    function Net_RACE ($CE='UTF-8')

    {

        /* check multibyte support */

        if (!extension_loaded('mbstring')) {

            return $this->raiseError('multibyte string support is not enabled', null, 

                PEAR_ERROR_DIE, null, 'compile PHP using "--enable-mbstring"', 'Net_RACE_Error', false );

        }

        if (!function_exists('mb_convert_encoding')) {

            return $this->raiseError('unknown multibyte string support, only works with PHP 4.06', null, 

                PEAR_ERROR_DIE, null, null, 'Net_RACE_Error', false );

        }



        /* initialize */

        if(in_array($CE,$this->raceKnownCE)) {

            $this->raceCharacterEncoding = $CE;

        } else {

            return $this->raiseError('invalid character encoding', null, 

                PEAR_ERROR_DIE, null, null, 'Net_RACE_Error', false );

        }



        return true;

    }







    /**

    * RACE encode a domain

    *

    * @param    $domain     internationalised domain to encode, string has character encoding specified by constructor

    *

    * @return   $RACEresult true if encoding succeeds, false if not

    *

    * @access   public

    *

    */



    function doRace($domain)

    {

        /* convert to UCS-4 encoded string */

        $UCS4domain = $this->convertEncoding($domain,$this->raceCharacterEncoding,"UCS-4");



        /* check for prohibited characters */

        if($this->checkForProhib($UCS4domain)) {

            /* prohibited characters found */

            $this->raceConverted    = false;

            $this->raceError        = 'Found prohibited character(s): '.join(', ',$this->raceProhibChars);

            return false;

        } else {

            /* map characters */

            $UCS4domainMap = $this->doMap($UCS4domain);



            /* convert to UTF-16 encoded string */

            $UTF16domain = $this->convertEncoding($UCS4domainMap,"UCS-4","UTF-16");



            /* RACE encoding */

            $RACEresult = $this->UTF16toRACE($UTF16domain);



            return $RACEresult;

        }

    }







    /**

    * RACE decode a domain

    *

    * @param    $RACEdomain RACE encoded domain (bq--***) to decode

    *

    * @return   $RACEresult true if decoding succeeds, false if not

    *

    * @access   public

    *

    */



    function undoRace($RACEdomain)

    {

        /* RACE decoding */

        $RACEresult = $this->RACEtoUTF16($RACEdomain);



        /* convert to required character encoding */

        $this->raceResult = $this->convertEncoding($this->raceResult,"UTF-16",$this->raceCharacterEncoding);



        return $RACEresult;

    }







    /**

    * Check $UCS4domain for prohibited unicode characters

    *

    * @param    $UCS4domain UCS-4 encoded domain to check

    *

    * @return   $result     true if prohibited characters are found, false if not

    *

    * @access   private

    *

    */



    function checkForProhib($UCS4domain) {
        $result = false;

        for($i=0; $i<strlen($UCS4domain); $i+=4) {

            $UCS4byte1 = substr($UCS4domain,$i,1);

            $UCS4byte2 = substr($UCS4domain,$i+1,1);

            $UCS4byte3 = substr($UCS4domain,$i+2,1);

            $UCS4byte4 = substr($UCS4domain,$i+3,1);

            $UCS4int = ord($UCS4byte4)+(ord($UCS4byte3)*256)+((ord($UCS4byte2)*256)*256)+(((ord($UCS4byte1)*256)*256)*256);

            $UCS4char = $UCS4byte1.$UCS4byte2.$UCS4byte3.$UCS4byte4;


            /* check for single character */


            if(in_array($UCS4int,$this->racePC)||in_array($UCS4int,$this->raceUC)) {

                /* character is prohibited/unassigned */

                array_push($this->raceProhibChars,$this->UCS4toUnicode($UCS4char));

                $result = true;

            } else

            {

                foreach(array_merge($this->racePCR,$this->raceUCR) as $raceR) {
                    if(($UCS4int>=$raceR[0])&&($UCS4int<=$raceR[1])) {
         //if (($UCS4int>=$raceR[0]) && ($UCS4int<=$raceR[1])) echo "<br>aca si";
                    /* character in prohibited/unassigned range */

                        array_push($this->raceProhibChars,$this->UCS4toUnicode($UCS4char));

                        $result = true;

                    }

                }

            }

        }



        return $result;

    }



    /**

    * Map certain unicode characters in $UCS4domain to proper characters (for example, make all characters lowercase)

    *

    * @param    $UCS4domain UCS-4 encoded domain to map

    *

    * @return   $result     mapped $UCS4domain

    *

    * @access   private

    *

    */



    function doMap($UCS4domain) {

        /* declaration */

        $this->raceMapped = false;

        $UCS4domainStack = array();



        for($i=0; $i<strlen($UCS4domain); $i+=4) {

            /* reset for each character */

            $UCS4mappedStack = array();

            $UCS4byte1  = substr($UCS4domain,$i,1);

            $UCS4byte2  = substr($UCS4domain,$i+1,1);

            $UCS4byte3  = substr($UCS4domain,$i+2,1);

            $UCS4byte4  = substr($UCS4domain,$i+3,1);

            $UCS4char   = $UCS4byte1.$UCS4byte2.$UCS4byte3.$UCS4byte4;

            $UCS4int    = ord($UCS4byte4)+(ord($UCS4byte3)*256)+((ord($UCS4byte2)*256)*256)+(((ord($UCS4byte1)*256)*256)*256);



            if(isset($this->raceUMap[$UCS4int]))

            {

                /* char must be mapped, break apart mapped-to chars (some chars are mapped to multiple chars)*/

                for($iM=0; $iM<strlen($this->raceUMap[$UCS4int][0]); $iM+=4) {

                    array_push ($UCS4mappedStack, $this->UCS4toUnicode(substr($this->raceUMap[$UCS4int][0],$iM,4)));

                }



                /* add characters to stack */

                array_push ($UCS4domainStack, $this->raceUMap[$UCS4int][0]);



                /* add mapped-from, mappet-to and the reason to array */

                array_push ($this->raceMappedChars, array($this->UCS4toUnicode($UCS4char), $this->convertEncoding($UCS4char,"UCS-4",$this->raceCharacterEncoding), join(',',$UCS4mappedStack), $this->convertEncoding($this->raceUMap[$UCS4int][0],"UCS-4",$this->raceCharacterEncoding), $this->raceUMap[$UCS4int][1]));



                /* set mapped */

                $this->raceMapped = true;

            } else {

                /* characters does not need mapping */

                array_push ($UCS4domainStack, $UCS4byte1.$UCS4byte2.$UCS4byte3.$UCS4byte4);

            }

        }



        $result = join('',$UCS4domainStack);

        return $result;

    }







    /**

    * Convert a UCS-4 encoded character into Unicode U+* notation

    *

    * @param    $UCS4Char   UCS-4 encoded character (4 bytes)

    *

    * @return   $Unicode    Unicode notated $UCS4Char

    *

    * @access   private

    *

    */



    function UCS4toUnicode ($UCS4Char) {

        $UCS4hex1 = bin2hex(substr($UCS4Char,0,1));

        $UCS4hex2 = bin2hex(substr($UCS4Char,1,1));

        $UCS4hex3 = bin2hex(substr($UCS4Char,2,1));

        $UCS4hex4 = bin2hex(substr($UCS4Char,3,1));



        if($UCS4hex1=='00') {

            $UCS4hex1 = '';

        }



        if($UCS4hex2=='00') {

            $UCS4hex2 = '';

        }



        return 'U+'.strtoupper($UCS4hex1.$UCS4hex2.$UCS4hex3.$UCS4hex4);

    }





    

    /**

    * RACE encode a UTF-16 encoded, internationalised domain

    *

    * @param    $domain     UTF-16 encoded, nameprepped, internationalised domain

    *

    * @return   $RACEdomain RACE encoded domain or original domain if no RACE encoding is needed, false if encoding fails

    *

    * @access   private

    *

    */



    function UTF16toRACE ($domain)

    {

        /* declaration */

        $InArr      = array();

        $UpperSeen  = array();

        $UpperUniq  = array();

        $DoStep3    = false;

        $U1         = null;

        $U2         = null;

        $N1         = null;

        $CompString = null;

        $RACEdomain = null;



        /* Make an array of the UTF16 octets */

        for ($i = 0; $i < strlen($domain); $i++) {

            array_push($InArr,substr($domain,$i,1));

        }



        /* check for STD13 compliant name */

        if($this->checkForSTD13Name($domain)) {



            /* The name contains only ascii chars, no RACE encoding allowed */

            $RACEdomain             = $this->convertEncoding($domain,"UTF-16",$this->raceCharacterEncoding);

            $this->raceConverted    = false;

            $this->raceError        = 'Invalid input to UTF16toRACE: no RACE encoding needed, input contained only STD13 ASCII characters.';

            $this->raceResult       = $RACEdomain;

            return true;



        } else {



            /* Prepare for steps 1 and 2 by making an array of the upper octets */

            for($InputPointer = 0; $InputPointer <= (count($InArr)-1); $InputPointer += 2) {

                if (!isset($UpperSeen[$InArr[$InputPointer]])) {

                    $UpperSeen[$InArr[$InputPointer]] = true;

                    if (!isset($UpperUniq[$InArr[$InputPointer]])) {

                        array_push ($UpperUniq, $InArr[$InputPointer]);

                    }

                }

            }



            /* Step 1 & 2 */

            if(count($UpperUniq) == 1) {

                /* Step 1 */

                $U1 = $UpperUniq[0];

                $DoStep3 = false;

            } else

            if(count($UpperUniq) == 2) {

                /* Step 2*/

                if($UpperUniq[0] == "\x00") {

                    $U1 = $UpperUniq[1];

                    $DoStep3 = false;

                } else

                if($UpperUniq[1] == "\x00") {

                    $U1 = $UpperUniq[0];

                    $DoStep3 = false;

                } else {

                    $DoStep3 = true;

                }

            } else {

                $DoStep3 = true;

            }



            /* Now output based on the value of $DoStep3 */

            if($DoStep3) 

            {

                /* Step 3 */

                $CompString = "\xd8". explode('', $InArr);

            } else {

                /* Step 4a */

                if((ord($U1)>215)&&(ord($U1)<223)) {

                    $DieOrd                 = sprintf('%04lX', ord($U1));

                    $this->raceConverted    = false;

                    $this->raceError        = 'Found invalid input to UTF16toRACE step 4a: $DieOrd.';

                    return false;

                }



                /* Step 4b */

                $CompString = $U1;  

                $InputPointer = 0;



                /* Step 5a */

                while($InputPointer <= (count($InArr)-1)) {

                    /* Step 5b */

                    $U2 = $InArr[$InputPointer++]; $N1 = $InArr[$InputPointer++];

                    if(($U2 == "\x00")&&($N1 == "\x99")) {

                        /* Step 5c */

                        $this->raceConverted    = false;

                        $this->raceError        = 'Found U+0099 in input stream to UTF16toRACE step 5c.';

                        return false;

                    }

                    

                    if(($U2 == $U1)&&($N1 != "\xff")) {

                        /* Step 6 */

                        $CompString .= $N1;

                    } else

                    if(($U2 == $U1)&&($N1 == "\xff")) {

                        /* Step 7 */

                        $CompString .= "\xff\x99";

                    } else {

                        /* Step 8 */

                        $CompString .= "\xff" . $N1;

                    }

                }



                if(strlen($CompString) >= 37)

                {

                    $this->raceConverted    = false;

                    $this->raceError        = 'Lenth of compressed string was >= 37 in UTF16toRACE.';

                    return false;

                }



                /* base32 encode */

                $RACEdomain = $this->racePrefix.$this->base32Encode($CompString);



                $this->raceConverted    = true; 

                $this->raceResult       = $RACEdomain;

                return true;

            }

        }

    }







    /**

    * RACE decode a domain to a UTF-16 encoded, internationalised domain

    *

    * @param    $RACEdomain RACE encoded domain

    *

    * @return   $domain        UTF-16 encoded, nameprepped, internationalised domain is needed, false if encoding fails

    *

    * @access   private

    *

    */



    function RACEtoUTF16 ($RACEdomain)

    {

        /* declaration */

        $deArr        = array();

        $checkArr   = array();

        $UpperSeen  = array();

        $UpperUniq  = array();

        $postBase32    = null;

        $U1            = null;

        $N1         = null;

        $domain     = null;

        $LCheck     = null;



        /* strip any whitespaces */

        $RACEdomain = trim($RACEdomain);



        /* check prefix */

        if(substr($RACEdomain,0,strlen($this->racePrefix))!=$this->racePrefix) {

            $this->raceResult        = $RACEdomain;

            $this->raceConverted    = false;

            $this->raceWrongPrefix    = true;

            $this->raceError        = 'The input to RACEtoUTF16 did not start with '.$this->racePrefix.'.';

            return false;

        } else {

            $this->raceWrongPrefix    = false;

        }



        /* strip the prefix string */

        $RACEdomain = substr($RACEdomain,strlen($this->racePrefix));



        /* base32 decode */

        $postBase32 = $this->base32Decode($RACEdomain);



        /* break apart */

        for ($i = 0; $i < strlen($postBase32); $i++) {

            array_push ($deArr,substr($postBase32,$i,1));

        }



        /* reverse the compression */



        /* Step 1a */

        $U1 = $deArr[0];

        if(count($deArr) < 1) {

            /* Step 1b */

            $this->raceResult        = $RACEdomain;

            $this->raceConverted    = false;

            $this->raceError        = 'The output of Base32Decode was zero length.';

            return false;

        }

 

        /* Step 1c */

        if($U1 != "\xd8") {  

            $i = 1;

            /* Step 2a */

            while($i < count($deArr)) {

                /* Step 2b */

                $N1 = $deArr[$i++];

                if($N1 != "\xff") {

                    /* Step 2c */

                    if(($U1=="\x00")&&($N1=="\x99")) {

                        /* Step 3 */

                        $this->raceResult        = $RACEdomain;

                        $this->raceConverted    = false;

                        $this->raceError        = 'Found 0099 in the input to RACEtoUCS4, step 3.';

                        return false;

                    }

                    /* Step 4 */

                    $domain .= $U1 . $N1;

                } else {

                    if($i > count($deArr)) {

                        /* Step 5 */

                        $this->raceResult        = $RACEdomain;

                        $this->raceConverted    = false;

                        $this->raceError        = 'Input in RACE string at octet $i too short at step 5.';

                        return false;

                    }

                    /* Step 6a */

                    $N1 = $deArr[$i++];

                    if($N1 == "\x99") {

                        /* Step 6b */

                        $domain .= $U1 . "\xff";

                    } else {

                        /* Step 7 */

                        $domain .= "\x00" . $N1;

                    }

                }

            }



            /* Step 11 */

            if((strlen($domain) % 2) == 1) { 

                $this->raceResult        = $RACEdomain;

                $this->raceConverted    = false;

                $this->raceError        = 'The output of RACEtoUCS4 for compressed input was an odd number of characters at step 11.';

                return false;

            }

        } else {

            /* Was not compressed */

            $LCheck = substr(join('', $deArr), 1);

            /* Step 8a */

            if((length($LCheck) % 2 ) == 1 ) {

                /* Step 8b */

                $this->raceResult        = $RACEdomain;

                $this->raceConverted    = false;

                $this->raceError        = 'The output of RACEtoUCS4 for uncompressed input was an odd number of characters at step 8b.';

                return false;

            }

            /* Do the step 9 check to be sure the right length was used */

            for ($i = 0; $i < strlen($LCheck); $i++) {

                array_push ($checkArr,substr($LCheck,$i,1));

            }



            for($inputPointer = 0; $inputPointer <= count($checkArr); $inputPointer += 2) {

                if (!isset($UpperSeen[$checkArr[$inputPointer]])) {

                    $UpperSeen[$checkArr[$inputPointer]] = 1;

                    array_push ($UpperUniq, $checkArr[$inputPointer]);

                }

            }

            

            /* Should it have been compressed? */

            if( (count($UpperUniq) == 0) || ((count($UpperUniq) == 1)&&(($UpperUniq[0] == "\x00")||($UpperUniq[1] == "\x00"))) ) { 

                $this->raceResult        = $RACEdomain;

                $this->raceConverted    = false;

                $this->raceError        = 'Input to RACEtoUCS4 failed during LCHECK format test in step 9.';

                return false;

            }



            /* Step 10a */

            if((strlen($LCheck) % 2) == 1) { 

                $this->raceResult        = $RACEdomain;

                $this->raceConverted    = false;

 $this->raceError        = 'The output of RACEtoUCS4 for uncompressed input was an odd number of characters at step 10a.';

                return false;

            }

            $domain = $LCheck;

        }



        if($this->checkForSTD13Name($domain)) { 

            $this->raceResult        = $RACEdomain;

            $this->raceConverted    = false;

            $this->raceError        = 'Found all-STD13 name before output of RACEtoUCS4';

            return false;

        }



        /*

        if($this->checkForBadSurrogates($domain)) { 

            $this->raceResult        = $RACEdomain;

            $this->raceConverted    = false;

            $this->raceError        = 'Found bad surrogate before output of RACEtoUCS4';

            return $RACEdomain;

        }

        */



        /* converted */

        $this->raceConverted    = true;

        $this->raceResult        = $domain;

        return true;

    }







    /**

    * Convert $inString, an $inEnc encoded string into $outString, an $outEnc encoded string

    *

    * @param    $inString   binary string

    * @param    $inEnc      character encoding of $inString

    * @param    $outEnc     character encoding for $outString

    *

    * @return   $outString  binary string encoded in $outEnc

    *

    * @access   private

    *

    */



    function convertEncoding ($inString,$inEnc,$outEnc)

    {

        /* This works in PHP 4.06 */
        return mb_convert_encoding($inString,$outEnc,$inEnc);

    }







    /**

    * Check whether a string contains only STD13 ASCII characters

    *

    * @param    $inString   UTF-16 encoded string to to check

    *

    * @return   $return     true if $inString contains only STD13 characters, false otherwise

    *

    * @access   private

    *

    */



    function checkForSTD13Name ($inString)

    {

        $STD13 = true;

        $STD13Chars = array(

            '0','1','2','3','4','5','6','7','8','9',

            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',

            '-',

            'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',

        );



        $InArr = array();



        /* break apart all UTF-16 octets */

        for ($i = 0; $i < strlen($inString); $i++) {

            array_push ($InArr,substr($inString,++$i,1));

        }



        /* no need to double check :] */

        $check_array = array_unique ($InArr);



        foreach($check_array as $char)

        {

            if (!in_array(strtolower($char),$STD13Chars))

            {

                $STD13 = false;

            }

        }

        

        return $STD13;

    }







    /**

    * Base32 encode a binary string

    *

    * @param    $inString   Binary string to base32 encode

    *

    * @return   $outString  Base32 encoded $inString

    *

    * @access   private

    *

    */



    function base32Encode ($inString) 

    { 

        $outString = ""; 

        $compBits = ""; 

        $BASE32_TABLE = array( 

            '00000' => 0x61, 

            '00001' => 0x62, 

            '00010' => 0x63, 

            '00011' => 0x64, 

            '00100' => 0x65, 

            '00101' => 0x66, 

            '00110' => 0x67, 

            '00111' => 0x68, 

            '01000' => 0x69, 

            '01001' => 0x6a, 

            '01010' => 0x6b, 

            '01011' => 0x6c, 

            '01100' => 0x6d, 

            '01101' => 0x6e, 

            '01110' => 0x6f, 

            '01111' => 0x70, 

            '10000' => 0x71, 

            '10001' => 0x72, 

            '10010' => 0x73, 

            '10011' => 0x74, 

            '10100' => 0x75, 

            '10101' => 0x76, 

            '10110' => 0x77, 

            '10111' => 0x78, 

            '11000' => 0x79, 

            '11001' => 0x7a, 

            '11010' => 0x32, 

            '11011' => 0x33, 

            '11100' => 0x34, 

            '11101' => 0x35, 

            '11110' => 0x36, 

            '11111' => 0x37, 

        ); 



        /* Turn the compressed string into a string that represents the bits as 0 and 1. */

        for ($i = 0; $i < strlen($inString); $i++) {

            $compBits .= str_pad(decbin(ord(substr($inString,$i,1))), 8, '0', STR_PAD_LEFT);

        }



        /* Pad the value with enough 0's to make it a multiple of 5 */

        if((strlen($compBits) % 5) != 0) {

            $compBits = str_pad($compBits, strlen($compBits)+(5-(strlen($compBits)%5)), '0', STR_PAD_RIGHT);

        }



        /* Create an array by chunking it every 5 chars */

        $fiveBitsArray = split("\n",rtrim(chunk_split($compBits, 5, "\n"))); 



        /* Look-up each chunk and add it to $outstring */

        foreach($fiveBitsArray as $fiveBitsString)

        { 

            $outString .= chr($BASE32_TABLE[$fiveBitsString]); 

        } 



        return $outString; 

    } 







    /**

    * Base32 decode to a binary string

    *

    * @param    $inString   String to base32 decode

    *

    * @return   $outString  Base32 decoded $inString

    *

    * @access   private

    *

    */



    function Base32Decode($inString) {

        /* declaration */

        $inputCheck = null;

        $deCompBits = null;



        $BASE32_TABLE = array( 

            0x61 => '00000', 

            0x62 => '00001', 

            0x63 => '00010', 

            0x64 => '00011', 

            0x65 => '00100', 

            0x66 => '00101', 

            0x67 => '00110', 

            0x68 => '00111', 

            0x69 => '01000', 

            0x6a => '01001', 

            0x6b => '01010', 

            0x6c => '01011', 

            0x6d => '01100', 

            0x6e => '01101', 

            0x6f => '01110', 

            0x70 => '01111', 

            0x71 => '10000', 

            0x72 => '10001', 

            0x73 => '10010', 

            0x74 => '10011', 

            0x75 => '10100', 

            0x76 => '10101', 

            0x77 => '10110', 

            0x78 => '10111', 

            0x79 => '11000', 

            0x7a => '11001', 

            0x32 => '11010', 

            0x33 => '11011', 

            0x34 => '11100', 

            0x35 => '11101', 

            0x36 => '11110', 

            0x37 => '11111', 

        ); 



        /* Step 1 */

        $inputCheck = strlen($inString) % 8;

        if(($inputCheck == 1)||($inputCheck == 3)||($inputCheck == 6)) { 

            return $this->raiseError('input to Base32Decode was a bad mod length: '.$inputCheck, null, 

                PEAR_ERROR_DIE, null, null, 'Net_RACE_Error', false );

        }

 

        /* $deCompBits is a string that represents the bits as 0 and 1.*/

        for ($i = 0; $i < strlen($inString); $i++) {

            $inChar = ord(substr($inString,$i,1));

            if(isset($BASE32_TABLE[$inChar])) {

                $deCompBits .= $BASE32_TABLE[$inChar];

            } else {

                return $this->raiseError('input to Base32Decode had a bad character: '.$inChar, null, 

                    PEAR_ERROR_DIE, null, null, 'Net_RACE_Error', false );

            }

        }



        /* Step 5 */

        $padding = strlen($deCompBits) % 8;

        $paddingContent = substr($deCompBits, (strlen($deCompBits) - $padding));

        if(substr_count($paddingContent, '1')>0) { 

            return $this->raiseError('found non-zero padding in Base32Decode', null, 

                PEAR_ERROR_DIE, null, null, 'Net_RACE_Error', false );

        }

 

        /* Break the decompressed string into octets for returning */

        $deArr = array();

        for($i = 0; $i < (int)(strlen($deCompBits) / 8); $i++) {

            $deArr[$i] = chr(bindec(substr($deCompBits, $i*8, 8)));

        }

       $outString = join('',$deArr);
        return $outString;
    }

}

class Net_RACE_Error extends PEAR_Error

{

    var $classname             = 'Net_RACE_Error';
    var $error_message_prepend = 'Error in Net_RACE';

    function Net_RACE_Error ($message, $code = 0, $mode = PEAR_ERROR_RETURN, $level = E_USER_NOTICE)
    {

        $this->PEAR_Error ($message, $code, $mode, $level);
    }



}

?>
