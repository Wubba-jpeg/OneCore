<?php
class injectpatch {
public static function clean($string) {
$string = trim($string);
// convert to html special charactrs
$string = htmlspecialchars($string, ENT_QUOTES);
// remove sql injection shit
$string = str_replace("\0", "", $string);
$string = str_replace("|", "", $string);
$string = str_replace("~", "", $string);
$string = str_replace("#", "", $string);
$string = str_replace(":", "", $string);
$string = str_replace(")", "", $string);

return $string;
}
// for numbers, just keep numbers 0-9 and commas
public static function number($string) {
return preg_replace("/[^0-9-]/", "", $string);
}

// for lists with numbers
public static function listnumber($string) {
return preg_replace("/[^0-9,]/", "", $string);
} 

// remove special characters, symbols, etc
public static function charclean($string) {
return preg_replace("/[^A-Za-z0-9 ]/", "", $string);
}
}
?>