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
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 10px;
            color: #1a1a1a;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 14px;
            color: #757575;
            margin-bottom: 5px;
        }
        .card-amount {
            font-size: 24px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 0;
        }
        .card-detail {
            font-size: 12px;
            color: #757575;
            margin-top: 5px;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
        }
        .btn {
            flex: 1;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 0 5px;
        }
        .btn-add { background: #007bff; color: white; }
        .btn-withdraw { background: #6c757d; color: white; }
        .verified { color: #28a745; font-size: 12px; }
        .progress {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .progress-circle {
            width: 12px;
            height: 12px;
            background: #ffd700;
            border-radius: 50%;
        }
        .bitcoin-graph {
            width: 50px;
            height: 20px;
            background: linear-gradient(to right, #28a745, #ffd700);
            border-radius: 5px;
            margin-left: 5px;
        }
        .copiable {
            cursor: pointer;
            color: #007bff;
        }
        .copiable:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cash Balance Card -->
        <div class="card">
            <div class="card-title">Cash balance</div>
            <div class="card-amount">$<?php echo htmlspecialchars($balance ?? '1,226.00'); ?></div>
            <div class="card-detail">Account +$<?php echo htmlspecialchars($account_change ?? '430'); ?> Routing +<?php echo htmlspecialchars($routing ?? '329'); ?></div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-add">Add money</button>
            <button class="btn btn-withdraw">Withdraw</button>
        </div>

        <!-- CashTag Card -->
        <div class="card">
            <div class="card-title">CashTag:</div>
            <div class="card-amount"><span class="copiable" onclick="copyToClipboard('<?php echo htmlspecialchars($cashtag ?? 'copiable cashtag'); ?>')"><?php echo htmlspecialchars($cashtag ?? 'copiable cashtag'); ?></span></div>
        </div>

        <!-- Save & Invest Section -->
        <div class="card">
            <div class="card-title">Save & Invest</div>
            <div class="card-amount">Hello <?php echo htmlspecialchars($name ?? 'User'); ?></div>
            <div class="card-detail">Scan CashTags to Redeem Funds into Your Account</div>
        </div>

        <!-- Explore Button -->
        <div class="card">
            <div class="card-title">Explore</div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Copied to clipboard: ' + text);
            });
        }

        // Simple button interactivity
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', () => alert(`Action: ${button.textContent}`));
        });
    </script>
</body>
</html>

<?php include('inc/footer.php'); ?>
