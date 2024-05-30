<?php
//ENFORCE https on all pages
if ($_SERVER['HTTPS'] != "on") {
    $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}

$domain = "https://www.countmyvote.great-site.net/";
?>

<header>
        <div class="logo">
            <img src="<?php echo $domain ?>includes/images/logo/cmv-globe.svg" alt="Logo" >
        </div>
        <div class="menu-button" onclick="toggleMenu()">☰</div>
        <nav id="navMenu">
            <div class="close-button" onclick="toggleMenu()">✖</div>
            <a href="<?php echo $domain ?>index.php">Home</a>
            <a href="<?php echo $domain ?>index.php">Events</a>
            <a href="#">Nominee Filling</a>
            <a href="<?php echo $domain ?>tnc.php">Terms and Conditions</a>
            <a href="#">Contact Us</a>
        </nav>
        <script src="<?php echo $domain ?>js/header.js"></script>
</header>