<?php 
// dashboard.php
include 'header.php'; 

// Fetch basic totals for the top cards
$p_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM patient");
$p_count = $p_query ? mysqli_fetch_assoc($p_query)['total'] : 0;

$d_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM doctors");
$d_count = $d_query ? mysqli_fetch_assoc($d_query)['total'] : 0;

// THE NEW JOIN OPERATION: Fetch the next 5 upcoming appointments
$join_query = "
    SELECT 
        a.appointment_date, 
        p.name AS patient_name, 
        d.name AS doctor_name 
    FROM appointments a
    INNER JOIN patient p ON a.patient_id = p.patient_id
    INNER JOIN doctors d ON a.doctor_id = d.doctor_id
    WHERE a.appointment_date >= NOW()
    ORDER BY a.appointment_date ASC
    LIMIT 5
";
$upcoming_appointments = mysqli_query($conn, $join_query);
?>

<div class="mb-5">
    <h2 class="fw-bold">Hospital Dashboard</h2>
    <p class="text-muted">Welcome to the MedCare Admin Portal.</p>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-4">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 fw-bold">TOTAL PATIENTS</h6>
                    <h2 class="fw-bold mb-0"><?= $p_count; ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success me-4">
                    <i class="fas fa-user-md fa-2x"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 fw-bold">MEDICAL STAFF</h6>
                    <h2 class="fw-bold mb-0"><?= $d_count; ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-3"><i class="fas fa-clock text-warning me-2"></i>Upcoming Appointments Feed</h4>
<div class="card p-4 border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="text-secondary">Date & Time</th>
                    <th class="text-secondary">Patient</th>
                    <th class="text-secondary">Consulting Doctor</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($upcoming_appointments && mysqli_num_rows($upcoming_appointments) > 0) {
                    while($row = mysqli_fetch_assoc($upcoming_appointments)) { 
                        // Format the date nicely
                        $formatted_date = date('d M Y, h:i A', strtotime($row['appointment_date']));
                ?>
                <tr>
                    <td class="fw-semibold text-dark"><?= $formatted_date; ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($row['patient_name']); ?></td>
                    <td><i class="fas fa-user-md text-primary me-2"></i> Dr. <?= htmlspecialchars($row['doctor_name']); ?></td>
                </tr>
                <?php 
                    } 
                } else {
                    echo "<tr><td colspan='3' class='text-center py-4 text-muted'>No upcoming appointments scheduled.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</div> </body>
</html>