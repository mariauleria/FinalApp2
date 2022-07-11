<?php

require 'dbaset.php';

function login($data){
    global $dbconn;

    $userEmail = $data['email'] . '@binus.ac.id';
    $userEmail = filter_var(strtolower(sanitize_input($userEmail)), FILTER_SANITIZE_EMAIL);
    $password = pg_escape_string($dbconn, $data['password']);

    $query = "SELECT * FROM users WHERE user_email = $1";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($userEmail));

    $result = pg_num_rows($statement);

    if($result === 1){
        $row = pg_fetch_object($statement);
        if(password_verify($password, $row->user_password)){
            $_SESSION['login'] = true;
            $_SESSION['curr-user'] = $row;

            return false;
        }
    }
    
    return true;
}


?>