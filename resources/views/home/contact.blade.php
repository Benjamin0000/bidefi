@include('includes.header')
@include('includes.page_title', ['name'=>'Contact Us'])
<section class="tf-login tf-section">
    <div class="themesflat-container">
        <div class="row justify-content-center">
            <div class="col-8">
                <h2 class="tf-title-heading ct style-1">
                  We want to hear from you!
                </h2>
                <div class="flat-form box-login-email">
                    <div class="form-inner">
                        @if(session('success'))
                            <div class="alert alert-success">
                                    <h4>Message Sent!</h4>
                                    <div>You will hear from us soon.</div> 
                            </div> 
                        @else 
                            <form method="POST" action="">
                                <input name="name" required type="text" placeholder="Enter Your fullname">
                                <input  name="email" required type="email" placeholder="Enter your email">
                                <input  name="mobile" required type="text" placeholder="Enter your mobile number with country code">
                                <textarea name="message" id="" required placeholder="Enter your message" cols="30" rows="10"></textarea>
                                @csrf 
                                <button class="submit">Send</button>
                            </form>
                        @endif 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes.footer')
