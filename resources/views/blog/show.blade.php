@include('includes.header')
<!-- title page -->
<section class="flat-title-page inner">
    <div class="overlay"></div>
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading mg-bt-12">
                    <h1 class="heading text-center">Blog</h1>
                </div>
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/">Blog</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>                    
</section>

<div class="tf-section post-details">
    <div class="themesflat-container">
        <div class="wrap-flex-box style">
            <div class="post">
                <div class="inner-content">
                    <h2 class="title-post">{{$blog->title}}</h2>
                    <div class="divider"></div>
                    <div class="meta-post flex mg-bt-31">
                        <div class="box">
                            <div class="inner">
                                <h6 class="desc">DATE</h6>
                                <p>{{$blog->created_at->isoFormat('ll')}}</p>
                            </div>
                        </div>
                        <div class="box left">
                            {{-- <div class="inner boder pad-r-50">
                                <h6 class="desc">WRITER</h6>
                                <p>DWINAWAN</p>
                            </div> --}}
                            <div class="inner mg-l-39 mg-r-1">
                                {{-- <h6 class="desc">DATE</h6>
                                <p>{{$blog->created_at->isoFormat('ll')}}</p> --}}
                            </div>
                        </div>
                    </div>
                    <div class="image">
                        <img src="{{$blog->poster}}" alt="Image">
                    </div> 

                    <div class="inner-post mg-t-40" style="font-size:15px;">
                      {!! $blog->body !!}
                    </div>   

         
                    <br>
                    <br>
                    {{-- <div class="sc-widget style-1">
                        <div class="widget widget-social style-2">
                            <h4 class="title-widget">Share:</h4>
                            <ul>
                                <li><a href="blog-details.html#" class="icon-fl-facebook"></a></li>
                                <li class="style-2"><a href="blog-details.html#" class="icon-fl-coolicon"></a></li>
                                <li class="mgr-none"><a href="blog-details.html#" class="icon-fl-mess"></a></li>
                            </ul>
                        </div>
                    </div>     --}}
                    <div class="divider d2"></div>
                </div>
            </div>
            <div class="side-bar details">
                <div class="widget widget-recent-post mg-bt-43">
                    <h3 class="title-widget mg-bt-23">Recent Post</h3>
                    <ul>
                        @foreach($blogs as $blog)
                            <li class="box-recent-post">
                                <div class="box-feature"><a href="/blog/{{$blog->slug}}"><img src="{{$blog->poster}}" alt="image"></a></div>
                                <div class="box-content">
                                    <a href="/blog/{{$blog->slug}}" class="title-recent-post">{{$blog->title}}</a>
                                    <span><span class="sub-recent-post">{{substr($blog->caption, 0, 26)}}....</span><a href="/blog/{{$blog->slug}}" class="day-recent-post">{{$blog->created_at->isoFormat('ll')}}</a></span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function(){
        setTimeout(() => {
            $.get('/adsfasd/{{$blog->id}}')
        }, 10000);
    }
</script>
@include('includes.footer')