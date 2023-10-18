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
                    
                    <div>
                        @if($avatar = $winner->user->avatar)
                            <img width="50" src="{{Storage::url($avatar)}}" alt="" >
                        @else 
                            <img width="50" src="/assets/images/avatar/avt-2.jpg" alt="">
                        @endif 
                    </div>
                    <h4>{{$winner->user->get_name()}}</h4>
                    
                    <hr>
                @endforeach
            </div>
        </div>
    </div>
</div> 