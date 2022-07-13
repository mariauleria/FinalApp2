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
    <main>
        <h2>Update Data Asset</h2>

        <form action="" method="post" id="update-asset">
            <ul>
                <li>
                    <label for="serial-number">Serial Number</label>
                    <input type="text" name="serial-number" id="serial-number" value="<?= $result['asset_sn'] ?>" required>
                </li>
                <li>
                    <label for="asset-status">Status Asset</label>
                    <?php if($result['asset_status'] == 'on use') : ?>
                        <select name="asset-status" id="asset-status" required>
                            <option value="on use" selected>On Use</option>
                        </select>
                    <?php else: ?>
                        <select name="asset-status" id="asset-status" required>
                            <option value="in storage" selected>Available</option>
                            <option value="not available">Not Available</option>
                        </select>
                    <?php endif; ?>
                </li>
                <li>
                    <label for="storage-location">Storage Location</label>
                    <input type="text" name="storage-location" id="storage-location" value="<?= $result['asset_assigned_location'] ?>" required>
                </li>
                <li>
                    <label for="brand">Brand</label>
                    <input type="text" name="brand" id="brand" value="<?= $result['asset_brand'] ?>" required>
                </li>
                <li>
                    <input type="hidden" name="asset-id" value="<?= $result['asset_id'] ?>">
                    <button type="submit" name="submit">Submit</button>
                </li>
            </ul>
        </form>
    </main>
<?php else: notValid(); ?>
<?php endif; ?>