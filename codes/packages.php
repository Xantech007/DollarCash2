<?php
session_start();
include('../config/dbcon.php');

if (isset($_POST['add_package'])) {
    $name = $_POST['name'];
    $cashtag = $_POST['cashtag'];
    $max_amount = $_POST['max_amount'];
    $status = isset($_POST['status']) ? '1' : '0';
    $dashboard = isset($_POST['dashboard']) ? 'enabled' : 'disabled'; // Map checkbox to 'enabled'/'disabled'

    // Use prepared statements for security
    $stmt = $con->prepare("INSERT INTO packages (name, cashtag, max_a, status, dashboard) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $name, $cashtag, $max_amount, $status, $dashboard);

    if ($stmt->execute()) {
        $_SESSION['success'] = "New package created successfully";
        header("Location: ../admin/manage-packages");
        exit(0);
    } else {
        $_SESSION['error'] = "Failed to create package";
        header("Location: ../admin/add-package");
        exit(0);
    }
    $stmt->close();
}

if (isset($_POST['edit_package'])) {
    $id = $_POST['edit_package'];
    $name = $_POST['name'];
    $cashtag = $_POST['cashtag'];
    $max_amount = $_POST['max_amount'];
    $status = isset($_POST['status']) ? '1' : '0';
    $dashboard = isset($_POST['dashboard']) ? 'enabled' : 'disabled'; // Map checkbox to 'enabled'/'disabled'

    // Use prepared statements for security
    $stmt = $con->prepare("UPDATE packages SET name = ?, cashtag = ?, max_a = ?, status = ?, dashboard = ? WHERE id = ?");
    $stmt->bind_param("ssissi", $name, $cashtag, $max_amount, $status, $dashboard, $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Package updated successfully";
        header("Location: ../admin/manage-packages");
        exit(0);
    } else {
        $_SESSION['error'] = "Failed to update package";
        header("Location: ../admin/edit-package?id=" . $id);
        exit(0);
    }
    $stmt->close();
}

if (isset($_POST['delete'])) {
    $id = $_POST['delete'];

    // Use prepared statements for security
    $stmt = $con->prepare("DELETE FROM packages WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Package deleted successfully";
        header("Location: ../admin/manage-packages");
        exit(0);
    } else {
        $_SESSION['error'] = "Failed to delete package";
        header("Location: ../admin/manage-packages");
        exit(0);
    }
    $stmt->close();
}
?>
