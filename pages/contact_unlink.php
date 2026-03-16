<?php
    include '../db/binarycity_db.php';
    //Checks if post contains client id to link contact with
    if(isset($_POST['unlink_client_contact'])){
        $client_id = intval($_POST['client_id']);
        //if client id does not exist, returns with message, else procees to execute
        if(!$client_id){
            header("Location: ../?no-client-selected=true");
        }else{
            //Deletes/Removes client linked with contact from contacts table
            $stmt = $conn->prepare("DELETE FROM contacts WHERE client_id=?");
            $stmt->bind_param("i",$client_id);
    
            if($stmt->execute()){
                header("Location: ../?unlink_status=success");
            } else {
                header("Location: ../?unlink_status=error");
            }
        }        
    } else {
        header("Location: ../?serror=true");
    }
?>
