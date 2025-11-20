<?php
try {
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $conn = new PDO("mysql:host=$server_name;dbname=expense_db", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "connection success";
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
