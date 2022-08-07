<?php 

require './complement/header.php';

include './backend/dbaset.php';

$request_id = $_GET['id'];

function notValid(){
    echo "
    <script>
        alert('invalid request!');
        document.location.href = 'index.php';
    </script>
    ";
    exit;
}

?>


<?php if(!empty($_GET['id'])) : ?>
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
            <br>
            <textarea class="w-75 form-control" name="" id="" cols="50" rows="5" readonly><?= $result['request_reason'] ?></textarea>
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
                </tr>
                <?php foreach($obj as $o) :?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $o->asset_name ?></td>
                        <td><?= $o->asset_qty ?></td>
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
            <textarea class="w-75 form-control" name="" id="" cols="50" rows="5" readonly><?= $kondisi[1] ?></textarea>
        </div>
    </div>

    </main>
<?php else: notValid(); ?>
<?php endif; ?>