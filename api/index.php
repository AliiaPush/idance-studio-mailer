<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$mail = new PHPMailer(true);

if (file_exists(__DIR__ . '/../.env')) {
    echo "Exists";
}

echo "Hello Vercel";
echo $_ENV['SAMPLE_API_KEY'];
