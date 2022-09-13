<?php

require './complement/header.php';

include '../backend/dbaset.php';

$kode_prodi = $_SESSION['curr-user']->user_kode_prodiv;
$query = "
SELECT user_id, book_date, return_date, request_reason, request_status, request_items, request_id, realize_return_date, flag_return, track_approver, lokasi_pinjam
FROM (
	SELECT request_id,
    lokasi_pinjam,
    realize_return_date,
    flag_return,
    track_approver,
	request_date,
	book_date,
	return_date,
	request_reason,
	request_status,
	user_id,
	request_items,
	request_items -> 'items' -> 0 ->> 'category_id' AS category_id
	FROM requests WHERE track_approver > 0
) temp_table
INNER JOIN assetcategory
ON temp_table.category_id::int = assetcategory.category_id
WHERE asset_kode_prodi = '$kode_prodi' AND (request_status = 'waiting approval' OR request_status = 'approved' OR request_status = 'on use');
";

$requests = query($query);

?>

<main class="asset-container">
    <div>
        <?php if(!$requests) :?>
            <h2 class="mb-4">No requests.</h2>
        <?php else: ?>
            <h2 class="mb-4">Incoming Requests</h2>
            <table class="table" cellpadding="10" cellspacing="0">
                <tr class="row">
                    <th class="col-1">No</th>
                    <th class="col-1">Nama peminjam</th>
                    <th class="col-1">Binusian ID</th>
                    <th class="col-1">Nama barang</th>
                    <th class="col-1">Asset ID</th>
                    <th class="col-1">qty</th>
                    <th class="col-1">Tanggal pinjam</th>
                    <th class="col-1">Tanggal kembali</th>
                    <th class="col-1">Lokasi Peminjaman</th>
                    <th class="col-1">Keperluan</th>
                    <th class="col-1">Status peminjaman</th>
                    <th class="col-1" style="max-width:8rem;">Aksi</th>
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
                        <td class="col-1"><?= $req['lokasi_pinjam'] ?></td>
                        <td class="col-1"><?= $req['request_reason']; ?></td>
                        <td class="col-1"><?= $req['request_status']; ?></td>
                        <td class="col-1" style="max-width:8rem;">
                            <?php 
                                $a = 'approve-' . $req['request_id'];
                                $r = 'reject-' . $req['request_id'];

                                if(isset($_POST[$a])){
                                    approve($req['request_id']);
                                }
                                else if(isset($_POST[$r])){
                                    reject($req['request_id']);
                                }
                            ?>
                            <?php if($req['request_status'] == 'waiting approval') :?>
                                <form method="post">
                                    <input class="btn btn-primary" type="submit" name="approve-<?= $req['request_id'] ?>" value="approve">
                                    <input class="btn btn-primary" type="submit" name="reject-<?= $req['request_id'] ?>" value="reject" onclick="return confirm('request akan direject?');">
                                </form>
                            <?php elseif($req['request_status'] == 'approved') :?>
                                -
                            <?php elseif($req['request_status'] == 'on use') :?>

                                <!-- DONE: buat pdf generate receiptnya -->
                                <form action="../backend/fpdf/" method="post">
                                    <button type="submit" name="req_id" value="<?= $req['request_id'] ?>" class="btn btn-primary">Download Receipt</button>
                                </form>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</main>