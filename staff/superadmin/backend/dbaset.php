<?php

//connecting the database
$dbconn = pg_connect("host=localhost dbname=LabAssetManagement user=postgres password=12345")or die('Could not connect: ' . pg_last_error());

function sanitize_input($text){
    $text = htmlspecialchars($text);
    $text = trim($text);
    $text = stripslashes($text);

    return $text;
}

function query($query){
    global $dbconn;
    $result = pg_query($query);
    $rows = [];
    while($row = pg_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

function updateUser($data, $result){
    $i = 0;
    foreach($result as $res){
        $uid = $res['user_id'];
        $d = $data[$i];
        $query = "UPDATE users SET user_role = '$d' WHERE user_id = $uid;";
        $query = pg_query($query);

        if(pg_affected_rows($query) > 0){
            $i++;
        }
        else{
            $i = 0;
            break;
        }
    }

    if($i > 0){
        return true;
    }
    else{
        return false;
    }
}

//DONE: bikin fungsi add new department
function addNewDept($data){

    $kode_prod = sanitize_input($data['kode-prodiv']);
    $asset = $data['admin'];
    if(isset($data['approver'])){
        $asset = $data['approver'];
    }

    $query = "ALTER TYPE prodi ADD VALUE IF NOT EXISTS '$kode_prod';";
    $query = pg_query($query);

    $query = "INSERT INTO prodiv(kode_prodiv, num_approver) VALUES ('$kode_prod', $asset);";
    $query = pg_query($query);

    return pg_affected_rows($query);
}
?>