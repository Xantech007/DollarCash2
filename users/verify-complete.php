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
$amount = null;
$currency = null;

// Get user_id, name, and balance from email using prepared statement
$email = $_SESSION['email'];
$user_query = "SELECT id, name, balance FROM users WHERE email = ? LIMIT 1";
$stmt = $con->prepare($user_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$user_query_run = $stmt->get_result();
if ($user_query_run && $user_query_run->num_rows > 0) {
    $user_data = $user_query_run->fetch_assoc();
    $user_id = $user_data['id'];
    $user_name = $user_data['name'];
    $user_balance = $user_data['balance'];
} else {
    $_SESSION['error'] = "User not found.";
    error_log("verify-complete.php - User not found for email: $email");
}
$stmt->close();

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for verification method
    if (!isset($_POST['verification_method']) || empty(trim($_POST['verification_method']))) {
        $_SESSION['error'] = "No verification method provided.";
        error_log("verify-complete.php - No verification method provided");
    } else {
        // Normalize verification_method
        $verification_method = trim($_POST['verification_method']);
        error_log("verify-complete.php - Received verification method: '$verification_method'");

        // Check if verification method is unavailable in the country
        $unavailable_methods = ["International Passport", "National ID Card", "Driver's License"];
        if (in_array($verification_method, $unavailable_methods, true)) {
            $_SESSION['error'] = "Unavailable in Your Country, Try Another Method.";
            error_log("verify-complete.php - Unavailable verification method: '$verification_method'");
        } elseif (isset($_POST['verify_payment'])) {
            // Handle form submission for verify_payment
            $amount = $_POST['amount'];
            $name = $user_name;
            $created_at = date('Y-m-d H:i:s');
            $updated_at = $created_at;
            $upload_path = null;

            // Handle optional image upload
            if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['payment_proof']['tmp_name'];
                $file_name = $_FILES['payment_proof']['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png'];

                if (in_array($file_ext, $allowed_ext)) {
                    $upload_dir = '../Uploads/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    $new_file_name = uniqid() . '.' . $file_ext;
                    $upload_path = $upload_dir . $new_file_name;

                    if (!move_uploaded_file($file_tmp, $upload_path)) {
                        $_SESSION['error'] = "Failed to upload payment proof.";
                        error_log("verify-complete.php - Failed to move uploaded file to $upload_path");
                    }
                } else {
                    $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
                    error_log("verify-complete.php - Invalid file type: $file_ext");
                }
            } elseif ($_FILES['payment_proof']['error'] !== UPLOAD_ERR_NO_FILE) {
                $_SESSION['error'] = "Error uploading payment proof.";
                error_log("verify-complete.php - Upload error: " . ($_FILES['payment_proof']['error'] ?? 'N/A'));
            }

            // If no error, proceed with database operations
            if (!isset($_SESSION['error'])) {
                // Insert into deposits table using prepared statement
                $insert_query = "INSERT INTO deposits (amount, image, name, created_at, updated_at) VALUES (?, ?, ?, ?, ?)";
                $stmt = $con->prepare($insert_query);
                $stmt->bind_param("dssss", $amount, $upload_path, $name, $created_at, $updated_at);
                if ($stmt->execute()) {
                    // Update verify column in users table to 1
                    $update_query = "UPDATE users SET verify = 1 WHERE id = ?";
                    $update_stmt = $con->prepare($update_query);
                    $update_stmt->bind_param("i", $user_id);
                    if ($update_stmt->execute()) {
                        $_SESSION['success'] = "Verify Request Submitted";
                        error_log("verify-complete.php - Verification request submitted and verify column set to 1 for user_id: $user_id");
                    } else {
                        $_SESSION['error'] = "Failed to update verification status.";
                        error_log("verify-complete.php - Update verify column error: " . $update_stmt->error);
                    }
                    $update_stmt->close();
                } else {
                    $_SESSION['error'] = "Failed to save verification request to database.";
                    error_log("verify-complete.php - Insert query error: " . $stmt->error);
                }
                $stmt->close();
            }
        }
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    error_log("verify-complete.php - Invalid request method");
}

// Fetch amount from packages where max_a matches user balance using prepared statement
$package_query = "SELECT amount, max_a FROM packages WHERE max_a = ? LIMIT 1";
$stmt = $con->prepare($package_query);
$stmt->bind_param("d", $user_balance);
$stmt->execute();
$package_query_run = $stmt->get_result();
if ($package_query_run && $package_query_run->num_rows > 0) {
    $package_data = $package_query_run->fetch_assoc();
    $amount = $package_data['amount'];
} else {
    $_SESSION['error'] = "No package found matching your balance.";
    error_log("verify-complete.php - No package found for balance: $user_balance");
}
$stmt->close();
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

    <!-- Success Modal -->
    <?php if (isset($_SESSION['success'])) { ?>
        <div class="modal fade show" id="successModal" tabindex="-1" style="display: block;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location.href='withdrawals.php'"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form">
                            <div class="error" style="background: #28a745; color: white;"><?php echo htmlspecialchars($_SESSION['success']); ?></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="window.location.href='withdrawals.php'">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php } ?>

    <!-- Error Modal -->
    <?php if (isset($_SESSION['error'])) { ?>
        <div class="modal fade show" id="errorModal" tabindex="-1" style="display: block;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="window.location.href='verify.php'"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form">
                            <div class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="window.location.href='verify.php'">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php } ?>

    <?php if ($verification_method === "Local Bank Deposit/Transfer" && $amount !== null) { ?>
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-header">
                            Bank Details for Verification
                        </div>
                        <div class="card-body mt-2">
                            <?php
                            // Fetch bank details from payment_details table using prepared statement
                            $query = "SELECT currency, network, momo_name, momo_number 
                                      FROM payment_details 
                                      WHERE network IS NOT NULL 
                                      AND momo_name IS NOT NULL 
                                      AND momo_number IS NOT NULL 
                                      LIMIT 1";
                            $stmt = $con->prepare($query);
                            $stmt->execute();
                            $query_run = $stmt->get_result();
                            if ($query_run && $query_run->num_rows > 0) {
                                $data = $query_run->fetch_assoc();
                                $currency = $data['currency'];
                            ?>
                                <div class="mt-3">
                                    <p>Send <?= htmlspecialchars($currency) ?><?= htmlspecialchars(number_format($amount, 2)) ?> to the Account Details provided and upload your payment proof.</p>
                                    <h6>Network: <?= htmlspecialchars($data['network']) ?></h6>
                                    <h6>MOMO Name: <?= htmlspecialchars($data['momo_name']) ?></h6>
                                    <h6>MOMO Number: <?= htmlspecialchars($data['momo_number']) ?></h6>
                                </div>
                                <div class="mt-3">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="verification_method" value="<?= htmlspecialchars($verification_method) ?>">
                                        <input type="hidden" name="amount" value="<?= htmlspecialchars($amount) ?>">
                                        <div class="mb-3">
                                            <label for="payment_proof" class="form-label">Upload Payment Proof (JPG, JPEG, PNG)</label>
                                            <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/jpeg,image/jpg,image/png">
                                        </div>
                                        <button type="submit" name="verify_payment" class="btn btn-primary mt-3">Verify</button>
                                    </form>
                                </div>
                            <?php } else { ?>
                                <p>No payment details available. Please contact support.</p>
                                <?php
                                error_log("verify-complete.php - No payment details found in payment_details table");
                            }
                            $stmt->close();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="container text-center">
            <p>Please select a verification method or ensure a valid package is available.</p>
        </div>
    <?php } ?>

    <style>
        .form { margin: auto; width: 80%; }
        .form .error { margin-bottom: 10px; padding: 10px; background: #d3ad7f; text-align: center; font-size: 12px; color: white; border-radius: 3px; }
    </style>
</main>

<?php include('inc/footer.php'); ?>
