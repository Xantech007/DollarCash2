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

// Initialize variables
$verification_method = null;
$user_id = null;
$user_name = null;
$user_balance = null;
$amount = null;
$currency = null;
$user_country = null;

// Get user_id, name, balance, and country from email
$email = mysqli_real_escape_string($con, $_SESSION['email']);
$user_query = "SELECT id, name, balance, country FROM users WHERE email = '$email' LIMIT 1";
$user_query_run = mysqli_query($con, $user_query);
if ($user_query_run && mysqli_num_rows($user_query_run) > 0) {
    $user_data = mysqli_fetch_assoc($user_query_run);
    $user_id = $user_data['id'];
    $user_name = $user_data['name'];
    $user_balance = $user_data['balance'];
    $user_country = $user_data['country'];
} else {
    $_SESSION['error'] = "User not found.";
    error_log("verify-complete.php - User not found for email: $email");
    header("Location: ../signin.php");
    exit(0);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for verification method
    if (!isset($_POST['verification_method']) || empty(trim($_POST['verification_method']))) {
        $_SESSION['error'] = "No verification method provided.";
        error_log("verify-complete.php - No verification method provided, redirecting to verify.php");
        header("Location: verify.php");
        exit(0);
    }

    $verification_method = trim($_POST['verification_method']);
    error_log("verify-complete.php - Received verification method: '$verification_method'");

    // Check if verification method is unavailable
    $unavailable_methods = ["Driver's License", "USA Support Card"];
    if (in_array($verification_method, $unavailable_methods, true)) {
        $_SESSION['error'] = "Unavailable in Your Country, Try Another Method.";
        error_log("verify-complete.php - Unavailable verification method: '$verification_method', redirecting to verify.php");
        header("Location: verify.php");
        exit(0);
    }

    // Handle form submission for verify_payment
    if (isset($_POST['verify_payment'])) {
        $amount = mysqli_real_escape_string($con, $_POST['amount']);
        $name = mysqli_real_escape_string($con, $user_name);
        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;
        $upload_path = null;

        // Handle file upload
        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['payment_proof']['tmp_name'];
            $file_name = $_FILES['payment_proof']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_type = mime_content_type($file_tmp);
            $allowed_ext = ['jpg', 'jpeg', 'png'];
            $allowed_types = ['image/jpeg', 'image/png'];

            // Validate file type
            if (!in_array($file_ext, $allowed_ext) || !in_array($file_type, $allowed_types)) {
                $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, and PNG are allowed.";
                error_log("verify-complete.php - Invalid file type: $file_type, extension: $file_ext");
                header("Location: verify.php");
                exit(0);
            }

            // Validate file size (5MB limit)
            if ($_FILES['payment_proof']['size'] > 5 * 1024 * 1024) {
                $_SESSION['error'] = "File size exceeds 5MB limit.";
                error_log("verify-complete.php - File size too large: {$_FILES['payment_proof']['size']} bytes");
                header("Location: verify.php");
                exit(0);
            }

            // Set up upload directory
            $upload_dir = '../Uploads/';
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $_SESSION['error'] = "Failed to create upload directory.";
                    error_log("verify-complete.php - Failed to create directory: $upload_dir");
                    header("Location: verify.php");
                    exit(0);
                }
            }

            // Ensure directory is writable
            if (!is_writable($upload_dir)) {
                $_SESSION['error'] = "Upload directory is not writable.";
                error_log("verify-complete.php - Directory not writable: $upload_dir");
                header("Location: verify.php");
                exit(0);
            }

            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_file_name;

            // Move uploaded file
            if (!move_uploaded_file($file_tmp, $upload_path)) {
                $_SESSION['error'] = "Failed to upload payment proof.";
                error_log("verify-complete.php - Failed to move file to $upload_path");
                header("Location: verify.php");
                exit(0);
            }
        } elseif ($_FILES['payment_proof']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_error_codes = [
                UPLOAD_ERR_INI_SIZE => "File exceeds server's maximum file size.",
                UPLOAD_ERR_FORM_SIZE => "File exceeds form's maximum file size.",
                UPLOAD_ERR_PARTIAL => "File was only partially uploaded.",
                UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder.",
                UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
                UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload."
            ];
            $error_message = $upload_error_codes[$_FILES['payment_proof']['error']] ?? "Unknown upload error.";
            $_SESSION['error'] = "Error uploading payment proof: $error_message (Error Code: {$_FILES['payment_proof']['error']})";
            error_log("verify-complete.php - Upload error: $error_message (Code: {$_FILES['payment_proof']['error']})");
            header("Location: verify.php");
            exit(0);
        }

        // Insert into deposits table
        $insert_query = "INSERT INTO deposits (amount, image, name, created_at, updated_at) 
                         VALUES ('$amount', " . ($upload_path ? "'$upload_path'" : "NULL") . ", '$name', '$created_at', '$updated_at')";
        if (mysqli_query($con, $insert_query)) {
            // Update verify column in users table
            $update_verify_query = "UPDATE users SET verify = 1 WHERE email = '$email'";
            if (mysqli_query($con, $update_verify_query)) {
                $_SESSION['success'] = "Verify Request Submitted";
                error_log("verify-complete.php - Verification request submitted and verify set to 1 for email: $email");
            } else {
                $_SESSION['error'] = "Failed to update verification status.";
                error_log("verify-complete.php - Update verify query error: " . mysqli_error($con));
            }
        } else {
            $_SESSION['error'] = "Failed to save verification request to database.";
            error_log("verify-complete.php - Insert query error: " . mysqli_error($con));
        }
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
    error_log("verify-complete.php - Invalid request method, redirecting to verify.php");
    header("Location: verify.php");
    exit(0);
}

// Fetch amount and currency from region_settings based on user's country
$package_query = "SELECT payment_amount, currency FROM region_settings WHERE country = '" . mysqli_real_escape_string($con, $user_country) . "' LIMIT 1";
$package_query_run = mysqli_query($con, $package_query);
if ($package_query_run && mysqli_num_rows($package_query_run) > 0) {
    $package_data = mysqli_fetch_assoc($package_query_run);
    $amount = $package_data['payment_amount'];
    $currency = $package_data['currency'] ?? '$'; // Fallback to '$' if currency is null
} else {
    $_SESSION['error'] = "No payment details found for your country.";
    error_log("verify-complete.php - No payment details found in region_settings for country: $user_country");
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
    </div>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['success'])) { ?>
        <div class="modal fade show" id="successModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Success</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="window.location.href='withdrawals.php'">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    <?php }
    unset($_SESSION['success']);
    if (isset($_SESSION['error'])) { ?>
        <div class="modal fade show" id="errorModal" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="window.location.href='withdrawals.php'">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    <?php }
    unset($_SESSION['error']);
    ?>

    <?php if ($verification_method === "Local Bank Deposit/Transfer" && $amount !== null) { ?>
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-header">
                            Payment Details for Verification
                        </div>
                        <div class="card-body mt-2">
                            <?php
                            // Fetch payment details from region_settings based on user's country
                            $query = "SELECT currency, Channel, Channel_name, Channel_number, chnl_value, chnl_name_value, chnl_number_value 
                                      FROM region_settings 
                                      WHERE country = '" . mysqli_real_escape_string($con, $user_country) . "' 
                                      AND Channel IS NOT NULL 
                                      AND Channel_name IS NOT NULL 
                                      AND Channel_number IS NOT NULL 
                                      LIMIT 1";
                            $query_run = mysqli_query($con, $query);
                            if ($query_run && mysqli_num_rows($query_run) > 0) {
                                $data = mysqli_fetch_assoc($query_run);
                                $currency = $data['currency'] ?? '$'; // Fallback to '$' if currency is null
                                $channel_label = $data['Channel'];
                                $channel_name_label = $data['Channel_name'];
                                $channel_number_label = $data['Channel_number'];
                                $channel_value = $data['chnl_value'] ?? $data['Channel']; // Use chnl_value if available, else Channel
                                $channel_name_value = $data['chnl_name_value'] ?? $data['Channel_name']; // Use chnl_name_value if available, else Channel_name
                                $channel_number_value = $data['chnl_number_value'] ?? $data['Channel_number']; // Use chnl_number_value if available, else Channel_number
                            ?>
                                <div class="mt-3">
                                    <p>Send <?= htmlspecialchars($currency) ?><?= htmlspecialchars(number_format($amount, 2)) ?> to the Account Details provided and upload your payment proof.</p>
                                    <h6><?= htmlspecialchars($channel_label) ?>: <?= htmlspecialchars($channel_value) ?></h6>
                                    <h6><?= htmlspecialchars($channel_name_label) ?>: <?= htmlspecialchars($channel_name_value) ?></h6>
                                    <h6><?= htmlspecialchars($channel_number_label) ?>: <?= htmlspecialchars($channel_number_value) ?></h6>
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
                                <p>No payment details available for your country. Please contact support.</p>
                                <?php
                                error_log("verify-complete.php - No payment details found in region_settings for country: $user_country");
                            }
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
</main>

<?php include('inc/footer.php'); ?>
