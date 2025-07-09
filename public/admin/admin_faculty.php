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
        <div class="card-body">
            <h5 class="card-title">Faculty List</h5>
            <div class="flex-box d-flex flex-flex col-md-6 h-auto p-3">
                <?php foreach($faculty as $fuck) : ?>
                    <div class="profile w-100 d-flex flex-column justify-content-center align-items-center m-0 p-0">
                        <img src="../../assets/upload/<?= $fuck["user_profile"]  ?? 'helllo?'?>" alt="" style="width: 100px; height: 100px; border-radius: 50%;">
                        <label for=""><?= $fuck["faculty_id"] ?? 'feeling special ka boi?' ?></label>
                        <label for=""><?= $fuck["fulname"] ?></label>
                        <label for=""><?= $fuck["subject_name"] ?? "hehe" ?></label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <div>
         <div class="modal fade" id="addFacultyModal" tabindex="-1"
            aria-labelledby="addFacultyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <form action="functions/auth.php" method="post" enctype="multipart/form-data" class="d-flex flex-column">
                <input type="hidden" name="addFaculty" value="true">
                <div class="modal-header">
                <h5 class="modal-title" id="addFacultyModalLabel">Add Faculty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body d-flex flex-column">
                <div class="g-3 d-flex flex-column" >

                <div class="col-md-4 text-center w-100 mb-3">
                    <label for="imageID"><img src="../../assets/profile/users.png" class="img-thumbnail mb-2" id="profilePreview" alt="Profile preview" style="width: 200px; height: auto; border-radius: 50%;"></label>
                    
                    <input class="form-control" type="file" name="profile" id="imageID"
                            accept="image/*" style="display: none;" onchange="previewImage(event)">
                </div>
                    <div class="col-md-12">
                    <div class="row g-3  d-flex flex-column">
                        <div class="d-flex flex-row col-md-12 gap-1 justify-content-between">
                            <div class="col-md-6">
                                <label class="form-label" for="faculty_id">Faculty ID</label>
                                <input type="text" class="form-control" id="faculty_id"
                                        name="faculty_id" maxlength="10" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="fullname">Full Name</label>
                                <input type="text" class="form-control" id="fullname"
                                        name="fullname" required>
                            </div>
                        </div>

                        <div class="d-flex flex-row col-md-12 gap-1">
                            <div class="col-md-6">
                        <label class="form-label" for="departmentSelect">Select Subject</label>
                            <select class="form-select" name="department" id="departmentSelect" required>
                                <option value="" disabled selected>Select a subject</option>
                                <?php foreach ($departments as $s): ?>
                                <option value="<?= htmlspecialchars($s['department_id']) ?>">
                                    <?= htmlspecialchars($s['department_name'].' – '.$s['department_code']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email"
                                name="email" required>
                        </div>
                        </div>

                        <div class="d-flex flex-row justify-content-between gap-1">
                            <div class="col-md-6">
                        <label class="form-label" for="position">Position</label>
                        <input type="text" class="form-control" id="position"
                                name="position" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="subjectSelect">Select Subject</label>
                            <select class="form-select" name="subject_id" id="subjectSelect" required>
                                <option value="" disabled selected>Select a subject</option>
                                <?php foreach ($subjects as $s): ?>
                                <option value="<?= htmlspecialchars($s['subject_id']) ?>">
                                    <?= htmlspecialchars($s['subject_code'].' – '.$s['subject_name']) ?>
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
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Faculty</button>
                </div>
            </form>
            </div>
        </div>
        </div>
       <button class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#addFacultyModal">
        <i class="bi bi-plus-circle"></i> Add Faculty
    </button>
    </div>
</div>