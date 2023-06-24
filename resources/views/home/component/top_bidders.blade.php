<section class="tf-section top-seller home6 s2 mobie-style">
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="tf-title style2 mb-25 text-left">Top Bidders</h2>
                <div class="flat-tabs seller-tab style3 tablet-30">
                    <div class="content-tab mg-t-24">
                        <div class="content-inner">
                            <div class="swiper-container seller seller-slider3 button-arow-style">
                                <div class="swiper-wrapper">
                                    @include('auction.component.bidder_box', ['bidders'=>$top_bidders])
                                </div>
                                <div class="swiper-button-next btn-slide-next active"></div>
                                <div class="swiper-button-prev btn-slide-prev "></div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>     
</section>