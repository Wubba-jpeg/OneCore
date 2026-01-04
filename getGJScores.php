<?php
include "incl/lib/connection.php";
require_once "incl/lib/injectionlibpatch.php";

// check secret
if (!isset($_POST["secret"])) {
exit("-1");
}

// get type
$type = injectpatch::clean($_POST["type"]);

// leaderboard variables
$rank = 0;
$output = "";

if ($type == "top") {
// top 50
$query = $db->prepare("SELECT userId, userName, stars, icon, color1, color2 FROM users WHERE stars > 0 ORDER BY stars DESC LIMIT 50");
$query->execute();
$users = $query->fetchAll();

foreach ($users as $user) {
$rank++;
$output .= "1:".$user["userName"].":2:".$user["userId"].":3:".$user["stars"].":6:".$rank.":9:".$user["icon"].":10:".$user["color1"].":11:".$user["color2"]."|";
}


echo rtrim($output, "|");
}
if ($type == "relative") {
// global
$query = $db->prepare("SELECT userId, userName, stars, icon, color1, color2 FROM users WHERE stars > 0 ORDER BY stars DESC");
$query->execute();
$users = $query->fetchAll();

foreach ($users as $user) {
$rank++;
$output .= "1:".$user["userName"].":2:".$user["userId"].":3:".$user["stars"].":6:".$rank.":9:".$user["icon"].":10:".$user["color1"].":11:".$user["color2"]."|";
}


echo rtrim($output, "|");
}
?>