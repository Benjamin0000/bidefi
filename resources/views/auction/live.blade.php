@include('includes.header')
@include('includes.page_title', ['name'=>'Live Auctions'])
<section class="tf-login tf-section">
    <div class="themesflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="tf-soft">
                    <div class="soft-left">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 10H7C9 10 10 9 10 7V5C10 3 9 2 7 2H5C3 2 2 3 2 5V7C2 9 3 10 5 10Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17 10H19C21 10 22 9 22 7V5C22 3 21 2 19 2H17C15 2 14 3 14 5V7C14 9 15 10 17 10Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M17 22H19C21 22 22 21 22 19V17C22 15 21 14 19 14H17C15 14 14 15 14 17V19C14 21 15 22 17 22Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M5 22H7C9 22 10 21 10 19V17C10 15 9 14 7 14H5C3 14 2 15 2 17V19C2 21 3 22 5 22Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>                                        
                                <span class="inner" id="live_pp">Category</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a class="dropdown-item live_category" pp_name='all' href="javascript:void(0)">
                                <div class='sort-filter active'>
                                    <span>All</span>
                                    {{-- <i class="fal fa-check"></i> --}}
                                </div>
                              </a>
                              <a class="dropdown-item live_category" pp_name='Free' href="javascript:void(0)">
                                <div class='sort-filter'>
                                    <span>Free</span>
                                    {{-- <i class="fal fa-check"></i> --}}
                                </div>
                              </a>
                              <a class="dropdown-item live_category" pp_name='Paid' href="javascript:void(0)">
                                <div class='sort-filter'>
                                    <span>Paid</span>
                                </div>
                              </a>
                             
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.16992 7.44043L11.9999 12.5504L20.7699 7.47043" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 21.61V12.54" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.92965 2.48028L4.58965 5.44028C3.37965 6.11028 2.38965 7.79028 2.38965 9.17028V14.8203C2.38965 16.2003 3.37965 17.8803 4.58965 18.5503L9.92965 21.5203C11.0696 22.1503 12.9396 22.1503 14.0796 21.5203L19.4196 18.5503C20.6296 17.8803 21.6196 16.2003 21.6196 14.8203V9.17028C21.6196 7.79028 20.6296 6.11028 19.4196 5.44028L14.0796 2.47028C12.9296 1.84028 11.0696 1.84028 9.92965 2.48028Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="inner" net='' id="chain_name_live">Blockchain</span>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item net_live_sort" net='all' href="javascript:void(0)">
                                    <div class='sort-filter'>
                                        <span>All</span>
                                    </div>
                                </a>
                                @foreach(all_networks() as $net_id)
                                    @php  
                                        $net_name = get_network_name($net_id); 
                                    @endphp 
                                    <a class="dropdown-item net_live_sort" net_name='{{$net_name}}' net='{{$net_id}}' href="javascript:void(0)">
                                        <div class='sort-filter'>
                                            <span>{{$net_name}}</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="item_con">
            @include('auction.component.item', ['auctions'=>$items, 'class'=>'h_live'])
        </div>

        @if($items->count() == 8)
        <div class="text-center">
            <button class="btn btn-primary" data-id='1' id="llmore">Load more</button>
        </div> 
        @endif 
    </div>
</section>
<script>
window.onload = function(){
    $(document).on('click', '.net_live_sort', function(e){
        $(".h_live").hide(); 
        let net = $(e.currentTarget).attr('net');
        let net_name = $(e.currentTarget).attr('net_name');
        let live_pp = $("#live_pp").text();
        if(net == 'all'){
            $("#chain_name_live").html('Blockchain')
            $(".h_live").show();
            if( live_pp != 'Category' ){
                $(".h_live").hide();
                $('.h_live'+live_pp).show();
            }
        }else{
            $("#chain_name_live").html(net_name)
            $("#chain_name_live").attr('net', net)
            if( live_pp == 'Category' ){
                $(".h_live").hide();
            }else{
                $('.h_live'+net+'.h_live'+live_pp).show(); 
                return; 
            }
            $('.h_live'+net).show(); 
        }
    })

    $(document).on('click', '.live_category', function(e){
        $(".h_live").hide();
        let live_pp = $("#live_pp"); 
        let name = $(e.currentTarget).attr('pp_name');
        let chain = $("#chain_name_live"); 
        let chainID = $("#chain_name_live").attr('net'); 
        if(name == 'all'){
            live_pp.html("Category")
            $(".h_live").show();
            if( chain.text() != 'Blockchain' ){
                $(".h_live").hide();
                $('.h_live'+chainID).show();
            }
        }else{
            live_pp.html(name)
            if( chain.text() != 'Blockchain' ){
                $(".h_live").hide();
                $('.h_live'+chainID+".h_live"+name).show();
                return; 
            }
            $(".h_live"+name).show();
        }
    }); 
}
</script>
@include('includes.footer')