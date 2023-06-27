@extends('admin.layout')
@section('content')
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Withdrawal</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <form method="POST" action="" id="ad_withdrawal_form">
            <label for="">Amount</label>
            <p>
                <input type="text" value="" class="form-control" name="amt" required placeholder="Enter amount to withdraw in ETH">
            </p>
            @csrf 
            <p>
                <button class="btn btn-primary">Continue</button>
            </p>
        </form>
    </div>
    <div class="col-md-8">
        <h4 class="text-center">History</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Amount</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @php $no = tableNumber(10) @endphp 
                @foreach($histories as $history)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{number_format($history->amt, 4)}} ETH</td>
                        <td>
                            {{$history->created_at->isoFormat('lll')}}
                            <div>{{$history->created_at->diffForHumans()}}</div> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{$histories->links()}}
    </div>
</div>
@stop 