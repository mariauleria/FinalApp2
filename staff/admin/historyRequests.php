<?php

require './complement/header.php';

include '../backend/dbaset.php';

$kode_prodi = $_SESSION['curr-user']->user_kode_prodiv;
$query = "
SELECT user_id, book_date, return_date, request_reason, request_status, request_items
FROM (
	SELECT request_id,
	request_date,
	book_date,
	return_date,
	request_reason,
	request_status,
	user_id,
	request_items,
	request_items -> 'items' -> 0 ->> 'category_id' AS category_id
	FROM requests
) temp_table
INNER JOIN assetcategory
ON temp_table.category_id::int = assetcategory.category_id
WHERE asset_kode_prodi = '$kode_prodi' AND (request_status = 'done' OR request_status = 'rejected' OR request_status = 'canceled');
";

$requests = query($query);

?>

<main class="asset-container">
    <div>
        <?php if(!$requests) :?>
            <h2 class="mb-4">No requests.</h2>
        <?php else: ?>
            <h2 class="mb-4">Previous Requests</h2>
            <table class="table text-center" cellpadding="10" cellspacing="0">
                <tr class="row">
                    <th class="col-1">No</th>
                    <th class="col-1">Nama peminjam</th>
                    <th class="col-1">Binusian ID</th>
                    <th class="col-1">Nama barang</th>
                    <th class="col-1">Asset ID</th>
                    <th class="col-1">qty</th>
                    <th class="col-1">Tanggal pinjam</th>
                    <th class="col-1">Tanggal kembali</th>
                    <th class="col-1">Keperluan</th>
                    <th class="col-2">Status peminjaman</th>
                    <th class="col-1">Aksi</th>
                </tr>

                <?php $i = 1; ?>
                <?php foreach($requests as $req) :?>
                    <tr class="row">
                        <td class="col-1"><?= $i; ?></td>
                        <?php 
                            $temp = $req['user_id'];
                            $query = "SELECT username, binusian_id FROM users WHERE user_id = $temp";
                            $user = query($query);
                        ?>
                        <td class="col-1"><?= $user[0]['username'] ?></td>
                        <td class="col-1"><?= $user[0]['binusian_id'] ?></td>
                        <td class="col-1">
                            <?php 
                            $obj = json_decode($req['request_items']);
                            $item = $obj->items;
                            
                            foreach($item as $el){
                                echo "- " . $el->asset_name . "<br>";
                            }
                            ?>
                        </td>
                        <td class="col-1">
                            <?php 
                                foreach($item as $el){
                                    printAssetId($el->asset_id);
                                    echo "<br>";
                                }
                            ?>
                        </td>
                        <td class="col-1">
                            <?php 
                                foreach($item as $el){
                                    echo $el->asset_qty . "<br>";
                                }
                            ?>
                        </td>
                        <td class="col-1"><?= $req['book_date']; ?></td>
                        <td class="col-1"><?= $req['return_date']; ?></td>
                        <td class="col-1"><?= $req['request_reason']; ?></td>
                        <td class="col-2"><?= $req['request_status']; ?></td>
                        <td class="col-1">
                            <!-- TO DO: buat pdf generate receiptnya -->
                            <?php if($req['request_status'] == 'done') :?>
                                <button class="btn btn-primary">Download Receipt</button>
                            <?php elseif($req['request_status'] == 'rejected' || $req['request_status'] == 'canceled') :?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</main>