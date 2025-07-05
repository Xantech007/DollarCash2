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
        <h1>Pending Deposits</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Deposit</li>
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
                            <th scope="col">Email</th>
                            <th scope="col">Screenshot</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM deposits WHERE status = '0'";
                        $query_run = mysqli_query($con, $query);
                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $data) { ?>
                                <tr>
                                    <td><?= $currency ?> <?= number_format($data['amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($data['email']) ?></td>
                                    <td>
                                        <img src="../Uploads/receipts/<?= htmlspecialchars($data['image']) ?>" style="width:50px;height:50px" alt="Profile" class="">
                                    </td>
                                    <?php
                                    if ($data['status'] == 0) { ?>
                                        <td><span class="badge bg-warning text-light">Pending</span></td>
                                    <?php } elseif ($data['status'] == 1) { ?>
                                        <td><span class="badge bg-danger text-light">Rejected</span></td>
                                    <?php } else { ?>
                                        <td><span class="badge bg-success text-light">Completed</span></td>
                                    <?php } ?>
                                    <td><?= date('d-M-Y', strtotime($data['created_at'])) ?></td>
                                    <td>
                                        <a href="edit-deposit?id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-light">Edit</a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="6">No pending deposits found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- End Bordered Table -->
        </div>
    </div>
</main><!-- End #main -->

<?php include('inc/footer.php'); ?>
</html>
