<?php

include './complement/header.php';

include '../backend/dbaset.php';

$kode = $_SESSION['curr-user']->user_kode_prodiv;
$query = "SELECT * FROM assetcategory WHERE asset_kode_prodi = $1";
$result = pg_prepare($dbconn, "", $query);
$result = pg_execute($dbconn, "", array($kode));
$result = pg_fetch_assoc($result);

if(isset($_POST['submit'])){

    require '../backend/insertAssetFunction.php';

    if(insertAsset($_POST) > 0){
        echo "
        <script>
            alert('Asset sudah ditambahkan!');
            document.location.href = './searchAsset.php';
        </script>
        ";
    }
    else{
        $errorMsg = true;
    }
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


<main>

    <h2>Tambah Asset Baru</h2>

    <form action="" method="post" id="tambah-asset">
        <ul>
            <li>
                <label for="serial-number">Serial Number</label>
                <input type="text" name="serial-number" id="serial-number">
            </li>
            <li>
                <label for="storage-location">Storage Location</label>
                <input type="text" name="storage-location" id="storage-location" required>
            </li>
            <li>
                <label for="brand">Brand</label>
                <input type="text" name="brand" id="brand">
            </li>
            <li class="button-group" data-toggle="buttons">
                Asset Type <br>
                <?php 
                // DONE: fix kalau belum ada aset nya sama sekali
                if($result) :
                    $results = array();
                    array_push($results, $result);

                    foreach($results as $res) :?>
                        <input type="radio" name="asset-category" id="default" value="<?= $res['category_id'] ?>"><?= $res['asset_name'] ?><br>
                    <?php endforeach; ?>
                <?php endif; ?>
                    <input type="radio" name="asset-category" value="">Tambah Type Aset Baru
                <div id="new-asset"></div>
            </li>

            <?php if(isset($errorMsg)) :?>
                <li>
                    <p>Storage location & Asset Type harus diisi.</p>
                </li>
            <?php endif; ?>

            <li>
                <button type="submit" name="submit">Submit</button>
            </li>
        </ul>
    </form>

    <script>
        function newAssetField(id){
            const element = document.getElementById(id);
            element.innerHTML = "<label for='new-asset-category'>Nama Type Asset Baru </label><input type='text' name='new-asset-category' id='new-asset-category'>";
        }

        $('#tambah-asset input[value=""]').on('change', function(event) {
            $('#new-asset').html(
                newAssetField('new-asset')
            );
        })

        $('#tambah-asset input[id=default]').on('change', function(event) {
            $('#new-asset').html("");
        })
    </script>

</main>