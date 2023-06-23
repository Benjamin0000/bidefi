@extends('admin.layout')
@section('content')
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Items  <a href="/admin/create-items" class="btn btn-primary btn-sm">Create</a></h3>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Type</th>
            <th>Est. Price</th>
            <th>Bid Price</th>
            <th>Start Price</th>
            <th>Start time</th>
            <th>Free credit</th>
            <th>Created</th>
            <th>More</th>
        </tr>
    </thead>
    <tbody>
        @php $no = tableNumber(10) @endphp 
        @foreach($items as $item)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$item->name}}</td>
                <td>
                    @if($item->type == 1)
                        ERC-721
                    @elseif($item->type == 2)
                        ERC-1155
                    @elseif($item->type == 3)
                        <div>ERC-20</div> 
                        {{$item->prize}} {{$item->symbol}}
                    @elseif($item->type == 4)
                        <div>Native token</div> 
                        {{$item->prize}} {{$item->symbol}}
                    @endif 
                </td>
                <td>
                    ETH {{number_format($item->price, 2)}}
                </td>
                <td>
                    ETH {{number_format($item->bid_price,2)}}
                </td>
                <td>
                    ETH {{number_format($item->start_price, 2)}}
                </td>
                <td>
                    {{$item->start_time}}
                    <div>{{Carbon\Carbon::create($item->start_time)->diffForHumans()}}</div> 
                </td>
                <td>
                    {{$item->free_bid}} Points
                </td>
                <td>
                    {{$item->created_at->isoFormat('lll')}}
                    <div>
                        {{$item->created_at->diffForHumans()}}
                    </div> 
                </td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#vm{{$item->id}}">View more</button>
                </td>
            </tr>
            <div  class="modal fade" id="vm{{$item->id}}" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog modal-lg" >
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">{{$item->name}}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        @if($item->image_type == 0)
                            <img src="{{$item->image}}" width="100" alt="">
                        @else 
                            <video src="{{$item->image}}"></video>
                        @endif 
                        <br>
                        <h5>Contract Address</h5>
                        {{$item->contract_address}}
                        <h5>Description</h5>
                        {{$item->description}}
                      </div>
                    </div>
                </div>
            </div>
        @endforeach 
    </tbody>
</table>
</div>
{{$items->links()}}
@stop 