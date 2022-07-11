<?php

require 'dbaset.php';

$book_date = '';
$return_date = '';

function notAvailable($category_id, $data){

    $result_date = explode(" - ", $data['datetimes']);

    global $book_date;
    $book_date = strtotime($result_date[0]);
    global $return_date;
    $return_date = strtotime($result_date[1]);

    // TO DO: gimana klo asset statusnya unavailable? alias barangnya rusak apakah ttp keitung?

    $query = "SELECT * FROM assets WHERE category_id = $category_id";
    $result = query($query);

    $na = 0;
    $available = true;
    $avail_items = array();

    foreach($result as $res){
        $obj = json_decode($res['asset_booked_date']);

        if($obj){
            $req = $obj->requests;
            foreach($req as $r){
                // echo $r->request_ID . " " . $r->book_date . " " . $r->return_date;
                $test_book_date = strtotime($r->book_date);
                $test_return_date = strtotime($r->return_date);

                if($book_date > $test_return_date || $return_date < $test_book_date){
                    $available = true;
                }
                else{
                    $available = false;
                    $na++;
                    break;
                }
            }
        }
        if($available){
            array_push($avail_items, $res['asset_id']);
        }
    }

    return array($na, $avail_items);
}

function newRequest($data){
    global $dbconn;

    $num_approver = $data['num_approver'];
    $book_date = $data['book-date'];
    $return_date = $data['return-date'];
    $request_reason = sanitize_input($data['request-reason']);
    $request_status = 'waiting approval';
    $user_id = intval($_SESSION['curr-user']->user_id);

    $categories = $data['categories'];
    $asset_qty = $data['asset-qty'];
    $available_items = $data['available-items'];

    // echo '<br>';
    // var_dump($categories);
    // echo '<br>';
    // var_dump($asset_qty);
    // echo '<br>';
    // var_dump($available_items);

    $keys = array();

    for ($i = 0; $i < count($categories); $i++){
        if($asset_qty[$i] == 0){
            array_push($keys, $i);
        }
    }

    // echo '<br><br>';
    // var_dump($keys);

    foreach($keys as $key){
        unset($categories[$key]);
        unset($asset_qty[$key]);
        unset($available_items[$key]);
    }

    $categories = array_values($categories);
    $asset_qty = array_values($asset_qty);
    $available_items = array_values($available_items);

    if(empty($asset_qty)){
        echo "
        <script>
            alert('Masukan qty barang!');
        </script>
        ";
        return false;
    }

    // echo '<br>';
    // var_dump($categories);
    // echo '<br>';
    // var_dump($asset_qty);
    // echo '<br>';
    // var_dump($available_items);

    $asset_name = array();

    foreach($categories as $c_id){
        $query = "SELECT asset_name FROM assetcategory WHERE category_id = $c_id";
        $result = query($query);
        array_push($asset_name, $result[0]['asset_name']);
    }

    $request_items = array("items" => array());

    for($i = 0; $i < count($asset_qty); $i++){

        $available = array();

        if(strlen($available_items[$i]) != 1){
            $available = explode(",", $available_items[$i]);
        }
        else{
            array_push($available, $available_items[$i]);
        }
        $booked_indeks = array_rand($available, $asset_qty[$i]);

        // var_dump($booked_indeks);
        // echo '<br>';

        $booked_items = array();

        if(is_int($booked_indeks)){
            array_push($booked_items, (int)$available[$booked_indeks]);
        }
        else{
            foreach($booked_indeks as $bi){
                array_push($booked_items, (int)$available[$bi]);
            }
        }
        
        // var_dump($booked_items);
        // echo '<br><br>';

        $item = array("category_id" => (int)$categories[$i], "asset_name" => $asset_name[$i], "asset_qty" => (int)$asset_qty[$i], "asset_id" => $booked_items);

        array_push($request_items["items"], $item);
    }

    $fin = json_encode($request_items);
    // echo $fin;
    // echo '<br><br>';

    $query = "INSERT INTO requests(book_date, return_date, request_reason, request_status, user_ID, request_items, num_approver) VALUES ($1, $2, $3, $4, $5, $6, $7);";
    // $query = "INSERT INTO requests(book_date, return_date, request_reason, request_status, user_ID, request_items) VALUES ('$book_date', '$return_date', '$request_reason', '$request_status', $user_id, '$fin');";

    // echo $query;

    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($book_date, $return_date, $request_reason, $request_status, $user_id, $fin, $num_approver));

    return pg_affected_rows($statement);
}


?>