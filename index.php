<?php
    include './complement/header.php';

    include 'backend/dbaset.php';

    $user_id = intval($_SESSION['curr-user']->user_id);
    $query = "SELECT * FROM requests WHERE user_id = $user_id";

    $requests = query($query);

    if(isset($_GET['id'])){
        $id = $_GET['id'];

        //DONE: ngecek pas mo cancel request udh di approve atau belum. kalo udh approved gabisa di cancel.
        $query = "SELECT request_status FROM requests WHERE request_id = $id;";
        $query = pg_query($query);
        $query = pg_fetch_assoc($query);
        $query = $query['request_status'];

        if($query == 'waiting approval'){
            $query = "DELETE FROM requests WHERE request_id = $1";
            $statement = pg_prepare($dbconn, "", $query);
            $statement = pg_execute($dbconn, "", array($id));

            if(pg_affected_rows($statement) > 0){
                echo "
                <script>
                    alert('Request berhasil dihapus!');
                    document.location.href = 'index.php';
                </script>
                ";
                exit;
            }
        }
        else{
            echo "
            <script>
                alert('Request sudah di approved admin tidak bisa di cancel!');
                document.location.href = 'index.php';
            </script>
            ";
            exit;
        }
    }
?>

<main class="asset-container">
    <div>
        <?php if(!$requests) :?>
            <h2 class="mb-4">You have no requests.</h2>
        <?php else :?>
            <h2 class="mb-4">Your requests</h2>
            <table class="table" cellpadding="10" cellspacing="0">
                <tr class="row">
                    <th class="col-1">No</th>
                    <th class="col-1">Request</th>
                    <th class="col-2">Book date</th>
                    <th class="col-2">Return date</th>
                    <th class="col-1">Lokasi Pinjam</th>
                    <th class="col-2">Item (qty)</th>
                    <th class="col-1">Status</th>
                    <th class="col-2">Action</th>
                </tr>

                <?php $i = 1; ?>
                <?php foreach($requests as $req) :?>
                    <tr class="row">
                        <td class="col-1"><?= $i; ?></td>
                        <td class="col-1"><?= $req['request_reason']; ?></td>
                        <td class="col-2"><?= $req['book_date']; ?></td>
                        <td class="col-2"><?= $req['return_date']; ?></td>
                        <td class="col-1"><?= $req['lokasi_pinjam'] ?></td>
                        <td class="col-2">
                            <?php 
                                $obj = json_decode($req['request_items']);
                                $items = $obj->items;
                                
                                foreach($items as $item){
                                    echo "- " . $item->asset_name . " (" . $item->asset_qty . ")<br>";
                                }
                            ?>
                        </td>
                        <td class="req-status col-1"><?= $req['request_status']; ?></td>
                        <td class="col-2">
                            <?php if($req['request_status'] == 'waiting approval') : ?>
                                <a class="btn btn-primary" href="index.php?id=<?= $req['request_id'] ?>" onclick="return confirm('Request akan di cancel?');">Cancel</a>
                            <?php elseif($req['request_status'] == 'approved') :?>
                                Silahkan ambil barang sesuai jadwal booking.
                            <?php elseif($req['request_status'] == 'on use') :?>

                                <!-- DONE: buat pdf generate receiptnya -->
                                <form action="backend/fpdf/" method="post">
                                    <button type="submit" name="req_id" value="<?= $req['request_id'] ?>" class="btn btn-primary">Download Receipt</button>
                                </form>

                                <!-- DONE: pengembalian barang -->
                                <!-- DONE: udah dibenerin :D -->
                                <!-- DONE: betulin tanggalnya -_- -->
                                <?php 
                                $a = new DateTime($req['return_date']);
                                $b = new DateTime("now", new DateTimeZone('Asia/Jakarta'));

                                $c = $a->format('m/d/Y');
                                $d = $b->format('m/d/Y');

                                if($d >= $c):
                                    $a = (int)$a->format('His');
                                    $b = (int)$b->format('His');
                                    if($b >= $a):
                                        if(!$req['flag_return']) :?>       
                                            <a class="btn btn-primary" href="kembaliAsset.php?id=<?= htmlspecialchars($req['request_id']) ?>">Kembalikan Barang</a>
                                        <?php else: ?>
                                            <!-- DONE: buat lihat status pengembaliannya -->
                                            <a class="btn btn-primary" href="statusKembali.php?id=<?= htmlspecialchars($req['request_id']) ?>">Lihat Status Pengembalian</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                            <?php elseif($req['request_status'] == 'done'): ?>

                                <!-- DONE: buat pdf generate receiptnya -->
                                <form action="backend/fpdf/" method="post">
                                    <button type="submit" name="req_id" value="<?= $req['request_id'] ?>">Download Receipt</button>
                                </form>

                            <?php elseif($req['request_status'] == 'rejected' || $req['request_status'] == 'canceled') :?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php $i++; ?>
                <?php endforeach;?>
            </table>
        <?php endif;?>
    </div>
</main>

<?php
    include './complement/footer.php';
?>