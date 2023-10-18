@if($item->status > 0)
    @if($item->status > 1 && $item->share > 1)
    @else 
        <div class="avatar">
            @if( $avatar = $item->get_last_bidder_avatar()  )
                <img src="{{Storage::url($avatar)}}" alt="">
            @else 
                <img src="/assets/images/avatar/avt-3.jpg" alt="">
            @endif 
        </div>
    @endif 
@endif 
<div class="info">
    @if($item->status == 1)
        <span>Last Bidder</span>
    @elseif($item->status > 1)
        @if($item->share <= 1)
            <span>Won By</span>
        @endif 
    @endif 

    @if($item->status > 0)
        @if($item->status > 1 && $item->share > 1)
            <a href="#" data-toggle="modal" data-target="#winners" class="sc-button style bag fl-button pri-3">View Winners</a>
        @else 
            <h6><a href="{{route('auction.show', $item->id)}}">{{get_bidder($item->bidder_id)}}</a> </h6>
        @endif 
    @endif
</div>
