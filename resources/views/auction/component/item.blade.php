@foreach($auctions as $auction)
<div class="fl-item col-xl-3 col-lg-4 col-md-6 col-sm-6">
    <div class="sc-card-product">
        <div class="card-media style2">
            <a href="{{route('auction.show', $auction->id)}}"><img src="{{$auction->image}}" alt="Image"></a>
            <button class="wishlist-button heart"><span class="number-like"> 100</span></button>
            <div class="featured-countdown style2">
                <span class="slogan"></span>
                <span class="js-countdown" data-timer="516400" data-labels=" :  ,  : , : , "></span>
            </div>
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
        <div class="card-bottom">
            <a href="{{route('auction.show', $auction->id)}}" class="sc-button style bag fl-button pri-3"><span>Place Bid</span></a>
        </div>
    </div>
</div>
@endforeach 