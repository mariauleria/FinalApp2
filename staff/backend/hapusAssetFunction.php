<?php

require 'dbaset.php';

function notValid(){
    echo "
    <script>
        alert('penghapusan gagal!');
        document.location.href = '../admin/searchAsset.php';
    </script>
    ";
    exit;
}

function hapusAsset($asset_id){
    global $dbconn;

    $query = "SELECT * FROM assets WHERE asset_id = $asset_id AND (asset_status = 'in storage' OR asset_status = 'not available');";

    if(query($query)){
        $cat_id = query($query)[0]['category_id'];

        $query = "DELETE FROM assets WHERE asset_id = $asset_id AND (asset_status = 'in storage' OR asset_status = 'not available');";
        $query = pg_query($query);

        if(pg_affected_rows($query) > 0){
            $query = "UPDATE assetcategory SET asset_qty = (SELECT asset_qty FROM assetcategory WHERE category_id = $cat_id) - 1 WHERE category_id = $cat_id";
            $query = pg_query($query);

            return pg_affected_rows($query);
        }
    }
    else{
        notValid();
    }
}

if(!empty($_GET['id'])){
    $asset_id = $_GET['id'];

    if(hapusAsset($asset_id) > 0){
        echo "
        <script>
            alert('data berhasil dihapus!');
            document.location.href = '../admin/searchAsset.php';
        </script>
        ";
        exit;
    }
    else{
        notValid();
    }
}
else{
    notValid();
}

?>