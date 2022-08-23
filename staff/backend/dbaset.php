<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('phpmailer/src/Exception.php');
include('phpmailer/src/PHPMailer.php');
include('phpmailer/src/SMTP.php');

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

function sendMail($receiver, $subyek, $message){
    // $email_sender = 'managementasset.binusbdg@gmail.com';
    // $password = '';
    // $name_sender = 'management asset';

    // $email_receiver = $receiver;
    // $subjek = $subyek;
    // $pesan = $message;

    // $mail = new PHPMailer();
    // $mail->isSMTP();

    // $mail->Host = 'smtp.gmail.com';
    // $mail->Port = 587;
    // $mail->SMTPAuth = true;
    // $mail->SMTPSecure = 'tls';
    // // $mail->SMTPDebug = 2;

    // $mail->Username = $email_sender;
    // $mail->Password = $password;
    // $mail->setFrom($email_sender, $name_sender);
    
    // $mail->addAddress($email_receiver);
    // $mail->Subject = $subjek;
    // $mail->Body = $pesan;

    // return $mail->send();
    return true;
}

function printAssetId($arr){
    foreach ($arr as $a) {
        echo $a . "; ";
    }
}

function approve($data){
    global $dbconn;

    $query = "SELECT * FROM requests WHERE request_id = $data";
    $result = query($query);
    $result = $result[0];

    // DONE: tambahin si track_approvernya
    $query = "UPDATE requests SET track_approver = (SELECT track_approver FROM requests WHERE request_id = $data) + 1 WHERE request_id = $data;";
    $query = pg_query($query);

    if(pg_affected_rows($query) > 0){
        $query = "SELECT * FROM requests WHERE request_id = $data;";
        $result = query($query);
        $result = $result[0];

        if($result['track_approver'] == $result['num_approver']){
            // DONE: update status requestnya
            $query = "UPDATE requests SET request_status = 'approved' WHERE request_id = $data;";
            $query = pg_query($query);

            if(pg_affected_rows($query) > 0){
                // DONE: update tabel assets nya si json asset_booked_datenya tambahin
                $assets = array();
                $obj = json_decode($result['request_items']);
                $obj = $obj->items;

                foreach($obj as $o){
                    $assets = array_merge($assets, $o->asset_id);
                }

                $add = array(array("request_ID"=> (int)$result['request_id'], "book_date"=>$result["book_date"], "return_date"=>$result["return_date"]));
                $flag = false;

                foreach($assets as $as){
                    $query = "SELECT * FROM assets WHERE asset_id = $as";
                    $row = query($query);
                    $temp = array("requests"=>array());

                    $j = json_decode($row[0]['asset_booked_date']);
                    if($j){
                        $j->requests = array_merge($j->requests, $add);
                        $temp['requests'] = $j->requests;
                    }
                    else{
                        $temp['requests'] = $add;
                    }

                    $query = "UPDATE assets SET asset_booked_date = $1 WHERE asset_id = $2;";
                    $statement = pg_prepare($dbconn, "", $query);
                    $statement = pg_execute($dbconn, "", array(json_encode($temp), $as));

                    if(pg_affected_rows($statement) > 0){
                        $flag = true;
                    }
                }

                if($flag){
                    $temp = $result['user_id'];
                    $receiver = "SELECT user_email FROM users WHERE user_id = $temp;"; //ke studentnya
                    $receiver = pg_query($receiver);
                    $receiver = pg_fetch_assoc($receiver)['user_email'];
                    
                    $subyek = 'PEMINJAMAN APPROVED';
                    $pesan = 'Selamat peminjaman anda berhasil di approve! silahkan ambil barang sesuai dengan tanggal peminjaman.';

                    sendMail($receiver, $subyek, $pesan);

                    echo "
                    <script>
                        alert('Request berhasil di approve!');
                        document.location.href = 'index.php';
                    </script>
                    ";
                    exit;
                }
            }
        }else{
            $temp = $_SESSION['curr-user']->user_kode_prodiv;
            $receiver = "SELECT user_email FROM users WHERE user_kode_prodiv = '$temp' AND user_role = 'Approver';";
            $receiver = pg_query($receiver);
            $receiver = pg_fetch_assoc($receiver)['user_email'];
            
            $temp = $result['user_id'];
            $temp = "SELECT username FROM users WHERE user_id = $temp;";
            $temp = pg_query($temp);
            $temp = pg_fetch_assoc($temp)['username'];

            $subyek = 'REQUEST PEMINJAMAN ALAT LAB';
            $pesan = 'Ada request peminjaman alat lab baru dari ' . $temp;

            sendMail($receiver, $subyek, $pesan);

            echo "
            <script>
                alert('Request berhasil di approve!');
            </script>
            ";
            return 1;
        }
    }
    
}

function reject($data){
    global $dbconn;

    // DONE: isi rejectnya, kalau direject gadiapus tapi update aja rejected
    $query = "UPDATE requests SET request_status = 'rejected' WHERE request_id = $data;";
    $query = pg_query($query);

    if(pg_affected_rows($query) > 0){

        //DONE: kirim emailnya ke student klo di reject requestnya
        $query = "SELECT * FROM requests WHERE request_id = $data;";
        $result = query($query);
        $result = $result[0];

        $temp = $result['user_id'];
        $receiver = "SELECT user_email FROM users WHERE user_id = $temp;"; //ke studentnya
        $receiver = pg_query($receiver);
        $receiver = pg_fetch_assoc($receiver)['user_email'];
        
        $subyek = 'PEMINJAMAN REJECTED';
        $pesan = 'Mohon maaf, peminjaman anda tidak disetujui oleh approver. Silahkan pilih tanggal lain untuk meminjam.';

        sendMail($receiver, $subyek, $pesan);

        echo "
        <script>
            alert('Request berhasil di reject!');
            document.location.href = 'index.php';
        </script>
        ";
        exit;
    }
}

function taken($data){
    global $dbconn;

    $query = "UPDATE requests SET taken_date = CURRENT_TIMESTAMP, request_status = 'on use' WHERE request_id = $data;";
    $query = pg_query($query);

    if(pg_affected_rows($query) > 0){

        // DONE: update assets table untuk setiap asset id yg di pinjam request asset_status-nya jadi on use dan asset_curr_location nya jadi alamat peminjam
        $query = "SELECT * FROM requests WHERE request_id = $data;";
        $result = query($query);
        $user_id = $result[0]['user_id'];
        $lokasi_pinjam = $result[0]['lokasi_pinjam'];
        $result = $result[0]['request_items'];        
        $result = json_decode($result);
        $obj = $result->items;
        $assets = array();

        foreach($obj as $o){
            $assets = array_merge($assets, $o->asset_id);
        }
        
        $flag = false;
        foreach($assets as $as){
            if($lokasi_pinjam == 'bawa pulang'){
                $query = "UPDATE assets SET asset_status = 'on use', asset_curr_location = (SELECT user_address FROM users WHERE user_id = $user_id) WHERE asset_id = $as;";
            }
            else{
                $query = "UPDATE assets SET asset_status = 'on use', asset_curr_location = '$lokasi_pinjam' WHERE asset_id = $as;";
            }
            $statement = pg_query($query);

            if(pg_affected_rows($statement) > 0){
                $flag = true;
            }
        }

        if($flag){
            echo "
            <script>
                alert('Request updated!');
                document.location.href = 'index.php';
            </script>
            ";
            exit;
        }
    }
}

?>