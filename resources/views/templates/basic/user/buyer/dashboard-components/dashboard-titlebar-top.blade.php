<div class="col-md-7">
    @if (Auth::user()->completed_step < 5)
        <p class="mb-1 text-danger" style="font-size: 15px"> Please complete your Profile to access all links!
        <a class="text-danger fw-bold" style="text-decoration: underline" href="{{ route('user.buyer.profile') }}">Click here</a> to complete profile.</p>
    @elseif(Auth::user()->completed_step >=5 && Auth::user()->status == 1)
        <p class="mb-1 text-success" style="font-size: 15px"> Congratulations 🎉.Your profile is complete. </p>
    @endif
    @if (Auth::user()->status == 2)
        <div class="col-md-12">
            <p class="mb-1 text-danger"> Your Profile is completed and currently awaiting admin approval!</p>
        </div>
    @endif
    @include('templates.basic.user.common_components.profile_completeness')
</div>




