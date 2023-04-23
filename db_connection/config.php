<?php

session_start();

$host = 'localhost';
$db = 'lms_pupill_db';
$user = 'root';
$pass = '';

try {
    $con = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Add error handling for PDO connection
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
