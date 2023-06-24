@include('includes.header')
@include('includes.page_title', ['name'=>$item->name])
<style>
    #claim_price:hover{
        color:white; 
    }
</style>
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
                                <span class="liked heart wishlist-button mg-l-8"><span class="number-like">{{$item->likes}}</span></span>
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
                                <div class="author">
                                    @if($item->status > 0)
                                        <div class="avatar">
                                            <img src="" alt="">
                                        </div>
                                    @endif 
                                    <div class="info">
                                        @if($item->status == 1)
                                            <span>Last Bidder</span>
                                        @elseif($item->status > 1)
                                            <span>Won By</span>
                                        @endif 
                                        @if($item->status > 0)
                                            <h6> <a href="">{{get_bidder($item->bidder_id)}}</a> </h6>
                                        @endif 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p>{{$item->description}}</p>
                        <div class="meta-item-details style2">
                            <div class="item meta-price">
                                <span class="heading">Current Bid</span>
                                <div class="price">
                                    <div class="price-box">
                                        <h5> {{number_format($item->bid_price, 3)}} ETH</h5>
                                        <span>= ${{number_format(eth_to_usd($item->bid_price))}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="item count-down">
                                <span class="heading style-2">Countdown</span>
                                <span class="js-countdown" data-timer="60"
                                    data-labels=" :  ,  : , : , "></span>
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
                                    <ul class="bid-history-list">
                                        <li>
                                            <div class="content">
                                                <div class="client">
                                                    <div class="sc-author-box style-2">
                                                        <div class="author-avatar">
                                                            <a href="item-details.html#">
                                                                <img src="assets/images/avatar/avt-3.jpg" alt="" class="avatar">
                                                            </a>
                                                            <div class="badge"></div>
                                                        </div>
                                                        <div class="author-infor">
                                                            <div class="name">
                                                                <h6><a href="author02.html">Mason Woodward </a></h6> <span> place a bid</span>
                                                            </div>
                                                            <span class="time">8 hours ago</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price">
                                                    <h5> 4.89 ETH</h5>
                                                    <span>= $12.246</span>
                                                </div>
                                            </div>
                                        </li>
                                     

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
@include('auction.component.bid_modal')
@include('includes.footer')
