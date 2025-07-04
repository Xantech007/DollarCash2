<?php
session_start();
include('../config/dbcon.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_package'])) {
        $name = $_POST['name'];
        $cashtag = $_POST['cashtag'];
        $max_amount = $_POST['max_amount'];
        $charges = $_POST['charges'];
        $status = isset($_POST['status']) ? '1' : '0';
        $dashboard = isset($_POST['dashboard']) ? 'enabled' : 'disabled';

        // Validate input
        if (empty($name) || empty($cashtag) || empty($max_amount) || empty($charges)) {
            $_SESSION['error'] = "All fields are required.";
            error_log("packages.php - Missing required fields for add_package");
            header("Location: ../admin/add-package.php");
            exit(0);
        }

        // Use prepared statements for security
        $stmt = $con->prepare("INSERT INTO packages (name, cashtag, max_a, amount, status, dashboard, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssddis", $name, $cashtag, $max_amount, $charges, $status, $dashboard);

        if ($stmt->execute()) {
            $_SESSION['success'] = "New package created successfully";
            error_log("packages.php - Package added: $name, Cashtag: $cashtag, Charges: $charges");
            header("Location: ../admin/manage-packages.php");
            exit(0);
        } else {
            $_SESSION['error'] = "Failed to create package";
            error_log("packages.php - Insert query error: " . $stmt->error);
            header("Location: ../admin/add-package.php");
            exit(0);
        }
        $stmt->close();
    } elseif (isset($_POST['edit_package'])) {
        $id = $_POST['edit_package'];
        $name = $_POST['name'];
        $cashtag = $_POST['cashtag'];
        $max_amount = $_POST['max_amount'];
        $charges = $_POST['charges'];
        $status = isset($_POST['status']) ? '1' : '0';
        $dashboard = isset($_POST['dashboard']) ? 'enabled' : 'disabled';

        // Validate input
        if (empty($id) || empty($name) || empty($cashtag) || empty($max_amount) || empty($charges)) {
            $_SESSION['error'] = "All fields are required.";
            error_log("packages.php - Missing required fields for edit_package");
            header("Location: ../admin/edit-package.php?id=" . urlencode($id));
            exit(0);
        }

        // Use prepared statements for security
        $stmt = $con->prepare("UPDATE packages SET name = ?, cashtag = ?, max_a = ?, amount = ?, status = ?, dashboard = ? WHERE id = ?");
        $stmt->bind_param("ssddisi", $name, $cashtag, $max_amount, $charges, $status, $dashboard, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Package updated successfully";
            error_log("packages.php - Package updated: ID $id, Name: $name, Charges: $charges");
            header("Location: ../admin/manage-packages.php");
            exit(0);
        } else {
            $_SESSION['error'] = "Failed to update package";
            error_log("packages.php - Update query error: " . $stmt->error);
            header("Location: ../admin/edit-package.php?id=" . urlencode($id));
            exit(0);
        }
        $stmt->close();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['delete'];

        // Validate input
        if (empty($id)) {
            $_SESSION['error'] = "Invalid package ID.";
            error_log("packages.php - Missing package ID for delete");
            header("Location: ../admin/manage-packages.php");
            exit(0);
        }

        // Use prepared statements for security
        $stmt = $con->prepare("DELETE FROM packages WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Package deleted successfully";
            error_log("packages.php - Package deleted: ID $id");
            header("Location: ../admin/manage-packages.php");
            exit(0);
        } else {
            $_SESSION['error'] = "Failed to delete package";
            error_log("packages.php - Delete query error: " . $stmt->error);
            header("Location: ../admin/manage-packages.php");
            exit(0);
        }
        $stmt->close();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    error_log("packages.php - Invalid request method");
    header("Location: ../admin/manage-packages.php");
    exit(0);
}
?>
