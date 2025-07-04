<?php
session_start();
include('../config/dbcon.php');
include('inc/header.php');
include('inc/navbar.php');
?>

<main id="main" class="main">

    <div class="pagetitle">
        <?php
        $email = $_SESSION['email'];

        $query = "SELECT balance, verify FROM users WHERE email='$email'";
        $query_run = mysqli_query($con, $query);
        
        if ($query_run) {
            $row = mysqli_fetch_array($query_run);
            $balance = $row['balance'];
            $verify = $row['verify'] ?? 0; // Default to 0 if not set
        }
        ?>
        <h1>Available Balance: $<?= htmlspecialchars($balance) ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Withdrawals</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->   
    <?php  
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
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    <?php } 
    unset($_SESSION['error']);
    if (isset($_SESSION['success'])) { ?>
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
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
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
        /* Modal Form Styles */
        .withdrawal-form .form-group {
            position: relative;
            margin-bottom: 20px;
        }
        .withdrawal-form input {
            width: 100%;
            padding: 8px 0;
            font-size: 14px;
            border: none;
            border-bottom: 2px solid #ccc;
            outline: none;
            background: transparent;
        }
        .withdrawal-form label {
            position: absolute;
            top: 8px;
            left: 0;
            font-size: 14px;
            color: #666;
            transition: 0.3s ease-in-out;
            pointer-events: none;
        }
        .withdrawal-form input:focus + label,
        .withdrawal-form input:not(:placeholder-shown) + label {
            top: -20px;
            font-size: 12px;
            color: #0d6efd;
        }
        .withdrawal-form .error {
            color: #d32f2f;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        @media (max-width: 500px) {
            .withdrawal-form {
                width: 100%;
            }
        }
    </style>

    <div class="card" style="margin-top:20px">
        <div class="card-body">
            <h5 class="card-title">Withdrawal Request</h5>
            <p>Fill in amount to be withdrawn, network, MOMO name, and MOMO number, then submit form to complete your request</p>

            <!-- Withdrawal Modal -->
            <?php if ($verify == 2): ?>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#withdrawalModal">
                    Request Withdrawal
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-secondary" disabled>Request Withdrawal (Verify Account First)</button>
            <?php endif; ?>
            <div class="modal fade" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="withdrawalModalLabel">Minimum withdrawal is set at $50</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="../codes/withdrawals.php" method="POST" class="withdrawal-form" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="number" name="amount" id="amount" required min="50" placeholder=" " />
                                    <label for="amount">Amount In USD</label>
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="network" id="network" required placeholder=" " />
                                    <label for="network">Network</label>
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="momo_name" id="momo_name" required placeholder=" " />
                                    <label for="momo_name">MOMO Name</label>
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="momo_number" id="momo_number" required placeholder=" " />
                                    <label for="momo_number">MOMO Number</label>
                                    <div class="error"></div>
                                    <button type="button" id="copyMomoNumber" class="btn btn-light btn-sm mt-2">Copy</button>
                                </div>
                                <input type="hidden" value="<?= htmlspecialchars($_SESSION['email']) ?>" name="email">
                                <input type="hidden" value="<?= htmlspecialchars($balance) ?>" name="balance">
                                <input type="hidden" value="<?= htmlspecialchars($verify) ?>" name="verify_status">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-secondary" name="withdraw">Submit Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- End Withdrawal Modal-->
        </div>
    </div>

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
                            <th scope="col">MOMO Number</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $email = $_SESSION['email'];
                        $query = "SELECT id, amount, network, momo_number, status, created_at FROM withdrawals WHERE email='$email'";
                        $query_run = mysqli_query($con, $query);
                        if (mysqli_num_rows($query_run) > 0) { 
                            foreach ($query_run as $data) { ?>
                                <tr>                                       
                                    <td>$<?= htmlspecialchars($data['amount']) ?></td>
                                    <td><?= htmlspecialchars($data['network']) ?></td>
                                    <td><?= htmlspecialchars($data['momo_number']) ?></td>
                                    <?php if ($data['status'] == 0) { ?>
                                        <td><span class="badge bg-warning text-light">Pending</span></td> 
                                    <?php } else { ?>
                                        <td><span class="badge bg-success text-light">Completed</span></td>                
                                    <?php } ?>
                                    <td><?= date('d-M-Y', strtotime($data['created_at'])) ?></td>
                                    <td>
                                        <form action="../codes/withdrawals.php" method="POST">
                                            <button class="btn btn-light" value="<?= $data['id'] ?>" name="delete">Delete</button>
                                        </form>                        
                                    </td>
                                </tr>
                            <?php }        
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- End Bordered Table -->
        </div>
    </div>

    <!-- Verify Account Button -->
    <?php if ($verify == 0 || $verify == 1): ?>
        <div class="action-buttons">
            <a href="verify.php" class="btn btn-verify">Verify Account</a>
        </div>
    <?php endif; ?>

</main><!-- End #main -->

<script>
    // Copy MOMO Number
    const copyButton = document.querySelector('#copyMomoNumber');
    const momoNumberInput = document.querySelector('#momo_number');
    if (copyButton && momoNumberInput) {
        copyButton.addEventListener('click', () => {
            momoNumberInput.select();
            document.execCommand('copy');
            copyButton.textContent = 'Copied!';
            setTimeout(() => { copyButton.textContent = 'Copy'; }, 2000);
        });
    }

    // Ensure Bootstrap modal works correctly
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.querySelector('#withdrawalModal');
        if (modal) {
            modal.addEventListener('shown.bs.modal', () => {
                document.querySelector('#amount').focus();
            });
        }
    });
</script>

<?php include('inc/footer.php'); ?>
</html>
