<style>
    .ntn-seller-outer {
        /* background-color: #E5E5E5; */
        background: linear-gradient(to bottom, #F7F7F7 0%, #F7F7F7 80%, #ffffff 80%, #ffffff 100%);
    }

    .ntn-seller-cover-image {
        width: 100%;
    }

    .ntn-seller-content-inner-a {
        display: flex;
        flex-direction: column;
        justify-content: left;
        align-items: flex-start;
    }

    .ntn-for-seller-heading {
        /* border-bottom: 3px solid var(--dark-background); */
        padding-right: 10px;
        margin-bottom: 16px;
        color: #069170;
        font-size: 48px;
    }

    .ntn-for-seller-heading-a {
        color: #000000;
        font-size: 28px;
        font-weight: bold;
    }

    .ntn-for-seller-heading-b {
        color: #0CB48C;
        font-size: 36px;
        text-transform: uppercase;
        font-weight: bold;
    }

    .ntn-for-seller-heading-c {
        font-weight: 600;
        font-size: 24px;
    }

    .ntn-for-seller-paragraph-a {
        color: var(--text-color);
        font-size: 16px;
        line-height: 22px;
    }

    .ntn-double-box-outer {
        width: 100%;
        display: flex;
        height: 240px;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }


    .ntn-box-d {
        width: 100%;
        padding: 30px;
        height: 100%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: start;
    }

    .ntn-box-d-2 {
        width: 100%;
        padding: 0px 30px;
        height: 100%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: start;
    }

    .ntn-box-d>img {
        position: absolute;
        bottom: 30px;
        right: 45px;
        width: 35px;
    }

    .ntn-box-d-2>img {
        position: absolute;
        bottom: 30px;
        right: 45px;
        width: 35px;
    }

    .ntn-box-1 {
        background-color: var(--secondary-color);
    }

    .ntn-box-2 {
        background-color: var(--dark-green);
    }

    .ntn-box-heading-432 {
        font-weight: 600;
        font-size: 24px;
        line-height: 30px;
    }

    .ntn-box-heading-433 {
        font-weight: 600;
        font-size: 24px;
        line-height: 30px;
    }


    .ntn-seller-double-box-check-text {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 16px;
    }

    .ntn-seller-double-box-check-text>img {
        height: 18px;
        width: 28.54;

    }

    .ntn-seller-double-box-check-text>p {
        font-size: 18px;
        margin: 0;
        padding: 0;
    }
</style>
<div class="py-4">
    <div class="container pb-4">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-12 col-9 pt-5 text-left ">
                <div class="ntn-seller-content-inner-a">
                    <h1 class="ntn-for-seller-heading fw-noraml">For Sellers</h1>
                    <h3 class="ntn-for-seller-heading-a mt-4">Discover outstanding opportunities</h3>

                    <p class="ntn-poppins ntn-for-seller-paragraph-a mt-2">Are you looking to take control of your career
                        and
                        work on your own terms?
                        Freelancing might be the perfect option for you! As a freelancer, you have the freedom to choose
                        your own projects, set your own hours, and work from anywhere in the world.</p>

                    <p class="ntn-poppins ntn-for-seller-paragraph-a">
                        Working as a freelancer can be a rewarding and fulfilling career choice. With the right skills,
                        tools, and mindset, you can enjoy the benefits of a flexible and independent lifestyle.</p>
                    <h2 class="afacad-bold ntn-for-seller-heading-b mt-4">So why wait? </h2>
                    <h3 class="afacad-semibold ntn-for-seller-heading-c">Start exploring Wiserlance for freelancing
                        today!</h3>

                    <p class="ntn-poppins ntn-for-seller-paragraph-a">Wiselance enables you to reach out to clients
                        all over the world with predefined services in various categories.
                    </p>

                    <p class="ntn-poppins ntn-for-seller-paragraph-a">Wiserlance provides freelancers with the
                        opportunity to work on an hourly basis as
                        Wiserlance offers flexible pricing options <br> for clients, including fixed-price or hourly
                        contracts.
                    </p>
                </div>



            </div>
            <div class="col-lg-5 col-md-5 col-sm-0 col-0 pt-5 pr-5">
                <img class="img-fluid ntn-seller-cover-image mt-4 ml-4"
                    src="{{ asset('assets/wiserlance/assets/img/home/for-sellar.svg') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-md-12 col-sm-12 col-9 pt-5 mx-4">
                <div class="ntn-double-box-outer">
                    <div class="ntn-box-d ntn-box-1">
                        <div class="ntn-box-content-6789">
                            <h4 class="text-white ntn-box-heading-432 afacad-bold">Discover Opportunities</h4>
                            <div>
                                <div class="text-white ntn-seller-double-box-check-text">
                                    <img src="{{ asset('assets/wiserlance/assets/img/home/for-seller-check.svg') }}" />
                                    <p class=" ntn-box-list-1257">Verified Clients</p>
                                </div>
                                <div class="text-white ntn-seller-double-box-check-text">
                                    <img src="{{ asset('assets/wiserlance/assets/img/home/for-seller-check.svg') }}" />
                                    <p class=" ntn-box-list-1257">Earn money in different ways</p>
                                </div>

                                <div class="text-white ntn-seller-double-box-check-text">
                                    <img src="{{ asset('assets/wiserlance/assets/img/home/for-seller-check.svg') }}" />
                                    <p class=" ntn-box-list-1257">Work when, where, and how you want</p>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('assets/wiserlance/assets/img/home/for-seller-arrow.svg') }}" />
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12 col-sm-12 col-9 pt-5 ">
                <div class="ntn-double-box-outer">
                    <div class="ntn-box-d-2 ntn-box-2">
                        <div class="ntn-box-content-6789">
                            <h4 class="text-white ntn-box-heading-433 afacad-bold">Create Service In Various
                                Categories
                            </h4>
                            <div>
                                <div class="text-white ntn-seller-double-box-check-text">
                                    <img src="{{ asset('assets/wiserlance/assets/img/home/for-seller-check.svg') }}" />
                                    <p class=" ntn-box-list-1257">Service with a predefined cost</p>
                                </div>
                                <div class="text-white ntn-seller-double-box-check-text">
                                    <img src="{{ asset('assets/wiserlance/assets/img/home/for-seller-check.svg') }}" />
                                    <p class=" ntn-box-list-1257">Present your portfolio to the</p>
                                </div>

                                <div class="text-white ntn-seller-double-box-check-text">
                                    <img src="{{ asset('assets/wiserlance/assets/img/home/for-seller-check.svg') }}" />
                                    <p class=" ntn-box-list-1257">Customize services for clients</p>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('assets/wiserlance/assets/img/home/for-seller-arrow.svg') }}" />

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
