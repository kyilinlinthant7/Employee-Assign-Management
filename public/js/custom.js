// documentation file input
function handleFiles(event) {
    var files = event.target.files;
    var filePreviewContainer = document.getElementById(
        "file-preview-container"
    );
    var chosenFilesCount = document.getElementById("chosen-files-count");

    // clear existing file previews
    filePreviewContainer.innerHTML = "";

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var reader = new FileReader();

        reader.onload = function(e) {
            var filePreview = document.createElement("div");
            filePreview.className = "file-preview";

            var removeButton = document.createElement("button");
            removeButton.className = "remove-button btn btn-sm btn-secondary";
            removeButton.textContent = "Remove";
            removeButton.addEventListener("click", function() {
                filePreview.remove(); // remove the preview div
                updateFileCount();
            });

            var filePreviewName = document.createElement("span");
            filePreviewName.textContent = file.name;

            filePreview.appendChild(filePreviewName);
            filePreview.appendChild(removeButton);
            filePreviewContainer.appendChild(filePreview);

            updateFileCount();
        };

        reader.readAsDataURL(file);
    }

    chosenFilesCount.textContent = files.length.toString(); // update the chosen files count
}

// function to update the file count
function updateFileCount() {
    var fileCount = document.querySelectorAll(".file-preview").length;
    var chosenFilesCount = document.getElementById("chosen-files-count");
    chosenFilesCount.textContent = fileCount.toString() + "   files";
    if (chosenFilesCount.textContent === "1   files") {
        chosenFilesCount.textContent = "1   file";
    } else if (chosenFilesCount.textContent === "0   files") {
        chosenFilesCount.textContent = "No file chosen";
    }
}

// language change box
function redirectToRoute(route) {
    if (route) {
        window.location.href = route;
    }
}

// image show by chosen image
function loadPhoto(event) {
    var e = event.target;
    var file = e.files[0];
    var reader = new FileReader();

    reader.onload = function (event) {
        var image = document.createElement("img");
        image.src = event.target.result;
        image.style.width = "108px";
        image.style.height = "108px";
        image.style.position = "relative";
        image.style.top = "-39px";
        image.style.right = "-1px";
        image.style.objectFit = "cover";
        image.style.borderRadius = "50%";
        image.setAttribute("data-image", file.name);
        console.log(image.getAttribute("data-image"));
        var preview = document.getElementById("preview");
        preview.innerHTML = "";
        preview.appendChild(image);

        var removeButton = document.getElementById("remove-button");
        removeButton.style.display = "block";
        removeButton.addEventListener("click", function () {
            image.src = "";
            e.value = "";
            removeButton.style.display = "none";
        });
    };
    reader.readAsDataURL(file);
}

// edit form
// for non-profile condition in edit form
function loadPhotoEditNone(event) 
{
    var e = event.target;
    var file = e.files[0];
    var reader = new FileReader();

    reader.onload = function (event) {
        var image = document.createElement("img");
        image.src = event.target.result;
        image.style.width = "108px";
        image.style.height = "108px";
        image.style.position = "absolute";
        image.style.top = "20px";
        image.style.right = "16px";
        image.style.objectFit = "cover";
        image.style.borderRadius = "50%";
        image.setAttribute("data-image", file.name);
        console.log(image.getAttribute("data-image"));
        var preview = document.getElementById("preview-edit-none");

        var existingImage = preview.querySelector("img");
        if (existingImage) {
            preview.replaceChild(image, existingImage);
        } else {
            preview.appendChild(image);
        }
        var removeButton = document.getElementById("remove-button-edit-none");
        removeButton.style.display = "block";
        removeButton.addEventListener("click", function () {
            image.src = "/images/default-profile.jpg";
            e.value = "";
            image.style.width = "150px";
            image.style.height = "150px";
            image.style.left = "2px";
            image.style.top = "-2px";
            removeButton.style.display = "none";
        });
    };

    reader.readAsDataURL(file);
}

// for existing profile condition in edit form
function loadPhotoEditExists(event) 
{
    var nextArea = document.getElementById("nextArea");
    nextArea.style.marginTop = "-12px";
    var removeButtonBefore = document.getElementById("remove-button-before");
    removeButtonBefore.style.display = "none";

    var e = event.target;
    var file = e.files[0];
    var reader = new FileReader();

    reader.onload = function (event) {
        var image = document.createElement("img");
        image.src = event.target.result;
        image.style.width = "108px";
        image.style.height = "108px";
        image.style.top = "-20px";
        image.style.left = "25px";
        image.style.objectFit = "cover";
        image.style.borderRadius = "50%";
        image.setAttribute("data-image", file.name);
        console.log(image.getAttribute("data-image"));
        var preview = document.getElementById("preview-edit-exists");

        var existingImage = preview.querySelector("img");
        if (existingImage) {
            preview.replaceChild(image, existingImage);
        } else {
            preview.appendChild(image);
        }

        var removeButton = document.getElementById("remove-button-edit-exists");
        removeButton.style.display = "block";
        removeButton.addEventListener("click", function () {
            image.src = "/images/default-profile.jpg";
            image.style.width = "150px";
            image.style.height = "150px";
            image.style.top = "-40px";
            image.style.left = "4px";
            e.value = "";
            removeButton.style.display = "none";
            var nextArea = document.getElementById("nextArea");
            nextArea.style.marginTop = "-50px";
        });
    };

    reader.readAsDataURL(file);
}

// remove the existing image from file and db
document.addEventListener('DOMContentLoaded', function()
{
    var removeButtonBefore = document.getElementById('remove-button-before');
    if (removeButtonBefore) {
      removeButtonBefore.addEventListener('click', function() {
        removeImageBefore();
      });
    }
});  
  
function removeImageBefore() 
{
    var fileInput = document.getElementById('file-upload-edit');
    fileInput.value = null;
  
    var previewElement = document.getElementById('preview-edit-exists');
    previewElement.innerHTML = '';
  
    document.getElementById('remove-button-before').style.display = 'none';
  
    var defaultImage = document.createElement('img');
    defaultImage.src = '/images/default-profile.jpg';
    defaultImage.style.width = '150px';
    defaultImage.style.height = '150px';
    defaultImage.style.top = "-40px";
    defaultImage.style.left = "4px";
    defaultImage.style.objectFit = "cover";
    previewElement.appendChild(defaultImage);
    var nextArea = document.getElementById("nextArea");
    nextArea.style.marginTop = "-50px";
  
    var imageInput = document.getElementById('file-upload-edit');
    imageInput.value == '';
    if (imageInput == '') {
        document.getElementById('remove-button-edit-exists').style.display = 'none';
    }
}  


