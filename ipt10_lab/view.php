<?php
require_once 'db_connect.php';

$id = trim($_GET['id'] ?? '');
if ($id === '') { 
    die('Invalid student ID'); 
}

// TODO (7): Prepare SELECT statement[cite: 11]
$stmt = $conn->prepare('SELECT id, first_name, middle_name, last_name, birthday, sex, email, student_number, program, enrolment_date, created_at, updated_at FROM students WHERE id = ?');
$stmt->bind_param('s', $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f3f4f6;
            color: #1e293b;
        }
        .hero-banner {
            background: linear-gradient(135deg, #0d1527 0%, #1e293b 100%);
            border-radius: 20px 20px 0 0;
            padding: 2.5rem 2rem;
            color: white;
        }
        .profile-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .avatar-circle {
            width: 70px;
            height: 70px;
            background: #3b82f6;
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.2);
        }
        .info-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
        }
        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
        }
    </style>
</head>
<body>

<div class="container py-5" style="max-width: 850px;">
    <div class="mb-3">
        <a href="index.php" class="text-decoration-none text-muted fw-semibold small">
            <i class="bi bi-arrow-left me-1"></i> Back to Student Registry
        </a>
    </div>

    <div class="profile-card">
        <?php if (!$row): ?>
            <div class="p-5 text-center">
                <i class="bi bi-exclamation-circle text-danger fs-1"></i>
                <h4 class="mt-3">Student Record Not Found</h4>
                <p class="text-muted">No student matching that ID exists in the database.</p>
                <a href="index.php" class="btn btn-primary mt-2">Return Home</a>
            </div>
        <?php else: ?>
            <!-- Hero Header -->
            <div class="hero-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-circle">
                        <?= strtoupper(substr($row['first_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <!-- TODO (9): Display student full name with htmlspecialchars()[cite: 12] -->
                        <h3 class="fw-bold m-0">
                            <?= htmlspecialchars($row['first_name'] . ' ' . ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . $row['last_name']) ?>
                        </h3>
                        <span class="badge bg-primary mt-1"><?= htmlspecialchars($row['student_number']) ?></span>
                    </div>
                </div>
                <div>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning text-dark font-semibold px-3 py-2 me-1">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                </div>
            </div>

            <!-- Profile Details Body -->
            <div class="p-4 p-md-5">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="info-box">
                            <div class="info-label">Full System UUID</div>
                            <div class="info-value font-monospace text-primary"><?= htmlspecialchars($row['id']) ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Email Address</div>
                            <div class="info-value"><?= htmlspecialchars($row['email']) ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Academic Program</div>
                            <div class="info-value"><?= htmlspecialchars($row['program']) ?></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Sex</div>
                            <div class="info-value"><?= htmlspecialchars($row['sex']) ?></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Date of Birth</div>
                            <div class="info-value"><?= htmlspecialchars($row['birthday']) ?></div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="info-box">
                            <div class="info-label">Enrolment Date</div>
                            <div class="info-value"><?= htmlspecialchars($row['enrolment_date']) ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Record Created</div>
                            <div class="info-value text-muted small"><?= htmlspecialchars($row['created_at']) ?></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box">
                            <div class="info-label">Last Modified</div>
                            <div class="info-value text-muted small"><?= htmlspecialchars($row['updated_at']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>