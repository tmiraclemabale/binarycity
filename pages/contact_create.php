<?php
    include '../db/binarycity_db.php';

    $client_id = $_POST['client_id'];
    $email_address = trim($_POST['email_address']);

    $client_check = $conn->query("SELECT id, first_name, last_name FROM clients WHERE id=$client_id");
    $cl_row = $client_check->fetch_assoc();
    $first_name = $cl_row['first_name'];
    $last_name = $cl_row['last_name'];

    
    // Insert new contact
    $active = 1;
    $stmt = $conn->prepare("INSERT INTO contacts(client_id, first_name, last_name,email_address, active) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssss",$client_id, $first_name, $last_name, $email_address, $active);

    if($stmt->execute()){
        header("Location: ../?contact_status=success");
    }else{
        header("Location: ../?contact_status=error");
    }

    
?>