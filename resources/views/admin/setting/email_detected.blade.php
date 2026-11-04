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
                                    <th>@lang('Message')</th>
                                    <th>@lang('Sender')</th>
                                    <th>@lang('Reciever')</th>
                                    <th>@lang('Date & Time')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($messages as $message)
                                <tr>
                                     <td data-label="@lang('Message')">
                                        <div class="user">
                                            <span class="name">{{__($message->body)}}</span>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Sender')">
                                        <div class="user">
                                            <span class="name">{{__($message->sender->firstname)}}</span>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Reciever')">
                                        <div class="user">
                                            <span class="name">{{__($message->Reciever->firstname)}}</span>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Date & Time')">
                                        <div class="user">
                                            <span class="name">{{__($message->created_at->format('Y-m-d H:i:s'))}}</span>
                                        </div>
                                    </td> 
                                    <td data-label="@lang('Action')">
                                        <a href="{{route('admin.user.chat', [$message->sender->id, $message->Reciever->id])}}"  class="icon-btn mr-2" data-toggle="tooltip" title="" data-original-title="@lang('Edit')">
                                            <i class="las la-edit text--shadow"></i>
                                        </a>
                                    </td>
                                    
                               
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
                    {{ paginateLinks($messages) }}
                </div>
            </div>
        </div>
    </div>

@endsection

