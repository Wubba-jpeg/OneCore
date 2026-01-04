<?php
include "incl/lib/connection.php";
include "incl/lib/mainLib.php";
require_once "incl/lib/injectionlibpatch.php";
$gs = new mainLib();

// check secret
if (!isset($_POST["secret"])) {
exit("-1");
}

// level id
$levelID = injectpatch::number($_POST["levelID"]);

// pagination
$page = injectpatch::number($_POST["page"] ?? 0);

// mode: 0 = recent, 2 = most liked
$mode = injectpatch::number($_POST["mode"] ?? 0);

// see if level exists
$check = $db->prepare("SELECT COUNT(*) as count FROM levels WHERE levelID = :levelID");
$check->execute([':levelID' => $levelID]);
if ($check->fetch()["count"] == 0) {
exit("-1");
}

// order by recent or most liked
if ($mode == 2) {
$order = "ORDER BY likes DESC";
} else {
$order = "ORDER BY ID DESC";
}

// offset
$offset = $page * 10;

// get comments - just get username from comments table
$query = $db->prepare("SELECT * FROM comments WHERE LevelID = :levelID $order LIMIT 10 OFFSET $offset");
$query->execute([':levelID' => $levelID]);
$comments = $query->fetchAll();

// build response
$commentstring = "";
$userstring = "";
$users = [];

foreach ($comments as $comment) {
$commentstring .= "2~".$comment["content"]."~3~".$comment["UserID"]."~4~".($comment["likes"] ?? 0)."~5~0~7~0~9~".$comment["timestamp"]."~6~".$comment["ID"]."~10~0";
if ($comment["userName"]) {
if (!in_array($comment["UserID"], $users)) {
$users[] = $comment["UserID"];
$userstring .= $comment["UserID"].":".$comment["userName"].":0|";
}
$commentstring .= "~1~".$comment["userName"]."|";
} else {
$commentstring .= "|";
}
}

$commentstring = rtrim($commentstring, "|");
$userstring = rtrim($userstring, "|");

// get total comments
$totalQuery = $db->prepare("SELECT COUNT(*) as total FROM comments WHERE LevelID = :levelID");
$totalQuery->execute([':levelID' => $levelID]);
$total = $totalQuery->fetch()["total"];

echo $commentstring . "#" . $userstring . "#" . $total . ":" . ($page * 10) . ":" . count($comments);
?>