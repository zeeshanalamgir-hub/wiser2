{{--  <div class="row px-1">
    <div class="col-md-6 col-lg-7 col-xl-8 pt-3">
        <p><strong>Job Title: </strong><a class="links-c" href="">{{ $jobTitle }}</a></p>
    </div>
    <div class="col-md-6 col-lg-5 col-xl-4">
    </div>
</div>  --}}

<div class="row px-1">
    <div class="col-md-6 col-lg-7 col-xl-8">
        <p><strong><span class="text-white" style="font-size: 22px">{{ $jobTitle }}</span></strong></p>
        {{--  <p><strong>Description: </strong><span class="text-secondary">{!! $jobDescription !!}</span></p>  --}}

        {{--  Accordian Start  --}}
        <div class="accordion" id="accordionExample">
            <!-- Accordion Item 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" style="border-radius: 10px;font-weight:bold" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                        aria-controls="collapseOne">
                        Description:
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <p>
                            {!! $jobDescription !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        {{--  Accordian End  --}}

    </div>
    <div class="col-md-6 col-lg-5 col-xl-4">
        @if(Auth::user()->is_freelancer == 1 || Auth::user()->is_freelancer == '1')
            <p class="fs-5 text-white">
                <!-- Display the seller's profile image or a default placeholder -->
                <img src="{{ $buyer->image ? Storage::url($buyer->image) : 'https://cdn-icons-png.freepik.com/512/7235/7235981.png' }}"
                    style="width: 45px; height: 45px;border-radius: 50%;object-fit:cover">
                {{ $buyer->username }}
            </p>
        @elseif(Auth::user()->is_employer == 1 || Auth::user()->is_employer == '1')
            @dd("Buyer");
            <p class="fs-5 text-white">
                <!-- Display the seller's profile image or a default placeholder -->
                <img src="{{ $seller->image ? Storage::url($seller->image) : 'https://cdn-icons-png.freepik.com/512/7235/7235981.png' }}"
                    style="width: 45px; height: 45px;border-radius: 50%;object-fit:cover">
                {{ $seller->username }}
            </p>
        @endif
        @if ($isCompleted == 1)
            <!-- Display 'Contract Ended' button with green background and red text if completed -->
            <button class="btn btn-sm bg-lsgreen border text-danger" title="Contract Ended">
                <i class="fa-regular fa-circle-check"></i> Contract Ended
            </button>
        @endif
        <!--Give Bonus-->

    </div>
</div><!--row end-->

