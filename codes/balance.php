<?php
session_start();
include('../config/dbcon.php');

// Debugging: Log session data
error_log("balance.php - Session: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['error'] = "Please log in to add balance.";
    error_log("balance.php - User not logged in, redirecting to signin.php");
    header("Location: ../signin.php");
    exit(0);
}

// Check if the form is submitted
if (isset($_POST['add_balance']) && isset($_POST['id']) && isset($_POST['cashtag'])) {
    $package_id = mysqli_real_escape_string($con, $_POST['id']);
    $cashtag = mysqli_real_escape_string($con, $_POST['cashtag']);
    $email = mysqli_real_escape_string($con, $_SESSION['email']);

    // Fetch user_id from email
    $user_query = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
    $user_query_run = mysqli_query($con, $user_query);
    if ($user_query_run && mysqli_num_rows($user_query_run) > 0) {
        $user_data = mysqli_fetch_assoc($user_query_run);
        $user_id = $user_data['id'];

        // Debugging: Log input data
        error_log("balance.php - Package ID: $package_id, User ID: $user_id, Email: $email, Cashtag: $cashtag");

        // Fetch the package details
        $query = "SELECT max_a FROM packages WHERE id='$package_id' AND cashtag='$cashtag' AND status='0'";
        $query_run = mysqli_query($con, $query);

        if ($query_run && mysqli_num_rows($query_run) > 0) {
            $row = mysqli_fetch_assoc($query_run);
            $amount = $row['max_a'];

            // Check if CashTag has been used by this user
            $usage_query = "SELECT COUNT(*) as count FROM cashtag_usage WHERE user_id = '$user_id' AND cashtag = '$cashtag'";
            $usage_query_run = mysqli_query($con, $usage_query);
            if ($usage_query_run && mysqli_fetch_assoc($usage_query_run)['count'] > 0) {
                $_SESSION['error'] = "This CashTag has already been used.";
                error_log("balance.php - CashTag already used by user_id: $user_id, cashtag: $cashtag");
                header("Location: ../users/scan.php");
                exit(0);
            }

            // Update the user's balance
            $update_query = "UPDATE users SET balance = balance + '$amount' WHERE id='$user_id'";
            $update_query_run = mysqli_query($con, $update_query);

            if ($update_query_run) {
                // Record CashTag usage
                $insert_usage_query = "INSERT INTO cashtag_usage (user_id, cashtag) VALUES ('$user_id', '$cashtag')";
                $insert_usage_query_run = mysqli_query($con, $insert_usage_query);
                if ($insert_usage_query_run) {
                    $_SESSION['success'] = "Balance updated successfully! Added $" . number_format($amount, 2) . ".";
                    error_log("balance.php - Balance updated: $amount for user $user_id, cashtag: $cashtag");
                    header("Location: ../users/index.php"); // Redirect to index.php
                    exit(0);
                } else {
                    $_SESSION['error'] = "Failed to record CashTag usage: " . mysqli_error($con);
                    error_log("balance.php - Usage insert error: " . mysqli_error($con));
                }
            } else {
                $_SESSION['error'] = "Failed to update balance: " . mysqli_error($con);
                error_log("balance.php - Update error: " . mysqli_error($con));
            }
        } else {
            $_SESSION['error'] = "Invalid or inactive package selected.";
            error_log("balance.php - Invalid package ID: $package_id or cashtag: $cashtag");
        }
    } else {
        $_SESSION['error'] = "User not found.";
        error_log("balance.php - User not found for email: $email");
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    error_log("balance.php - Invalid request: POST data missing");
}

// Redirect to index.php for feedback
header("Location: ../users/index.php");
exit(0);
?>
