<?php
require_once __DIR__ . '/../../middleware/auth_required.php';
require_once __DIR__ . '/../../helpers/validation.php';
require_once __DIR__ . '/../../helpers/redirect.php';
require_once __DIR__ . '/../../services/UserService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/index.php?page=settings');
}

$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    redirect('/public/auth/auth-login.php');
}

$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$businessName = trim($_POST['business_name'] ?? '');
$city = trim($_POST['city'] ?? '');
$location = trim($_POST['location'] ?? '');
$plan = trim($_POST['plan'] ?? '');

$errors = [];

if (!isRequired($firstName)) {
    $errors['first_name'] = 'Ad tələb olunur.';
}

if (!isRequired($lastName)) {
    $errors['last_name'] = 'Soyad tələb olunur.';
}

if (!isValidEmail($email)) {
    $errors['email'] = 'Email düzgün deyil.';
}

if (!isValidPhoneAz($phone)) {
    $errors['phone'] = 'Telefon +994 formatında olmalıdır.';
}

if (!isRequired($businessName)) {
    $errors['business_name'] = 'Biznes adı tələb olunur.';
}

if (!isRequired($city)) {
    $errors['city'] = 'Şəhər tələb olunur.';
}

if (!isValidPlan($plan)) {
    $errors['plan'] = 'Plan düzgün seçilməyib.';
}

if (mb_strlen($businessName) > 150) {
    $errors['business_name'] = 'Biznes adı 150 simvoldan çox ola bilməz.';
}

if (mb_strlen($firstName) > 120) {
    $errors['first_name'] = 'Ad çox uzundur.';
}

if (mb_strlen($lastName) > 120) {
    $errors['last_name'] = 'Soyad çox uzundur.';
}

$service = new UserService();

if (empty($errors) && $service->emailExistsForOther($email, $userId)) {
    $errors['email'] = 'Bu email artıq istifadə olunur.';
}

if (empty($errors) && $service->phoneExistsForOther($phone, $userId)) {
    $errors['phone'] = 'Bu telefon artıq istifadə olunur.';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old'] = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'business_name' => $businessName,
        'city' => $city,
        'location' => $location,
        'plan' => $plan,
    ];
    redirect('/index.php?page=settings');
}

try {
    $service->updateProfile($userId, [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'business_name' => $businessName,
        'city' => $city,
        'location' => $location,
        'plan' => $plan,
    ]);

    $_SESSION['success'] = 'Profil məlumatları yeniləndi.';
    $_SESSION['email'] = $email;
    $_SESSION['business_name'] = $businessName;
} catch (Throwable $e) {
    $_SESSION['errors'] = ['general' => 'Məlumatlar yenilənərkən xəta baş verdi.'];
}

redirect('/index.php?page=settings');
