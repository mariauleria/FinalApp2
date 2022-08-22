<?php

session_start();

if(!isset($_SESSION['login-staff'])){
    header("Location: ../login.php");
    exit;
}
else{
    if($_SESSION['curr-user']->user_role == 'Approver'){
        echo "
        <script>
            alert('Anda tidak punya akses ke halaman ini!');
            document.location.href = '../approver/';
        </script>
        ";
        exit;
    }
    elseif($_SESSION['curr-user']->user_role == 'Staff'){
        // TO DO: arahin ke staff/index.php
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- External CSS -->
    <link rel="stylesheet" href="../../CSS/style.css" />
</head>
<body>
    <header class="headerBodyStyling">
        <div class="user-name mx-5 py-4">
            <h2 class="greetings">
                Hello, <?php echo $_SESSION['curr-user']->username;?>
            </h2>
        </div>

        <div class="pageContainer ">
            <nav class="w-100">
                <ul class="nav-links d-flex">
                    <li class="nav-link"><a href="./index.php">Dashboard</a></li>
                    <li class="nav-link"><a href="./searchAsset.php">Cari Aset</a></li>
                    <li class="nav-link"><a href="./historyRequests.php">History</a></li>
                    <li class="nav-link ml-auto"><a class="text-danger" href="../backend/logoutFunction.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>