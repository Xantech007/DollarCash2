<?php
session_start();
include('../config/dbcon.php');
include('inc/header.php');
include('inc/navbar.php');

// Debug: Log session data
error_log("scan-results.php - Session ID: " . session_id());
error_log("scan-results.php - User ID: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'));

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to view scan results.";
    header("Location: ../login.php"); // Adjust to your login page
    exit();
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
  </div>

  <!-- Success/Error Messages -->
  <?php
  if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['success']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <script>
      console.log("Redirecting to index.php...");
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
      console.log("Redirecting to index.php...");
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
      $query = "SELECT * FROM packages WHERE status = '0' ORDER BY created_at DESC";
      $query_run = mysqli_query($con, $query);
      if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
          foreach ($query_run as $data) { ?>
            <div class="col-md-4">
              <div class="card text-center">
                <div class="card-header">
                  <?= htmlspecialchars($data['name']) ?>
                </div>
                <div class="card-body mt-2">
                  <div class="mt-3">
                    <h6>Amount: $<?= htmlspecialchars(number_format($data['max_a'], 2)) ?></h6>
                  </div>
                  <div class="mt-3">
                    <form action="../codes/balance.php" method="POST">
                      <input type="hidden" name="id" value="<?= $data['id'] ?>">
                      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>">
                      <button type="submit" name="add_balance" class="btn btn-outline-secondary mt-3">Add Balance</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php }
        } else {
          echo '<p>No active packages found.</p>';
        }
      } else {
        $_SESSION['error'] = "Failed to fetch packages: " . mysqli_error($con);
        error_log("scan-results.php - Query error: " . mysqli_error($con));
      }
      ?>
    </div>
  </div>
</main>

<?php include('inc/footer.php'); ?>
