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

if(isset($_POST['register'])){

    $_POST['register'] = true;

    require './backend/registerFunction.php';

    if(register($_POST) > 0){
        echo "
        <script>
            alert('Anda sudah terdaftar silahkan login!');
            document.location.href = './login.php';
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
    <title>Staff Register</title>

    <link rel="stylesheet" href="../CSS/style.css">

</head>
<body>
    
    <h1>Staff Register</h1>

    <form action="" method="POST">
        <ul>
            <li>
                <label for="username">Name</label>
                <input type="text" name="username" id="username" required>
            </li>
            <li>
                <label for="binusian-id">Binusian ID</label>
                <input type="text" name="binusian-id" id="binusian-id" required>
            </li>
            <li>
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" required>
            </li>
            <li>
                <label for="address">Home Address</label>
                <input type="text" name="address" id="address" required>
            </li>
            <li>
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required>
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </li>
            <li>
                <label for="confirm-password">Confirm Password</label>
                <input type="password" name="confirm-password" id="confirm-password" required>
            </li>
            <li>
                <label for="department">Department</label>
                <select name="department" id="department">
                    <!-- DONE: iterasi option utk tampilin departmentnya dari database. pake enum aja -->
                    <?php 
                        require './backend/dbaset.php';
                        $query = "SELECT unnest(enum_range(NULL, NULL::prodi));";
                        $result = query($query);

                        foreach($result as $res):
                    ?>
                        <option value="<?= $res['unnest'] ?>"><?= $res['unnest'] ?></option>
                    <?php endforeach; ?>
                </select>
            </li>

            <?php if(isset($errorMsg)): ?>
                <li>
                    <p>Data tidak sesuai!</p>
                </li>
            <?php endif; ?>

            <li>
                <button type="submit" name="register">Register</button>
            </li>
            <li>
                <a href="./login.php">Login Staff</a>
            </li>
        </ul>
    </form>

</body>
</html>