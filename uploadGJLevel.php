<?php
include "incl/lib/connection.php";
include "incl/lib/mainLib.php";
$gs = new mainLib();
// check if you're actually from gd
if (!isset($_POST["secret"])) {
    exit("-1");
}
// get level info
$levelName = $_POST["levelName"];
$levelData = $_POST["levelString"];
$levelDesc = $_POST["levelDesc"];
$udid = $_POST["udid"];
$userName = $_POST["userName"];  // Use this directly
$length = $_POST["levelLength"];
$song = $_POST["audioTrack"];


$uid = $gs->userID($udid, $userName);

// get the level id

$query = $db->prepare("SELECT MAX(levelID) FROM levels");
$query->execute();
$result = $query->fetchColumn();
$levelID = ($result ?: 0) + 1;

// add the string 

$lvlfile = "levelData/$levelID";
file_put_contents($lvlfile, $levelData);

// insert

$query = $db->prepare("INSERT INTO levels (levelID, levelName, description, userID, userName, length, officialSong, gameVersion, levelVersion, difficulty, downloads, likes) VALUES ($levelID, '$levelName', '$levelDesc', $uid, '$userName', '$length', '$song', 1, 1, 0, 0, 0)");
$query->execute();
echo $levelID;
?>