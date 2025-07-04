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
        input {
            border: none;
            outline: none;
        }
        #button {
            border: none;
            outline: none;
            color: #012970;
            background: #f7f7f7;
            border-radius: 5px;
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
        /* Styles for Verify Account button */
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
    </style>

    <div class="card" style="margin-top:20px">
        <div class="card-body">
            <h5 class="card-title">Withdrawal Request</h5>
            <p>Fill in amount to be withdrawn, network, MOMO name, and MOMO number, then submit form to complete your request</p>

            <!-- Basic Modal -->
            <?php if ($verify == 2): ?>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                    Request Withdrawal
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-secondary" disabled>Request Withdrawal (Verify Account First)</button>
            <?php endif; ?>
            <div class="modal fade" id="verticalycentered" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Minimum withdrawal is set at $50</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form" data-aos="fade-up">
                                <form action="../codes/withdrawals.php" method="POST" class="F" id="form" enctype="multipart/form-data"> 
                                    <div class="error"></div>						               
                                    <div class="inputbox">
                                        <input class="input" type="number" name="amount" autocomplete="off" required="required" min="50" />
                                        <span>Amount In USD</span>
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
                                    <input type="hidden" value="<?= htmlspecialchars($balance) ?>" name="balance">                                            
                                    <input type="hidden" value="<?= htmlspecialchars($verify) ?>" name="verify_status">                                            
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-secondary" name="withdraw">Submit Request</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- End Basic Modal-->
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
    let input = document.querySelector("#text");
    let inputbutton = document.querySelector("#button");

    if (inputbutton) {
        inputbutton.addEventListener('click', copytext);
    }

    function copytext() {
        if (input) {
            input.select();
            document.execCommand('copy');
            inputbutton.innerHTML = 'copied!';
        }
    }
</script> 

<?php include('inc/footer.php'); ?>
</html>
