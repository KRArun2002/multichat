<?php 
    session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";
        $ciphering = "AES-128-CTR";
        $decryption_key = "GeeksforGeeks";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decryption_iv = '1234567891011121';
        $query = mysqli_query($conn, $sql);

        
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_msg_id'] === $outgoing_id){
                    $decrypted_message = openssl_decrypt ($row['msg'], $ciphering, $decryption_key, $options, $decryption_iv);
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $decrypted_message .'</p>
                                </div>
                                </div>';
                }else{
                    $decrypted_message = openssl_decrypt ($row['msg'], $ciphering, $decryption_key, $options, $decryption_iv);
                    $output .= '<div class="chat incoming">
                                <img src="php/images/'.$row['img'].'" alt="">
                                <div class="details">
                                    <p>'. $decrypted_message .'</p>
                                </div>
                                </div>';
                }
            }
        }else{
            $output .= '<div class="text">Start a new conversation.</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }

?>