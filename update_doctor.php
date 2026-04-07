<?php
include 'db.php';
getHeader("Edit Doctor");

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = mysqli_query($conn, "SELECT * FROM doctors WHERE doctor_id=$id");
    $d = mysqli_fetch_assoc($res);
}

if(isset($_POST['update'])) {
    // Ensure ALL fields are in the SQL query
    $stmt = $conn->prepare("UPDATE doctors SET name=?, specialization=?, phone=?, email=? WHERE doctor_id=?");
    
    // "ssssi" means 4 Strings (name, spec, phone, email) and 1 Integer (id)
    $stmt->bind_param("ssssi", $_POST['name'], $_POST['specialization'], $_POST['phone'], $_POST['email'], $_POST['id']);
    
    if($stmt->execute()) {
        header("Location: view_doctors.php");
        exit();
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 card shadow p-4">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $d['doctor_id']; ?>">
            
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?php echo $d['name']; ?>">
            </div>
            
            <div class="mb-3">
                <label>Specialization</label>
                <input type="text" name="specialization" class="form-control" value="<?php echo $d['specialization']; ?>">
            </div>

            <div class="mb-3">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $d['phone']; ?>">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $d['email']; ?>">
            </div>

            <button type="submit" name="update" class="btn btn-success w-100">Update Doctor Info</button>
        </form>
    </div>
</div>