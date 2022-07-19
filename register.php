<?php

session_start();

if(isset($_SESSION['login'])){
    header("Location: index.php");
    exit;
}

if(isset($_SESSION['login-staff'])){
    if($_SESSION['curr-user']->user_role == 'Admin'){
        echo "
        <script>
            alert('Anda tidak punya akses ke halaman ini!');
            document.location.href = 'staff/admin/';
        </script>
        ";
        exit;
    }
    else if($_SESSION['curr-user']->user_role == 'Approver'){
        echo "
        <script>
            alert('Anda tidak punya akses ke halaman ini!');
            document.location.href = 'staff/approver/';
        </script>
        ";
        exit;
    }
    else if($_SESSION['curr-user']->user_role == 'Staff'){
        // TO DO: arahin ke staff/index.php
    }
}

if(isset($_POST['register'])){

    $_POST['register'] = true;

    require './backend/registerFunction.php';

    if(register($_POST) > 0){
        echo "
        <script>
            alert('Anda sudah terdaftar silahkan login!');
            document.location.href = 'login.php';
        </script>
        ";
        exit;
    }
    else{
        $errorMsg = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Register</title>

    <link rel="stylesheet" href="./CSS/style.css">

</head>
<body>
    
    <h1>Student Register</h1>

    <form action="" method="POST">
        <ul>
            <li>
                <label for="username">Name</label>
                <input type="text" name="username" id="username">
            </li>
            <li>
                <label for="binusian-id">Binusian ID</label>
                <input type="text" name="binusian-id" id="binusian-id">
            </li>
            <li>
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone">
            </li>
            <li>
                <label for="address">Home Address</label>
                <input type="text" name="address" id="address">
            </li>
            <li>
                <label for="email">Email</label>
                <input type="text" name="email" id="email">
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </li>
            <li>
                <label for="confirm-password">Confirm Password</label>
                <input type="password" name="confirm-password" id="confirm-password">
            </li>
            <li>
                <label for="prodi">Prodi</label>
                <select name="prodi" id="prodi">
                    <option value="DKV">DKV</option>
                    <option value="DI">DI</option>
                    <option value="IT">IT</option>
                </select>
            </li>

            <?php if(isset($errorMsg)): ?>
                <li>
                    <p>Data tidak sesuai!</p>
                </li>
            <?php endif;?>
            
            <li>
                <button type="submit" name="register">Register</button>
            </li>
            <li>
                <a href="./login.php">Login</a>
            </li>
        </ul>
    </form>

</body>
</html>