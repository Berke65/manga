<?php
$servername = "localhost";   
$db_username = "root";       
$db_password = "";           
$db_name = "manga";  

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>
