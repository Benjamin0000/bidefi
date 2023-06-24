<section class="tf-section live-auctions home5 style2 bg-style3">
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading-live-auctions">
                    <h2 class="tf-title pb-23">Completed Auctions</h2>
                    <a href="/live-auction" class="exp style2">EXPLORE MORE</a>
                </div>
            </div> 
            @include('auction.component.item', ['auctions'=>$completed])
        </div>
    </div>
</section>
