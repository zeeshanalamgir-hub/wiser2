@foreach ($jobWorkDeliveryBids as $jobWorkDeliveryBid)
<div class="row pt-3 px-4 text-secondary fs-14"> <!-- Invoice start -->
    <div class="col-6 col-sm-4 col-md-2 px-md-0"> Invoice# {{ $orderNumber  }}</div>
    <div class="col-sm-8 col-md-4 text-dark  fw-bold">{{ $sellerNames }}</div>
    <div class="col-6 col-sm-3 col-md-2 text-secondary">{{ $jobWorkDeliveryBid->created_at->format('d-m-Y') }}</div>
    <div class="col-6 col-sm-3 col-md-2 text-secondary"> Total: ${{ $amount }}</div>
    <div class="col-6 col-sm-3 col-md-1">
        <a class="btn btn-sm text-white border-0 btn-success bg-green py-0 px-1 fs-12" href="#">Paid</a>
    </div>
    <div class="col-6 col-sm-3 col-md-1">
        <a class="btn btn-sm text-white border-0 btn-success bg-dgreen2 py-0 px-1" href="#">View</a>
    </div>
    <hr class="mt-3">
</div> <!-- Invoice End -->
@endforeach
