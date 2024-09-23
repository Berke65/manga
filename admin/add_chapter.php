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
    $manga_id = $_POST['manga_id'];
    $chapter_number = $_POST['chapter_number'];
    $title = $_POST['title'];
    $created_at = $_POST['created_at'];

    $query = "INSERT INTO chapters (manga_id, chapter_number, title, created_at) VALUES (?, ?, ?, ?)";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('isss', $manga_id, $chapter_number, $title, $created_at);
        if ($stmt->execute()) {
            $success = "Bölüm başarıyla eklendi!";
            // Form gönderildikten sonra tüm değerleri sıfırla
            $manga_id = $chapter_number = $title = $created_at = '';
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
    <title>Bölüm Ekle</title>
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
        <h1>Bölüm Ekle</h1>
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
            <select name="manga_id" required>
                <option value="">Manga Seçin</option>
                <?php
                // Tüm mangaları çek
                $mangas = $conn->query("SELECT id, title FROM mangas");
                while ($row = $mangas->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>" . htmlspecialchars($row['title']) . "</option>";
                }
                ?>
            </select>
            <input type="number" name="chapter_number" placeholder="Bölüm Numarası" required>
            <input type="text" name="title" placeholder="Bölüm Açıklaması" required onfocus="showAlert()">
            <input type="date" name="created_at" placeholder="Yayın Tarihi" required>
            <button type="submit" style="display:block;">Ekle</button>
        </form>
    </div>

    <script>
let alertShown = false; // Uyarının gösterilip gösterilmediğini kontrol et

function showAlert() {
    if (!alertShown) {
        alert("Lütfen bölüm açıklaması eklerken açıklamanın başına manga adını yazınız.");
        alertShown = true; // Uyarı gösterildi, bir daha gösterme
    }
}
</script>



</body>
</html>
