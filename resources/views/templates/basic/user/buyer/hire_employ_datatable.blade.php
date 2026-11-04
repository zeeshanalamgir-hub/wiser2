@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job')
@section('content')
    <div class="modal fade" id="completedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Job Completed Confirmation')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.job.completed') }}">
                        @csrf
                        <input type="hidden" name="id">
                        <p>@lang('Are you sure to add this job to completed')</p>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="approvedModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Accept Proposal')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.proposal.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="confirm" value="approved">
                        @lang('Are you sure to approved this work delivery')
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="loginmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl login-pop-form" role="document">
            <div class="modal-content" id="loginmodal">
                <div class="modal-headers">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="ti-close"></span>
                    </button>
                </div>
                <div class="modal-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="m-0 ft-regular">@lang('Cancel Proposal')</h2>
                    </div>
                    <form method="POST" action="{{ route('user.proposal.approved') }}">
                        @csrf
                        <input type="hidden" name="order_number">
                        <input type="hidden" name="confirm" value="cancel">
                        <p>@lang('Are you sure to cancel this Proposal')</p>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-md full-width theme-bg text-light fs-md ft-medium">@lang('Confirm')</button>
                        </div>
                        <div class="form-group text-center mb-0">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <div class="dashboard-content">
        <div class="dashboard-tlbar d-block mb-5">
            <div class="row">
                <div class="colxl-12 col-lg-12 col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item text-muted"><a href="landing2.html">Home</a></li>
                            <li class="breadcrumb-item text-muted"><a href="dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="dashboard-manage-service.html" class="theme-cl">Manage
                                    Services</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <style type="text/css">
            .btn {
                padding: 7px;
            }
        </style>
        <div class="dashboard-widg-bar d-block">
            <div class="row">
                <div class="col-lg-12 pb-3">

                    <a href="{{ route('user.buyer.hire.employ') }}"
                        class="btn rounded theme-bg text-light fs-sm ft-medium ">Open jobs</a>
                    <a href="{{ route('user.buyer.offered.employ') }}"
                        class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2 ">Offered jobs</a>
                    <a href="{{ route('user.buyer.hire.employ.completed') }}"
                        class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Completed</a>
                    <a href="{{ route('user.buyer.hire.employ.closed') }}"
                        class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2">Closed</a>
                    <!-- <a href="#" class="btn rounded theme-bg-light theme-cl fs-sm ft-medium ml-2" >Chat with seller</a> -->
                </div>

            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class=" bg-white">
                        <table id="example" class="row-border" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                    <th>Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>61</td>
                                    <td>2011-04-25</td>
                                    <td>$320,800</td>
                                </tr>
                                <tr>
                                    <td>Garrett Winters</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>63</td>
                                    <td>2011-07-25</td>
                                    <td>$170,750</td>
                                </tr>
                                <tr>
                                    <td>Ashton Cox</td>
                                    <td>Junior Technical Author</td>
                                    <td>San Francisco</td>
                                    <td>66</td>
                                    <td>2009-01-12</td>
                                    <td>$86,000</td>
                                </tr>
                                <tr>
                                    <td>Cedric Kelly</td>
                                    <td>Senior Javascript Developer</td>
                                    <td>Edinburgh</td>
                                    <td>22</td>
                                    <td>2012-03-29</td>
                                    <td>$433,060</td>
                                </tr>
                                <tr>
                                    <td>Airi Satou</td>
                                    <td>Accountant</td>
                                    <td>Tokyo</td>
                                    <td>33</td>
                                    <td>2008-11-28</td>
                                    <td>$162,700</td>
                                </tr>
                                <tr>
                                    <td>Brielle Williamson</td>
                                    <td>Integration Specialist</td>
                                    <td>New York</td>
                                    <td>61</td>
                                    <td>2012-12-02</td>
                                    <td>$372,000</td>
                                </tr>
                                <tr>
                                    <td>Herrod Chandler</td>
                                    <td>Sales Assistant</td>
                                    <td>San Francisco</td>
                                    <td>59</td>
                                    <td>2012-08-06</td>
                                    <td>$137,500</td>
                                </tr>
                                <tr>
                                    <td>Rhona Davidson</td>
                                    <td>Integration Specialist</td>
                                    <td>Tokyo</td>
                                    <td>55</td>
                                    <td>2010-10-14</td>
                                    <td>$327,900</td>
                                </tr>
                                <tr>
                                    <td>Colleen Hurst</td>
                                    <td>Javascript Developer</td>
                                    <td>San Francisco</td>
                                    <td>39</td>
                                    <td>2009-09-15</td>
                                    <td>$205,500</td>
                                </tr>
                                <tr>
                                    <td>Sonya Frost</td>
                                    <td>Software Engineer</td>
                                    <td>Edinburgh</td>
                                    <td>23</td>
                                    <td>2008-12-13</td>
                                    <td>$103,600</td>
                                </tr>
                                <tr>
                                    <td>Jena Gaines</td>
                                    <td>Office Manager</td>
                                    <td>London</td>
                                    <td>30</td>
                                    <td>2008-12-19</td>
                                    <td>$90,560</td>
                                </tr>
                                <tr>
                                    <td>Quinn Flynn</td>
                                    <td>Support Lead</td>
                                    <td>Edinburgh</td>
                                    <td>22</td>
                                    <td>2013-03-03</td>
                                    <td>$342,000</td>
                                </tr>
                                <tr>
                                    <td>Charde Marshall</td>
                                    <td>Regional Director</td>
                                    <td>San Francisco</td>
                                    <td>36</td>
                                    <td>2008-10-16</td>
                                    <td>$470,600</td>
                                </tr>
                                <tr>
                                    <td>Haley Kennedy</td>
                                    <td>Senior Marketing Designer</td>
                                    <td>London</td>
                                    <td>43</td>
                                    <td>2012-12-18</td>
                                    <td>$313,500</td>
                                </tr>
                                <tr>
                                    <td>Tatyana Fitzpatrick</td>
                                    <td>Regional Director</td>
                                    <td>London</td>
                                    <td>19</td>
                                    <td>2010-03-17</td>
                                    <td>$385,750</td>
                                </tr>
                                <tr>
                                    <td>Michael Silva</td>
                                    <td>Marketing Designer</td>
                                    <td>London</td>
                                    <td>66</td>
                                    <td>2012-11-27</td>
                                    <td>$198,500</td>
                                </tr>
                                <tr>
                                    <td>Paul Byrd</td>
                                    <td>Chief Financial Officer (CFO)</td>
                                    <td>New York</td>
                                    <td>64</td>
                                    <td>2010-06-09</td>
                                    <td>$725,000</td>
                                </tr>
                                <tr>
                                    <td>Gloria Little</td>
                                    <td>Systems Administrator</td>
                                    <td>New York</td>
                                    <td>59</td>
                                    <td>2009-04-10</td>
                                    <td>$237,500</td>
                                </tr>
                                <tr>
                                    <td>Bradley Greer</td>
                                    <td>Software Engineer</td>
                                    <td>London</td>
                                    <td>41</td>
                                    <td>2012-10-13</td>
                                    <td>$132,000</td>
                                </tr>
                                <tr>
                                    <td>Dai Rios</td>
                                    <td>Personnel Lead</td>
                                    <td>Edinburgh</td>
                                    <td>35</td>
                                    <td>2012-09-26</td>
                                    <td>$217,500</td>
                                </tr>
                                <tr>
                                    <td>Jenette Caldwell</td>
                                    <td>Development Lead</td>
                                    <td>New York</td>
                                    <td>30</td>
                                    <td>2011-09-03</td>
                                    <td>$345,000</td>
                                </tr>
                                <tr>
                                    <td>Yuri Berry</td>
                                    <td>Chief Marketing Officer (CMO)</td>
                                    <td>New York</td>
                                    <td>40</td>
                                    <td>2009-06-25</td>
                                    <td>$675,000</td>
                                </tr>
                                <tr>
                                    <td>Caesar Vance</td>
                                    <td>Pre-Sales Support</td>
                                    <td>New York</td>
                                    <td>21</td>
                                    <td>2011-12-12</td>
                                    <td>$106,450</td>
                                </tr>
                                <tr>
                                    <td>Doris Wilder</td>
                                    <td>Sales Assistant</td>
                                    <td>Sydney</td>
                                    <td>23</td>
                                    <td>2010-09-20</td>
                                    <td>$85,600</td>
                                </tr>
                                <tr>
                                    <td>Angelica Ramos</td>
                                    <td>Chief Executive Officer (CEO)</td>
                                    <td>London</td>
                                    <td>47</td>
                                    <td>2009-10-09</td>
                                    <td>$1,200,000</td>
                                </tr>
                                <tr>
                                    <td>Gavin Joyce</td>
                                    <td>Developer</td>
                                    <td>Edinburgh</td>
                                    <td>42</td>
                                    <td>2010-12-22</td>
                                    <td>$92,575</td>
                                </tr>
                                <tr>
                                    <td>Jennifer Chang</td>
                                    <td>Regional Director</td>
                                    <td>Singapore</td>
                                    <td>28</td>
                                    <td>2010-11-14</td>
                                    <td>$357,650</td>
                                </tr>
                                <tr>
                                    <td>Brenden Wagner</td>
                                    <td>Software Engineer</td>
                                    <td>San Francisco</td>
                                    <td>28</td>
                                    <td>2011-06-07</td>
                                    <td>$206,850</td>
                                </tr>
                                <tr>
                                    <td>Fiona Green</td>
                                    <td>Chief Operating Officer (COO)</td>
                                    <td>San Francisco</td>
                                    <td>48</td>
                                    <td>2010-03-11</td>
                                    <td>$850,000</td>
                                </tr>
                                <tr>
                                    <td>Shou Itou</td>
                                    <td>Regional Marketing</td>
                                    <td>Tokyo</td>
                                    <td>20</td>
                                    <td>2011-08-14</td>
                                    <td>$163,000</td>
                                </tr>
                                <tr>
                                    <td>Michelle House</td>
                                    <td>Integration Specialist</td>
                                    <td>Sydney</td>
                                    <td>37</td>
                                    <td>2011-06-02</td>
                                    <td>$95,400</td>
                                </tr>
                                <tr>
                                    <td>Suki Burks</td>
                                    <td>Developer</td>
                                    <td>London</td>
                                    <td>53</td>
                                    <td>2009-10-22</td>
                                    <td>$114,500</td>
                                </tr>
                                <tr>
                                    <td>Prescott Bartlett</td>
                                    <td>Technical Author</td>
                                    <td>London</td>
                                    <td>27</td>
                                    <td>2011-05-07</td>
                                    <td>$145,000</td>
                                </tr>
                                <tr>
                                    <td>Gavin Cortez</td>
                                    <td>Team Leader</td>
                                    <td>San Francisco</td>
                                    <td>22</td>
                                    <td>2008-10-26</td>
                                    <td>$235,500</td>
                                </tr>
                                <tr>
                                    <td>Martena Mccray</td>
                                    <td>Post-Sales support</td>
                                    <td>Edinburgh</td>
                                    <td>46</td>
                                    <td>2011-03-09</td>
                                    <td>$324,050</td>
                                </tr>
                                <tr>
                                    <td>Unity Butler</td>
                                    <td>Marketing Designer</td>
                                    <td>San Francisco</td>
                                    <td>47</td>
                                    <td>2009-12-09</td>
                                    <td>$85,675</td>
                                </tr>
                                <tr>
                                    <td>Howard Hatfield</td>
                                    <td>Office Manager</td>
                                    <td>San Francisco</td>
                                    <td>51</td>
                                    <td>2008-12-16</td>
                                    <td>$164,500</td>
                                </tr>
                                <tr>
                                    <td>Hope Fuentes</td>
                                    <td>Secretary</td>
                                    <td>San Francisco</td>
                                    <td>41</td>
                                    <td>2010-02-12</td>
                                    <td>$109,850</td>
                                </tr>
                                <tr>
                                    <td>Vivian Harrell</td>
                                    <td>Financial Controller</td>
                                    <td>San Francisco</td>
                                    <td>62</td>
                                    <td>2009-02-14</td>
                                    <td>$452,500</td>
                                </tr>
                                <tr>
                                    <td>Timothy Mooney</td>
                                    <td>Office Manager</td>
                                    <td>London</td>
                                    <td>37</td>
                                    <td>2008-12-11</td>
                                    <td>$136,200</td>
                                </tr>
                                <tr>
                                    <td>Jackson Bradshaw</td>
                                    <td>Director</td>
                                    <td>New York</td>
                                    <td>65</td>
                                    <td>2008-09-26</td>
                                    <td>$645,750</td>
                                </tr>
                                <tr>
                                    <td>Olivia Liang</td>
                                    <td>Support Engineer</td>
                                    <td>Singapore</td>
                                    <td>64</td>
                                    <td>2011-02-03</td>
                                    <td>$234,500</td>
                                </tr>
                                <tr>
                                    <td>Bruno Nash</td>
                                    <td>Software Engineer</td>
                                    <td>London</td>
                                    <td>38</td>
                                    <td>2011-05-03</td>
                                    <td>$163,500</td>
                                </tr>
                                <tr>
                                    <td>Sakura Yamamoto</td>
                                    <td>Support Engineer</td>
                                    <td>Tokyo</td>
                                    <td>37</td>
                                    <td>2009-08-19</td>
                                    <td>$139,575</td>
                                </tr>
                                <tr>
                                    <td>Thor Walton</td>
                                    <td>Developer</td>
                                    <td>New York</td>
                                    <td>61</td>
                                    <td>2013-08-11</td>
                                    <td>$98,540</td>
                                </tr>
                                <tr>
                                    <td>Finn Camacho</td>
                                    <td>Support Engineer</td>
                                    <td>San Francisco</td>
                                    <td>47</td>
                                    <td>2009-07-07</td>
                                    <td>$87,500</td>
                                </tr>
                                <tr>
                                    <td>Serge Baldwin</td>
                                    <td>Data Coordinator</td>
                                    <td>Singapore</td>
                                    <td>64</td>
                                    <td>2012-04-09</td>
                                    <td>$138,575</td>
                                </tr>
                                <tr>
                                    <td>Zenaida Frank</td>
                                    <td>Software Engineer</td>
                                    <td>New York</td>
                                    <td>63</td>
                                    <td>2010-01-04</td>
                                    <td>$125,250</td>
                                </tr>
                                <tr>
                                    <td>Zorita Serrano</td>
                                    <td>Software Engineer</td>
                                    <td>San Francisco</td>
                                    <td>56</td>
                                    <td>2012-06-01</td>
                                    <td>$115,000</td>
                                </tr>
                                <tr>
                                    <td>Jennifer Acosta</td>
                                    <td>Junior Javascript Developer</td>
                                    <td>Edinburgh</td>
                                    <td>43</td>
                                    <td>2013-02-01</td>
                                    <td>$75,650</td>
                                </tr>
                                <tr>
                                    <td>Cara Stevens</td>
                                    <td>Sales Assistant</td>
                                    <td>New York</td>
                                    <td>46</td>
                                    <td>2011-12-06</td>
                                    <td>$145,600</td>
                                </tr>
                                <tr>
                                    <td>Hermione Butler</td>
                                    <td>Regional Director</td>
                                    <td>London</td>
                                    <td>47</td>
                                    <td>2011-03-21</td>
                                    <td>$356,250</td>
                                </tr>
                                <tr>
                                    <td>Lael Greer</td>
                                    <td>Systems Administrator</td>
                                    <td>London</td>
                                    <td>21</td>
                                    <td>2009-02-27</td>
                                    <td>$103,500</td>
                                </tr>
                                <tr>
                                    <td>Jonas Alexander</td>
                                    <td>Developer</td>
                                    <td>San Francisco</td>
                                    <td>30</td>
                                    <td>2010-07-14</td>
                                    <td>$86,500</td>
                                </tr>
                                <tr>
                                    <td>Shad Decker</td>
                                    <td>Regional Director</td>
                                    <td>Edinburgh</td>
                                    <td>51</td>
                                    <td>2008-11-13</td>
                                    <td>$183,000</td>
                                </tr>
                                <tr>
                                    <td>Michael Bruce</td>
                                    <td>Javascript Developer</td>
                                    <td>Singapore</td>
                                    <td>29</td>
                                    <td>2011-06-27</td>
                                    <td>$183,000</td>
                                </tr>
                                <tr>
                                    <td>Donna Snider</td>
                                    <td>Customer Support</td>
                                    <td>New York</td>
                                    <td>27</td>
                                    <td>2011-01-25</td>
                                    <td>$112,000</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Office</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                    <th>Salary</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table text-center">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col" class="text-left">@lang('Job Title')</th>
                                        <th scope="col" class="text-left">@lang('Job Type')</th>
                                        <th scope="col" class="text-left">@lang('Bids')</th>
                                        <th scope="col" class="text-left">@lang('Date')</th>
                                        <th scope="col" class="text-center">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hireEmploys as $hireEmploy)
                                        <tr>
                                            <td class="text-left"><a style="color:blue;text-decoration: underline;"
                                                    href="{{ route('job.details', [slug($hireEmploy->job->title), encrypt($hireEmploy->job->id)]) }}">{{ __(str_limit($hireEmploy->job->title, 20)) }}
                                                </a></td>
                                            @if ($hireEmploy->job->budgettype == 'fixed')
                                                <td class="text-left">Fixed</td>
                                            @elseif($hireEmploy->job->budgettype == 'per_hour')
                                                <td class="text-left">Per Hour</td>
                                            @endif
                                            @php
                                                $bids = DB::table('user_biding')
                                                    ->where('job_id', $hireEmploy->job->id)
                                                    ->count();
                                                $offers = DB::table('user_offers')
                                                    ->where('job_id', $hireEmploy->job->id)
                                                    ->count();

                                            @endphp
                                            <td class="text-left"><a style="color:blue;text-decoration: underline;"
                                                    href="{{ route('user.buyer.hire.employ.bids', encrypt($hireEmploy->job_id)) }}">{{ $bids }}</a>
                                            </td>

                                            <td class="text-left">
                                                {{ showDateTime($hireEmploy->job->created_at, 'd M Y') }}
                                            </td>
                                            <td>

                                                <div class="dash-action-98563">
                                                    <a title="View Bidding Detail"
                                                        href="{{ route('user.buyer.hire.employ.bids', encrypt($hireEmploy->job_id)) }}"
                                                        class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i
                                                            class="lni lni-eye"></i></a>
                                                    @if ($hireEmploy->job->show_completed == '1')
                                                        <a title="Add to completed" href="javascript:void(0);"
                                                            data-id="{{ $hireEmploy->job->id }}" data-toggle="modal"
                                                            data-target="#completedModal"
                                                            class="completedBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center ml-1"><i
                                                                class="fa fa-check"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  <div class="dashboard-widg-bar d-block">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="mb-4 tbl-lg rounded overflow-hidden">
                        <div class="table-responsive bg-white">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">@lang('Job')</th>
                                        <th scope="col">@lang('Employ')</th>
                                        <th scope="col">@lang('Amount')</th>
                                        <th scope="col">@lang('Working Status')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hireEmploys as $hireEmploy)
    <tr>
                                        <td>
                                            <img src="{{ getImage('assets/images/job/' . $hireEmploy->job->image, '590x300') }}" alt="@lang('Job Image')" class="img-fluid circle" width="50" />
                                        </td>
                                        <td>{{ __(str_limit($hireEmploy->job->title, 20)) }}</td>
                                        <td>{{ __(@$hireEmploy->user->fullname) }} <a href="{{ route('profile', [slug(@$hireEmploy->job->user->username), encrypt(@$hireEmploy->job->user->id)]) }}" >{{ $hireEmploy->job->user->username }}</a></td>
                                        <td>{{ showAmount($hireEmploy->bid_price) }} {{ __($general->cur_text) }}</td>
                                        <td>
                                            @if ($hireEmploy->working_status == 0)
    <span class="badge badge-primary">@lang('Pending')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 1)
    <span class="badge badge-success">@lang('Completed')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 2)
    <span class="badge badge-secondary">@lang('Delivered')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 3)
    <span class="badge badge-danger">@lang('Cancel')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 4)
    <span class="badge badge-info">@lang('In Progress')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 5)
    <span class="badge badge-danger">@lang('Delivery Expired')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
@elseif($hireEmploy->working_status == 6)
    <span class="badge badge-warning">@lang('Dispute')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->updated_at) }}
    @endif
                                        </td>
                                        <td style="width:10%;">
                                            @if ($hireEmploy->status == 1)
    <span class="badge badge-success">@lang('Running')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 2)
    <span class="badge badge-warning">@lang('Payable Both')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 3)
    <span class="badge badge-success">@lang('Paid')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->status_updated_at) }}
@elseif($hireEmploy->status == 4)
    <span class="badge badge-info">@lang('Refund')</span>
                                            <br>
                                            {{ diffforhumans($hireEmploy->status_updated_at) }}
    @endif
                                        </td>
                                        <td>
                                            <div class="dash-action-98563">
                                                <a href="{{ route('user.buyer.hire.employ.details', encrypt($hireEmploy->id)) }}" class="p-2 circle text-info bg-light-info d-inline-flex align-items-center justify-content-center mr-1"><i class="lni lni-eye"></i></a>
                                                @if ($hireEmploy->working_status == 0)
    <a href="javascript:void(0);" data-order_number="{{ $hireEmploy->id }}" data-order_number="{{ $hireEmploy->id }}" data-toggle="modal" data-target="#approvedModal" class="approvedBtn p-2 circle text-success bg-light-success d-inline-flex align-items-center justify-content-center"><i class="lni lni-pencil"></i></a>
                                                <a data-toggle="modal" data-target="#cancelModal" href="javascript:void(0);" data-order_number="{{ $hireEmploy->id }}" class="  p-2 circle text-danger bg-light-danger d-inline-flex align-items-center justify-content-center ml-1"><i class="lni lni-trash-can"></i></a>
    @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!--  <style type="text/css">
        .btn{
        padding: 7px;
        }
        </style>
        <div class="dashboard-widg-bar d-block ">
            <div class="row">
                <div class="col-lg-12 pb-3" >


                </div>

            </div>

            <div class="workboxmain">
                <div class="row">
                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                <h5>Title:Admin servicers required from an expert</h5>
                                <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals: 1</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-warning">@lang('Running')</span></p>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">

                                <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a>
                                <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2" ><i class="fa fa-edit"></i> </a>
                                <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-ban"></i> </a>


                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                <h5>Title:Admin servicers required from an expert</h5>
                                <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals: 1</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-warning">@lang('Running')</span></p>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">

                                <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a>
                                <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2" ><i class="fa fa-edit"></i> </a>
                                <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-ban"></i> </a>


                            </div>
                        </div>


                    </div>
                    <div class="col-lg-12 innerbox">
                        <div class="row">
                            <div class="col-lg-7 col-md-5 col-sm-12 col-12">
                                <h5>Title:Admin servicers required from an expert</h5>
                                <p><span class="font-weight-normal"><i class="lni lni-lock-alt"></i> Fixed</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-drop"></i> Proposals: 1</span> &nbsp &nbsp <span class="font-weight-normal"><i class="lni lni-calendar"></i> 12 Jan,2023</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-info">@lang('Pending')</span> &nbsp &nbsp <span style="font-size: 10px;" class="badge badge-warning">@lang('Running')</span></p>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-12 col-12 text-right " style="padding-top: 12px;">

                                <a href="#" class="toggle btn rounded theme-bg-light theme-cl fs-sm ft-medium mr-2" ><i class="fa fa-eye"></i> </a>
                                <a href="#" class="toggle btn rounded bg-blue text-white fs-sm ft-medium mr-2" ><i class="fa fa-edit"></i> </a>
                                <a href="#" class="toggle btn rounded bg-danger text-white fs-sm ft-medium mr-2" ><i class="fa fa-ban"></i> </a>


                            </div>
                        </div>


                    </div>



                </div>
            </div>
        </div> -->
    @endsection
    @section('script')
        <!-- <script type="text/javascript">
            "use strict";
            $('.approvedBtn').on('click', function() {
                var modal = $('#approvedModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
            $('.cancelBtn').on('click', function() {
                var modal = $('#cancelModal');
                modal.find('input[name=order_number]').val($(this).data('order_number'))
                modal.modal('show');
            });
        </script> -->
        <script type="text/javascript">
            'use strict';
            $('.completedBtn').on('click', function() {
                var modal = $('#completedModal');
                modal.find('input[name=id]').val($(this).data('id'))
                modal.modal('show');
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#example').DataTable();
            });
        </script>
    @endsection
