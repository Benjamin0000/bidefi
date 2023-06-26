@if($item->status > 0)
<div class="avatar">
    <img src="/assets/images/avatar/avt-3.jpg" alt="">
</div>
@endif 
<div class="info">
    @if($item->status == 1)
        <span>Last Bidder</span>
    @elseif($item->status > 1)
        <span>Won By</span>
    @endif 
    @if($item->status > 0)
        <h6><a href="#">{{get_bidder($item->bidder_id)}}</a> </h6>
    @endif 
</div> 