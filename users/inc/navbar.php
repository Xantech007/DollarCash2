<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
    <a href="index.php" class="logo d-flex align-items-center">  
        <img src="../Uploads/logo/logodark.png" alt="">
    </a>
</div><!-- End Logo -->

<div class="search-bar">
</div><!-- End Search Bar -->

<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
        <li class="nav-item dropdown pe-3">
            <?php 
            $email = $_SESSION['email'];
            // Updated query to include bank_name and account_number
            $query = "SELECT name, address, country, balance, referal_bonus, image, bank_name, account_number FROM users WHERE email = ? LIMIT 1";
            $stmt = $con->prepare($query); // Use prepared statement for security
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $data = $result->fetch_assoc();
                $name = $data['name'];
                $address = $data['address'];
                $country = $data['country'];
                $balance = $data['balance'];
                $bonus = $data['referal_bonus'];
                $image = $data['image'];
                $bank_name = $data['bank_name']; // New variable
                $account_number = $data['account_number']; // New variable
            } else {
                // Handle case where user is not found (optional)
                $name = "Guest";
                $image = "default.png";
                $address = "";
                $country = "";
                $balance = 0;
                $bonus = 0;
                $bank_name = "";
                $account_number = "";
            }
            $stmt->close();
            ?>
            
            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <img src="../Uploads/profile-picture/<?= $image ?>" alt="Profile" class="rounded-circle">
                <span class="d-none d-md-block dropdown-toggle ps-2"><?= $name ?></span>
            </a><!-- End Profile Image Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                <li class="dropdown-header">
                    <h6><?= $name ?></h6>             
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>           
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="users-profile">
                        <i class="bi bi-person-lines-fill"></i>
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <style>
                    .header-nav .nav-profile img {
                        width: 40px;
                        height: 40px;
                        object-fit: cover;
                        border-radius: 10px;
                        margin-right: 15px;
                    }
                </style>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#">
                        <i class="bi bi-box-arrow-left"></i>
                        <form action="../codes/logout.php" method="POST">
                            <button type="submit" name="logout" style="background:transparent;border:none;color:black">Logout</button>
                        </form>
                    </a>
                </li>
            </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
    </ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->
<div style="margin-top:60px;">
                    </div>
