@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job-withoutsidebar')

@section('content')
    <div class="row p-3 p-md-0 pb-5">
        <div class="col-12 pt-md-0 p-md-4 pb-md-2">
            <p class="mb-0 mt-3 text-3gray">
                @if ($user->is_employer)
                    <a class="text-3gray" href="/user/buyer/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                @else
                    <a class="text-3gray" href="/user/seller/dashboard"><i class="fa-solid fa-house fs-14"></i></a>
                @endif
                <i class="fa-solid fa-chevron-right fs-12"></i>
                <a href="/service" class="text-3gray" style="text-decoration:none;">Services</a>
                <i class="fa-solid fa-chevron-right fs-12"></i> Order Now
            </p>
        </div>

        <!-- Right Section-->
        <div class="col-lg-4 order-second order-lg-last">
            <div class="bg-white p-4 shadow-sm border fs-14 rounded">
                <div class="w-100 fw-semibold fs-5 text-dgreen2">Service 1 <span class="float-end">$0.00</span></div>
                <div class="w-100 mt-1">This is my service 1 </div>
                <div class="w-100 fw-semibold mt-1"><i class="fas fa-clock fs-10"></i> 2 Days Delivery </div>
                <div class="w-100 fw-semibold mt-1"><i class="fas fa-clock fs-10"></i> Revisions <span
                        class="float-end">Select Revisions</span></div>
                <div class="w-100 mt-1"> Model Documentation <i class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 mt-1"> Data Source Connectivity <i class="fas fa-check text-green fw-bold float-end"></i>
                </div>
                <div class="w-100 mt-1"> Model Validation/Testing <i class="fas fa-check text-green fw-bold float-end"></i>
                </div>
                <div class="w-100 mt-1"> Web Embedding <i class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 mt-1"> Interactive/Animated Visuals <i
                        class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 fw-semibold mt-1">Service Price <span class="float-end">$0.00</span></div>
                <div class="w-100 fw-semibold mt-1">Select Quantity <input type="text"
                        class="form-control p-0 text-center fw-bold fs-14 float-end" "="" value="1" required="" style="width:30px"></div>
                <div class="w-100 fw-semibold mt-1">Quantity <span class="float-end">1</span></div>
                <div class="w-100 fw-semibold mt-1">Subtotal <span class="float-end">0</span></div>
                <div class="w-100 fw-semibold mt-1">Additional Service Cost <span class="float-end">0</span></div>
                <div class="w-100 fw-semibold mt-1">Service Fee <span class="float-end">5%</span></div>
                <div class="w-100 fw-semibold mt-1">Total <span class="float-end">0</span></div>
                <div class="w-100">
                    <label for="validationServer02" class="form-label"></label>
                    <input type="text" class="form-control is-valid fs-14" id="validationServer02" value="Coupen code" required="">
                </div>
                <div class="w-100 mt-3">   
                    <a class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold" href="{{ route('user.service.order.payment',['service_id'=>encrypt(25)]) }}">Confirm &amp; Pay</a></div>
            </div>
        </div>

        <!--Left Section---xxxxxxxxxxxxxxx-->
            <div class="col-lg-8 mt-lg-0 my-3" id="contents">
            <!-- Main Box 1 -->
                <div class="px-3 py-2 shadow-sm border rounded-2 bg-white">
                    <div class="row d-flex  py-2">
                        <div class="col-md-9">
                            <p class="mb-2 fw-bold ps-md-4 ms-md-5">I will your social media manager and digital marketing viral content creator.</p>
                            <div class="d-flex">
                                <div>
                                    <img class="rounded-circle float-md-end" src="../img/Tufail.png" alt="" width="70">
                                </div>
                                <div class="ms-3 ms-md-2">
                                    <p class="m-0 fs-18 fw-bold">Asim R. </p>
                                    <p class="m-0 text-secondary fs-14">
                                        <i class="fas fa-star fs-12 text-warning"></i> 5
                                        <i class="fas fa-map-marker-alt ms-2"></i> Pakistan
                                        <span class="text-green ms-2"> Verified <i class="fas fa-check fs-12"></i> </span>
                                    </p>
                                    <span class="me-2 text-secondary fs-14"><i class="fas fa-user fs-12"></i> Member Since 04 Feb 2024</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col d-flex flex-column pt-3">
                            <button class="btn btn-sm btn-success bg-dgreen2 mb-2 fw-semibold">View Profile</button>
                            <button class="btn btn-sm btn-dark fw-semibold">Contact Me</button>
                        </div>
                    </div>
                </div>
               
            <!--Main Box 2-->
                    <div class=" px-3 py-2 mt-3 shadow-sm border border-2 rounded-2 bg-white">
                    <h5 class="fw-bold fs-18 p-2 text-dgreen">Upgrade your order with extras</h5>
                <!-- Box 2 -->
                    <div class="px-4 py-3 mb-3 bg-lgreen3 rounded-2 ">
                        <div class="w-100">
                            <input type="checkbox" id="fast-delivery" class="form-check-input float-end">
                            <p class=" mb-1 fw-bold text-dgreen2 m-0 fs-16">Extra-fast 7-day delivery</p>
                            <p class="m-0 fw-semibold fs-18">$198.51</p>
                        </div>
                        
                    </div>
                <!-- Box 2 -->
                    <div class="px-4 py-3 mb-3 bg-lgreen3 rounded-2">
                        <input type="checkbox" id="fast-delivery" class="form-check-input float-end">
                        <p class="mb-1 fw-bold fs-16 text-dgreen2">E-commerce functionality <span class="fw-normal">(+3 days)</span></p>
                        <p class="m-0 fs-14">The freelancer will add e-commerce functionality to your website.</p>
                        <p class="m-0 fw-semibold fs-18">$148.88</p>
                    </div>
                <!-- Box 3 -->
                    <div class="px-4 py-3 mb-3 bg-lgreen3 rounded-2">
                        <input type="checkbox" id="fast-delivery" class="form-check-input float-end">
                        <p class="mb-1 fw-bold fs-16 text-dgreen2">Additional page <span class="fw-normal">(+1 day)</span></p>
                        <p class="m-0 fs-14">The freelancer will add an additional page to your website.</p>
                        <p class="m-0 fw-semibold fs-18">$99.26</p>
                    </div>
                <!-- Box 4 -->

                    <div class="px-4 py-3 mb-3 bg-lgreen3 rounded-2">
                        <input type="checkbox" id="fast-delivery" class="form-check-input float-end">
                        <p class="mb-1 fw-bold fs-16 text-dgreen2">Woocommerce Setup <span class="fw-normal">(+3 days)</span></p>
                        <p class="m-0 fs-14">I will setup a complete functional woocommerce store.</p>
                        <p class="m-0 fw-semibold fs-18">$148.88</p>
                    </div>
                        
                </div>
                
                
            <!--Main Box 3-->
                <div class="px-3 py-3 mt-3 shadow-sm border rounded-2 bg-white">
                    <button id="addMoreBtn" class="btn btn-sm btn-success bg-dgreen2 border-0 text-white fw-semibold float-end">
                        Add More
                    </button>
                
                    <h5 class="fw-bold fs-18 text-dgreen">Extra Service</h5>
                
                    <!-- Wrapper -->
                    <div id="extraServiceWrapper">
                        <!-- Single Row -->
                        <div class="row py-3 bg-white extra-service-row">
                            <div class="col-12 col-md-7 mt-1">
                                <input type="text" class="form-control fs-14" placeholder="Enter title">
                            </div>
                            <div class="col-8 col-md-4 mt-1">
                                <input type="text" class="form-control fs-14" placeholder="Price">
                            </div>
                            <div class="col-4 col-md-1 mt-1">
                                <button type="button" class="btn btn-sm btn-danger remove-row">
                                    <i class="fas fa-multiply fs-12"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
            </div>
@endsection

@section('script')
<script>
    document.getElementById('addMoreBtn').addEventListener('click', function () {
        const wrapper = document.getElementById('extraServiceWrapper');
    
        const row = document.createElement('div');
        row.classList.add('row', 'py-3', 'bg-white', 'extra-service-row');
    
        row.innerHTML = `
            <div class="col-12 col-md-7 mt-1">
                <input type="text" class="form-control fs-14" placeholder="Enter title">
            </div>
            <div class="col-8 col-md-4 mt-1">
                <input type="text" class="form-control fs-14" placeholder="Price">
            </div>
            <div class="col-4 col-md-1 mt-1">
                <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="fas fa-multiply fs-12"></i>
                </button>
            </div>
        `;
    
        wrapper.appendChild(row);
    });
    
    // Remove row (event delegation)
    document.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.extra-service-row').remove();
        }
    });
    </script>
    
@endsection