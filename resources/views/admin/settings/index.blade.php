@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Settings</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <form id="bid_price_form">
            <label for="">Bid Price (ETH)</label>
            <p>
                <input type="text" value="" id="ad_bid_price" class="form-control" name="price" required placeholder="Enter bid price">
            </p>
            <label for="">Referral commision (%)</label>
            <p>
                <input type="text" id="ad_bid_com" id="" class="form-control" name="commission" required placeholder="Enter referral commission">
            </p>
            <label for="">Bid fee (ETH)</label>
            <p>
                <input type="text" id="ad_bid_fee" class="form-control" name="bid_fee" required placeholder="Enter bid fee">
            </p>
            <div id="msg"></div> 
            <p>
                <button class="btn btn-primary">Update</button>
            </p>
        </form>
    </div>
    <div class="col-md-3">
        <form method="POST" action="/admin/update-min-bid" id="min_bid_form">
            <label for="">Min Bid purchase</label>
            <p>
                <input type="text" value="{{(int)get_register('min_bid_purchase')}}" class="form-control" name="amount" required placeholder="Enter min bid purchase">
            </p>
            <label for="">Fee commission %</label>
            <p>
                <input type="text" value="{{(float)get_register('fee_ref')}}" class="form-control" name="fee_ref" required placeholder="Enter fee commission">
            </p>
            <label for="">Min withdrawal</label>
            <p>
                <input type="text" value="{{(float)get_register('min_fee_with')}}" class="form-control" name="min_fee_with" required placeholder="Enter min fee with">
            </p>
            @csrf 
            <p>
                <button class="btn btn-primary">Update</button>
            </p>
        </form>
    </div>
</div>
@stop 