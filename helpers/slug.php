<?php
function slugify(string $value): string
{
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value);
    $value = trim($value, '-');
    return $value !== '' ? $value : substr(md5((string)microtime(true)), 0, 8);
}
