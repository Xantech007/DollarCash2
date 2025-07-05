<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
include('inc/sidebar.php');

// Fetch currency from payment_details
$currency_query = "SELECT currency FROM payment_details LIMIT 1";
$currency_result = mysqli_query($con, $currency_query);
$currency = '$'; // Default fallback
if ($currency_result && mysqli_num_rows($currency_result) > 0) {
    $row = mysqli_fetch_assoc($currency_result);
    $currency = htmlspecialchars($row['currency']); // Sanitize for safety
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Pending Withdrawals</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Withdrawals</li>
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
                            <th scope="col">Amount</th>
                            <th scope="col">Network</th>
                            <th scope="col">MOMO Name</th>
                            <th scope="col">MOMO Number</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Complete Request</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Join withdrawals and users tables to get network, momo_name, and momo_number
                        $query = "SELECT w.id, w.amount, w.email, w.status, w.created_at, u.network, u.momo_name, u.momo_number 
                                  FROM withdrawals w 
                                  LEFT JOIN users u ON w.email = u.email 
                                  WHERE w.status = '0'";
                        $query_run = mysqli_query($con, $query);
                        if ($query_run && mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $data) {
                        ?>
                        <tr>
                            <td><?= $currency ?> <?= number_format($data['amount'], 2) ?></td>
                            <td><?= htmlspecialchars($data['network'] ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($data['momo_name'] ?: 'N/A') ?></td>
                            <td><?= htmlspecialchars($data['momo_number'] ?: 'N/A') ?></td>
                            <?php if ($data['status'] == 0) { ?>
                                <td><span class="badge bg-warning text-light">Pending</span></td>
                            <?php } else { ?>
                                <td><span class="badge bg-success text-light">Completed</span></td>
                            <?php } ?>
                            <td><?= date('d-M-Y', strtotime($data['created_at'])) ?></td>
                            <td>
                                <form action="codes/withdrawals.php" method="POST">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($data['id']) ?>">
                                    <button class="btn btn-light" type="submit" name="complete">Complete</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7" class="text-center">No pending withdrawals found.</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- End Bordered Table -->
        </div>
    </div>
</main><!-- End #main -->

<?php include('inc/footer.php'); ?>
</html>
