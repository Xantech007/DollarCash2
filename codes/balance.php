<?php
session_start();
include('../config/dbcon.php');

// Debugging: Log session data
error_log("balance.php - Session: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to add balance.";
    error_log("balance.php - User not logged in, redirecting to scan-results.php");
    header("Location: ../users/scan-results.php");
    exit(0);
}

// Check CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "Invalid CSRF token.";
    error_log("balance.php - Invalid CSRF token");
    header("Location: ../users/scan-results.php");
    exit(0);
}

// Check if the form is submitted
if (isset($_POST['add_balance']) && isset($_POST['id'])) {
    $package_id = mysqli_real_escape_string($con, $_POST['id']);
    $user_id = mysqli_real_escape_string($con, $_SESSION['user_id']);

    // Debugging: Log input data
    error_log("balance.php - Package ID: $package_id, User ID: $user_id");

    // Fetch the package details
    $query = "SELECT max_a FROM packages WHERE id='$package_id' AND status='0'";
    $query_run = mysqli_query($con, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        $amount = $row['max_a'];

        // Update the user's balance
        $update_query = "UPDATE users SET balance = balance + '$amount' WHERE id='$user_id'";
        $update_query_run = mysqli_query($con, $update_query);

        if ($update_query_run) {
            $_SESSION['success'] = "Balance updated successfully! Added $" . number_format($amount, 2) . ".";
            error_log("balance.php - Balance updated: $amount for user $user_id");
        } else {
            $_SESSION['error'] = "Failed to update balance: " . mysqli_error($con);
            error_log("balance.php - Update error: " . mysqli_error($con));
        }
    } else {
        $_SESSION['error'] = "Invalid or inactive package selected.";
        error_log("balance.php - Invalid package ID: $package_id");
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    error_log("balance.php - Invalid request: POST data missing");
}

// Redirect to scan-results.php
header("Location: ../users/scan-results.php");
exit(0);
?>
