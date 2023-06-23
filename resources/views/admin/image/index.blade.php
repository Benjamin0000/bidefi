@extends('admin.layout')
@section('content')
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Media</h3>
                <p class="mb-2">Upload media for bidding</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <form action="/images" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <input type="file" name="image" required>
                </div>
            </div>
            <div class="col-md-4">
                <select name="type" required>
                    <option value="">Select Type</option>
                    <option value="0">Image</option>
                    <option value="1">Video</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <button class="btn btn-primary">Upload</button>
                </div>
            </div> 
        </div>
        @csrf 
    </form>
</div> 
<br>
<table class="table table-bordered">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Media</th>
            <th class="text-center">URL</th>
            <th class="text-center">Delete</th>
        </tr>
    </thead>
    <tbody >
        @php 
            $count = tableNumber(10); 
        @endphp  
        @foreach($medias as $media)
            <tr>
                <td class="text-center">{{$count++}}</td>
                <td class="text-center">
                    @if($media->type == 0)
                        <img src="{{Storage::url($media->name)}}" width="100" alt="">
                    @else 
                        <video src="{{Storage::url($media->name)}}"></video>
                    @endif 
                </td>
                <td class="text-center">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <input type="text" id="url_copy{{$media->id}}" readonly value="{{asset(Storage::url($media->name))}}" class="form-control">
                            <div class="input-group-append">
                              <span class="input-group-text btn btn-primary bg-primary" onclick="copy_input('#url_copy{{$media->id}}')">Copy</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <form method="POST" action="/media/{{$media->id}}">
                        @csrf
                        @method('delete')
                        <button onclick="return confirm('are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                    </form> 
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
{{$medias->links()}}
<script>
function copy_input(input) {
    $(input).focus();
    $(input).select();
    try {  
        var successful = document.execCommand('copy');  
    } catch(err) {  
        console.error('Unable to copy'); 
    }       
} 
</script>
@stop 