@foreach($bidders as $bidder)
<div class="swiper-slide">
    <div class="slider-item">										
        <div class="sc-author-box style-2">
            <div class="author-avatar">
                @if($bidder->user && $bidder->user->avatar)
                    <img src="{{Storage::url($bidder->user->avatar)}}" alt="" class="avatar">
                @else 
                    <img src="/assets/images/avatar/avt-2.jpg" alt="" class="avatar">
                @endif 
                <div class="badge"></div>
            </div>
            <div class="author-infor">
                <h5><a href="#">{{$bidder->get_name()}}</a></h5>
                <span class="price">{{get_bid_value($bidder->total_credit)}}ETH</span>
            </div>
        </div>    	
    </div><!-- item--> 
</div>
@endforeach 