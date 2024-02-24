         
<section class="flat-cart_item home6 style2">
    <div class="overlay"></div>
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <div class="swiper-container carousel8  auctions" style="width: 80% !important;">
                    <div class="swiper-wrapper">
                        @foreach($trendings as $trending)
                            <div class="swiper-slide">
                                <div class="slider-item">

                                    <div class="sc-card-product">
                                        <div class="text-center">
                                            <h5>
                                                @if($trending->share > 1)
                                                    <span class="text-info" style="position: absolute;left:30px">{{$trending->share}} <b class="fas fa-users"></b></span>
                                                @endif 
                                                <img src="{{get_logo($trending->network)}}" width="30" alt="">
                                            </h5>
                                        </div>
                                        <br>
                                        <div class="card-media style2">
                                            <a href="{{route('auction.show', $trending->id)}}">
                                                <div style="height:40vh;display:flex;width:100%">
                                                    <img src="{{$trending->image}}" style="object-fit: contain;" alt="Image">
                                                </div>
                                            </a>
                                            {{-- <br>
                                            @if($trending->free_bid)
                                                <button class="wishlist-button bg-success" style="left: 0 !important; width:50px; background:var(--primary-color3) !important">Free</button>
                                            @else 
                                                <button class="wishlist-button bg-success" style="left: 0 !important; width:50px; background:red !important">Paid</button>
                                            @endif 
                                            
                                
                                            <button style="float:right" class="wishlist-button heart @if( $user && liked($trending->id, $user->id) ) active @endif"><span  onclick="likeItem({{$trending->id}})" class="number-like">{{$trending->likes}}</span></button>
                                             --}}
                                            @if($trending->points >= $trending->start_points && $trending->status < 2)
                                                <div class="featured-countdown style2">
                                                    <span class="slogan"></span>
                                                    <span id="timer{{$trending->id}}">
                                                        @if($trending->status == 1)
                                                            <span class="js-countdown"  data-timer="{{Carbon\Carbon::parse($trending->timer)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                                                        @elseif($trending->status == 0)
                                                            <span class="js-countdown"  data-timer="{{Carbon\Carbon::parse($trending->start_time)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                                                        @endif 
                                                    </span>
                                                </div>
                                            @endif 
                                        </div>

                                        <div class="item_l">
                                            @if($trending->free_bid)
                                                <button class="wishlist-button bg-success" style="left: 0 !important; width:50px; background:var(--primary-color3) !important; float:left">Free</button>
                                            @else 
                                                <button class="wishlist-button bg-success" style="left: 0 !important; width:50px; background:red !important; float:left">Paid</button>
                                            @endif 
                                            <a style="float:right" href="javascript:void(0)"  class="wishlist-button heart @if( $user && liked($trending->id, $user->id) ) active @endif"><span  onclick="likeItem({{$trending->id}})" class="number-like">{{$trending->likes}}</span></a> 
                                        </div> 

                                        <div style="margin-top:70px;"> 
                                        @if($trending->points < $trending->start_points && $trending->status == 0)
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width:{{get_pct($trending->points, $trending->start_points)}}%;height: 100%;font-size:15px;line-height:20px;" aria-valuenow="{{get_pct($trending->points, $trending->start_points)}}" aria-valuemin="0" aria-valuemax="{{get_pct($trending->points, $trending->start_points)}}">{{get_pct($trending->points, $trending->start_points)}}%</div>
                                            </div>
                                            <br>
                                        @endif 
                                        </div> 
                                        <div class="card-title">
                                            <h5 class="style2"><a href="{{route('auction.show', $trending->id)}}">{{$trending->name}}</a></h5>
                                            <div class="tags">
                                                @if($trending->type == 1)
                                                    ERC-721
                                                @elseif($trending->type == 2)
                                                    ERC-1155
                                                @elseif($trending->type == 3)
                                                    ERC-20
                                                @elseif($trending->type == 4)
                                                    {{strtoupper($trending->symbol)}}
                                                @endif 
                                            </div>
                                        </div>
                                        <div class="meta-info">
                                            <div class="author" id="author{{$trending->id}}">
                                                <div class="avatar">
                                                    @if( $avatar = $trending->get_last_bidder_avatar() )
                                                        <img src="{{Storage::url($avatar)}}" class="img-fluid" alt="Image">
                                                    @else 
                                                        <img src="/assets/images/avatar/avt-12.jpg"  class="img-fluid" alt="Image">
                                                    @endif
                                                </div>
                                                <div class="info">
                                                    <span>Last bidder</span>
                                                    <h6> <a href="{{route('auction.show', $trending->id)}}">{{get_bidder($trending->bidder_id)}}</a> </h6>
                                                </div>
                                            </div>
                                            <div class="price">
                                                <span>Current Bid</span>
                                                <h5><span>$</span><span id="c_bid{{$trending->id}}">{{number_format($trending->bid_price, 5)}}</span></h5>
                                            </div>
                                        </div>
                                        @if($trending->status == 0)
                                            <div class="card-bottom">
                                                <a href="{{route('auction.show', $trending->id)}}" class="sc-button style bag fl-button pri-3"><span>Place Bid</span></a>
                                            </div>
                                        @endif 
                                    </div>

                                    {{-- <div class="wrap-cart">
                                        <div class="cart_item style2 style3">
                                            <div class="inner-cart">
                                                <div class="overlay"></div>
                                                <img class="img-fluid" src="{{$trending->image}}" alt="Image">
                                                <div class="content">
                                                    <div class="fs-16">
                                                        <a href="{{route('auction.show', $trending->id)}}">{{$trending->name}}</a>
                                                    </div>
                                                </div>   
                                            </div>
                                        </div>
                                    </div> 	 --}}
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