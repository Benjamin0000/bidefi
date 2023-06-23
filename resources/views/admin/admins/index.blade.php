@extends('admin.layout')
@section('content')
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Admins</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <h5>Create admin</h5>
        <form method="POST" action="/admin/admins" id="create_admin_form">
            <label for="">Fullname</label>
            <p>
                <input type="text"  class="form-control" name="name" required placeholder="Enter admin name">
            </p>
            <label for="">Address</label>
            <p>
                <input type="text"  class="form-control" name="address" required placeholder="Enter admin address">
            </p>
            @csrf 
            <p>
                <button class="btn btn-primary">Update</button>
            </p>
        </form>
    </div>
    <div class="col-md-8">
        <h5>All admins</h5>
        <div class="table-responsive"> 
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Created</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @php $count = tableNumber(10) @endphp 
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{$count++}}</td>
                            <td>{{$admin->name}}</td>
                            <td>{{$admin->address}}</td>
                            <td>
                                {{$admin->created_at->isoFormat('lll')}}
                                <div>{{$admin->created_at->diffForHumans()}}</div> 
                            </td>
                            <td>
                                <form class="remove_admin" action="/admin/admins/{{$admin->id}}" method="POST">
                                    @csrf 
                                    @method('delete')
                                    <input type="hidden" name="address" value="{{$admin->address}}">
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach 
                </tbody>
            </table>
        </div> 
        {{$admins->links()}}
    </div>
</div>
@stop 