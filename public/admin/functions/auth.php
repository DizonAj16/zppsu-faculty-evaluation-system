<?php
session_start();
require_once '../../../config/db.php';
require_once 'control.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit;
}

/* ✅ Helper: Handle Image Upload */
function handleImageUpload($file, $default = "../../../assets/profile/users.png"): string
{
    $target_dir = "../../../assets/upload/";

    if (isset($file) && $file["error"] === 0) {
        $allowed_types = ["image/jpeg", "image/jpg", "image/png"];
        if (!in_array($file["type"], $allowed_types)) {
            throw new Exception("Only JPG, JPEG, PNG files are allowed.");
        }
        if ($file["size"] > 5 * 1024 * 1024) {
            throw new Exception("The image must not exceed 5MB!");
        }

        $image_file_name = uniqid() . "-" . basename($file["name"]);
        $target_file = $target_dir . $image_file_name;

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Error uploading image.");
        }
        return $image_file_name;
    }

    // ✅ Copy default if no upload
    $image_file_name = uniqid() . "-users.png";
    copy($default, $target_dir . $image_file_name);
    return $image_file_name;
}

/* ✅ Helper: Delete Old Image */
function deleteOldImage($oldImage): void
{
    $path = "../../../assets/upload/" . $oldImage;
    if ($oldImage && file_exists($path) && !str_contains($oldImage, "users.png")) {
        unlink($path);
    }
}

/* ✅ ADD FACULTY */
if (isset($_POST["addFaculty"]) && $_POST["addFaculty"] === "true") {
    try {
        $pdo->beginTransaction();

        $faculty_id = $_POST["faculty_id"];
        $fullname = $_POST["fullname"];
        $department = $_POST["department"];
        $email = $_POST["email"];
        $position = $_POST["position"];
        $subject_ids = $_POST["subject_ids"] ?? [];

        $profile = handleImageUpload($_FILES["profile"]);

        $stmt = $pdo->prepare("
            INSERT INTO faculty (faculty_id, fulname, department_id, email, position, user_profile)
            VALUES (:faculty_id, :fulname, :department_id, :email, :position, :user_profile)
        ");
        $stmt->execute([
            ':faculty_id' => $faculty_id,
            ':fulname' => $fullname,
            ':department_id' => $department,
            ':email' => $email,
            ':position' => $position,
            ':user_profile' => $profile
        ]);

        $facultyId = $pdo->lastInsertId();

        if (!empty($subject_ids)) {
            $stmtSub = $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (?, ?)");
            foreach ($subject_ids as $subId) {
                $stmtSub->execute([$facultyId, $subId]);
            }
        }

        $pdo->commit();
        header("Location: ../admin_faculty.php?success=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die("❌ Add Faculty failed: " . $e->getMessage());
    }
}

/* ✅ DELETE FACULTY */
if (isset($_POST["deleteFaculty"]) && $_POST["deleteFaculty"] === "true") {
    $id = $_POST["faculty_id"] ?? null;

    if (!$id) {
        header("Location: ../admin_faculty.php?failed=1");
        exit;
    }

    try {
        // ✅ Delete profile image first
        $stmt = $pdo->prepare("SELECT user_profile FROM faculty WHERE id = ?");
        $stmt->execute([$id]);
        $oldProfile = $stmt->fetchColumn();
        deleteOldImage($oldProfile);

        // ✅ Delete faculty record
        $stmt = $pdo->prepare("DELETE FROM faculty WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: ../admin_faculty.php?deleted=1");
        exit;
    } catch (Exception $e) {
        die("❌ Delete failed: " . $e->getMessage());
    }
}

/* ✅ EDIT FACULTY */
if (isset($_POST["EditFaculty"]) && $_POST["EditFaculty"] === "true") {
    $id = $_POST["editJobId"] ?? null;
    if (!$id) {
        header('Location: ../admin_faculty.php?failed=1');
        exit;
    }

    try {
        $pdo->beginTransaction();

        $facultyCode = $_POST["faculty_id"];
        $fullName = $_POST["fulname"];
        $email = $_POST["email"];
        $position = $_POST["position"];
        $deptId = $_POST["department_id"];
        $subject_ids = $_POST["subject_ids"] ?? [];

        // ✅ Handle profile update
        $profile = null;
        if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] === 0) {
            // ✅ Get old profile to delete later
            $stmtOld = $pdo->prepare("SELECT user_profile FROM faculty WHERE id = ?");
            $stmtOld->execute([$id]);
            $oldProfile = $stmtOld->fetchColumn();

            $profile = handleImageUpload($_FILES["profile"]);
            deleteOldImage($oldProfile);
        }

        $sql = "
            UPDATE faculty SET
                faculty_id = :faculty_code,
                fulname = :full_name,
                department_id = :dept_id,
                email = :email,
                position = :position
                " . ($profile ? ", user_profile = :profile" : "") . "
            WHERE id = :id
        ";

        $params = [
            ':faculty_code' => $facultyCode,
            ':full_name' => $fullName,
            ':dept_id' => $deptId,
            ':email' => $email,
            ':position' => $position,
            ':id' => $id
        ];
        if ($profile) {
            $params[':profile'] = $profile;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // ✅ Update subjects
        $pdo->prepare("DELETE FROM faculty_subjects WHERE faculty_id = ?")->execute([$id]);
        if (!empty($subject_ids)) {
            $stmtSub = $pdo->prepare("INSERT INTO faculty_subjects (faculty_id, subject_id) VALUES (?, ?)");
            foreach ($subject_ids as $subId) {
                $stmtSub->execute([$id, $subId]);
            }
        }

        $pdo->commit();
        header('Location: ../admin_faculty.php?updated=1');
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Faculty update failed: " . $e->getMessage());
        header('Location: ../admin_faculty.php?failed=1');
        exit;
    }
}
