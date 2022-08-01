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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- External CSS -->
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body class="bodyStyling d-flex flex-column justify-content-center h-100">
    
    <div class="container w-25 text-center containerStyling">

    <img src="../img/logo-binus.png" class="logoBinus"/>

        <h1 class="pb-4">Staff Login</h1>

        <?php if(isset($errorMsg)) : ?>
            <div class="alert alert-danger w-50 m-auto" role="alert">
                <h6>Email / Password Salah!</h6>
            </div>        
        <?php endif; ?>
        
        <form action="" method="post">
            <div>
                <div class="mx-4 mb-3">
                    <div class="text-left">
                        <label class="h5" for="email" >Email</label>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" name="email" id="email" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">@binus.edu</span>
                        </div>
                    </div>
                </div>
                <div class="mx-4">
                    <div class="text-left">                    
                        <label class="h5" for="password">Password</label>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <div>
                        <button class="btn btn-primary btn-lg mt-4 mb-2 w-50" type="submit" name="login">Login</button>
                    </div>
                    <div>
                        <a class="btn btn-secondary my-2 w-50" href="register.php">Staff Register</a>
                    </div>
                    <div>
                        <a class="btn btn-secondary my-2 w-50" href="../login.php">Student Portal</a>
                    </div>
                    <div>
                        <a class="btn btn-secondary mt-2 mb-4 w-50" href="">Login as Superadmin</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>