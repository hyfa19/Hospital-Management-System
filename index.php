<?php 
// index.php
include 'header.php';

// Handle form submission
$message = "";
if(isset($_POST['submit'])) {
    $stmt = $conn->prepare("INSERT INTO patient (name, age, gender, phone, doctor_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissi", $_POST['name'], $_POST['age'], $_POST['gender'], $_POST['phone'], $_POST['doctor_id']);
    
    if($stmt->execute()) {
        $message = "<div class='alert alert-success'>Patient successfully registered and assigned!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Fetch doctors AND their specializations to populate the dropdown
$docs = mysqli_query($conn, "SELECT * FROM doctors ORDER BY name ASC");
?>

<div class="row">
    <div class="col-lg-8">
        <h2 class="fw-bold mb-4">Register New Patient</h2>
        <?= $message; ?>
        
        <div class="card p-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Age</label>
                        <input type="number" name="age" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-primary">Assign Doctor & Speciality</label>
                    <select name="doctor_id" class="form-select border-primary" required>
                        <option value="">Select a Doctor...</option>
                        <?php 
                        if ($docs) {
                            while($d = mysqli_fetch_assoc($docs)) {
                                // Now showing Dr. Name - Specialization
                                echo "<option value='".$d['doctor_id']."'>Dr. ".htmlspecialchars($d['name'])." - ".htmlspecialchars($d['specialization'])."</option>";
                            } 
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save me-2"></i> Save Patient Record
                </button>
            </form>
        </div>
    </div>
</div>

</div> </body>
</html>