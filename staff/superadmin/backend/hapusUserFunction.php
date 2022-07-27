<?php

require 'dbaset.php';

function notValid(){
    echo "
    <script>
        alert('penghapusan gagal!');
        document.location.href = '../index.php';
    </script>
    ";
    exit;
}

function hapusUser($user_id){
    global $dbconn;

    $query = "DELETE FROM users WHERE user_id = $1";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($user_id));

    return pg_affected_rows($statement);
}

if(!empty($_GET['id'])){
    $user_id = sanitize_input($_GET['id']);

    if(hapusUser($user_id) > 0){
        echo "
        <script>
            alert('data berhasil dihapus!');
            document.location.href = '../index.php';
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