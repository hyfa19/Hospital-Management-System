<?php
// 1. CONNECT TO DATABASE
include 'header.php'; 

// 2. SEARCH LOGIC (The "Brain")
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

// This SQL finds names or specialties that match what you type
$query = "SELECT * FROM doctors 
          WHERE name LIKE '%$search%' 
          OR specialization LIKE '%$search%'";

$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-primary"><i class="fas fa-user-md me-2"></i>Doctor Directory</h2>
        </div>
        <div class="col-md-6">
            <form method="GET" class="d-flex shadow-sm">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Search name or specialty..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary px-4">Search</button>
                <a href="view_doctors.php" class="btn btn-outline-secondary ms-2">Reset</a>
            </form>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Doctor Name</th>
                        <th>Specialization</th>
                        <th>Phone Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Check if we found any doctors
                    if(mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) { 
                    ?>
                        <tr>
                            <td>#<?php echo $row['doctor_id']; ?></td>
                            <td class="fw-bold"><?php echo $row['name']; ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo $row['specialization']; ?></span></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td>
                                <a href="edit_doctor.php?id=<?php echo $row['doctor_id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            </td>
                        </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>No doctors found matching '$search'</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>