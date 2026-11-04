<!DOCTYPE html>
<html>
    <head>
        <title>Wiserlance Invoice</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    </head>
    
    <body>
     @php
        $job=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
        $seller=DB::table('users')->where('id',$accepteddata->seller_id)->first();
        $buyer=DB::table('users')->where('id',$job->user_id)->first();
       
        @endphp
        <div class="row  p-3 mt-3"> 
            
            <div class="col-lg-12 mt-3">
                <h5>INVOICE </h5>
            </div>
            <div class="col-lg-12 mt-3">
                <table class="table ">
                    <tbody>
                        <tr  style="width: 100%;border-color:white ;">
                            <td style="width: 50%; border: 1px solid white !important;"><span class="font-weight-bold">Invoice Number</span><br>
                <span >#12345</span><br></td>
                <td></td>
                            <td style="width: 50%; border: 1px solid white !important;">
                                 <span class="font-weight-bold">Date Created</span><br>
                <span >{{showDateTime($accepteddata->created_at, ('d M Y'))}}</span><br>
                            </td>
                        </tr>

                        <tr  style="width: 100%;border-color:white ;">
                            <td style="width: 50%; border: 1px solid white !important;"><span class="font-weight-bold">Buyer's Detail</span><br>
                <span >Name: {{$buyer->username}}</span><br>
                <span >Address:  {{$buyer->country}}</span><br></td>
               <td></td>
                            <td style="width: 50%; border: 1px solid white !important;">
                                <span class="font-weight-bold">Seller's Detail</span><br>
                <span >Name: {{$seller->username}}</span><br>
                <span >Address: {{$seller->country}}</span><br>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <style type="text/css">
            .table tr th, .table tr td {
            
            padding: 5px 8px !important;
            vertical-align: top;
            }
            </style>
            <div class="col-lg-12 mt-4">
                <table class="table ">
                    
                    <tbody>
                        
                        
                        @if($job->budgettype == 'per_hour')
                        @php
                        @$counteroffer=DB::table('seller_counter_offers')->where('offer_id',$accepteddata->offer_id)->first();
                        @$counterofferdata=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->first();
                        $works=DB::table('job_work_delivery_houly_offer')->where('data_id',@$counterofferdata->id)->where('job_id',$accepteddata->job_id)->where('offer_id',$accepteddata->offer_id)->get();
                        $allwork=DB::table('job_work_delivery_houly_offer')->where('data_id',@$counterofferdata->id)->where('job_id',$accepteddata->job_id)->where('offer_id',$accepteddata->offer_id)->where('status',5)->get();
                        $total=$allwork->sum('amount');
                        @endphp
                        <tr>
                            <th  scope="row">Particulars</th>
                            <th  scope="row">Rate ($)</th>
                            <th  scope="row">Quantity</th>
                            <th  scope="row">Unit</th>
                            <th  scope="row">Amount ($)</th>
                            
                        </tr>
                        @foreach($works as $work)
                        <tr>
                            <td>{{$work->title}} {{showDateTime($work->date_start, ('d M Y'))}} - {{showDateTime($work->date_end, ('d M Y'))}}</td>
                            <td>{{$counterofferdata->amount}}</td>
                            <td> {{$work->time}}.{{$work->minutes}}</td>
                            <td>hourly</td>
                            @if($work->status == 5)
                            <td>{{$work->total_cost}}</td>
                            @else
                            <td>0</td>
                            @endif
                            
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Invoice Total</td>
                            <td style="border-bottom:1px solid black;border-top:1px solid black;">${{$total}}</td>
                            
                        </tr>
                        <!--  <tr>
                            <th style="width:20%;" scope="row">Job Budget:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount($job->amount)}} /hour</td>
                            
                        </tr>
                        <tr>
                            <th style="width:20%;" scope="row">Accepted amount:</th>
                            <td style="width:80%;text-align: right;">{{$general->cur_sym}}{{showAmount(@$counterofferdata->amount)}} /hour</td>
                            
                        </tr> -->
                        
                        @else
                        
                        
                        @php
                        @$counteroffer=DB::table('seller_counter_offers')->where('offer_id',$accepteddata->offer_id)->first();
                        @$counterofferdata=DB::table('seller_counter_offers_data')->where('disputed_amount','0')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->get();
                        $counterofferdataorg=DB::table('seller_counter_offers_data')->where('counter_offer_id',$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->get();
                        $payed=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->where('status','5')->where('disputed_amount','0')->get();
                        $payed1=DB::table('seller_counter_offers_data')->where('counter_offer_id',@$counteroffer->id)->where('offer_id',$accepteddata->offer_id)->where('status','5')->where('disputed_amount','!=','0')->get();
                        
                        
                        $all=(@$payed1->sum('disputed_amount')+$payed->sum('amount'));
                        $Jobname=DB::table('jobs')->where('id',$accepteddata->job_id)->first();
                        @endphp
                        <tr>
                            <th  scope="row">Description</th>
                            <th  scope="row">Unit cost</th>
                            <th  scope="row">Qty/HR Rate</th>
                            <th  scope="row">Amount</th>
                            
                        </tr>
                        @foreach($counterofferdataorg as $cd)
                        <tr>
                            <td>
                                {{$cd->title}}
                                
                            </td>
                            <td>$ {{$cd->amount}}</td>
                            <td>1</td>
                            @if($cd->status == 5)
                            @if($cd->disputed_amount == 0)
                            <td>$ {{$cd->amount}}</td>
                            @else
                            <td>$ {{$cd->disputed_amount}}</td>
                            @endif
                            @else
                            <td>$ 0</td>
                            @endif
                        </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Invoice Total</td>
                            <td style="border-bottom:1px solid black;border-top:1px solid black;">$ {{$all}}</td>
                            
                        </tr>
                        
                        
                        @endif
                        
                        
                       
                        
                    </tbody>
                </table>
            </div>
            <div class="col-lg-12 mt-5">
                <p class="font-weight-bold">This is a computer-generated invoice.</p>
            </div>
        </div>   
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    </html>