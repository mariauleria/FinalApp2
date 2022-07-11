<?php
    include './complement/header.php';

    include '../backend/dbaset.php';

    $user_prodi = $_SESSION['curr-user']->user_kode_prodiv;
    $query = "SELECT * FROM assetcategory WHERE asset_kode_prodi = '$user_prodi'";

    $requests = query($query);
?>

<main>
    <div class="asset-container">
        <h2><?= $user_prodi . " Assets"; ?></h2>
        <a href="./insertAsset.php">Tambah Asset</a>

        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>

            <?php $i = 1; ?>
            <?php foreach($requests as $req) :?>
                <tr>
                    <td><?= $i; ?></td>
                    <td><?= $req['asset_name']; ?></td>
                    <td><?= $req['asset_qty']; ?></td>
                    <td><a href="./detailAsset.php?category_id=<?= $req['category_id'] ?>&asset_name=<?= $req['asset_name'] ?>">Detail</a></td>
                </tr>
            <?php $i++; ?>
            <?php endforeach;?>
        </table>
    </div>
</main>