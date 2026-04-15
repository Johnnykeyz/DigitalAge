<?php
require_once "config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // make sure you've run "composer require phpmailer/phpmailer"

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if (empty($full_name) || empty($email) || empty($subject) || empty($message)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Save message to database
    $stmt = $conn->prepare("INSERT INTO contact_messages (full_name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $subject, $message);

    if ($stmt->execute()) {
        // ✅ SMTP Email Sending
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Change if using another service
            $mail->SMTPAuth   = true;
            $mail->Username   = 'yourgmail@gmail.com'; // Your Gmail
            $mail->Password   = 'your_app_password'; // App password from Google
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('yourgmail@gmail.com', 'Website Contact Form');
            $mail->addAddress('info@yourwebsite.com', 'Website Owner'); // Change to recipient email

            // Content
            $mail->isHTML(true);
            $mail->Subject = "📩 New Contact Message from {$full_name}";
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; border-radius: 10px;'>
                    <h2 style='color: #333;'>New Contact Message</h2>
                    <p><strong>Name:</strong> {$full_name}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Subject:</strong> {$subject}</p>
                    <p><strong>Message:</strong></p>
                    <p style='background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #ddd;'>{$message}</p>
                    <hr>
                    <p style='font-size: 0.9rem; color: #777;'>Sent on: " . date("Y-m-d H:i:s") . "</p>
                </div>
            ";

            $mail->AltBody = "New Contact Message\n\nName: {$full_name}\nEmail: {$email}\nSubject: {$subject}\nMessage:\n{$message}";

            $mail->send();
            echo "success";
        } catch (Exception $e) {
            echo "Message saved but could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "Database error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
