@include('includes.header')
@include('includes.page_title', ['name'=>'Bid Credit'])
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
@include('includes.footer')
