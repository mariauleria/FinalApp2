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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- External CSS -->
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body class="bodyStyling">
    
    <div class="container w-25 text-center containerStyling">

        <img src="img/logo-binus.png" class="logoBinus"/>

        <h1 class="pb-4">Student Login</h1>

        <?php if(isset($errorMsg)) : ?>
            <div class="alert alert-danger w-50 m-auto" role="alert">
                <h6>Email / Password Salah!</h6>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div>
                <div class="text-left">
                    <label class="h5 mx-4 mb-2" for="email" >Email</label>
                </div>
                <div class="input-group mb-3 px-4">
                    <input type="text" class="form-control" name="email" id="email">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">@binus.ac.id</span>
                    </div>
                </div>
                <div class="text-left">                    
                    <label class="h5 mx-4 mb-2 mt-2" for="password">Password</label>
                </div>
                <div class="input-group mb-3 px-4">
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div>
                    <button class="btn btn-primary btn-lg mt-4 w-50" type="submit" name="login">Login</button>
                </div>
                <div>
                    <a class="btn btn-secondary mt-3 w-50" href="./register.php">Register</a>
                </div>
                <div>
                    <a class="btn btn-secondary mt-3 w-50 mb-5" href="./staff/login.php">Staff Portal</a>
                </div>
            </div>
        </form>
    </div>
    
</body>
</html>