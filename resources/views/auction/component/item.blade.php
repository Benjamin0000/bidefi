@php 
    $user = Auth::user(); 
@endphp 
@foreach($auctions as $auction)
<div class="fl-item col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <div class="sc-card-product">
        <div class="card-media style2">
            <a href="{{route('auction.show', $auction->id)}}"><img src="{{$auction->image}}" alt="Image"></a>
            <button class="wishlist-button heart @if( $user && liked($auction->id, $user->id) ) active @endif"><span  onclick="likeItem({{$auction->id}})" class="number-like">{{$auction->likes}}</span></button>
            @if($auction->status < 2)
                <div class="featured-countdown style2">
                    <span class="slogan"></span>
                    @if($auction->status == 1)
                        <span class="js-countdown" id="timer{{$auction->id}}" data-timer="{{Carbon\Carbon::parse($auction->timer)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                    @else 
                        <span class="js-countdown" id="timer{{$auction->id}}" data-timer="{{Carbon\Carbon::parse($auction->start_time)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                    @endif 
                </div>
            @endif 
        </div>
        <div class="card-title">
            <h5 class="style2"><a href="{{route('auction.show', $auction->id)}}">{{$auction->name}}</a></h5>
            <div class="tags">
                @if($auction->type == 1)
                    ERC-721
                @elseif($auction->type == 2)
                    ERC-1155
                @elseif($auction->type == 3)
                    ERC-20
                @elseif($auction->type == 4)
                    ETH
                @endif 
            </div>
        </div>
        <div class="meta-info">
            <div class="author">
                <div class="avatar">
                    <img src="assets/images/avatar/avt-12.jpg" alt="Image">
                </div>
                <div class="info">
                    <span>Last bidder</span>
                    <h6> <a href="author02.html">{{get_bidder($auction->bidder_id)}}</a> </h6>
                </div>
            </div>
            <div class="price">
                <span>Current Bid</span>
                <h5> {{number_format($auction->bid_price, 3)}} ETH</h5>
            </div>
        </div>
        @if($auction->status == 1)
            <div class="card-bottom">
                <a href="{{route('auction.show', $auction->id)}}" class="sc-button style bag fl-button pri-3"><span>Place Bid</span></a>
            </div>
        @endif 
    </div>
</div>
@endforeach 