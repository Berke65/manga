<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_panel.css">
</head>
<body>

    <div class="header">
        <h1>Admin Paneli</h1>
        <form action="" method="get">
            <button type="submit" name="logout" class="logout-btn">Çıkış Yap</button>
        </form>
    </div>

    <div class="content">
        <h2>Hoş Geldiniz, <?php echo htmlspecialchars($_SESSION['admin']); ?>!</h2>
        <p>Burada <strong>manga</strong> bölümlerini yönetebilirsiniz.</p>
        
        <div class="actions">
            <h3>Yapmak İstediğiniz İşlemi Seçin:</h3>
            <a href="add_manga.php" class="action-btn">Manga Ekle</a>
            <a href="add_chapter.php" class="action-btn">Bölüm Ekle</a>
            <a href="add_page.php" class="action-btn">Sayfa Ekle</a>
           <!-- <a href="manage_mangas.php" class="action-btn">Manga Yönet</a>        yapılacak -->
           <!-- <a href="manage_chapters.php" class="action-btn">Bölüm Yönet</a>      yapılacak -->
        </div>
    </div>

</body>
</html>
