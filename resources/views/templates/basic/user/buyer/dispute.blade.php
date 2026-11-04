@extends('templates.basic.layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="row">
        <h5 class="text-dgreen2 mt-2">Disputes</h5>

        <div class="col-12">
            <!-- <h5 class="text-dgreen2 ms-2">Disputes</h5> -->

            <div class="card shadow-sm border mt-3 p-4">
                @if (isset($disputes) && count($disputes) > 0)
                    @foreach ($disputes as $dispute)
                        <div class="row pb-3 border-bottom fs-14 mt-3"> <!-- Dispute-->
                            <div class="col-md-9">
                                <a href="#" class="links-c2">
                                    <h5>{{ $dispute->job_title }}</h5> <!-- Dynamic Job Title -->
                                </a>
                                <ul class="nav">
                                    <li>Amount: <b>${{ $dispute->dispute_amount ?? $dispute->disputed_amount }}</b></li>
                                    <li><span class="ms-sm-2">Due Date:
                                            <b>{{ \Carbon\Carbon::parse($dispute->created_at)->format('d M, Y') }}</b></span>
                                    </li>
                                    @if ($dispute->status == 0)
                                        <!-- Disputed Status -->
                                        <li class="mt-2 mt-sm-0">
                                            <span
                                                class="text-success ms-0 ms-sm-2 bg-danger text-white px-2 py-1 fs-12 rounded-1">Disputed</span>
                                        </li>
                                    @elseif($dispute->status == 1)
                                        <!-- Solved Status -->
                                        <li class="mt-2 mt-sm-0">
                                            <span
                                                class="text-success ms-0 ms-sm-2 bg-green text-white px-2 py-1 fs-12 rounded-1">Dispute
                                                Settled</span>
                                        </li>
                                        @if ($dispute->booking_status == 4)
                                            <!-- Refund Status -->
                                            <li class="mt-2 mt-sm-0">
                                                <span
                                                    class="text-danger ms-0 ms-sm-2 bg-danger text-white px-2 py-1 fs-12 rounded-1">Refund</span>
                                            </li>
                                        @elseif ($dispute->booking_status == 3)
                                            <!-- Paid Status -->
                                            <li class="mt-2 mt-sm-0">
                                                <span
                                                    class="text-success ms-0 ms-sm-2 bg-green text-white px-2 py-1 fs-12 rounded-1">Paid</span>
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            </div>

                            <div class="col-md-3 pt-3">
                                <ul class="nav float-md-end">
                                    <li>
                                        <button class="btn btn-sm btn-outline-danger float-end"
                                            onclick="location.href='{{ route('user.buyer.dispute.view', ['type' => $dispute->dispute_type, 'id' => encrypt($dispute->id)]) }}';">
                                            <i class="fa-solid fa-eye"></i> View Dispute
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div> <!-- Dispute End -->
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-dgreen2 ms-2">No Disputes Found.</h5>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
