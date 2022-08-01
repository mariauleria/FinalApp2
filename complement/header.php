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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- External CSS -->
    <link rel="stylesheet" href="CSS/style.css" />
</head>
<body >
    <header class="headerBodyStyling">
        <div class="user-name mx-5 py-4">
            <h1 class="greetings">
                Hello, <?php echo $_SESSION['curr-user']->username;?>
            </h1>
        </div>

        <div class="pageContainer ">
            <nav class="w-100">
                <ul class="nav-links d-flex">
                    <li class="nav-link"><a href="./index.php">Dashboard</a></li>
                    <li class="nav-link"><a href="./pinjamAsset.php">Pinjam Asset</a></li>
                    <li class="nav-link ml-auto"><a class="text-danger" href="./backend/logoutFunction.php">Logout</a></li>
                </ul>
            </nav>
    </header>