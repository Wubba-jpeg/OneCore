<?php
include "incl/lib/connection.php";
require_once "incl/lib/injectionlibpatch.php";

// check if secret exists (basically finding if they are accessing from gd or url)
if (!isset($_POST["secret"]) || $_POST["secret"] !== "Wmfd2893gb7") {
    exit("-1");
}

// post params
$page = injectpatch::number($_POST["page"] ?? 0);
$str = injectpatch::clean($_POST["str"] ?? "");
$type = injectpatch::number($_POST["type"] ?? 0);

$gdDiff = injectpatch::number($_POST["diff"] ?? 0);

// makes the difficulty of gd go to database format
$diff = 0;
switch ($gdDiff) {
    case 1:
        $diff = 10;
        break;
    case 2:
        $diff = 20;
        break;
    case 3:
        $diff = 30;
        break;
    case 4:
        $diff = 40;
        break;
    case 5:
        $diff = 50;
        break;
    case 6:
        $diff = -1;
        break; // demon level
    default:
        $diff = 0;
        break;
}

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
        if (empty($str)) {
            $order = "ORDER BY levelID DESC";
            $wheretype = "";
        } elseif (is_numeric($str)) {
            $wheretype = "WHERE levelID = :str";
            $params[":str"] = $str;
            $order = "";
        } else {
            $wheretype = "WHERE LOWER(levelName) LIKE LOWER(:str)";
            $params[":str"] = "%$str%";
            $order = "ORDER BY levelID DESC";
        }
        break;
    // downloaded
    case 1:
        $order = "ORDER BY downloads DESC";
        break;
    case 2:
        // liked
        $order = "ORDER BY likes DESC";
        break;
    case 3:
        // trending (most liked this week)
        $wheretype = "WHERE uploadDate >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        $order = "ORDER BY likes DESC";
        break;
    case 4:
        // recent tab
        $order = "ORDER BY levelID DESC";
        break;
    case 5:
        // view a users levels
        $wheretype = "WHERE userID = :userid";
        $params[":userid"] = $str;
        $order = "ORDER BY levelID DESC";
        break;
    case 6:
        // featured
        $wheretype = "WHERE featured != 0";
        $order = "ORDER BY levelID DESC";
        break;
    case 7:
        // magic tab
        $wheretype = "WHERE length > 3";
        $order = "ORDER BY downloads DESC";
        break;
}

// difficulty filter
if ($diff >= 10) {
    if ($wheretype == "") {
        $wheretype = "WHERE difficulty = :diff";
    } else {
        $wheretype .= " AND difficulty = :diff";
    }
    $params[":diff"] = $diff;
}

$offset = $page * 10;
$query = $db->prepare(
    "SELECT * FROM levels $wheretype $order LIMIT 10 OFFSET $offset"
);
$query->execute($params);
$levels = $query->fetchAll();

$levelObject = "";
$creatorObject = "";

foreach ($levels as $level) {
    $levelObject .= "1:{$level["levelID"]}:2:{$level["levelName"]}:3:{$level["description"]}:5:{$level["levelVersion"]}:6:{$level["userID"]}:8:10:9:{$level["difficulty"]}:10:{$level["downloads"]}:11:0:12:{$level["officialSong"]}:13:{$level["gameVersion"]}:14:{$level["likes"]}:15:{$level["length"]}:18:{$level["rated"]}:19:{$level["featured"]}:17:{$level["demon"]}:25:{$level["auto"]}|";
    $creatorObject .= "{$level["userID"]}:{$level["userName"]}|";
}

$levelObject = rtrim($levelObject, "|");
$creatorObject = rtrim($creatorObject, "|");

// pagimation
$countQuery = $db->prepare("SELECT COUNT(*) FROM levels $wheretype");
foreach ($params as $key => $value) {
    $countQuery->bindValue($key, $value);
}
$countQuery->execute();
$totalLevels = $countQuery->fetchColumn();

echo $levelObject .
    "#" .
    $creatorObject .
    "#$totalLevels:" .
    $page * 10 .
    ":10:" .
    $totalLevels;
?>
