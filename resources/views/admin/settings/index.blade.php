@extends('admin.layout')
@section('content')
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
        <form method="POST" action="/admin/update-bid-price" id="bid_price_form">
            <label for="">Bid Price</label>
            <p>
                <input type="text" value="{{(float)get_register('bid_price')}}" class="form-control" name="price" required placeholder="Enter bid price">
            </p>
            @csrf 
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
            @csrf 
            <p>
                <button class="btn btn-primary">Update</button>
            </p>
        </form>
    </div>
</div>
<div id="msg"></div> 
@stop 