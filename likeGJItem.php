<?php
include "incl/lib/connection.php";
require_once "incl/lib/injectionlibpatch.php";

// check if secret exists
if (!isset($_POST["secret"])) {
exit("-1");
}

// get values
$levelID = injectpatch::number($_POST["itemID"]);
$isLike = injectpatch::number($_POST["like"]);
$type = injectpatch::number($_POST["type"]);

// get user ip
$userIP = $_SERVER['REMOTE_ADDR'];

// Only comment and level likes for now
if ($type == 1) {
$table = "levels";
$column = "levelID";
} elseif ($type == 2) {
$table = "comments";
$column = "ID";
} else {
exit("-1");
}

// see if it is a dislike or a like
if ($isLike == 1) {
$query = $db->prepare("UPDATE $table SET likes = likes + 1 WHERE $column = :levelID");
} else {
$query = $db->prepare("UPDATE $table SET likes = likes - 1 WHERE $column = :levelID"); 
}

// Change likes
$query->execute([':levelID' => $levelID]);

// log in actions table
$logQuery = $db->prepare("INSERT INTO actions (actionType, ip, levelID) VALUES (2, :ip, :levelID)");
$logQuery->execute([':ip' => $userIP, ':levelID' => $levelID]);

// return 1 to gd
echo "1";
?>