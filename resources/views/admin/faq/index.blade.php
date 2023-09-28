@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">

                <h3>FAQ  <a href="{{route('admin.faq.create')}}" class="btn btn-primary btn-sm">Create</a></h3>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Created</th>
            <th>More</th>
        </tr>
    </thead>
    <tbody>
        @php $no = tableNumber(10) @endphp 
        @foreach($faqs as $faq)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$faq->q}}</td>
                <td>{{$faq->a}}</td>
                <td>
                    {{$faq->created_at->isoFormat('lll')}}
                    <div>
                        {{$faq->created_at->diffForHumans()}}
                    </div> 
                </td>
                <td>
                    <p>
                        <a href="{{route('admin.faq.create', $faq->id)}}" class="btn btn-primary btn-sm">Edit</a>
                    </p> 
        
                    <form action="{{route('admin.faq.delete', $faq->id)}}" method="POST">
                        @method('delete')
                        @csrf 
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    
                </td>
            </tr>
        @endforeach 
    </tbody>
</table>
</div>
{{$faqs->links()}}
@stop 