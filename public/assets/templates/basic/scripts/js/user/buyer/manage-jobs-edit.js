$(document).ready(function () {
    handleBudgetFields();
    let today = new Date().toISOString().split('T')[0];

    function updateFieldsVisibility() {
        if ($('#fixedType').is(':checked')) {
            $('#dueDateField').show(); // Show due date field for fixed jobs
            $('#weeklyHoursField').hide(); // Hide weekly hours field for fixed jobs
            $('#dueDate').prop('disabled', false);
            $('#dueDate').attr('min', today); // Set today's date as the minimum selectable date
        } else if ($('#hourlyType').is(':checked')) {
            $('#dueDateField').hide(); // Hide due date field for hourly jobs
            $('#weeklyHoursField').show(); // Show weekly hours field for hourly jobs
            $('#dueDate').prop('disabled', true);
            $('#dueDate').removeAttr('min');
        }
    }
    $('input[name="job_type"]').on('change', updateFieldsVisibility);
    updateFieldsVisibility();

    // Show/hide budget fields based on "Not Specified" checkbox
    $('#noBudgetCheck').on('change', function () {
        handleBudgetFields();
    });

    // Load categories on page load
    $('#categorySelect').on('change', function () {
        let categoryId = $(this).val();
        getSubCategories(categoryId).then(function () {
            $('#features_section').fadeOut(); // Hide features section smoothly while loading subcategories
        });
    });

    $('#subcategorySelect').on('change', function () {
        let subcategoryId = $(this).val();
        getRelatedSkills(subcategoryId).then(function () {
            $('#features_section').fadeIn(); // Show features section smoothly after loading skills
        });
    });
});

function handleBudgetFields() {
    // Check if the checkbox is checked
    if ($('#noBudgetCheck').is(':checked')) {
        $('#budgetFields').hide(); // Hide budget fields if "Not Specified" is checked
    } else {
        $('#budgetFields').show(); // Show budget fields if "Not Specified" is unchecked
    }
}
// Include all the provided JavaScript functions for handling categories, subcategories, and skills
const checkload = [];
const skills_arr = [];
const skills_name = [];
const newskills_arr = [];
const newskills_name = [];
const exskills_arr = [];
const exskills_name = [];
const newexskills_arr = [];
const newexskills_name = [];
let newskillcount = 0;
let newexskillcount = 0;

async function setSubcategoryOfCategory(id) {
    const res = await getAjax('/user/seller/skillcategory?subcategory=' + id)
    console.log(res);
}

function unselectskill(s_id) {
    const index = skills_arr.indexOf(s_id);
    if (index > -1) {
        skills_arr.splice(index, 1);
        skills_name.splice(index, 1);
    }
    selectskills(); // Update the display
}

function selectskills(s_id, name, c_id, check) {
    // If no skill ID is provided, just update the display
    if (s_id === undefined) {
        updateSkillsDisplay();
        return;
    }

    // Prevent adding duplicate skills
    if (skills_arr.indexOf(s_id) !== -1) {
        return;
    }

    // Add skill ID and name to arrays
    skills_arr.push(s_id);
    skills_name.push(name);

    // Update the HTML with the new set of skills
    updateSkillsDisplay();

    // Clear the search result if 'check' is true
    if (check === true) {
        $("#skil_result").html("");
        $("#search_skill").val("");
    }
}

function updateSkillsDisplay() {
    let button_html = "";
    console.log(skills_arr);
    // Create buttons for each skill
    for (let i = 0; i < skills_arr.length; i++) {
        button_html += `
            <button type="button" onclick="unselectskill(${skills_arr[i]})" id="skill_btn${skills_arr[i]}" value="${skills_arr[i]}" class="selected-feature-item">
                ${skills_name[i]} <i class="fa fa-times ml-2"></i>
            </button>&nbsp;
            <input type="hidden" name="skills[${i}][id]" value="${skills_arr[i]}"/>
            <input type="hidden" name="skills[${i}][name]" value="${skills_name[i]}"/>
        `;
    }

    // Update the HTML with the generated buttons
    $("#skills_result").html(button_html);
}

async function getskills(c_id) {
    let url = `/ajax/job/skills-by-category-id/${c_id}`;
    const res = await getAjax(url);
    const data = res;
    let html = "";
    // Clear the current tab content before loading new skills
    $(".tab-pane").html("");
    // If no data is returned, check and handle that case
    if (data.length === 0) {
        $(`#tab-${c_id}`).html('<p>No skills available</p>');
        return; // Exit the function if no data
    }
    $.each(data, function (index, skillData) {
        html += `
        <button type="button" onclick="selectskills(${skillData.id}, '${skillData.name}', ${c_id}, false)" value="${skillData.id}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">
        <span>${skillData.name}</span><span class="plus-symbol">+</span></button>`;
    });
    // Update the content of the currently active tab
    $(`#tab-${c_id}`).html(html);
}


async function getRelatedSkills(subcategory_id) {
    let url = `/ajax/job/subcaegory-skills/${subcategory_id}`;
    const res = await getAjax(url);
    const data = res;
    let tabHtml = "";
    let contentHtml = "";

    $.each(data, function (index, skillData) {
        // Add 'active' class to the first tab and tab content
        let activeClass = index === 0 ? 'active' : '';

        tabHtml += `
<li class="nav-item">
    <a onclick="getskills(${skillData.id})" type="button" class="nav-link ${activeClass} addcl afacad-regular ffs-14" id="nav-link-${skillData.id}"
    data-toggle="tab" href="#tab-${skillData.id}" role="tab">
        ${skillData.name}
    </a>
</li>`;

        contentHtml += `
<div class="tab-pane ${activeClass}" id="tab-${skillData.id}" role="tabpanel">
    <!-- Dynamic skills content for ${skillData.name} will be loaded here -->
</div>`;
    });

    $("#result").html(tabHtml); // Update the tab list
    console.log(contentHtml);
    $("#demos").html(contentHtml); // Update the tab content area

    // Automatically trigger the first tab to load its skills if available
    if (data.length > 0) {
        // Trigger the click event to load skills for the first tab
        $('a[href="#tab-' + data[0].id + '"]').trigger('click');
    }
}

async function getCurrentCategories() {
    let url = '/ajax/job/caegories';
    const res = await getAjax(url);
    $('#categorySelect').empty().append('<option selected disabled>Choose...</option>');

    for (var i = 0; i < res.length; i++) {
        let item = res[i];
        var option = $('<option></option>')
            .attr('value', item.id)
            .text(item.name);
        $('#categorySelect').append(option);
    }
}

async function getSubCategories(id, oldSubcategory) {
    let url = '/ajax/job/subcaegories/' + id;
    const res = await getAjax(url);

    let subcat = res[0].sub_category;
    $('#subcategorySelect').empty().append('<option selected disabled>Choose...</option>');
    for (var j = 0; j < subcat.length; j++) {
        let sitem = subcat[j];
        var option = $('<option></option>')
            .attr('value', sitem.id)
            .text(sitem.name);
        $('#subcategorySelect').append(option);
        if (sitem.id == oldSubcategory) {
            option.prop('selected', true);
        }
    }
    getRelatedSkills(subcat[0].id).then(function () {
        if (oldSkills) {
            $('#features_section').fadeIn(); // Show features section smoothly after loading skills

            if (Array.isArray(oldSkills) && oldSkills.length > 0) {
                // Populate skills_arr and skills_name with old values
                oldSkills.forEach(function (skill, index) {
                    skills_arr.push(skill.id);
                    skills_name.push(skill.name);
                });

                // Call updateSkillsDisplay to render the old skills
                updateSkillsDisplay();
            }
        }
    });
}

async function getSkillbuname(search) {
    if (search == "") {
        $("#skil_result").html("").hide(); // Clear and hide the results if the search is empty
        return;
    }
    let url = `/ajax/job/skills-by-search?search=${search}`;
    const res = await getAjax(url);
    var html = "<div style='height:100px;overflow:scroll; overflow-x: hidden;'>";
    $.each(res, function (index, data) {
        html += `<p><b><a onclick="selectextraskills(${data.id},'${data.name}',true,this)"
style="cursor:pointer;font-size:14px; line-height: 0.7;">${data.name}</a></b></p>`;
    });
    html += "</div>";
    $("#skil_result").html(html).show(); // Update the content and show the results
}

function removeSkill(id) {
    $(`#badge_${id}`).remove();
}

function removeExtraSkill(id) {
    $.ajax({
        type: "GET",
        url: `/user/buyer/delete/extra/skill/${id}`,
        success: function (response) {
            $(`#badge_${id}`).remove();

            console.log("response =>",response);
        }
    });
}


function selectextraskills(id, name, someBoolean, element = null) {
    const formId = $("#edit-job-form-5432346").attr("id");

    if (!formId) {
        console.error("Form ID not found.");
        return;
    }


    let badgeLength = $("#skills_result_extra").children().toArray().length;
    if (badgeLength >= 5) {
        $("#error").removeClass("d-none");
        $("#error-message").text("You can only select up to 5 skills.");

        // Optionally, hide after 3 seconds
        setTimeout(() => {
            $("#error").addClass("d-none");
        }, 3000);

        return;
    }

    // Check if the skill is already selected
    var textArray = [];
    $("#skills_result_extra").children().each(function () {
        var spanText = $(this).find("span").text().trim();
        textArray.push(spanText);
    });

    if (textArray.includes(name)) {
        return;
    }

    // Create the skill badge with a remove button
    var badge = $("<div>").addClass("btn btn-secondary btn-sm m-1")
        .html("<span>" + name + "</span>");

    var closeButton = $("<button>").addClass("close").html("&times;")
        .attr("type", "button");

    badge.append(closeButton);

    // Add event listener to remove the badge when clicked
    closeButton.on("click", function (event) {
        event.preventDefault();
        badge.remove();
        $("#extra_skill_" + id).remove();
    });

    // Append the new skill to the selected skills section
    $("#skills_result_extra").append(badge);


    // Clear the search input
    $("#search_skill").val("");

    // Clear and hide the search results after selection
    $("#skil_result").html("").hide();

    if (formId) {
        $(`#${formId}`).append(
            `<input type="hidden" name="extra_skills[]" id="extra_skill_${id}" value='${JSON.stringify({ id: id, name: name })}'>`
        );
    } else {
        $("form").first().append(
            `<input type="hidden" name="extra_skills[]" id="extra_skill_${id}" value='${JSON.stringify({ id: id, name: name })}'>`
        );
    }
    
}
