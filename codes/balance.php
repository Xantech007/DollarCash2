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
    exit();
}

// Check CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['error'] = "Invalid CSRF token.";
    error_log("balance.php - Invalid CSRF token");
    header("Location: ../users/scan-results.php");
    exit();
}

// Check if the form is submitted
if (isset($_POST['add_balance']) && isset($_POST['id'])) {
    $package_id = intval($_POST['id']); // Sanitize input
    $user_id = $_SESSION['user_id']; // Get logged-in user ID

    // Debugging: Log input data
    error_log("balance.php - Package ID: $package_id, User ID: $user_id");

    // Start transaction
    mysqli_begin_transaction($con);
    try {
        // Fetch the package details
        $query = "SELECT max_a FROM packages WHERE id = ? AND status = '0'";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $package_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $amount = $row['max_a'];

            // Update the user's balance
            $update_query = "UPDATE users SET balance = balance + ? WHERE id = ?";
            $update_stmt = mysqli_prepare($con, $update_query);
            mysqli_stmt_bind_param($update_stmt, "di", $amount, $user_id);

            if (mysqli_stmt_execute($update_stmt)) {
                $_SESSION['success'] = "Balance updated successfully! Added $" . number_format($amount, 2) . ".";
                error_log("balance.php - Balance updated: $amount for user $user_id");
                mysqli_commit($con);
            } else {
                throw new Exception("Failed to update balance: " . mysqli_error($con));
            }
        } else {
            throw new Exception("Invalid or inactive package selected.");
        }

        // Close statements
        mysqli_stmt_close($stmt);
        if (isset($update_stmt)) {
            mysqli_stmt_close($update_stmt);
        }
    } catch (Exception $e) {
        mysqli_rollback($con);
        $_SESSION['error'] = $e->getMessage();
        error_log("balance.php - Error: " . $e->getMessage());
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    error_log("balance.php - Invalid request: POST data missing");
}

// Redirect to scan-results.php
header("Location: ../users/scan-results.php");
exit();
?>
