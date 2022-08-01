<?php

include './complement/header.php';

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

if(!empty($_GET['id'])){
    $asset_id = $_GET['id'];

    $query = "SELECT * FROM assets WHERE asset_id = $asset_id;";
    $result = query($query);
    if(!$result){
        notValid();
    }
    else{
        $result = $result[0];
        $cat_id = $result['category_id'];
        $query = "SELECT asset_name FROM assetcategory WHERE category_id = $cat_id;";
        $result2 = query($query)[0]['asset_name'];
    }
}
else{
    notValid();
}

if(isset($_POST['submit'])){
    require '../backend/editAssetFunction.php';
    if(editAsset($_POST) > 0){
        echo "
        <script>
            alert('Data asset berhasil di update!');
            document.location.href = 'detailAsset.php?category_id=" . $cat_id . "&asset_name=" . $result2 . "';
        </script>
        ";
        exit;
    }
}

?>

<?php if($asset_id) :?>
    <main class="asset-container">
        <h2 class="mb-4">Update Data Asset</h2>

        <form action="" method="post" id="update-asset">
            <div>
                <div class="input-group d-flex my-4">
                    <label class="w-25" for="serial-number">Serial Number</label>
                    <input class="w-75" type="text" name="serial-number" id="serial-number" value="<?= $result['asset_sn'] ?>" required>
                </div>
                <div class="input-group d-flex my-4">
                    <label class="w-25" class="w-25" for="asset-status">Status Asset</label>
                    <?php if($result['asset_status'] == 'on use') : ?>
                        <select class="w-75" name="asset-status" id="asset-status" required>
                            <option value="on use" selected>On Use</option>
                        </select>
                    <?php else: ?>
                        <select class="w-75" name="asset-status" id="asset-status" required>
                            <?php if($result['asset_status'] == 'in storage') :?>
                                <option value="in storage" selected>Available</option>
                                <option value="not available">Not Available</option>
                            <?php elseif($result['asset_status'] == 'not available'): ?>
                                <option value="in storage">Available</option>
                                <option value="not available" selected>Not Available</option>
                            <?php endif; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="input-group d-flex my-4">
                    <input type="hidden" name="curr-location" value="<?= $result['asset_curr_location'] ?>">
                    <label class="w-25" for="storage-location">Storage Location</label>
                    <input class="w-75" type="text" name="storage-location" id="storage-location" value="<?= $result['asset_assigned_location'] ?>" required>
                </div>
                <div class="input-group d-flex my-4">
                    <label class="w-25" for="brand">Brand</label>
                    <input class="w-75" type="text" name="brand" id="brand" value="<?= $result['asset_brand'] ?>" required>
                </div>
                <div>
                    <input type="hidden" name="asset-id" value="<?= $result['asset_id'] ?>">
                    <button class="btn btn-primary btn-lg" type="submit" name="submit">Submit</button>
                </div>
            </div>
        </form>
    </main>
<?php else: notValid(); ?>
<?php endif; ?>