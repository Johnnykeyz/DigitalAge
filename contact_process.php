<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    // Simple validation
    if (empty($full_name) || empty($email) || empty($subject) || empty($message)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO contact_messages (full_name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Database error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
