<?php 

session_start();

if(isset($_SESSION['login']) || isset($_SESSION['login-staff'])){
    echo"
    <script>
        alert('Anda tidak punya akses ke halaman ini!');
        document.location.href = '../';
    </script>
    ";
    exit;
}

if(isset($_SESSION['login-SA'])){
    header("Location: index.php");
    exit;
}

require 'backend/loginFunctionSA.php';

// DONE: fungsi login-nya
if(isset($_POST['login'])){
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
    <title>Superadmin Login</title>

    <link rel="stylesheet" href="../../CSS/style.css">

</head>
<body>
    
    <h1>Superadmin Login</h1>

    <?php if(isset($errorMsg)) : ?>
        <p>username/password salah!</p>
    <?php endif; ?>
    
    <form action="" method="post">
        <ul>
            <li>
                <label for="username">username</label>
                <input type="text" name="username" id="username" required>
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </li>
            <li>
                <button type="submit" name="login">Login</button>
            </li>
        </ul>
    </form>

</body>
</html>