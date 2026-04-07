<?php
include 'db_config.php';
if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM doctors WHERE doctor_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: view_doctors.php");
exit();
?>