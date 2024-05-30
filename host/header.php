<?php
//ENFORCE https on all pages
if ($_SERVER['HTTPS'] != "on") {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}

?>

<header>
        <div class="logo">
            <img src="../includes/images/logo/cmv-globe.svg" alt="Logo" >
        </div>
        <div class="menu-button" onclick="toggleMenu()">☰</div>
        <nav id="navMenu">
            <div class="close-button" onclick="toggleMenu()">✖</div>
            <a href="../index.php">Home</a>
            <a href="../index.php">Events</a>
            <a href="#">Nominee Filling</a>
            <a href="../tnc.php">Terms and Conditions</a>
            <a href="#">Contact Us</a>
        </nav>
        <script src="../js/header.js"></script>
</header>