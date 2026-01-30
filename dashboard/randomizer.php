<?php
include "../incl/lib/connection.php";
include "../incl/lib/mainLib.php";
$gs = new mainLib();

echo "<h1>Level Randomizer</h1>";
echo "<hr>";
// get max level id

$query = $db->prepare(
    "SELECT levelID FROM levels ORDER BY levelID DESC LIMIT 1"
);
$query->execute();
$result = $query->fetch();
$maxlevelid = $result["levelID"];

# get level id

$level = rand(4, $maxlevelid);

// get level name

$query = $db->prepare("SELECT levelName FROM levels WHERE levelID=$level");
$query->execute();
$result2 = $query->fetch();
$levelName = $result2["levelName"];

// reroll if the level doesnt exist

while ($levelName === null) {
    $level = rand(4, $maxlevelid);
    $query = $db->prepare("SELECT levelName FROM levels WHERE levelID=$level");
    $query->execute();
    $result2 = $query->fetch();
    $levelName = $result2["levelName"];
}

// get creator

$query = $db->prepare("SELECT userName from levels WHERE levelID=$level");
$query->execute();
$result3 = $query->fetch();
$creator = $result3["userName"];

// get stars

$query = $db->prepare("SELECT rated from levels WHERE levelID=$level");
$query->execute();
$result4 = $query->fetch();
$stars = $result4["rated"];

// get difficulty

$query = $db->prepare(
    "SELECT difficulty, auto, demon from levels WHERE levelID=$level"
);
$query->execute();
$result5 = $query->fetch();
$diffnum = $result5["difficulty"];
$auto = $result5["auto"];
$demon = $result5["demon"];
$diff = $gs->getDiff($diffnum, $auto, $demon);

// echo results

echo "<h2>$levelName by $creator</h2>";
echo "<h3>$stars stars</h3>";
echo "difficulty: $diff";
echo "<h3>id: $level</h3>";
?>
