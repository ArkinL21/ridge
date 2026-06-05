<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../html/contact.html");
    exit;
}

function cleanInput($data) {
    return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
}

$name = cleanInput($_POST['name'] ?? '');
$email = cleanInput($_POST['email'] ?? '');
$phone = cleanInput($_POST['phone'] ?? '');
$message = cleanInput($_POST['message'] ?? '');

// Validation
if (
    empty($name) ||
    empty($email) ||
    empty($message)
) {
    echo "Please complete all required fields.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email address.";
    exit;
}

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom(FROM_EMAIL, FROM_NAME);
    $mail->addAddress(CONTACT_EMAIL);

    // Allows receptionist to click Reply
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = "New Contact Message - Ridge Dental Practices";

    $mail->Body = "
        <h2>New Contact Message</h2>

        <p><strong>Name:</strong> {$name}</p>

        <p><strong>Email:</strong> {$email}</p>

        <p><strong>Phone:</strong> {$phone}</p>

        <p><strong>Message:</strong></p>

        <p>{$message}</p>
    ";

    $mail->AltBody =
        "New Contact Message\n\n" .
        "Name: {$name}\n" .
        "Email: {$email}\n" .
        "Phone: {$phone}\n\n" .
        "Message:\n{$message}";

    $mail->send();

    header("Location: ../html/thank-you.html");
    exit;

} catch (Exception $e) {

    // For production:
    echo "Message could not be sent. Please try again later.";

    // For testing:
    // echo $mail->ErrorInfo;
}