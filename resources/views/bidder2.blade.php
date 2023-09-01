<div class="avatar">
    @if( $avatar = $item->get_last_bidder_avatar()  )
        <img src="{{Storage::url($avatar)}}" alt="">
    @else 
        <img src="/assets/images/avatar/avt-3.jpg" alt="">
    @endif 
</div>
<div class="info">
    <span>Last bidder</span>
    <h6><a href="#">{{get_bidder($item->bidder_id)}}</a></h6>
</div> 