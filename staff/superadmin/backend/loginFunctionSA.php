<?php

require 'dbaset.php';

function login($data){
    global $dbconn;

    $username = strtolower(sanitize_input($data['username']));
    $password = pg_escape_string($dbconn, $data['password']);

    $query = "SELECT * FROM users WHERE username = $1 AND user_role = $2";
    $statement = pg_prepare($dbconn, "", $query);
    $statement = pg_execute($dbconn, "", array($username, 'SuperAdmin'));

    $result = pg_num_rows($statement);

    if($result === 1){
        $row = pg_fetch_object($statement);
        if(password_verify($password, $row->user_password)){
            $_SESSION['login-SA'] = true;
            $_SESSION['curr-user'] = $row;

            return false;
        }
    }

    return true;
}

?>