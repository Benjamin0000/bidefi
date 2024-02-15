<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bidefi Admin</title>
    <meta name="description"content="">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/logo/logo.png">
    <link rel="stylesheet" href="/admin/css/style.css?v=3">
    <style>
    .modal{background: rgba(0, 0, 0, 0.5); }
    .modal-backdrop {display: none;}
    </style>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({selector:'#blog_textarea'});</script>
</head>
<body class="@@dashboard">
<div id="preloader"><i>.</i><i>.</i><i>.</i></div>
<div id="main-wrapper" class="admin" style="padding-top:20px">
    <div class="header"> 
    <div class="container">
        <div class="row">
            <div class="col-xxl-12">
                <div class="header-content">
                    <div class="header-left">
                        {{-- <div class="brand-logo"><a class="mini-logo" href="index.html"><img src="/admin/images/logoi.png" alt=""
                                    width="40"></a></div>
                        <div class="search">
                            <form action="dashboard.html#"><span><i class="ri-search-line"></i></span><input type="text"
                                    placeholder="Search Here"></form>
                        </div> --}}
                    </div>
                    <div class="header-right">
                        {{-- <div class="theme-switch"><i class="ri-sun-line"></i></div>  --}}
                        <div class="dark-light-toggle theme-switch" onclick="themeToggle()">
                            <span class="dark"><i class="ri-moon-line"></i></span>
                            <span class="light"><i class="ri-sun-line"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="sidebar">
    <div class="brand-logo"><a class="full-logo" href="/"><img src="/assets/images/logo/logo.png" alt="" width="50"></a></div>
    <div class="menu">
        <ul>
            <li>
                <a href="/admin/dashboard">
                    <span><i class="ri-layout-grid-fill"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <li class="">
                <a href="/admin/media">
                    <span><i class="ri-briefcase-line"></i></span>
                    <span class="nav-text">Media</span></a>
            </li>
            <li class="">
                <a href="/admin/items">
                    <span><i class="ri-heart-line"></i></span>
                    <span class="nav-text">Items</span></a>
            </li>
            <!-- <li class="">
                <a href="./collection.html">
                    <span><i class="ri-star-line"></i></span>
                    <span class="nav-text">Collection</span></a>
            </li> -->
            <li class="">
                <a href="/admin/admins">
                    <span><i class="ri-account-box-line"></i></span>
                    <span class="nav-text">Admins</span></a>
            </li>
            <li class="">
                <a href="/admin/withdrawal">
                    <span><i class="ri-heart-line"></i></span>
                    <span class="nav-text">Withdrawal</span></a>
            </li> 

            <li class="">
                <a href="{{route('admin.blog.index')}}">
                    <span><i class="ri-heart-line"></i></span>
                    <span class="nav-text">Blogs</span></a>
            </li> 
            <li class="">
                <a href="{{route('admin.faq.index')}}">
                    <span><i class="ri-heart-line"></i></span>
                    <span class="nav-text">FAQ</span></a>
            </li> 
            <li class="">
                <a href="{{route('admin.points.index')}}">
                    <span><i class="ri-heart-line"></i></span>
                    <span class="nav-text">Points</span></a>
            </li> 
            <li class="">
                <a href="/admin/settings">
                    <span><i class="ri-settings-3-line"></i></span>
                    <span class="nav-text">Settings</span></a>
            </li>
            <li class="">
                <a href="/">
                    <span><i class="ri-logout-circle-line"></i></span>
                    <span class="nav-text">Leave</span>
                </a>
            </li>
            <br>
            <br>
            <li>
                <a href="#">
                    <span>Net:</span>
                    <span class="nav-text net_show"></span>
                </a>
            </li>
        </ul>
    </div>
</div>
    <div class="content-body">
        <div class="container-fluid">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>

@auth 
<script>
    window.auth_address = {{Auth::user()->address}}; 
</script>
@endauth
<script src="/admin/vendor/jquery/jquery.min.js"></script>
<script src="/admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js" integrity="sha512-efUTj3HdSPwWJ9gjfGR71X9cvsrthIA78/Fvd/IN+fttQVy7XWkOAXb295j8B3cmm/kFKVxjiNYzKw9IQJHIuQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@if(session('success'))
<script>
    $.notify('{{session('success')}}', "success");
</script>
@elseif(session('error'))
<script>
    $.notify('{{session('error')}}', "error");
</script>
@endif 
<script src="/admin/js/scripts.js"></script>
@vite('resources/js/app.js')
</body>
</html>