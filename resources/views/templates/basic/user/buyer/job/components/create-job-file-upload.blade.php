<style>

    .drop-area {
        background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='8' ry='8' stroke='%2300C535' stroke-width='4' stroke-dasharray='6%2c 22' stroke-dashoffset='0' stroke-linecap='round'/%3e%3c/svg%3e");
        border-radius: 8px;
        width: 470px;
        height: 230px;
        /* margin: 26px 0; */
        border-radius: 6px;
        /* text-align: center; */
        position: relative;
    }

    .drop-area:hover {
        background-color: #f9f9f9;
    }

    #fileNameDisplay{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .inside-fileNameDisplay{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%)

    }

</style>
<div class="drop-area" id="drop-area">

    <div onclick="document.getElementById('image_job').click()" id="fileNameDisplay"
        class="file-upload-text-center-5432534">
        <div class="inside-fileNameDisplay">Drag & Drop or <span class="green-5435234">browse</span>
            file</div>

    </div>
    <input type="file" name="image" id="image_job"
    accept=".png,.jpg,.jpeg" style="display: none;">
    <script>
        // Get references to the file input and display div
        const fileInput = document.getElementById('image_job');
        const fileNameDisplay = document.getElementById('fileNameDisplay');

        // Add event listener for the file input change event
        fileInput.addEventListener('change', function() {
            // Check if any file is selected
            if (fileInput.files.length > 0) {
                // Display the selected file name in the div
                fileNameDisplay.textContent = fileInput.files[0].name;

                // Create a new FileReader object
                const reader = new FileReader();

                // Define the onload event for the FileReader
                reader.onload = function(e) {
                    // Get the base64 string
                    const base64String = e.target.result;
                    // Set the base64 string as the background image of the div
                    console.log(base64String)
                    fileNameDisplay.style.backgroundImage = `url(${base64String})`;
                    fileNameDisplay.style.backgroundSize = 'cover'; // Optional: Cover the entire div
                    fileNameDisplay.style.backgroundPosition = 'center'; // Optional: Center the image
                    fileNameDisplay.textContent = ''; // Clear the text content
                };

                // Read the image file as a data URL (base64 string)
                reader.readAsDataURL(fileInput.files[0]);

            } else {
                // If no file is selected, display a default message
                // fileNameDisplay.textContent = 'No file selected';
            }
        });
    </script>

</div>
<p style="font-size: 11px; font-weight: 700;">Allowed files
    types: .png,.jpg,.jpeg
</p>
<script>

    document.getElementById('drop-area').ondragover = function(e) {
        e.preventDefault();
    };

    document.getElementById('drop-area').ondrop = function(e) {
        e.preventDefault();
        uploadFiles(e.dataTransfer.files);
    };
</script>
