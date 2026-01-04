<?php
include "incl/lib/connection.php";
include "incl/lib/mainLib.php";
require_once "incl/lib/injectionlibpatch.php";
$gs = new mainLib();

// Check if params are set
if (!isset($_POST["udid"]) OR !isset($_POST["stars"]) OR !isset($_POST["demons"]) 
    OR !isset($_POST["icon"]) OR !isset($_POST["color1"]) OR !isset($_POST["color2"])) {
    exit("-1");
}

// Get info
$udid = injectpatch::clean($_POST["udid"]);
$stars = injectpatch::number($_POST["stars"]);
$demons = injectpatch::number($_POST["demons"]);
$icon = injectpatch::number($_POST["icon"]);
$color1 = injectpatch::number($_POST["color1"]);
$color2 = injectpatch::number($_POST["color2"]);
$userName = injectpatch::clean($_POST["userName"]);

// get the user (or create if they dotn exist)
$userID = $gs->userID($udid, $userName);

// Update stats in db
$query = $db->prepare("UPDATE users SET 
    stars = :stars, 
    demons = :demons, 
    icon = :icon, 
    color1 = :color1, 
    color2 = :color2,
    lastPlayed = UNIX_TIMESTAMP()
    WHERE userId = :userID");

$query->execute([
':stars' => $stars,
':demons' => $demons,
':icon' => $icon,
':color1' => $color1,
':color2' => $color2,
':userID' => $userID
]);


echo "1";
?>
