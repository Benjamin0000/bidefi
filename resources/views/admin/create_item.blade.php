@include('includes.header')
<!-- title page -->
<section class="flat-title-page inner">
    <div class="overlay"></div>
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading mg-bt-12">
                    <h1 class="heading text-center">Create Item</h1>
                </div>
                
            </div>
        </div>
    </div>                    
</section>

<div class="tf-create-item tf-section">
    <div class="themesflat-container">
        <div class="row">
             <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                 <h4 class="title-create-item">Preview item</h4>
                <div class="sc-card-product">
                    <div class="card-media">
                        <a href="item-details.html"><img src="assets/images/box-item/image-box-6.jpg" alt="Image"></a>
                        <button class="wishlist-button heart"><span class="number-like"> 100</span></button>
                        <div class="featured-countdown">
                            <span class="slogan"></span>
                            <span class="js-countdown" data-timer="716400" data-labels=" :  ,  : , : , "></span>
                        </div>
                    </div>
                    <div class="card-title">
                        <h5><a href="item-details.html">"Cyber Doberman #766”</a></h5>
                        <div class="tags">bsc</div>
                    </div>
                    <div class="meta-info">
                        <div class="author">
                            <div class="avatar">
                                <img src="assets/images/avatar/avt-9.jpg" alt="Image">
                            </div>
                            <div class="info">
                                <span>Owned By</span>
                                <h6> <a href="author02.html">Freddie Carpenter</a></h6>
                            </div>
                        </div>
                        <div class="price">
                            <span>Current Bid</span>
                            <h5> 4.89 ETH</h5>
                        </div>
                    </div>
                    <div class="card-bottom">
                        <a href="create-item.html#" data-toggle="modal" data-target="#popup_bid" class="sc-button style bag fl-button pri-3"><span>Place Bid</span></a>
                        <a href="activity1.html" class="view-history reload">View History</a>
                    </div>
                </div>
             </div>
             <div class="col-xl-9 col-lg-6 col-md-12 col-12">
                 <div class="form-create-item">
                    <form action="create-item.html#">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="title-create-item">Image/Video (URL)</h4>
                                <input type="text" name="image" placeholder="Enter url">
                            </div>
                            <div class="col-lg-6">
                                <h4 class="title-create-item">Select Type</h4>
                                <select name="image_type" class="select_tag" id="">
                                    <option value="">Select</option>
                                    <option value="0">Image</option>
                                    <option value="1">Video</option>
                                </select>
                            </div>
                        </div>     
                        <br>
                        <p>
                            <h4 class="title-create-item">URL (for nfts)</h4>
                            <input type="text" name="url" placeholder="(optional)">
                        </p>
                        <br>
                        <p>
                            <h4 class="title-create-item">Title</h4>
                            <input type="text" name="title" placeholder="Item Name">
                        </p>

                        <br>
                        <p>
                            <div class="row">
                                <div class="col-lg-4">
                                    <h4 class="title-create-item">Price Est.</h4>
                                    <input type="text" name="price" placeholder="Enter price for item (ETH)">
                                </div>
                                <div class="col-lg-4">
                                    <h4 class="title-create-item">Bid start price</h4>
                                    <input type="text" name="start_price" placeholder="in ETH">
                                </div>
                                <div class="col-lg-4">
                                    <h4 class="title-create-item">Prize</h4>
                                    <input type="text" name="prize" placeholder="Only for tokens">
                                </div>
                            </div> 
                        </p> 
                        <br>
                        <p>
                            <h4 class="title-create-item">Description</h4>
                            <textarea name="description" placeholder="e.g."></textarea>
                        </p>
                        <br>

                        <div class="row-form style-3">
                            <div class="inner-row-form">
                                <h4 class="title-create-item">Start Time</h4>
                                <input type="text" name="start_time" placeholder="In minutes">
                            </div>
                            <div class="inner-row-form">
                                <h4 class="title-create-item">Free Bid?</h4>
                                <input type="text" name="free" placeholder="eg: 100">
                            </div>
                            <div class="inner-row-form">
                                <h4 class="title-create-item">Symbol</h4>
                                <input type="text" name="symbol" placeholder="If erc-20”">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="title-create-item">Type</h4>
                                <select name="type" class="select_tag" id="">
                                    <option value="">Select</option>
                                    <option value="1">Erc-721</option>
                                    <option value="2">Erc-1155</option>
                                    <option value="3">Erc-20</option>
                                    <option value="4">ETH-Native</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <h4 class="title-create-item">Contract address</h4>
                                <input type="text" name="contract_address"  placeholder="If NFT or erc20">
                            </div>
                        </div>

                        <br>
                        <br>
                        <p>
                            <button class="sc-button style bag">Create</button>
                        </p>
                    </form>
                       
                 </div>
             </div>
        </div>
    </div>
</div>

@include('includes.footer')
