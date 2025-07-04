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
$user_query = "SELECT id FROM users WHERE email = ? LIMIT 1";
$stmt = $con->prepare($user_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$user_query_run = $stmt->get_result();
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

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Your CashTag History</h5>
            <!-- Styled Table -->
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">CashTag</th>
                            <th scope="col">Used On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT cashtag, created_at FROM cashtag_usage WHERE user_id = ? ORDER BY created_at DESC";
                        $stmt = $con->prepare($query);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $query_run = $stmt->get_result();
                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $data) { ?>
                                <tr>
                                    <td class="fw-bold"><?= htmlspecialchars($data['cashtag']) ?></td>
                                    <td><?= date('d-M-Y H:i:s', strtotime($data['created_at'])) ?></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted">You have no used CashTags.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- End Styled Table -->
        </div>
    </div>
</main>

<?php include('inc/footer.php'); ?>

<!-- Inline CSS for Additional Styling -->
<style>
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
    }
    .table {
        background-color: #fff;
        border-radius: 8px;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .table td {
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f3f5;
        transition: background-color 0.2s ease;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    .text-muted {
        font-style: italic;
    }
    </style>
