<?php
session_start();
include('../config/dbcon.php');
include('inc/header.php');
include('inc/navbar.php');

// Fetch currency from payment_details
$currency_query = "SELECT currency FROM payment_details LIMIT 1";
$currency_result = mysqli_query($con, $currency_query);
$currency = '$'; // Default fallback
if ($currency_result && mysqli_num_rows($currency_result) > 0) {
    $currency = htmlspecialchars(mysqli_fetch_assoc($currency_result)['currency']);
}
?>

<main id="main" class="main">

    <div class="pagetitle">
        <?php
        $email = $_SESSION['email'];
        $query = "SELECT balance, verify FROM users WHERE email = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_array();
            $balance = $row['balance'];
            $verify = $row['verify'] ?? 0; // Default to 0 if not set
        } else {
            $balance = 0;
            $verify = 0;
        }
        $stmt->close();
        ?>
        <h1>Available Balance: <?= $currency ?><?= number_format($balance, 2) ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Withdrawals</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="modal fade" id="errorModal" tabindex="-1" aria-modal="true" role="dialog">
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
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    unset($_SESSION['error']);
    if (isset($_SESSION['success'])) { ?>
        <div class="modal fade" id="successModal" tabindex="-1" aria-modal="true" role="dialog">
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
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
    <?php }
    unset($_SESSION['success']);
    ?>

    <style>
        .form1 {
            padding: 10px 10px;
            width: 300px;
            background: white;
            display: flex;
            justify-content: space-between;
            opacity: 0.85;
            border-radius: 10px;
        }
        input {
            border: none;
            outline: none;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        @media (max-width: 500px) {
            .form {
                width: 100%;
                margin: auto;
            }
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
        }
        .btn-verify {
            background: #ffc107;
            flex: 1;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
            text-align: center;
            text-decoration: none;
            color: white;
        }
        #form { margin: auto; width: 80%; }
        .form .inputbox { position: relative; width: 100%; margin-top: 20px; }
        .form .inputbox input, .form .inputbox textarea { width: 100%; padding: 5px 0; font-size: 12px; border: none; outline: none; background: transparent; border-bottom: 2px solid #ccc; margin: 10px 0; }
        .form .inputbox span { position: absolute; left: 0; padding: 5px 0; font-size: 12px; margin: 10px 0; }
        .form .inputbox input:focus ~ span, .form .inputbox textarea:focus ~ span { color: #0dcefd; font-size: 12px; transform: translateY(-20px); transition: 0.4s ease-in-out; }
        .form .inputbox input:valid ~ span, .form .inputbox textarea:valid ~ span { color: #0dcefd; font-size: 12px; transform: translateY(-20px); }
        .error { margin-bottom: 10px; padding: 0px; background: #d3ad7f; text-align: center; font-size: 12px; transition: all 0.5s ease; color: white; border-radius: 3px; }
    </style>

    <?php if ($verify == 2) { ?>
        <div class="card" style="margin-top:20px">
            <div class="card-body">
                <h5 class="card-title">Withdrawal Request</h5>
                <p>Fill in amount to be withdrawn, network, MOMO name, and MOMO number, then submit form to complete your request</p>

                <!-- Basic Modal -->
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                    Request Withdrawal
                </button>
                <div class="modal fade" id="verticalycentered" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Minimum withdrawal is set at <?= $currency ?>50</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form" data-aos="fade-up">
                                    <form action="../codes/withdrawals.php" method="POST" class="F" id="form" enctype="multipart/form-data">
                                        <div class="error"></div>
                                        <div class="inputbox">
                                            <input class="input" type="number" name="amount" autocomplete="off" required="required" />
                                            <span>Amount In <?= $currency ?></span>
                                        </div>
                                        <div class="inputbox">
                                            <input class="input" type="text" name="network" autocomplete="off" required="required" />
                                            <span>Network</span>
                                        </div>
                                        <div class="inputbox">
                                            <input class="input" type="text" name="momo_name" autocomplete="off" required="required" />
                                            <span>MOMO Name</span>
                                        </div>
                                        <div class="inputbox">
                                            <input class="input" type="text" name="momo_number" autocomplete="off" required="required" />
                                            <span>MOMO Number</span>
                                        </div>
                                        <input type="hidden" value="<?= htmlspecialchars($_SESSION['email']) ?>" name="email">
                                        <input type="hidden" value="<?= $balance ?>" name="balance">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] = bin2hex(random_bytes(32))) ?>">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-secondary" name="withdraw">Submit Request</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Basic Modal-->
            </div>
        </div>
    <?php } else { ?>
        <div class="card" style="margin-top:20px">
            <div class="card-body">
                <h5 class="card-title">Withdrawal Request</h5>
                <p>Please verify your account to request a withdrawal.</p>
            </div>
        </div>
    <?php } ?>

    <div class="pagetitle">
        <h1>Withdrawal History</h1>
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
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ ستاد = $con->prepare("SELECT id, amount, network, momo_name, momo_number, status, created_at FROM withdrawals WHERE email = ?");
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $query_run = $stmt->get_result();
                        if ($query_run->num_rows > 0) {
                            foreach ($query_run as $data) { ?>
                                <tr>
                                    <td><?= $currency ?><?= number_format($data['amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($data['network']) ?: 'N/A' ?></td>
                                    <td><?= htmlspecialchars($data['momo_name']) ?: 'N/A' ?></td>
                                    <td><?= htmlspecialchars($data['momo_number']) ?: 'N/A' ?></td>
                                    <?php if ($data['status'] == 0) { ?>
                                        <td><span class="badge bg-warning text-light">Pending</span></td>
                                    <?php } else { ?>
                                        <td><span class="badge bg-success text-light">Completed</span></td>
                                    <?php } ?>
                                    <td><?= date('d-M-Y', strtotime($data['created_at'])) ?></td>
                                    <td>
                                        <form action="../codes/withdrawals.php" method="POST">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                            <button class="btn btn-light" value="<?= htmlspecialchars($data['id']) ?>" name="delete">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">No withdrawals found.</td>
                            </tr>
                        <?php }
                        $stmt->close();
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- End Bordered Table -->
        </div>
    </div>

    <!-- Verify Account Button -->
    <?php if ($verify == 0 || $verify == 1) { ?>
        <div class="action-buttons">
            <a href="verify.php" class="btn btn-verify">Verify Account</a>
        </div>
    <?php } ?>

</main><!-- End #main -->

<script>
    // Initialize modals dynamically
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (isset($_SESSION['success'])) { ?>
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        <?php } elseif (isset($_SESSION['error'])) { ?>
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        <?php } ?>
    });

    // Client-side validation for withdrawal form
    document.getElementById('form')?.addEventListener('submit', function (e) {
        const amount = parseFloat(document.querySelector('input[name="amount"]').value);
        const balance = parseFloat(document.querySelector('input[name="balance"]').value);
        const errorDiv = document.querySelector('.error');
        if (amount < 50) {
            e.preventDefault();
            errorDiv.textContent = 'Minimum withdrawal is <?= $currency ?>50.';
        } else if (amount > balance) {
            e.preventDefault();
            errorDiv.textContent = 'Insufficient balance.';
        }
    });
</script>

<?php include('inc/footer.php'); ?>

</html>
