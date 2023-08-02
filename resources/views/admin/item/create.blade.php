@extends('admin.layout')
@section('content')
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
            <input type="text" name="image" class="form-control" required placeholder="Enter url">
        </div>
        <div class="col-lg-6">
            <h4 class="title-create-item">Select Type</h4>
            <select name="image_type" class="form-control" required id="">
                <option value="">Select</option>
                <option value="0">Image</option>
                <option value="1">Video</option>
            </select>
        </div>
    </div>     
    <br>
    <div class="row">
        <div class="col-lg-6">
            <h4 class="title-create-item">URL (for nfts)</h4>
            <input type="text" name="url" class="form-control"  placeholder="(optional)">
        </div>
        <div class="col-lg-6">
            <h4 class="title-create-item">Title</h4>
            <input type="text" name="title" class="form-control" required placeholder="Item Name">
        </div>
    </div>
    <br>
    <p>
        <div class="row">
            <div class="col-lg-4">
                <h4 class="title-create-item">Price Est.</h4>
                <input type="text" name="price" class="form-control" required placeholder="Enter price for item (USD)">
            </div>
            <div class="col-lg-4">
                <h4 class="title-create-item">Bid start price</h4>
                <input type="text" name="start_price" required class="form-control" placeholder="in ETH">
            </div>
            <div class="col-lg-4">
                <h4 class="title-create-item">Prize</h4>
                <input type="text" name="prize" class="form-control" placeholder="Only for tokens">
            </div>
        </div> 
    </p> 
    <br>
    <p>
        <h4 class="title-create-item">Description</h4>
        <textarea name="description" class="form-control" required placeholder="e.g."></textarea>
    </p>
    <br>

    <div class="row">
        <div class="col">
            <h4 class="title-create-item">Start Time</h4>
            <input type="text" name="start_time" class="form-control" required placeholder="In minutes">
        </div>
        <div class="col">
            <h4 class="title-create-item">Symbol</h4>
            <input type="text" name="symbol" class="form-control" placeholder="If erc-20”">
        </div>
        <div class="col">
            <h4 class="title-create-item">Free Bid?</h4>
            <input type="text" name="free" class="form-control" required placeholder="eg: 100">
        </div>

        <div class="col">
            <h4 class="title-create-item">Min. Bid per entry</h4>
            <input type="text" name="min_bid" class="form-control" required placeholder="Minimum bid allowed">
        </div>

        <div class="col">
            <h4 class="title-create-item">Required total Bid</h4>
            <input type="text" name="start_points" class="form-control" required placeholder="Bid required before countdown">
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-lg-6">
            <h4 class="title-create-item">Type</h4>
            <select name="type" class="form-control" id="" required>
                <option value="">Select</option>
                <option value="1">Erc-721</option>
                <option value="2">Erc-1155</option>
                <option value="3">Erc-20</option>
                <option value="4">ETH-Native</option>
            </select>
        </div>
        <div class="col-lg-6">
            <h4 class="title-create-item">Contract address</h4>
            <input type="text" name="contract_address" class="form-control" placeholder="If NFT or erc20">
        </div>
    </div>
    <br>
    <br> @csrf 
    <p>
        <h4 class="title-create-item">NFT ID</h4>
        <input type="text" name="id" class="form-control" placeholder="(optional)">
    </p>
    <p>
        <button class="btn btn-primary" id="create_btn">Create</button>
    </p>
    <div id="msg"></div> 
</form>
@stop 