@extends($activeTemplate . 'layouts.wiserlance.dashboard-layout-job-withoutsidebar')

@section('content')
    <div class="row p-3 p-md-0 pb-5">
        <div class="col-12 p-md-4 pb-md-2">
            <!--Breadcrumbs-->
            <p class=" text-bc "> <a class="text-3gray" href="index.php">
                    <i class="fa-solid fa-house "></i></a>
                <i class="fa-solid fa-chevron-right"></i> <a class="text-3gray links" href="../services.php">Services</a>
                <i class="fa-solid fa-chevron-right"></i><span class="text-3gray"> Payment</span>
            </p> <!--Breadcrumbs End-->
        </div>

        <!--Right Section-->
        <div class="col-lg-4 order-second order-lg-last">
            <!-- Right Box 1-->
            <div class="bg-white p-4 shadow-sm border fs-14 rounded">
                <div class="d-flex border-bottom pb-2">
                    <img class="rounded-circle float-md-end" src="../img/Tufail.png" alt="" width="50"
                        height="50">
                    <p class="mb-2 fw-bold ms-3 mt-1">I will your social media manager and digital marketing viral content
                        creator.</p>
                </div>
                <!-- Box 2 -->
                <div class="w-100 fw-semibold fs-5 mt-2 text-dgreen2">Service 1 <span class="float-end">$228.29</span></div>
                <div class="w-100 mt-1"> Functional website <i class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 mt-1"> 1 Page<i class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 mt-1"> 3 Revisions <i class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 mt-1"> Responsive design <i class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 mt-1"> 1 Plugin/Extention <i class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 mt-1">1 Product <i class="fas fa-check text-green fw-bold float-end"></i></div>
                <div class="w-100 mt-1">Socila Media Icons <i class="fas fa-check text-green fw-bold float-end"></i></div>

                <div class="w-100 fw-semibold py-1 mt-2 fs-16 border-top">
                    Promo Code <span class="fw-normal text-green">*JT3DNX*
                    </span> <span class="float-end">-22.83</span>
                    <p class="fs-12 mb-0 text-secondary fw-normal"><i>Avail discount per order</i></p>
                </div>
                <div class="w-100 fw-semibold mt-1 border-top fs-16 border-bottom py-1">
                    Service Fee
                    <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-html="true"
                        data-bs-title="&lt;span class='fs-12'&gt; Add information &lt;/span&gt;"><i
                            class="fa-regular fa-circle-question"></i></button>
                    <span class="float-end">$11.31</span>
                </div>
                <div class="w-100 fw-semibold mt-1 fs-16">Total <span class="float-end">$216.77</span></div>
                <div class="w-100 mt-1">Delivery Time <span class="float-end">6 Days</span></div>

                <div class="mt-3 w-100 "> <button class="btn btn-sm btn-success bg-green border-0 w-100 fw-bold">Confirm
                        &amp; Pay</button></div>
                <p class="text-gray mb-0 text-center mt-2"><i class="fas fa-lock fs-10"></i> Secure payment</p>
                <p class="text-start mb-0 fs-14 text-center mt-2">You will be charged <b>$216.77</b> Total amount <br>
                    includes currency conversion fees.</p>
            </div>
        </div>

        <!--Right Section---xxxxxxxxxxxxxxx-->
        <div class="col-lg-8 mt-3 mt-lg-0" id="contents">

            <!-- Main Box 1 -->
            <div class="p-4 shadow-sm border rounded-2 bg-white">
                <div class="row">
                    <div class="col-md-9">
                        <h5 class="text-dgreen fw-bold"> Billing Information</h5>
                        <p class="fs-14 text-secondary ps-2">Your invoice will be issued according to the details listed
                            here.</p>
                        <p class="fs-14 text-secondary mb-0 ps-2">
                            <span><b>Your name</b></span><br>
                            <span>Address</span><br>
                            <span>Tel:</span><br>
                            <span>Email:</span><br>
                        </p>
                    </div>

                    <div class="col-md-3 col d-flex flex-column pt-3">
                        <button class="btn btn-sm btn-success bg-dgreen2 mb-2 fw-semibold">Add Details</button>
                    </div>
                </div>
            </div>

            <!--Main Box 2-->
            <div class="p-4 my-3 shadow-sm border rounded bg-white">
                <h5 class="fw-bold text-dgreen">Payment Options</h5>

                <!--Payment Method-->
                <label class="d-flex gap-2 p-2 align-items-center">
                    <input type="radio" class="mr-2"> Select Payment Method
                    <img src="https://help.zazzle.com/hc/article_attachments/360010513393" alt=""
                        style="width: 130px; height: auto;">
                </label>

                <!-- Card Number -->
                <p class="m-0 ps-3 mt-2">Card number</p>
                <div class="input-group ps-3">
                    <input type="text" class="form-control fs-14" placeholder="5864 5943 9523" aria-label="Card number">
                    <span class="input-group-text"><i class="fas fa-lock" style="font-size: 14px;"></i></span>
                </div>
                <small class="text-danger ps-3"><i class="fas fa-exclamation-circle fs-12"></i> Please enter a valid card
                    number.</small>

                <!-- Expire date -->
                <div class="row ps-3 mt-2">
                    <div class="col pb-3 py-2">
                        <p class="m-0">Expiration Date</p>
                        <div class="input-group">
                            <input type="text" class="form-control fs-14" placeholder="MM/YY" aria-label="Card number">
                        </div>
                    </div>
                    <div class="col pb-3 py-2">
                        <p class="m-0 ">Security Code</p>
                        <div class="input-group">
                            <input type="text" class="form-control fs-14" placeholder="123" aria-label="Card number">
                        </div>
                    </div>
                </div>

                <!-- Cardholder Name -->
                <div class="ps-3">
                    <p class="m-0 ">Cardholder's name</p>
                    <div class="input-group">
                        <input type="text" class="form-control fs-14" aria-label="Card number"
                            placeholder="Cardholder's name">
                    </div>
                    <small class="text-secondary">*As written on the card.</small>
                </div>

                <!-- Save card -->
                <div class="ps-3 mt-2">
                    <label class="d-flex gap-1 align-items-center fs-14">
                        <input type="checkbox"> Save this card for future payments.
                        <button type="button" class="btn p-0" data-bs-toggle="tooltip" data-bs-html="true"
                            data-bs-title="&lt;span class='fs-12'&gt; Add information &lt;/span&gt;"><i
                                class="fa-regular fa-circle-question"></i></button>
                    </label>
                </div>
                <!-- Method Paypal -->
                <div class="py-2 justify-content-between mt-3">
                    <label class="d-flex gap-2 align-items-center">
                        <input type="radio" class="me-2">
                        <i class="fa-brands fa-cc-paypal fs-4 text-primary"></i><span class="fs-6 fw-bold"> Paypal</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
@endsection
