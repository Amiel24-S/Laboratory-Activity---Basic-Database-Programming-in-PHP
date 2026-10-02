<?php
require_once 'db_connect.php';

$errors = [];
$first_name = $middle_name = $last_name = $email = $student_number = $program = $birthday = $sex = $enrolment_date = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name     = trim($_POST['first_name'] ?? '');
    $middle_name    = trim($_POST['middle_name'] ?? '');
    $last_name      = trim($_POST['last_name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $student_number = trim($_POST['student_number'] ?? '');
    $program        = trim($_POST['program'] ?? '');
    $birthday       = trim($_POST['birthday'] ?? '');
    $sex            = trim($_POST['sex'] ?? '');
    $enrolment_date = trim($_POST['enrolment_date'] ?? '');

    // TODO (10): Validate first and last names (Required, 2-100 chars, letters & spaces only)[cite: 13, 22]
    if (empty($first_name) || strlen($first_name) < 2 || strlen($first_name) > 100 || !preg_match("/^[A-Za-z\s]+$/", $first_name)) {
        $errors['first_name'] = 'First name is required (2-100 characters, letters and spaces only).';
    }

    if (empty($last_name) || strlen($last_name) < 2 || strlen($last_name) > 100 || !preg_match("/^[A-Za-z\s]+$/", $last_name)) {
        $errors['last_name'] = 'Last name is required (2-100 characters, letters and spaces only).';
    }

    // TODO (11): Validate email, birthday, and enrolment date[cite: 13, 22]
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }

    if (empty($birthday) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $birthday)) {
        $errors['birthday'] = 'Birthday is required and must match YYYY-MM-DD format.';
    }

    if (empty($enrolment_date) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $enrolment_date)) {
        $errors['enrolment_date'] = 'Enrolment date is required and must match YYYY-MM-DD format.';
    }

    // TODO (12): Validate sex against ENUM values ('Male', 'Female')[cite: 13, 22]
    if (empty($sex) || !in_array($sex, ['Male', 'Female'], true)) {
        $errors['sex'] = 'Please select a valid option (Male or Female).';
    }

    // Optional fields validation[cite: 22]
    if ($student_number !== '' && (!preg_match("/^[A-Za-z0-9]+$/", $student_number) || strlen($student_number) > 50)) {
        $errors['student_number'] = 'Student number must be alphanumeric and up to 50 characters.';
    }

    if ($program !== '' && strlen($program) > 200) {
        $errors['program'] = 'Program name cannot exceed 200 characters.';
    }

    // If no validation errors, proceed to INSERT[cite: 13]
    if (empty($errors)) {
        // TODO (13): Prepared statement for INSERT with UUID() in SQL[cite: 13]
        $sql = "INSERT INTO students (id, first_name, middle_name, last_name, birthday, sex, email, student_number, program, enrolment_date) 
                VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);

        // TODO (14): Bind parameters (9 placeholders for remaining fields)[cite: 13]
        $stmt->bind_param('sssssssss', $first_name, $middle_name, $last_name, $birthday, $sex, $email, $student_number, $program, $enrolment_date);
        
        if ($stmt->execute()) {
            // Confirm write with affected_rows[cite: 7, 13]
            if ($stmt->affected_rows > 0) {
                $success_msg = "Student record created successfully!";
                $first_name = $middle_name = $last_name = $email = $student_number = $program = $birthday = $sex = $enrolment_date = '';
            }
        } else {
            $errors['db'] = "Failed to create student: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student</title>
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
        .form-card {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .form-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-custom mb-5">
        <div class="container" style="max-width: 900px;">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white p-2 rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-mortarboard-fill fs-5"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-white m-0">IPT10 Student Portal</h4>
                    <span class="text-white-50 small">Procedural mysqli Database Application</span>
                </div>
            </div>
            <a href="index.php" class="btn btn-outline-light btn-sm fw-semibold px-3">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mb-5" style="max-width: 900px;">
        <div class="form-card p-4 p-md-5">
            <div class="mb-4 pb-3 border-bottom">
                <h3 class="fw-bold m-0 text-dark">Add New Student</h3>
                <p class="text-muted small m-0 mt-1">Register a new student record into the system</p>
            </div>

            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                    <div><?= htmlspecialchars($success_msg) ?></div>
                </div>
            <?php endif; ?>

            <?php if (isset($errors['db'])): ?>
                <div class="alert alert-danger mb-4" role="alert">
                    <?= htmlspecialchars($errors['db']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>
                <div class="row g-3">
                    <!-- First Name -->
                    <div class="col-md-4">
                        <label class="form-label">First Name *</label>
                        <input type="text" name="first_name" class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($first_name) ?>">
                        <?php if (isset($errors['first_name'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['first_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Middle Name -->
                    <div class="col-md-4">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" value="<?= htmlspecialchars($middle_name) ?>">
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-4">
                        <label class="form-label">Last Name *</label>
                        <input type="text" name="last_name" class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($last_name) ?>">
                        <?php if (isset($errors['last_name'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['last_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($email) ?>">
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Student Number -->
                    <div class="col-md-6">
                        <label class="form-label">Student Number</label>
                        <input type="text" name="student_number" class="form-control <?= isset($errors['student_number']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($student_number) ?>">
                        <?php if (isset($errors['student_number'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['student_number']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Academic Program -->
                    <div class="col-12">
                        <label class="form-label">Academic Program *</label>
                        <input type="text" name="program" class="form-control <?= isset($errors['program']) ? 'is-invalid' : '' ?>" placeholder="e.g. Bachelor of Science in Information Technology" value="<?= htmlspecialchars($program) ?>">
                        <?php if (isset($errors['program'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['program']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Sex -->
                    <div class="col-md-4">
                        <label class="form-label">Sex *</label>
                        <select name="sex" class="form-select <?= isset($errors['sex']) ? 'is-invalid' : '' ?>">
                            <option value="">-- Select --</option>
                            <option value="Male" <?= $sex === 'Male' ? 'selected' : '' ?>>Male</option>
                            <option value="Female" <?= $sex === 'Female' ? 'selected' : '' ?>>Female</option>
                        </select>
                        <?php if (isset($errors['sex'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['sex']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Birthday -->
                    <div class="col-md-4">
                        <label class="form-label">Birthday *</label>
                        <input type="date" name="birthday" class="form-control <?= isset($errors['birthday']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($birthday) ?>">
                        <?php if (isset($errors['birthday'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['birthday']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Enrolment Date -->
                    <div class="col-md-4">
                        <label class="form-label">Enrolment Date *</label>
                        <input type="date" name="enrolment_date" class="form-control <?= isset($errors['enrolment_date']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($enrolment_date) ?>">
                        <?php if (isset($errors['enrolment_date'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['enrolment_date']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <a href="index.php" class="btn btn-light border fw-semibold px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary fw-semibold px-4">Save Student</button>
                </div>
            </form>
        </div>
    </div>

    <?php $conn->close(); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>