@extends('admin.layouts.app')
<style>
    .chat-container {
        max-height: 400px;
        overflow-y: auto;
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
    }

    .chat-bubble {
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 10px;
        max-width: 75%;
        word-wrap: break-word;
    }

    .chat-buyer {
        background-color: #e2f0d9;
        align-self: flex-start;
    }

    .chat-seller {
        background-color: #d9eaf7;
        align-self: flex-end;
    }

    .chat-meta {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 2px;
    }

    .chat-wrapper {
        display: flex;
        flex-direction: column;
    }
</style>
@section('panel')
<div class="row mb-none-30">
    <div class="col-xl-3 col-lg-5 col-md-5 col-sm-12">
        <div class="card b-radius--10 overflow-hidden box--shadow1">
            <div class="card-body p-0">
                <div class="p-3 bg--white">
                    <div>
                        {{-- <img src="{{ getImage('assets/images/job/'. $job->image, '590x300')}}" alt="@lang('Job image')"
                             class="b-radius--10 w-100"> --}}
                    </div>
                    <div class="mt-10">
                        <h4 class="">{{__($job->title)}}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
            <div class="card-body">
                <h5 class="mb-20 text-muted">@lang('Buyer Information')</h5>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Username')
                        <span class="font-weight-bold">{{__($job->user->username)}}</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('User Status')
                        @if($job->user->status == 1)
                            <span class="badge badge-pill bg--success">@lang('Active')</span>
                        @elseif($job->user->status == 0)
                            <span class="badge badge-pill bg--danger">@lang('Pending')</span>
                        @endif
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Job Status')
                        @if($job->status == 1)
                            <span class="badge badge-pill bg--success">@lang('Active')</span>
                        @elseif($job->status == 0)
                            <span class="badge badge-pill bg--danger">@lang('Pending')</span>
                        @endif
                    </li>

                     <li class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Balance')
                        <span class="font-weight-bold">{{getAmount($job->user->balance)}}  {{__($general->cur_text)}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-xl-9 col-lg-7 col-md-7 col-sm-12">

        <div class="row mb-30">
            <div class="col-lg-12 mt-2">
                <div class="card border--dark">
                    <h5 class="card-header bg--dark">@lang('Job Information')</h5>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                              @lang('Category')
                              <span>{{__($job->category->name)}}</span>
                            </li>
                            @if(!empty($job->sub_category_id))
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                  @lang('Sub Category')
                                  <span>{{__($job->subCategory->name)}}</span>
                                </li>
                            @endif

                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                              @lang('Budget')
                              <span>{{ $general->cur_sym }}
                                @if ($job->budgettype == 'hourly')
                                    {{ getAmount( $job->amount * $job->weekly_hours )}}
                                @elseif ($job->budgettype == 'fixed')
                                    {{ getAmount($job->amount) }}
                                @else
                                    0
                                @endif</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                              @lang('Delivery Time')
                                <span>{{$job->delivery_time}} @lang('Days')</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                              @lang('Last Update')
                              <span>{{diffforhumans($job->updated_at)}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="card border--dark">
                    <h5 class="card-header bg--dark">@lang('Description')</h5>
                    <div class="card-body">
                        @php echo $job->description @endphp
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-30">
             <div class="col-lg-12">
                <div class="card border--dark">
                    <h5 class="card-header bg--dark">@lang('Skill')</h5>
                    <div class="card-body">
                       @if (isset($job->skill) || isset($job->extraSkills))
                        @php
                            $counter = 1;
                        @endphp

                        <ul>
                            <div class="row">
                                {{-- Regular Skills --}}
                                @if (!empty($job->skill))
                                    @foreach($job->skill as $value)
                                        <div class="col-md-4 mb-2">
                                            <li class="font-weight-bold">{{ $counter++ }}. {{ __($value) }}</li>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- Extra Skills --}}
                                @if (!empty($job->extraSkills))
                                    @foreach($job->extraSkills as $skill)
                                        <div class="col-md-4 mb-2">
                                            <li class="font-weight-bold">{{ $counter++ }}. {{ $skill?->name }}</li>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </ul>
                    @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-30">
            <div class="col-lg-12">
                <div class="card border--dark">
                    <h5 class="card-header bg--dark">@lang('Requirements')</h5>
                    <div class="card-body">
                        @php echo $job->requirements @endphp
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card border--dark">
                    <h5 class="card-header bg--dark">@lang('Chat History')</h5>
                    <div class="card-body chat-container">
                        @if ($messages->isEmpty())
                            <p class="text-center text-muted mb-0">No negotiations found.</p>
                        @else
                            <div class="chat-wrapper">
                                @foreach ($messages as $msg)
                                    @php
                                        $isBuyer = $msg->from_id == $buyerId;
                                    @endphp
                                    <div class="d-flex {{ $isBuyer ? 'justify-content-start' : 'justify-content-end' }}">
                                        <div class="chat-bubble {{ $isBuyer ? 'chat-buyer' : 'chat-seller' }}">
                                            <strong>{{ $msg->sender_name }}</strong><br>
                                            {{ $msg->body }}
                                            <div class="chat-meta">{{ \Carbon\Carbon::parse($msg->created_at)->format('d M Y, h:i A') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(!empty($job->attachments) && count($job->attachments) > 0)
            <div class="row mb-30">
                <div class="col-lg-12">
                    <div class="card border--dark">
                        <h5 class="card-header bg--dark">@lang('Attachments')</h5>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($job->attachments as $attachment)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa fa-file text-primary"></i>
                                            <span class="text-truncate ml-2" style="max-width: 250px;">
                                                {{ basename($attachment->image_url) }}
                                            </span>
                                        </div>

                                        <a href="{{ url('storage/' . $attachment->image_url) }}" 
                                            target="_blank" 
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-download me-1"></i> Download
                                        </a>

                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.job.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i>@lang('Go Back')</a>
@endpush
