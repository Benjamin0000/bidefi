@foreach($bidders as $bidder)
<li>
    <div class="content">
        <div class="client">
            <div class="sc-author-box style-2">
                <div class="author-avatar">
                    <a href="#">
                        @if($bidder->user && $bidder->user->avatar)
                            <img src="{{Storage::url($bidder->user->avatar)}}" alt="" class="avatar">
                        @else 
                            <img src="/assets/images/avatar/avt-2.jpg" alt="" class="avatar">
                        @endif 
                    </a>
                    <div class="badge"></div>
                </div>
                <div class="author-infor">
                    <div class="name">
                        <h6><a href="#">{{get_bidder($bidder->id)}}</a></h6> 
                        <span> placed a bid</span>
                    </div>
                    <span class="time">{{$bidder->updated_at->diffForHumans()}}</span>
                </div>
            </div>
        </div>
        <div class="price">
            <h5> {{get_bid_value($bidder->points)}} ETH</h5>
            <span>= ${{eth_to_usd( get_bid_value($bidder->points) )}}</span>
        </div>
    </div>
</li>
@endforeach