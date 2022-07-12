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
    if($_POST['submit'] == 'approve'){
        // DONE: buat approved nya

        $query = "SELECT request_items FROM requests WHERE request_id = $request_id";
        $row = query($query);
        $row = json_decode($row[0]['request_items']);
        
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
    else if($_POST['submit'] == 'reject'){
        // DONE: buat rejeted submitnya
        $query = "UPDATE requests SET flag_return = NULL WHERE request_id = $1";
        $statement = pg_prepare($dbconn, "", $query);
        $statement = pg_execute($dbconn, "", array($request_id));

        if(pg_affected_rows($statement) > 0){
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

?>

<?php if(!empty($_GET['id'])) :?>
<main>

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

    <h2>Keterangan Kembali Pinjaman</h2>

    <ul>
        <li>
            Pinjaman nomor: <?= $result['request_id'] ?>
        </li>
        <li>
            <?php
                $temp = $result['user_id'];
                $query = "SELECT username, binusian_id FROM users WHERE user_id = $temp";
                $user = query($query);
            ?>
            Peminjam: <?= $user[0]['username'] ?>
            <br>
            Binusian ID: <?= $user[0]['binusian_id'] ?>
        </li>
        <li>
            Periode peminjaman:
            <br>
            <?= $result['book_date'] . " - " . $result['return_date'] ?>
        </li>
        <li>
            Tanggal submit pengembalian: <?= $result['realize_return_date'] ?>
        </li>
        <li>
            Keperluan:
            <br>
            <textarea name="" id="" cols="50" rows="5" readonly><?= $result['request_reason'] ?></textarea>
        </li>
        <li>
            <?php
                $obj = json_decode($result['request_items']);
                $obj =  $obj->items;
                $i = 1;
            ?>
            Barang yang dikembalikan:
            <table border="1" cellpadding="10" cellspacing="0">
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
        </li>
        <li>
            Kondisi barang: 
            <?php 
                $kondisi = explode('#', $result['return_condition']);
                echo $kondisi[0];
            ?>
        </li>
        <li>
            Notes pengembalian barang:
            <br>
            <textarea name="" id="" cols="50" rows="5" readonly><?= $kondisi[1] ?></textarea>
        </li>

        <?php if($result['flag_return']) :?>
        <li>
                <form method="post">
                    <input type="submit" value="approve" name="submit">
                    <input type="submit" value="reject" name="submit">
                </form>
        </li>
        <?php endif; ?>
    </ul>

</main>
<?php else: notValid(); ?>
<?php endif; ?>