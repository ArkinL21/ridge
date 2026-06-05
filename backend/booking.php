<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../html/booking.html");
    exit;
}

function cleanInput($data) {
    return htmlspecialchars(trim($data ?? ''), ENT_QUOTES, 'UTF-8');
}

$fullName = cleanInput($_POST['full_name'] ?? '');
$contactDetails = cleanInput($_POST['contact_details'] ?? '');
$medicalAidName = cleanInput($_POST['medical_aid_name'] ?? '');
$memberNumber = cleanInput($_POST['member_number'] ?? '');
$mainMember = cleanInput($_POST['main_member'] ?? '');
$date = cleanInput($_POST['date'] ?? '');
$time = cleanInput($_POST['time'] ?? '');
$service = cleanInput($_POST['service'] ?? '');
$notes = cleanInput($_POST['notes'] ?? '');

if (
    empty($fullName) ||
    empty($contactDetails) ||
    empty($date) ||
    empty($time) ||
    empty($service)
) {
    echo "Please complete all required fields.";
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
    $mail->addAddress(BOOKING_EMAIL);

    $mail->isHTML(true);
    $mail->Subject = "New Appointment Request - Ridge Dental Practices";

    $mail->Body = "
        <h2>New Appointment Request</h2>

        <h3>Patient Details</h3>
        <p><strong>Full Name:</strong> {$fullName}</p>
        <p><strong>Contact Details:</strong> {$contactDetails}</p>

        <h3>Medical Aid</h3>
        <p><strong>Medical Aid Name:</strong> {$medicalAidName}</p>
        <p><strong>Member Number:</strong> {$memberNumber}</p>
        <p><strong>Main Member:</strong> {$mainMember}</p>

        <h3>Appointment Details</h3>
        <p><strong>Preferred Date:</strong> {$date}</p>
        <p><strong>Preferred Time:</strong> {$time}</p>
        <p><strong>Service:</strong> {$service}</p>
        <p><strong>Additional Notes:</strong> {$notes}</p>

        <hr>
        <p><em>This is an appointment request only. Reception must confirm availability.</em></p>
    ";

    $mail->AltBody =
        "New Appointment Request\n\n" .
        "Patient Details\n" .
        "Full Name: {$fullName}\n" .
        "Contact Details: {$contactDetails}\n\n" .
        "Medical Aid\n" .
        "Medical Aid Name: {$medicalAidName}\n" .
        "Member Number: {$memberNumber}\n" .
        "Main Member: {$mainMember}\n\n" .
        "Appointment Details\n" .
        "Preferred Date: {$date}\n" .
        "Preferred Time: {$time}\n" .
        "Service: {$service}\n" .
        "Additional Notes: {$notes}\n\n" .
        "This is an appointment request only. Reception must confirm availability.";

    $mail->send();

    header("Location: ../html/thank-you.html");
    exit;

} catch (Exception $e) {
    echo "Booking request could not be sent. Please try again later.";
}