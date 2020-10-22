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

function is_var_array($arr)
{
    if (isset($arr) && is_array($arr) && count($arr) > 0) {
        return true;
    } else {
        return false;
    }
}

function get_array_value($arr, $key = "", $init_val = "")
{
    if (is_var_array($arr) && $key != "" && array_key_exists($key, $arr) && !is_blank($arr[$key])) {
        return $arr[$key];
    } else {
        if ($init_val != "") {
            return $init_val;
        } else {
            return "";
        }
    }
}

function get_array_by_key_with_value($array, $key = "", $value = "",$init_val="")
{
    if (!is_var_array($array) || is_blank($key)) {
        return "";
    }
    $result = "";
    foreach ($array as $item=>$rs) {
        if($item == $key){
            foreach ($rs as $v){
                if(is_blank($value)){
                    $result[] = $v;
                }else{
                    $result = $v["$value"];
                }
            }
        }


    }


//    echo "OK Value is =".$result;
    return $result;
}

function is_in_array($val, $arr)
{
    if (is_var_array($arr)) {
        return in_array($val, $arr);
    } else {
        return false;
    }
}

function chack_data($data)
{

    $abc = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
    $all = count($abc);
    for ($k = 0; $k < $all; $k++) {
        if ($abc[$k] == $data) {
            //echo "555555555555";
            return true;
        }

    }
    return false;


}

function is_padding()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'] . "\n\n";
    if (preg_match('/Firefox/i', $u_agent)) {
        $padding = 31;
    } elseif (preg_match('/Edge/i', $u_agent)) {
        $padding = 31;
    } else {
        $padding = 30;
    }
    return $padding;
}

function is_paddingType2()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'] . "\n\n";
    if (preg_match('/Firefox/i', $u_agent)) {
        $padding = 28;
    } elseif (preg_match('/Edge/i', $u_agent)) {
        $padding = 28;
    } else {
        $padding = 27;
    }
    return $padding;
}

function is_top()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'] . "\n\n";
    if (preg_match('/Firefox/i', $u_agent)) {
        $page_top = 18;
    } elseif (preg_match('/Edge/i', $u_agent)) {
        $page_top = 18;
    } else {
        $page_top = 20;
    }
    return $page_top;
}

function is_checktop()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'] . "\n\n";
    if (preg_match('/Firefox/i', $u_agent)) {
        $page_top = 14;
    } elseif (preg_match('/Edge/i', $u_agent)) {
        $page_top = 14;
    } else {
        $page_top = 15;
    }
    return $page_top;
}

function object_to_array($obj)
{
    if (is_object($obj)) $obj = (array)$obj;
    if (is_array($obj)) {
        $new = array();
        foreach ($obj as $key => $val) {
            $new[$key] = object_to_array($val);
        }
    }
}


function count_dimension($Array, $count = 0) {
    if(is_array($Array)) {
        return count_dimension(current($Array), ++$count);
    } else {
        return $count;
    }
}
function string_hex2bin($a)
{
    $packed = "";
    foreach ( $a as $byte ) {
//        $packed .= hex2bin($byte);
    }

    return $packed;
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function str_list_to_in_array($strList){
    $res = array();
    if(!is_blank($strList)){
        $res = explode(",",$strList);
    }

    return $res;
}

?>
