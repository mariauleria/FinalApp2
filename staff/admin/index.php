<?php

require './complement/header.php';

include '../backend/dbaset.php';

$kode_prodi = $_SESSION['curr-user']->user_kode_prodiv;
$query = "
SELECT user_id, book_date, return_date, request_reason, request_status, request_items, request_id, realize_return_date, flag_return
FROM (
	SELECT request_id,
    realize_return_date,
    flag_return,
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
WHERE asset_kode_prodi = '$kode_prodi' AND (request_status = 'waiting approval' OR request_status = 'approved' OR request_status = 'on use');
";

$requests = query($query);

?>

<main>
    <div class="requests-container">
        <?php if(!$requests) :?>
            <h2>No requests.</h2>
        <?php else: ?>
            <h2>Incoming Requests</h2>
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
                        <td><?= $req['request_reason']; ?></td>
                        <td><?= $req['request_status']; ?></td>
                        <td>
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
                                    <input type="submit" name="approve-<?= $req['request_id'] ?>" value="approve">
                                    <input type="submit" name="reject-<?= $req['request_id'] ?>" value="reject">
                                </form>
                            <?php elseif($req['request_status'] == 'approved') :?>
                                <!-- DONE: pengambilan barang -->
                                <?php 
                                $t = 'taken-' . $req['request_id'];
                                if(isset($_POST[$t])){
                                    taken($req['request_id']);
                                }
                                ?>
                                <form method="post">
                                    <button type="submit" name="taken-<?= $req['request_id'] ?>" value="taken">Barang sudah diambil</button>
                                </form>
                            <?php elseif($req['request_status'] == 'on use') :?>

                                <!-- TO DO: buat pdf generate receiptnya -->
                                <button>Download Receipt</button>

                                <!-- DONE: buat lihat keterangan pengembalian -->
                                <?php if($req['flag_return'] == 'f' || (!$req['flag_return'] && $req['realize_return_date'])) :?>
                                    <a href="formKembaliAsset.php?id=<?= htmlspecialchars($req['request_id']) ?>">Form Kembali</a>
                                <?php endif; ?>

                            <?php endif; ?>
                        </td>
                    </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</main>