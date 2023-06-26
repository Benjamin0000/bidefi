@include('includes.header')
@include('includes.page_title', ['name'=>$item->name])
@php $user = Auth::user(); @endphp
<style>#claim_price:hover{color:white;}</style>
<div class="tf-section tf-item-details">
    <div class="themesflat-container">
        <div class="row">
            <div class="col-xl-6 col-md-12">
                <div class="content-left">
                    <div class="media">
                        <img src="{{$item->image}}" alt="">
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-12">
                <div class="content-right">
                    <div class="sc-item-details">
                        <h2 class="style2">{{$item->name}}</h2>
                        <div class="meta-item">
                            <div class="left">
                                <span class="viewed eye">{{$item->views}}</span>
                                <span class="liked heart wishlist-button mg-l-8 {{$user && liked($item->id, $user->id) ? 'active': ''}}"><span class="number-like" onclick="likeItem({{$item->id}})">{{$item->likes}}</span></span>
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
                                        <h6>$<span>{{number_format($item->price)}}</span></h6>
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
                                        <h5><span id="the_bid_price_eth">{{number_format($item->bid_price, 3)}}</span> ETH</h5>
                                        <span>=<span id="the_bid_price_usd">${{number_format(eth_to_usd($item->bid_price))}}</span></span>
                                    </div>
                                </div>
                            </div>
                            <div class="item count-down">
                                <span class="heading style-2">Countdown</span>
                                <span id="the_timer">
                                    @if($item->status == 0) 
                                        <span  class="js-countdown" data-timer="{{Carbon\Carbon::parse($item->start_time)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                                    @elseif($item->status == 1)
                                        <span  class="js-countdown" data-timer="{{Carbon\Carbon::parse($item->timer)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                                    @else 
                                        <span class="counter">0:0</span>
                                    @endif 
                                </span>
                            </div>
                        </div>
                        @if($item->status == 0)
                            <a href="#" data-toggle="modal" data-target="#popup_bid" class="sc-button loadmore style bag fl-button pri-3"><span>Place a bid</span></a>
                        @elseif($item->status == 2 && $item->bidder_id == Auth::user()->id)
                            <button id="claim_price" idd="{{$item->id}}" class="sc-button sc-button loadmore style bag fl-button pri-3 btn-block">Claim</button>
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
@include('auction.component.bid_modal')
@include('includes.footer')
