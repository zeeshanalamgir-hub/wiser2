<style>

.ntn-container-parant{
    background-color: #F7F7F7;
    width: 100%;
    margin-top: 100px;
}
.ntn-subheading{
    font-size: 28px;
    font-weight:300;
    margin-top: 20px !important;
}
.ntn-icon{
    position: relative;
    top: 1px;
    right: 15px;
    width: 20px;
}
.ntn-boxes{
    display: flex;
    align-items: center;
    justify-content:space-evenly;
    padding: 10px
}
.ntn-cards{
    height: 320px;
    width: 24%;
    background-color:#FFFFFF;
    border-radius:5px;
    padding:10px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2)
}
.ntn-image{
    height: 145px;
    width: 100%;
    background-color:black;
    border-radius:5px;
}
.ntn-img{
    height: 145px;
    width: 100%;
    border-radius:5px;
    opacity:60%
}
.ntn-box-content{
    display: flex;
    justify-content:left;
    align-items: center;
    flex-wrap: wrap;
}
.ntn-content-img{
    height: 50px;
    width: 50px;
    border-radius:20px;
    object-fit:cover
}
.ntn-content-image{
    height: 50px;
    width: 50px;
    border-radius:50px;
    object-fit:cover
}
.ntn-paragraph{
    font-size: 10px;
    text-align:left
}.ntn-card-bottom{
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.ntn-order-button{
    height:35px;
    width:90px;
    background-color:#003F45;
    border-radius:5px;
    color:white;
    font-size: 12px;
    display:flex;
    justify-content:center;
    align-items: center
}
</style>
<div class="ntn-container-parant">
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-left pl-2">
                    <h1 class="font-weight-bold" Style="color:#069170;">Featured Services</h1>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-right">
                    <span class="font-weight-light ntn-subheading">Browse all services &nbsp
                    <img src="{{ asset('assets/wiserlance/assets/img/home/vector.png') }}" class="ntn-icon">
                </div>
            </div>
            <div class="row ntn-boxes" >
                <div class="ntn-cards" >
                    <div class="ntn-image">
                      <img src="{{ asset('assets/wiserlance/assets/img/certification/home/card-banner.png') }}" alt="" class="ntn-img">
                  </div>
                <div class="ntn-box-content" >
                    <div style="margin-top: 10px">
                       <div class="ntn-content-img">
                           <img src="{{ asset('assets/wiserlance/assets/img/certification/home/profile.png') }}" alt="" class="ntn-content-image">
                       </div>
                    </div>
                    <div style="margin-top: 10px">
                      <p class="ntn-paragraph" >I will be your social media manager and digital marketing viral content creator....</p>
                    <div class="ntn-card-bottom">
                        <div>
                            <p style="font-size:11px; color:#003F45; font-weight:600">Starting From</p>
                            <p style="font-size: 11px; margin-top:-5px; color:#00C535; font-weight:700">$5.00</p>
                        </div>
                        <div>
                           <p style="font-size:11px; color:#003F45; font-weight:600">Delivery From</p>
                           <p style="font-size: 11px; margin-top:-5px; color:#00C535; font-weight:700">$5.00</p>
                        </div>
                        <div class="ntn-order-button" >Order Now
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
