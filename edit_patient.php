<?php 
// edit_patient.php
include 'header.php';

// Enable PHP Assertions
ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

// Make sure we actually have a patient ID to edit
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>window.location.href='view_patients.php';</script>";
    exit();
}

$patient_id = intval($_GET['id']);

// THE FORMAL ASSERTION
assert($patient_id > 0, "Security Assertion Failed: Patient ID must be a positive number.");

$message = "";

// Handle the Form Submission (The UPDATE Query)
if(isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE patient SET name = ?, age = ?, gender = ?, phone = ?, doctor_id = ? WHERE patient_id = ?");
    $stmt->bind_param("sissii", $_POST['name'], $_POST['age'], $_POST['gender'], $_POST['phone'], $_POST['doctor_id'], $patient_id);
    
    // NEW: The try...catch safety net
    try {
        $stmt->execute();
        $message = "<div class='alert alert-success'><i class='fas fa-check-circle me-2'></i>Patient record updated successfully! <a href='view_patients.php' class='alert-link'>Return to Patient List</a></div>";
    } catch (mysqli_sql_exception $e) {
        // This catches the constraint failure and displays it neatly
        $message = "<div class='alert alert-danger'><i class='fas fa-exclamation-triangle me-2'></i>Error: Invalid Age. " . $e->getMessage() . "</div>";
    }
}

// Fetch the current patient's data to pre-fill the form
$stmt = $conn->prepare("SELECT * FROM patient WHERE patient_id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0) {
    echo "<div class='alert alert-danger m-4'>Patient not found!</div></div></body></html>";
    exit();
}

$patient = $result->fetch_assoc();

// Fetch doctors to populate the dropdown
$docs = mysqli_query($conn, "SELECT * FROM doctors");
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Edit Patient Record</h2>
            <a href="view_patients.php" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
        
        <?= $message; ?>
        
        <div class="card p-4 shadow-sm border-0">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($patient['name']); ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Age</label>
                        <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($patient['age']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="Male" <?= ($patient['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?= ($patient['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?= ($patient['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($patient['phone']); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-primary">Assign Doctor</label>
                    <select name="doctor_id" class="form-select border-primary" required>
                        <option value="">Select a Doctor...</option>
                        <?php 
                        if ($docs) {
                            while($d = mysqli_fetch_assoc($docs)) {
                                $selected = ($d['doctor_id'] == $patient['doctor_id']) ? 'selected' : '';
                                echo "<option value='".$d['doctor_id']."' ".$selected.">Dr. ".$d['name']."</option>";
                            } 
                        }
                        ?>
                    </select>
                </div>

                <button type="submit" name="update" class="btn btn-success w-100">
                    <i class="fas fa-save me-2"></i> Update Patient Record
                </button>
            </form>
        </div>
    </div>
</div>

</div> </body>
</html>