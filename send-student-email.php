<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
//require_once __DIR__ . '/Dotenv/Dotenv.php'; // Corrected Dotenv path

//use Dotenv\Dotenv;

// Load .env variables
//$dotenv = Dotenv::createImmutable(__DIR__);
//$dotenv->load();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify reCAPTCHA
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = '';
    $verifyUrl = '';
    $response = file_get_contents($verifyUrl . '?secret=' . $secretKey . '&response=' . $recaptchaResponse);
    $responseKeys = json_decode($response, true);
    
    if (!$responseKeys['success']) {
        die('Captcha verification failed. Please try again.');
    }

    // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $postal_code = htmlspecialchars($_POST['postal_code']);
    $student = htmlspecialchars($_POST['student']);
    $school = htmlspecialchars($_POST['school']);
    $equipment = isset($_POST['equipment']) ? implode(', ', $_POST['equipment']) : 'None';
    $additional_info = htmlspecialchars($_POST['additional_info']);

    $mail = new PHPMailer(true);

    try {
        // Enable SMTP debugging for detailed error output
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'mail.studentforhire.ca'; // Securely load SMTP Host from .env variable
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = ''; // Securely load password from .env variable
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465; // Securely load SMTP port from .env variable;

        // Recipients
        $mail->setFrom('info@studentforhire.ca', 'Student For Hire');
        $mail->addAddress('ethan.katsiroubas@gmail.com');
        $mail->addAddress('info@studentforhire.ca');

        // Email subject and body content
        $mail->Subject = "New Student Registration";
        $mail->Body = "From: $name <$email> \nPhone Number: $phone \nPostal Code: $postal_code \nAre You a Student?: $student \nSchool: $school \nEquipment Available: $equipment \n\nAdditional Information:\n$additional_info";

        $mail->send();
        echo "Thank you! Your registration has been sent.";
    } catch (Exception $e) {
        echo "Oops! Something went wrong. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
