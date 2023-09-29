@include('includes.header')
@include('includes.page_title', ['name'=>'Bid Credit'])
<style>
    .net_list{
        border:1px solid #ccc;
        padding:10px; 
        height: 100px; 
        line-height: 40px;
        cursor: pointer;
    }    
    .net_list:hover{
        background:#050505;
        color:white; 
    }
</style> 
<section class="tf-login tf-section">
    <div class="themesflat-container">
        <div class="row justify-content-center">
            <div class="col-8">
                <h2 class="tf-title-heading ct style-1">
                    Buy bid credit 
                </h2>
                <div class="flat-form box-login-email">
                    <div class="form-inner">
                        <form id="buy_credit_form">
                            <input id="bid_amt" name="amount" required type="number" placeholder="Enter amount">
                            <input id="bid_eth_price" name="eth" placeholder="ETH" type="number" readonly >
                            <div class="alert alert-success" style="font-size: 20px; font-weight:bold; display:none"  id="bid_info">
                                Bid purchase successful
                            </div>
                            <div class="alert alert-danger" id="min_bid_info" style="font-size: 20px; font-weight:bold; display:none" >
                                Minimum purchase is {{(float)get_register('min_bid_purchase')}} Credits
                            </div> 
                            <input type="hidden" name="ref" id="ref_val" value="{{$user? $user->ref_by: ''}}">
                            <button class="submit">Buy Credit</button>
                            <div  style="font-size: 20px; font-weight:bold" id="msg"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    window.min_bid = {{(float)get_register('min_bid_purchase')}}
</script>


        
<div class="modal fade popup" id="select_network_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" id="close_net" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body space-y-20 pd-40">
                <h3>Select Network</h3>
                <div class="row">
                    @foreach(all_networks() as $net)
                        <div class="col-md-6">
                            <div class="text-center net_list net_cc" net="{{$net}}">
                                <img width="50" src="{{get_logo($net)}}" alt="">
                                <h3>{{get_network_name($net)}}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div> 


@include('includes.footer')
