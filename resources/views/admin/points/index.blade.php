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