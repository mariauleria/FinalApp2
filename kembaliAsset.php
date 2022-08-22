<?php

include './complement/header.php';

include './backend/dbaset.php';

function notValid(){
    echo "
    <script>
        alert('invalid request!');
        document.location.href = 'index.php';
    </script>
    ";
    exit;
}

if(isset($_POST['submit'])){
    // DONE: update return_condition table requests jadi message dari studentnya
    // DONE: update realize_return_date jadi timestamp submit pengembaliannya

    $return_condition = sanitize_input($_POST['kondisi_asset']) . '#' . sanitize_input($_POST['return_condition']);
    $request_id = $_POST['request_id'];

    $query = "UPDATE requests SET return_condition = $1, realize_return_date = CURRENT_TIMESTAMP, flag_return = FALSE WHERE request_id = $2;";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($return_condition, $request_id));

    if(pg_affected_rows($statement) > 0){
        $temp = $_SESSION['curr-user']->user_kode_prodiv;
        $receiver = "SELECT user_email FROM users WHERE user_kode_prodiv = '$temp' AND user_role = 'Admin';";
        $receiver = pg_query($receiver);
        $receiver = pg_fetch_assoc($receiver)['user_email'];

        $subyek = 'PENGAJUAN PENGEMBALIAN BARANG';
        $pesan = $_SESSION['curr-user']->username . ' mengajukan pengembalian barang.';

        if(sendMail($receiver, $subyek, $pesan)){
            echo "
            <script>
                alert('Pengajuan pengembalian dikirim!');
                document.location.href = 'index.php';
            </script>
            ";
            exit;
        }
    }
}

?>

<?php if(!empty($_GET['id'])) : ?>
<main class="asset-container">

<?php

    $request_id = $_GET['id'];
    $query = "SELECT * FROM requests WHERE request_id = $request_id AND request_status = 'on use';";
    $result = query($query);
    if(empty($result)){
        notValid();
    }
    else{
        $result = $result[0];
    }   

?>

    <h2 class="mb-4">Kembali Pinjaman</h2>

    <div>
        <div class="input-group d-flex my-4">
        <label class="w-25">Tanggal pengembalian:</label> <?= $result['return_date'] ?>
        </div>
        <div class="input-group d-flex my-4">
            <?php
                $obj = json_decode($result['request_items']);
                $obj =  $obj->items;
                $i = 1;
            ?>
            <label class="w-25">Barang yang dikembalikan:</label>
            <table class="table w-75" cellpadding="10" cellspacing="0">
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
        <form method="post">
            <div class="input-group d-flex my-4">
                <label class="w-25">Apakah barang dalam kondisi yang baik? </label>
                <div class="w-75">
                    <input type="radio" name="kondisi_asset" id="kondisi_asset" value="aman" required onclick="document.getElementById('return_condition').removeAttribute('required')"/>Ya
                    <input type="radio" name="kondisi_asset" id="kondisi_asset" value="rusak" required onclick="document.getElementById('return_condition').setAttribute('required', 'required')"/>Tidak
                </div>
            </div>
            <div class="input-group d-flex my-4">
            <label class="w-25">Berikan deskripsi keterangan terkait kondisi barang: </label>
                <textarea class="form-control" name="return_condition" id="return_condition" cols="30" rows="5"></textarea>
            </div>
            <div class="input-group d-flex my-4 d-flex">
                <input type="hidden" name="request_id" id="request_id" value="<?= $request_id ?>">
                <button class="btn btn-primary btn-lg" type="submit" name="submit" id="submit">Submit</button>
            </div>
        </form>
    </div>

</main>
<?php else: notValid(); ?>
<?php endif; ?>

<?php
    include './complement/footer.php';
?>