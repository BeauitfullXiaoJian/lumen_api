<?php
$PASSWORD = 123456789;

if ($_GET['password'] !== $PASSWORD) {
    return 'ERROR';
}

return realpath(__DIR__ . '../');
