<?php
session_start();
include('../../config/dbcon.php'); // Adjust path to your database connection file

if (isset($_POST['update_payment_details'])) {
    // Sanitize inputs to prevent SQL injection
    $network = mysqli_real_escape_string($con, $_POST['network']);
    $momo_name = mysqli_real_escape_string($con, $_POST['momo_name']);
    $momo_number = mysqli_real_escape_string($con, $_POST['momo_number']);
    $currency = mysqli_real_escape_string($con, $_POST['currency']);
    $rate = mysqli_real_escape_string($con, $_POST['rate']); // Added rate

    // Basic validation
    if (empty($network) || empty($momo_name) || empty($momo_number) || empty($currency) || empty($rate)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../settings.php");
        exit(0);
    }

    // Additional validation for rate (ensure it's a valid number)
    if (!is_numeric($rate) || $rate < 0) {
        $_SESSION['error'] = "Rate must be a valid positive number.";
        header("Location: ../settings.php");
        exit(0);
    }

    // Use prepared statements for secure database update
    $query = "UPDATE payment_details SET network = ?, momo_name = ?, momo_number = ?, currency = ?, rate = ? WHERE id = 1"; // Added rate to query
    $stmt = $con->prepare($query);
    
    if ($stmt === false) {
        $_SESSION['error'] = "Database error: " . mysqli_error($con);
        header("Location: ../settings.php");
        exit(0);
    }

    $stmt->bind_param("ssssd", $network, $momo_name, $momo_number, $currency, $rate); // Changed to ssssd for rate (double)
    $query_run = $stmt->execute();

    if ($query_run) {
        $_SESSION['success'] = "Payment details updated successfully.";
        header("Location: ../settings.php");
        exit(0);
    } else {
        $_SESSION['error'] = "Failed to update payment details: " . $stmt->error;
        header("Location: ../settings.php");
        exit(0);
    }

    $stmt->close();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../settings.php");
    exit(0);
}
?>
