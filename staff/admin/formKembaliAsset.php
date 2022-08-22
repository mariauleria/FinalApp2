<?php

require './complement/header.php';

include '../backend/dbaset.php';

function notValid(){
    echo "
    <script>
        alert('invalid request!');
        document.location.href = 'index.php';
    </script>
    ";
    exit;
}

$request_id = $_GET['id'];

if(isset($_POST['submit'])){

    $query = "SELECT request_items, user_id FROM requests WHERE request_id = $request_id";
    $row = query($query);
    $temp = $row[0]['user_id'];
    $row = json_decode($row[0]['request_items']);

    if($_POST['submit'] == 'approve'){
        // DONE: buat approved nya
        foreach($row->items as $rw){
            $rws = $rw->asset_id;

            $flag = false;
            foreach($rws as $r){
                $query = "UPDATE assets SET asset_status = 'in storage', asset_curr_location = (SELECT asset_assigned_location FROM assets WHERE asset_id = $r) WHERE asset_id = $r;";
                $query = pg_query($dbconn, $query);

                if(pg_affected_rows($query) > 0){
                    $flag = true;
                }
            }
        }
        
        if($flag){
            $query = "UPDATE requests SET request_status = 'done', realize_return_date = CURRENT_TIMESTAMP, flag_return = TRUE WHERE request_id = $1;";
            $statement = pg_prepare($dbconn, "", $query);
            $statement = pg_execute($dbconn, "", array($request_id));

            if(pg_affected_rows($statement) > 0){
                $receiver = "SELECT user_email FROM users WHERE user_id = $temp;";
                $receiver = pg_query($receiver);
                $receiver = pg_fetch_assoc($receiver)['user_email'];

                $subyek = 'PENGEMBALIAN DI APPROVE';
                $pesan = 'Selamat pengembalian anda di approve!';

                if(sendMail($receiver, $subyek, $pesan)){
                    echo "
                    <script>
                        alert('pengembalian di approve!');
                        document.location.href = 'index.php';
                    </script>
                    ";
                    exit;
                }
            }
        }
    }
    else if($_POST['submit'] == 'reject'){
        // DONE: buat rejeted submitnya
        $query = "UPDATE requests SET flag_return = NULL WHERE request_id = $1";
        $statement = pg_prepare($dbconn, "", $query);
        $statement = pg_execute($dbconn, "", array($request_id));

        if(pg_affected_rows($statement) > 0){
            $receiver = "SELECT user_email FROM users WHERE user_id = $temp;";
            $receiver = pg_query($receiver);
            $receiver = pg_fetch_assoc($receiver)['user_email'];

            $subyek = 'PENGEMBALIAN DI REJECT';
            $pesan = 'Mohon maaf pengembalian anda di reject silahkan isi kembali!';

            if(sendMail($receiver, $subyek, $pesan)){
                echo "
                <script>
                    alert('pengembalian di reject!');
                    document.location.href = 'index.php';
                </script>
                ";
                exit;
            }
        }
    }
}

?>

<?php if(!empty($_GET['id'])) :?>
<main class="asset-container">

<?php 

    $query = "SELECT * FROM requests WHERE request_id = $request_id AND request_status = 'on use';";
    $result = query($query);
    if(empty($result)){
        notValid();
    }
    else{
        $result = $result[0];
    }   

?>

    <h2 class="mb-4">Keterangan Kembali Pinjaman</h2>

    <div>
        <div class="input-group d-flex my-4">
            <label class="w-25">Pinjaman nomor:</label> <?= $result['request_id'] ?>
        </div>
        <div class="input-group d-flex my-4">
            <?php
                $temp = $result['user_id'];
                $query = "SELECT username, binusian_id FROM users WHERE user_id = $temp";
                $user = query($query);
            ?>
            <label class="w-25">Peminjam:</label> <?= $user[0]['username'] ?>
        </div>
        <div class="input-group d-flex my-4">
            <label class="w-25">Binusian ID:</label> <?= $user[0]['binusian_id'] ?>
        </div>
        <div class="input-group d-flex my-4">
            <label class="w-25">Periode peminjaman:</label>
            <?= $result['book_date'] . " - " . $result['return_date'] ?>
        </div>
        <div class="input-group d-flex my-4">
            <label class="w-25">Tanggal submit pengembalian:</label> <?= $result['realize_return_date'] ?>
        </div>
        <div class="input-group d-flex my-4">
            <label class="w-25">Keperluan:</label>
            <textarea class="form-control" name="" id="" cols="50" rows="5" readonly><?= $result['request_reason'] ?></textarea>
        </div>
        <div class="input-group d-flex my-4">
            <?php
                $obj = json_decode($result['request_items']);
                $obj =  $obj->items;
                $i = 1;
            ?>
            <label class="w-25">Barang yang dikembalikan:</label>
            <table class="w-75 table" cellpadding="10" cellspacing="0">
                <tr>
                    <th>No.</th>
                    <th>Nama Asset</th>
                    <th>Quantity</th>
                    <th>ID Asset</th>
                </tr>
                <?php foreach($obj as $o) :?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $o->asset_name ?></td>
                        <td><?= $o->asset_qty ?></td>
                        <td><?php foreach($o->asset_id as $ids) echo $ids . ";"; ?></td>
                    </tr>
                <?php $i++; endforeach; ?>
            </table>
        </div>
        <div class="input-group d-flex my-4">
            <label class="w-25">Kondisi barang:</label> 
            <?php 
                $kondisi = explode('#', $result['return_condition']);
                echo $kondisi[0];
            ?>
        </div>
        <div class="input-group d-flex my-4">
            <label class="w-25">Notes pengembalian barang:</label>
            <br>
            <textarea class="form-control" name="" id="" cols="50" rows="5" readonly><?= $kondisi[1] ?></textarea>
        </div>

        <?php if($result['flag_return']) :?>
        <div class="input-group d-flex my-4">
                <form method="post">
                    <input class="btn btn-primary btn-lg" type="submit" value="approve" name="submit">
                    <input class="btn btn-danger btn-lg mx-2" type="submit" value="reject" name="submit">
                </form>
        </div>
        <?php endif; ?>
    </div>

</main>
<?php else: notValid(); ?>
<?php endif; ?>