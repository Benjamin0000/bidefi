@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Points  <a href="#" data-bs-toggle="modal" data-bs-target="#point_modal" class="btn btn-primary btn-sm">Create</a></h3>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Bid items</th>
                <th>Points</th>
                <th>Network</th>
                <th>Title</th>
                <th>Created</th>
                <th>Expiry Date</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @php $no = tableNumber(10) @endphp 
            @foreach($points as $point)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$point->bid}}</td>
                    <td>{{$point->reward}}</td>
                    <td>{{get_network_name($point->network)}}</td>
                    <td>{{$point->title}}</td>
                    <td>
                        {{$point->created_at->isoFormat('ll')}}
                    </td>
                    <td>
                        {{$point->expiry_date->isoFormat('ll')}}
                    </td>
                    <td>
                        <form action="{{route('admin.points.delete_points', $point->id)}}" method="POST">
                            @csrf 
                            @method('delete')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('are you sure about this?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="point_modal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Create point</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                <form action="{{route('admin.points.create_points')}}" method="POST">
                    <div class="form-group">
                        <label for="">Bid Items</label>
                        <input type="number" name="bid" required class="form-control">
                    </div> @csrf 
                    <div class="form-group">
                        <label for="">Points</label>
                        <input type="number" name="reward" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Network</label>
                        <select name="network" id="" required class="form-control">
                            <option value="">Select</option>
                            @foreach(all_networks() as $network)
                                <option value="{{$network}}">{{get_network_name($network)}}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Title</label>
                        <textarea name="title" required class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Expiry Days</label>
                        <input name="days" type="number" required class="form-control">
                    </div>
                    <br>
                    <div class="form-group">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </form>
          </div>
        </div>
    </div>
</div>
@stop 