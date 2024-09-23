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
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $status = $_POST['status'];

    // Veritabanına ekleme sorgusu
    $query = "INSERT INTO mangas (title, description, author, genre, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('sssss', $title, $description, $author, $genre, $status);
        if ($stmt->execute()) {
            $success = "Manga başarıyla eklendi!";
        } else {
            $error = "Bir hata oluştu. Lütfen tekrar deneyin.";
        }
        $stmt->close();
    } else {
        $error = "Veritabanı hatası.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manga Ekle</title>
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
        form input, form textarea, form select {
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
        <h1>Manga Ekle</h1>
        <a href="admin_panel.php">Admin Paneline Dön</a>
    </div>

    <div class="content">
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="title" placeholder="Manga Başlığı" required>
            <textarea name="description" placeholder="Açıklama" required></textarea>
            <input type="text" name="author" placeholder="Yazar" required>
            <input type="text" name="genre" placeholder="Tür (örn. shounen)" required>
            <select name="status" required>
                <option value="Devam Ediyor">Devam Ediyor</option>
                <option value="Tamamlandı">Tamamlandı</option>
            </select>
            <button type="submit" style="display:block;">Ekle</button>
        </form>
    </div>

</body>
</html>
