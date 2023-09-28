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
                        <li>Blog</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>                    
</section>

<div class="tf-section sc-card-blog dark-style2">
    <div class="themesflat-container">
        <div class="row">
            @foreach($blogs as $blog)
                <div class="fl-blog fl-item2 col-lg-4 col-md-6">
                    <article class="sc-card-article">
                        <div class="card-media">
                            <a href="/blog/{{$blog->slug}}"><img src="{{$blog->poster}}" style="height:300px; object-fit: cover;" alt=""></a>
                        </div>
                        <div class="meta-info">
                            <div class="date">{{$blog->created_at->isoFormat('ll')}}</div>
                        </div>
                        <div class="text-article">
                            <h3><a href="/blog/{{$blog->slug}}">{{$blog->title}}</a></h3>
                            <p>{{$blog->caption}}...</p>
                        </div>
                        <a href="/blog/{{$blog->slug}}" class="sc-button fl-button pri-3"><span>Read More</span></a>
                    </article>
                </div>
            @endforeach
                
            <div class="col-md-12 wrap-inner load-more text-center mg-t-10">
                {{-- <a href="blog.html"  class="sc-button loadmore fl-button pri-3"><span>Load More</span></a> --}}
                {{$blogs->links()}}
            </div>
        </div>
    </div>
</div>
@include('includes.footer')
