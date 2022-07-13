<?php

function editAsset($data){
    global $dbconn;

    $asset_id = $data['asset-id'];
    $serial_number = sanitize_input($data['serial-number']);
    $asset_status = sanitize_input($data['asset-status']);
    $asset_assigned_location = sanitize_input($data['storage-location']);
    $asset_brand = sanitize_input($data['brand']);
    $asset_curr_location = $data['curr-location'];

    if($asset_status != 'on use'){
        $asset_curr_location = $asset_assigned_location;
    }
    
    $query = "UPDATE assets SET asset_sn = $1, asset_status = $2, asset_assigned_location = $3, asset_curr_location = $4, asset_brand = $5 WHERE asset_id = $6;";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($serial_number, $asset_status, $asset_assigned_location, $asset_curr_location, $asset_brand, $asset_id));

    return pg_affected_rows($statement);

}


?>