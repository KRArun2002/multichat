<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        $ciphering = "AES-128-CTR";

        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        // Store the encryption key
        $encryption_key = "GeeksforGeeks";


        $encrypted_message = openssl_encrypt($message, $ciphering,$encryption_key, $options, $encryption_iv);

        if(!empty($encrypted_message)){
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$encrypted_message}')") or die();
        }
    }else{
        header("location: ../login.php");
    }


    
?>