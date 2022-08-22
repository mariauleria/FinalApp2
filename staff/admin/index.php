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
	FROM requests
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
            <h2>No requests.</h2>
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
                    <!-- TO DO: fahmi -->
                    <th>Lokasi Peminjaman</th>
                    <th class="col-1">Keperluan</th>
                    <th class="col-1">Status peminjaman</th>
                    <th class="col-2">Aksi</th>
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
                        <!-- TO DO: fahmi -->
                        <td><?= $req['lokasi_pinjam'] ?></td>
                        <td class="col-1"><?= $req['request_reason']; ?></td>
                        <td class="col-1"><?= $req['request_status']; ?></td>
                        <td class="col-2">
                            <?php 
                                $a = 'approve-' . $req['request_id'];
                                $r = 'reject-' . $req['request_id'];
                                $flag = $req['track_approver'];

                                if(isset($_POST[$a])){
                                    $flag = approve($req['request_id']);
                                }
                                else if(isset($_POST[$r])){
                                    reject($req['request_id']);
                                }
                            ?>
                            <?php if($req['request_status'] == 'waiting approval') :?>
                                <?php if($flag == 0) :?>
                                    <form method="post">
                                        <input class="btn btn-primary" type="submit" name="approve-<?= $req['request_id'] ?>" value="approve">
                                        <input class="btn btn-primary" type="submit" name="reject-<?= $req['request_id'] ?>" value="reject" onclick="return confirm('request akan direject?');">
                                    </form>
                                <?php elseif($flag == 1) :?>
                                    Waiting other approver.
                                <?php endif; ?>
                            <?php elseif($req['request_status'] == 'approved') :?>
                                <!-- DONE: pengambilan barang -->
                                <?php 
                                $t = 'taken-' . $req['request_id'];
                                if(isset($_POST[$t])){

                                    // DONE: betulin tanggalnya -_-

                                    $a = new DateTime($req['book_date']);
                                    $b = new DateTime("now", new DateTimeZone('Asia/Jakarta'));

                                    $c = $a->format('m/d/Y');
                                    $d = $b->format('m/d/Y');
                                    if($d >= $c){
                                        $a = (int)$a->format('His');
                                        $b = (int)$b->format('His');
                                        if($b >= $a){
                                            taken($req['request_id']);
                                        }
                                        else{
                                            echo "
                                            <script>
                                                alert('Silahkan ambil barang sesuai dengan tanggal booking!');
                                                document.location.href = 'index.php';
                                            </script>
                                            ";
                                            exit;
                                        }
                                    }
                                    else{
                                        echo "
                                        <script>
                                            alert('Silahkan ambil barang sesuai dengan tanggal booking!');
                                            document.location.href = 'index.php';
                                        </script>
                                        ";
                                        exit;
                                    }
                                }
                                ?>
                                <form method="post">
                                    <button class="btn btn-primary"  type="submit" name="taken-<?= $req['request_id'] ?>" value="taken">Barang sudah diambil</button>
                                </form>
                            <?php elseif($req['request_status'] == 'on use') :?>

                                <!-- DONE: buat pdf generate receiptnya -->
                                <form action="../backend/fpdf/" method="post">
                                    <button type="submit" name="req_id" value="<?= $req['request_id'] ?>" class="btn btn-primary" >Download Receipt</button>
                                </form>

                                <!-- DONE: buat lihat keterangan pengembalian -->
                                <?php if($req['flag_return'] == 'f' || (!$req['flag_return'] && $req['realize_return_date'])) :?>
                                    <a class="btn btn-primary" href="formKembaliAsset.php?id=<?= htmlspecialchars($req['request_id']) ?>">Form Kembali</a>
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