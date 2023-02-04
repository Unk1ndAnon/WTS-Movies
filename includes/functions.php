<?php
function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, '-');
    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // lowercase
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}
function slugged($res)
{
    // replace non digits by -
    $res = preg_replace('/[^0-9]/', '', $res);
    if (empty($res)) {
        return 'n-a';
    }
    return $res;
}
function RemoveSpecialChar($str)
{
    // Using str_ireplace() function 
    // to replace the word 
    $str = str_ireplace( array( '\'', '"', ';' ), ' ', $str);
    if (empty($str)) {
        return 'n-a';
    }
    // returning the result 
    return $str;
}
function Ratingtwo($str)
{
    //Show rating to only one decimal
    $str = number_format((float)$str, 1, '.', '');
    if (empty($str)) {
        return 'N/A';
    }
    // returning the result 
    return $str;
}
?>