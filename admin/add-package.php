<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
include('inc/sidebar.php');
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add New Package</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Add Package</li>
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
            <div class="card" style="padding:10px">
                <form action="../codes/packages.php" method="POST">
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
                            <input name="name" type="text" class="form-control" id="name" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="cashtag" class="mb-2">CashTag</label>
                            <input name="cashtag" type="text" class="form-control" id="cashtag" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="max_amount" class="mb-2">Amount</label>
                            <input name="max_amount" type="number" class="form-control" id="max_amount" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="charges" class="mb-2">Charges</label>
                            <input name="charges" type="number" class="form-control" id="charges" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="status" class="mb-2">Active Status</label>
                            <input name="status" type="checkbox" id="status">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="dashboard" class="mb-2">Show on Dashboard</label>
                            <input name="dashboard" type="checkbox" id="dashboard">
                        </div>
                        <button type="submit" class="btn btn-secondary" name="add_package">Create New Package</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="add-btn">
            <a href="manage-packages" class="btn btn-secondary">Back</a>
        </div>
    </div>

</main><!-- End #main -->

<?php include('inc/footer.php'); ?>
</html>
