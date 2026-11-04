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




async function setSubcategoryOfCategory(id){
    const res = await getAjax('/user/seller/skillcategory?subcategory=' + id)
    console.log(res);
}

function unselectskill(s_id) {
    //removing skill from skill array
    var index = skills_arr.indexOf(s_id);
    if (index !== -1) {
        skills_arr.splice(index, 1);
        skills_name.splice(index, 1);
    }
    $("#skill_btn" + s_id).remove();
    $("#job_skills" + s_id).remove();
}

function selectskills(s_id, name, c_id, check) {
    //alert(c_id);
    let button_html = "";
    //check if skill already selected
    if (skills_arr.indexOf(s_id) !== -1) {
        //alert("Value exists!")
        return;
    }
    //initialize note at first
    // button_html+='<p><b>Click to unselect the skill</b></p>';
    skills_arr.push(s_id);
    skills_name.push(name);
    //console.log(skills_arr);
    for (let i = 0; i < skills_arr.length; i++) {
        button_html +=
            '<button type="button" onclick="unselectskill(' +
            skills_arr[i] +
            ')" id="skill_btn' +
            skills_arr[i] +
            '" value="' +
            skills_arr[i] +
            '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' +
            skills_name[i] +
            ' <i class="fa fa-times ml-2"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills' +
            skills_arr[i] +
            '" value="' +
            skills_arr[i] +
            '"/>';
    }
    for (let i = 0; i < newskills_arr.length; i++) {
        button_html +=
            '<button type="button" onclick="unselectnewskill(' +
            newskills_arr[i] +
            ')" id="newskill_btn' +
            newskills_arr[i] +
            '" value="' +
            skills_arr[i] +
            '" class="btn gray ft-small apply-btn fs-sm rounded mt-1">' +
            newskills_name[i] +
            ' <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills' +
            newskills_arr[i] +
            '" value="' +
            newskills_name[i] +
            '"/>';
    }
    $("#skills_result").html(button_html);
    if (check == true) {
        $("#skil_result").html("");
        $("#search_skill").val("");
    }
}

async function getskills(c_id) {

    if (checkload.indexOf(c_id) !== -1) {
        //alert("Value exists!")
        return;
    }

    let url = `/ajax/job/skills-by-category-id/${c_id}`
    const res = await getAjax(url)

    var html = "";
    const data = res;
    $.each(data, function (index, data) {
        html += `<div class="tab-pane nbv " style="display:inline;" id="tab-${c_id}" role="tabpanel"><button type="button" onclick="selectskills(${data.id},'${data.name}',${c_id},false)" value="${data.id}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${data.name}<i class="fa fa-plus ml-2"></i></button>&nbsp</div>`;
    });

    $("#demos").html(html);


}

async function getRelatedSkills(subcategory_id){

    let url = `/ajax/job/subcaegory-skills/${subcategory_id}`
    const res = await getAjax(url)
    console.log(res);

    const data = res;
    var html = "";
    $.each(data, function (index, data) {
        console.log(data.id);
        //alert(name);
        // html+='<div class="card"><div  class="card-header text-white"><button style="color:#28b661;background-color: rgba(40,182,97,0.1);" onclick="getskills('+id+')" type="button" class="btn" data-toggle="collapse" data-target="#demo'+id+'">'+name+' <i class="fa fa-arrow-down"></i></button></div><div id="demo'+id+'" class="card-body collapse"> </div></div>';
        html += `
        <li class="nav-item">
            <a onclick="getskills(${data.id})" type="button" class="nav-link addcl afacad-regular ffs-14" id="nav-link" data-toggle="tab" href="#tab-${data.id}" role="tab">
                ${data.name}
            </a>
        </li>`;
    });

    $("#result").html(html);



}
async function getCurrentCategories(){

    let url = '/ajax/job/caegories'
    const res = await getAjax(url)
    console.log(res);

    $('#category').empty();
    for(var i = 0; i < res.length; i++){
        let item = res[i];
        var option = $('<option></option>')
            .attr('value', item.id)
            .text(item.name);
        $('#category').append(option);

    }

    let subcat = res[0].sub_category;
    $('#subcategorys').empty();
    for(var j = 0; j < subcat.length; j++){
        let sitem = subcat[j];
        var option = $('<option></option>')
            .attr('value', sitem.id)
            .text(sitem.name);
        $('#subcategorys').append(option);
    }

    //getRelatedSkills(subcat[0].id)
}

async function getSubCategories(id){

    let url = '/ajax/job/subcaegories/' + id
    const res = await getAjax(url)
    console.log(res);

    let subcat = res[0].sub_category;
    $('#subcategorys').empty();
    for(var j = 0; j < subcat.length; j++){
        let sitem = subcat[j];
        var option = $('<option></option>')
            .attr('value', sitem.id)
            .text(sitem.name);
        $('#subcategorys').append(option);
    }

    getRelatedSkills(subcat[0].id)

}

getCurrentCategories();

$("#category").on("change", function () {
    $("#result").empty();
    $("#skills_result").empty();
    var categoryId = $(this).val();
    getSubCategories(categoryId)
});


//let button_html = '';
$("#subcategorys").on("change", function () {
    var subcategoryId = $(this).val();
    getRelatedSkills(subcategoryId)
});

//getting existing skill by search
async function getSkillbuname(search) {

    if (search == "") {
        $("#skil_result").html("");
        return;
    }

    let url = `/ajax/job/skills-by-search?search=${search}`
    const res = await getAjax(url)

    var html = "";
    const data = res;

    html +=
        "<div  style='height:100px;overflow:scroll; overflow-x: hidden;'>";
    $.each(data, function (index, data) {
        html += `<p><b><a onclick="selectextraskills(${data.id},'${data.name}',true)" style="cursor:pointer;font-size:14px; line-height: 0.7;">${data.name}</a></b></p>`;
    });
    html += "</div>";
    $("#skil_result").html(html);

}

function selectextraskills(id, name, someBoolean) {
    // Assuming you want to update the input field with the skill name
    $("#search_skill").val(name);

    // Optionally, hide the results dropdown after selection
    $("#skil_result").html("");
}

var maxSkills = 5; // Maximum number of skills allowed

function selectextraskills(id, name, someBoolean) {
    let badgeLength = $("#skills_result_extra").children().toArray().length;
    // Check if the maximum limit has been reached
    if (badgeLength >= maxSkills) {
        alert("You can only select up to " + maxSkills + " skills.");
        return;
    }

    // prevent duplicate
    var textArray = [];

    $("#skills_result_extra")
        .children()
        .each(function () {
            var spanText = $(this).find("span").text().trim();
            textArray.push(spanText);
        });

    console.log(textArray);
    if (textArray.includes(name)) {
        return;
    }

    // Create a badge element
    var badge = $("<div>")
        .addClass("btn gray ft-small apply-btn fs-sm rounded m-2")
        .html("<span>" + name + "</span>");

    // Create a close button
    var closeButton = $("<button>")
        .addClass("close")
        .html("&times;")
        .attr("type", "button");

    // Append the close button to the badge
    badge.append(closeButton);

    // Attach click event listener to the close button to remove the badge
    closeButton.on("click", function (event) {
        event.preventDefault(); // Prevent the default behavior of the button
        badge.remove(); // Remove the badge
    });

    // Append the badge to the desired location
    $("#skills_result_extra").append(badge);

    // If the maximum limit has been reached, remove the oldest badge
    if (badgeLength > maxSkills) {
        $("#skills_result_extra").children().first().remove();
    }

    // Optionally, clear the search input field
    $("#search_skill").val("");
}
