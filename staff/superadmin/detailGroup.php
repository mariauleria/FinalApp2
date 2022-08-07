<?php

require 'component/header.php';
require 'backend/dbaset.php';

if(!empty($_GET['code'])){
    $kode_prodiv = $_GET['code'];
}
else{
    echo "
    <script>
        alert('invalid requests!');
        document.location.href = 'index.php';
    </script>
    ";
    exit;
}

$query = "SELECT * FROM users WHERE user_kode_prodiv = '$kode_prodiv' AND (user_role = 'Approver' OR user_role = 'Admin' OR user_role = 'Staff');";
$result = query($query);

$i = 1;

if(isset($_POST['save'])){
    if(updateUser($_POST['user-role'], $result)){
        echo "
        <script>
            alert('Data berhasil disimpan!');
            document.location.href = 'index.php';
        </script>
        ";
        exit;
    }
}
?>

<main class="asset-container">
    <h2 class="mb-4"><?= $kode_prodiv ?> Users</h2>

    <?php if(!$result) :?>
        <h2 class="mb-4">No Users</h2>
    <?php else: ?>
        <form action="" method="post">
            <table class="table" cellpadding="10" cellspacing="0">
                <tr class="row">
                    <th class="col-1">No</th>
                    <th class="col-1">Username</th>
                    <th class="col-1">Binusian ID</th>
                    <th class="col-2">Email</th>
                    <th class="col-1">Phone</th>
                    <th class="col-1">Address</th>
                    <th class="col-1">Department</th>
                    <th class="col-2">Role</th>
                    <th class="col-2">Aksi</th>
                </tr>

                <?php foreach($result as $res) :?>
                <tr class="row">
                    <td class="col-1"><?= $i ?></td>
                    <td class="col-1"><?= $res['username'] ?></td>
                    <td class="col-1"><?= $res['binusian_id'] ?></td>
                    <td class="col-2"><?= $res['user_email'] ?></td>
                    <td class="col-1"><?= $res['user_phone'] ?></td>
                    <td class="col-1"><?= $res['user_address'] ?></td>
                    <td class="col-1"><?= $res['user_kode_prodiv'] ?></td>
                    <td class="col-2">
                        <select class="form-select pr-2" name="user-role[]" id="user-role">
                            <?php if($res['user_role'] == 'Approver') :?>
                                <option value="Approver" selected>Approver</option>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                            <?php elseif($res['user_role'] == 'Admin') :?>
                                <option value="Approver">Approver</option>
                                <option value="Admin" selected>Admin</option>
                                <option value="Staff">Staff</option>
                            <?php elseif($res['user_role'] == 'Staff') :?>
                                <option value="Approver">Approver</option>
                                <option value="Admin">Admin</option>
                                <option value="Staff" selected>Staff</option>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td class="col-2">
                        <!-- DONE: buat button utk hapus usernya -->
                        <a class="btn btn-danger"href="backend/hapusUserFunction.php?id=<?= $res['user_id'] ?>" onclick="return confirm('User akan dihapus?');">Hapus</a>
                    </td>
                </tr>
                <?php $i++; endforeach; ?>
            </table>
            
            <div class="d-flex justify-content-end">
                <a class="btn btn-secondary btn-lg mx-3" href="./index.php">Cancel</a>
                <button class="btn btn-primary btn-lg" type="submit" name="save">Save</button>
            </div>
        </form>
    <?php endif; ?>
</main>