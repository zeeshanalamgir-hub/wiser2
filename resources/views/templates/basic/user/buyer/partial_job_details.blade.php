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
        <p class="fs-5 text-white">
            <!-- Display the seller's profile image or a default placeholder -->
            <img src="{{ $seller->image ? Storage::url($seller->image) : 'https://cdn-icons-png.freepik.com/512/7235/7235981.png' }}"
                style="width: 45px; height: 45px;border-radius: 50%;object-fit:cover">
            {{ $seller->username }}
        </p>
        <!--Give Bonus-->
        {{--  <button class="btn btn-sm btn-success bg-green border-0 me-2" data-bs-toggle="modal"
            data-bs-target="#modalBonus" title="Giv Bonus" type="button"> Give Bonus</button>  --}}
        @if ($isCompleted != 1)
            <button class="btn btn-sm btn-success bg-green border-0 me-2 giveBonus" title="Giv Bonus" type="button">
                Give Bonus</button>
        @endif
        <div class="modal fade" id="modalBonus" tabindex="-1" aria-labelledby="modalBonusLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="max-width:500px">
                    <div class="modal-header bg-green text-white py-2 px-4">
                        <h1 class="modal-title fs-5" id="modalBonus"> Send Bonus </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.buyer.store.job.bonus') }}" method="post">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $encJobId }}">
                            <div class="mb-3">
                                <label for="description" class="form-label">Bonus
                                    Amount</label>
                                <input type="number" class="form-control" name="bonus_amount"
                                    placeholder="Bonus Amount" required>
                            </div>
                            <div>
                                <label for="description" class="form-label">Add Note
                                    (Optional)</label>
                                <textarea class="form-control" name="description" id="description" rows="2"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="submit" class="btn btn-sm btn-success">Send</button>
                    </div>
                    </form>

                </div>
            </div>
        </div> <!--Give Bonus End-->

        <!--End Contract-->
        @if ($isCompleted == 1)
            <!-- Display 'Contract Ended' button with green background and red text if completed -->
            <button class="btn btn-sm bg-lsgreen border text-danger" title="Contract Ended">
                <i class="fa-regular fa-circle-check"></i> Contract Ended
            </button>
        @else
            <!-- Display 'End Contract' button if not completed -->
            {{--  <button class="btn btn-sm btn-dark border-0" data-bs-toggle="modal" data-bs-target="#modalContract"  --}}
            <button class="btn btn-sm btn-dark border-0 endContract" onclick="customConfirm()" title="End Contract">
                <i class="fa-regular fa-circle-xmark"></i> End Contract
            </button>
            @include('modals.rating_modal')
        @endif


        <!--Add Funds-->
        {{-- <button class="btn btn-sm btn-success ms-2" data-bs-toggle="modal" data-bs-target="#modalFunds"
            title="Add Funds"> <i class="far fa-plus-square"></i> Funds</button>
        <div class="modal fade" id="modalFunds" tabindex="-1" aria-labelledby="modalFundsLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="max-width:500px">
                    <div class="modal-header bg-success text-white py-2 px-4">
                        <h1 class="modal-title fs-5" id="modalReject"> Deposit Funds </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3 row border-bottom">
                                <label for="exampleFormControlTextarea1" class="form-label col-10">Total
                                    Deposit</label>
                                <div class="col-2 fw-bold"> $200 </div>
                            </div>
                            <div class="mb-3 row border-bottom">
                                <label for="exampleFormControlTextarea1" class="form-label col-9 pt-2">Add
                                    Funds</label>
                                <div class="col-3">
                                    <textarea class="form-control mb-3" id="exampleFormControlTextarea1" rows="1"> </textarea>
                                </div>
                            </div>
                            <div class="row">
                                <label for="exampleFormControlTextarea1"
                                    class="form-label col-10">Balance</label>
                                <div class="col-2 fw-bold"> $200 </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-sm btn-success">Add</button>
                    </div>
                </div>
            </div>
        </div> <!--Add Fund End--> --}}
    </div>
</div><!--row end-->

<!-- Custom Confirm Modal -->
<div class="modal fade" id="customConfirmModal" tabindex="-1" aria-labelledby="customConfirmLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="customConfirmLabel">Confirmation</h5>
            </div>
            <div class="modal-body" id="customConfirmMessage">
                <!-- Message will be injected -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="customConfirmYes">Yes</button>
                <button type="button" class="btn btn-danger" id="customConfirmNo">No</button>
            </div>
        </div>
    </div>
</div>



<script>
    function customConfirm() {
        $('#customConfirmMessage').text("Are you sure want to give bonus ?");
        $('#customConfirmModal').modal('show');
        $('#customConfirmYes').off('click').on('click', function() {
            $('#customConfirmModal').modal('hide');
            $('#modalBonus').modal('show');
        });
        $('#customConfirmNo').off('click').on('click', function() {
            $('#customConfirmModal').modal('hide');
            $('#modalContract').modal('show');
        });
    }



    $(document).ready(function() {

        $(document).on('click', '.giveBonus,.customConfirmNo', function() {
            $('#customConfirmModal').modal('hide');
            $('#modalBonus').modal('show');
        });


        $(document).on('click', '.giveBonus', function() {
            $('#modalBonus').modal('show');
        });
    });
</script>
