<?php
session_start();
include('../config/dbcon.php');

if (isset($_POST['signup'])) {
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    // You can add more fields like address, btc_wallet, etc., as needed
    $address = isset($_POST['address']) ? mysqli_real_escape_string($con, $_POST['address']) : '';
    $btc_wallet = isset($_POST['btc_wallet']) ? mysqli_real_escape_string($con, $_POST['btc_wallet']) : '';
    $balance = 0; // Default balance for new users
    $image = ''; // Default image or handle file upload separately

    // Optional: Add validation (e.g., check if email already exists)
    $check_email_query = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_run) > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: ../signup");
        exit(0);
    }

    // Hash the password for security (recommended)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $insert_query = "INSERT INTO users (name, email, password, address, btc_wallet, balance, image) 
                     VALUES ('$name', '$email', '$hashed_password', '$address', '$btc_wallet', '$balance', '$image')";
    $insert_query_run = mysqli_query($con, $insert_query);

    if ($insert_query_run) {
        // Get the newly created user's data
        $user_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $user_query_run = mysqli_query($con, $user_query);

        if (mysqli_num_rows($user_query_run) > 0) {
            foreach ($user_query_run as $data) {
                $user_id = $data['id'];
                $user_name = $data['name'];
                $balance = $data['balance'];
                $email = $data['email'];
                $image = $data['image'];
                $address = $data['address'];
                $btc_wallet = $data['btc_wallet'];
            }

            // Set session variables for login
            $_SESSION['auth'] = true;
            $_SESSION['email'] = $email;
            // Add more session variables if needed
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $user_name;
            $_SESSION['balance'] = $balance;
            $_SESSION['address'] = $address;
            $_SESSION['btc_wallet'] = $btc_wallet;
            $_SESSION['image'] = $image;

            // Redirect to the user dashboard
            header("Location: ../users/index");
            exit(0);
        } else {
            $_SESSION['error'] = "Error retrieving user data after registration.";
            header("Location: ../signup");
            exit(0);
        }
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: ../signup");
        exit(0);
    }
}
?>
