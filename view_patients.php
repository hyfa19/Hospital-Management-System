<?php 
include 'header.php'; // This connects to your database

// 1. Fetch data from the FIXED View
$query = "SELECT * FROM view_patient_details";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-primary"><i class="fas fa-user-injured me-2"></i>Patient Directory</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="add_patient.php" class="btn btn-success shadow-sm">
                <i class="fas fa-plus me-2"></i>Register New Patient
            </a>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Patient Name</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>Assigned Doctor</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>#<?php echo $row['patient_id']; ?></td>
                        <td class="fw-bold"><?php echo $row['patient_name']; ?></td>
                        <td><?php echo date('d-M-Y', strtotime($row['dob'])); ?></td>
                        <td><?php echo $row['gender']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><span class="badge bg-info text-dark">Dr. <?php echo $row['assigned_doctor']; ?></span></td>
                        <td>
                            <a href="update_patient.php?id=<?php echo $row['patient_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>