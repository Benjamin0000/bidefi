@extends('admin.layout')
@section('content')
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Dashboard</h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-sm-6">
        <div class="stat-widget d-flex align-items-center">
            <div class="widget-icon me-3 bg-primary"><span><i class="ri-file-copy-2-line"></i></span></div>
            <div class="widget-content">
                <h3>{{number_format($total_users)}}</h3>
                <p>Total Users</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-widget d-flex align-items-center">
            <div class="widget-icon me-3 bg-success"><span><i class="ri-file-list-3-line"></i></span></div>
            <div class="widget-content">
                <h3>{{number_format($total_items)}}</h3>
                <p>Total Items</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-widget d-flex align-items-center">
            <div class="widget-icon me-3 bg-warning"><span><i class="ri-file-paper-line"></i></span></div>
            <div class="widget-content">
                <h3>{{(int)get_register('total_credits')}}</h3>
                <p>Total credit purchased</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-widget d-flex align-items-center">
            <div class="widget-icon me-3 bg-danger"><span><i class="ri-file-paper-2-line"></i></span></div>
            <div class="widget-content">
                <h3>${{(float)get_register('total_fees')}}</h3>
                <p>Total Fees</p>
            </div>
        </div>
    </div>
</div>

<h4>Users</h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Bid Credit</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @php $count = tableNumber(10) @endphp 
            @foreach($users as $user)
            <tr>
                <td>{{$count++}}</td>
                <td>{{$user->fname.' '.$user->lname}}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->address}}</td>
                <td>{{$user->bid_credit}}</td>
                <td>
                    {{$user->created_at->isoFormat('lll')}}
                    <div>{{$user->created_at->diffForHumans()}}</div> 
                </td>
            </tr>
            @endforeach 
        </tbody>
    </table>
</div>
{{$users->links()}}
@stop 