<?php
include "../config/discord.php";
include "../incl/lib/connection.php";
include "../config/pass.php";
include "../incl/lib/mainLib.php";
include "../config/misc.php";
require_once "../incl/lib/injectionlibpatch.php";
require "../incl/lib/DiscordWebhook.php";

$gs = new mainLib();
$dw = new DiscordWebhook($webhook);
// submits the form stuff
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = $_POST["password"] ?? "";
    $levelID  = injectpatch::number($_POST["levelID"] ?? 0);
    $rated    = injectpatch::number($_POST["stars"] ?? 0);
    $featured = injectpatch::number($_POST["featured"] ?? 0);

    // convert to integers, extra safety ig
    $levelID = (int)$levelID;
    $rated = (int)$rated;
    $featured = (int)$featured;

if ($rated == 10) {
    $demon = 1;
} else {
    $demon = 0;
}

if ($rated == 1) {
    $auto = 1;
} else {
    $auto = 0;
}

// set difficulty based on stars
if ($rated == 2) {
    $difficulty = 10;
} elseif ($rated == 3) {
    $difficulty = 20;
} elseif ($rated == 4) {
    $difficulty = 30;
} elseif ($rated == 5) {
    $difficulty = 40;
} elseif ($rated >= 6 && $rated <= 10) {
    $difficulty = 50;
} else {
    $difficulty = 0;
}

    if ($password !== $RATE_PASSWORD) {
        $message = "wrong password!";
    } elseif ($levelID <= 0 || $rated <= 0 || $rated > 10) {
        $message = "invalid input!!!!!!";
    } else {
        $stmt = $db->prepare("
            UPDATE levels
            SET rated = :rated,
                featured = :featured,
                demon = :demon,
                auto = :auto,
                difficulty = :difficulty
            WHERE levelID = :levelID
        ");

        $stmt->execute([
            ":rated"    => $rated,
            ":featured" => $featured,
            ":demon"    => $demon,
            ":auto"     => $auto,
            ":difficulty" => $difficulty,
            ":levelID"  => $levelID
        ]);
// get featured status

  if ($featured == 0) {
    $fe = "Star Rate";
  } else if ($featured == 1) {
    $fe = "Featured";
  }

// get levels difficulty face

   if ($demon == 1) {
    $diffimg = "images/$featured/demon.png";
  } else if ($auto == 1) {
    $diffimg = "images/$featured/auto.png";
  } else {

if ($difficulty == 10) {
    $diffimg = "images/$featured/easy.png";
  } else if ($difficulty == 20) {
    $diffimg = "images/$featured/normal.png";
  } else if ($difficulty == 30) {
    $diffimg = "images/$featured/hard.png";
  } else if ($difficulty == 40) {
    $diffimg = "images/$featured/harder.png";
  } else if ($difficulty == 50) {
    $diffimg = "images/$featured/insane.png";
  }
} 
 
// get level name

  $query = $db->prepare("SELECT levelName FROM levels WHERE levelID=:levelID");
  $query->execute([":levelID" => $levelID]);
  $result = $query->fetch();
  $levelName =$result['levelName'];

// get creators name

  $query = $db->prepare("SELECT userName from levels WHERE levelID=:levelID"); 
  $query->execute([":levelID" => $levelID]);
  $result2 = $query->fetch();
  $creator =$result2['userName'];

// get difficulty
 
  $diff =$gs->getDiff($difficulty, $auto, $demon);

// send webhook
if ($webhooks === true) {

  $msg = $dw
    ->newMessage()
    ->setContent("$cont")
    ->setTitle("New Level Rated!")
    ->setThumbnail("https://chrns.elementfx.com/mоԁtοоӏʂ/$diffimg")
    ->setDescription("# $levelName by $creator was rated!
** **
⭐ **Stars**: $rated
🏆 **Rate Type**: $featured
🔢 **Level ID**: $levelID
🎮 **Difficulty**: $diff")
    ->setColor("#fffd7a")
    ->send();
} 
// run cron if activated
if ($autocron === true) {
include "cron.php";
echo "Cron Ran Automatically!<br>";
} else {
echo "Cron hasn't been ran automatically. either do it manually or activate it in misc.php.<br>";
}
  
  echo "<hr>";

        $message = "you rated the levels";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>rate tool</title>
</head>
<body>

<h2>rating tool</h2>

<?php if (isset($message)) echo "<p>$message</p>"; ?>

<form method="post">
    <div>
        <label>password</label><br>
        <input type="password" name="password">
    </div>

    <div>
        <label>level id</label><br>
        <input type="number" name="levelID">
    </div>

    <div>
        <label>stars</label><br>
        <input type="number" name="stars" min="1" max="10">
    </div>

    <div>
        <label>featured put 0 or 1</label><br>
        <input type="number" name="featured" min="0" max="1">
    </div>

    <br>
    <button type="submit">rate the level</button>
</form>

</body>
</html>
