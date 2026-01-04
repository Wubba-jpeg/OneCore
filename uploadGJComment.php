<?php
include "incl/lib/connection.php";
include "incl/lib/mainLib.php";
require_once "incl/lib/injectionlibpatch.php";
$mainLib = new mainLib();

// check secret
if (!isset($_POST["secret"])) {
exit("-1");
}

// get comment data
$comment = injectpatch::clean($_POST["comment"]);
$levelID = injectpatch::number($_POST["levelID"]);
$udid = injectpatch::clean($_POST["udid"]);
$userName = injectpatch::clean($_POST["userName"] ?? $udid); // Use UDID if no username
$percent = isset($_POST["percent"]) ? injectpatch::number($_POST["percent"]) : 0;

// check if comment is empty
if (empty($comment)) {
exit("-1");
}

// get user ID
$userID = $mainLib->userIDFromUDID($udid);
if ($userID == 0) {
$userID = $mainLib->userID($udid, $userName);
}

// insert comment with username
$query = $db->prepare("INSERT INTO comments (UserID, content, LevelID, userName) VALUES (:userID, :comment, :levelID, :userName)");
$query->execute([
':userID' => $userID,
':comment' => $comment,
':levelID' => $levelID,
':userName' => $userName
]);

echo "1";
?>