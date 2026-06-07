<?php


$host     = "localhost";
$username = "root";           
$password = "";               
$dbname   = "animalfarmdb";   

$conn = mysqli_connect($host, $username, $password, $dbname);


if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}


mysqli_set_charset($conn, "utf8mb4");
?>