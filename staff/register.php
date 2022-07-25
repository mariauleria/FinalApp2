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

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- External CSS -->
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body class="bodyStyling">

    <div class="container w-50 text-center containerStyling">

        <h1 class="pt-5 pb-4">Staff Register</h1>

        <form action="" method="POST">

            <div class="row d-flex text-left">
                <div class="col-6">
                    <label class="h5 mx-4 mb-2" for="username">Name</label>
                    <div class="input-group mb-3 px-4">
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="h5 mx-4 mb-2" for="email">Email</label>
                    <div class="input-group mb-3 px-4">
                        <input type="text" class="form-control" name="email" id="email" required>
                        <div class="input-group-append">
                            <span class="input-group-text" id="basic-addon2">@binus.edu</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row d-flex text-left">
                <div class="col-6">
                    <label class="h5 mx-4 mb-2" for="binusian-id">Binusian ID</label>
                    <div class="input-group mb-3 px-4">
                        <input type="text" class="form-control" name="binusian-id" id="binusian-id" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="h5 mx-4 mb-2" for="phone">Phone Number</label>
                    <div class="input-group mb-3 px-4">
                        <input type="text" class="form-control" name="phone" id="phone" required>
                    </div>
                </div>
            </div>

            <div class="row d-flex text-left">
                <div class="col-6">
                    <label class="h5 mx-4 mb-2" for="address">Home Address</label>
                    <div class="input-group mb-3 px-4">
                        <input type="text" class="form-control" name="address" id="address" required> 
                    </div>
                </div>
                <div class="col-6">
                    <label class="h5 mx-4 mb-2" for="department">Department</label>
                    <div class="input-group mb-3 px-4">
                        <select class="custom-select" name="department" id="department">
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
                    </div>
                </div>
            </div>

            <div class="row d-flex text-left">
                <div class="col-6">
                    <label class="h5 mx-4 mb-2" for="password">Password</label>
                    <div class="input-group mb-3 px-4">
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                </div>
                <div class="col-6">
                    <label class="h5 mx-4 mb-2" for="confirm-password">Confirm Password</label>
                    <div class="input-group mb-3 px-4">
                        <input type="password" class="form-control" name="confirm-password" id="confirm-password" required>
                    </div>  
                </div>
            </div>

            <?php if(isset($errorMsg)): ?>
                <div class="alert alert-danger w-50 m-auto" role="alert">
                    <h6>Data Tidak Sesuai!</h6>
                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-end">
                <a class="btn btn-secondary btn-lg mt-4 mb-5 px-5" href="./login.php">Login Staff</a>
                <button class="btn btn-primary btn-lg mt-4 mb-5 px-5 mx-4" type="submit" name="register">Register</button>
            </div>

        </form>
    </div>

</body>
</html>