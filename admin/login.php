<?php
session_start();
require 'db_settings.php'; 

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $query = "SELECT * FROM admins WHERE username = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('s', $username);  
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

// hash kullanmak mantıklı daha sonra hash e bak fakat hash kullanmak icin kayıt olmak gerek
            
if ($password === $row['password']) {
                $_SESSION['admin'] = $row['username'];
                $_SESSION['address'] = $row['address'];
                header("Location: admin_panel.php");
                exit();
            } else {
                $error = "Hatalı kullanıcı adı veya şifre.";
            }
        } else {
            $error = "Kullanıcı bulunamadı.";
        }

        $stmt->close();
    } else {
        $error = "Veritabanı hatası.";
    }
}

if (isset($_SESSION['admin'])) {
    header("Location: admin_panel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style_login.css">
</head>
<body>

    <div class="login-container">
        <h1>Admin Girişi</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Kullanıcı Adı" required>
            <input type="password" name="password" placeholder="Şifre" required>
            <button type="submit">Giriş Yap</button>
        </form>
    </div>

</body>
</html>
