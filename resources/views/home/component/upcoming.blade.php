<section class="tf-section live-auctions style3 home5 mobie-pb-70 bg-style3">
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="heading-live-auctions mg-bt-24">
                    <h2 class="tf-title">
                        Upcoming</h2>
                    <a href="explore-3.html" class="exp style2">EXPLORE MORE</a>
                </div>
            </div>
            @include('auction.component.item', ['auctions'=>$upcomings])
        </div>
    </div>
</section>
