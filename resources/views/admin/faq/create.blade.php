@extends('admin.layout')
@section('content')
<br>
<br>
<div class="page-title">
    <div class="row align-items-center justify-content-between">
        <div class="col-6">
            <div class="page-title-content">
                <h3>Create FAQ</h3>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{route('admin.faq.save', $faq?$faq->id: '')}}">
    <div>
        <h4 class="title-create-item">Question</h4>
        <input type="text" name="q" value="{{$faq ? $faq->q: ''}}" class="form-control" required placeholder="Enter query">
    </div>
     
    <br>
    <div>
        <h4 class="title-create-item">Answer</h4>
        <input type="text" name="a" value="{{$faq ? $faq->a: ''}}" class="form-control" required placeholder="Enter Answer">
    </div>

   @csrf 
   <br>
    <p>
        <button class="btn btn-primary">Save</button>
    </p>
</form>
@stop 