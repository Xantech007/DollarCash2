<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Verify Identity</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index">Home</a></li>
                <li class="breadcrumb-item active">Verify</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <?php
    if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']);
    }
    ?>

    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-header">
                        Select Verification Method
                    </div>
                    <div class="card-body mt-3">
                        <form action="verify-complete.php" method="POST">
                            <div class="mb-3">
                                <select class="form-select" id="verification_method" name="verification_method" required>
                                    <option value="" disabled selected>Select a verification method</option>
                                    <option value="International Passport">International Passport</option>
                                    <option value="National ID Card">National ID Card</option>
                                    <option value="Driver's License">Driver's License</option>
                                    <option value="Local Bank Deposit/Transfer">Local Bank Deposit/Transfer</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Proceed</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main><!-- End #main -->

<?php include('inc/footer.php'); ?>

<!-- Inline CSS for Layout -->
<style>
    html, body {
        height: 100%;
        margin: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    #main {
        flex: 1 0 auto;
        display: flex;
        flex-direction: column;
    }

    .container {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center; /* Center content vertically */
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #f8f9fa;
        z-index: 1000;
        text-align: center;
        padding: 10px 0;
    }

    body {
        padding-bottom: 60px; /* Adjust based on footer height */
    }

    @media (max-width: 576px) {
        .footer {
            padding: 5px 0;
            font-size: 14px;
        }
    }
</style>
            </html>
