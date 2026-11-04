function showError(key, error) {
    $("#create-job-form-5432346").find(key).show().text(error);
    setTimeout(() => {
        $("#create-job-form-5432346").find(key).hide();
    }, 5000);
}

async function validateCreateJobForm() {
    return new Promise((resolve) => {
        const formdata = $("#create-job-form-5432346").serializeArray();
        console.log(formdata);
        var valid = true;

        const title = formdata.find((x) => x.name == "title").value;

        if (!title) {
            showError("#job_title_error", "Title is required");
            valid = false;
        }

        const description = formdata.find((x) => x.name == "description").value;

        if (!description) {
            showError("#description_error", "description is required");
            valid = false;
        }

        const priority = formdata.find((x) => x.name == "priority").value;

        if (!priority) {
            showError("#priority_error", "priority is required");
            valid = false;
        }

        const requirement = formdata.find((x) => x.name == "requirement").value;

        if (!requirement) {
            showError("#requirement_error", "requirement is required");
            valid = false;
        }

        const category = formdata.find((x) => x.name == "category").value;

        if (!category) {
            showError("#category_error", "category is required");
            valid = false;
        }

        const subcategory = formdata.find((x) => x.name == "subcategory").value;

        if (!subcategory) {
            showError("#subcategory_error", "subcategory is required");
            valid = false;
        }

        const skills_count = skills_arr.length;
        if (skills_count == 0) {
            showError("#skills_error", "Skills are required");
            valid = false;
        }

        // const keyword = formdata.find((x) => x.name == "keyword").value;

        // if (!keyword) {
        //     showError("#keyword_error", "keyword is required");
        //     valid = false;
        // }

        const delivery = formdata.find((x) => x.name == "delivery").value;

        if (!delivery) {
            showError("#delivery_error", "delivery is required");
            valid = false;
        }

        const budgetType = formdata.find((x) => x.name == "budgettype").value;

        const amount = formdata.find((x) => x.name == "amount").value;

        if (!amount && budgetType == "fixed") {
            showError("#amount_error", "amount is required");
            valid = false;
        }

        const minBudget = formdata.find((x) => x.name == "minBudget").value;

        if (!minBudget && budgetType == "per_hour") {
            showError("#minBudget_error", "minBudget is required");
            valid = false;
        }

        const maxBudget = formdata.find((x) => x.name == "maxBudget").value;

        if (!maxBudget && budgetType == "per_hour") {
            showError("#maxBudget_error", "maxBudget is required");
            valid = false;
        }

        if(budgetType == "per_hour"){

            const intmaxBudget = parseInt(maxBudget);
            const intminBudget = parseInt(minBudget);
            if (intmaxBudget < intminBudget) {
                showError("#minBudget_error", "minBudget should be lower");
                showError("#maxBudget_error", "maxBudget should be higher");
                valid = false;
            }

        }




        const exp = formdata.find((x) => x.name == "exp").value;

        if (!exp) {
            showError("#exp_error", "Experince is required");
            valid = false;
        }

        // const file = formdata.find((x) => x.name == "file").value;

        // if (!file) {
        //     showError("#file_error", "file is required");
        //     valid = false;
        // }

        resolve(valid);
    });
}

$("#create-job-submit-btn-678956").on("click", async function () {

    const valid = await validateCreateJobForm();

    console.log(valid);
    if (!valid) {
        return;
    }

    const formdata = $("#create-job-form-5432346").serializeArray();
    console.log(formdata);
    $("#create-job-form-5432346").submit();
    // form serialize array
    // console.log(?)
});


document.getElementById('upload-icon').onclick = function () {
    document.getElementById('attachment-files').click();
};

document.getElementById('attachment-files').addEventListener('change', function () {
    const fileList = this.files;
    const selectedFilesList = document.getElementById('selected-files-list');
    selectedFilesList.innerHTML = '';

    if (fileList.length > 5) {
        alert('You can only upload a maximum of 5 files.');
        this.value = '';
        return;
    }

    Array.from(fileList).forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.innerHTML = `
            <span>${file.name}</span>
            <button type="button" class="remove-file" data-index="${index}">
                    <i class="fas fa-times"></i>
                </button>
        `;
        selectedFilesList.appendChild(fileItem);
    });

    const removeButtons = document.querySelectorAll('.remove-file');
    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const index = this.getAttribute('data-index');
            removeFile(index);
        });
    });
});

function removeFile(index) {
    const fileInput = document.getElementById('attachment-files');
    const dataTransfer = new DataTransfer();

    Array.from(fileInput.files).forEach((file, i) => {
        if (i != index) {
            dataTransfer.items.add(file);
        }
    });

    fileInput.files = dataTransfer.files;

    const selectedFilesList = document.getElementById('selected-files-list');
    selectedFilesList.innerHTML = ''; // Clear previous selection

    Array.from(fileInput.files).forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.innerHTML = `
            <span>${file.name}</span>
            <button type="button" class="remove-file" data-index="${index}">Remove</button>
        `;
        selectedFilesList.appendChild(fileItem);
    });

    const removeButtons = document.querySelectorAll('.remove-file');
    removeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const index = this.getAttribute('data-index');
            removeFile(index);
        });
    });
}
