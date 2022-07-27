<?php

session_start();

if(isset($_SESSION['login-staff'])){
    if($_SESSION['curr-user']->user_role == 'Admin'){
        header("Location: admin/index.php");
        exit;
    }
    else if($_SESSION['curr-user']->user_role == 'Approver'){
        header("Location: approver/index.php");
        exit;
    }
    else if($_SESSION['curr-user']->user_role == 'Staff'){
        // TO DO: arahin ke staff/index.php
    }
}


if(isset($_SESSION['login'])){
    echo"
    <script>
        alert('Anda tidak punya akses ke halaman ini!');
        document.location.href = '../';
    </script>
    ";
    exit;
}
else if(isset($_SESSION['login-SA'])){
    echo "
    <script>
        alert('Anda tidak punya akses ke halaman ini!');
        document.location.href = 'superadmin/';
    </script>
    ";
    exit;
}

require './backend/loginFunction.php';

if(isset($_POST['login'])){
    if(login($_POST) == false){
        if($_SESSION['curr-user']->user_role == 'Admin'){
            header("Location: admin/index.php");
            exit;
        }
        else if($_SESSION['curr-user']->user_role == 'Approver'){
            header("Location: approver/index.php");
            exit;
        }
        else if($_SESSION['curr-user']->user_role == 'Staff'){
            // TO DO: arahin ke staff/index.php
        }
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
    <title>Staff Login</title>

    <link rel="stylesheet" href="../CSS/style.css">

</head>
<body>
    
    <h1>Staff Login</h1>

    <?php if(isset($errorMsg)) : ?>
        <p>email/password salah!</p>
    <?php endif; ?>
    
    <form action="" method="post">
        <ul>
            <li>
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required>
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </li>
            <li>
                <button type="submit" name="login">Login</button>
            </li>
            <li>
                <a href="./superadmin/login.php">Login as Superadmin</a>
            </li>
            <li>
                <a href="register.php">Staff Register</a>
            </li>
        </ul>
    </form>

</body>
</html>