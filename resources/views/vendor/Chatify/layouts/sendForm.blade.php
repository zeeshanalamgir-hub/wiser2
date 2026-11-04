<div class="messenger-sendCard">
    <form id="message-form" method="POST" action="{{ route('send.message') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="job_id" value="{{ $job_id ?? NULL }}">

        <textarea readonly='readonly' name="message" class="m-send app-scroll" style="font-size: 16px" placeholder="Type your message!"></textarea>
        <label><span class="fas fa-paperclip "></span><input disabled='disabled' type="file" class="upload-attachment"
                name="file"
                accept=".{{ implode(', .', config('chatify.attachments.allowed_images')) }}, .{{ implode(', .', config('chatify.attachments.allowed_files')) }}" /></label>
        <button class="emoji-button"></span><span class="fas fa-smile"></button>
        <button disabled='disabled' class="send-button btn btn-sm bg-green btn-success border-0"><span
                class="fas fa-paper-plane"></span>
        </button>
    </form>
</div>
