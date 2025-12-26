<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <title>Giriş</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <h2>Giriş</h2>
    <?php if (!empty($errors['general'])): ?>
        <p style="color:red;">&nbsp;<?php echo $errors['general']; ?></p>
    <?php endif; ?>
    <form method="POST" action="login_post.php" novalidate>
        <input type="email" name="email" placeholder="Email" required>
        <span style="color:red;">&nbsp;<?php echo $errors['email'] ?? ''; ?></span>
        <input type="password" name="password" placeholder="Şifrə" required>
        <span style="color:red;">&nbsp;<?php echo $errors['password'] ?? ''; ?></span>
        <button type="submit">Daxil ol</button>
    </form>
    <p>Hesabınız yoxdur? <a href="auth-register.php">Qeydiyyatdan keçin</a></p>
</body>
</html>
