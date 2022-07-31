<?php

session_start();

if(!isset($_SESSION['login-SA'])){
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <div class="user-name">
            <h2 class="greetings">
                Hello <?php echo $_SESSION['curr-user']->username;?>
            </h2>
        </div>

        <nav>
            <ul class="nav-links">
                <li><a class="nav-link" id="" href="index.php">Dashboard</a></li>
                <li><a class="nav-link" href="backend/logoutFunction.php">Logout</a></li>
            </ul>
        </nav>
    </header>
