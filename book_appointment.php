<?php 
// 1. DATABASE CONNECTION
include 'header.php'; 

$message = "";

// 2. FETCH DOCTORS FOR THE DROPDOWN
$doc_query = "SELECT * FROM doctors";
$doctors = mysqli_query($conn, $doc_query);

// 3. HANDLE THE FORM SUBMISSION
if (isset($_POST['book_btn'])) {
    $doctor_id = $_POST['doc_id'];
    $patient_id = $_POST['pat_id'];
    $raw_date = $_POST['app_date']; // This gets the date + time from the form

    // FIX: Convert "2026-04-07T14:30" to MySQL "2026-04-07 14:30:00"
    $formatted_date = date('Y-m-d H:i:s', strtotime($raw_date));

    // 4. INSERT INTO DATABASE
    $query = "INSERT INTO appointments (doctor_id, patient_id, appointment_date) 
              VALUES ('$doctor_id', '$patient_id', '$formatted_date')";

    if (mysqli_query($conn, $query)) {
        $message = "<div class='alert alert-success shadow-sm'>Appointment booked for " . date('M d, Y h:i A', strtotime($formatted_date)) . "</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Book New Appointment</h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Doctor</label>
                            <select name="doc_id" class="form-select" required>
                                <option value="">-- Choose Doctor --</option>
                                <?php while($d = mysqli_fetch_assoc($doctors)) { ?>
                                    <option value="<?php echo $d['doctor_id']; ?>">
                                        <?php echo $d['name']; ?> (<?php echo $d['specialization']; ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Patient ID</label>
                            <input type="number" name="pat_id" class="form-control" placeholder="Enter Patient ID" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Appointment Date & Time</label>
                            <input type="datetime-local" name="app_date" class="form-control" required>
                            <small class="text-muted">Click the clock icon to set the time.</small>
                        </div>

                        <button type="submit" name="book_btn" class="btn btn-primary w-100 fw-bold py-2">
                            Confirm Appointment
                        </button>
                    </form>
                </div>
                <div class="card-footer bg-light text-center py-3">
                    <a href="view_appointments.php" class="text-decoration-none small">View All Appointments →</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>