<?php
session_start();
include('../config/dbcon.php');
include('inc/header.php');
include('inc/navbar.php');

// Check if user is logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['error'] = "Please log in to access this page.";
    error_log("scan-results.php - User not logged in, redirecting to signin.php");
    header("Location: ../signin.php");
    exit(0);
}

// Debugging: Log session data
error_log("scan-results.php - Session: " . print_r($_SESSION, true));

// Check if CashTag is submitted
if (!isset($_POST['scan_input']) || empty(trim($_POST['scan_input']))) {
    $_SESSION['error'] = "No CashTag provided.";
    error_log("scan-results.php - No CashTag provided, redirecting to scan.php");
    header("Location: scan.php");
    exit(0);
}

// Get user_id from email
$email = mysqli_real_escape_string($con, $_SESSION['email']);
$user_query = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
$user_query_run = mysqli_query($con, $user_query);
if ($user_query_run && mysqli_num_rows($user_query_run) > 0) {
    $user_data = mysqli_fetch_assoc($user_query_run);
    $user_id = $user_data['id'];
} else {
    $_SESSION['error'] = "User not found.";
    error_log("scan-results.php - User not found for email: $email");
    header("Location: ../signin.php");
    exit(0);
}

// Validate CashTag
$cashtag = mysqli_real_escape_string($con, trim($_POST['scan_input']));
$cashtag_query = "SELECT COUNT(*) as count FROM packages WHERE cashtag = '$cashtag' AND status = '0'";
$cashtag_query_run = mysqli_query($con, $cashtag_query);

if ($cashtag_query_run) {
    $cashtag_result = mysqli_fetch_assoc($cashtag_query_run);
    if ($cashtag_result['count'] == 0) {
        $_SESSION['error'] = "Invalid CashTag.";
        error_log("scan-results.php - Invalid CashTag: $cashtag, redirecting to scan.php");
        header("Location: scan.php");
        exit(0);
    }
} else {
    $_SESSION['error'] = "Error validating CashTag. Please try again.";
    error_log("scan-results.php - CashTag query error: " . mysqli_error($con));
    header("Location: scan.php");
    exit(0);
}

// Check if CashTag has been used by this user
$usage_query = "SELECT COUNT(*) as count FROM cashtag_usage WHERE user_id = '$user_id' AND cashtag = '$cashtag'";
$usage_query_run = mysqli_query($con, $usage_query);

if ($usage_query_run) {
    $usage_result = mysqli_fetch_assoc($usage_query_run);
    if ($usage_result['count'] > 0) {
        $_SESSION['error'] = "This CashTag has already been used.";
        error_log("scan-results.php - CashTag already used by user_id: $user_id, cashtag: $cashtag");
        header("Location: scan.php");
        exit(0);
    }
} else {
    $_SESSION['error'] = "Error checking CashTag usage. Please try again.";
    error_log("scan-results.php - Usage query error: " . mysqli_error($con));
    header("Location: scan.php");
    exit(0);
}
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>CashTag Found! Select Amount</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../users/index.php">Home</a></li>
        <li class="breadcrumb-item">Scan</li>
        <li class="breadcrumb-item active">Results</li>
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
      console.log("Redirecting to index.php in 3 seconds...");
      setTimeout(() => {
        window.location.href = '../users/index.php';
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
      console.log("Redirecting to index.php in 3 seconds due to error...");
      setTimeout(() => {
        window.location.href = '../users/index.php';
      }, 3000);
    </script>
  <?php }
  unset($_SESSION['error']);
  ?>

  <div class="container text-center">
    <div class="row">
      <?php
      // Fetch packages for the valid CashTag
      $query = "SELECT * FROM packages WHERE cashtag = '$cashtag' AND status = '0' ORDER BY created_at DESC";
      $query_run = mysqli_query($con, $query);
      if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
          foreach ($query_run as $data) { ?>
            <div class="col-md-4">
              <div class="card text-center">
                <div class="card-header">
                  <?= htmlspecialchars($data['name']) ?>
                Kauf</div>
                <div class="card-body mt-2">
                  <div class="mt-3">
                    <h6>Amount: $<?= htmlspecialchars(number_format($data['max_a'], 2)) ?></h6>
                  </div>
                  <div class="mt-3">
                    <form action="../codes/balance.php" method="POST">
                      <input type="hidden" name="id" value="<?= $data['id'] ?>">
                      <button type="submit" name="add_balance" class="btn btn-outline-secondary mt-3">Add Balance</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php }
        } else {
          echo '<p>No active packages found for this CashTag.</p>';
        }
      } else {
        $_SESSION['error'] = "Failed to fetch packages. Please try again.";
        error_log("scan-results.php - Package query error: " . mysqli_error($con));
        header("Location: scan.php");
        exit(0);
      }
      ?>
    </div>
  </div>
</main>

<?php include('inc/footer.php'); ?>
