@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Level Name')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Last Update')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($ranks as $rank)
                                <tr>
                                    <td data-label="@lang('Level Name')"><span class="font-weight-bold">{{__($rank->level)}}</span></td>
                                    <td data-label="@lang('Amount')">
                                        <span class="font-weight-bold">{{$general->cur_sym}}{{getAmount($rank->amount)}}</span>
                                    </td>
                                    <td data-label="@lang('Status')">
                                        @if($rank->status == 1)
                                            <span class="badge badge--success">@lang('Enable')</span>
                                        @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                        @endif
                                    </td>
                                  
                                    <td data-label="@lang('Last Update')">
                                        {{ showDateTime($rank->updated_at) }} <br> {{ diffForHumans($rank->updated_at) }}
                                    </td>

                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" class="icon-btn btn--primary ml-1 updateRank"
                                            data-id="{{$rank->id}}" 
                                            data-level="{{$rank->level}}" 
                                            data-amount="{{getAmount($rank->amount)}}" 
                                            data-status="{{$rank->status}}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($ranks) }}
                </div>
            </div>
        </div>
    </div>


    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Rank')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.rank.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label" class="form-control-label font-weight-bold">@lang('Level Name')</label>
                            <input type="text" class="form-control form-control-lg" name="level" placeholder="@lang("Enter Level")"  maxlength="40" required="">
                        </div>

                        <div class="form-group">
                            <label for="amount" class="form-control-label font-weight-bold">@lang('Amount')</label>
                            <div class="input-group mb-3">
                                  <input type="text" id="amount" class="form-control form-control-lg" placeholder="@lang('Enter Amount')" name="amount" aria-label="Recipient's username" aria-describedby="basic-addon2" required="">
                                  <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">{{$general->cur_text}}</span>
                                  </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Status') </label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="updatRankModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Rank Update')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.rank.update')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="label" class="form-control-label font-weight-bold">@lang('Level Name')</label>
                            <input type="text" class="form-control form-control-lg" name="level" placeholder="@lang("Enter Level")"  maxlength="40" required="">
                        </div>

                        <div class="form-group">
                            <label for="amount" class="form-control-label font-weight-bold">@lang('Amount')</label>
                            <div class="input-group mb-3">
                                  <input type="text" id="amount" class="form-control form-control-lg" placeholder="@lang('Enter Amount')" name="amount" aria-label="Recipient's username" aria-describedby="basic-addon2" required="">
                                  <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">{{$general->cur_text}}</span>
                                  </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Status') </label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary"><i class="fa fa-fw fa-paper-plane"></i>@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="javascript:void(0)" class="btn btn-sm btn--primary box--shadow1 text--small addRank" ><i class="las la-plus"></i>@lang('Add Rank')</a>
@endpush

@push('script')
    <script>
        "use strict";
        $('.addRank').on('click', function() {
            $('#addModal').modal('show');
        });

        $('.updateRank').on('click', function () {
            var modal = $('#updatRankModel');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('input[name=level]').val($(this).data('level'));
            modal.find('input[name=amount]').val($(this).data('amount'));
            var data = $(this).data('status');
            if(data == 1){
                modal.find('input[name=status]').bootstrapToggle('on');
            }else{
                modal.find('input[name=status]').bootstrapToggle('off');
            }
            modal.modal('show');
        });
    </script>
@endpush
