<?php
    include './complement/header.php';

    include '../backend/dbaset.php';

    $user_prodi = $_SESSION['curr-user']->user_kode_prodiv;
    $query = "SELECT * FROM assetcategory WHERE asset_kode_prodi = '$user_prodi'";

    $requests = query($query);
?>

<main class="asset-container">
    <div>
        <h2 class="mb-4"><?= $user_prodi . " Assets"; ?></h2>

        <?php if(!$requests) :?>
            <p>Tidak ada asset tersedia silahkan tambahkan asset.</p>
        <?php else: ?>

            <table class=" table w-100" cellpadding="10" cellspacing="0">
                <tr class="row">
                    <th class="col-1">No</th>
                    <th class="col-5">Nama</th>
                    <th class="col-5">Jumlah</th>
                    <th class="col-1">Aksi</th>
                </tr>

                <?php $i = 1; ?>
                <?php foreach($requests as $req) :?>
                    <tr class="row">
                        <td class="col-1"><?= $i; ?></td>
                        <td class="col-5"><?= $req['asset_name']; ?></td>
                        <td class="col-5"><?= $req['asset_qty']; ?></td>
                        <td class="col-1">
                            <?php if($req['asset_qty'] != 0) :?>
                                <a class="btn btn-primary" href="./detailAsset.php?category_id=<?= $req['category_id'] ?>&asset_name=<?= $req['asset_name'] ?>">Detail</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php $i++; ?>
                <?php endforeach;?>
            </table>
            
        <?php endif; ?>
        <div class="d-flex justify-content-end mt-5">        
            <a class="btn btn-primary my-1" href="./insertAsset.php">Tambah Asset</a>
        </div>
    </div>
</main>