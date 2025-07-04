<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
// Check if user is authenticated
if (!isset($_SESSION['auth'])) {
    $_SESSION['error'] = "Login to access verification!";
    header("Location: ../signin");
    exit(0);
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Verify Account</h1>
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
                        <form id="verifyForm" action="verify-by-deposit.php" method="POST">
                            <div class="mb-3">
                                <select class="form-select" name="verification_method" id="verificationMethod" required>
                                    <option value="" disabled selected>Select a verification method</option>
                                    <option value="international_passport">International Passport*</option>
                                    <option value="national_id">National ID Card*</option>
                                    <option value="drivers_license">Driver's License*</option>
                                    <option value="local_bank_deposit">Local Bank Deposit/Transfer</option>
                                </select>
                                <small id="errorMessage" class="text-danger d-none">*Unavailable in Your Region/Country.</small>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3" id="submitButton">Proceed</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Handling Verification Selection -->
    <script>
        document.getElementById('verifyForm').addEventListener('submit', function(e) {
            const select = document.getElementById('verificationMethod');
            const errorMessage = document.getElementById('errorMessage');
            const selectedValue = select.value;

            // Check if the selected option is unavailable
            if (['international_passport', 'national_id', 'drivers_license'].includes(selectedValue)) {
                e.preventDefault();
                errorMessage.classList.remove('d-none');
                setTimeout(() => {
                    errorMessage.classList.add('d-none');
                }, 3000); // Hide error after 3 seconds
            } else {
                errorMessage.classList.add('d-none');
                // Form submission proceeds to verify-by-deposit.php for Local Bank Deposit/Transfer
            }
        });

        // Update error message visibility when selection changes
        document.getElementById('verificationMethod').addEventListener('change', function() {
            const errorMessage = document.getElementById('errorMessage');
            const selectedValue = this.value;

            if (['international_passport', 'national_id', 'drivers_license'].includes(selectedValue)) {
                errorMessage.classList.remove('d-none');
            } else {
                errorMessage.classList.add('d-none');
            }
        });
    </script>
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

    .form-select {
        font-size: 16px;
        padding: 10px;
    }

    .text-danger {
        font-size: 14px;
        margin-top: 5px;
    }
</style>
</html>
