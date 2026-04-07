<?php
include 'db.php';
getHeader("Edit Patient");

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $p_data = mysqli_query($conn, "SELECT * FROM patient WHERE patient_id=$id");
    $p = mysqli_fetch_assoc($p_data);
}

if(isset($_POST['update'])) {
    // The names in $_POST['name'] must match the 'name' attribute in the HTML below
    $stmt = $conn->prepare("UPDATE patient SET name=?, age=?, gender=?, phone=?, doctor_id=? WHERE patient_id=?");
    $stmt->bind_param("sissii", $_POST['name'], $_POST['age'], $_POST['gender'], $_POST['phone'], $_POST['doctor_id'], $_POST['id']);
    
    if($stmt->execute()) { 
        header("Location: view_patients.php"); 
        exit();
    }
}
?>

<form method="POST">
    <input type="hidden" name="id" value="<?php echo $p['patient_id']; ?>">
    
    <label>Full Name</label>
    <input type="text" name="name" class="form-control" value="<?php echo $p['name']; ?>">

    <label>Age</label>
    <input type="number" name="age" class="form-control" value="<?php echo $p['age']; ?>">

    <label>Phone Number</label>
    <input type="text" name="phone" class="form-control" value="<?php echo $p['phone']; ?>">

    <button type="submit" name="update" class="btn btn-primary mt-3">Save Changes</button>
</form>