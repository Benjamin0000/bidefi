@include('includes.header')
@include('includes.page_title', ['name'=>$item->name])
@php $user = Auth::user(); @endphp
<style>#claim_price:hover{color:white;}</style>
<div class="tf-section tf-item-details">
    <div class="themesflat-container">
        <div class="row">
            <div class="col-xl-6 col-md-12">
                <div class="content-left">
                    <div style="height: 600px">
                        <img src="{{$item->image}}" style="height: 100%; width:100%; object-fit: contain;" alt="">
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12">
                <div class="content-right">
                    <div class="sc-item-details">
                        <h2 class="style2">{{$item->name}}</h2>
                        <div class="meta-item">
                            <div class="left">
                                @if($item->free_bid)
                                    <span class="bg-success" style="background:var(--primary-color3) !important; color:white;">Free</span>
                                @else 
                                    <span class="bg-danger" style="background:red !important; color:white;">Paid</span>

                                @endif 
                                <span class="viewed eye">{{$item->views}}</span>
                                <span class="liked heart wishlist-button mg-l-8 {{$user && liked($item->id, $user->id) ? 'active': ''}}"><span class="number-like" onclick="likeItem({{$item->id}})">{{$item->likes}}</span></span>
                                
                                <span class="mg-l-8"><b class="text-success">Network:</b> <b>{{get_network_name($item->network)}}</b></span>

                            </div>
                            <div class="right">
                                {{-- <a href="item-details.html#" class="share"></a>
                                <a class="option"></a> --}}
                            </div>
                        </div>
                        <div class="client-infor sc-card-product">
                            <div class="meta-info">
                                <div class="author">
                                    <div class="info">
                                        <span>Price</span>
                                        <h6>${{number_format($item->price)}}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="meta-info">
                                <div id="the_author" class="author">
                                  @include('bidder')
                                </div>
                            </div>
                        </div>

                        <p>{{$item->description}}</p>
                        <div class="meta-item-details style2">
                            <div class="item meta-price">
                                <span class="heading">Current Bid</span>
                                <div class="price">
                                    <div class="price-box">
                                        {{-- <h5><span id="the_bid_price_eth">{{number_format($item->bid_price, 3)}}</span> ETH</h5> --}}
                                        <span><span id="the_bid_price_usd">${{number_format($item->bid_price, 5)}}</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="item count-down">
                                <span class="heading style-2">Countdown</span>
                                @if($item->points >= $item->start_points)
                                    <span id="the_timer">
                                        @if($item->status == 0) 
                                            <span  class="js-countdown" data-timer="{{Carbon\Carbon::parse($item->start_time)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                                        @elseif($item->status == 1)
                                            <span  class="js-countdown" data-timer="{{Carbon\Carbon::parse($item->timer)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                                        @else 
                                            <span class="counter">0:0</span>
                                        @endif 
                                    </span>
                                @endif 
                            </div>
                        </div>
                        @if($item->points < $item->start_points && $item->status == 0)
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width:{{get_pct($item->points, $item->start_points)}}%;height: 100%;font-size:15px;line-height:20px;" aria-valuenow="{{get_pct($item->points, $item->start_points)}}" aria-valuemin="0" aria-valuemax="{{get_pct($item->points, $item->start_points)}}">{{get_pct($item->points, $item->start_points)}}%</div>
                            </div>
                            <br>
                        @endif 
                        @if(!$user)
                            <h5 class="text-center text-danger">Connect wallet to bid</h5>
                            <br>
                        @else 
                            @if($item->status == 0)
                                <a href="#" data-toggle="modal" data-target="#popup_bid" class="sc-button  style bag fl-button pri-3"><span>Place a bid</span></a>
                            @elseif($item->status == 2 && $item->canClaim())
                                <button id="claim_price" idd="{{$item->id}}" net="{{$item->network}}" class="sc-button sc-button style bag fl-button pri-3 btn-block" style="color:white;">Claim</button>
                                <div id="c_msg"></div> 
                            @endif 
                        @endif  
                        <div class="flat-tabs themesflat-tabs">
                            {{-- <ul class="menu-tab tab-title">
                                <li class="item-title active">
                                    <span class="inner">Bid History</span>
                                </li>
                            </ul> --}}
                            <br>
                            <h5 class="text-center">Bid History</h5>
                            <div class="content-tab">
                                <div class="content-inner tab-content">
                                    <ul class="bid-history-list" id="show_bidders">
                                      @include('bidders', compact('bidders'))
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(() => {
        window.count_views({{$item->id}})
    }, 2000);
    window.show_id = {{$item->id}}
</script>
@if($user)
@include('auction.component.bid_modal')
@endif 
@include('includes.footer')
