<?php
session_start();
if (!isset($_SESSION['loggedIn'])) {
    header("Location: ./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom! | M3T</title>
</head>

<body>
    <div class="container">
        <h1>M3T</h1>
        <div class="short-info">This is the landing page!</div>
        <div class="short-info">If you see this then you have succesfuly logged in.</div>
    </div>
</body>

</html>