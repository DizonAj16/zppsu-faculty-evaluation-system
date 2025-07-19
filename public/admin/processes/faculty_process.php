<?php
require_once '../../../config/db.php'; // Adjust if needed
require_once '../../../includes/auth.php';
require_role('admin');

// ✅ Functions to handle image upload
function uploadProfile($file, $existingFile = null) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return $existingFile ?? null;
    }

    $uploadDir = '../../../assets/upload/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $fileType = mime_content_type($file['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        die("❌ Invalid file type. Only JPG and PNG allowed.");
    }

    $fileName = uniqid('faculty_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $destination = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // ✅ Delete old image if replacing
        if ($existingFile && file_exists($uploadDir . $existingFile)) {
            unlink($uploadDir . $existingFile);
        }
        return $fileName;
    }
    return $existingFile ?? null;
}

// ✅ ADD FACULTY
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    try {
        $pdo->beginTransaction();

        $profileImage = uploadProfile($_FILES['profile'] ?? null);

        // Insert faculty
        $stmt = $pdo->prepare("INSERT INTO faculty (faculty_id, fulname, email, position, department_id, user_profile)
                               VALUES (:faculty_id, :fulname, :email, :position, :department_id, :user_profile)");
        $stmt->execute([
            ':faculty_id'    => $_POST['faculty_id'],
            ':fulname'       => $_POST['fullname'],
            ':email'         => $_POST['email'],
            ':position'      => $_POST['position'],
            ':department_id' => $_POST['department_id'],
            ':user_profile'  => $profileImage
        ]);
        $facultyId = $pdo->lastInsertId();

        // Insert subjects
        if (!empty($_POST['subject_ids'])) {
            $stmtSub = $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (:faculty_id, :subject_id)");
            foreach ($_POST['subject_ids'] as $subjectId) {
                $stmtSub->execute([
                    ':faculty_id' => $facultyId,
                    ':subject_id' => $subjectId
                ]);
            }
        }

        $pdo->commit();
        header("Location: ../admin_faculty.php?success=added");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("❌ Failed to add faculty: " . $e->getMessage());
    }
}

// ✅ EDIT FACULTY
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    try {
        $pdo->beginTransaction();

        $facultyId = (int)$_POST['faculty_pk'];
        $existingProfile = $_POST['existing_profile'] ?? null;
        $profileImage = uploadProfile($_FILES['profile'] ?? null, $existingProfile);

        // Update faculty table
        $stmt = $pdo->prepare("UPDATE faculty 
                               SET faculty_id=:faculty_id, fulname=:fulname, email=:email, position=:position, 
                                   department_id=:department_id, user_profile=:user_profile 
                               WHERE id=:id");
        $stmt->execute([
            ':faculty_id'    => $_POST['faculty_id'],
            ':fulname'       => $_POST['fullname'],
            ':email'         => $_POST['email'],
            ':position'      => $_POST['position'],
            ':department_id' => $_POST['department_id'],
            ':user_profile'  => $profileImage,
            ':id'            => $facultyId
        ]);

        // Delete old subjects first
        $pdo->prepare("DELETE FROM faculty_subjects WHERE faculty_id=:faculty_id")
            ->execute([':faculty_id' => $facultyId]);

        // Insert updated subjects
        if (!empty($_POST['subject_ids'])) {
            $stmtSub = $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (:faculty_id, :subject_id)");
            foreach ($_POST['subject_ids'] as $subjectId) {
                $stmtSub->execute([
                    ':faculty_id' => $facultyId,
                    ':subject_id' => $subjectId
                ]);
            }
        }

        $pdo->commit();
        header("Location: ../admin_faculty.php?success=updated");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("❌ Failed to update faculty: " . $e->getMessage());
    }
}

// ✅ DELETE FACULTY
if (isset($_GET['delete'])) {
    try {
        $pdo->beginTransaction();

        $facultyId = (int)$_GET['delete'];

        // Get profile image for deletion
        $stmt = $pdo->prepare("SELECT user_profile FROM faculty WHERE id=:id");
        $stmt->execute([':id' => $facultyId]);
        $faculty = $stmt->fetch(PDO::FETCH_ASSOC);

        // Delete from faculty_subjects first
        $pdo->prepare("DELETE FROM faculty_subjects WHERE faculty_id=:faculty_id")
            ->execute([':faculty_id' => $facultyId]);

        // Delete faculty
        $pdo->prepare("DELETE FROM faculty WHERE id=:id")->execute([':id' => $facultyId]);

        // Delete profile image if exists
        if (!empty($faculty['user_profile'])) {
            $imagePath = '../../../assets/upload/' . $faculty['user_profile'];
            if (file_exists($imagePath)) unlink($imagePath);
        }

        $pdo->commit();
        header("Location: ../admin_faculty.php?success=deleted");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("❌ Failed to delete faculty: " . $e->getMessage());
    }
}
?>
