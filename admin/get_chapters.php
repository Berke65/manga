<?php
require 'db_settings.php'; // Veritabanı bağlantısını sağla

$manga_id = $_GET['manga_id'];
$query = "SELECT id, title FROM chapters WHERE manga_id = ?"; // Manga ID'ye göre bölümleri al
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $manga_id);
$stmt->execute();
$result = $stmt->get_result();

$chapters = [];
while ($row = $result->fetch_assoc()) {
    $chapters[] = $row;
}

echo json_encode($chapters); // JSON formatında döndür
?>
