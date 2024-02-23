@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-2">
            <div class="page-title-content">
                <h3>Items  <a href="/admin/create-items" class="btn btn-primary btn-sm">Create</a></h3>
            </div>
        </div>
        <div class="col-10">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Optional">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="">Network</label>
                            <select name="network" class="form-control">
                                <option value="">All</option>
                                @foreach(all_networks() as $net)
                                    <option value="{{$net}}">{{get_network_name($net)}}</option>
                                @endforeach 
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <label for="">Type</label>
                        <select name="type" class="form-control">
                            <option value="">All</option>
                            <option value="1">Free</option>
                            <option value="0">Paid</option>
                        </select>
                    </div> 
                    <div class="col-2">
                        <br>
                        <div class="form-group">
                            <button class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </form>
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
                        {{$item->prize}} {{$item->symbol}}
                    @elseif($item->type == 4)
                        <div>Native token</div> 
                        {{$item->prize}} {{$item->symbol}}
                    @endif 
                    <div class="text-info">
                        {{get_network_name($item->network)}}
                    </div> 
                </td>
                <td>
                    ${{number_format($item->price, 2)}}
                </td>
                <td>
                    ${{$item->bid_price}}
                </td>
                <td>
                    ${{$item->start_price}}
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
                        <form action="{{route('item.update_contract', $item->id)}}" class="update_contract" method="POST">
                            @method('put')
                            @csrf
                            <input type="hidden" name="id" value="{{$item->id}}">
                            <input type="hidden" name="_id" value="{{$item->_id}}">
                            <input type="hidden" name="prize" value="{{$item->prize}}">
                            <input type="hidden" name="type" value="{{$item->type}}">
                            <input type="hidden" name="start_time" value="{{$item->ctd_timer}}">
                            <input type="hidden" name="free" value="{{$item->free_bid}}">
                            <input type="hidden" name="start_points" value="{{$item->start_points}}">
                            <input type="hidden" name="share" value="{{$item->share}}">
                            <input type="hidden" name="decimal" value="{{$item->decimal}}">
                            <input type="text" required name="contract_address" value="{{$item->contract_address}}" class="form-control">
                            <br>
                            
                            <button id="jj{{$item->id}}" class="btn btn-primary">Update</button>
                            
                            <p class="msg" id="msg{{$item->id}}"></p>
                        </form>
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