<?php
session_start();
include('../config/dbcon.php');
include('inc/header.php');
include('inc/navbar.php');

// Check if user is logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['error'] = "Please log in to access this page.";
    error_log("used-cashtag.php - User not logged in, redirecting to signin.php");
    header("Location: ../signin.php");
    exit(0);
}

// Debugging: Log session data
error_log("used-cashtag.php - Session: " . print_r($_SESSION, true));

// Get user_id from email
$email = mysqli_real_escape_string($con, $_SESSION['email']);
$user_query = "SELECT id FROM users WHERE email = '$email' LIMIT 1";
$user_query_run = mysqli_query($con, $user_query);
if ($user_query_run && mysqli_num_rows($user_query_run) > 0) {
    $user_data = mysqli_fetch_assoc($user_query_run);
    $user_id = $user_data['id'];
} else {
    $_SESSION['error'] = "User not found.";
    error_log("used-cashtag.php - User not found for email: $email");
    header("Location: ../signin.php");
    exit(0);
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Used CashTags</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Used CashTags</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-body">
            <!-- Bordered Table -->
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">User ID</th>
                            <th scope="col">CashTag</th>
                            <th scope="col">Used On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT id, user_id, cashtag, created_at FROM cashtag_usage WHERE user_id = '$user_id' ORDER BY created_at DESC";
                        $query_run = mysqli_query($con, $query);
                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $data) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['id']) ?></td>
                                    <td><?= htmlspecialchars($data['user_id']) ?></td>
                                    <td><?= htmlspecialchars($data['cashtag']) ?></td>
                                    <td><?= date('d-M-Y H:i:s', strtotime($data['created_at'])) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="4">You have no used CashTags.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- End Bordered Table -->
        </div>
    </div>
</main>

<?php include('inc/footer.php'); ?>
