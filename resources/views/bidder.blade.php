@if($item->status > 0)
<div class="avatar">
    @if( $avatar = $item->get_last_bidder_avatar()  )
        <img src="{{Storage::url($avatar)}}" alt="">
    @else 
        <img src="/assets/images/avatar/avt-3.jpg" alt="">
    @endif 
</div>
@endif 
<div class="info">
    @if($item->status == 1)
        <span>Last Bidder</span>
    @elseif($item->status > 1)
        <span>Won By</span>
    @endif 
    @if($item->status > 0)
        <h6><a href="{{route('auction.show', $item->id)}}">{{get_bidder($item->bidder_id)}}</a> </h6>
    @endif 
</div> 