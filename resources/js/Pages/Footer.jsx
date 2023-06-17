const Footer = ()=>{
    return (
        <footer id="footer" className="footer-light-style clearfix">
            <div className="themesflat-container">
                <div className="row">
                    <div className="col-lg-3 col-md-12 col-12">
                        <div className="widget widget-logo">
                            <div className="logo-footer" id="logo-footer">
                                <a href="index.html">
                                    <img id="logo_footer" src="assets/images/logo/logo_dark.png" alt="nft-gaming" width="135" height="56"
                                    data-retina="/assets/images/logo/logo_dark@2x.png" data-width="135"
                                    data-height="56"/>
                                </a>
                            </div>
                            <p className="sub-widget-logo">Lorem ipsum dolor sit amet,consectetur adipisicing elit. Quis non, fugit totam vel laboriosam vitae.</p>
                        </div>
                    </div>
                    <div className="col-lg-2 col-md-4 col-sm-5 col-5">
                        <div className="widget widget-menu style-1">
                            <h5 className="title-widget">My Account</h5>
                            <ul>
                                <li><a href="author01.html">Authors</a></li>
                                <li><a href="connect-wallet.html">Collection</a></li>
                                <li><a href="profile.html">Author Profile</a></li>
                                <li><a href="create-item.html">Create Item</a></li>
                            </ul>
                        </div>
                    </div>
                    <div className="col-lg-2 col-md-4 col-sm-7 col-7">
                        <div className="widget widget-menu style-2">
                            <h5 className="title-widget">Resources</h5>
                            <ul>
                                <li><a href="help-center.html">Help & Support</a></li>
                                <li><a href="auctions.html">Live Auctions</a></li>
                                <li><a href="item-details.html">Item Details</a></li>
                                <li><a href="activity1.html">Activity</a></li>
                            </ul>
                        </div>
                    </div>
                    <div className="col-lg-2 col-md-4 col-sm-5 col-5">
                        <div className="widget widget-menu fl-st-3">
                            <h5 className="title-widget">Company</h5>
                            <ul>
                                <li><a href="explore-1.html">Explore</a></li>
                                <li><a href="contact1.html">Contact Us</a></li>
                                <li><a href="blog.html">Our Blog</a></li>
                                <li><a href="faq.html">FAQ</a></li>
                            </ul>
                        </div>
                    </div>
                    <div className="col-lg-3 col-md-6 col-sm-7 col-12">
                        <div className="widget widget-subcribe">
                            <h5 className="title-widget">Subscribe Us</h5>
                            <div className="form-subcribe">
                                <form id="subscribe-form" action="home6.html#" method="GET" acceptCharset="utf-8" className="form-submit">
                                    <input name="email" value="" onChange={()=>{}} className="email" type="email" placeholder="info@yourgmail.com" required/>
                                    <button id="submit" name="submit" type="submit"><i className="icon-fl-send"></i></button>
                                </form>
                            </div>
                            <div className="widget-social style-1 mg-t32">
                                <ul>
                                    <li><a href="home6.html#"><i className="fab fa-twitter"></i></a></li>
                                    <li><a href="home6.html#"><i className="fab fa-facebook"></i></a></li>
                                    
                                    <li className="style-2"><a href="home6.html#"><i className="fab fa-telegram-plane"></i></a></li>
                                    <li><a href="home6.html#"><i className="fab fa-youtube"></i></a></li>
                                    <li className="mgr-none"><a href="home6.html#"><i className="icon-fl-tik-tok-2"></i></a></li>
                                    <li className="mgr-none"><a href="home6.html#"><i className="icon-fl-vt"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       </footer>
        
    )
}


export default Footer;