<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }
    // Set the recipient email address
    $to = "kwamegilbert1114@gmail.com"; // Replace with your email address
    // Set the email subject
    $email_subject = "New Contact Us Message from: $name";
    // Construct the email body
    $email_body = "CountMyVote:Contact Us. You have received a new message from the Contact Us form on your website.\n\n";
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Subject: $subject\n";
    $email_body .= "Message:\n$message\n";
    // Set the email headers
    $headers = "From: $email\n";
    $headers .= "Reply-To: $email\n";
    // Send the email
    if (mail($to, $email_subject, $email_body, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message. Please try again.";
    }
} else {
    echo "Invalid request";
}
?>