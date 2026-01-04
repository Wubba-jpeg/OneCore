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
}
?>