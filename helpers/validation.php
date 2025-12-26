<?php
function sanitize($value): string
{
    return htmlentities(trim((string)$value), ENT_QUOTES, 'UTF-8');
}

function isRequired($value): bool
{
    return strlen(trim((string)$value)) > 0;
}

function isValidEmail($email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidPhoneAz($phone): bool
{
    return preg_match('/^\+994\d{9}$/', $phone) === 1;
}

function isValidPassword($password): bool
{
    if (strlen($password) < 8) {
        return false;
    }
    $hasLetter = preg_match('/[A-Za-z]/', $password);
    $hasDigit = preg_match('/\d/', $password);
    return $hasLetter === 1 && $hasDigit === 1;
}

function isValidPlan($plan): bool
{
    return in_array($plan, ['kicik', 'orta', 'boyuk'], true);
}

function termsAccepted($value): bool
{
    return (int)$value === 1;
}
