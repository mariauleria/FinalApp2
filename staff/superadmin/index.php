<?php

require 'component/header.php';
require 'backend/dbaset.php';

$query = "SELECT * FROM prodiv;";
$result = query($query);

$i = 1;
?>

<main class="asset-container">
    <h2 class="mb-4">BINUS User Group</h2>

    <?php if(!$result) :?>
        <h2>No Groups</h2>
    <?php else: ?>
        <table class="table" cellpadding="10" cellspacing="0">
            <tr class="row">
                <th class="col-3">No</th>
                <th class="col-3">Nama Departemen</th>
                <th class="col-3">Jumlah User</th>
                <th class="col-3">Aksi</th>
            </tr>

            <?php foreach($result as $res) :?>
            <tr class="row">
                <td class="col-3"><?= $i ?></td>
                <td class="col-3"><?= $res['kode_prodiv'] ?></td>
                <td class="col-3">
                    <?php 
                    
                    $kode = $res['kode_prodiv'];
                    $query = "SELECT COUNT(*) FROM users WHERE user_kode_prodiv = '$kode' AND (user_role = 'Approver' OR user_role = 'Admin' OR user_role = 'Staff');";
                    $query = pg_query($query);
                    $query = pg_fetch_assoc($query);
                    
                    echo $query['count'];
                    
                    ?>
                </td>
                <td class="col-3"><a class="btn btn-primary" href="./detailGroup.php?code=<?= $res['kode_prodiv'] ?>">Details</a></td>
            </tr>
            <?php $i++; endforeach; ?>
        </table>
    <?php endif; ?>

    <a class="btn btn-primary" href="insertNewDept.php">Add New Department</a>
</main>