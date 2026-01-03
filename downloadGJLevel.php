<?php
include "incl/lib/connection.php";

// check if secret exists (basically finding if they are accessing from gd or url)
if (!isset($_POST["secret"])) {
exit("-1");
}

// level id
$levelID = $_POST["levelID"];

// select all the stuff related to that level ig
$query = $db->prepare("SELECT * FROM levels WHERE levelID = $levelID");
$query->execute();
$levels = $query->fetchAll();

// get the level data
$levelfile = "levelData/$levelID";
$leveldat = file_get_contents($levelfile);

$level = $levels[0];
$levelstring = $leveldat;

// Level Object
$response = "1:{$level["levelID"]}:2:{$level["levelName"]}:3:{$level["description"]}:5:{$level["levelVersion"]}:6:{$level["userID"]}:8:10:9:{$level["difficulty"]}:10:{$level["downloads"]}:11:0:12:{$level["officialSong"]}:13:{$level["gameVersion"]}:14:{$level["likes"]}:15:{$level["length"]}:4:$levelstring";

echo $response;
?>