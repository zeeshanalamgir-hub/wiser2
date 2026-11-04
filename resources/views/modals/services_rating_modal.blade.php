<style>
    .star {
        font-size: 1.5rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }

    .star.hovered,
    .star.selected {
        color: gold;
    }
    .form-check-label, #skillsRating, #qualityRating, #availabilityRating, #communicationRating, #cooperationRating {
        font-weight:400 !important;
    }
</style>
<div class="modal fade" id="contractModal" tabindex="-1" aria-labelledby="modalContractLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="max-width:500px">
            <div class="modal-header bg-secondary text-white py-2 px-4">
                <h1 class="modal-title fs-5" id="contractModal">End Contract</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold">Rating &amp; review</h6>
                <p>How was your work experience with <i class="fa-solid fa-circle-user fs-5"></i>
                    <b>{{ $seller->username }}</b>
                </p>
                <h6 class="fw-bold">Private Feedback:<span class="fs-12 fw-normal"> Never share feedback directly</span>
                </h6>

                <!-- Form for posting data -->
                <form action="{{ route('user.booking-service-rating') }}" method="POST">
                    @csrf

                    <input type="hidden" name="buyer_id" value="{{ $booking->buyer_id }}">
                    <input type="hidden" name="seller_id" value="{{ $booking?->seller_id }}">
                    <input type="hidden" name="service_id" value="{{ $booking->service_id }}">
                    <input type="hidden" name="skills" id="skills">
                    <input type="hidden" name="quality" id="quality">
                    <input type="hidden" name="availability" id="availability">
                    <input type="hidden" name="communication" id="communication">
                    <input type="hidden" name="cooperation" id="cooperation">

                    <!-- Reason for Ending Contract -->
                    <div class="mb-3">
                        <label for="contractReason" class="form-label">Reason for ending contract:</label>
                        <select name="reason" class="form-select text-secondary fs-14"
                            aria-label="Default select example">
                            <option value="Service completed successfully">Service completed successfully</option>
                            <option value="Not satisfied with work">Not satisfied with work</option>
                            <option value="Very lazy freelancer">Very lazy freelancer</option>
                        </select>
                    </div>

                    <!-- Likelihood Rating -->
                    <div class="mb-3" style="width:100%;">
                        <span class="fs-12"> Very Unlikely </span>
                        <span class="fs-12 float-end mt-1"> Very Likely </span><br>
                        @for ($i = 1; $i <= 10; $i++)
                            <a class="btn btn-sm rounded-0 border-0 btn-{{ $i <= 3 ? 'danger' : ($i <= 7 ? 'warning' : 'success') }} rating-btn"
                                data-value="{{ $i }}"
                                onclick="setLikelyRating({{ $i }}, this)">{{ $i }}</a>
                        @endfor
                        <input type="hidden" name="likely_rating" id="likely_rating">
                    </div>
                    <h6 class="fw-bold">Public Feedback:

                        <!-- Strengths Checkbox -->
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label fw-bold">What do you think are
                                their
                                strengths?</label><br>
                            <input class="form-check-input" type="checkbox" name="strengths[]" value="Quality"
                                id="defaultCheck1">
                            <label class="form-check-label fs-14" for="defaultCheck1"> Quality </label>
                            <input class="form-check-input" type="checkbox" name="strengths[]" value="Communication"
                                id="defaultCheck2">
                            <label class="form-check-label fs-14" for="defaultCheck2"> Communication </label>
                        </div>

                        <!-- English Proficiency Rating -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rate their English proficiency:</label><br>
                            <input class="form-check-input" type="radio" name="englishProficiency"
                                value="Difficult to understand" id="epCheck1">
                            <label class="form-check-label fs-14" for="epCheck1"> Difficult to understand </label>
                            <input class="form-check-input" type="radio" name="englishProficiency" value="Acceptable"
                                id="epCheck2">
                            <label class="form-check-label fs-14" for="epCheck2"> Acceptable </label>
                            <input class="form-check-input" type="radio" name="englishProficiency" value="Fluent"
                                id="epCheck3">
                            <label class="form-check-label fs-14" for="epCheck3"> Fluent </label>
                            <input class="form-check-input" type="radio" name="englishProficiency"
                                value="Didn’t speak" id="epCheck4">
                            <label class="form-check-label fs-14" for="epCheck4"> Didn’t speak </label>
                        </div>

                        <!-- Star Rating for Skills, Quality, etc. -->
                        <div class="mb-3">
                            <!-- Skills Rating -->
                            <p class="mb-0" id="skillsRating">
                                <span class="text-e1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star star" data-score="{{ $i }}"
                                            data-category="skills"></i>
                                    @endfor
                                </span>
                                Skills
                            </p>

                            <!-- Quality of Work Rating -->
                            <p class="mb-0" id="qualityRating">
                                <span class="text-e1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star star" data-score="{{ $i }}"
                                            data-category="quality"></i>
                                    @endfor
                                </span>
                                Quality of Work
                            </p>

                            <!-- Availability Rating -->
                            <p class="mb-0" id="availabilityRating">
                                <span class="text-e1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star star" data-score="{{ $i }}"
                                            data-category="availability"></i>
                                    @endfor
                                </span>
                                Availability
                            </p>

                            <!-- Communication Rating -->
                            <p class="mb-0" id="communicationRating">
                                <span class="text-e1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star star" data-score="{{ $i }}"
                                            data-category="communication"></i>
                                    @endfor
                                </span>
                                Communication
                            </p>

                            <!-- Cooperation Rating -->
                            <p class="mb-0" id="cooperationRating">
                                <span class="text-e1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star star" data-score="{{ $i }}"
                                            data-category="cooperation"></i>
                                    @endfor
                                </span>
                                Cooperation
                            </p>
                        </div>

                        <!-- Total Score -->
                        <h6 class="fw-bold my-3">Total score: <span id="totalScore">0</span></h6>

                        <!-- Comment -->
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" name="comment" id="comment" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    let ratings = {
        skills: 0,
        quality: 0,
        availability: 0,
        communication: 0,
        cooperation: 0
    };

    const stars = document.querySelectorAll(".star");

    stars.forEach(star => {

        const category = star.dataset.category;
        const score = parseInt(star.dataset.score);

        // Hover effect
        star.addEventListener("mouseover", function () {
            highlightStars(category, score);
        });

        // Remove hover when leaving
        star.addEventListener("mouseleave", function () {
            highlightStars(category, ratings[category]);
        });

        // Click event
        star.addEventListener("click", function () {
            ratings[category] = score;

            document.getElementById(category).value = score;
            highlightStars(category, score);
            calculateTotal();
        });

    });

    function highlightStars(category, score) {

        document.querySelectorAll(`.star[data-category="${category}"]`)
            .forEach(star => {
                star.classList.remove("selected");

                if (parseInt(star.dataset.score) <= score) {
                    star.classList.add("selected");
                }
            });
    }

    function calculateTotal() {
        let total = Object.values(ratings).reduce((a, b) => a + b, 0);
        document.getElementById("totalScore").innerText = total;
    }

});
</script>
