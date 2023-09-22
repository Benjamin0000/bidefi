@foreach($bidders as $bidder)
<div class="swiper-slide"> 
    <div class="slider-item">										
        <div class="sc-author-box style-2">
            <div class="author-avatar">
                @if($bidder->avatar)
                    <img src="{{Storage::url($bidder->avatar)}}" alt="" class="avatar">
                @else 
                    <img src="/assets/images/avatar/avt-2.jpg" alt="" class="avatar">
                @endif 
                <div class="badge"></div>
            </div>
            <div class="author-infor">
                <h5><a href="#">{{$bidder->get_name()}}</a></h5>
                <span class="price">${{$bidder->total_credit * $bidder->item->}}</span>
            </div>
        </div>    	
    </div><!-- item--> 
</div>
@endforeach 