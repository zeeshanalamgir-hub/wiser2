$(document).on("click", "#fixedTypeDispute", function () {
    var actionUrl = $(this).data("url");
    $("#fixedTypeDisputeForm").attr("action", actionUrl);
    var disputeAmount = $(this).data("dispute-amount"); // Fetch the dispute amount
    if(disputeAmount){
        $('#disputeAmount').text('$' + disputeAmount.toFixed(2));
    }

});

$(document).on('change', '#start_time, #end_time', calculateDuration);

const startTimeInput = document.getElementById('start_time');
const endTimeInput = document.getElementById('end_time');
const totalHoursInput = document.getElementById('total_hours');
const totalMinutesInput = document.getElementById('total_minutes');

function calculateDuration() {
    const startTime = startTimeInput.value;
    const endTime = endTimeInput.value;

    if (startTime && endTime) {
        const start = new Date(`1970-01-01T${startTime}:00`);
        const end = new Date(`1970-01-01T${endTime}:00`);

        let duration = end - start;
        if (duration < 0) {
            duration += 24 * 60 * 60 * 1000; // Handle time crossing midnight
        }

        const hours = Math.floor(duration / (1000 * 60 * 60));
        const minutes = Math.round((duration % (1000 * 60 * 60)) / (1000 * 60));

        totalHoursInput.value = hours;
        totalMinutesInput.value = minutes;
    } else {
        totalHoursInput.value = '';
        totalMinutesInput.value = '';
    }
}

$(document).on('change', '#start', function () {
    const selectedDate = this.value;
    const timesheetUrl = `${this.dataset.url}?date=${selectedDate}`;
    loadAjaxContent(timesheetUrl, 'Timesheet');
});

$(document).on("submit", "#fixedTypeDisputeForm", function (e) {
    e.preventDefault();
    $(".error-message").remove();
    var formData = new FormData(this);
    var actionUrl = $(this).attr("action");

    $.ajax({
        url: actionUrl,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if(response.status) {
                $('#success-alert').removeClass('d-none');
                $('#modalDispute').modal('hide');
                const timesheetUrl = $("a.custom-ajax-link:contains('Milestone')").data('url');

                loadAjaxContent(timesheetUrl, 'Milestone');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
            // alert("Dispute submitted successfully!");
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function (field, messages) {
                    var fieldElement = $("#" + field);
                    fieldElement.after('<div class="error-message text-danger">' + messages[0] + '</div>');
                });
            } else {
                alert("An unexpected error occurred. Please try again.");
            }
        }
    });
});


function setLikelyRating(value, element) {
    // Set the hidden input value
    document.getElementById('likely_rating').value = value;

    // Reset the background color of all buttons
    document.querySelectorAll('.rating-btn').forEach(function(btn) {
        btn.style.backgroundColor = ''; // Reset background
        btn.style.color = ''; // Reset text color
    });

    // Highlight the selected button
    element.style.backgroundColor = 'black';
    element.style.color = 'white'; // Set text color to white for contrast
}

$(document).ready(function() {
    function initializeDatepicker() {
        $('.datepicker').datetimepicker({
            format: 'Y-m-d',
            minDate: 0
        });
    }

    const ratings = { skills: 0, quality: 0, availability: 0, communication: 0, cooperation: 0 };
    const requestPaymentButtons = document.querySelectorAll('.requestPaymentBtn');
    const workId = document.querySelector('input[name="work_id"]');

    requestPaymentButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = button.getAttribute('data-id');
            workId.value = id;
        });
    });

    $(document).on('click', '.viewMileStoneRequestBtn', function () {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                $('#modalRequestContent').html(response);
                $('#modalViewRequest').modal('show');
            },
            error: function () {
                $('#modalRequestContent').html('<p class="text-danger">Something went wrong. Please try again later.</p>');
                $('#modalViewRequest').modal('show');
            }
        });
    });

    $(document).on('click', '#submitAccept', function (e) {
        e.preventDefault();
        const workBidId = $('#work_id').val();
        //user/buyer/hire/employees/accept/work/bid
        $.ajax({
            url: routes.acceptWorkBid,
            type: "POST",
            data: {
                 _token: csrfTokenAjax,
                work_id: workBidId
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('#modalPayNow').modal('hide');
                    //location.reload();
                    $('#alert-message').text(response.message || 'Action completed successfully!');
                    $('#alert').removeClass('d-none').addClass('show');
                    $('#rejectWorkModal').modal('hide');
                    setTimeout(() => {
                        $('#alert').alert('close');
                        window.location.reload();
                    }, 2000);

                }else {
                    $('#alert-message').text(response.message || 'Action completed successfully!');
                    $('#alert').removeClass('d-none').addClass('show');
                    $('#rejectWorkModal').modal('hide');
                    setTimeout(() => {
                        $('#alert').alert('close');
                        window.location.reload();
                    }, 2000);
                }
            },
            error: function () {
                alert('Failed to accept the work bid.');
            }
        });
    });

    $(document).on('click', '.edit-rating-button', function () {
        $('#ratingForm').attr('action', '{{ route("user.buyer.rating.bid.edit") }}');
        const skills = $(this).data('skills');
        const quality = $(this).data('quality');
        const availability = $(this).data('availability');
        const communication = $(this).data('communication');
        const cooperation = $(this).data('cooperation');
        const reason = $(this).data('reason');
        const comment = $(this).data('comment');
        const likelyRating = $(this).data('likely-rating');
        const sellerName = $(this).data('seller-name');
        const strengths = $(this).data('strengths');
        const englishProficiency = $(this).data('english-proficiency');
        const rating = $(this).data('score');

        $('#skills').val(skills);
        $('#totalScore').html(rating);
        $('#quality').val(quality);
        $('#availability').val(availability);
        $('#communication').val(communication);
        $('#cooperation').val(cooperation);
        $(`a[data-value="${likelyRating}"]`).addClass('selected');
        $('select[name="reason"]').val(reason);
        $('#comment').val(comment);
        $('#modalContract .modal-body p').find('b').text(sellerName);
        populateStarRating('skillsRating', skills);
        populateStarRating('qualityRating', quality);
        populateStarRating('availabilityRating', availability);
        populateStarRating('communicationRating', communication);
        populateStarRating('cooperationRating', cooperation);

        let strengthsArray = [];
        try {
            strengthsArray = JSON.parse(strengths);
        } catch (e) {
            console.error("Error parsing strengths:", e);
        }

        if (Array.isArray(strengthsArray)) {
            strengthsArray.forEach(strength => {
                $(`input[name="strengths[]"][value="${strength}"]`).prop('checked', true);
            });
        }
        $(`input[name="englishProficiency"][value="${englishProficiency}"]`).prop('checked', true);
    });

    function populateStarRating(elementId, rating) {
        const stars = $(`#${elementId} .star`);
        stars.each(function(index) {
            if (index < rating) {
                $(this).addClass('text-warning'); // Highlight the stars based on rating
            } else {
                $(this).removeClass('text-warning');
            }
        });
    }
    document.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', function () {
            const category = this.getAttribute('data-category');
            const score = parseInt(this.getAttribute('data-score'));
            updateStars(category, score); // Update the stars and total score
        });
    });

    function updateStars(category, score) {
        // Update all stars in the category
        document.querySelectorAll(`[data-category="${category}"]`).forEach(star => {
            star.classList.toggle('text-warning', star.getAttribute('data-score') <= score);
        });
        ratings[category] = score; // Update the score in the ratings object
        document.getElementById(category).value = score;

        calculateTotalScore();
    }

    function calculateTotalScore() {
        const totalScore = Object.values(ratings).reduce((acc, score) => acc + score, 0);
        const avg = totalScore / 5
        document.getElementById('totalScore').innerText = avg;
    }
    let count_row = 1;
    window.calculateDaysFromToday = function(index) { // Define globally
        const dueDateInput = document.querySelector(`[name="milestone[${index}][due_date]"]`);
        const daysFromTodayInput = document.querySelector(`#days_from_today_${index}`);

        const dueDate = new Date(dueDateInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Set current date to midnight for accurate calculation
        dueDate.setHours(0, 0, 0, 0);

        if (dueDate < today) {
            daysFromTodayInput.value = ''; // Clear if the due date is in the past
            alert("Due date cannot be in the past.");
        } else {
            const timeDiff = dueDate - today;
            const daysDiff = timeDiff / (1000 * 3600 * 24); // Calculate days difference
            daysFromTodayInput.value = daysDiff.toFixed(0); // Set the days difference
        }
    }

    $(document).on('click', '#add_more_milestone', function() {
        const newField = `
            <div class="row row-10 milestone" id="row_${count_row}">
                <div class="col-sm-6 col-12">
                    <div class="form-field">
                        <label for="title">Milestone title</label>
                        <input type="text" name="milestone[${count_row}][title]" class="form-control required titled" placeholder="Milestone Title">
                    </div>
                </div>
                <div class="col-sm-3 col-12">
                    <div class="form-field">
                        <label for="amount">Amount</label>
                        <input type="text" name="milestone[${count_row}][amount]" class="form-control required amount" placeholder="$0">
                    </div>
                </div>
                <div class="col-sm-3 col-12">
                    <div class="form-field">
                        <label for="due_date">Due date</label>
                        <input type="text" name="milestone[${count_row}][due_date]" class="form-control required due_date datepicker" placeholder="YYYY-MM-DD" onchange="calculateDaysFromToday(${count_row})">
                        <input type="hidden" id="days_from_today_${count_row}" name="milestone[${count_row}][days_from_today]" value="">
                    </div>
                </div>
                <div class="col-sm-1 col-12 mt-3">
                    <button type="button" id="remove-button" class="close_milestone btn btn-danger mt-1 btn-circle" style="width:10p" data-close="row_${count_row}" ><i class="fa fa-minus fa-fw"></i></button>
                </div>
            </div>
        `;
        $('#milestone_wrapper').append(newField);
        initializeDatepicker();
        count_row++;
    });


    $(document).on('click', '.close_milestone', function(e) {
        e.preventDefault();
        const id = $(this).attr('data-close');
        $('#' + id).remove();
    });
    $(document).on('shown.bs.modal', '#modalRequestMilestone', function () {
        initializeDatepicker();
    });
});

$(document).on('click', '.viewRequestBtn', function () {
    var dataId = $(this).data('id');
    var baseUrl = $(this).data('url');
    var url = baseUrl.replace('__id__', dataId);
    console.log("URL:", url);
    $.ajax({
        url: url,
        type: 'GET',
        success: function (response) {
            $('#modalRequestContent').html(response);
            $('#modalViewRequest').modal('show');
        },
        error: function () {
            $('#modalRequestContent').html('<p class="text-danger">Something went wrong. Please try again later.</p>');
            $('#modalViewRequest').modal('show');
        }
    });
});
$(document).on('click', '.acceptButton', function (e) {
    e.preventDefault();
    const workBidId = $(this).data('id');
    $('#work_id').val(workBidId);

    $('#modalViewRequest').modal('hide');
    $('#modalPayNow').modal('show');
});

$(document).on('click', '.rejectButton', function (e) {
    e.preventDefault();
    // Set the work bid ID in the hidden input
    const workBidId = $(this).data('id');
    $('#reject_work_id').val(workBidId);

    // Show the modal
    $('#modalViewRequest').modal('hide');
    $('#rejectWorkModal').modal('show');
});

$(document).on('click','#submitReject', function (e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('rejectForm'));
    $.ajax({
        url: routes.rejectWorkBid,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === 'success') {
                $('#alert-message').text(response.message || 'Action completed successfully!');
                $('#alert').removeClass('d-none').addClass('show');
                $('#rejectWorkModal').modal('hide');
                  setTimeout(() => {
                    $('#alert').alert('close');
                    window.location.reload();
                }, 2000);
            }else {
                $('#alert-message').text(response.message || 'Action completed successfully!');
                $('#alert').removeClass('d-none').addClass('show');
                $('#rejectWorkModal').modal('hide');
                  setTimeout(() => {
                    $('#alert').alert('close');
                    window.location.reload();
                }, 2000);
            }
        },
        error: function () {
            $('#alert-message').text(response.message || 'Action Failed!');
            $('#alert').removeClass('d-none').addClass('show');
            $('#rejectWorkModal').modal('hide');
                setTimeout(() => {
                $('#alert').alert('close');
                window.location.reload();
            }, 2000);
        }
    });
});

$(document).on('click', '.reqmsacceptBtn', function () {
    const modal = $('#reqmsacceptModal');
    const workId = $(this).data('work-id');
    modal.find('#new_milestone_accept_request_id').val(workId);
    modal.modal('show');
});

$(document).on('click', '.reqmsrejectBtn', function () {
    const modal = $('#reqmsrejectModal');
    const workId = $(this).data('work-id'); // Ensure the button has the `data-work-id` attribute
    modal.find('#new_milestone_reject_request_id').val(workId);
    modal.modal('show');
});



$(document).on('change', '.select-all-checkbox', function () {
    const rowId = $(this).data('row-id'); // Get the row ID for this "Select All" checkbox
    const isChecked = $(this).is(':checked'); // Check if "Select All" is checked

    // Find all checkboxes in the same row and update their checked status
    $(`.select-checkbox[data-row-id="${rowId}"]`).prop('checked', isChecked);
});

// If any individual checkbox is unchecked, uncheck the "Select All" checkbox
$(document).on('change', '.select-checkbox', function () {
    const rowId = $(this).data('row-id'); // Get the row ID for this checkbox

    // Check if all checkboxes in this row are checked
    const allChecked = $(`.select-checkbox[data-row-id="${rowId}"]`).length === $(`.select-checkbox[data-row-id="${rowId}"]:checked`).length;

    // Update the "Select All" checkbox based on individual checkboxes
    $(`.select-all-checkbox[data-row-id="${rowId}"]`).prop('checked', allChecked);
});

$(document).on('click', '#disputeSelectedIntervals', function (e) {
    e.preventDefault();

    // Get selected interval IDs (tracked intervals)
    const selectedIntervalIds = [];
    $('input.select-checkbox:checked').each(function () {
        selectedIntervalIds.push($(this).val());
    });

    // Get selected manual delivery hour blocks
    const selectedManualBlocks = [];
    $('.select-all-checkbox:checked').each(function () {
        const blockCost = parseFloat($(this).data('block-cost') || 0); // Get block cost if present (manual only)
        const deliveryHourId = $(this).data('delivery-hour-id');
        if ($(this).data('block-cost') !== undefined) {
            // Only add manual blocks with data-block-cost
            selectedManualBlocks.push({ id: deliveryHourId, cost: blockCost });
        }
    });
    console.log(selectedIntervalIds.length);
    console.log(selectedManualBlocks.length);
    // Ensure only one type (manual or auto) is selected
    if (selectedIntervalIds.length > 0 && selectedManualBlocks.length > 0) {
        alert("You can only dispute either tracked intervals or manual blocks at one time.");
        return; // Prevent modal from opening
    }

    // If nothing is selected, show alert
    if (selectedIntervalIds.length === 0 && selectedManualBlocks.length === 0) {
        alert("Please select at least one interval or block to dispute.");
        return; // Prevent modal from opening
    }

    // Open the modal for disputes
    $('#modalHourlyDispute').modal('show');

    // Update the list of selected intervals or manual blocks in the modal
    let intervalInputs = '';
    let blockInputs = '';

    if (selectedIntervalIds.length > 0) {
        // Add selected interval IDs for auto-tracked case
        intervalInputs = selectedIntervalIds.map(id => `<input type="hidden" name="interval_ids[]" value="${id}">`).join('');
    } else if (selectedManualBlocks.length > 0) {
        // Add selected manual delivery hour IDs for manual case
        blockInputs = selectedManualBlocks.map(block => `<input type="hidden" name="manual_delivery_hour_ids[]" value="${block.id}">`).join('');
    }

    $('#selectedIntervalsContainer').html(intervalInputs + blockInputs);

    // Calculate the total dispute amount
    let totalDisputeAmount = 0;

    if (selectedIntervalIds.length > 0) {
        // Add costs of selected intervals
        totalDisputeAmount += selectedIntervalIds.reduce((sum, id) => {
            const cost = parseFloat($(`#interval-${id}`).data('cost') || 0); // Default cost to 0 if missing
            return sum + cost;
        }, 0);
    } else if (selectedManualBlocks.length > 0) {
        // Add costs of selected manual blocks
        totalDisputeAmount += selectedManualBlocks.reduce((sum, block) => sum + block.cost, 0);
    }

    // Update the dispute amount display in the modal
    $('#disputeAmount').text(`$${totalDisputeAmount.toFixed(2)}`);
    $('#total_cost').val(totalDisputeAmount.toFixed(2));
});


$(document).on('click', '#sendHourlyDispute', function (e) {
    e.preventDefault();

    const formData = new FormData($('#hourlyDisputeForm')[0]);

    $.ajax({
        url: routes.disputeWorkIntervals,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            alert('Dispute submitted successfully!');
            $('#modalHourlyDispute').modal('hide');
            location.reload();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;

                // Clear previous error messages
                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');

                // Display validation errors below respective fields
                Object.keys(errors).forEach(function (field) {
                    const errorField = $('#' + field + '-error');
                    if (errorField.length) {
                        errorField.text(errors[field][0]);
                        $('#' + field).addClass('is-invalid');
                    }
                });
            } else {
                alert('An error occurred. Please try again later.');
            }
        },
    });
});
document.addEventListener('click', function (event) {
    if (event.target.closest("[id^='liveToastBtn-']")) {
        const button = event.target.closest("[id^='liveToastBtn-']");
        const intervalId = button.id.split('-')[1];
        const toastElement = document.getElementById('liveToast-' + intervalId);

        if (toastElement) {
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }
    }
});
