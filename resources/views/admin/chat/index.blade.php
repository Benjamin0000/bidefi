@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Chat</h3>
            </div>
        </div>
    </div>
</div>
<h6>Baned words</h6>
<form action="{{route('word_ban')}}" method="POST">
    <div class="form-group">
        <textarea name="words" required id="" placeholder="Eg. Fuck, damit" class="form-control">{{get_register('baned_words')}}</textarea>
    </div>
    @csrf 
    <br>
    <div class="form-group">
        <button class="btn btn-primary">Save</button>
    </div>
</form>

<br>
<h5 class="text-center">Messages</h5>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Message</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php $no = tableNumber(10) @endphp 
            @foreach($msgs as $msg)
                <tr>
                    <td>{{$no++}}</td>
                    <td>
                        {{$msg->user->fname.' '.$msg->user->lname}}
                        <div>{{$msg->user->address}}</div>
                    </td>
                    <td style="width: 150px; word-break:break-all;">
                        @if($msg->user->admin)
                            {!!$msg['msg']!!}
                        @else 
                            {{ $msg['msg'] }}
                        @endif
                    </td>
                    <td>
                        {{$msg->created_at->diffForHumans()}}
                    </td>
                    <td>
                        <form action="{{route('delete_msg', $msg->id)}}" method="POST" style="display: inline-block">
                            @csrf 
                            @method('delete')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure')">Delete</button>
                        </form>

                        <form action="{{route('user_chat_ban', $msg->id)}}" method="POST" style="display: inline-block">
                            @csrf 
                            @method('patch')

                            @if(!$msg->user->msg_ban)
                                <button class="btn btn-info btn-sm">Ban user</button>
                            @else 
                                <button class="btn btn-primary btn-sm">Unban user</button>
                            @endif 
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br>
{{$msgs->links()}}
@stop 