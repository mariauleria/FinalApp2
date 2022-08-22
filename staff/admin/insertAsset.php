<?php

include './complement/header.php';

include '../backend/dbaset.php';

$kode = $_SESSION['curr-user']->user_kode_prodiv;
$query = "SELECT * FROM assetcategory WHERE asset_kode_prodi = '$kode';";
$result = query($query);

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


<main class="asset-container">
    <h2 class="mb-4">Tambah Asset Baru</h2>

    <form action="" method="post" id="tambah-asset">
        <div>
            <div class="input-group d-flex my-4">
                <label class="w-25" for="serial-number">Serial Number</label>
                <input class="w-75" type="text" class="form-control" name="serial-number" id="serial-number">
            </div>
            <div class="input-group d-flex my-4">
                <label class="w-25" for="storage-location">Storage Location</label>
                <input class="w-75" type="text" name="storage-location" id="storage-location" required>
            </div>
            <div class="input-group d-flex my-4">
                <label class="w-25" for="brand">Brand</label>
                <input class="w-75" type="text" name="brand" id="brand">
            </div>
            <div class="button-group d-flex my-4" data-toggle="buttons">
            <label class="w-25">Asset Type</label>
                <div class="w-75">
                <?php 
                // DONE: fix kalau belum ada aset nya sama sekali
                
                if($result) :
                    //DONE: fix kenapa ini gamau muncul asset category ke 2 dan 3 nya
                    foreach($result as $res) :?>
                        <input type="radio" name="asset-category" id="default" value="<?= $res['category_id'] ?>"><?= $res['asset_name'] ?><br>
                    <?php endforeach; ?>
                <?php endif; ?>
                <input type="radio" name="asset-category" value="">
                <label>Tambah Type Aset Baru</label>
                </div>
            </div>
            <div class="w-100" id="new-asset"></div>

            <?php if(isset($errorMsg)) :?>
                <div>
                    <p>Storage location & Asset Type harus diisi.</p>
                </div>
            <?php endif; ?>

            <div>
                <button class="btn btn-primary btn-lg" type="submit" name="submit">Submit</button>
            </div>
        </div>
    </form>

    <script>
        function newAssetField(id){
            const element = document.getElementById(id);
            element.innerHTML = "<label class='w-25 mb-5' for='new-asset-category'>Nama Type Asset Baru </label><input class='w-75' type='text' name='new-asset-category' id='new-asset-category'>";
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