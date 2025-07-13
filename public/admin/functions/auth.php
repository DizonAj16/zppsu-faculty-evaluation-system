<?php 
session_start();
require_once '../../../config/db.php';
require_once 'control.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if(isset($_POST["addFaculty"]) && $_POST["addFaculty"] == "true"){
        echo $faculty_id = $_POST["faculty_id"];
        echo '<br>';
        echo $fullname = $_POST["fullname"]; echo '<br>';
        echo $department = $_POST["department"]; echo '<br>';
        $email = $_POST["email"];
        $position = $_POST["position"];
        $subject_id = intval($_POST["subject_id"]);

        $errors = [];

        if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] === 0) {
                echo $profile = $_FILES["profile"];

                if (empty_image($profile)) {
                    $errors["image_Empty"] = "Please insert your profile image!";
                }

                if (fileSize_notCompatible($profile)) {
                    $errors["large_File"] = "The image must not exceed 5MB!";
                }

                $allowed_types = [
                    "image/jpeg",
                    "image/jpg",
                    "image/png"
                ];

                if (image_notCompatible($profile, $allowed_types)) {
                    $errors["file_Types"] = "Only JPG, JPEG, PNG files are allowed.";
                }

                if (!$errors) {
                    $target_dir = "../../../assets/upload/";
                    $image_file_name = uniqid() . "-" . basename($profile["name"]);
                    $target_file = $target_dir . $image_file_name;

                    if (move_uploaded_file($profile["tmp_name"], $target_file)) {
                        $profile = $image_file_name;
                    } else {
                        $errors["upload_Error"] = "There was an error uploading your image.";
                    }
                }
            } else {
                $default_image = "../../../assets/profile/users.png";
                $target_dir = "../../../assets/upload/";
                $image_file_name = uniqid() . "-users.png";
                $target_file = $target_dir . $image_file_name;

                if (copy($default_image, $target_file)) {
                    $profile = $image_file_name;
                } else {
                    $errors["upload_Error"] = "Failed to assign default profile image.";
                }
            }
            try { 
                echo $faculty_id;
                $query = "INSERT INTO faculty (faculty_id, fulname, DEPTid, email, position, subject_id, user_profile
                ) VALUES (
                :faculty_id, :fulname, :DEPTid, :email, :position, :subject_id, :user_profile);";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(":faculty_id", $faculty_id);
                $stmt->bindParam(":fulname", $fullname);
                $stmt->bindParam(":DEPTid", $department);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":position", $position);
                $stmt->bindParam(":subject_id", $subject_id);
                $stmt->bindParam(":user_profile", $profile);
                $stmt->execute();
                header("Location: ../admin.php?page=faculty&success=1");
                die();
            } catch (PDOException $e) {
                die("query failed: " . $e->getMessage());
            }
    }elseif (isset($_POST['deleteFaculty']) && $_POST["deleteFaculty"] === "true") {
       echo $id = $_POST['faculty_id'] ?? 'Helo?';
        if(!$id){
            header("Location: ../admin.php?page=faculty&failed=1");
            exit;
        }
        try {
            $stmt = $pdo->prepare("DELETE FROM faculty WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: ../admin.php?page=faculty&deleted=1");
            exit;
        } catch (PDOException $e) {
            die("❌ Delete failed: " . $e->getMessage());
        }
    
    }elseif (isset($_POST['EditFaculty']) && $_POST['EditFaculty'] === 'true') {

    /* ---------------- grab POST values ---------------- */
    $id          = $_POST['editJobId']   ?? null;          // primary‑key id
    $facultyCode = $_POST['faculty_id']  ?? '';            // the visible code
    $fullName    = $_POST['fulname']     ?? '';
    $email       = $_POST['email']       ?? '';
    $position    = $_POST['position']    ?? '';

    $subjectId   = $_POST['subject_id']  ?? null;          // may be null
    $deptId      = $_POST['department']  ?? null;          // name matches <select>

    if (!$id) {
        header('Location: ../admin.php?page=faculty&failed=1');
        exit;
    }

    try {
        $sql = "UPDATE faculty SET
                    faculty_id   = :faculty_code,
                    fulname      = :full_name,
                    subject_id   = :subject_id,
                    DEPTid       = :dept_id,
                    email        = :email,
                    position     = :position
                WHERE id = :id";         

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':faculty_code' => $facultyCode,
            ':full_name'    => $fullName,
            ':subject_id'   => $subjectId ?: null,
            ':dept_id'      => $deptId    ?: null,
            ':email'        => $email,
            ':position'     => $position,
            ':id'           => $id
        ]);

        header('Location: ../admin.php?page=faculty&updated=1');
        exit;

    } catch (PDOException $e) {
        error_log('Faculty update failed: ' . $e->getMessage());
        header('Location: ../admin.php?page=faculty&failed=1');
        exit;
    }
}

}