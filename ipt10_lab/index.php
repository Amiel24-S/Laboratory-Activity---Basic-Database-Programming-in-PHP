<?php
require_once 'db_connect.php';

// TODO (4): SELECT columns id, first_name, last_name, email, enrolment_date[cite: 10]
$sql = "SELECT id, first_name, last_name, email, enrolment_date FROM students ORDER BY enrolment_date DESC, id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registry</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts & Bootstrap Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f3f4f6;
            color: #1e293b;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0d1527 0%, #1e293b 100%);
            padding: 1.25rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .main-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .table-modern {
            border-collapse: separate;
            border-spacing: 0 8px;
            margin-bottom: 0;
        }
        .table-modern tbody tr {
            background-color: #f8fafc;
            border-radius: 12px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .table-modern tbody tr:hover {
            background-color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        .table-modern td, .table-modern th {
            padding: 1.1rem 1.25rem;
            vertical-align: middle;
            border: none;
        }
        .table-modern thead th {
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 700;
        }
        .uuid-chip {
            font-family: monospace;
            background: #e0e7ff;
            color: #3730a3;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: scale(1.08);
        }
    </style>
</head>
<body>

    <!-- Top Navigation Header -->
    <nav class="navbar navbar-custom mb-5">
        <div class="container" style="max-width: 1050px;">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-mortarboard-fill fs-5"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-white m-0">IPT10 Student Portal</h4>
                    <span class="text-white-50 small">Procedural mysqli Database Application</span>
                </div>
            </div>
            <a href="create.php" class="btn btn-warning fw-semibold px-4 py-2 text-dark shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Add Student
            </a>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container mb-5" style="max-width: 1050px;">
        <div class="main-card p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold m-0 text-dark">Registered Students</h3>
                    <p class="text-muted small m-0 mt-1">Manage and view all enrolled student records in the database</p>
                </div>
                <span class="badge bg-light text-dark border px-3 py-2 fw-medium">
                    <i class="bi bi-database me-1 text-primary"></i> Live Database
                </span>
            </div>

            <!-- Table Section -->
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>UUID Reference</th>
                            <th>Student Name</th>
                            <th>Email Address</th>
                            <th>Enrolment Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php // TODO (5): Loop over the result set using fetch_assoc()[cite: 10] ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <span class="uuid-chip" title="<?= htmlspecialchars($row['id']) ?>">
                                    <?= htmlspecialchars(substr($row['id'], 0, 8)) ?>...
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></div>
                            </td>
                            <td class="text-muted small">
                                <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($row['email']) ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1"><?= htmlspecialchars($row['enrolment_date']) ?></span>
                            </td>
                            <td class="text-end">
                                <a href="view.php?id=<?= $row['id'] ?>" class="action-btn bg-light text-primary me-1" title="View Profile">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="edit.php?id=<?= $row['id'] ?>" class="action-btn bg-light text-warning me-1" title="Edit Record">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="delete.php?id=<?= $row['id'] ?>" class="action-btn bg-light text-danger" onclick="return confirm('Are you sure you want to delete this student?')" title="Delete Record">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php 
    // TODO (6): Release resources[cite: 11]
    mysqli_free_result($result);
    mysqli_close($conn);
    ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>