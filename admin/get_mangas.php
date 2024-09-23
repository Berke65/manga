<?php
require 'db_settings.php'; // Veritabanı bağlantısını sağla

$query = "SELECT id, title FROM mangas"; // Mangaların listesini al
$result = $conn->query($query);

$mangas = [];
while ($row = $result->fetch_assoc()) {
    $mangas[] = $row;
}

echo json_encode($mangas); // JSON formatında döndür
?>
