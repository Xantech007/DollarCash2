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
            padding: 2px 5px; /* Consistent with previous small size */
            font-size: 10px; /* Smaller text */
        }
        #button:hover {
            background: #e0e0e0;
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
            <p>Paste a CashTag below to proceed with the transaction, then confirm.</p>
        </div>

        <div class="row mb-3">
            <div class="qr-container">
                <div>
                    <h3 style="text-align:center">Pasted CashTag</h3>
                    <div class="form1">
                        <?php
                        // Placeholder for pasted CashTag; will be updated by JavaScript
                        $pasted_cashtag = isset($_SESSION['pasted_cashtag']) ? $_SESSION['pasted_cashtag'] : '';
                        ?>
                        <input type="text" value="<?= htmlspecialchars($pasted_cashtag) ?>" id="text">
                        <button type="button" id="button">Paste</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top:20px">
            <div class="card-body">
                <h5 class="card-title">Confirm CashTag</h5>
                <p>Verify the pasted CashTag and proceed to confirmation.</p>

                <!-- Proceed Button -->
                <form action="confirm-cashtag.php" method="POST">
                    <input type="hidden" name="cashtag" value="<?= htmlspecialchars($pasted_cashtag) ?>">
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

        inputbutton.addEventListener('click', pasteText);

        function pasteText() {
            navigator.clipboard.readText().then(text => {
                if (text) {
                    input.value = text;
                    // Store in session (requires server-side update)
                    // This is a placeholder; use AJAX or form submission to update $_SESSION['pasted_cashtag']
                    <?php $_SESSION['pasted_cashtag'] = "/* JavaScript text value */"; // Placeholder ?>
                    inputbutton.textContent = 'Pasted!';
                    setTimeout(() => {
                        inputbutton.textContent = 'Paste';
                    }, 2000); // Revert after 2 seconds
                } else {
                    alert('Clipboard is empty. Please copy a CashTag first.');
                }
            }).catch(err => {
                console.error('Paste failed:', err);
                alert('Paste from clipboard failed. Please try manually.');
            });
        }
    </script>

    <?php include('inc/footer.php'); ?>
    </html>
