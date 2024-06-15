<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../styles/login.css">
</head>
<body>
    <div class="login-container">
    <img src="../../includes/images/logo/cmv-globe.svg" alt="Logo" >
        <h2>CountMyVote Super Admin Login</h2>
        <form action="authenticate.php" method="post">
            <label for="username_email">Username or Email:</label>
            <input type="text" id="username_email" name="username_email" placeholder="Enter your username or email" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password"required>
            <br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="../sign-up">Sign Up</a></p>
        <a href="../../index.php">Back to Home</a>
    </div>
</body>
</html>