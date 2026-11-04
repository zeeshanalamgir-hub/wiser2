@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="card shadow-sm mt-4">
        <div class="card-body p-4">
            @include('templates.basic.user.buyer.header')
            <div class="row">
                <div class="col-12 ps-3 mt-3">
                    <nav class="navbar navbar-expand-sm bg-light fw-bold">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li class="nav-item">
                                        <a class="nav-link" aria-current="page" href="javascript:void(0);"
                                            onclick="loadTabContent('case', '{{ $jobDisputeId }}')">Case Identification</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active text-green" aria-current="page" href="javascript:void(0);"
                                            onclick="loadTabContent('negotiation', '{{ $jobDisputeId }}')">Negotiation</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <div id="tabContent" class="row pt-3">
                @include('templates.basic.user.buyer.dispute_negotiation', [
                    'disputedetail' => $disputedetail,
                    'booking' => $booking,
                ])
            </div>


        </div> <!--card Body End-->
    </div> <!--card End-->

@endsection
@section('script')
    <script>
        // Function to load tab content using AJAX
        function loadTabContent(tabName, jobDisputeId) {
            // Construct the URL with the jobDisputeId parameter
            var url = '/user/buyer/dispute/' + tabName + '/' + jobDisputeId;

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#tabContent').html(response);
                    // Update browser URL without reloading the page
                    if (history.pushState) {
                        history.pushState({ path: url }, '', url);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

            // Update active tab
            $('.nav-link').removeClass('active text-green'); // Remove active class from all nav links
            $(event.target).addClass('active text-green'); // Add active class to the clicked nav link
        }
        $(document).ready(function() {
            // Initial binding in case the form is already loaded
            $(document).on('submit', '#disputeFormSendOffer', function(event) {
                console.log("disputeFormSendOffer");
                event.preventDefault(); // Prevent default form submission

                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('user.buyer.dispute.offer.work.bid') }}",
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status === 'error') {
                            $.each(data.errors, function(index, error) {
                                iziToast.error({
                                    message: error[1],
                                    position: "topRight" // Position of the toast
                                });
                            });
                        } else {
                            iziToast.success({
                                message: data.message,
                                position: "topRight" // Position of the toast
                            });
                            loadTabContent('negotiation', '{{ $jobDisputeId }}');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);

                        // Check if the error response has a JSON body
                        if (xhr.responseJSON) {
                            // If the response status is 422 (validation errors)
                            if (xhr.status === 422) {
                                $.each(xhr.responseJSON.errors, function(index, error) {
                                    iziToast.error({
                                        message: error[
                                        0], // Assuming the first error message is what you want to display
                                        position: "topRight" // Position of the toast
                                    });
                                });
                            } else {
                                // Handle other errors with a generic message
                                iziToast.error({
                                    message: 'An unexpected error occurred. Please try again.',
                                    position: "topRight" // Position of the toast
                                });
                            }
                        } else {
                            // Fallback for any other error
                            iziToast.error({
                                message: 'An unexpected error occurred. Please try again.',
                                position: "topRight" // Position of the toast
                            });
                        }
                    }
                });
            });
            $(document).on('click', '.reqmsrejectBtn', function() {
                var modal = $('#reqmsrejectModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'));
                modal.modal('show');
            });

            $(document).on('click', '.reqmsacceptBtn', function() {
                var modal = $('#reqmsacceptModal');
                modal.find('input[name=work_id]').val($(this).data('work_id'));
                modal.modal('show');
            });
        });
    </script>
@endsection

