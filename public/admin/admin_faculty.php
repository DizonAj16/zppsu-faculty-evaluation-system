<?php
require_once '../../config/db.php';

// ✅ FETCH SUBJECTS
$stmt = $pdo->query("SELECT subject_id, subject_code, subject_name FROM subjects ORDER BY subject_name ASC");
$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ FETCH DEPARTMENTS
$stmt = $pdo->query("SELECT department_id, department_name, department_code FROM departments ORDER BY department_name ASC");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ FETCH FACULTY (with subjects)
$sql = "
  SELECT f.id, f.faculty_id, f.fulname AS fulname, f.email, f.position,
         f.department_id, d.department_name, d.department_code, f.user_profile
  FROM faculty f
  LEFT JOIN departments d ON f.department_id = d.department_id
";
$facultyData = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// ✅ Attach subjects to each faculty
foreach ($facultyData as &$f) {
  $stmt = $pdo->prepare("
        SELECT s.subject_id, s.subject_name, s.subject_code
        FROM faculty_subjects fs
        JOIN subjects s ON fs.subject_id = s.subject_id
        WHERE fs.faculty_id = ?
    ");
  $stmt->execute([$f['id']]);
  $f['subjects'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
unset($f);
$faculty = $facultyData;
?>

<?php require_once '../../includes/header-user.php'; ?>

<div class="d-flex" id="adminLayout" style="min-height: 100vh;">
  <div id="sidebarContainer">
    <?php include '../../includes/sidenav.php'; ?>
  </div>

  <div id="dashboardContainer" class="flex-grow-1">
    <div class="container-fluid p-4">
      <h2 class="mb-4">Faculty Management</h2>
      <div class="card shadow-sm mb-4">
        <div class="card-body" style="height: 70vh; overflow-y: auto;">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Faculty List</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
              <i class="bi bi-plus-circle"></i> Add Faculty
            </button>
          </div>

          <div class="row g-3">
            <?php if (empty($faculty)): ?>
              <div class="col-12">
                <div class="alert alert-warning text-center">
                  <strong>No faculty members found.</strong>
                </div>
              </div>
            <?php else: ?>
              <?php foreach ($faculty as $f): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                  <div class="card text-center shadow h-100 rounded-4" style="background-color:#800000;color:#fff;">
                    <div class="card-body d-flex flex-column align-items-center">
                      <img src="../../assets/upload/<?= $f["user_profile"] ?: 'default.png' ?>" class="rounded-circle mb-2"
                        style="width:200px;height:200px;object-fit:cover;">
                      <p class="mb-1 fw-light" style="font-size:14px;"><?= htmlspecialchars($f["faculty_id"]) ?></p>
                      <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($f["fulname"]) ?></h5>
                      <p class="mb-1 fst-italic" style="font-size:14px;"><?= htmlspecialchars($f["position"]) ?></p>
                      <p class="mb-2" style="font-size:14px;"><?= htmlspecialchars($f["department_code"]) ?></p>
                      <div class="d-flex justify-content-center gap-2 mt-auto w-100">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#viewFacultyModal"
                          onclick='viewFaculty(
                            <?= json_encode($f["faculty_id"]) ?>,
                            <?= json_encode($f["fulname"]) ?>,
                            <?= json_encode($f["department_name"]) ?>,
                            <?= json_encode($f["email"]) ?>,
                            <?= json_encode($f["position"]) ?>,
                            <?= json_encode(array_column($f["subjects"], "subject_name")) ?>,
                            <?= json_encode($f["user_profile"]) ?>
                          )'>
                          <i class="bi bi-eye"></i> View
                        </button>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editFacultyModal"
                          onclick='editFaculty(
                            <?= (int) $f["id"] ?>,
                            <?= json_encode($f["faculty_id"]) ?>,
                            <?= json_encode($f["fulname"]) ?>,
                            <?= json_encode($f["email"]) ?>,
                            <?= json_encode($f["position"]) ?>,
                            <?= json_encode(array_column($f["subjects"], "subject_id")) ?>,
                            <?= json_encode($f["department_id"]) ?>,
                            <?= json_encode($f["user_profile"]) ?>
                          )'>
                          <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFacultyModal"
                          onclick="setDeleteId(<?= $f['id'] ?>)">
                          <i class="bi bi-trash"></i> Delete
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- ✅ VIEW FACULTY MODAL (Minimalistic Design - Bigger Image & Text) -->
<div class="modal fade" id="viewFacultyModal" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content border-0 shadow rounded-4" style="background-color: #fff;">

      <!-- Header -->
      <div class="modal-header border-0" style="background-color: #800000; color: #FFD700;">
        <h5 class="modal-title fw-semibold" style="font-size: 1.3rem;">Faculty Information</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body text-center">
        <div class="card border-0 shadow-sm rounded-4 p-4" style="background: #fdfdfd;">

          <!-- Profile Image -->
          <img id="viewImagePreview" class="rounded-circle shadow-sm border border-3 mx-auto d-block"
            style="border-color: #FFD700; width:200px; height:200px; object-fit:cover; margin-bottom:15px;">

          <!-- Faculty ID -->
          <p id="viewFacultyId" class="fw-semibold mb-1" style="color:#800000; font-size:14px;"></p>

          <!-- Position -->
          <p id="viewPosition" class="fst-italic mb-1" style="font-size: 15px;"></p>

          <!-- Full Name -->
          <h5 id="viewFullName" class="fw-bold mb-1 fs-3" style="color:#800000; font-size: 1.3rem;"></h5>

          <!-- Faculty ID, Department, Email as simple text (no labels) -->
          <p id="viewDepartment" class="fw-semibold mb-1" style="color:#800000; font-size:14px;"></p>
          <p id="viewEmail" class="text-muted mb-3" style="font-size:14px;"></p>

          <hr style="border-top: 1px solid #ddd;">

          <!-- Subjects as Bullet List -->
          <div class="text-start" style="max-width: 350px; margin: 0 auto;">
            <p class="fw-semibold mb-2" style="color:#800000; font-size: 15px;">Subjects:</p>
            <ul id="viewSubject" style="padding-left:18px; color:#333; font-size:14px;"></ul>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer border-0">
        <button type="button" class="btn" data-bs-dismiss="modal"
          style="background-color:#800000; color:#FFD700; border-radius:6px; padding:8px 24px; font-size: 15px;">
          Close
        </button>
      </div>
    </div>
  </div>
</div>






<!-- ✅ ADD FACULTY MODAL -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4" style="background-color: #fff;">
      <form action="../admin/processes/faculty_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <div class="modal-header border-0" style="background-color: #800000; color: #FFD700;">
          <h5 class="modal-title">Add Faculty</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-3">
            <label for="profile"><img src="../../assets/profile/users.png" id="profilePreview" class="img-thumbnail"
                style="width:200px;height:200px;border-radius:50%;object-fit:cover;"></label>
            <input type="file" class="d-none" name="profile" id="profile" accept="image/*"
              onchange="previewImage(event,'profilePreview')">
          </div>
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Faculty ID</label>
              <input type="text" class="form-control" name="faculty_id" required>
            </div>
            <div class="col-md-6"><label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="fullname" required>
            </div>
            <div class="col-md-6"><label class="form-label">Department</label>
              <select class="form-select" name="department_id" required>
                <option value="" disabled selected>Select department</option>
                <?php foreach ($departments as $d): ?>
                  <option value="<?= $d['department_id'] ?>">
                    <?= htmlspecialchars($d['department_code']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6"><label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" required>
            </div>
            <div class="col-md-6"><label class="form-label">Position</label>
              <input type="text" class="form-control" name="position" required>
            </div>
            <div class="col-md-12"><label class="form-label">Subjects</label>
              <div id="subjectContainer">
                <div class="input-group mb-2">
                  <select class="form-select" name="subject_ids[]" required>
                    <option value="" disabled selected>Select subject</option>
                    <?php foreach ($subjects as $s): ?>
                      <option value="<?= $s['subject_id'] ?>">
                        <?= htmlspecialchars($s['subject_code'] . ' – ' . $s['subject_name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <button type="button" class="btn btn-success" onclick="addSubjectField()">+</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn"
            style="background-color:#800000; color:#FFD700; border-radius:6px; padding:8px 24px; font-size: 15px;">
            Save Faculty
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ✅ EDIT FACULTY MODAL -->
<div class="modal fade" id="editFacultyModal" tabindex="-1">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-4" style="background-color: #fff;">
      <form action="../admin/processes/faculty_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="faculty_pk" id="editFacultyId">
        <input type="hidden" name="existing_profile" id="edit_existing_profile">
        <div class="modal-header border-0" style="background-color: #800000; color: #FFD700;">
          <h5 class="modal-title">Edit Faculty</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="text-center mb-3">
            <label for="edit_profile">
              <img src="../../assets/profile/users.png" id="editImagePreview" class="img-thumbnail"
                style="width:200px;height:200px;border-radius:50%;object-fit:cover;">
            </label>
            <input type="file" class="d-none" name="profile" id="edit_profile" accept="image/*"
              onchange="previewImage(event,'editImagePreview')">
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Faculty ID</label>
              <input type="text" class="form-control" id="edit_faculty_id" name="faculty_id" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" id="edit_fullname" name="fullname" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Department</label>
              <select class="form-select" id="edit_department" name="department_id" required>
                <option value="" disabled>Select department</option>
                <?php foreach ($departments as $d): ?>
                  <option value="<?= $d['department_id'] ?>">
                    <?= htmlspecialchars($d['department_code']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Position</label>
              <input type="text" class="form-control" id="edit_position" name="position" required>
            </div>
            <div class="col-md-12">
              <label class="form-label">Subjects</label>
              <div id="editSubjectContainer"></div>
              <button type="button" class="btn btn-success mt-2" onclick="addEditSubjectField()">+ Add Subject</button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn"
            style="background-color:#800000; color:#FFD700; border-radius:6px; padding:8px 24px; font-size: 15px;">
            Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ✅ DELETE FACULTY MODAL -->
<div class="modal fade" id="deleteFacultyModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="GET" action="../admin/processes/faculty_process.php" class="modal-content">
      <input type="hidden" name="delete" id="deleteId">
      <div class="modal-header border-0" style="background-color: #800000; color: #FFD700;">
        <h5 class="modal-title">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Are you sure you want to delete this faculty member?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn"
          style="background-color:#800000; color:#FFD700; border-radius:6px; padding:8px 24px; font-size: 15px;">
          Delete
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  const allSubjects = <?= json_encode($subjects) ?>;
  function previewImage(e, id) { const r = new FileReader(); r.onload = () => document.getElementById(id).src = r.result; if (e.target.files[0]) r.readAsDataURL(e.target.files[0]); }

  function viewFaculty(id, fullname, dept, email, pos, subs, profile) {
    document.getElementById('viewFullName').innerText = fullname;
    document.getElementById('viewPosition').innerText = pos;
    document.getElementById('viewFacultyId').innerText = id;
    document.getElementById('viewDepartment').innerText = dept;
    document.getElementById('viewEmail').innerText = email;
    document.getElementById('viewImagePreview').src = profile
      ? `../../assets/upload/${profile}`
      : '../../assets/profile/users.png';

    // ✅ Subjects as Bullet List
    const subjectContainer = document.getElementById('viewSubject');
    subjectContainer.innerHTML = subs.length
      ? subs.map(sub => `<li>${sub}</li>`).join("")
      : `<li class="text-muted">No subjects assigned</li>`;
  }


  function editFaculty(pk, id, fullname, email, pos, subs, dept, profile) {
    document.getElementById('editFacultyId').value = pk;
    document.getElementById('edit_faculty_id').value = id;
    document.getElementById('edit_fullname').value = fullname;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_position').value = pos;
    document.getElementById('edit_department').value = dept;
    document.getElementById('edit_existing_profile').value = profile || "";
    document.getElementById('editImagePreview').src = profile
      ? `../../assets/upload/${profile}` : '../../assets/profile/users.png';
    const container = document.getElementById("editSubjectContainer");
    container.innerHTML = "";
    if (!subs || !subs.length) {
      addEditSubjectField();
    } else {
      subs.forEach(subId => {
        const field = document.createElement("div");
        field.classList.add("input-group", "mb-2");
        let opts = `<option value="" disabled>Select subject</option>`;
        allSubjects.forEach(s => {
          opts += `<option value="${s.subject_id}" ${String(s.subject_id) === String(subId) ? "selected" : ""}>
          ${s.subject_code} – ${s.subject_name}</option>`;
        });
        field.innerHTML = `<select class="form-select" name="subject_ids[]" required>${opts}</select>
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">-</button>`;
        container.appendChild(field);
      });
    }
  }

  function addSubjectField() {
    const container = document.getElementById("subjectContainer");
    const field = document.createElement("div"); field.classList.add("input-group", "mb-2");
    field.innerHTML = `<select class="form-select" name="subject_ids[]" required>
      <option value="" disabled selected>Select subject</option>
      <?php foreach ($subjects as $s): ?>
        <option value="<?= $s['subject_id'] ?>"><?= htmlspecialchars($s['subject_code'] . ' – ' . $s['subject_name']) ?></option>
      <?php endforeach; ?></select>
      <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">-</button>`;
    container.appendChild(field);
  }

  function addEditSubjectField() {
    const container = document.getElementById("editSubjectContainer");
    const field = document.createElement("div");
    field.classList.add("input-group", "mb-2");
    let opts = `<option value="" disabled selected>Select subject</option>`;
    allSubjects.forEach(s => {
      opts += `<option value="${s.subject_id}">${s.subject_code} – ${s.subject_name}</option>`;
    });
    field.innerHTML = `<select class="form-select" name="subject_ids[]" required>${opts}</select>
    <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">-</button>`;
    container.appendChild(field);
  }

  function setDeleteId(id) { document.getElementById('deleteId').value = id; }
</script>


<?php require_once '../../includes/footer-user.php'; ?>