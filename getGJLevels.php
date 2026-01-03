<?php
include "incl/lib/connection.php";

// check if secret exists (basically finding if they are accessing from gd or url)
if (!isset($_POST["secret"])) {
exit("-1");
}

// post params
$page = $_POST["page"] ?? 0;
$str = $_POST["str"] ?? "";
$type = $_POST["type"] ?? 0;

// search type
switch ($type) {

case 4:
// recent
$order = "ORDER BY levelID DESC";
break;
}
// get levels
$offset = $page * 10;
$query = $db->prepare("SELECT * FROM levels $order LIMIT 10 OFFSET $offset");
$query->execute();
$levels = $query->fetchAll();

// build response

$levelamount = 1;
$levelObject = "";
$creatorObject= "";

foreach ($levels as $level) {
$levelObject .= "1:{$level['levelID']}:2:{$level['levelName']}:3:{$level['description']}:5:{$level['levelVersion']}:6:{$level['userID']}:8:10:9:{$level['difficulty']}:10:{$level['downloads']}:11:0:12:{$level['officialSong']}:13:{$level['gameVersion']}:14:{$level['likes']}:15:{$level['length']}|";
$creatorObject .= "{$level['userID']}:{$level['userName']}|";
}
$levelObject = rtrim($levelObject, "|");
$creatorObject = rtrim($creatorObject, "|"); 
echo $levelObject . "#" . $creatorObject . "#9999:" . ($page * 10) . ":" . count($levels);
?>