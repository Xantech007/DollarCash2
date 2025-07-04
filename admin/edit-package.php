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
                        $id = $_GET['id'];
                        $query = "SELECT * FROM packages WHERE id='$id' LIMIT 1";
                        $query_run = mysqli_query($con, $query);

                        if($query_run)
                        {
                            $row = mysqli_fetch_array($query_run);
                            $name = $row['name'];
                            $cashtag = $row['cashtag'];
                            $max = $row['max_a'];
                            $id = $row['id'];
                            $status = $row['status'];
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="name" class="mb-2">Package name</label>
                            <input name="name" type="text" class="form-control" required value="<?php echo htmlspecialchars($name); ?>">
                        </div>                                  
                        <div class="col-md-6 form-group mb-3">
                            <label for="cashtag" class="mb-2">CashTag</label>
                            <input name="cashtag" type="text" class="form-control" required value="<?php echo htmlspecialchars($cashtag); ?>">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="max_amount" class="mb-2">Amount</label>
                            <input name="max_amount" type="number" class="form-control" required value="<?php echo htmlspecialchars($max); ?>">
                        </div> 
                        <div class="col-md-6 form-group mb-3">
                            <label for="status" class="mb-2">Status</label>
                            <input name="status" <?php echo $status == '1' ? 'checked' : ''; ?> type="checkbox">
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
