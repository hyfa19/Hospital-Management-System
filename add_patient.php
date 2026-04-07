<?php 
// 1. DATABASE CONNECTION
include 'header.php'; // Includes your db connectivity code [cite: 215]

$message = "";

// FETCH DOCTORS FOR THE ASSIGNMENT DROPDOWN
$doc_query = "SELECT * FROM doctors";
$doctors = mysqli_query($conn, $doc_query);

// 2. PROCESS FORM SUBMISSION
if (isset($_POST['save_patient'])) {
    // Sanitize inputs to prevent SQL Injection [cite: 186]
    $name = mysqli_real_escape_string($conn, $_POST['patient_name']);
    $dob  = mysqli_real_escape_string($conn, $_POST['patient_dob']); // Captured as YYYY-MM-DD
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $doc_id = mysqli_real_escape_string($conn, $_POST['assign_doctor']);

    // SQL INSERT QUERY using the new 'dob' column 
    $query = "INSERT INTO patient (name, dob, gender, phone, doctor_id) 
              VALUES ('$name', '$dob', '$gender', '$phone', '$doc_id')";

    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert alert-success shadow-sm'><strong>Success!</strong> Patient $name registered with DOB: $dob</div>";
    } else {
        $message = "<div class='alert alert-danger'><strong>Error:</strong> " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4 text-dark">Register New Patient</h2>
                    
                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="patient_name" class="form-control form-control-lg" placeholder="e.g. John Doe" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-primary">Date of Birth</label>
                                <input type="date" name="patient_dob" class="form-control form-control-lg" required>
                                <small class="text-muted">Select birth date from calendar</small>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Gender</label>
                                <select name="gender" class="form-select form-select-lg" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control form-control-lg" placeholder="Contact number" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold text-primary">Assign Doctor & Speciality</label>
                            <select name="assign_doctor" class="form-select form-select-lg" required>
                                <option value="">Select a Doctor...</option>
                                <?php while($d = mysqli_fetch_assoc($doctors)) { ?>
                                    <option value="<?php echo $d['doctor_id']; ?>">
                                        <?php echo $d['name']; ?> - <?php echo $d['specialization']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <button type="submit" name="save_patient" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Save Patient Record
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>