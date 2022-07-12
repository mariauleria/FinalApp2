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
                </tr>
                <?php foreach($obj as $o) :?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $o->asset_name ?></td>
                        <td><?= $o->asset_qty ?></td>
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
    </ul>

    </main>
<?php else: notValid(); ?>
<?php endif; ?>