<div class="row">
    <div class="col-12">
        <!-- Check if the rating exists -->
        @if (!$rating)
            <!-- Display message if no rating is found -->
            <div class="row text-secondary fs-14">
                <div class="col-12 text-center">
                    <div class="alert">
                        No rating found for the given job.
                    </div>
                </div>
            </div>
        @else
            <!-- Display the rating information -->
            <p class="mb-2">From: <b>{{ $rating->buyer->username ?? 'Buyer' }}</b> <span class="ms-5">To: <b>{{ $rating->seller->username ?? 'Seller' }}</b></span></p>
            <p class="fs-6">
                @for ($i = 0; $i < 5; $i++)
                    <i class="fa-solid fa-star {{ $i < $rating->skill ? 'text-warning' : 'text-secondary' }}"></i>
                @endfor
                <i class="fa-regular fa-calendar text-secondary ms-4"></i>
                <span class="text-dark">{{ $rating->created_at->format('d/m/Y') }}</span>
            </p>
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                See all skills rating <i class="fa-solid fa-chevron-down ms-1 fs-14 text-secondary"></i>
            </button>

            <div class="collapse" id="collapseExample">
                <div class="card card-body border-0">
                    <p class="mb-0">
                        <span class="text-e1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rating->skill ? 'text-warning' : '' }}"></i>
                            @endfor
                        </span>
                        Skills
                    </p>

                    <p class="mb-0">
                        <span class="text-e1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rating->quality ? 'text-warning' : '' }}"></i>
                            @endfor
                        </span>
                        Quality of Work
                    </p>

                    <p class="mb-0">
                        <span class="text-e1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rating->availability ? 'text-warning' : '' }}"></i>
                            @endfor
                        </span>
                        Availability
                    </p>

                    <p class="mb-0">
                        <span class="text-e1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rating->communication ? 'text-warning' : '' }}"></i>
                            @endfor
                        </span>
                        Communication
                    </p>

                    <p class="mb-0">
                        <span class="text-e1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= $rating->cooperation ? 'text-warning' : '' }}"></i>
                            @endfor
                        </span>
                        Cooperation
                    </p>
                </div>
            </div>

            <div class="w-100 border mt-4">
                <p class="border-bottom p-3">Reason for ending contract <span class="float-end">{{ $rating->reason }}</span></p>
                <p class="border-bottom px-3 pb-3">Recommendation <span class="float-end">{{ $rating->likely_rating }}</span></p>
                <p class="border-bottom px-3 pb-3">Strength
                    <span class="float-end">
                        @if (is_array(json_decode($rating->strengths)))
                            {{ implode(', ', json_decode($rating->strengths)) }} <!-- Convert the array to a string -->
                        @else
                            {{ json_decode($rating->strengths) }} <!-- Just output the string -->
                        @endif
                    </span>
                </p>
                <p class="px-3">English proficiency <span class="float-end">{{ $rating->english_proficiency }}</span></p>
            </div>

            <p class="mb-2 mt-4"><b>Comments:</b> {{ $rating->review }} </p>
            @if ($rating->reply != '')
                <p class="mb-2 mt-2"><b>Reply:</b> {{ $rating->reply }} </p>
            @endif
            @if(is_null($rating->reply))
                <button class="btn btn-sm btn-success bg-dgreen2 mt-3 edit-rating-button"
                    data-bs-toggle="modal"
                    data-bs-target="#modalContract"
                    data-score="{{ $rating->rating }}"
                    data-review_id="{{ $rating->id }}"
                    data-skills="{{ $rating->skill }}"
                    data-quality="{{ $rating->quality }}"
                    data-availability="{{ $rating->availability }}"
                    data-communication="{{ $rating->communication }}"
                    data-cooperation="{{ $rating->cooperation }}"
                    data-reason="{{ $rating->reason }}"
                    data-comment="{{ $rating->review }}"
                    data-likely-rating="{{ $rating->likely_rating }}"
                    data-seller-name="{{ $rating->seller->username }}"
                    data-strengths="{{ json_encode($rating->strengths) }}"
                    data-english-proficiency="{{ $rating->english_proficiency }}">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button>
                @include('modals.rating_modal')
            @endif
        @endif
    </div>
</div>
