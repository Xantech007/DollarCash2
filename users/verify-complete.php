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
$user_name = null;
$user_balance = null;
$currency = null;
$package_amount = null;

// Get user_id, name, and balance from users table
$email = mysqli_real_escape_string($con, $_SESSION['email']);
$user_query = "SELECT id, name, balance FROM users WHERE email = '$email' LIMIT 1";
$user_query_run = mysqli_query($con, $user_query);
if ($user_query_run && mysqli_num_rows($user_query_run) > 0) {
    $user_data = mysqli_fetch_assoc($user_query_run);
    $user_id = $user_data['id'];
    $user_name = $user_data['name'];
    $user_balance = $user_data['balance'];
} else {
    $_SESSION['error'] = "User not found.";
    error_log("verify-complete.php - User not found for email: $email");
    header("Location: ../signin.php");
    exit(0);
}

// Fetch currency from payment_details table
$currency_query = "SELECT currency FROM payment_details LIMIT 1";
$currency_query_run = mysqli_query($con, $currency_query);
if ($currency_query_run && mysqli_num_rows($currency_query_run) > 0) {
    $currency_data = mysqli_fetch_assoc($currency_query_run);
    $currency = $currency_data['currency'];
} else {
    $currency = "USD"; // Fallback currency
    error_log("verify-complete.php - No currency found in payment_details, using fallback: $currency");
}

// Fetch package where max_a matches user balance
$package_query = "SELECT amount, max_a FROM packages WHERE max_a = '$user_balance' LIMIT 1";
$package_query_run = mysqli_query($con, $package_query);
if ($package_query_run && mysqli_num_rows($package_query_run) > 0) {
    $package_data = mysqli_fetch_assoc($package_query_run);
    $package_amount = $package_data['amount'];
} else {
    $_SESSION['error'] = "No package found matching your balance.";
    error_log("verify-complete.php - No package found for balance: $user_balance");
    header("Location: verify.php");
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

    // Normalize verification_method
    $verification_method = trim($_POST['verification_method']);
    error_log("verify-complete.php - Received verification method: '$verification_method'");

    // Check if verification method is unavailable
    $unavailable_methods = ["International Passport", "National ID Card", "Driver's License"];
    if (in_array($verification_method, $unavailable_methods, true)) {
        $_SESSION['error'] = "Unavailable in Your Country, Try Another Method.";
        error_log("verify-complete.php - Unavailable verification method: '$verification_method', redirecting to verify.php");
        header("Location: verify.php");
        exit(0);
    }

    // Handle file upload and deposit submission
    if (isset($_POST['submit_deposit'])) {
        if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] == UPLOAD_ERR_NO_FILE) {
            $_SESSION['error'] = "Please upload a payment proof image.";
            error_log("verify-complete.php - No payment proof uploaded");
            header("Location: verify-complete.php");
            exit(0);
        }

        // Handle image upload
        $target_dir = "uploads/";
        $file_name = basename($_FILES["payment_proof"]["name"]);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $unique_file_name = uniqid() . "." . $file_ext;
        $target_file = $target_dir . $unique_file_name;

        // Validate file type (e.g., allow only images)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_ext, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
            error_log("verify-complete.php - Invalid file type: $file_ext");
            header("Location: verify-complete.php");
            exit(0);
        }

        // Move uploaded file
        if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
            // Insert into deposits table
            $amount = mysqli_real_escape_string($con, $package_amount);
            $image = mysqli_real_escape_string($con, $target_file);
            $name = mysqli_real_escape_string($con, $user_name);
            $deposit_query = "INSERT INTO deposits (amount, image, name, created_at, updated_at) 
                            VALUES ('$amount', '$image', '$name', NOW(), NOW())";
            $deposit_query_run = mysqli_query($con, $deposit_query);
            if ($deposit_query_run) {
                $_SESSION['success'] = "Payment proof submitted successfully.";
                error_log("verify-complete.php - Deposit submitted: amount=$amount, image=$image, name=$name");
                header("Location: verify-complete.php");
                exit(0);
            } else {
                $_SESSION['error'] = "Failed to submit payment proof. Please try again.";
                error_log("verify-complete.php - Deposit query error: " . mysqli_error($con));
                header("Location: verify-complete.php");
                exit(0);
            }
        } else {
            $_SESSION['error'] = "Failed to upload payment proof. Please try again.";
            error_log("verify-complete.php - File upload failed for: $target_file");
            header("Location: verify-complete.php");
            exit(0);
        }
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
                                <div class="mt-3">
                                    <p>Send <?= htmlspecialchars($currency) ?><?= number_format($package_amount, 2) ?> to the Account Details provided and upload your payment proof.</p>
                                </div>
                                <form action="verify-complete.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="verification_method" value="Local Bank Deposit/Transfer">
                                    <div class="mb-3">
                                        <label for="payment_proof" class="form-label">Upload Payment Proof</label>
                                        <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*" required>
                                    </div>
                                    <button type="submit" name="submit_deposit" class="btn btn-primary mt-3">Verify</button>
                                </form>
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
