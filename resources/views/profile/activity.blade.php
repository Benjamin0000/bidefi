@include('includes.header')
@include('includes.page_title', ['name'=>'Bid Activities'])
<style>.sc-button:hover{color:white;}</style> 
<section class="tf-login tf-section">
    <div class="themesflat-container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-12">
                @foreach ($activities as $activity)
                    <div class="sc-card-activity style1">
                        <div class="content">
                            <div class="media">
                                <img src="{{$activity->item->image}}" alt="">
                            </div> 
                            <div class="infor">
                                <h3><a href="{{route('auction.show', $activity->item->id)}}">{{$activity->item->name}}</a></h3>
                                <div class="time">{{$activity->created_at->isoFormat('lll')}}</div>
                                <div class="time"><b>Total Bid: {{$activity->points}} Credit</b></div>
                            </div>
                        </div>
                       <a href="{{route('auction.show', $activity->item->id)}}" style="font-size:15px;" class="sc-button style bag fl-button pri-3">View</a>
                    </div>
                @endforeach         
               {{$activities->links()}}
            </div>
        </div>
    </div>
</section>
@include('includes.footer')