
<style>
    .ntn-container-for-buyer-waa {
      background: #F2F2F2;
      padding-top: 60px;
      /* padding-bottom: 50px; */
    }

    .ntn-header {
      background-color: #F7F7F7;
      width: 100%;
      margin-top: 20px;
    }

    .ntn-seller-outer {
      background-color: #E5E5E5;
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
    }

    .ntn-for-seller-heading-a {
      color: var(--dark-green);
      font-weight: bold;
      font-size: 2em;
    }

    .ntn-container-popular-categories {
      background-color: rgba(15, 82, 86, 0.2);
      padding-top: 60px;
    }

    .ntn-row-position {
      position: relative;
      padding-bottom: 80px
    }

    .ntn-for-seller-heading-b {
      color: var(--secondary-color);
      text-transform: uppercase;
      font-weight: bold;
    }

    .ntn-for-seller-heading-c {
      font-weight: 600;
      font-size: 1.5em;
    }

    .ntn-for-seller-paragraph-a {
      color: var(--text-color);
      font-size: 15px;
      line-height: 17px;
    }

    .ntn-double-box-outer {
      width: 100%;
      display: flex;
      height: 302px;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .ntn-box-d-1 {
      width: 100%;
      padding: 30px;
      height: 100%;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: start;

    }

    .ntn-box-d-1>img {
      position: absolute;
      bottom: 60px;
      right: 57px;
      width: 35px;
    }

    .ntn-box-1 {
      background-color: var(--secondary-color);
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
      /* box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15); */
    }

    .ntn-box-2 {
      background-color: var(--dark-green);
      box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
      /* box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15); */
    }

    .ntn-box-heading-4322 {
      font-size: 24px;
      font-weight: medium;
    }

    .ntn-seller-double-box-check-text {
      display: flex;
      flex-direction: row;
      align-items: center;
      gap: 16px;
    }

    .ntn-seller-double-box-check-text>img {
      height: 18px;
      width: 25px;

    }

    .ntn-seller-double-box-check-text>p {
      font-size: 28px;
      margin: 0;
      padding: 0;
    }

    .ntn-for-buyer-heading {
      /* border-bottom: 3px solid var(--dark-background); */
      padding-right: 10px;
      margin-bottom: 16px;
      color: #009971;
      font-size: 48px;
    }

    .ntn-center-content {
      display: flex;
      align-items: center;
      flex-direction: column;
    }

    .ntn-sub-heading1 {
      color: #000000;
      text-transform: uppercase;
      font-size: 40px;
      font-weight: bold;
      font-family: "Afacad", sans-serif;
    }
  </style>

  <style>
    .ntn-container-why-choose-us {
      background: #FFFFFF;
      padding-top: 50px;
      padding-bottom: 50px;
    }

    .ntn-heading {
      color: #0CB48C;
      font-size: 42px;
    }

    .ntn-list {
      color: #1E1E1E;
      font-size: 18px
    }

    .ntn-sub-heading {
      font-weight: bold;
      font-size: 22px;
    }

    .ntn-qustions {
      font-style: italic;
      font-weight: bold;
    }

    .ntn-readmore-corporate-businesss {
      /* padding: 7px 26px; */
      width: 130px;
      height: 50px;
      border: none;
      background-color: #0F5256;
      color: white;
      border-radius: 5px !important;
      font-size: 18px;
      margin-top: 30px;
      font-weight: bold;
      display: flex;
      justify-content: space-around;
      align-items: center
    }

    .img-fluids {
      width: 7px;
      margin-left: 5px;
    }

    .row {
      display: flex;
      justify-content: center;
      align-items: center;
    }
</style>
<div>
    <div class="ntn-container-why-choose-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-12 col-12 pt-3">
                    <h1 class="font-weight-bold ntn-heading">Why Choose Wiserlance?</h1>
                    <h4 class="text-dark text-bold mt-4 ntn-sub-heading">Talented and professionals from around the world
                        at your fingertips now & why should businesses trust Wiserlance?</h4>
                    <ul class="list-style mt-4 ntn-list">
                        <li style="display: flex;align-items: center;position: relative;justify-content: space-between;">
                            <img class="img-fluid"style="position: absolute;left: 0px;width: 24px;"
                                src="{{ asset('assets/wiserlance/assets/img/home/check.png') }}">Pay and reward the
                            right people, based on your budget and requirements
                        </li>
                        <li style="display: flex;align-items: center;position: relative;justify-content: space-between;">
                            <img class="img-fluid"style="position: absolute;left: 0px;width: 24px;"
                                src="{{ asset('assets/wiserlance/assets/img/home/check.png') }}"> Registration on Wiserlance will be limited to only professional and talented individuals
                            with identity verified, as our goal is to offer quality services.</li>
                            <li style="display: flex;align-items: center;position: relative;justify-content: space-between;">
                                <img class="img-fluid"style="position: absolute;left: 0px;width: 24px;"
                                    src="{{ asset('assets/wiserlance/assets/img/home/check.png') }}">Work and quality is at the top of your priority list, while protection of your data,
                            privacy and money is at ours</li>
                    </ul>
                    <h6 class="ntn-qustions"> Do you have a question? please send us your question, and we'll be happy
                        to assist you?</h6>
                    <button class="ntn-readmore-corporate-businesss">Ask Now <img class="img-fluids"
                            src="{{ asset('assets/wiserlance/assets/img/home/rignt-arrows.png') }}"></button>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                    <img class="img-fluid" src="{{ asset('assets/wiserlance/assets/img/home/Illustration.svg') }}">
                </div>
            </div>
        </div>
    </div>

</div>
