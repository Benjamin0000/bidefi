         
<section class="flat-cart_item home6 style2">
    <div class="overlay"></div>
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="swiper-container carousel8  auctions" style="width: 80% !important; height:50vh">
                    <div class="swiper-wrapper">
                        @foreach($trendings as $trending)
                            <div class="swiper-slide">
                                <div class="slider-item">	
                                    <div class="wrap-cart">
                                        <div class="cart_item style2 style3">
                                            <div class="inner-cart">
                                                <div class="overlay"></div>
                                                <img style="height: 100%; width:100%;" src="{{$trending->image}}" alt="Image">
                                                <div class="content">
                                                    <div class="fs-16">
                                                        <a href="{{route('auction.show', $trending->id)}}">{{$trending->name}}</a>
                                                    </div>
                                                </div>   
                                            </div>
                                        </div>
                                    </div> 	
                                </div>
                            </div>
                        @endforeach                       
                    </div>
                    <div class="swiper-pagination mg-t22"></div>
                </div>  
                <div class="swiper-button-next btn-slide-next active"></div>
                <div class="swiper-button-prev btn-slide-prev"></div>
            </div>
        </div>
    </div>
</section>