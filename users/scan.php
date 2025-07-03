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
            <p>Paste a CashTag from your clipboard below to proceed with the transaction, then confirm.</p>
        </div>

        <div class="row mb-3">
            <div>
                <h3 style="text-align:center">Pasted CashTag</h3>
                <div class="form1">
                    <?php
                    // Input bar is blank by default
                    $scanned_cashtag = '';
                    ?>
                    <input type="text" value="" id="text">
                    <button type="button" id="button"><i class="bi bi-clipboard"></i></button>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top:20px">
            <div class="card-body">
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

        inputbutton.addEventListener('click', pasteFromClipboard);

        function pasteFromClipboard() {
            navigator.clipboard.readText().then(text => {
                if (text.trim()) {
                    input.value = text.trim();
                    inputbutton.innerHTML = 'pasted!';
                    setTimeout(() => {
                        inputbutton.innerHTML = '<i class="bi bi-clipboard"></i>';
                    }, 2000); // Revert after 2 seconds
                } else {
                    console.warn('Clipboard is empty');
                    inputbutton.innerHTML = 'no data!';
                    setTimeout(() => {
                        inputbutton.innerHTML = '<i class="bi bi-clipboard"></i>';
                    }, 2000);
                }
            }).catch(err => {
                console.error('Paste failed:', err);
                inputbutton.innerHTML = 'error!';
                setTimeout(() => {
                    inputbutton.innerHTML = '<i class="bi bi-clipboard"></i>';
                }, 2000);
            });
        }
    </script>

    <?php include('inc/footer.php'); ?>
</html>
