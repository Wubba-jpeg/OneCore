<?php
// I finally optimized this after months!!
include "../incl/lib/connection.php";
include "../incl/lib/mainLib.php";
$gs = new mainLib();

// filtering
$ratedOnly = isset($_GET['rated']) && $_GET['rated'] == 'on';

echo "<h1>Level Randomizer</h1>";
echo "<hr>";

// get levels
if ($ratedOnly) {
    $query = $db->query("SELECT * FROM levels WHERE rated > 0");
} else {
    $query = $db->query("SELECT * FROM levels");
}
$levels = $query->fetchAll();

// if no levels exist (why would yuon even be here if there were none??)
if (empty($levels)) {
    echo "<h2>No levels found!</h2>";
    exit;
}

// pick a random one
$level = $levels[array_rand($levels)];

// grab the data
$levelName = $level["levelName"];
$creator = $level["userName"] ?? "Unknown";
$stars = $level["rated"] ?? 0;
$levelID = $level["levelID"];
$diff = $gs->getDiff($level["difficulty"], $level["auto"], $level["demon"]);

// show results
echo "<h2>$levelName by $creator</h2>";
if ($stars > 0) {
    echo "<h3>$stars stars</h3>";
} else {
    echo "<h3>unrated</h3>";
}
if ($diff !== "N/A") {
    echo "difficulty: $diff";
}
echo "<h3>id: $levelID</h3>";

// checkbox
echo "<hr>";
echo "<form method='get'>";
echo "<label>";
echo "<input type='checkbox' name='rated' " . ($ratedOnly ? "checked" : "") . " onchange='this.form.submit()'>";
echo " rated only";
echo "</label>";
echo "</form>";
?>
