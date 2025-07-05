<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
include('inc/sidebar.php');
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Packages</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Packages</li>
            </ol>     
        </nav>     
    </div>

    <style>
        .add-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
        }
        .dashboard-status {
            font-size: 1.2em;
            color: #28a745; /* Green for enabled */
        }
        .dashboard-status.disabled {
            color: #dc3545; /* Red for disabled */
        }
        .status-valid {
            font-size: 1.2em;
            color: #28a745; /* Green for valid */
        }
        .status-invalid {
            font-size: 1.2em;
            color: #dc3545; /* Red for invalid */
        }
    </style>

    <div class="add-btn">
        <a href="add-package" class="btn btn-secondary float-center">Add New Package</a>
    </div>

    <div class="container text-center">
        <?php
        // Include database connection
        include('../config/dbcon.php');

        // First, get distinct cashtags
        $cashtag_query = "SELECT DISTINCT cashtag FROM packages ORDER BY cashtag";
        $cashtag_result = mysqli_query($con, $cashtag_query);

        if ($cashtag_result && mysqli_num_rows($cashtag_result) > 0) {
            while ($cashtag_row = mysqli_fetch_assoc($cashtag_result)) {
                $current_cashtag = $cashtag_row['cashtag'];
        ?>
                <h3 class="mt-4"><?php echo htmlspecialchars($current_cashtag); ?></h3>
                <div class="row">
                    <?php
                    // Query packages for current cashtag, including status
                    $query = "SELECT id, name, cashtag, max_a, amount, dashboard, status 
                              FROM packages 
                              WHERE cashtag = '" . mysqli_real_escape_string($con, $current_cashtag) . "' 
                              ORDER BY created_at DESC";
                    $query_run = mysqli_query($con, $query);
                    
                    if ($query_run && mysqli_num_rows($query_run) > 0) {
                        while ($data = mysqli_fetch_assoc($query_run)) {
                    ?>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-header">
                                        <?php echo htmlspecialchars($data['name']); ?>                      
                                    </div>
                                    <div class="card-body mt-2">
                                        <div class="mt-3">
                                            <h6>CashTag: <?php echo htmlspecialchars($data['cashtag']); ?></h6>
                                            <h6>Amount: $<?php echo htmlspecialchars(number_format($data['max_a'], 2)); ?></h6>
                                            <h6>Charges: $<?php echo htmlspecialchars(number_format($data['amount'], 2)); ?></h6>
                                            <h6>Show on Dashboard: 
                                                <span class="dashboard-status <?php echo $data['dashboard'] == 'enabled' ? '' : 'disabled'; ?>">
                                                    <?php echo $data['dashboard'] == 'enabled' ? '✔' : '✗'; ?>
                                                </span>
                                            </h6>
                                            <h6>Status: 
                                                <span class="<?php echo $data['status'] == '1' ? 'status-valid' : 'status-invalid'; ?>">
                                                    <?php echo $data['status'] == '1' ? 'Valid' PRIVILEGED' : 'Invalid'; ?>
                                                </span>
                                            </h6>
                                        </div>
                                        <div class="mt-3">                          
                                            <form action="../codes/packages.php" method="POST">
                                                <a href="edit-package?id=<?php echo $data['id']; ?>" class="btn btn-outline-secondary mt-3">Edit Package</a> 
                                                <button class="btn btn-outline-danger mt-3" name="delete" value="<?php echo $data['id']; ?>">Delete Package</button>
                                            </form>                               
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p>No packages found for this CashTag.</p>';
                    }
                    ?>
                </div>
        <?php
            }
        } else {
            echo '<p>No packages found.</p>';
        }
        ?>
    </div>       
</main>

<?php include('inc/footer.php'); ?>
</html>
