@php 
    $user = Auth::user(); 
    $className = ""; 
    if( isset($class) ){
        $className = $class;
    }
@endphp 
@foreach($auctions as $auction)
<div class="fl-item col-xl-3 col-lg-4 col-md-6 col-sm-6 {{$className.$auction->network}} {{ $auction->free_bid > 0 ? $className.'Free' : $className.'Paid'}} {{$className}}" style="display: block;">
    <div class="sc-card-product">
        <div class="text-center">
            <h5>
                @if($auction->share > 1)
                    <span class="text-info" style="position: absolute;left:30px">{{$auction->share}} <b class="fas fa-users"></b></span>
                @endif 
                <img src="{{get_logo($auction->network)}}" width="30" alt="">
                {{-- <img src="{{get_logo($auction->network)}}" width="30" alt=""> --}}
            </h5>
        </div>
        <br>
        <div class="card-media style2">
            <a href="{{route('auction.show', $auction->id)}}">
                <div style="height:40vh;display:flex;width:100%">
                    <img src="{{$auction->image}}" style="object-fit: contain;" alt="Image">
                </div>
            </a>
            @if($auction->points >= $auction->start_points && $auction->status < 2)
            <br><br><br><br>
                <div class="featured-countdown style2">
                    <span class="slogan"></span>
                    <span id="timer{{$auction->id}}">
                        @if($auction->status == 1)
                            <span class="js-countdown"  data-timer="{{Carbon\Carbon::parse($auction->timer)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                        @elseif($auction->status == 0)
                            <span class="js-countdown"  data-timer="{{Carbon\Carbon::parse($auction->start_time)->diffInSeconds()}}" data-labels=" :  ,  : , : , "></span>
                        @endif 
                    </span>
                </div>
            @endif 
        
        <br>

    </div>

    <div class="item_l">
        @if($auction->free_bid)
            <button class="wishlist-button bg-success" style="left: 0 !important; width:50px; background:var(--primary-color3) !important; float:left">Free</button>
        @else 
            <button class="wishlist-button bg-success" style="left: 0 !important; width:50px; background:red !important; float:left">Paid</button>
        @endif 
        <a style="float:right" href="javascript:void(0)"  class="wishlist-button heart @if( $user && liked($auction->id, $user->id) ) active @endif"><span  onclick="likeItem({{$auction->id}})" class="number-like">{{$auction->likes}}</span></a> 
    </div> 
    
        <div style="margin-top:60px;"> 
            @if($auction->points < $auction->start_points && $auction->status == 0)
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width:{{get_pct($auction->points, $auction->start_points)}}%;height: 100%;font-size:15px;line-height:20px;" aria-valuenow="{{get_pct($auction->points, $auction->start_points)}}" aria-valuemin="0" aria-valuemax="{{get_pct($auction->points, $auction->start_points)}}">{{get_pct($auction->points, $auction->start_points)}}%</div>
                </div>
                <br>
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
                    {{strtoupper($auction->symbol)}}
                @endif 
            </div>
        </div>
        <div class="meta-info">
            <div class="author" id="author{{$auction->id}}">
                <div class="avatar">
                    @if( $avatar = $auction->get_last_bidder_avatar() )
                        <img src="{{Storage::url($avatar)}}" class="img-fluid" alt="Image">
                    @else 
                        <img src="/assets/images/avatar/avt-12.jpg"  class="img-fluid" alt="Image">
                    @endif
                </div>
                <div class="info">
                    <span>Last bidder</span>
                    <h6> <a href="{{route('auction.show', $auction->id)}}">{{get_bidder($auction->bidder_id)}}</a> </h6>
                </div>
            </div>
            <div class="price">
                <span>Current Bid</span>
                <h5><span>$</span><span id="c_bid{{$auction->id}}">{{number_format($auction->bid_price, 5)}}</span></h5>
            </div>
        </div>
        
            <div class="card-bottom">
                @if($auction->status == 0)
                    <a href="{{route('auction.show', $auction->id)}}" class="sc-button style bag fl-button pri-3"><span>Place Bid</span></a>
                @endif 
                @if($auction->status < 2)
                    <a target="_blank" href="{{get_locked_url($auction->network)}}">
                        <img class="img-fluid rounded" width="80" src="/lock_price.png" alt="">
                    </a>
                @endif 
            </div>
        
    </div>
</div> 
@endforeach  

