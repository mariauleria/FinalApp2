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
    $query = query($query)[0];
    $cat_id = $query['category_id'];
    $assetBookedID = $query['asset_booked_date'];
    $assetBookedID = json_decode($assetBookedID);

    $allRequestIDs = array();

    $flag = true;

    if($assetBookedID){
        $assetBookedID = $assetBookedID->requests;
        // DONE: ambil semua request ID yg udh ngebook aset yg mo dihapus dimasa depan

        foreach($assetBookedID as $a){

            $now = new DateTime("now", new DateTimeZone('Asia/Jakarta'));
            $book = new DateTime($a->book_date, new DateTimeZone('Asia/Jakarta'));
    
            $c = $now->format('m/d/Y');
            $d = $book->format('m/d/Y');
            if($d > $c){
                array_push($allRequestIDs, $a->request_ID);
            }
            else if($d == $c){
                $now = $now->format('His');
                $book = $book->format('His');
    
                if($book >= $now){
                    array_push($allRequestIDs, $a->request_ID);
                }
            }
        }

        // DONE: terus dari request id itu yg udh didapetin set request_statusnya jadi canceled
        foreach($allRequestIDs as $ids){
            $query = "UPDATE requests SET request_status = 'canceled' WHERE request_id = $1;";
            $query = pg_prepare($dbconn, "", $query);
            $query = pg_execute($dbconn, "", array($ids));
    
            if(pg_affected_rows($query) <= 0){
                $flag = false;
            }
        }
    }

    if($flag == true){

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