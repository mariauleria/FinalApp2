<?php

function deleteRequestsData($r_id_arr){
    // var_dump($r_id_arr);
    // echo '<br><br>';

    $flag = -1;

    foreach($r_id_arr as $r){
        global $dbconn;

        $query = "UPDATE requests SET request_status = 'rejected' WHERE request_id = $r;";
        $query = pg_query($query);

        $query = "SELECT request_items FROM requests WHERE request_id = $r;";
        $query = query($query)[0]['request_items'];
        $query = json_decode($query);
        $query = $query->items;

        $ass_id = array();

        foreach($query as $q){
            foreach ($q->asset_id as $ai){
                array_push($ass_id, $ai);
            }
        }
        // echo '<br>';
        // var_dump($ass_id);
        // echo '<br>';

        foreach($ass_id as $i){
            $query = "SELECT asset_booked_date FROM assets WHERE asset_id = $i";
            $result = query($query)[0]['asset_booked_date'];
            $result = json_decode($result);
            $result = $result->requests;

            for($j = count($result)-1; $j >= 0; $j--){
                if($result[$j]->request_ID == $r){
                    unset($result[$j]);
                    break;
                }
            }

            $result = json_encode(array("requests" => $result));
            $query = "UPDATE assets SET asset_booked_date = $1 WHERE asset_id = $2;";
            $statement = pg_prepare($dbconn, "", $query);
            $statement = pg_execute($dbconn, "", array($result, $i));

            if(pg_affected_rows($statement) > 0){
                $flag = pg_affected_rows($statement);
            }

            // var_dump($i); 
            // echo " ";
            // var_dump($result);
            // echo '<br>';
        }
    }

    return $flag;
}

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

        if($asset_status == 'not available'){
            $query = "SELECT * FROM assets WHERE asset_id = $asset_id;";
            $result = query($query)[0];
            $result = $result['asset_booked_date'];
            $result = json_decode($result);
            $result = $result->requests;

            $r_id_arr = array();

            for($i = count($result)-1; $i >= 0; $i--){
                // var_dump($result[$i]->book_date);
                // echo '<br>';

                $a = new DateTime($result[$i]->book_date, new DateTimeZone('Asia/Jakarta'));
                $b = new DateTime("now", new DateTimeZone('Asia/Jakarta'));

                $c = $a->format('m/d/Y');
                $d = $b->format('m/d/Y');
                if($c > $d){
                    array_push($r_id_arr, $result[$i]->request_ID);
                }
                else if($c == $d){
                    $a = (int)$a->format('His');
                    $b = (int)$b->format('His');

                    if($a > $b){
                        array_push($r_id_arr, $result[$i]->request_ID);
                    }
                }
            }
            deleteRequestsData($r_id_arr);
        }
    }
    
    $query = "UPDATE assets SET asset_sn = $1, asset_status = $2, asset_assigned_location = $3, asset_curr_location = $4, asset_brand = $5 WHERE asset_id = $6;";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($serial_number, $asset_status, $asset_assigned_location, $asset_curr_location, $asset_brand, $asset_id));

    return pg_affected_rows($statement);

}


?>