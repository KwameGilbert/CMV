<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - CMV</title>
    <link rel="stylesheet" href="styles/contact_us.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/footer.css">
</head>
<body>
    <?php include 'header.php'; ?>
<br>
    <div class="container">
        <h1>Contact Us</h1>
        <div class="contact-details">
            <p><strong>Phone:</strong> +233 541-436-414</p>
            <p><strong>Email:</strong> kwamegilbert1114@gmail.com</p>
            <p><strong>Address:</strong>AAMUSTED-K, Kumasi, Tanoso. Sunyani Road</p>
        </div>
        <form action="contact_form_handler.php" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
