@include('includes.header')
<!-- title page -->
<section class="flat-title-page inner">
    <div class="overlay"></div>
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading mg-bt-12">
                    <h1 class="heading text-center">FAQ</h1>
                </div>
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li>FAQ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>                    
</section>

            <!-- flat-accordion -->
            <section class="tf-section wrap-accordion">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="tf-title-heading ct style-2 fs-30 mg-bt-10">
                                Frequently Asked Questions
                            </h2>
                            {{-- <h5 class="sub-title help-center mg-bt-32 ">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum obcaecati dignissimos quae quo ad iste ipsum officiis deleniti asperiores sit.
                            </h5> --}}
                        </div>
                        <div class="col-md-12">
                            <div class="flat-accordion2">
                                
                                @foreach($faqs as $faq)

                                    <div class="flat-toggle2">
                                        <h6 class="toggle-title {{$loop->index == 0 ? 'active' : ''}}">{{$faq->q}}?</h6>
                                        <div class="toggle-content">
                                            <p>
                                                {{$faq->a}}
                                            </p>
                                        </div>
                                    </div>
                                    
                                @endforeach 
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- flat-accordion -->  

@include('includes.footer')
