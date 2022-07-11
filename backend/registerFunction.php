<?php

require 'dbaset.php';

function register($data){

    foreach($data as $el){
        if(empty($el)){
            return false;
        }
    }

    global $dbconn;

    $username = strtolower(sanitize_input($data['username']));
    $binusianid = sanitize_input($data['binusian-id']);
    $userEmail = $data['email'] . '@binus.ac.id';
    $userEmail = filter_var(strtolower(sanitize_input($userEmail)), FILTER_SANITIZE_EMAIL);
    $userPhone = filter_var(sanitize_input($data['phone']), FILTER_SANITIZE_NUMBER_INT);
    $userAddress = sanitize_input($data['address']);
    $userProdi = $data['prodi'];
    $userRole = "Student";
    $password = pg_escape_string($dbconn, $data['password']);
    $confirmPassword = pg_escape_string($dbconn, $data["confirm-password"]);

    $query = "SELECT user_email FROM users WHERE user_email = $1;";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($userEmail));

    $result = pg_fetch_assoc($statement);

    if($result){
        echo "
        <script>
            alert('email sudah terdaftar silahkan login!');
            document.location.href = 'login.php';
        </script>
        ";
        exit;
    }

    if($password !== $confirmPassword){
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users(username, binusian_id, user_email, user_password, user_phone, user_address, user_kode_prodiv, user_role) VALUES ($1, $2, $3, $4, $5, $6, $7, $8);";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($username, $binusianid, $userEmail, $password, $userPhone, $userAddress, $userProdi, $userRole));

    return pg_affected_rows($statement);
}

?>