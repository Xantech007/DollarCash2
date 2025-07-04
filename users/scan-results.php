<?php
session_start();
include('../config/dbcon.php');
include('inc/header.php');
include('inc/navbar.php');

// Debugging: Log session data
error_log("scan-results.php - Session: " . print_r($_SESSION, true));

// Check if user is logged in
if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    $_SESSION['error'] = "Please log in to view this page.";
    error_log("scan-results.php - User not logged in, redirecting to login.php");
    header("Location: ../login.php");
    exit(0);
}

// Fetch user balance
$user_id = isset($_SESSION['user_id']) ? mysqli_real_escape_string($con, $_SESSION['user_id']) : null;
$email = isset($_SESSION['email']) ? mysqli_real_escape_string($con, $_SESSION['email']) : null;
if ($user_id) {
    $user_query = "SELECT balance FROM users WHERE id='$user_id'";
} elseif ($email) {
    $user_query = "SELECT balance FROM users WHERE email='$email'";
} else {
    $_SESSION['error'] = "Session data missing.";
    header("Location: ../login.php");
    exit(0);
}
$user_result = mysqli_query($con, $user_query);
$balance = mysqli_fetch_assoc($user_result)['balance'] ?? '0.00';

// Fetch package details
$package = null;
if (isset($_GET['I'])) {
    $id = mysqli_real_escape_string($con, $_GET['I']);
    $query = "SELECT * FROM packages WHERE id='$id' AND status='0' LIMIT 1";
    $query_run = mysqli_query($con, $query);
    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $package = mysqli_fetch_array($query_run);
    } else {
        $_SESSION['error'] = "Invalid or inactive package.";
        error_log("scan-results.php - Invalid package ID: $id");
    }
} else {
    $_SESSION['error'] = "No package selected.";
    error_log("scan-results.php - No package ID provided");
}
?>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Available Balance: $<?= htmlspecialchars(number_format($balance, 2)) ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../users/index.php">Home</a></li>
        <li class="breadcrumb-item">Users</li>
        <li class="breadcrumb-item active">Scan Results</li>
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

  <div class="container">
    <div class="row">
      <div class="card">
        <?php if ($package) { ?>
          <form action="../codes/balance.php" method="POST">
            <div class="row">
              <div class="col-md-6 form-group mb-3">
                <label for="name" class="mb-2">Package name</label>
                <input name="name" type="text" class="form-control" required value="<?= htmlspecialchars($package['name']) ?>" readonly>
              </div>
              <div class="col-md-6 form-group mb-3">
                <label for="amount" class="mb-2">Amount</label>
                <input name="amount" type="number" class="form-control" required value="<?= htmlspecialchars($package['max_a']) ?>" readonly>
              </div>
              <input type="hidden" value="<?= htmlspecialchars($email ?? '') ?>" name="email">
              <input type="hidden" value="<?= htmlspecialchars($package['id']) ?>" name="id">
              <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>">
            </div>
            <button type="submit" class="btn btn-secondary" name="add_balance">Add Balance</button>
          </form>
          <div class="add-btn">
            <a href="../users/scan-results.php" class="btn btn-secondary">Back</a>
          </div>
        <?php } else { ?>
          <p>No package found. Please select a valid package.</p>
        <?php } ?>
      </div>
    </div>
  </div>

  <style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .add-btn {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      margin: 15px 0;
    }
  </style>
</main>

<?php include('inc/footer.php'); ?>
