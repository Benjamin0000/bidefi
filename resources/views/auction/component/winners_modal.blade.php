@php 
    $winners = App\Models\Winner::where('item_id', $item->id)->get(); 
@endphp 
<div class="modal fade popup" id="winners" tabindex="-10" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body space-y-20 pd-40">
                <h3>Winners</h3>
                @foreach($winners as $winner)
                    <div class="meta-info">
                        <div class="author">
                            <div class="avatar">
                                @if( $avatar = $winner->user->avatar  )
                                    <img src="{{Storage::url($avatar)}}" alt="">
                                @else 
                                    <img src="/assets/images/avatar/avt-3.jpg" alt="">
                                @endif 
                            </div>
                            <div class="info">
                                <span>Won By</span>
                                <h6><a href="{{route('auction.show', $item->id)}}">{{$winner->user->fname.' '.$winner->user->lname}}</a></h6>
                            </div>
                        </div>
                    </div> 
                @endforeach
            </div>
        </div>
    </div>
</div> 