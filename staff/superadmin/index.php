<?php

require 'component/header.php';
require 'backend/dbaset.php';

$query = "SELECT * FROM users WHERE user_role = 'Approver' OR user_role = 'Admin' OR user_role = 'Staff';";
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

<main>
    <h2>BINUS Users</h2>

    <!-- <a href="insertNewDept.php">Add New Department</a> -->

    <?php if(!$result) :?>
        <h2>No Users</h2>
    <?php else: ?>
        <form action="" method="post">
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Binusian ID</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>

                <?php foreach($result as $res) :?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $res['username'] ?></td>
                    <td><?= $res['binusian_id'] ?></td>
                    <td><?= $res['user_email'] ?></td>
                    <td><?= $res['user_phone'] ?></td>
                    <td><?= $res['user_address'] ?></td>
                    <td><?= $res['user_kode_prodiv'] ?></td>
                    <td>
                        <select name="user-role[]" id="user-role">
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
                    <td>
                        <!-- DONE: buat button utk hapus usernya -->
                        <a href="backend/hapusUserFunction.php?id=<?= $res['user_id'] ?>" onclick="return confirm('User akan dihapus?');">Hapus</a>
                    </td>
                </tr>
                <?php $i++; endforeach; ?>
            </table>
            
            <div>
                <button type="submit" name="save">Save</button>
                <button onclick="location.reload();">Cancel</button>
            </div>
        </form>
    <?php endif; ?>
</main>