@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Create Items</h3>
            </div>
        </div>
    </div>
</div>
<form id="create_form" method="POST" action="/admin/items">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="title-create-item">Image/Video (URL)</h4>
            <input type="text" name="image" value="{{$item ? $item->image: ''}}" class="form-control" required placeholder="Enter url">
        </div>
        <div class="col-lg-6">
            <h4 class="title-create-item">Select Type</h4>
            <select name="image_type" class="form-control" required id="">
                <option value="">Select</option>
                <option value="0" @selected($item ? $item->image_type == 0 : 0)>Image</option>
                <option value="1" @selected($item ? $item->image_type == 1 : 0)>Video</option>
            </select>
        </div>
    </div>     
    <br>
    <div class="row">
        <div class="col-lg-6">
            <h4 class="title-create-item">URL (for nfts)</h4>
            <input type="text" name="url" class="form-control" value="{{$item ? $item->url: ''}}"  placeholder="(optional)">
        </div>
        <div class="col-lg-6">
            <h4 class="title-create-item">Title</h4>
            <input type="text" name="title" class="form-control" value="{{$item ? $item->name: ''}}" required placeholder="Item Name">
        </div>
    </div>
    <br>
    <p>
        <div class="row">
            <div class="col-lg-4">
                <h4 class="title-create-item">Price Est.</h4>
                <input type="text" name="price" class="form-control" value="{{$item ? $item->price: ''}}" required placeholder="Enter price for item (USD)">
            </div>
            <div class="col-lg-4">
                <h4 class="title-create-item">Bid start price</h4>
                <input type="text" name="start_price" required class="form-control" value="{{$item ? $item->start_price: ''}}" placeholder="in ETH">
            </div>
            <div class="col-lg-4">
                <h4 class="title-create-item">Prize</h4>
                <input type="text" name="prize" class="form-control" value="{{$item ? $item->prize: ''}}" placeholder="Only for tokens or 1155 NFTs">
            </div>
        </div> 
    </p> 
    <br>
    <p>
        <h4 class="title-create-item">Description</h4>
        <textarea name="description" class="form-control" required placeholder="e.g.">{{$item ? $item->description: ''}}</textarea>
    </p>
    <br>

    <div class="row">
        <div class="col">
            <h4 class="title-create-item">Start Time</h4>
            <input type="text" name="start_time" class="form-control" value="{{$item ? $item->ctd_timer: ''}}" required placeholder="In minutes">
        </div>
        <div class="col">
            <h4 class="title-create-item">Symbol</h4>
            <input type="text" name="symbol" class="form-control" value="{{$item ? $item->symbol: ''}}" placeholder="If erc-20”">
        </div>
        <div class="col">
            <h4 class="title-create-item">Free Bid?</h4>
            <input type="text" name="free" class="form-control" required value="{{$item ? $item->free_bid: ''}}" placeholder="eg: 100">
        </div>

        <div class="col">
            <h4 class="title-create-item">Min. Bid per entry</h4>
            <input type="text" name="min_bid" class="form-control" value="{{$item ? $item->min_bid: ''}}" required placeholder="Minimum bid allowed">
        </div>

        <div class="col">
            <h4 class="title-create-item">Required total Bid</h4>
            <input type="text" name="start_points" class="form-control" value="{{$item ? $item->start_points: ''}}" required placeholder="Bid required before countdown">
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-lg-2">
            <h4 class="title-create-item">Type</h4>
            <select name="type" class="form-control" id="type_input" required>
                <option value="">Select</option>
                <option value="1" @selected($item ? $item->type == 1 : 0)>Erc-721</option>
                <option value="2" @selected($item ? $item->type == 2 : 0)>Erc-1155</option>
                <option value="3" @selected($item ? $item->type == 3 : 0)>Erc-20</option>
                <option value="4" @selected($item ? $item->type == 4 : 0)>ETH-Native</option>
            </select>
        </div>
        <div class="col-lg-2">
            <h4 class="title-create-item">Decimals</h4>
            <input value="{{$item ? $item->decimal: ''}}" id="dec_input" type="number" name="decimal" class="form-control" placeholder="Eg: 18">
        </div>
        <div class="col-lg-2">
            <h4 class="title-create-item">Share</h4>
            <input value="{{$item ? $item->share: 0}}" id="share" type="number" name="share" class="form-control" placeholder="Eg: 5">
        </div>
        <div class="col-lg-6">
            <h4 class="title-create-item">Contract address</h4>
            <input type="text" name="contract_address" class="form-control" value="{{$item ? $item->contract_address: ''}}" placeholder="If NFT or erc20">
        </div>
    </div>
    <input type="hidden" name="network" id="network_input" value="{{$item ? $item->network: ''}}">
    <br> @csrf 
    <p>
        <h4 class="title-create-item">NFT ID</h4>
        <input type="text" name="_id" class="form-control" placeholder="(optional)">
    </p>
    <input type="hidden" name="id" value="{{$id}}">

    <p>
        <label> Show in header <input name="h" value="1" @checked($item ? $item->h: 0) type="checkbox"> </label>
    </p>
    <p>
        <button class="btn btn-primary" id="create_btn">Create</button>
    </p>
    <div id="msg"></div> 
</form>

<script>
window.onload = ()=>{
    $("#type_input").on('change', (e)=>{
        if(e.target.value == 4){
            $("#dec_input").val(18)
        }else{
            $("#dec_input").val('')
        }
    }); 
}
</script>
@stop 