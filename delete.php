<?php
// include database connection file
include_once("config.php");
require_once("auth_check.php");

// Get id from URL to delete that equipment
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Delete user row from table based on given id
    $stmt = mysqli_prepare($mysqli, "DELETE FROM alat WHERE id=?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        mysqli_query($mysqli, "DELETE FROM alat WHERE id=$id");
    }
}

// After delete redirect to Home, so that latest user list will be displayed.
header("Location: index.php");
exit();
?>
