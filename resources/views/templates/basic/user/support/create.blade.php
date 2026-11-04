@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
<div class="row mt-2">
    <div class="col-12">
        <h5 class="text-dgreen2 "> Create a new ticket</h5>
        <div class="card shadow-sm mt-3 p-4" style="border: solid 1px #d2d2d2">
            <form class="user-profile-form row g-3"  action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                @csrf
                <div class="col-md-6 mb-2">
                    <label for="name" class="col-form-label">@lang('Name')</label>
                    <input type="text" name="name" value="{{@$user->firstname . ' '.@$user->lastname}}" class="form-control" placeholder="@lang('Enter your name')" readonly>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="email" class="col-form-label">@lang('Email address')</label>
                    <input type="email" name="email" value="{{@$user->email}}" class="form-control" class="form-control" placeholder="@lang('Enter your email')" readonly>
                </div>
                <div class="col-md-6 mt-1">
                    <label for="website"  class="col-form-label">@lang('Subject')</label>
                    <input type="text" name="subject" value="{{old('subject')}}" class="form-control" placeholder="@lang('Subject')" required>
                </div>
                <div class="col-md-6 mt-1">
                    <label for="priority" class="col-form-label">@lang('Priority')* </label>
                    <select name="priority" class="form-select form-select" aria-label=".form-select-sm example" required>
                        <option value="3">@lang('High')</option>
                        <option value="2">@lang('Medium')</option>
                        <option value="1">@lang('Low')</option>
                    </select>
                </div>
                <div class="col-12 mt-1">
                    <label for="inputMessage" class="col-form-label px-0"> @lang('Message') * </label>
                    <textarea name="message" id="inputMessage" class="form-control" rows="4" placeholder="@lang('Enter Message')" required >{{old('message')}}</textarea>
                </div>


                <div class="col-12">
                    <label for="inputAttachments" class="form-label">@lang('Attachments')</label>
                    <input
                        class="form-control"
                        type="file"
                        name="attachments[]"
                        id="inputAttachments"
                        multiple
                        accept=".jpg,.jpeg,.png,.doc,.docx"
                        aria-describedby="fileHelp"
                    >
                    <p class="form-text">@lang('Allowed File Extensions'): .jpg, .jpeg, .png, .doc, .docx</p>
                </div>

                <div id="fileUploadsContainer"></div>
                <div class="col-12 mt-1">
                    <button type="button" class="btn btn-outline-secondary addFile">@lang('Add Another File')</button>
                </div>

                <div class="col-12 mb-2">
                    <button type="submit" class="btn btn-success bg-green border-0">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    "use strict";
    $(document).on("change", ".custom-file-input", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $('.addFile').on('click', function() {
        $("#fileUploadsContainer").append(`
            <div class="input-group my-2">
                <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.doc,.docx" name="attachments[]">
                <button type="button" class="btn btn-danger remove-btn" style="margin-left: 5px;">@lang('Remove')</button>
            </div>
        `);
    });

    $(document).on('click', '.remove-btn', function() {
        $(this).closest('.input-group').remove();
    });
</script>
@endsection
