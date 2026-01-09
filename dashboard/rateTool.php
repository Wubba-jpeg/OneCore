<?php
include "../incl/lib/connection.php";
include "../config/pass.php";
require_once "../incl/lib/injectionlibpatch.php";

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
                difficulty = :difficulty
            WHERE levelID = :levelID
        ");

        $stmt->execute([
            ":rated"    => $rated,
            ":featured" => $featured,
            ":demon"    => $demon,
            ":difficulty" => $difficulty,
            ":levelID"  => $levelID
        ]);

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
