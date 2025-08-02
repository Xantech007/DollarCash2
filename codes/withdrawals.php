<?php
session_start();
include('../config/dbcon.php');

if (isset($_POST['withdraw'])) {
    // Sanitize inputs
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $balance = mysqli_real_escape_string($con, $_POST['balance']);
    $channel = mysqli_real_escape_string($con, $_POST['channel']);
    $channel_name = mysqli_real_escape_string($con, $_POST['channel_name']);
    $channel_number = mysqli_real_escape_string($con, $_POST['channel_number']);

    // Check if the user's account is verified
    $verify_query = "SELECT verify, country FROM users WHERE email = ? LIMIT 1";
    $stmt = $con->prepare($verify_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $verify_result = $stmt->get_result();

    if ($verify_result && $verify_result->num_rows > 0) {
        $user = $verify_result->fetch_assoc();
        $user_country = $user['country'];
        if ($user['verify'] == 0) {
            $_SESSION['error'] = "Verify Your Account and Try Again.";
            header("Location: ../users/withdrawals.php");
            exit(0);
        } elseif ($user['verify'] == 1) {
            $_SESSION['error'] = "Verification Under Review, Try Again Later.";
            header("Location: ../users/withdrawals.php");
            exit(0);
        } elseif ($user['verify'] != 2) {
            $_SESSION['error'] = "Invalid verification status.";
            header("Location: ../users/withdrawals.php");
            exit(0);
        }
    } else {
        $_SESSION['error'] = "User not found.";
        header("Location: ../users/withdrawals.php");
        exit(0);
    }
    $stmt->close();

    // Validate inputs
    if (empty($channel) || empty($channel_name) || empty($channel_number)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../users/withdrawals.php");
        exit(0);
    }

    if ($amount < 50) {
        $_SESSION['error'] = "Minimum withdrawal is set at $50";
        header("Location: ../users/withdrawals.php");
        exit(0);
    }

    if ($amount > $balance) {
        $_SESSION['error'] = "Request failed due to insufficient balance!";
        header("Location: ../users/withdrawals.php");
        exit(0);
    }

    // Fetch currency from region_settings based on user's country
    $payment_query = "SELECT currency FROM region_settings WHERE country = ? LIMIT 1";
    $stmt = $con->prepare($payment_query);
    $stmt->bind_param("s", $user_country);
    $stmt->execute();
    $payment_result = $stmt->get_result();

    if ($payment_result && $payment_result->num_rows > 0) {
        $payment = $payment_result->fetch_assoc();
        $currency = $payment['currency'] ?? '$';
    } else {
        $_SESSION['error'] = "Failed to fetch payment details for your region.";
        header("Location: ../users/withdrawals.php");
        exit(0);
    }
    $stmt->close();

    // Note: The frontend doesn't provide a 'rate' for conversion, so we'll assume amount is in USD
    // If you have a rate in region_settings or another table, add it to the query above
    // For now, we'll store the amount as-is (in USD) since the frontend displays balance in USD
    $total = $amount; // Adjust if you have a conversion rate

    // Insert withdrawal request using prepared statement
    $query = "INSERT INTO withdrawals (email, amount, channel, channel_name, channel_number, status, created_at) 
              VALUES (?, ?, ?, ?, ?, '0', NOW())";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sdsss", $email, $total, $channel, $channel_name, $channel_number);

    if ($stmt->execute()) {
        // Update the user's balance
        $new_balance = $balance - $amount;
        $update_query = "UPDATE users SET balance = ? WHERE email = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param("ds", $new_balance, $email);

        if ($update_stmt->execute()) {
            $_SESSION['success'] = "$currency" . number_format($total, 2) . " withdrawal request submitted successfully for $channel_name.";
            header("Location: ../users/withdrawals.php");
            exit(0);
        } else {
            $_SESSION['error'] = "Failed to update balance.";
            header("Location: ../users/withdrawals.php");
            exit(0);
        }
        $update_stmt->close();
    } else {
        $_SESSION['error'] = "Failed to submit withdrawal request.";
        header("Location: ../users/withdrawals.php");
        exit(0);
    }
    $stmt->close();
}

if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($con, $_POST['delete']);

    // Use prepared statement for deletion
    $delete_query = "DELETE FROM withdrawals WHERE id = ? LIMIT 1";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Withdrawal request deleted successfully.";
        header("Location: ../users/withdrawals.php");
        exit(0);
    } else {
        $_SESSION['error'] = "Failed to delete withdrawal request.";
        header("Location: ../users/withdrawals.php");
        exit(0);
    }
    $stmt->close();
}

$con->close();
?>
