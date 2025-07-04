<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
include('inc/sidebar.php');
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
                            <th scope="col">Name</th> <!-- Changed from Email to Name -->
                            <th scope="col">Bank Name</th> <!-- New column -->
                            <th scope="col">Account Number</th> <!-- New column -->
                            <th scope="col">Status</th>                   
                            <th scope="col">Date</th>             
                            <th scope="col">Complete Request</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Join withdrawals and users tables to get name, bank_name, and account_number
                        $query = "SELECT w.id, w.amount, w.email, w.status, w.created_at, u.name, u.bank_name, u.account_number 
                                  FROM withdrawals w 
                                  LEFT JOIN users u ON w.email = u.email 
                                  WHERE w.status = '0'";
                        $query_run = mysqli_query($con, $query);
                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $data) {
                        ?>
                        <tr>                         
                            <td>$<?= htmlspecialchars($data['amount']) ?></td>                   
                            <td><?= htmlspecialchars($data['name']) ?: 'N/A' ?></td> <!-- Display Name -->
                            <td><?= htmlspecialchars($data['bank_name']) ?: 'N/A' ?></td> <!-- Display Bank Name -->
                            <td><?= htmlspecialchars($data['account_number']) ?: 'N/A' ?></td> <!-- Display Account Number -->
                            <?php if ($data['status'] == 0) { ?>
                                <td><span class="badge bg-warning text-light">Pending</span></td> 
                            <?php } else { ?>
                                <td><span class="badge bg-success text-light">Completed</span></td>                
                            <?php } ?>
                            <td><?= date('d-M-Y', strtotime($data['created_at'])) ?></td>           
                            <td>
                                <form action="codes/withdrawals.php" method="POST">                         
                                    <button class="btn btn-light" value="<?= $data['id'] ?>" name="complete">Complete</button>
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
