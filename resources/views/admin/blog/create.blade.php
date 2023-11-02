@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Create Blog</h3>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{route('admin.blog.save', $blog?$blog->id: '')}}">
    <div>
        <h4 class="title-create-item">Poster (URL)</h4>
        <input type="text" name="poster" value="{{$blog ? $blog->poster: ''}}" class="form-control" required placeholder="Enter Url">
    </div>
     
    <br>
    <div>
        <h4 class="title-create-item">Title</h4>
        <input type="text" name="title" value="{{$blog ? $blog->title: ''}}" class="form-control" required placeholder="Enter Title">
    </div>
    <br>
    <div>
        <h4 class="title-create-item">Caption</h4>
        <input type="text" name="caption" value="{{$blog ? $blog->caption: ''}}" class="form-control" required placeholder="Enter Caption">
    </div>
    <br> @csrf 
    <p>
        <h4 class="title-create-item">Body</h4>
        <textarea name="body" id="blog_textarea" class="form-control" required placeholder="e.g.">{!!$blog ? $blog->body: ''!!}</textarea>
    </p>
    <br>
    <p>
        <button class="btn btn-primary">Save</button>
    </p>
</form>
@stop 