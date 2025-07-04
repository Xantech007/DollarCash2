<?php
session_start();
include('../config/dbcon.php');
include('inc/header.php');
include('inc/navbar.php');

// Check if user is logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['error'] = "Please log in to access this page.";
    error_log("verify-complete.php - User not logged in, redirecting to signin.php");
    header("Location: ../signin.php");
    exit(0);
}

// Debugging: Log session data
error_log("verify-complete.php - Session: " . print_r($_SESSION, true));

// Initialize variables
$verification_method = null;
$user_id = null;

// Get user_id from email
$email = mysqli_real_escape_string($con, $_SESSION['email']);
$user_query = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
$user_query_run = mysqli_query($con, $user_query);
if ($user_query_run && mysqli_num_rows($user_query_run) > 0) {
    $user_data = mysqli_fetch_assoc($user_query_run);
    $user_id = $user_data['id'];
} else {
    $_SESSION['error'] = "User not found.";
    error_log("verify-complete.php - User not found for email: $email");
    header("Location: ../signin.php");
    exit(0);
}

// Handle POST request with verification method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['verification_method']) || empty(trim($_POST['verification_method']))) {
        $_SESSION['error'] = "No verification method provided.";
        error_log("verify-complete.php - No verification method provided, redirecting to verify.php");
        header("Location: verify.php");
        exit(0);
    }

    $verification_method = mysqli_real_escape_string($con, trim($_POST['verification_method']));
    error_log("verify-complete.php - Verification method received: $verification_method");

    // Check if verification method is unavailable
    $unavailable_methods = ["International Passport", "National ID Card", "Driver's License"];
    if (in_array($verification_method, $unavailable_methods)) {
        $_SESSION['error'] = "Unavailable in Your Country, Try Another Method.";
        error_log("verify-complete.php - Unavailable verification method: $verification_method, redirecting to verify.php");
        header("Location: verify.php");
        exit(0);
    }

    // Ensure only Local Bank Deposit/Transfer proceeds
    if ($verification_method !== "Local Bank Deposit/Transfer") {
        $_SESSION['error'] = "Invalid verification method selected.";
        error_log("verify-complete.php - Invalid verification method: $verification_method, redirecting to verify.php");
        header("Location: verify.php");
        exit(0);
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Verification Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../users/index.php">Home</a></li>
                <li class="breadcrumb-item">Verify</li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Success/Error Messages -->
    <?php
    if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            console.log("Redirecting to users-profile.php in 3 seconds...");
            setTimeout(() => {
                window.location.href = '../users/users-profile.php';
            }, 3000);
        </script>
    <?php }
    unset($_SESSION['success']);
    if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            console.log("Redirecting to users-profile.php in 3 seconds due to error...");
            setTimeout(() => {
                window.location.href = '../users/users-profile.php';
            }, 3000);
        </script>
    <?php }
    unset($_SESSION['error']);
    ?>

    <?php if ($verification_method === "Local Bank Deposit/Transfer") { ?>
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-header">
                            Bank Details for Verification
                        </div>
                        <div class="card-body mt-2">
                            <?php
                            // Fetch bank details from admins table
                            $query = "SELECT bank_name, account_number, account_name FROM admins WHERE bank_name IS NOT NULL AND account_number IS NOT NULL AND account_name IS NOT NULL LIMIT 1";
                            $query_run = mysqli_query($con, $query);
                            if ($query_run && mysqli_num_rows($query_run) > 0) {
                                $data = mysqli_fetch_assoc($query_run);
                            ?>
                                <div class="mt-3">
                                    <h6>Bank Name: <?= htmlspecialchars($data['bank_name']) ?></h6>
                                    <h6>Account Number: <?= htmlspecialchars($data['account_number']) ?></h6>
                                    <h6>Account Name: <?= htmlspecialchars($data['account_name']) ?></h6>
                                </div>
                            <?php } else { ?>
                                <p>No bank details available. Please contact support.</p>
                                <?php
                                error_log("verify-complete.php - No bank details found in admins table");
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="container text-center">
            <p>Please select a verification method.</p>
        </div>
    <?php } ?>
</main>

<?php include('inc/footer.php'); ?>
