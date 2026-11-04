"use strict";
// NicEdit Initialization & Character Count
bkLib.onDomLoaded(function () {
    const descriptionId = 'description';
    new nicEditor({ fullPanel: true }).panelInstance(descriptionId);

    setInterval(function () {
        let editor = nicEditors.findEditor(descriptionId);
        if (!editor) return;

        let rawHtml = editor.getContent();
        let plainText = rawHtml.replace(/<[^>]*>/g, '');
        const counter = document.getElementById('descCharCount');
        const counterBox = document.getElementById('descCounterBox');
        const overMsg = document.getElementById('descOverMsg');

        counter.textContent = plainText.length;

        if (plainText.length >= 2000) {
            overMsg.style.display = '';
        } else {
            overMsg.style.display = 'none';
        }
    }, 400);

    document.getElementById('serviceForm').addEventListener('submit', function (e) {
        let editor = nicEditors.findEditor(descriptionId);
        let plainText = editor.getContent().replace(/<[^>]*>/g, '');
        if (plainText.length > 2000) {
            e.preventDefault();
            alert('Service Description cannot exceed 2000 characters!');
        }
    });
});

$(document).on('change', '.toggle-addon', function () {
    let index = $(this).attr('id').split('_')[1];
    let fields = $(`#addon_fields_${index}`);
    if ($(this).is(':checked')) {
        fields.show();
    } else {
        fields.hide();
    }
});

// === MAIN IMAGE UPLOAD HANDLING (with AJAX save) ===
$('#main-image').on('change', function () {
    let file = this.files[0];
    if (!file) return;
    let formData = new FormData();
    formData.append('image', file);

    $.ajax({
        url: imageUpload,
        method: "POST",
        headers: { 'X-CSRF-TOKEN': window.csrfToken },
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            $('#main-img-view').html(`<img src="${res.url}" style="width:100%;height:100%;object-fit:cover;">`);
            $('#main_image_temp').val(res.filename);
            $('#remove-main-img').removeClass('d-none');
        }
    });
});

$('#remove-main-img').on('click', function () {
    $('#main-image').val('');
    $('#main-img-view').html(`<p class="fw-medium m-0">Drag & Drop Image Or <span class="text-primary">Browse</span></p>`);
    $('#remove-main-img').addClass('d-none');

    // Delete from server
    const filename = $('#main_image_temp').val();
    if (filename) {
        $.post(window.routes.tempImageDeleteUrl, { filename, _token: window.csrfToken });
        $('#main_image_temp').val('');
    }
});


function getSkillNameById(id) {
    console.log(skillsMap[id]);
    return skillsMap[id] || '';
}
function generateTierBlock(tierName, addonId) {
    let tierIndex = tierName === 'lite' ? 1 : tierName === 'plus' ? 2 : 3;

    let time = oldFastTierTimes[tierIndex] || '';
    let price = oldFastTierPrices[tierIndex] || '';

    return `<div class="tier-addon-block mb-2 border p-2 rounded">
            <strong class="text-capitalize">${tierName}</strong>
            <input type="hidden" name="fast_tier[${tierIndex}]" value="${addonId}">
            <div class="row mt-1">
                <div class="col-md-6">
                    <label>Delivery Time (in days)</label>
                    <input type="number" name="fast_tier_time[${tierIndex}]" class="form-control" min="0" value="${time}">
                </div>
                <div class="col-md-6">
                    <label>Extra Cost</label>
                    <input type="number" name="fast_tier_price[${tierIndex}]" class="form-control" min="0" value="${price}">
                </div>
            </div>
        </div>`;
}

function removeTempImage(btn, filename) {
        $(btn).parent().remove();
        $.post(window.routes.tempImageDeleteUrl, { filename, _token: window.csrfToken });
    };

// Restore old inputs after validation errors (server-side)
function restoreOldInputs(oldInputs) {
    if (oldInputs.title) $('#tt').val(oldInputs.title).trigger('keyup');
    if (oldInputs.description) nicEditors.findEditor('description').setContent(oldInputs.description);
    if (oldInputs.priority) $('#priority').val(oldInputs.priority);
    if (oldInputs.category) $('#category').val(oldInputs.category).trigger('change');
    if (oldInputs.subcategory) $('#subCategorys').val(oldInputs.subcategory).trigger('change');
    if (oldInputs.childcategory) setTimeout(() => $('#childCategorys').val(oldInputs.childcategory), 500);
};

function getTiers(subcategory, childcategory = null) {
    $.ajax({
        url: window.routes?.searchTiersUrl || "/Search_tiers",
        method: "POST",
        data: {
            _token: window.csrfToken,
            subcategory: subcategory,
            childcategory: childcategory
        },
        success: function (response) {
            if (response !== 0) {
                let result = response;
                console.log(result);

                if (result.status === 'true') {
                    $("#tiers-box").show();
                    $('.filtered-tiers').html(result.content_data).show();
                } else {
                    $("#tiers-box").hide();
                }

                let oldVarsScript = document.getElementById('scope-old-values');
                if (oldVarsScript) {
                    eval(oldVarsScript.innerText); // refresh old tier input vars
                }

                if (typeof restoreTierOldValues === "function") {
                    restoreTierOldValues();
                }

                if (result.tiers_checkbox && result.tiers_checkbox.length > 0) {
                    let checkboxes = '';
                    let isMulti = $('#msval').is(':checked');

                    result.tiers_checkbox.forEach(function (item, index) {
                        let isChecked = oldTiers.includes(item.id.toString());
                        let price = oldPrices[item.id] || '';
                        let delivery = oldDeliveryTimes[item.id] || '';
                        let isFastDelivery = item.name.toLowerCase().includes('fast delivery');
                        let tierSelectHtml = '';

                        if (isFastDelivery) {
                            tierSelectHtml = isMulti
                                ? `<div class="multi-package-block">
                                        ${generateTierBlock('lite', item.id)}
                                        ${generateTierBlock('plus', item.id)}
                                        ${generateTierBlock('ultimate', item.id)}
                                   </div>`
                                : `<div class="single-package-block">
                                        ${generateTierBlock('lite', item.id)}
                                   </div>`;
                        } else {
                            tierSelectHtml = `<div class="row mt-2">
                                <div class="col-md-6">
                                    <label>Delivery Time (in days)</label>
                                    <input type="number" name="addon_delivery_time[${item.id}]" class="form-control" min="0" value="${delivery}">
                                </div>
                                <div class="col-md-6">
                                    <label>Extra Cost</label>
                                    <input type="number" name="addon_price[${item.id}]" class="form-control" min="0" value="${price}">
                                </div>
                            </div>`;
                        }

                        checkboxes += `<div class="form-check mb-3 border p-2 rounded">
                            <input class="form-check-input toggle-addon" type="checkbox" name="extra_tiers[]" value="${item.id}" id="tier_${index}" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label fw-bold" for="tier_${index}">
                                ${item.name}
                            </label>
                            <div class="addon-fields mt-2" id="addon_fields_${index}" style="${isChecked ? 'display:block;' : 'display:none;'}">
                                ${tierSelectHtml}
                            </div>
                        </div>`;
                    });

                    $('.extra_options').html(checkboxes);
                    $('#extra-options-box').show();
                } else {
                    $('.extra_options').empty();
                    $('#extra-options-box').hide();
                }
            }
        },
        error: function () {
            alert('Failed to fetch tiers');
        }
    });
}

function renderSkillsResult() {
    let button_html = '';
    // Old/existing skills
    for (let i = 0; i < skills_arr.length; i++) {
        button_html += `<button type="button" onclick="unselectskill(${skills_arr[i]})" id="skill_btn${skills_arr[i]}" value="${skills_arr[i]}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${skills_name[i]} <i class="fa fa-times ml-2"></i></button>&nbsp;<input type="hidden" name="skills[]" id="job_skills${skills_arr[i]}" value="${skills_arr[i]}"/>`;
    }
    $('#skills_result').html(button_html);
}

function renderExtraSkillsResult() {
    let button_html = '';

    for (let i = 0; i < newskills_arr.length; i++) {
        button_html += `<button type="button" onclick="unselectnewskill(${newskills_arr[i]})" id="newskill_btn${newskills_arr[i]}" value="${newskills_arr[i]}" class="btn gray ft-small apply-btn fs-sm rounded mt-1">${newskills_name[i]} <i class="fa fa-times"></i></button>&nbsp;<input type="hidden" name="newskills[]" id="newjob_skills${newskills_arr[i]}" value="${newskills_name[i]}"/>`;
    }
    $('#skills_result_extra').html(button_html);
}

function getskills(c_id) {
    if (checkload.indexOf(c_id) !== -1) {
        //alert("Value exists!")
        return
    }
    $.ajax({
        type: "GET",
        url: skillsUrl,
        data: {
            c_id: c_id
        },
        success: function (data) {
            var html = '';
            if (data.error) {

                html += 'No Skill  Availble';
            } else {
                $.each(data, function (name, id, skill_category_id) {
                    //alert(category_id);
                    console.log(c_id);
                    html +=
                        `<div class="tab-pane nbv" style="display:inline;" id="tab-'+c_id+'" role="tabpanel">
                            <button type="button" onclick="selectskills(${id},'${name}',${c_id},false)" value="${id}"
                                class="btn gray ft-small apply-btn fs-sm rounded mt-1">${name}<i class="fa fa-plus ml-2"></i>
                            </button>
                        &nbsp</div>`;
                });
            }
            $('#demos').html(html);
            //checkload.push(c_id);
        }
    });
}

function selectskills(s_id, name, c_id, check) {
    if (skills_arr.indexOf(s_id) !== -1) return;
    skills_arr.push(s_id);
    skills_name.push(name);
    renderSkillsResult();
    if (check == true) {
        $('#skil_result').html('');
        $('#search_skill').val('');
    }
}

function selectnewskill(name) {
    if (newskills_name.indexOf(name) !== -1) return;
    newskillcount++;
    newskills_arr.push(newskillcount);
    newskills_name.push(name);
    renderExtraSkillsResult();
    $('#skil_result').html('');
    $('#search_skill').val('');
}

function unselectskill(s_id) {
    var index = skills_arr.indexOf(s_id);
    if (index !== -1) {
        skills_arr.splice(index, 1);
        skills_name.splice(index, 1);
        renderSkillsResult();
    }
}

function unselectnewskill(s_id) {
    var index = newskills_arr.indexOf(s_id);
    if (index !== -1) {
        newskills_arr.splice(index, 1);
        newskills_name.splice(index, 1);
        renderExtraSkillsResult();
    }
}

function getSkillbuname(search) {
    let html = '';
    //console.log(search);
    if (search == "") {
        $('#skil_result').html('');
        return
    }
    $.ajax({
        type: "GET",
        url: skillsByName,
        data: {
            search: search
        },
        success: function (data) {
            var html = '';
            if (data.error) {

                html +=
                    `<b><a onclick="selectnewskill('${search}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${search}</a></b><hr>`;
            } else {
                html += "<div  style='height:100px;overflow:scroll; overflow-x: hidden;'>";
                $.each(data, function (name, id) {
                    html +=
                        `<p><b><a onclick="selectnewskill('${name}')" style="cursor:pointer;font-size:14px; line-height: 0.7;">${name}</a></b></p>`;

                });
                html += '</div>';
                //console.log(data);
            }

            $('#skil_result').html(html);
            // checkload.push(c_id);
        }
    });
}

$(document).on('change', '.tierchildcat', function () {
    var subcategory = $('#subCategorys').val();
    var childcategory = $('#childCategorys').val();
    console.log(subcategory, childcategory);
    if (subcategory && childcategory)
        getTiers(subcategory, childcategory);
});

$(document).ready(function () {
    console.log("old category", oldCategory);
    if (oldCategory) {
        $('#category').val(oldCategory); // Set old category
        loadSubcategories(oldCategory, oldSubcategory, oldChildcategory);
    }
    console.log("old skills");
    console.log(oldSkills.length);

    if (oldSkills && oldSkills.length > 0) {
        oldSkills.forEach(function (skillId) {
            let skillName = getSkillNameById(skillId);
            if (skillName) {
                skills_arr.push(skillId);
                skills_name.push(skillName);
            }
        });
    }

    console.log("old new extra skills");
    console.log(oldNewSkills.length);
    if (oldNewSkills && oldNewSkills.length > 0) {
        oldNewSkills.forEach(function (skillName) {
            newskillcount++;
            newskills_arr.push(newskillcount);
            newskills_name.push(skillName);
        });
    }
    // Now, after both, call render ONCE:
    renderSkillsResult();
    renderExtraSkillsResult();

    // Title Character Count
    $('#tt').on('input keyup', function () {
        $('#ttcount').text(this.value.length);
    });

    // Main Image Handling
    const mainImageInput = $('#main-image');
    const mainImgView = $('#main-img-view');
    const removeMainBtn = $('#remove-main-img');

    mainImageInput.on('change', function () {
        const file = this.files[0];
        if (file && file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = e => {
                mainImgView.html(`<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`);
                removeMainBtn.removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    removeMainBtn.on('click', () => {
        mainImageInput.val('');
        mainImgView.html(`<p class="fw-medium m-0">Drag & Drop Image Or <span class="text-primary">Browse</span></p>`);
        removeMainBtn.addClass('d-none');
    });

    // Additional Images Handling
    $('#more-images').on('change', function () {
        const preview = $('#more-images-preview');
        const maxFiles = 5;

        if ((preview.children().length + this.files.length) > maxFiles) {
            alert('You can upload up to 5 additional images only.');
            $(this).val('');
            return;
        }

        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = $(`
                    <div class="position-relative" style="width:120px;height:120px;">
                        <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">
                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1">&times;</button>
                    </div>`);

                wrapper.find('button').click(() => wrapper.remove());
                preview.append(wrapper);
            };
            reader.readAsDataURL(file);
        });
    });

    // Delete existing optional images (for edit page)
    window.deletedImageIDs = [];
    window.deletecer = function (id) {
        $('#cer-' + id).remove();
        deletedImageIDs.push(id);
        $('#deleted-optional-images').val(JSON.stringify(deletedImageIDs));
    };

    // AJAX Load Subcategories
    $('#category').on('change', function () {
        loadSubcategories($(this).val());
    });

    function loadSubcategories(categoryId, selectedSubcat = null, selectedChildcat = null) {
        $.ajax({
            type: "GET",
            url: url,
            data: { category: categoryId },
            success: function (data) {
                var html = '';
                $("#subCategorys").empty();
                if (data.error) {
                    html += `<option value="" selected disabled>${data.error}</option>`;
                    $(".mySubCatgry").html(html);
                } else {
                    html += `<option value="" selected disabled>Select Category Level 2</option>`;
                    $.each(data, function (index, item) {
                        let selected = selectedSubcat == item.id ? 'selected' : '';
                        html += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                    });
                    $(".mySubCatgry").html(html);
                    console.log(selectedSubcat);
                    if (selectedSubcat) {
                        setTimeout(function () {
                            $('#subCategorys').val(selectedSubcat).trigger('change');
                            getTiers(selectedSubcat, selectedChildcat ?? null);
                        }, 50);
                    }
                }
            }
        });
    }

    // Load Child Categories
    $('#subCategorys').on('change', function () {
        const subcategory = $(this).val();

        // 1. Load Child Categories
        $.get(window.routes.subCategoryUrl, { subcategory }, function (data) {
            let options = '';

            if (!data.error) {
                options += `<option value="" selected disabled>Select Category Level 3</option>`;
                options += data.map(item => `<option value="${item.id}">${item.name}</option>`).join('');
            }

            const select = options
                ? `<select name="childcategory" class="form-select tierchildcat" id="childCategorys">${options}</select>`
                : '';

            $(".myChildCatgry").html(select);

            // 2. If no child categories, fetch tiers & skills directly
            if (!options || data.length === 0) {
                getTiers(subcategory);
            }
        });

        // 3. Load Skills Tabs (Service Features)
        $.ajax({
            type: "GET",
            url: skillURL,
            data: { subcategory },
            success: function (data) {
                let html = '';
                if (data.error) {
                    html += 'No Skill Category Available';
                } else {
                    $.each(data, function (name, id) {
                        html += `<li class="nav-item">
                        <a onclick="getskills(${id})" type="button" class="nav-link addcl" id="nav-link"
                           data-toggle="tab" href="#tab-${id}" role="tab">${name}</a>
                    </li>`;
                    });
                }

                $('#result').html(html);

                setTimeout(function () {
                    $('#result .nav-link').first().trigger('click');
                }, 100);
            }
        });
    });
});
