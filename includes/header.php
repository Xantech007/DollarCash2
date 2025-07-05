<?php
include('config/dbcon.php');

// Default values for meta tags (fallback for static or home page)
$page_title = "Cash App"; // Default title
$page_description = "Welcome to Cash App, your solution for seamless financial transactions."; // Default description
$page_image = "https://pay-cashapp.rf.gd/Uploads/logo/logo1.png"; // Default image (use absolute URL)
$page_url = "https://pay-cashapp.rf.gd"; // Default URL

// Example: Dynamically set meta tags based on page content (e.g., for a blog post or dynamic page)
if (isset($_GET['page_id'])) {
    $page_id = $_GET['page_id']; // Example: Retrieve page ID from URL
    // Query to fetch page-specific data from the database
    $query = "SELECT title, description, image FROM pages WHERE id = ?";
    $stmt = $conn->prepare($query); // Assuming $conn is your database connection from dbcon.php
    $stmt->bind_param("i", $page_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $page_title = $row['title'];
        $page_description = $row['description'];
        $page_image = "https://yourwebsite.com/Uploads/" . $row['image']; // Ensure absolute URL
        $page_url = "https://yourwebsite.com?page_id=" . $page_id; // Dynamic URL
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- favicon -->
    <link rel="shortcut icon" href="Uploads/logo/logo1.png" type="image/x-icon">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>" />
    <meta property="og:image" content="<?php echo htmlspecialchars($page_image); ?>" />
    <meta property="og:url" content="<?php echo htmlspecialchars($page_url); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Cash App" />

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>" />
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>" />
    <meta name="twitter:image" content="<?php echo htmlspecialchars($page_image); ?>" />
    <meta name="twitter:site" content="@YourTwitterHandle" />

    <!-- bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Plugin css -->
    <link rel="stylesheet" href="assets/css/plugin.css">
    <!-- Flaticon -->
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <!-- stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dark.css">
    <!-- responsive -->
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>

<body>
    <!-- preloader area start -->
    <div class="preloader" id="preloader">
        <div class="loader loader-1">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>
        </div>
    </div>
    <!-- preloader area end -->
