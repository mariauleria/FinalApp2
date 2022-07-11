<?php

session_start();

if(isset($_SESSION['login'])){
    header("Location: index.php");
    exit;
}

if(isset($_SESSION['login-staff'])){
    if($_SESSION['curr-user']->user_role == 'Admin'){
        echo"
        <script>
            alert('Anda tidak punya akses ke halaman ini!');
            document.location.href = 'staff/admin/';
        </script>
        ";
        exit;
    }
    else if($_SESSION['curr-user']->user_role == 'Approver'){
        // TO DO: arahin ke approver/index.php
    }
    else if($_SESSION['curr-user']->user_role == 'Staff'){
        // TO DO: arahin ke staff/index.php
    }
}

require './backend/loginFunction.php';

if(isset($_POST["login"])){

    if(login($_POST) == false){
        header("Location: index.php");
        exit;
    }

    $errorMsg = login($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>

    <link rel="stylesheet" href="./CSS/style.css">

</head>
<body>
    
    <h1>Student Login</h1>

    <?php if(isset($errorMsg)) : ?>
        <p>email/password salah!</p>
    <?php endif; ?>
    
    <form action="" method="post">
        <ul>
            <li>
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </li>
            <li>
                <button type="submit" name="login">Login</button>
            </li>
            <li>
                <a href="./staff/login.php">Staff Portal</a>
            </li>
            <li>
                <a href="./register.php">Register</a>
            </li>
        </ul>
    </form>

</body>
</html>