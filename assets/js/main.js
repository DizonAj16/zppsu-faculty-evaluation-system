function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                console.log("Image Loaded: ", e.target.result);
                document.getElementById("profilePreview").src = e.target.result;
            };
            reader.readAsDataURL(file);
            console.log("below is the data");
            console.log(file);
        } else {
            console.log("No file selected");
        }
}
function setDeleteJobId(id) {
    document.getElementById('deleteJobId').value = id;
}
function editFaculty(id, facultyId, fullName, email, position,
                     subjectId, deptId, avatarFile) {

    // 1. shove the primary key into hidden input for POST
    document.getElementById('editJobId').value = id;
                        console.log("sdaasdasdasdasd");
    // 2. populate plain inputs
    document.getElementById('faculty_id').value = facultyId;
    document.getElementById('fulname').value    = fullName;
    document.getElementById('email').value      = email;
    document.getElementById('position').value   = position;

    // 3. select boxes
    document.getElementById('subjectSelect').value    = String(subjectId);
    document.getElementById('departmentSelect').value = String(deptId);

    // 4. avatar preview (optional)
    const preview = document.getElementById('editImagePreview');
    preview.src = avatarFile
        ? '../../assets/upload/' + avatarFile   // adjust path as needed
        : '../../assets/profile/users.png';     // default placeholder

    // 5. finally show the modal
    bootstrap.Modal.getOrCreateInstance(
        document.getElementById('editJobModal')
    ).show();
}