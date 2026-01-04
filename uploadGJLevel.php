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
$userName = $_POST["userName"];
$length = $_POST["levelLength"];
$song = $_POST["audioTrack"];


$uid = $gs->userID($udid, $userName);

// insert the level metadata into the database

$query = $db->prepare("INSERT INTO levels (levelName, description, userID, userName, length, officialSong, gameVersion, levelVersion, difficulty, downloads, likes, uploadDate, featured) VALUES ('$levelName', '$levelDesc', $uid, '$userName', '$length', '$song', 1, 1, 0, 0, 0, NOW(), 0)");
$query->execute();

// get the auto generated level id

$levelID = $db->lastInsertId();

// add the level string to a file with the level id

$lvlfile = "levelData/$levelID";
file_put_contents($lvlfile, $levelData);

echo $levelID;
?>echo $levelID;
?>
