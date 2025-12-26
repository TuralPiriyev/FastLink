<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

$email = $_GET['email'] ?? '';
$emailDisplay = htmlentities($email, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <title>OTP Doğrulama</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <h2>OTP Doğrulama</h2>
    <?php if (!empty($errors['general'])): ?>
        <p style="color:red;">&nbsp;<?php echo $errors['general']; ?></p>
    <?php endif; ?>
    <form method="POST" action="otp_post.php" novalidate>
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $emailDisplay; ?>" readonly>
        <label>OTP (6 rəqəm)</label>
        <input type="text" name="otp" pattern="\d{6}" maxlength="6" required>
        <span style="color:red;">&nbsp;<?php echo $errors['otp'] ?? ''; ?></span>
        <button type="submit">Təsdiqlə</button>
    </form>
    <form method="POST" action="resend_otp.php" style="margin-top:10px;">
        <input type="hidden" name="email" value="<?php echo $emailDisplay; ?>">
        <button type="submit">OTP-ni yenidən göndər</button>
    </form>
    <p><a href="auth-login.php">Giriş səhifəsinə qayıt</a></p>
</body>
</html>
