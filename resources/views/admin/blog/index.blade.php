@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">

                <h3>Blogs  <a href="{{route('admin.blog.create')}}" class="btn btn-primary btn-sm">Create</a></h3>
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
            <th>Poster</th>
            <th>Created</th>
            <th>Views</th>
            <th>Status</th>
            <th>More</th>
        </tr>
    </thead>
    <tbody>
        @php $no = tableNumber(10) @endphp 
        @foreach($blogs as $blog)
            <tr>
                <td>{{$no++}}</td>
                <td>{{$blog->title}}</td>
                <td><img src="{{$blog->poster}}" width="100"></td>
                <td>
                    {{$blog->created_at->isoFormat('lll')}}
                    <div>
                        {{$blog->created_at->diffForHumans()}}
                    </div> 
                </td>
                <td>
                    {{$blog->views}}
                </td>

                <td>
                    @if($blog->publish == 1)
                        <span class="badge bg-success">Published</span>
                    @else 
                        <span>hidden</span>
                    @endif
                </td>

                <td>
                    <p>
                        <form action="{{route('admin.blog.publish', $blog->id)}}" method="POST">
                            @csrf 
                            @method('patch')
                            @if($blog->publish)
                                <button class="btn btn-info">Unpublish</button>
                            @else 
                                <button class="btn btn-primary">Publish</button>
                            @endif 
                        </form>
                    </p>
                    <p> 
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#vm{{$blog->id}}">View more</button>
                    </p> 
  
                    <p>
                        <a href="{{route('admin.blog.create', $blog->id)}}" class="btn btn-primary btn-sm">Edit</a>
                    </p> 
        
                    <form action="{{route('admin.blog.delete', $blog->id)}}" method="POST">
                        @method('delete')
                        @csrf 
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    
                </td>
            </tr>
            <div class="modal fade" id="vm{{$blog->id}}" tabindex="-1" role="dialog"  aria-hidden="true">
                <div class="modal-dialog modal-lg" >
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">{{$blog->title}}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <h6>Blog caption</h6>
                        {!!$blog->caption!!}
                        <br>
                        <h6>Blog content</h6>
                        {!!$blog->body!!}
                      </div>
                    </div>
                </div>
            </div>
        @endforeach 
    </tbody>
</table>
</div>
{{$blogs->links()}}
@stop 