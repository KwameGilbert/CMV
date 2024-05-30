<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Link to Font Awesome all.min.css from includes -->
    <link rel="stylesheet" href="../includes/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/sidebar.css">
    <!-- <link rel="stylesheet" href="style/main.css">-->
   
</head>
<body>

<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<div class="container">
    <?php
        // Logic to determine which main content to include based on the page
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; // Default to dashboard
        $allowed_pages = array('dashboard', 'hosts', 'events', 'categories', 'settings');

        if (in_array($page, $allowed_pages)) {
            include $page . '.php';
        } else {
            include 'results.php'; // Default to dashboard if page is not recognized
        }
    ?>
</div>

<script>
    // JavaScript for toggling sidebar
    const toggleBtn = document.querySelector('.toggle-btn');
    const sidebar = document.querySelector('.sidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
    });
</script>

</body>
</html>
