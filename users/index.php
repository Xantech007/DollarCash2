<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');

// Check if user is authenticated
if (!isset($_SESSION['auth'])) {
    $_SESSION['error'] = "Login to access dashboard!";
    header("Location: ../signin");
    exit(0);
}

// Fetch the logged-in user's name and balance from the users table
// Assuming $_SESSION['auth'] contains user_id or email; adjust based on your login system
$name = 'Guest'; // Default name
$balance = 0.00; // Default balance
if (isset($_SESSION['auth'])) {
    // If $_SESSION['auth'] contains user_id or email, adjust query accordingly
    // Example: Assuming $_SESSION['auth'] is user_id or $_SESSION['email'] is set
    $email = $_SESSION['email'] ?? null; // Use email from session (seen in profile.php)
    if ($email) {
        $user_query = "SELECT name, balance FROM users WHERE email = ?";
        $stmt = $con->prepare($user_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user_result = $stmt->get_result();
        if ($user_result && $user_result->num_rows > 0) {
            $user_data = $user_result->fetch_assoc();
            $name = $user_data['name'];
            $balance = $user_data['balance'] ?? 0.00;
        }
        $stmt->close();
    }
}

// Fetch CashTags where dashboard is 'enabled'
$cashtag_query = "SELECT cashtag FROM packages WHERE dashboard = 'enabled' ORDER BY cashtag";
$cashtag_result = mysqli_query($con, $cashtag_query);
$cashtags = [];
if ($cashtag_result && mysqli_num_rows($cashtag_result) > 0) {
    while ($row = mysqli_fetch_assoc($cashtag_result)) {
        $cashtags[] = $row['cashtag'];
    }
}

// Format balance with commas if >= $1000
$formatted_balance = number_format($balance, 2, '.', $balance >= 1000 ? ',' : '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            padding: 10px;
            color: #1a1a1a;
        }

        .container {
            flex: 1;
            max-width: 400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
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

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f8f9fa;
            z-index: 1000;
            text-align: center;
            padding: 10px 0;
            font-size: 12px;
            color: #757575;
        }

        body {
            padding-bottom: 60px;
        }

        @media (max-width: 576px) {
            .footer {
                padding: 5px 0;
                font-size: 10px;
            }
            .container {
                padding: 0 10px;
            }
        }

        .cashtag-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Cash Balance Card -->
        <div class="card">
            <div class="card-title">Cash balance</div>
            <div class="card-amount">$<?php echo htmlspecialchars($formatted_balance); ?></div>
            <div class="card-title">Hello <?php echo htmlspecialchars($name); ?>, Scan CashTags to Add Funds into Your Account</div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="scan.php" class="btn btn-add">Scan</a>
            <a href="withdrawals.php" class="btn btn-withdraw">Withdraw</a>
        </div>

        <!-- Available CashTag(s) Card -->
        <div class="card">
            <div class="card-title">Available CashTag(s):</div>
            <?php if (!empty($cashtags)): ?>
                <?php foreach ($cashtags as $index => $cashtag): ?>
                    <div class="cashtag-item">
                        <div class="card-amount"><?php echo htmlspecialchars($cashtag); ?></div>
                        <button class="copy-btn" data-cashtag="<?php echo htmlspecialchars($cashtag); ?>" id="copyButton<?php echo $index; ?>"><i class="bi bi-front"></i></button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card-amount">No CashTags available</div>
            <?php endif; ?>
        </div>

        <!-- Explore Button -->
        <div class="card">
            <div class="card-title">Explore</div>
        </div>
    </div>

    <script>
        // Add event listeners for each copy button
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', function() {
                const cashtag = this.getAttribute('data-cashtag');
                const tempInput = document.createElement('input');
                tempInput.value = cashtag;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);

                try {
                    document.execCommand('copy');
                    this.innerHTML = 'copied!';
                    setTimeout(() => {
                        this.innerHTML = '<i class="bi bi-front"></i>';
                    }, 2000);
                } catch (e) {
                    console.error('Copy failed:', e);
                    alert('Copy to clipboard failed. Please try manually.');
                }

                document.body.removeChild(tempInput);
            });
        });
    </script>
</body>
</html>

<?php include('inc/footer.php'); ?>
