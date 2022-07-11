<?php

function validate($data){

    if(empty($data['asset-category'])){
        if(!isset($data['new-asset-category'])){
            return true;
        }
        return false;
    }
    return false;

}

function insertAsset($data){

    if(empty($data['storage-location']) || validate($data)){
        return false;
    }

    global $dbconn;

    $asset_sn = sanitize_input($data['serial-number']);
    $asset_assigned_location = sanitize_input($data['storage-location']);
    $asset_curr_location = $asset_assigned_location;
    $asset_brand = sanitize_input($data['brand']);
    $asset_status = 'in storage';
    $asset_category = '';
    $kode_prodi = $_SESSION['curr-user']->user_kode_prodiv;

    if(empty($data['asset-category'])){
        $asset_category = sanitize_input($data['new-asset-category']);

        $query = "SELECT num_approver FROM prodiv WHERE kode_prodiv = '$kode_prodi'";
        $statement = query($query);
        $num_approver = $statement[0]['num_approver'];

        $query = "INSERT INTO assetcategory(asset_name, asset_qty, asset_kode_prodi, num_approver) VALUES ($1, $2, $3, $4); ";
        $statement = pg_prepare($dbconn, "", $query);
        $statement = pg_execute($dbconn, "", array($asset_category, 0, $kode_prodi, $num_approver));

        $query = "SELECT category_id FROM assetcategory WHERE asset_name = '$asset_category'";
        $temp = pg_query($query);
        $result = pg_fetch_assoc($temp);
        $asset_category = $result['category_id'];
    }
    else{
        $asset_category = $data['asset-category'];
    }

    $query = "INSERT INTO assets(asset_SN, asset_status, asset_assigned_location, asset_curr_location, asset_brand, category_id) VALUES ($1, $2, $3, $4, $5, $6);";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($asset_sn, $asset_status, $asset_assigned_location, $asset_curr_location, $asset_brand, $asset_category));

    //uda di insert di update tambahin asset_qty nya
    $query = "UPDATE assetcategory SET asset_qty = (SELECT asset_qty FROM assetcategory WHERE category_id = $asset_category) + 1 WHERE category_id = $asset_category;";
    $temp = pg_query($query);

    return pg_affected_rows($statement);

}

?>