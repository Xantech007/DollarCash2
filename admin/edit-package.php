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
            justify-content: flex-end;
            align-items: center;
            margin: 15px 0
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="card" style="padding:10px">
                <form action="../codes/packages.php" method="POST">
                    <?php
                    if(isset($_GET['id']))
                    {
                        $id = mysqli_real_escape_string($con, $_GET['id']);
                        $query = "SELECT * FROM packages WHERE id='$id' LIMIT 1";
                        $query_run = mysqli_query($con, $query);

                        if($query_run)
                        {
                            $row = mysqli_fetch_array($query_run);
                            $name = $row['name'];
                            $cashtag = $row['cashtag'];
                            $max_amount = $row['max_a']; // Fixed typo: assuming column is 'max_a'
                            $amount = $row['amount']; // Fetch the amount (charges) value
                            $id = $row['id'];
                            $status = $row['status'];
                            $dashboard = $row['dashboard'];
                        }
                    }
                    ?>
                    <?php
                    // Display success or error messages if set
                    if(isset($_SESSION['success'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                        unset($_SESSION['success']);
                    }
                    if(isset($_SESSION['error'])) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error']) . '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="name" class="mb-2">Package Name</label>
                            <input name="name" type="text" class="form-control" id="name" required value="<?php echo htmlspecialchars($name); ?>">
                        </div>                                  
                        <div class="col-md-6 form-group mb-3">
                            <label for="cashtag" class="mb-2">CashTag</label>
                            <input name="cashtag" type="text" class="form-control" id="cashtag" required value="<?php echo htmlspecialchars($cashtag); ?>">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="max_amount" class="mb-2">Max Amount</label>
                            <input name="max_amount" type="number" class="form-control" id="max_amount" required value="<?php echo htmlspecialchars($max_amount); ?>">
                        </div> 
                        <div class="col-md-6 form-group mb-3">
                            <label for="charges" class="mb-2">Charges</label>
                            <input name="charges" type="number" class="form-control" id="charges" required value="<?php echo htmlspecialchars($amount); ?>">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="status" class="mb-2">Status</label>
                            <input name="status" <?php echo $status == '1' ? 'checked' : ''; ?> type="checkbox" id="status">
                        </div> 
                        <div class="col-md-6 form-group mb-3">
                            <label for="dashboard" class="mb-2">Show on Dashboard</label>
                            <input name="dashboard" <?php echo $dashboard == 'enabled' ? 'checked' : ''; ?> type="checkbox" id="dashboard">
                        </div> 
                        <button type="submit" class="btn btn-secondary" name="edit_package" value="<?php echo $id; ?>">Update Package</button>  
                    </div>  
                </form>
            </div>    
        </div>       
        <div class="add-btn">
            <a href="manage-packages" class="btn btn-secondary">Back</a>
        </div>
    </div>       
</main>

<?php include('inc/footer.php'); ?>
</html>
