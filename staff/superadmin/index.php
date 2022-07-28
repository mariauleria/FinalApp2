<?php

require 'component/header.php';
require 'backend/dbaset.php';

$query = "SELECT * FROM prodiv;";
$result = query($query);

$i = 1;
?>

<main>
    <h2>BINUS User Group</h2>

    <a href="insertNewDept.php">Add New Department</a>

    <?php if(!$result) :?>
        <h2>No Groups</h2>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama Departemen</th>
                <th>Jumlah User</th>
                <th>Aksi</th>
            </tr>

            <?php foreach($result as $res) :?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $res['kode_prodiv'] ?></td>
                <td>
                    <?php 
                    
                    $kode = $res['kode_prodiv'];
                    $query = "SELECT COUNT(*) FROM users WHERE user_kode_prodiv = '$kode' AND (user_role = 'Approver' OR user_role = 'Admin' OR user_role = 'Staff');";
                    $query = pg_query($query);
                    $query = pg_fetch_assoc($query);
                    
                    echo $query['count'];
                    
                    ?>
                </td>
                <td><a href="./detailGroup.php?code=<?= $res['kode_prodiv'] ?>">Details</a></td>
            </tr>
            <?php $i++; endforeach; ?>
        </table>
    <?php endif; ?>
</main>