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