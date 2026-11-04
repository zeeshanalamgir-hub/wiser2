@extends('admin.layouts.app')
@section('panel')
    <!-- Filter Form -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="GET" class="form-row align-items-end">
                        <div class="form-group col-md-3">
                            <label>@lang('Search')</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="@lang('Name')" 
                                   value="{{ request()->search }}">
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label>@lang('Category')</label>
                            <select name="category" class="form-control">
                                <option value="">@lang('All Categories')</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ request()->category == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label>@lang('Budget Type')</label>
                            <select name="budget_type" class="form-control">
                                <option value="">@lang('All Types')</option>
                                <option value="fixed" {{ request()->budget_type == 'fixed' ? 'selected' : '' }}>
                                    @lang('Fixed')
                                </option>
                                <option value="hourly" {{ request()->budget_type == 'hourly' ? 'selected' : '' }}>
                                    @lang('Hourly')
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <button type="submit" class="btn btn--primary btn-block">
                                <i class="las la-filter"></i> @lang('Filter')
                            </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Your existing table code here -->
     <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Job Title')</th>
                                    <th>@lang('Buyer')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Category / SubCategory')</th>
                                    <th>@lang('Budget')</th>
                                    <th>@lang('Delivery Time')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobs as $job)
                                    <tr @if ($loop->odd) class="table-light" @endif>
                                        <td data-label="@lang('Title')">
                                            <div class="user">
                                                {{-- <div class="thumb"><img
                                                        src="{{ getImage('assets/images/job/' . $job->image, '590x300') }}"
                                                        alt="@lang('image')"></div> --}}
                                                <span class="name">{{ __(str_limit($job->title, 20)) }}</span>
                                            </div>
                                        </td>

                                        <td data-label="@lang('Buyer')">
                                            {{-- <span class="font-weight-bold">{{ @$job->user->fullname }}</span> --}}
                                            <br>
                                            <span class="small">
                                                @if (@$job->user->id == '')
                                                @else
                                                    <a
                                                        href="{{ route('admin.users.detail', $job->user_id) }}">{{ $job->user->username }}</a>
                                            </span>
                                @endif
                                </td>

                                <td data-label="@lang('Type')">
                                    <span>{{ __($job->budgettype) }}</span>
                                </td>

                                <td data-label="@lang('Category / SubCategory')">
                                    <span class="font-weight-bold">{{ __(optional($job->category)->name) }}</span>
                                    <br>
                                    @if ($job->sub_category_id)
                                        <span>{{ __($job->subCategory->name) }}</span>
                                    @else
                                        <span>@lang('N/A')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Budget')">
                                    <span class="font-weight-bold">
                                        {{ $general->cur_sym }}
                                        @if ($job->budgettype == 'hourly')
                                            {{ getAmount($job->amount * $job->weekly_hours)}}
                                        @elseif ($job->budgettype == 'fixed')
                                            {{ getAmount($job->amount) }}
                                        @else
                                            0
                                        @endif
                                    </span>
                                </td>


                                <td data-label="@lang('Delivery Time')">
                                    <span class="font-weight-bold">{{ diffForHumans($job->delivery_date) }}</span>
                                </td>

                                <td data-label="@lang('Status')">
                                    <span class="font-weight-normal badge--warning">@lang('Closed')</span>
                                </td>

                                <td data-label="@lang('Last Update')">
                                    <span>{{ showDateTime($job->updated_at) }}</span>
                                    <br>
                                    {{ diffforhumans($job->updated_at) }}
                                </td>

                                <td data-label="@lang('Action')">
                                    <button type="button" class="icon-btn btn--primary ml-1 view-transaction" 
                                        data-toggle="tooltip" data-original-title="@lang('View Transaction')"
                                        data-job-id="{{ $job->id }}"
                                        data-transactions='@json($job->booking->transactions ?? [])'>
                                        <i class="las la-eye"></i> @lang('Details')
                                    </button>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($jobs) }}
                </div>
            </div>
        </div>
    </div>
@endsection

<div class="modal fade" id="transactionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="transactionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionDetailsModalLabel">
                        <i class="las la-file-invoice-dollar"></i> @lang('Transaction Details')
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>@lang('Transaction ID')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Date')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactionDetailsBody">
                                        <!-- Transaction details will be loaded here via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">
                        <i class="las la-times"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>

@push('script')
    <script>
        'use strict';

        // Transaction details viewer
        $('.view-transaction').on('click', function() {
            var transactions = $(this).data('transactions');
            var modal = $('#transactionDetailsModal');
            var tbody = $('#transactionDetailsBody');
            var currencySym = '{{ $general->cur_sym }}';
            
            // Clear previous data
            tbody.empty();
            
            if (transactions && transactions.length > 0) {
                $.each(transactions, function(index, transaction) {
                    // Format date
                    var date = 'N/A';
                    if (transaction.created_at) {
                        var d = new Date(transaction.created_at);
                        date = d.toLocaleDateString() + ' ' + d.toLocaleTimeString();
                    }
                    
                    var row = '<tr>' +
                        '<td><strong>' + (transaction.trx || 'N/A') + '</strong></td>' +
                        '<td><span class="text--success">' + currencySym + ' ' + (parseFloat(transaction.amount).toFixed(2) || '0.00') + '</span></td>' +
                        '<td>' + date + '</td>' +
                        '</tr>';
                    
                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="5" class="text-center text-muted"><i class="las la-info-circle"></i> @lang("No transaction found for this job")</td></tr>');
            }
            
            modal.modal('show');
        });

        // Optional: If you want to add tooltip initialization
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush

@push('style')
    <style>
        .modal-lg {
            max-width: 800px;
        }
        .table thead.thead-dark th {
            background-color: #343a40;
            color: #fff;
        }
        .text--success {
            color: #28c76f;
            font-weight: 600;
        }
        .icon-btn.btn--primary {
            background-color: #5e50ee;
            border-color: #5e50ee;
        }
        .icon-btn.btn--primary:hover {
            background-color: #4a3fd1;
            border-color: #4a3fd1;
        }
    </style>
@endpush