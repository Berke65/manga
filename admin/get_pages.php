<?php
require 'db_settings.php';

if (isset($_GET['chapter_id'])) {
    $chapter_id = $_GET['chapter_id'];
    
    $query = "SELECT id, page_number, image_url FROM pages WHERE chapter_id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $chapter_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pages = [];
        while ($row = $result->fetch_assoc()) {
            $pages[] = $row;
        }
        
        echo json_encode($pages);
    }
}
?>
