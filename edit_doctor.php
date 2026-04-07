<?php 
include 'header.php'; 

$message = "";

// 1. GET THE DOCTOR'S ID FROM THE URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Fetch current data for this doctor
    $res = mysqli_query($conn, "SELECT * FROM doctors WHERE doctor_id = '$id'");
    $row = mysqli_fetch_assoc($res);
}

// 2. UPDATE THE DOCTOR WHEN BUTTON IS CLICKED
if (isset($_POST['update_btn'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $spec = mysqli_real_escape_string($conn, $_POST['spec']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $update_query = "UPDATE doctors SET name='$name', specialization='$spec', phone='$phone' WHERE doctor_id='$id'";

    if (mysqli_query($conn, $update_query)) {
        $message = "<div class='alert alert-success'>Information updated successfully!</div>";
        // Refresh the data on the screen
        $res = mysqli_query($conn, "SELECT * FROM doctors WHERE doctor_id = '$id'");
        $row = mysqli_fetch_assoc($res);
    } else {
        $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Edit Doctor Details: <?php echo $row['name']; ?></h5>
                </div>
                <div class="card-body p-4">
                    <?php echo $message; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Specialization</label>
                            <input type="text" name="spec" class="form-control" value="<?php echo $row['specialization']; ?>" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" placeholder="Enter number here">
                        </div>
                        <button type="submit" name="update_btn" class="btn btn-success w-100">Update Doctor</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="view_doctors.php" class="text-decoration-none">← Back to Doctor List</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>