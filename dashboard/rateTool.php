<?php
include "../incl/lib/connection.php";
require_once "../incl/lib/injectionlibpatch.php";

// !! IMPORTANT !! CHANGE FOR FUCKS SAKE    
$RATE_PASSWORD = "placeholderpassplzchange";

// submits the form stuff
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = $_POST["password"] ?? "";
    $levelID  = (int)($_POST["levelID"] ?? 0);
    $rated    = (int)($_POST["stars"] ?? 0);
    $featured = (int)($_POST["featured"] ?? 0);

    if ($password !== $RATE_PASSWORD) {
        $message = "wrong password!";
    } elseif ($levelID <= 0 || $rated <= 0) {
        $message = "invalid input!!!!!!";
    } else {
        $stmt = $db->prepare("
            UPDATE levels
            SET rated = :rated,
                featured = :featured
            WHERE levelID = :levelID
        ");

        $stmt->execute([
            ":rated"    => $rated,
            ":featured" => $featured,
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
