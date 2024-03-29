        
<div class="modal fade popup" id="popup_bid" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body space-y-20 pd-40">
                <h3>Place a Bid</h3>
                <p class="text-center">You must bid at least <span class="price color-popup">{{$item->min_bid}} Credits</span>
                </p>
                @php $used = get_used($user->id, $item->id) @endphp

                @if($item->free_bid)
                    @if($used < $item->free_bid)
                        <p class="text-center">You have Free Credit <span class="price color-popup">{{$item->free_bid - $used}} Credits</span>
                        </p>
                    @endif 
                @endif 

                <p class="text-center">Your Credit <span class="price color-popup"><span class="bid_credit_info"></span> Credits</span>
                </p>
                @if(!$item->free_bid)
                    <p class="text-center" id="buy_credit_link"></p>
                @endif 
                <p>Enter quantity.
                </p>
                <form id="place_bid_form">
                    <input type="number" name="amt" class="form-control quantity" required value="{{$item->min_bid}}}">
                    <br>

                    <input type="hidden" name="network" value="{{$item->network}}">
                    <input type="hidden" name="free_bid" value="{{$item->free_bid}}">
                    <input type="hidden" name="used" value="{{$used}}">
                    

                    <input type="hidden" name="min" value="{{$item->min_bid}}">
                    <input type="hidden" name="id" value="{{$item->id}}">
                    <div id="bid_msg" style="overflow: hidden"></div> 
                    <button class="btn btn-primary">Place Bid</button>
                </form>
            </div>
        </div>
    </div>
</div> 