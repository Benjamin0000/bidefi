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
                    <li>
                        <div class="content">
                            <div class="client"> 
                                <div class="sc-author-box style-2">
                                    <div class="author-avatar">
                                        <a href="#">
                                            @if($avatar = $winner->user->avatar)
                                                <img style="width:30px !important" src="{{Storage::url($avatar)}}" alt="" class="avatar">
                                            @else 
                                                <img style="width:30px !important" src="/assets/images/avatar/avt-2.jpg" alt="" class="avatar">
                                            @endif 
                                        </a>
                                        <div class="badge"></div>
                                    </div>
                                    <div class="author-infor">
                                        <div class="name">
                                            <h6><a href="#">{{$winner->user->get_name()}}</a></h6> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
    </div>
</div> 