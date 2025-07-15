<?php
require_once 'functions/view.php';
$subjectsGet = getsubjects();
$subjects = $subjectsGet["subjects"];

$departmentsGet = departments();
$departments = $departmentsGet["departments"];

$facultyGet = faculty();
$faculty = $facultyGet["faculty"];
?>

<div class="p-4">
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
          <?php foreach ($faculty as $fuck): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <div class="card text-center shadow h-100 rounded-4" style="background-color: #800000; color: #fff;">
                <div class="card-body d-flex flex-column align-items-center">
                  <img src="../../assets/upload/<?= $fuck["user_profile"] ?? 'helllo?' ?>" alt="Profile"
                    class="rounded-circle mb-2" style="width: 200px; height: 200px; object-fit: cover;">

                  <p class="mb-1 small"><?= $fuck["faculty_id"] ?? 'feeling special ka boi?' ?></p>
                  <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($fuck["fulname"]) ?></h5>
                  <p class="mb-1" style="font-size: 14px;"><?= $fuck["department_name"] ?? "wala" ?></p>
                  <p class="text-white-50 mb-2" style="font-size: 13px;"><?= $fuck["subject_name"] ?? "hehe" ?></p>

                  <div class="d-flex justify-content-center gap-2 mt-auto w-100">
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editJobModal" onclick='editFaculty(
                        <?= (int) $fuck["id"] ?>,
                        <?= json_encode($fuck["faculty_id"]) ?>,
                        <?= json_encode($fuck["fulname"]) ?>,
                        <?= json_encode($fuck["email"]) ?>,
                        <?= json_encode($fuck["position"]) ?>,
                        <?= json_encode($fuck["subject_id"]) ?>,
                        <?= json_encode($fuck["DEPTid"]) ?>,
                        <?= json_encode($fuck["user_profile"] ?? "") ?>
                      )'>
                      Edit
                    </button>

                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewFacultyModal"
                      onclick='viewFaculty(
      <?= json_encode($fuck["faculty_id"]) ?>,
      <?= json_encode($fuck["fulname"]) ?>,
      <?= json_encode($fuck["department_name"] ?? "N/A") ?>,
      <?= json_encode($fuck["email"] ?? "N/A") ?>,
      <?= json_encode($fuck["position"] ?? "N/A") ?>,
      <?= json_encode($fuck["subject_name"] ?? "N/A") ?>,
      <?= json_encode($fuck["user_profile"] ?? "") ?>
   )'>
                      View
                    </a>

                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteJobModal"
                      onclick="setDeleteJobId(<?= $fuck['id'] ?>)">
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- ✅ VIEW FACULTY MODAL -->
      <div class="modal fade" id="viewFacultyModal" tabindex="-1" aria-labelledby="viewFacultyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-center w-100 fs-5 fw-bold" id="viewFacultyModalLabel">Faculty Information</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
              <div class="text-center mb-3">
                <img src="../../assets/profile/users.png" id="viewImagePreview" class="rounded-circle img-thumbnail"
                  style="width:150px; height:150px; object-fit:cover;">
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Faculty ID</label>
                  <input type="text" class="form-control" id="viewFacultyId" disabled>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control" id="viewFullName" disabled>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Department</label>
                  <input type="text" class="form-control" id="viewDepartment" disabled>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="text" class="form-control" id="viewEmail" disabled>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Position</label>
                  <input type="text" class="form-control" id="viewPosition" disabled>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Subject</label>
                  <input type="text" class="form-control" id="viewSubject" disabled>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>


      <!-- ✅ EDIT FACULTY MODAL -->
      <div class="modal fade" id="editJobModal" tabindex="-1" aria-labelledby="editJobModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <form method="POST" action="functions/auth.php" enctype="multipart/form-data">
              <input type="hidden" name="EditFaculty" value="true">
              <input type="hidden" name="editJobId" id="editJobId">

              <div class="modal-header">
                <h5 class="modal-title text-center w-100 fs-5 fw-bold">Edit Faculty Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>

              <div class="modal-body">
                <div class="text-center mb-3">
                  <label for="imageID">
                    <img src="../../assets/profile/users.png" id="editImagePreview" class="rounded-circle img-thumbnail"
                      style="width: 150px; height:150px; object-fit:cover;">
                  </label>
                  <input type="file" id="imageID" name="profile" accept="image/*" class="d-none"
                    onchange="previewEditImage(event)">

                </div>

                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Faculty ID</label>
                    <input class="form-control" id="faculty_id" name="faculty_id" maxlength="10">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input class="form-control" id="fulname" name="fulname">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Department</label>
                    <select class="form-select" id="departmentSelect" name="department">
                      <option value="" disabled selected>Select department</option>
                      <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['department_id'] ?>">
                          <?= htmlspecialchars($d['department_name'] . ' – ' . $d['department_code']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Position</label>
                    <input class="form-control" id="position" name="position">
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">Subject</label>
                    <select class="form-select" id="subjectSelect" name="subject_id">
                      <option value="" disabled selected>Select subject</option>
                      <?php foreach ($subjects as $s): ?>
                        <option value="<?= $s['subject_id'] ?>">
                          <?= htmlspecialchars($s['subject_code'] . ' – ' . $s['subject_name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- ✅ DELETE MODAL -->
      <div class="modal fade" id="deleteJobModal" tabindex="-1" aria-labelledby="deleteJobModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="functions/auth.php" class="modal-content">
            <input type="hidden" name="deleteFaculty" value="true">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title" id="deleteJobModalLabel">Confirm Deletion</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete this faculty member?
              <input type="hidden" name="faculty_id" id="deleteJobId">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>

  <div>
    <!-- ==================== ADD MODAL ===================== -->
    <div class="modal fade" id="addFacultyModal" tabindex="-1" aria-labelledby="addFacultyModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <form action="functions/auth.php" method="post" enctype="multipart/form-data" class="d-flex flex-column">
            <input type="hidden" name="addFaculty" value="true">
            <div class="modal-header">
              <h5 class="modal-title" id="addFacultyModalLabel">Add Faculty</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body d-flex flex-column">
              <div class="g-3 d-flex flex-column">

                <div class="col-md-4 text-center w-100 mb-3">
                  <label for="profile"><img src="../../assets/profile/users.png" class="img-thumbnail mb-2"
                      id="profilePreview" alt="Profile preview"
                      style="width: 200px; height: auto; border-radius: 50%;"></label>

                  <input class="form-control" type="file" name="profile" id="profile" accept="image/*"
                    onchange="previewImage(event)">
                </div>
                <div class="col-md-12">
                  <div class="row g-3  d-flex flex-column">
                    <div class="d-flex flex-row col-md-12 gap-1 justify-content-between">
                      <div class="col-md-6">
                        <label class="form-label" for="faculty_id">Faculty ID</label>
                        <input type="text" class="form-control" id="faculty_id" name="faculty_id" maxlength="10"
                          required>
                      </div>

                      <div class="col-md-6">
                        <label class="form-label" for="fullname">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                      </div>
                    </div>

                    <div class="d-flex flex-row col-md-12 gap-1">
                      <div class="col-md-6">
                        <label class="form-label" for="departmentSelect">Select Subject</label>
                        <select class="form-select" name="department" id="departmentSelect" required>
                          <option value="" disabled selected>Select a subject</option>
                          <?php foreach ($departments as $s): ?>
                            <option value="<?= htmlspecialchars($s['department_id']) ?>">
                              <?= htmlspecialchars($s['department_name'] . ' – ' . $s['department_code']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                      <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                      </div>
                    </div>

                    <div class="d-flex flex-row justify-content-between gap-1">
                      <div class="col-md-6">
                        <label class="form-label" for="position">Position</label>
                        <input type="text" class="form-control" id="position" name="position" required>
                      </div>

                      <div class="col-md-6">
                        <label class="form-label" for="subjectSelect">Select Subject</label>
                        <select class="form-select" name="subject_id" id="subjectSelect" required>
                          <option value="" disabled selected>Select a subject</option>
                          <?php foreach ($subjects as $s): ?>
                            <option value="<?= htmlspecialchars($s['subject_id']) ?>">
                              <?= htmlspecialchars($s['subject_code'] . ' – ' . $s['subject_name']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Faculty</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>