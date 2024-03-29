    <!-- Footer -->
    <footer id="footer" class="footer-light-style clearfix">
        <div class="themesflat-container">
            <div class="row">

                <div class="col-lg-3 col-md-12 col-12">
                    <div class="widget widget-logo">
                        <div class="logo-footer" id="logo-footer">
                            <a href="index.html">
                                <img id="logo_footer" src="/assets/images/logo/logo.png" alt="nft-gaming" width="50" height="56"
                                data-retina="/assets/images/logo/logo_dark@2x.png" data-width="135"
                                data-height="56">
                            </a>
                        </div>
                        <p class="sub-widget-logo">
                            The No.1 multichain Bid2Earn auction dApp that lets you save when you freely bid for the popular crypto assets and bluechip NFT arts you love.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-7 col-7">
                    <div class="widget widget-menu style-2">
                        <h5 class="title-widget">Resources</h5>
                        <ul>
                            <li><a href="/contact-us">Help & Support</a></li>
                            <li><a href="/live-auction">Live Auctions</a></li>
                            {{-- <li><a href="item-details.html">Item Details</a></li> --}}
                            {{-- <li><a href="activity1.html">Activity</a></li> --}}
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-5 col-5">
                    <div class="widget widget-menu fl-st-3">
                        <h5 class="title-widget">Company</h5>
                        <ul>
                            <li><a href="/blog">Our Blog</a></li>
                            <li><a href="/faq">FAQ</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-7 col-12">
                    <div class="widget widget-subcribe">
                        <h5 class="title-widget">Follow Us</h5>
                        {{-- <div class="form-subcribe">
                            <form id="subscribe-form" action="home6.html#" method="GET" accept-charset="utf-8" class="form-submit">
                                <input name="email" value="" class="email" type="email" placeholder="info@yourgmail.com" required>
                                <button id="submit" name="submit" type="submit"><i class="icon-fl-send"></i></button>
                            </form>
                        </div> --}}
                        <div class="widget-social style-1 mg-t32">
                            <ul>
                                <li><a href="https://twitter.com/BiDefi_Official" target="_blank"><i class="fab fa-twitter"></i></a></li>
                                <li class="style-2"><a href="https://t.me/TheBiDeFi_Official" target="_blank"><i class="fab fa-telegram-plane"></i></a></li>
                                <li><a href="/#"><i class="fab fa-youtube"></i></a></li>
                                <li class="mgr-none"><a href="https://discord.gg/35SGdmDE" target="_blank"><i class="icon-fl-vt"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- /#footer -->
    </div>
    <!-- /#page -->



    </div>
</div>
<!-- /#wrapper -->
<a id="scroll-top"></a>
<!-- Javascript -->
{{-- On page load track auth --}}
@auth 
<script>
    window.auth_address = {{Auth::user()->address}}; 
</script>
@endauth
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/main.js?v=2"></script> 
<script src="/assets/js/jquery.easing.js"></script> 
<script src="/assets/js/popper.min.js"></script> 
<script src="/assets/js/bootstrap.min.js"></script> 
<script src="/assets/js/wow.min.js"></script>  
<script src="/assets/js/plugin.js"></script> 
<script src="/assets/js/count-down.js"></script> 
<script src="/assets/js/shortcodes.js"></script> 
<script src="/assets/js/swiper-bundle.min.js"></script>
<script src="/assets/js/swiper.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js" integrity="sha512-lteuRD+aUENrZPTXWFRPTBcDDxIGWe5uu0apPEn+3ZKYDwDaEErIK9rvR0QzUGmUQ55KFE2RqGTVoZsKctGMVw==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data.min.js" integrity="sha512-rjmacQUGnwQ4OAAt3MoAmWDQIuswESNZwYcKC8nmdCIxAVkRC/Lk2ta2CWGgCZyS+FfBWPgaO01LvgwU/BX50Q==" crossorigin="anonymous"></script>

@vite('resources/js/app.js')
</body> 
</html>  