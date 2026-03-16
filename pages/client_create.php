<?php
    include("../db/binarycity_db.php");

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    if($first_name == "" || $last_name == ""){
        header("Location: ../?field-required=error");
    }

    // $fullname = $first_name . " " . $last_name;

    function generateClientCode($first_name, $last_name, $conn){
        $words = explode(" ",strtoupper($first_name));
        $letters = "";

        foreach($words as $word){
            $letters.=substr($word, 0, 3);
        }

        $letters=substr($letters, 0, 3);

        $result=$conn->query("SELECT MAX(id) as last_id FROM clients");
        $row=$result->fetch_assoc();

        $num=str_pad($row['last_id'] + 1, 3, "0", STR_PAD_LEFT);

        return $letters.$num;
    }

    $client_code = generateClientCode($first_name, $last_name, $conn);
    $active = 1;
    $stmt = $conn->prepare("INSERT INTO clients (client_code, first_name,last_name, active) VALUES(?,?,?,?)");
    $stmt->bind_param("ssss", $client_code, $first_name, $last_name, $active);

    if($stmt->execute()){
        header("Location: ../?client_status=success");
    }else{
        header("Location: ../?client_status=error");
    }
?>