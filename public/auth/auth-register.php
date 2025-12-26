<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <title>Qeydiyyat</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <h2>Qeydiyyat</h2>
    <?php if (!empty($errors['general'])): ?>
        <p style="color:red;"><?php echo $errors['general']; ?></p>
    <?php endif; ?>
    <form method="POST" action="register_post.php" novalidate>
        <input type="text" name="first_name" placeholder="Ad" value="<?php echo $old['first_name'] ?? ''; ?>" required>
        <span style="color:red;">&nbsp;<?php echo $errors['first_name'] ?? ''; ?></span>

        <input type="text" name="last_name" placeholder="Soyad" value="<?php echo $old['last_name'] ?? ''; ?>" required>
        <span style="color:red;">&nbsp;<?php echo $errors['last_name'] ?? ''; ?></span>

        <input type="text" name="phone" placeholder="+994XXXXXXXXX" value="<?php echo $old['phone'] ?? ''; ?>" required>
        <span style="color:red;">&nbsp;<?php echo $errors['phone'] ?? ''; ?></span>

        <input type="email" name="email" placeholder="Email" value="<?php echo $old['email'] ?? ''; ?>" required>
        <span style="color:red;">&nbsp;<?php echo $errors['email'] ?? ''; ?></span>

        <input type="password" name="password" placeholder="Şifrə" required>
        <span style="color:red;">&nbsp;<?php echo $errors['password'] ?? ''; ?></span>

        <input type="password" name="confirm_password" placeholder="Şifrənin təkrarı" required>
        <span style="color:red;">&nbsp;<?php echo $errors['confirm_password'] ?? ''; ?></span>

        <input type="text" name="business_name" placeholder="Biznes adı" value="<?php echo $old['business_name'] ?? ''; ?>" required>
        <span style="color:red;">&nbsp;<?php echo $errors['business_name'] ?? ''; ?></span>

        <input type="text" name="city" placeholder="Şəhər" value="<?php echo $old['city'] ?? ''; ?>" required>
        <span style="color:red;">&nbsp;<?php echo $errors['city'] ?? ''; ?></span>

        <textarea name="location" placeholder="Ünvan (opsional)"><?php echo $old['location'] ?? ''; ?></textarea>
        <span style="color:red;">&nbsp;<?php echo $errors['location'] ?? ''; ?></span>

        <select name="plan" required>
            <option value="">Plan seçin</option>
            <option value="kicik" <?php echo (isset($old['plan']) && $old['plan'] === 'kicik') ? 'selected' : ''; ?>>Kiçik</option>
            <option value="orta" <?php echo (isset($old['plan']) && $old['plan'] === 'orta') ? 'selected' : ''; ?>>Orta</option>
            <option value="boyuk" <?php echo (isset($old['plan']) && $old['plan'] === 'boyuk') ? 'selected' : ''; ?>>Böyük</option>
        </select>
        <span style="color:red;">&nbsp;<?php echo $errors['plan'] ?? ''; ?></span>

        <label>
            <input type="checkbox" name="terms_accepted" value="1" <?php echo isset($old['terms_accepted']) ? 'checked' : ''; ?>>
            Şərtləri qəbul edirəm
        </label>
        <span style="color:red;">&nbsp;<?php echo $errors['terms_accepted'] ?? ''; ?></span>

        <button type="submit">Qeydiyyat</button>
    </form>
    <p>Artıq hesabınız var? <a href="auth-login.php">Daxil olun</a></p>
</body>
</html>
