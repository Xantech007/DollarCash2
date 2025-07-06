<?php
session_start();
include('../config/dbcon.php');

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $referal = mysqli_real_escape_string($con, $_POST['ref']);    
    $token = md5(rand());

    $checkemail = "SELECT email FROM users WHERE email='$email'";
    $checkemail_run = mysqli_query($con, $checkemail);

    if (!mysqli_num_rows($checkemail_run) > 0) {
        $password_length = strlen($password);
        if ($password_length >= 4) {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (name, email, password, refered_by, verify_token) VALUES ('$name', '$email', '$hashed_password', '$referal', '$token')";
            $query_run = mysqli_query($con, $query);
            if ($query_run) {
                // Fetch the newly registered user's ID
                $user_id = mysqli_insert_id($con);

                // Set session variables for auto-login
                $_SESSION['auth'] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['success'] = "Registered successfully";

                // Redirect to the dashboard or desired page
                header("Location: ../users/index");
                exit(0);
            } else {
                $_SESSION['error'] = "Oops something went wrong!";
                header("Location: ../signup");
                exit(0);
            }
        } else {
            $_SESSION['error'] = "Password must be more than 3 characters!";
            header("Location: ../signup");
            exit(0);
        }
    } else {
        $_SESSION['error'] = "This email is already registered!";
        header("Location: ../signup");
        exit(0);
    }
}
