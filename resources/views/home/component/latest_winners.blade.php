<section class="tf-section top-seller home6 s2 mobie-style">
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="tf-title style2 mb-25 text-left">Latest Winners</h2>
                <div class="flat-tabs seller-tab style3 tablet-30">
                    <div class="content-tab mg-t-24">
                        <div class="content-inner">
                            <div class="swiper-container seller seller-slider3 button-arow-style">
                                <div class="swiper-wrapper">
                                    @foreach($latest_winners as $bidder)
                                    <div class="swiper-slide">
                                        <div class="slider-item">										
                                            <div class="sc-author-box style-2">
                                                <div class="author-avatar">
                                                    <img src="/assets/images/avatar/avt-2.jpg" alt="" class="avatar">
                                                    <div class="badge"></div>
                                                </div>
                                                <div class="author-infor">
                                                    <h5><a href="/#">{{get_bidder($bidder->id)}}</a></h5>
                                                    <span class="price">{{get_bid_value($bidder->points)}}ETH</span>
                                                </div>
                                            </div>    	
                                        </div><!-- item-->
                                    </div>
                                    @endforeach                  
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