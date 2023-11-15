@include('includes.header')
@include('includes.page_title', ['name'=>'Completed Auctions'])
<section class="tf-login tf-section">
    <div class="themesflat-container">
        <div class="row" id="item_con">
            @include('auction.component.item', ['auctions'=>$items])
        </div>
        @if($items->count() == 12)
        <div class="text-center">
            <button class="btn btn-primary" data-id='3' id="llmore">Load more</button>
        </div> 
        @endif
    </div>
</section>
@include('includes.footer')