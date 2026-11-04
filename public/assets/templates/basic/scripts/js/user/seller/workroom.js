// Document Ready Function
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('requestPaymentBtn')) {
            const id = event.target.getAttribute('data-id');
            const workId = document.querySelector('input[name="work_id"]');
            workId.value = id;
        }
    });
});

// Event delegation for dynamically generated elements
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

// Event Listeners for Modal Triggers
$(document).ready(function () {
    function initializeDatepicker() {
        $('.datepicker').datetimepicker({
            format: 'Y-m-d',
            minDate: 0
        });
    }

    $(document).on('click', '.reqmsacceptBtn', function () {
        console.log('Accept button clicked!');
        console.log('Work ID:', $(this).data('work-id'));
        var modal = $('#reqmsacceptModal');
        modal.find('input[name=milestone_accept_work_id]').val($(this).data('work-id'));
        modal.modal('show');
    });

    $(document).on('click', '.reqmsrejectBtn', function () {
        console.log('Reject button clicked!');
        console.log('Work ID:', $(this).data('work-id'));
        var modal = $('#reqmsrejectModal');
        modal.find('input[name=milestone_reject_work_id]').val($(this).data('work-id'));
        modal.modal('show');
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

    function calculateDuration() {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const totalHoursInput = document.getElementById('total_hours');
        const totalMinutesInput = document.getElementById('total_minutes');

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
            console.log(hours);
            const minutes = Math.round((duration % (1000 * 60 * 60)) / (1000 * 60));
            console.log(minutes);
            totalHoursInput.value = hours;
            totalMinutesInput.value = minutes;
        } else {
            totalHoursInput.value = '';
            totalMinutesInput.value = '';
        }
    }


    $(document).on('change', '#start_time, #end_time', calculateDuration);
    $('#modalManual').on('input', '#start_time, #end_time', calculateDuration);

    $(document).on('change', '#start', function () {
        const selectedDate = this.value;
        const timesheetUrl = `${this.dataset.url}?date=${selectedDate}`;
        loadAjaxContent(timesheetUrl, 'Timesheet');
    });
    $('#modalManual').on('shown.bs.modal', function () {
        // Clear all input fields
        $('#timesheetForm')[0].reset();

        // Clear error messages
        $('#manualError').html(''); // Clear the manual error section
        $('.text-danger').html(''); // Clear any other error messages

        // Recalculate duration
        calculateDuration();
    });
    // AJAX form submission
    $(document).on('submit', '#timesheetForm', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let formAction = $(this).attr('action');
        $.ajax({
            type: 'POST',
            url: formAction,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#modalManual').modal('hide');
                iziToast.success({
                    message: response.message,
                    position: "topRight"
                });
                const timesheetUrl = $("a.custom-ajax-link:contains('Timesheet')").data('url');
                loadAjaxContent(timesheetUrl, 'Timesheet');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function (response) {
                if (response.status === 409) {
                    // Custom error handling for tracker-on conflict
                    let message = response.responseJSON.message || 'Conflict occurred while submitting timesheet.';
                    $('#modalManual').modal('show');
                    $('#manualError').html(`<div class="alert alert-danger">${message}</div>`);
                } else {
                    let errors = response.responseJSON.errors;
                    $('#modalManual').modal('show');
                    $.each(errors, function (key, value) {
                        $('#' + key + '-error').html(value[0]);
                    });
                }
            }
        });
    });

    // Select All Checkbox Functionality
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('select-all-checkbox')) {
            const rowId = e.target.getAttribute('data-row-id');
            const isChecked = e.target.checked;

            document.querySelectorAll(`.select-checkbox[data-row-id="${rowId}"]`).forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        }
    });

    // Delete Selected Intervals
    $(document).on('click', '#deleteSelectedIntervals', function (e) {
        e.preventDefault();

        const intervalIds = $('input[name="interval_ids[]"]:checked').map(function () {
            return this.value;
        }).get();

        const manualDeliveryHourIds = Array.from(document.querySelectorAll('.select-all-checkbox:checked'))
            .map(checkbox => checkbox.getAttribute('data-delivery-hour-id'));
        console.log(manualDeliveryHourIds);
        if (intervalIds.length === 0 && manualDeliveryHourIds.length === 0) {
            iziToast.error({
                message: "Please select at least one interval to delete.",
                position: "topRight"
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: routes.deleteWorkIntervals,
            data: {
                _token: csrfTokenAjax,
                interval_ids: intervalIds,
                manual_delivery_hour_ids: manualDeliveryHourIds
            },
            success: function (response) {
                iziToast.success({
                    message: response.message,
                    position: "topRight"
                });
                const timesheetUrl = $("a.custom-ajax-link:contains('Timesheet')").data('url');
                loadAjaxContent(timesheetUrl, 'Timesheet');
                // Refresh page after short delay (so user can see toast)
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function (xhr) {
                let errors = xhr.responseJSON ? xhr.responseJSON.errors : { general: ["An error occurred."] };
                $.each(errors, function (key, value) {
                    iziToast.error({
                        message: value[0],
                        position: "topRight"
                    });
                });
            }
        });
    });


    let count_row = 1;
    window.calculateDaysFromToday = function (index) { // Define globally
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

    $(document).on('click', '#add_more_milestone', function () {
        const newField = `
                <div class="row mt-2 row-10 milestone" id="row_${count_row}">
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

function checkAmountByMilestone() {
    let totalAmount = 0;
    $('#milestone_wrapper .amount').each(function() {
        const amount = parseFloat($(this).val()) || 0;
        totalAmount += amount;
    });

    const commission = (totalAmount * commissionRate) / 100;
    const toPay = totalAmount - commission;
    $('#milestone_pay_amt').text(toPay.toFixed(2));
}

function enableMemoEdit(bidId) {
    // Toggle between text and input
    document.getElementById(`memo-text-${bidId}`).classList.add('d-none');
    document.getElementById(`memo-input-${bidId}`).classList.remove('d-none');

    // Show update button and hide edit button
    document.getElementById(`edit-memo-btn-${bidId}`).classList.add('d-none');
    document.getElementById(`update-memo-btn-${bidId}`).classList.remove('d-none');
}

function validateWordCount(bidId) {
    const textarea = document.getElementById(`memo-input-${bidId}`);
    const wordCountDisplay = document.getElementById(`word-count-${bidId}`);
    const updateButton = document.getElementById(`update-memo-btn-${bidId}`);

    const words = textarea.value.trim().split(/\s+/).filter(word => word.length > 0);
    const wordCount = words.length;

    wordCountDisplay.textContent = `Words: ${wordCount} (15-30 words required)`;
    wordCountDisplay.classList.remove('d-none');

    // if (wordCount >= 15 && wordCount <= 30) {
    //     updateButton.disabled = false;
    // } else {
    //     updateButton.disabled = true;
    // }
}

function updateMemo(bidId) {
    const alertBox = document.getElementById('memo_update');
    const input = document.getElementById(`memo-input-${bidId}`);
    const updatedMemo = input.value.trim();
    if (updatedMemo === '') {
        alertBox.className = 'alert alert-danger';
        alertBox.innerHTML = 'Memo cannot be empty!';
        alertBox.style.display = 'block';

        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000);

        return;
    }
    // Make API call to update memo
    fetch(`/user/seller/update-memo`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            job_work_delivery_hourly_bid_id: bidId,
            memo: updatedMemo
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`memo-text-${bidId}`).textContent = updatedMemo;
            // Show success message
            alertBox.className = 'alert alert-success';
            alertBox.innerHTML = 'Memo updated successfully!';
            alertBox.style.display = 'block';

        } else {
            // Show error message
            alertBox.className = 'alert alert-danger';
            alertBox.innerHTML = data.message ?? 'Failed to update memo.';
            alertBox.style.display = 'block';
        }
         // Auto hide after 3 seconds (optional)
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000);
    })
    .catch(error => {
        console.error('Error updating memo:', error);
        alertBox.className = 'alert alert-danger';
        alertBox.innerHTML = 'An error occurred while updating the memo.';
        alertBox.style.display = 'block';

        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000);
    })
    .finally(() => {
        // Toggle back to non-editable state
        document.getElementById(`memo-text-${bidId}`).classList.remove('d-none');
        document.getElementById(`memo-input-${bidId}`).classList.add('d-none');
        document.getElementById(`edit-memo-btn-${bidId}`).classList.remove('d-none');
        document.getElementById(`update-memo-btn-${bidId}`).classList.add('d-none');
    });
}

// function showBootstrapModal(modalId, onConfirm = null) {
//     const modalElement = document.getElementById(modalId);
//     if (!modalElement) return;

//     const modal = new bootstrap.Modal(modalElement);
//     const confirmButton = modalElement.querySelector(".btn-primary");

//     // Clone the confirm button to remove any old listeners
//     const newConfirmButton = confirmButton?.cloneNode(true);
//     if (confirmButton && newConfirmButton) {
//         confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);
//         if (onConfirm) {
//             newConfirmButton.addEventListener("click", () => {
//                 modal.hide();
//                 onConfirm();
//             });
//         }
//     }

//     modal.show();
// }

// function checkAndLaunchApp() {
//     // This method uses direct window.location to open the scheme
//     // Instead of using an iframe and waiting for blur/visibilitychange.

//     const protocolUrl = "alphatimetracker://";
//     const now = Date.now();

//     // Attempt to open the app by redirecting the browser
//     window.location = protocolUrl;

//     // Wait 1.5 seconds, then check if user is still here
//     // If the app was installed, typically the user will have left the page.
//     setTimeout(() => {
//       // We'll compare time to see if the user left for ~500ms or more
//       // But on iOS or some cases, we cannot detect "left" easily.
//       // So we rely on the user *not* being on the page after a small delay
//       // or at least we guess that if the app was installed, they'd be gone longer.

//       const timeElapsed = Date.now() - now;

//       // If the user is still here after 1.5s, assume the app isn't installed
//       // Show the install modal
//       if (timeElapsed < 2000) {
//         showBootstrapModal("downloadAppModal", () => {
//           window.location.href = routes.downloadUrl;
//         });
//       }
//     }, 1500);
// }

function checkAndLaunchApp() {
    const appUrl = "alphatimetracker://";
    const timeoutDuration = 3000; // Time to wait before assuming failure
    let appOpened = false;

    // Start timeout to trigger download if app doesn't open
    const timeout = setTimeout(() => {
        if (!appOpened) {
            // If the app hasn't opened after the timeout, show the download link
            const confirmDownload = confirm(
                "It seems the AlphaTimeTracker app is not installed. Would you like to download it?"
            );
            if (confirmDownload) {
                window.location.href = routes.downloadUrl;
            }
        }
    }, timeoutDuration);

    // Try opening the app using a hidden iframe
    const iframe = document.createElement("iframe");
    iframe.style.display = "none";
    iframe.src = appUrl;
    document.body.appendChild(iframe);

    // Detect if the app opens (browser loses focus or page becomes hidden)
    const onVisibilityChange = () => {
        if (document.hidden || document.visibilityState === "hidden") {
            appOpened = true;
            clearTimeout(timeout);
        }
    };
    document.addEventListener("visibilitychange", onVisibilityChange);

    // Cleanup the iframe and event listener
    setTimeout(() => {
        document.body.removeChild(iframe);
        document.removeEventListener("visibilitychange", onVisibilityChange);
    }, timeoutDuration);
}


// function checkAndLaunchApp() {
//     // Replace with your actual info:
//     const scheme = 'alphatimetracker';
//     const packageName = 'com.alphabizlance.timetracker';
//     const fallbackUrl = encodeURIComponent(routes.downloadUrl);

//     // Format the intent URL
//     const intentUrl = `intent://open#Intent;scheme=${scheme};package=${packageName};S.browser_fallback_url=${fallbackUrl};end;`;

//     // Redirect the browser to the intent URL:
//     window.location.href = intentUrl;
//   }
