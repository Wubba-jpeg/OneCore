<?php
include "../incl/lib/connection.php";
require_once "../incl/lib/injectionlibpatch.php";
// you don't need a secret here ozonous!!!

// very secret secret SECRET dont tell anyone!!
// $secret = $_POST["secret"] ?? "Secretlysecretsecret";

// initalizes shit i think i forgot lmfao
$rank = 0;

// i ripped this straight from megasa1nt core :sob:
$iconsRendererServer = 'https://gdicon.oat.zone/icon.png';

// actual getting what this page is for (leaderboard)
$stmt = $db->prepare("SELECT userId, userName, stars, icon, color1, color2 FROM users WHERE stars > 0 ORDER BY stars DESC LIMIT 50");
$stmt->execute();
$users = $stmt->fetchAll();

// loads the hypertext markup language shit
echo "<!DOCTYPE html>";
echo "<html><head><title>Leaderboard</title></head><body>";
echo "<h1>Leaderboard</h1>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Rank</th><th>Username</th><th>Stars</th><th>Icon</th></tr>";

// displays users 
foreach ($users as $user) {
    $rank++;
    $username = htmlspecialchars($user["userName"]);
    $stars = (int)$user["stars"];
    $iconID = (int)$user["icon"];
    $color1 = (int)$user["color1"];
    $color2 = (int)$user["color2"];

    // Render da icon (thanks megasa1nt for including this in the dashboard for ur core)
    $iconURL = $iconsRendererServer . "?type=cube&value={$iconID}&color1={$color1}&color2={$color2}";
    $iconImg = "<img src='{$iconURL}' width='30' height='30'>";

    echo "<tr>";
    echo "<td>{$rank}</td>";
    echo "<td>{$username}</td>";
    echo "<td>{$stars}</td>";
    echo "<td>{$iconImg}</td>";
    echo "</tr>";
}

echo "</table>";
echo "</body></html>";
?>
