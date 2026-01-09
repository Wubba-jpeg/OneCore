<?php
// IGNORE MY MESSY CODE PLS :sob:
// includes
include "../incl/lib/dashboardLib.php";
include "../config/dashboard.php";
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=leaderboard" />
<link rel="stylesheet" href="../incl/css/elektrick.css">
<body>
<div class="header">
<h1>
<?php
echo "$gdpsName"
?>

</h1>
</div>
<br><br><br>
<h1 style = "
text-align: center;">
Hello, User!
</h1>
<br>
<a href = "leaderboard.php" style = "
text-decoration: none;">
<div class ="container">
<h1>
<span class="material-symbols-outlined">
leaderboard
</span>
Leaderboard
</h1>
</div>
</a>

</body>
