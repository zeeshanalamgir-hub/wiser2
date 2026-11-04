
<p>Milestone Title: <b>{{ $data['title'] }}</b></p>
<label for="inputCity" class="form-label">Request Logs:</label>
<hr>
@foreach ($data['workDataList'] as $work)
    <p>Request Status:
        <span class="{{ $work['statusText'] == 'Rejected' ? 'bg-danger' : ($work['statusText'] == 'Paid' ? 'bg-green' : 'bg-warning') }}  px-2 py-1 fs-12 rounded-1 ms-0 text-white">{{ $work['statusText'] }}
    </p>
    <p>{{ $work['coverLetter'] }}</p>
    @if($work['attachment'])
        <p><span class="font-weight-bold">Attachment:</span>
            <a title="Download attachment" href="{{ route('user.work.send.file.download', encrypt($work['id'])) }}">
                <i class="fa fa-paperclip"></i>
            </a>
        </p>
    @endif
    <p><span class="font-weight-bold">Work Submitted Date:</span> {{ $work['workSubmittedOn'] }}</p>
    @if($work['reason'])
        <p class="col-12 shadow mt-3 alert alert-danger"><span class="font-weight-bold">Reason:</span> {{ $work['reason'] }}</p>
    @endif
    <hr> <!-- Divider for each work item -->
@endforeach




