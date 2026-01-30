<?php
class mainLib {
public function userID($udid, $username) {
include "connection.php";

$query = $db->prepare("SELECT * FROM users WHERE udid = :udid");
$query->execute([':udid' => $udid]);
$result = $query->fetch();

if ($result === false) {
// new user if they don't exist
$query = $db->prepare("INSERT INTO users (udid, userName) VALUES (:udid, :username)");
$query->execute([':udid' => $udid, ':username' => $username]);
return $db->lastInsertId();
} else {
// update username if it is changed
if ($result['userName'] != $username) {
$query = $db->prepare("UPDATE users SET userName = :username WHERE udid = :udid");
$query->execute([':username' => $username, ':udid' => $udid]);
}
return $result["userId"];
}
}

// get userID from UDID only, don't update username
public function userIDFromUDID($udid) {
include "connection.php";

$query = $db->prepare("SELECT userId FROM users WHERE udid = :udid");
$query->execute([':udid' => $udid]);
$result = $query->fetch();

if ($result === false) {
return 0; // User doesn't exist
} else {
return $result["userId"];
}
}
public function getDiff($diff, $auto, $demon) {
// check if its auto
if ($auto == 1) {
return "auto";
} else if ($demon == 1) {
// check if its demon
return "demon";
} else {
switch ($diff) {
// you know the rest
case 0:
return "N/A";
case 10:
return "Easy";
case 20;
return "Normal";
case 30:
return "Hard";
case 40:
return "Harder";
case 50:
return "Insane";
default:
return "Unknown";
}
}
}
public function getSong($id) {
switch($id){
case 0:
return "Stereo Madness";
case 1:
return "Back On Track";
case 2:
return "Polargeist";
case 3:
return "Dry Out";
case 4:
return "Base After Base";
case 5:
return "Can't Let Go";
case 6:
return "Jumper";
case 7:
return "Time Machine";
case 8:
return "Cycles";
case 9:
return "xStep";
case 10:
return "Clutterfunk";
case 11:
return "Theory Of Everything";
}
}
}
?>
