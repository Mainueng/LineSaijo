<?php
/**
 * Array Helper
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        Tisa Pathumwan
 *
 *
 *
 *
 *
 */

// ------------------------------------------------------------------------
/****************************************************************/
/* Check is value blank
/* Added by: Anon D.
/****************************************************************/
function is_blank($object)
{
    if (is_number($object)) return false;
    else if (is_null($object) || (is_string($object) && trim($object) == "") || empty($object)) return true;
    else return false;
}

/****************************************************************/
/* Check is value is number format
/* Added by: Oui
/****************************************************************/
function is_number($object)
{
    if ((is_numeric($object) || is_float($object) || is_int($object))) return true;
    else return false;
}

/****************************************************************/
/* Check is value blank
/* Added by: Oui
/****************************************************************/
function is_number_no_zero($object)
{
    if ((is_numeric($object) || is_float($object) || is_int($object)) && $object > 0) return true;
    else return false;
}

function clear_tags($str = "")
{
    $str = str_replace("<p>", "", $str);
    $str = str_replace("</p>", "", $str);

    return $str;
}

function get_text_pad($txt, $char = '0', $length = 5)
{
    return str_pad($txt, $length, $char, STR_PAD_LEFT);
}

function get_text_encode($text = "")
{
    if (!is_blank($text)) $text = @htmlspecialchars($text, ENT_QUOTES);
    return $text;
}

function get_text_encode_db($text = "")
{
    if (!is_blank($text)) $text = @htmlspecialchars($text);
    return $text;
}

function clearHTMLtoSave($str)
{
    $str = htmlspecialchars($str);
    //$str = addslashes($str);
    $str = str_replace("'", "\'", $str);
    $str = str_replace(chr(13), "<br>", $str);
    $str = str_replace("\n", "<br>", $str);
    $str = str_replace(chr(32), "&nbsp;", $str);

    return banned_word($str);
}

function getHTMLtoSave($str)
{
    //$str = htmlspecialchars($str);
    //$str = addslashes($str);
    $str = str_replace("'", "\'", $str);
    $str = str_replace(chr(13), "<br>", $str);
    $str = str_replace("\n", "<br>", $str);
    $str = str_replace(chr(32), "&nbsp; ", $str);

    return banned_word($str);
}

function getTexttoEdit($str)
{
    $str = get_text_encode($str);
    return banned_word($str);
}

function getTextQuote($str)
{
    $str = str_replace("'", "\'", $str);
    $str = str_replace('"', '\"', $str);
    return banned_word($str);
}

function getTextEnQuote($str)
{
    $str = str_replace("\'", "'", $str);
    $str = str_replace('\"', '"', $str);
    return banned_word($str);
}

function removeSingleQuote($str)
{
    $str = strip_tags(str_replace("'", "", $str));
    return banned_word($str);
}

function removeDoubleQuote($str)
{
    $str = strip_tags(str_replace('"', "", $str));
    return banned_word($str);
}

function removeAllQuote($str)
{
    $str = strip_tags(str_replace("'", "", $str));
    $str = str_replace('"', "", $str);
    return banned_word($str);
}

function getHTMLtoEdit($str)
{
    $str = str_replace("<br>", chr(13), $str);
    //$str = str_replace("<br>","\n",$str);
    $str = str_replace("&nbsp;", chr(32), $str);
    //$str = stripslashes($str);
    $str = str_replace("\'", "'", $str);
    return banned_word($str);
}

function getHTMLtoShow($str)
{
    //$str = str_replace("<br>",chr(13),$str);
    $str = str_replace("&nbsp;", chr(32), $str);
    //$str = stripslashes($str);
    $str = str_replace(chr(13), "<br>", $str);
    $str = str_replace("\'", "'", $str);
    return banned_word($str);
}

function getShortString($txt, $num)
{
    if (utf8_strlen($txt) > $num) {
        $txt = mb_substr($txt, 0, $num, 'UTF-8');
        $txt .= '..';
    }
    return ($txt);
}


function getUrlString($url)
{
    // $url = "& * %  # 1-9 ^ & Comic2 , 123456 , manga & Graphic Nov&%@ ^$%(els นะคะ";
    // echo "url = $url <BR>";
    $url = strtolower(trim($url));
    $url = preg_replace('/ /', '-', $url);
    $url = preg_replace('/[^a-zก-ฮๅุูึๆไใำะัํี๊ฯโเ้็่๋าแิฺื์0-9-\']/', '', $url);
    $url = preg_replace('/-+/', '-', $url);
    $url = preg_replace('/^-/', '', $url);
    $url = preg_replace('/-$/', '', $url);
    // echo "new url = $url <BR>";
    return $url;
}

/****************************************************************/
/* Convert Arabic digits to Thai digits
/* Added by: Ming / 30 Jul 2010
/****************************************************************/
function convertArabicToThai($str)
{
    $tmp = '';
    for ($i = 0; $i < strlen($str); $i++) {
        if (is_numeric($str[$i])) {
            $tmp .= unichr(uniord($str[$i]) + 3616);
        } else {
            $tmp .= $str[$i];
        }
    }
    return $tmp;
}

function convertThaiToArabic($str)
{
    $str = str_replace("๑", "1", $str);
    $str = str_replace("๒", "2", $str);
    $str = str_replace("๓", "3", $str);
    $str = str_replace("๔", "4", $str);
    $str = str_replace("๕", "5", $str);
    $str = str_replace("๖", "6", $str);
    $str = str_replace("๗", "7", $str);
    $str = str_replace("๘", "8", $str);
    $str = str_replace("๙", "9", $str);
    $str = str_replace("๐", "0", $str);
    return $str;
}

/****************************************************************/
/* Convert UTF-8 string to Ascii value
/* Added by: Ming / 30 Jul 2010
/****************************************************************/
function uniord($c)
{
    $ud = 0;
    if (ord($c{0}) >= 0 && ord($c{0}) <= 127)
        $ud = ord($c{0});
    if (ord($c{0}) >= 192 && ord($c{0}) <= 223)
        $ud = (ord($c{0}) - 192) * 64 + (ord($c{1}) - 128);
    if (ord($c{0}) >= 224 && ord($c{0}) <= 239)
        $ud = (ord($c{0}) - 224) * 4096 + (ord($c{1}) - 128) * 64 + (ord($c{2}) - 128);
    if (ord($c{0}) >= 240 && ord($c{0}) <= 247)
        $ud = (ord($c{0}) - 240) * 262144 + (ord($c{1}) - 128) * 4096 + (ord($c{2}) - 128) * 64 + (ord($c{3}) - 128);
    if (ord($c{0}) >= 248 && ord($c{0}) <= 251)
        $ud = (ord($c{0}) - 248) * 16777216 + (ord($c{1}) - 128) * 262144 + (ord($c{2}) - 128) * 4096 + (ord($c{3}) - 128) * 64 + (ord($c{4}) - 128);
    if (ord($c{0}) >= 252 && ord($c{0}) <= 253)
        $ud = (ord($c{0}) - 252) * 1073741824 + (ord($c{1}) - 128) * 16777216 + (ord($c{2}) - 128) * 262144 + (ord($c{3}) - 128) * 4096 + (ord($c{4}) - 128) * 64 + (ord($c{5}) - 128);
    if (ord($c{0}) >= 254 && ord($c{0}) <= 255) //error
    $ud = false;
    return $ud;
}

/****************************************************************/
/* Convert Ascii value back to UTF-8 string
/* Added by: Ming / 30 Jul 2010
/****************************************************************/
function unichr($dec)
{
    if ($dec < 128) {
        $utf = chr($dec);
    } else if ($dec < 2048) {
        $utf = chr(192 + (($dec - ($dec % 64)) / 64));
        $utf .= chr(128 + ($dec % 64));
    } else {
        $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
        $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
        $utf .= chr(128 + ($dec % 64));
    }
    return $utf;
}

function banned_word($txt)
{
    $string = read_file('include/banned.txt');
    //$string = iconv( 'TIS-620' , 'UTF-8' , $string);
    $banned_list = explode(",", $string);
    //print_r($banned_list);
    $str = str_replace($banned_list, "***", $txt);
    return $str;
}

function get_random_text($length = 8)
{
    $c = '9a1bc8defghijkl2mn0op3qr5st4uvwx2yz3A4B60CDEF95GHIJ6KLMNOPQ7R7STUV1WXYZ08123456789';
    $text = '';
    for ($i = 0; $i < $length; $i++) {
        $text .= $c[(rand() % strlen($c))];
    }
    return $text;
}

//Get JSON Format for PHP lower 5.2
function get_json_encode($obj)
{

    echo "{" . get_json_encode_body($obj) . "}";

}

function get_json_encode_body($obj)
{

    $json = '';
    if (is_var_array($obj)) {
        $i = 0;
        foreach ($obj as $key => $item) {
            if ($i > 0) $json .= ',';
            if (is_var_array($item) && gettype($key) != "integer") {
                $json .= '"' . $key . '":[';
                $json .= get_json_encode_body($item);
                $json .= ']';
            } else if (is_var_array($item) && gettype($key) == "integer") {
                $json .= '{' . get_json_encode_body($item) . '}';
            } else {
                $json .= '"' . $key . '":' . get_encode_value($item);
            }
            $i++;
        }
    }
    $json .= '';
    return $json;

}

function get_encode_value($var)
{
    switch (gettype($var)) {
        case 'boolean':
        return $var ? 'true' : 'false';

        case 'NULL':
        return 'null';

        case 'integer':
        return (int)$var;

        case 'double':
        case 'float':
        return (float)$var;

        case 'string':
            // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
        $ascii = '';
        $strlen_var = strlen($var);

            /*
             * Iterate over every character in the string,
             * escaping with a slash or encoding to UTF-8 where necessary
             */
            for ($c = 0; $c < $strlen_var; ++$c) {

                $ord_var_c = ord($var{$c});

                switch (true) {
                    case $ord_var_c == 0x08:
                    $ascii .= '\b';
                    break;
                    case $ord_var_c == 0x09:
                    $ascii .= '\t';
                    break;
                    case $ord_var_c == 0x0A:
                    $ascii .= '\n';
                    break;
                    case $ord_var_c == 0x0C:
                    $ascii .= '\f';
                    break;
                    case $ord_var_c == 0x0D:
                    $ascii .= '\r';
                    break;

                    case $ord_var_c == 0x22:
                    case $ord_var_c == 0x2F:
                    case $ord_var_c == 0x5C:
                        // double quote, slash, slosh
                    $ascii .= '\\' . $var{$c};
                    break;

                    case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                        // characters U-00000000 - U-0000007F (same as ASCII)
                    $ascii .= $var{$c};
                    break;

                    case (($ord_var_c & 0xE0) == 0xC0):
                        // characters U-00000080 - U-000007FF, mask 110XXXXX
                        // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $char = pack('C*', $ord_var_c, ord($var{$c + 1}));
                    $c += 1;
                    $utf16 = utf82utf16($char);
                    $ascii .= sprintf('\u%04s', bin2hex($utf16));
                    break;

                    case (($ord_var_c & 0xF0) == 0xE0):
                        // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                        // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $char = pack('C*', $ord_var_c,
                        ord($var{$c + 1}),
                        ord($var{$c + 2}));
                    $c += 2;
                    $utf16 = utf82utf16($char);
                    $ascii .= sprintf('\u%04s', bin2hex($utf16));
                    break;

                    case (($ord_var_c & 0xF8) == 0xF0):
                        // characters U-00010000 - U-001FFFFF, mask 11110XXX
                        // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $char = pack('C*', $ord_var_c,
                        ord($var{$c + 1}),
                        ord($var{$c + 2}),
                        ord($var{$c + 3}));
                    $c += 3;
                    $utf16 = utf82utf16($char);
                    $ascii .= sprintf('\u%04s', bin2hex($utf16));
                    break;

                    case (($ord_var_c & 0xFC) == 0xF8):
                        // characters U-00200000 - U-03FFFFFF, mask 111110XX
                        // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $char = pack('C*', $ord_var_c,
                        ord($var{$c + 1}),
                        ord($var{$c + 2}),
                        ord($var{$c + 3}),
                        ord($var{$c + 4}));
                    $c += 4;
                    $utf16 = utf82utf16($char);
                    $ascii .= sprintf('\u%04s', bin2hex($utf16));
                    break;

                    case (($ord_var_c & 0xFE) == 0xFC):
                        // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                        // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $char = pack('C*', $ord_var_c,
                        ord($var{$c + 1}),
                        ord($var{$c + 2}),
                        ord($var{$c + 3}),
                        ord($var{$c + 4}),
                        ord($var{$c + 5}));
                    $c += 5;
                    $utf16 = utf82utf16($char);
                    $ascii .= sprintf('\u%04s', bin2hex($utf16));
                    break;
                }
            }

            return '"' . $ascii . '"';
        }
    }

    function utf82utf16($utf8)
    {
    // oh please oh please oh please oh please oh please
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
        }

        switch (strlen($utf8)) {
            case 1:
            // this case should never be reached, because we are in ASCII range
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return $utf8;

            case 2:
            // return a UTF-16 character from a 2-byte UTF-8 char
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0x07 & (ord($utf8{0}) >> 2))
            . chr((0xC0 & (ord($utf8{0}) << 6))
                | (0x3F & ord($utf8{1})));

            case 3:
            // return a UTF-16 character from a 3-byte UTF-8 char
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr((0xF0 & (ord($utf8{0}) << 4))
                | (0x0F & (ord($utf8{1}) >> 2)))
            . chr((0xC0 & (ord($utf8{1}) << 6))
                | (0x7F & ord($utf8{2})));
        }

    // ignoring UTF-32 for now, sorry
        return '';
    }

    function utf162utf8($utf16)
    {
    // oh please oh please oh please oh please oh please
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($utf16, 'UTF-8', 'UTF-16');
        }

        $bytes = (ord($utf16{0}) << 8) | ord($utf16{1});

        switch (true) {
            case ((0x7F & $bytes) == $bytes):
            // this case should never be reached, because we are in ASCII range
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0x7F & $bytes);

            case (0x07FF & $bytes) == $bytes:
            // return a 2-byte UTF-8 character
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0xC0 | (($bytes >> 6) & 0x1F))
            . chr(0x80 | ($bytes & 0x3F));

            case (0xFFFF & $bytes) == $bytes:
            // return a 3-byte UTF-8 character
            // see: http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
            return chr(0xE0 | (($bytes >> 12) & 0x0F))
            . chr(0x80 | (($bytes >> 6) & 0x3F))
            . chr(0x80 | ($bytes & 0x3F));
        }

    // ignoring UTF-32 for now, sorry
        return '';
    }


    function get_decode_value($str)
    {
        $str = reduce_string($str);

        switch (strtolower($str)) {
            case 'true':
            return true;

            case 'false':
            return false;

            case 'null':
            return null;

            default:
            $m = array();

            if (is_numeric($str)) {
                // Lookie-loo, it's a number

                // This would work on its own, but I'm trying to be
                // good about returning integers where appropriate:
                // return (float)$str;

                // Return float or int, as appropriate
                return ((float)$str == (integer)$str)
                ? (integer)$str
                : (float)$str;

            } elseif (preg_match('/^("|\').*(\1)$/s', $str, $m) && $m[1] == $m[2]) {
                // STRINGS RETURNED IN UTF-8 FORMAT
                $delim = substr($str, 0, 1);
                $chrs = substr($str, 1, -1);
                $utf8 = '';
                $strlen_chrs = strlen($chrs);

                for ($c = 0; $c < $strlen_chrs; ++$c) {

                    $substr_chrs_c_2 = substr($chrs, $c, 2);
                    $ord_chrs_c = ord($chrs{$c});

                    switch (true) {
                        case $substr_chrs_c_2 == '\b':
                        $utf8 .= chr(0x08);
                        ++$c;
                        break;
                        case $substr_chrs_c_2 == '\t':
                        $utf8 .= chr(0x09);
                        ++$c;
                        break;
                        case $substr_chrs_c_2 == '\n':
                        $utf8 .= chr(0x0A);
                        ++$c;
                        break;
                        case $substr_chrs_c_2 == '\f':
                        $utf8 .= chr(0x0C);
                        ++$c;
                        break;
                        case $substr_chrs_c_2 == '\r':
                        $utf8 .= chr(0x0D);
                        ++$c;
                        break;

                        case $substr_chrs_c_2 == '\\"':
                        case $substr_chrs_c_2 == '\\\'':
                        case $substr_chrs_c_2 == '\\\\':
                        case $substr_chrs_c_2 == '\\/':
                        if (($delim == '"' && $substr_chrs_c_2 != '\\\'') ||
                            ($delim == "'" && $substr_chrs_c_2 != '\\"')) {
                            $utf8 .= $chrs{++$c};
                    }
                    break;

                    case preg_match('/\\\u[0-9A-F]{4}/i', substr($chrs, $c, 6)):
                            // single, escaped unicode character
                    $utf16 = chr(hexdec(substr($chrs, ($c + 2), 2)))
                    . chr(hexdec(substr($chrs, ($c + 4), 2)));
                    $utf8 .= utf162utf8($utf16);
                    $c += 5;
                    break;

                    case ($ord_chrs_c >= 0x20) && ($ord_chrs_c <= 0x7F):
                    $utf8 .= $chrs{$c};
                    break;

                    case ($ord_chrs_c & 0xE0) == 0xC0:
                            // characters U-00000080 - U-000007FF, mask 110XXXXX
                            //see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $c, 2);
                    ++$c;
                    break;

                    case ($ord_chrs_c & 0xF0) == 0xE0:
                            // characters U-00000800 - U-0000FFFF, mask 1110XXXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $c, 3);
                    $c += 2;
                    break;

                    case ($ord_chrs_c & 0xF8) == 0xF0:
                            // characters U-00010000 - U-001FFFFF, mask 11110XXX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $c, 4);
                    $c += 3;
                    break;

                    case ($ord_chrs_c & 0xFC) == 0xF8:
                            // characters U-00200000 - U-03FFFFFF, mask 111110XX
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $c, 5);
                    $c += 4;
                    break;

                    case ($ord_chrs_c & 0xFE) == 0xFC:
                            // characters U-04000000 - U-7FFFFFFF, mask 1111110X
                            // see http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
                    $utf8 .= substr($chrs, $c, 6);
                    $c += 5;
                    break;

                }

            }

            return $utf8;

        }
    }
}

function reduce_string($str)
{
    $str = preg_replace(array(

        // eliminate single line comments in '// ...' form
        '#^\s*//(.+)$#m',

        // eliminate multi-line comments in '/* ... */' form, at start of string
        '#^\s*/\*(.+)\*/#Us',

        // eliminate multi-line comments in '/* ... */' form, at end of string
        '#/\*(.+)\*/\s*$#Us'

    ), '', $str);

    // eliminate extraneous space
    return trim($str);
}

function makeClickableLinks($str, $popup = FALSE)
{
    // if (preg_match_all("#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)){
    if (preg_match_all("#(^|\s|\(|\>)((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", $str, $matches)) {
        $pop = ($popup == TRUE) ? " target=\"_blank\" " : "";
        for ($i = 0; $i < count($matches['0']); $i++) {
            $period = '';
            if (preg_match("|\.$|", $matches['6'][$i])) {
                $period = '.';
                $matches['6'][$i] = substr($matches['6'][$i], 0, -1);
            }
            $str = str_replace($matches['0'][$i],
                $matches['1'][$i] . '<a href="http' .
                $matches['4'][$i] . '://' .
                $matches['5'][$i] .
                $matches['6'][$i] . '"' . $pop . '>http' .
                $matches['4'][$i] . '://' .
                $matches['5'][$i] .
                $matches['6'][$i] . '</a>' .
                $period, $str);
        }//end for
    }//end if
    return $str;
}//end AutoLinkUrls

function utf8_strlen($str)
{
    $c = strlen($str);
    $l = 0;
    for ($i = 0; $i < $c; ++$i) {
        if ((ord($str[$i]) & 0xC0) != 0x80) {
            ++$l;
        }
    }
    return $l;
}

function strToHex($string)
{
    $hex = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0' . $hexCode, -2);
    }
    return strToUpper($hex);
}

function str2hex($str)
{
    $hex = '';
    $ascii = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $char = $str[$i];
        if (ord($char) >= 0x20 && ord($char) <= 0x7f) {
            $ascii .= $char;
        } else {
            $ascii .= '.';
        }
        $hex .= dechex(ord($char)) . ' ';
    }
    return $hex . '"' . $ascii . '"';
}

function hexToStr($hex)
{
    $string = '';
    for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
        $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
    }
    return $string;
}

function set_temp_dechex($dex = '')
{
    if (!is_blank($dex)) {
        $degree = dechex($dex);
        $degree = sprintf("0x%x", $degree);
    }

    return strval($degree);

}

function set_temp_hexbin($hex)
{

    $BinData = "";
    for ($i = 0; $i < strlen($hex); $i += 2)
        $BinData .= chr(HexDec(substr($hex, $i, 2)));
    return $BinData;

}

function energy_bill($hour = 0)
{
    $KWHOUR = 4.0;
    $bill = 0;

    if (!is_blank($hour)) {
        $bill = ($hour * $KWHOUR) / 1000;
        $bill = round($bill, 2);
    }

    return number_format($bill, 2);
//    return money_format('%i', $bill);


}

function energy_kwh($hour = 0)
{
    $energy = 0;
    if ($hour > 0) {
        $energy = $hour * 0.00027777777777778;
    }
    return number_format($energy, 2, '.', '');
}


function power_hour($hour = 0)
{
    $power_con = 0;
    if (!is_blank($hour)) {
        $power_con = $hour / 3600;
        $power_con = round($power_con, 2);
    }

    return $power_con;

}

function getDateForDatabase($date)
{
    $timestamp = strtotime($date);
    $date_formated = date('Y-m-d H:i:s', $timestamp);
    return $date_formated;
}

function calBtuRoom($w, $h, $l, $const)
{
    $btu = 0;
    if (is_number($w) && is_number($h) && is_number($l) && is_number($const)) {
        $btu = (($w * $h * $l) * $const) / 3;
    }

    return $btu;
}

function canvas_upload($imgBase64)
{
    if (!is_blank($imgBase64)) {

//        $img = $_POST['img'];
        $img = $imgBase64;
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = UPLOAD_DIR . uniqid() . '.png';
        $success = file_put_contents($file, $data);
        if ($success) {
            return $file;
        }
    }

//    print $success ? $file : 'Unable to save the file.';

}

function plan_upload($imgBase64, $newName = '')
{
    if (!is_blank($imgBase64)) {

//        $img = $_POST['img'];
        $file = "";
        $img = $imgBase64;
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace('data:image/gif;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        if (!is_blank($newName)) {
            $file = UPLOAD_PLAN_DIR . $newName;
        } else {
            $file = UPLOAD_PLAN_DIR . uniqid() . '.png';
        }

        $success = file_put_contents($file, $data);

        if ($success) {
            return $file;
        } else {
            return false;
        }


    }
//    print $success ? $file : 'Unable to save the file.';
}

function rename_image($text = "")
{
    $file_name = "";
    if (!is_blank($text)) {
        $file_name = trim($text);
        $file_name = str_replace(" ", "-", $file_name);
        $file_name = strtolower($file_name);

    }
    return $file_name . "_" . uniqid();
}
function modelType($item)
{
    $str = substr($item, 4, 1);
    if ($str == "W")
        return "Wall type";
    if ($str == "E")
        return "Floor/Ceiling Type";
    if ($str == "S")
        return "Cassette Type";
    if ($str == "F")
        return "Floor Standing Type";
    if ($str == "D")
        return "Duct Type";
    if ($str == "R")
        return "Rooftop Package";
    return "Default";
}
function productType($serial)
{

    $str = substr($serial, 4, 1);
    switch ($str) {
        case "C":
        $unit = "Outdoor";
        break;
        case "A":
        $unit = "Air Purifier";
        break;
        default:
        $unit = "Indoor";
        break;
    }
    return $unit;

}
function send_noti($token, $payload_notification, $payload_data)
{
    $SERVER_KEY = GG_API_KEY;
    $SEND_URL = GG_END_POINT;

    $url = $SEND_URL;
    $fields = array(
        'to' => $token,
        'priority' => 'high',
        'notification' => $payload_notification,
        'data' => $payload_data
    );
    $headers = array(
        'Authorization: key=' . $SERVER_KEY,
        'Content-Type: application/json'
    );
    // Open Connection
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Disable SSL Certificate support temporary
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === false) {
        die('Curl failed: ' . curl_error($ch));
    }
    //**** close connect
    curl_close($ch);


}
function send_noti_technician($token, $payload_notification, $payload_data)
{
    $SERVER_KEY = GG_CLUB_API_KEY;
    $SEND_URL = GG_END_POINT;

    $url = $SEND_URL;
    $fields = array(
        'to' => $token,
        'priority' => 'high',
        'notification' => $payload_notification,
        'data' => $payload_data
    );
    $headers = array(
        'Authorization: key=' . $SERVER_KEY,
        'Content-Type: application/json'
    );
    // Open Connection
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Disable SSL Certificate support temporary
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === false) {
        die('Curl failed: ' . curl_error($ch));
    }
    //**** close connect
    curl_close($ch);


}
function send_notification($to = '', $data = array())
{
    $api_key = GG_API_KEY;
    $fields = array('to'=>$to,'notification'=>$data);

    $headers = array('Authorization: key='.$api_key,'Content-Type: application/json');
    $fields = array(
        'to'=>$to,'notification'=>$data
    );
    $end_point = GG_END_POINT;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$end_point);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result,true);

}
function test_send_notification($to = '', $data = array())
{
    $api_key = "AIzaSyBuci4FSikEBqcF_GFNHFpPtyoHokVMpjk";

//    $to = 'dYBeGM6F2PI:APA91bEeOsDUu8WLzU1bxjQeLVnikz3A7zeGAaf0dd8kTpME-2H8ZgAtG7g2Ni3jq2VuqJQHOwlTvZS4p8icxwzN0eKKe2fQSOq8SE8bvdPVyD8SpyJl-MxqiuxMRjV6L4h_IrAWdWQd';
    $fields = array('to'=>$to,'notification'=>$data);

    $headers = array('Authorization: key='.$api_key,'Content-Type: application/json');
    $fields = array(
        'to'=>$to,'notification'=>$data
    );
    $end_point = GG_END_POINT;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$end_point);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result,true);

}
function core_send_notification($to = '', $data = array())
{
    $api_key = GG_CORE_API_KEY;
//    $api_key = "AIzaSyBuci4FSikEBqcF_GFNHFpPtyoHokVMpjk";

//    $to = 'dYBeGM6F2PI:APA91bEeOsDUu8WLzU1bxjQeLVnikz3A7zeGAaf0dd8kTpME-2H8ZgAtG7g2Ni3jq2VuqJQHOwlTvZS4p8icxwzN0eKKe2fQSOq8SE8bvdPVyD8SpyJl-MxqiuxMRjV6L4h_IrAWdWQd';
    $fields = array('to'=>$to,'notification'=>$data);

    $headers = array('Authorization: key='.$api_key,'Content-Type: application/json');
    $fields = array(
        'to'=>$to,'notification'=>$data
    );
    $end_point = GG_END_POINT;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$end_point);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result,true);

}
function club_send_notification($to = '', $data = array())
{
    $api_key = "AIzaSyBuci4FSikEBqcF_GFNHFpPtyoHokVMpjk";

//    $to = 'dYBeGM6F2PI:APA91bEeOsDUu8WLzU1bxjQeLVnikz3A7zeGAaf0dd8kTpME-2H8ZgAtG7g2Ni3jq2VuqJQHOwlTvZS4p8icxwzN0eKKe2fQSOq8SE8bvdPVyD8SpyJl-MxqiuxMRjV6L4h_IrAWdWQd';
    $fields = array('to'=>$to,'notification'=>$data);

    $headers = array('Authorization: key='.$api_key,'Content-Type: application/json');
    $fields = array(
        'to'=>$to,'notification'=>$data
    );
    $end_point = GG_END_POINT;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$end_point);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));

    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result,true);

}



function Hex2String($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

function subssid($ssid)
{
    $getsss = bin2hex($ssid);
    $subip = substr($getsss,60,64);
    $ssida = Hex2String($subip);

    return $ssida;
}

function subip($ip)
{
    $ipb2h = bin2hex($ip);
    $substrip = substr($ipb2h,48,2);
    $substrip2 = substr($ipb2h,50,2);
    $substrip3 = substr($ipb2h,52,2);
    $substrip4 = substr($ipb2h,54,2);
    $ips = hexdec($substrip).".".hexdec($substrip2).".".hexdec($substrip3).".".hexdec($substrip4);

    return $ips;
}

function subtimeon($indoor)
{
    $timeonb2h = bin2hex($indoor);
    $substrtimeon = substr($timeonb2h,60,4);
    $timeons = hexdec($substrtimeon);
    return $timeons;
}

function subtimeoff($indoor)
{
    $timeoffb2h = bin2hex($indoor);
    $substrtimeoff = substr($timeoffb2h,64,4);
    $timeoffs = hexdec($substrtimeoff);
    return $timeoffs;
}

function subairname($indoor)
{
    $airnameb2h = bin2hex($indoor);
    $substrairname = substr($airnameb2h,96,28);
    $airnames = Hex2String($substrairname);
    return $airnames;
}

function subairpass($indoor)
{
    $airpassb2h = bin2hex($indoor);
    $substrairpass = substr($airpassb2h,124,28);
    $airpasss = Hex2String($substrairpass);
    return $airpasss;
}

function sub_indoor_conf($indoor)
{
    $indoor_conf_hex = bin2hex($indoor);
    $indoor_conf = substr($indoor_conf_hex,152,16);
    return $indoor_conf;
}

function subairtype($indoor)
{
    $airtypeb2h = bin2hex($indoor);
    $substrairtype = substr($airtypeb2h,158,2);
    $airtypes = hexdec($substrairtype);
    return $airtypes;
}

function subairmodel($indoor)
{
    $airmodelb2h = bin2hex($indoor);
    $substrairmodel = substr($airmodelb2h,160,2);
    if($substrairmodel == '00')
    {
        $airmodels = $substrairmodel;
    }
    else {$airmodels = Hex2String($substrairmodel);}

    return $airmodels;
}



?>
