<?php
    $Servername = "localhost";
    $Username = "root";
    $Pasword = "";
    $Dbname = "user_account";

    try {
        $conn = new PDO("mysql:host=$Servername;dbname=$Dbname", $Username, $Pasword);
        $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

    }catch(PDOException $e) {
        echo "Konkesi gagal: ". $e ->getMessage();
    }