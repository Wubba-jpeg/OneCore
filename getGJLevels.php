<?php
include "incl/lib/connection.php";
require_once "incl/lib/injectionlibpatch.php";

// check if secret exists (basically finding if they are accessing from gd or url)
if (!isset($_POST["secret"])) {
exit("-1");
}

// post params
$page = injectpatch::number($_POST["page"] ?? 0);
$str = injectpatch::clean($_POST["str"] ?? "");
$type = injectpatch::number($_POST["type"] ?? 0);
$wheretype = ""; 
$order = "";
$params = [];

// star filters
if (!empty($_POST["star"])) {
$wheretype = "WHERE rated > 0";
}
if (!empty($_POST["noStar"])) {
$wheretype = "WHERE rated = 0";
}

switch ($type) {
case 0:
// search type (this is a mess)
if (empty($str)) {
// if there is nothing then order by recent

$order = "ORDER BY levelID DESC";
$wheretype = ""; 
// if not, then see if it is an id

} else if (is_numeric($str)) {
$wheretype = "WHERE levelID = :str";
$params[':str'] = $str;
$order = "";
// if not, then see if it is a level name
} else {
$wheretype = "WHERE LOWER(levelName) LIKE LOWER(:str)";
$params[':str'] = "%$str%";
$order = "ORDER BY levelID DESC"; 
}
break;

case 1:
// downloaded
$order = "ORDER BY downloads DESC";
break;

case 2:
// liked
$order = "ORDER BY likes DESC";
break;

case 3:
// trending (most downloaded this week, idk geometry dashes formula)
$wheretype = "WHERE uploadDate >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$order = "ORDER BY downloads DESC";
break;

case 4:
// recent
$order = "ORDER BY levelID DESC";
break;

case 6:
// featured
$wheretype = "WHERE featured != 0";
$order = "ORDER BY levelID DESC";
break;

case 7:
// magic
$wheretype = "WHERE length > 3";
break;
}


// get levels
$offset = $page * 10;
$query = $db->prepare("SELECT * FROM levels $wheretype $order LIMIT 10 OFFSET $offset");
$query->execute($params);
$levels = $query->fetchAll();

// build response

$levelamount = 1;
$levelObject = "";
$creatorObject = "";

foreach ($levels as $level) {
$levelObject .= "1:{$level['levelID']}:2:{$level['levelName']}:3:{$level['description']}:5:{$level['levelVersion']}:6:{$level['userID']}:8:10:9:{$level['difficulty']}:10:{$level['downloads']}:11:0:12:{$level['officialSong']}:13:{$level['gameVersion']}:14:{$level['likes']}:15:{$level['length']}:18:{$level['rated']}:19:{$level['featured']}:17:{$level['demon']}:25:{$level['auto']}|";
$creatorObject .= "{$level['userID']}:{$level['userName']}|";
}
$levelObject = rtrim($levelObject, "|");
$creatorObject = rtrim($creatorObject, "|"); 
echo $levelObject . "#" . $creatorObject . "#9999:" . ($page * 10) . ":" . count($levels);
?>
