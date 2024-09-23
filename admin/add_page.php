<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require 'db_settings.php'; // Veritabanı bağlantısını sağla

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chapter_id = $_POST['chapter_id'];
    $page_number = $_POST['page_number'];

    // Resmi yükle
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = $_FILES['image']['name'];
        $uploadPath = 'uploads/' . basename($imageName);

        // Görseli uploads dizinine taşı
        if (move_uploaded_file($imageTmpPath, $uploadPath)) {
            $query = "INSERT INTO pages (chapter_id, page_number, image_url, created_at) VALUES (?, ?, ?, NOW())";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param('iis', $chapter_id, $page_number, $uploadPath); // image_url ekleyin
                if ($stmt->execute()) {
                    $success = "Sayfa başarıyla eklendi!";
                    // Form gönderildikten sonra tüm değerleri sıfırla
                    $chapter_id = $page_number = '';
                } else {
                    $error = "Bir hata oluştu. Lütfen tekrar deneyin.";
                }
                $stmt->close();
            } else {
                $error = "Veritabanı hatası.";
            }
        } else {
            $error = "Görsel yüklenirken hata oluştu.";
        }
    } else {
        $error = "Resim yüklenirken bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sayfa Ekle</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header a {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            float: right;
        }
        .content {
            padding: 20px;
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-top: 20px;
        }
        form input, form select {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        form button:hover {
            background-color: #2980b9;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Sayfa Ekle</h1>
        <a href="admin_panel.php">Admin Paneline Dön</a>
    </div>

    <div class="content">
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <select name="chapter_id" required>
                <option value="">Bölüm Seçin</option>
                <?php
                // Tüm bölümleri çek
                $chapters = $conn->query("SELECT id, title FROM chapters");
                while ($row = $chapters->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>" . htmlspecialchars($row['title']) . "</option>";
                }
                ?>
            </select>
            <input type="number" name="page_number" placeholder="Sayfa Numarası" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit" style="display:block;">Ekle</button>
        </form>
    </div>

</body>
</html>
