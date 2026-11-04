<div class="container">
    @if ($errorMessage)
        <div class="row  px-4 text-secondary fs-14">
            <div class="col-12 text-center">
                <div class="alert">
                    {{ $errorMessage }}
                </div>
            </div>
        </div>
    @elseif ($invoices->isEmpty())
        <div class="row  px-4 text-secondary fs-14">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    No records found.
                </div>
            </div>
        </div>
    @else
        @foreach ($invoices as $invoice)
            @if ($loop->first)
                <hr class="mt-0"> <!-- Plain HR for the first row -->
            @else
                <hr class="mt-3"> <!-- HR with mt-3 for subsequent rows -->
            @endif
            <div class="row px-4 text-secondary fs-14"> <!-- Invoice start -->
                <div class="col-6 col-sm-4 col-md-3 px-md-0">
                    <strong>{{ $invoice['isCommission'] ? 'Commission #' : 'Invoice # ' }}</strong> {{ $invoice['invoiceNumber'] }}
                </div>
                <div class="col-sm-8 col-md-2 text-dark fw-bold">
                    {{ $invoice['sellerName'] }}
                </div>
                <div class="col-6 col-sm-3 col-md-2 text-secondary">
                    {{ $invoice['invoiceDate'] }}
                </div>
                <div class="col-6 col-sm-3 col-md-2 
                    {{ str_contains(strtolower($invoice['details']), 'platform') ? 'text-danger' : 'text-secondary' }}">
                    <strong>Invoice Total:</strong> ${{ $invoice['amount'] }}
                </div>
                <div class="col-6 col-sm-3 col-md-1">
                    <a class="btn btn-sm text-white border-0 btn-success bg-green py-0 px-1 fs-12" href="#">Paid</a>
                </div>
                <div class="col-6 col-sm-3 col-md-1">
                    <a class="btn btn-sm text-white border-0 btn-success bg-dgreen2 py-0 px-1 fs-12" href="{{ route('user.seller.workspace.invoice.print', ['invoiceNumber' => $invoice['invoiceNumber']]) }}" target="_blank">View</a>
                </div>
            </div> <!-- Invoice End -->
        @endforeach
    @endif
</div>
