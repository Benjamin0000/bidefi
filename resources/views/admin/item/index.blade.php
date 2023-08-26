@extends('admin.layout')
@section('content')
<br>
<br>
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
            <th>Credits</th>
            <th>Created</th>
            <th>Status</th>
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
                        {{number_format($item->prize, 3)}} {{$item->symbol}}
                    @elseif($item->type == 4)
                        <div>Native token</div> 
                        {{number_format($item->prize, 3)}} {{$item->symbol}}
                    @endif 
                </td>
                <td>
                    ${{number_format($item->price, 2)}}
                </td>
                <td>
                    ETH {{number_format($item->bid_price, 5)}}
                </td>
                <td>
                    ETH {{number_format($item->start_price, 5)}}
                </td>
                <td>
                    <div>{{$item->ctd_timer}} Minutes</div> 
                    @if($item->start_time)
                        {{$item->start_time->diffForHumans()}}
                    @else 
                        <span>waiting...</span>
                    @endif 
                </td>
                <td>
                    {{$item->free_bid}} Points
                </td>
                <td>
                    <div>Required</div> 
                    <div>{{$item->start_points}}</div> 
                    <div>Total</div> 
                    <div>{{$item->points}}</div> 
                </td>
                <td>
                    {{$item->created_at->isoFormat('lll')}}
                    <div>
                        {{$item->created_at->diffForHumans()}}
                    </div> 
                </td>
                <td>
                    @if($item->status == 1)
                        <span class="badge bg-info">Started</span>
                    @elseif($item->status == 2)
                        <span class="badge bg-danger">Ended</span>
                    @elseif($item->status == 3)
                        <span class="badge bg-success">Claimed</span>
                    @else 
                        <span>waiting..</span>
                    @endif
                </td>
                <td>
                    <p> 
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#vm{{$item->id}}">View more</button>
                    </p> 
  

                    <p>
                        <a href="{{route('item.create', $item->id)}}" class="btn btn-primary btn-sm">Re-use</a>
                    </p> 
                    <p>
                        <a href="{{route('item.edit', $item->id)}}" class="btn btn-primary btn-sm">Edit</a>
                    </p> 
        
                    <form action="{{route('item.delete', $item->id)}}" method="POST">
                        @method('delete')
                        @csrf 
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    
                </td>
            </tr>
            <div class="modal fade" id="vm{{$item->id}}" tabindex="-1" role="dialog"  aria-hidden="true">
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
                        <br>
                        <h5>Description</h5>
                        {{$item->description}}
                        <br>
                        <h5>Winner</h5>
                        @if($item->status > 1)
                            @php $winner = $item->the_winner() @endphp
                            @if($winner)
                                {{$winner->fname.' '.$winner->lname}}
                                <div>{{$item->winner}}</div>
                            @endif 
                        @endif 
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