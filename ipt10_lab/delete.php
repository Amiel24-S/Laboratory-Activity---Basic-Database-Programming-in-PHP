<?php
require_once 'db_connect.php';

// TODO (18): Validate the id parameter exists and is non-empty[cite: 15]
$id = trim($_GET['id'] ?? '');

if ($id === '') {
    die("Invalid or missing student ID.");
}

// TODO (19): Prepare a DELETE statement using a placeholder for id[cite: 15]
$sql = "DELETE FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // TODO (20): Bind parameter (id is string UUID, so 's') and execute[cite: 15]
    $stmt->bind_param('s', $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        // Redirect back to main page after successful deletion[cite: 15]
        header("Location: index.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
} else {
    echo "Failed to prepare deletion statement: " . $conn->error;
}

$conn->close();
?>