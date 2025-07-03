<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
include('inc/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .qr {
            width: 320px;
            margin-bottom: 20px;
        }
        .qr-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .form1 {
            padding: 10px;
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
        .copy-btn {
            border: none;
            outline: none;
            color: #012970;
            background: #f7f7f7;
            border-radius: 5px;
            padding: 2px 5px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 10px;
        }
        .copy-btn:hover {
            background: #e0e0e0;
        }
        .copy-btn i {
            font-size: 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Scan CashTag</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index">Home</a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Scan CashTag</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <?php
        if (isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['error']); }
        ?>

        <div style="width:95%;margin:auto">
            <p>Scan or paste a CashTag below to proceed with the transaction. Click the copy button to verify the CashTag, then confirm.</p>
        </div>

        <div class="row mb-3">
            <div class="qr-container">
                <img src="assets/img/qr.png" class="qr" alt="Scan QR Code">
                <div>
                    <h3 style="text-align:center">Scanned CashTag</h3>
                    <div class="form1">
                        <?php
                        // Placeholder for scanned CashTag; in a real scenario, this could come from a scan API or clipboard
                        $scanned_cashtag = isset($_SESSION['scanned_cashtag']) ? $_SESSION['scanned_cashtag'] : '@SampleCashTag';
                        ?>
                        <input type="text" value="<?= htmlspecialchars($scanned_cashtag) ?>" id="text">
                        <button type="button" id="button"><i class="bi bi-front"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top:20px">
            <div class="card-body">
                <h5 class="card-title">Confirm CashTag</h5>
                <p>Verify the scanned CashTag and proceed to confirmation.</p>

                <!-- Proceed Button -->
                <form action="confirm-cashtag.php" method="POST">
                    <input type="hidden" name="cashtag" value="<?= htmlspecialchars($scanned_cashtag) ?>">
                    <button type="submit" class="btn btn-secondary" style="">
                        Proceed to Confirmation
                    </button>
                </form>
            </div>
        </div>
    </main><!-- End #main -->

    <script>
        let input = document.querySelector("#text");
        let inputbutton = document.querySelector("#button");

        inputbutton.addEventListener('click', copytext);

        function copytext() {
            input.select();
            document.execCommand('copy');
            inputbutton.innerHTML = 'copied!';
            setTimeout(() => {
                inputbutton.innerHTML = '<i class="bi bi-front"></i>';
            }, 2000); // Revert after 2 seconds
        }
    </script>

    <?php include('inc/footer.php'); ?>
</html>
