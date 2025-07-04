<?php
session_start();
include('../inc/config.php'); // Assuming this file contains your database connection ($con)

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to add balance.";
    header("Location: ../invest.php");
    exit();
}

// Check if the form is submitted
if (isset($_POST['add_balance']) && isset($_POST['id'])) {
    $package_id = intval($_POST['id']); // Sanitize input
    $user_id = $_SESSION['user_id']; // Get logged-in user ID

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
            $_SESSION['success'] = "Balance updated successfully! Added $$amount.";
        } else {
            $_SESSION['error'] = "Failed to update balance. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Invalid or inactive package selected.";
    }

    // Close statements
    mysqli_stmt_close($stmt);
    if (isset($update_stmt)) {
        mysqli_stmt_close($update_stmt);
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

// Redirect to invest.php
header("Location: ../invest.php");
exit();
?>
