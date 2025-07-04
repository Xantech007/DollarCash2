<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
include('inc/sidebar.php');
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Payment Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Payment Details</li>
            </ol>     
        </nav>     
    </div><!-- End Page Title -->  

    <style>
        .add-btn {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: 15px 0;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="card">
                <form action="codes/payment-details.php" method="POST">
                    <?php
                    // Display success or error messages if set
                    if (isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                        unset($_SESSION['success']);
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                        unset($_SESSION['error']);
                    }

                    // Fetch existing payment details
                    $query = "SELECT * FROM payment_details LIMIT 1";
                    $query_run = mysqli_query($con, $query);

                    $network = '';
                    $momo_name = '';
                    $momo_number = '';
                    $currency = '';

                    if ($query_run && mysqli_num_rows($query_run) > 0) {
                        $row = mysqli_fetch_array($query_run);
                        $network = $row['network'];
                        $momo_name = $row['momo_name'];
                        $momo_number = $row['momo_number'];
                        $currency = $row['currency'];
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="network" class="mb-2">Network</label>
                            <input name="network" type="text" class="form-control" id="network" value="<?= htmlspecialchars($network) ?>" required>
                        </div>                                  
                        <div class="col-md-6 form-group mb-3">
                            <label for="momo_name" class="mb-2">MOMO Name</label>
                            <input name="momo_name" type="text" class="form-control" id="momo_name" value="<?= htmlspecialchars($momo_name) ?>" required>
                        </div>                                  
                        <div class="col-md-6 form-group mb-3">
                            <label for="momo_number" class="mb-2">MOMO Number</label>
                            <input name="momo_number" type="text" class="form-control" id="momo_number" value="<?= htmlspecialchars($momo_number) ?>" required>
                        </div>                          
                        <div class="col-md-6 form-group mb-3">
                            <label for="currency" class="mb-2">Currency</label>
                            <input name="currency" type="text" class="form-control" id="currency" value="<?= htmlspecialchars($currency) ?>" required>
                        </div>                          
                        <button type="submit" class="btn btn-secondary" name="update_payment_details">Save Changes</button>  
                    </div>  
                </form>
            </div>    
        </div>       
    </div>       

</main><!-- End #main -->

<?php include('inc/footer.php'); ?>
</html>
