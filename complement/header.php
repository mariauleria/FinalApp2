<?php

    session_start();

    if(!isset($_SESSION['login'])){
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

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                <li><a class="nav-link" id="" href="./index.php">Dashboard</a></li>
                <li><a class="nav-link" href="./pinjamAsset.php" >Pinjam Aset</a></li>
                <li><a class="nav-link" href="./backend/logoutFunction.php">Logout</a></li>
            </ul>
        </nav>
    </header>