<?php
session_start();
include('inc/header.php');
include('inc/navbar.php');
include('inc/sidebar.php');
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>All Members</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Members</li>
            </ol>     
        </nav>     
    </div><!-- End Page Title -->  

    <div class="card">
        <div class="card-body">                          
            <!-- Bordered Table -->
            <div class="table-responsive">
                <table class="table table-borderless">
                    <thead>
                        <tr>                   
                            <th scope="col">ID</th>                
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Refered By</th>
                            <th scope="col">Profile Picture</th>                   
                            <th scope="col">Status</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT id, name, email, refered_by, image, verify FROM users";
                        $query_run = mysqli_query($con, $query);
                        if (mysqli_num_rows($query_run) > 0) {
                            foreach ($query_run as $data) {
                                // Map verify values to status labels
                                $status = match ($data['verify']) {
                                    0 => 'Not Verified',
                                    1 => 'Under Review',
                                    2 => 'Verified',
                                    default => 'Unknown'
                                };
                        ?>
                                <tr>                                       
                                    <td><?= htmlspecialchars($data['id']) ?></td>                   
                                    <td><?= htmlspecialchars($data['name']) ?></td>                   
                                    <td><?= htmlspecialchars($data['email']) ?></td>                   
                                    <td><?= htmlspecialchars($data['refered_by']) ?></td>                   
                                    <td>
                                        <img src="../Uploads/profile-picture/<?= htmlspecialchars($data['image']) ?>" style="width:50px;height:50px" alt="Profile" class="">
                                    </td>                   
                                    <td><?= htmlspecialchars($status) ?></td>
                                    <td>
                                        <a href="edit-user.php?id=<?= htmlspecialchars($data['id']) ?>" class="btn btn-light">Edit</a> 
                                    </td>                   
                                    <td>
                                        <form action="codes/users.php" method="POST">  
                                            <input type="hidden" value="<?= htmlspecialchars($data['image']) ?>" name="profile_pic">                         
                                            <button class="btn btn-outline-danger" name="delete_user" value="<?= htmlspecialchars($data['id']) ?>">Delete</button>
                                        </form> 
                                    </td>                   
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="8">No users found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- End Bordered Table -->
        </div>
    </div>
</main><!-- End #main -->

<?php include('inc/footer.php'); ?>
</html>
