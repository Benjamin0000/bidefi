@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Edit Item</h3>
            </div>
        </div>
    </div>
</div>
<form  method="POST" action="{{route('item.update', $item->id)}}">
    <div class="row">
        <div class="col-lg-6">
            <h4 class="title-create-item">Image/Video (URL)</h4>
            <input type="text" name="image" value="{{$item->image}}" class="form-control" required placeholder="Enter url">
        </div>
        <div class="col-lg-6">
            <h4 class="title-create-item">Select Type</h4>
            <select name="image_type" class="form-control" required id="">
                <option value="">Select</option>
                <option value="0" @selected($item->image_type == 0)>Image</option>
                <option value="1" @selected($item->image_type == 1)>Video</option>
            </select>
        </div>
    </div>     
    <br>
    <div class="row">
        <div class="col-lg-6">
            <h4 class="title-create-item">URL (for nfts)</h4>
            <input type="text" name="url" value="{{$item->url}}" class="form-control"  placeholder="(optional)">
        </div>
        <div class="col-lg-6">
            <h4 class="title-create-item">Title</h4>
            <input type="text" name="title" class="form-control" value="{{$item->name}}" required placeholder="Item Name">
        </div>
    </div>
    <br>
    <p> @method('put')
        <div class="row">
            <div class="col-lg-4">
                <h4 class="title-create-item">Price Est.</h4>
                <input type="text" name="price" class="form-control" value="{{$item->price}}" required placeholder="Enter price for item (USD)">
            </div>
            <div class="col-lg-4">
                <h4 class="title-create-item">Bid start price [ETH]</h4>
                <input type="text" name="start_price" required value="{{$item->start_price}}" class="form-control" placeholder="in ETH">
            </div>
        </div> 
    </p> 
    <br>
    <p>
        <h4 class="title-create-item">Description</h4>
        <textarea name="description" class="form-control" required placeholder="e.g.">{{$item->description}}</textarea>
    </p>
    <br>

    <div class="row">
        <div class="col">
            <h4 class="title-create-item">Start Time</h4>
            <input type="text" name="start_time" class="form-control" value="{{$item->ctd_timer}}" required placeholder="In minutes">
        </div>
        <div class="col">
            <h4 class="title-create-item">Symbol</h4>
            <input type="text" name="symbol" class="form-control" value="{{$item->symbol}}" placeholder="If erc-20”">
        </div>

        <div class="col">
            <h4 class="title-create-item">Min. Bid per entry</h4>
            <input type="text" name="min_bid" class="form-control" value="{{$item->min_bid}}" required placeholder="Minimum bid allowed">
        </div>

        <div class="col">
            <h4 class="title-create-item">Required total Bid</h4>
            <input type="text" name="start_points" class="form-control" value="{{$item->start_points}}" required placeholder="Bid required before countdown">
        </div>
    </div>
    <br>
    <br>

     @csrf 

    <p>
        <label> Show in header <input name="h" value="1" @checked($item->h) type="checkbox"> </label>
    </p>
    <p>
        <button class="btn btn-primary">Update</button>
    </p>
</form>
@stop 