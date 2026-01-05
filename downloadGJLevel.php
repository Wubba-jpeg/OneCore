<?php
include "incl/lib/connection.php";
require_once "incl/lib/injectionlibpatch.php";

// check if secret exists
if (!isset($_POST["secret"])) {
exit("-1");
}

$levelID = injectpatch::number($_POST["levelID"]);
$inc = injectpatch::number($_POST["inc"]);

// add 1 to downloads
if ($inc == 1) {
$updateQuery = $db->prepare("UPDATE levels SET downloads = downloads + 1 WHERE levelID = :levelID");
$updateQuery->execute([':levelID' => $levelID]);
}

// get level data
$query = $db->prepare("SELECT * FROM levels WHERE levelID = :levelID");
$query->execute([':levelID' => $levelID]);
$levels = $query->fetchAll();

$levelfile = "levelData/$levelID";
$leveldat = file_get_contents($levelfile);

$level = $levels[0];
$levelstring = $leveldat;

// Level Object
$response = "1:{$level["levelID"]}:2:{$level["levelName"]}:3:{$level["description"]}:5:{$level["levelVersion"]}:6:{$level["userID"]}:8:10:9:{$level["difficulty"]}:10:{$level["downloads"]}:11:0:12:{$level["officialSong"]}:13:{$level["gameVersion"]}:14:{$level["likes"]}:15:{$level["length"]}:18:{$level["rated"]}:19:{$level["featured"]}:17:{$level["demon"]}:25:{$level["auto"]}:4:$levelstring";

echo $response;
?>
