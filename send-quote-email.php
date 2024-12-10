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
    $service = htmlspecialchars($_POST['service']);
    $bagClippings = isset($_POST['bag_clippings']) ? htmlspecialchars($_POST['bag_clippings']) : 'Not specified';
    $provide_bags = isset($_POST['provide_bags']) ? htmlspecialchars($_POST['provide_bags']) : 'Not specified';
    $tools = htmlspecialchars($_POST['tools']);
    $referral = htmlspecialchars($_POST['referral']);
    $message = htmlspecialchars($_POST['message']);

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
        $mail->Subject = "New Quote Request";
        $mail->Body = "From: $name <$email> \nPhone Number: $phone \nPostal Code: $postal_code \nService: $service \nWill student bag the grass clippings: $bagClippings \nProvide Bags: $provide_bags \nTools Customer will provide: $tools \nHow did you find out about us: $referral \n\nAdditional Message:\n$message";

        // Handle the uploaded photo (if any)
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileSize = $_FILES['photo']['size'];
            $fileType = $_FILES['photo']['type'];

            // Check file size limit (e.g., 5MB)
            if ($fileSize > 5242880) { // 5MB in bytes
                throw new Exception('File size exceeds 5MB limit.');
            }

            // Attach the uploaded file to the email
            $mail->addAttachment($fileTmpPath, $fileName);
        }

        $mail->send();
        echo "Thank you! Your registration has been sent.";
    } catch (Exception $e) {
        echo "Oops! Something went wrong. Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
