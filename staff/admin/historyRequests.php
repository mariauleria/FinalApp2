<?php

require './complement/header.php';

include '../backend/dbaset.php';

$kode_prodi = $_SESSION['curr-user']->user_kode_prodiv;
$query = "
SELECT user_id, book_date, return_date, request_reason, request_status, request_items, request_id, lokasi_pinjam
FROM (
	SELECT request_id,
    lokasi_pinjam,
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

<main>
    <div class="requests-container">
        <?php if(!$requests) :?>
            <h2>No requests.</h2>
        <?php else: ?>
            <h2>Previous Requests</h2>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>No</th>
                    <th>Nama peminjam</th>
                    <th>Binusian ID</th>
                    <th>Nama barang</th>
                    <th>Asset ID</th>
                    <th>qty</th>
                    <th>Tanggal pinjam</th>
                    <th>Tanggal kembali</th>
                    <th>Lokasi Peminjaman</th>
                    <th>Keperluan</th>
                    <th>Status peminjaman</th>
                    <th>Aksi</th>
                </tr>

                <?php $i = 1; ?>
                <?php foreach($requests as $req) :?>
                    <tr>
                        <td><?= $i; ?></td>
                        <?php 
                            $temp = $req['user_id'];
                            $query = "SELECT username, binusian_id FROM users WHERE user_id = $temp";
                            $user = query($query);
                        ?>
                        <td><?= $user[0]['username'] ?></td>
                        <td><?= $user[0]['binusian_id'] ?></td>
                        <td>
                            <?php 
                            $obj = json_decode($req['request_items']);
                            $item = $obj->items;
                            
                            foreach($item as $el){
                                echo "- " . $el->asset_name . "<br>";
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                                foreach($item as $el){
                                    printAssetId($el->asset_id);
                                    echo "<br>";
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                                foreach($item as $el){
                                    echo $el->asset_qty . "<br>";
                                }
                            ?>
                        </td>
                        <td><?= $req['book_date']; ?></td>
                        <td><?= $req['return_date']; ?></td>
                        <td><?= $req['lokasi_pinjam'] ?></td>
                        <td><?= $req['request_reason']; ?></td>
                        <td><?= $req['request_status']; ?></td>
                        <td>
                            <!-- DONE: buat pdf generate receiptnya -->
                            <?php if($req['request_status'] == 'done') :?>
                                <form action="../backend/fpdf/" method="post">
                                    <button type="submit" name="req_id" value="<?= $req['request_id'] ?>">Download Receipt</button>
                                </form>
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