<?php 
// view_appointments.php
include 'header.php'; 

// 1. Handle Appointment Cancellation (Delete)
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    // Changed 'id' to 'appointment_id'
    $stmt = $conn->prepare("DELETE FROM appointments WHERE appointment_id = ?");
    $stmt->bind_param("i", $cancel_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-warning mx-4 mt-3'><i class='fas fa-info-circle me-2'></i>Appointment cancelled successfully.</div>";
    }
}

// 2. Fetch Appointments using a Double JOIN
// Changed 'a.id' to 'a.appointment_id'
$query = "
    SELECT 
        a.appointment_id AS appt_id, 
        a.appointment_date, 
        a.status, 
        p.name AS patient_name, 
        d.name AS doctor_name 
    FROM appointments a
    JOIN patient p ON a.patient_id = p.patient_id
    JOIN doctors d ON a.doctor_id = d.doctor_id
    ORDER BY a.appointment_date ASC
";
$result = mysqli_query($conn, $query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0">Appointments List</h2>
        <p class="text-muted">Manage all scheduled patient consultations.</p>
    </div>
    <a href="book_appointment.php" class="btn btn-primary shadow-sm">
        <i class="fas fa-calendar-plus me-2"></i> Book New Slot
    </a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-secondary">#ID</th>
                    <th class="text-secondary">Patient Name</th>
                    <th class="text-secondary">Specialist</th>
                    <th class="text-secondary">Date & Time</th>
                    <th class="text-secondary text-center">Status</th>
                    <th class="text-secondary text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result && mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { 
                        // Format the date to look professional
                        $formatted_date = date('d M Y, h:i A', strtotime($row['appointment_date']));
                ?>
                <tr>
                    <td class="fw-bold text-muted">#<?= $row['appt_id']; ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($row['patient_name']); ?></td>
                    <td><i class="fas fa-user-md text-primary me-2"></i> Dr. <?= htmlspecialchars($row['doctor_name']); ?></td>
                    <td class="fw-semibold text-dark"><?= $formatted_date; ?></td>
                    <td class="text-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-2 rounded-pill">
                            <?= htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="view_appointments.php?cancel_id=<?= $row['appt_id']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Are you sure you want to cancel this appointment?');">
                            <i class="fas fa-times-circle me-1"></i> Cancel
                        </a>
                    </td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No appointments scheduled yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</div> </body>
</html>