@include('includes.header')
@include('includes.page_title', ['name'=>'Profile'])
@php 
    $user = Auth::user(); 
@endphp 
<div class="tf-create-item tf-section">
    <div class="themesflat-container">
        <div class="row">
             <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                <form id="p_image_form" class="sc-card-profile text-center">
                    <div class="card-media">
                        @if($user->avatar)
                            <img id="profileimg" src="{{Storage::url($user->avatar)}}" alt="Image">                         
                        @else 
                            <img id="profileimg" src="/assets/images/avatar/avata_profile.jpg" alt="Image">                         
                        @endif 
                    </div>

                    <div id="image_error"></div> 

                    <div id="upload-profile">
                        <a href="profile.html#" class="btn-upload">Upload New Photo</a>
                        <input id="tf-upload-img" onchange="uploadPicture()" type="file" name="profile" required="">
                    </div>
                    <a href="javascript:void(0)" onclick="delete_pp()" class="btn-upload style2">Delete</a>
                </form>
             </div>
             <div class="col-xl-6 col-lg-8 col-md-12 col-12">
                 <div class="sc-card-profile">
                    @if(session('success'))
                        <div class="alert alert-success text-center"><h5>Profile updated!</h5></div> 
                    @endif 
                    <form action="/Ryi71" method="POST" class="form-profile">
                        <div class="info-account">
                            <h4 class="title-create-item">Account info</h4>                                    
                            <fieldset>
                                <h4 class="title-infor-account">First name</h4>
                                <input type="text" name="fname" value="{{$user->fname}}" placeholder="Eg: Ben" required>
                            </fieldset>
                            <br>
                            <fieldset> @csrf 
                                <h4 class="title-infor-account">Last name</h4>
                                <input type="text" name="lname" value="{{$user->lname}}" placeholder="Eg: Johnson" required>
                            </fieldset>
                            <br>
                            <fieldset>
                                <h4 class="title-infor-account">Email</h4>
                                <input type="email" name="email" value="{{$user->email}}" placeholder="Enter your email"  required>
                            </fieldset>
                        </div>
                        <button class="tf-button-submit mg-t-15" type="submit">
                            Update Profile
                        </button>           
                    </form>
                
                    <br>
                    <br>
                    <h5 style="margin-bottom:5px;">Your Referral Link</h5>
                    <div class="input-group">
                        <input type="text" id="address_input" style="color:black" class="form-control form-control-xl rounded-start-4"
                            value="{{url('/')}}?ref={{$user->ref_id}}" readonly
                            aria-label="Dollar amount (with dot and two decimal places)">
                        <button id="copy_address" style="background:#5142FC; color:white; " class="input-group-text btn btn-primary rounded-end-4">
                            <span class="ms-2">COPY LINK</span>
                        </button>
                    </div>
                </div> 
             </div>
        </div>
    </div>
</div>
<script>
    const Msg = (type, msg)=>{
        switch(type){
            case 'error': 
                $("#image_error").html(`<div class='alert alert-danger'><h6>${msg}</h6></div>`); 
            break; 
            case 'success': 
                $("#image_error").html(`<div class='alert alert-success'><h6>${msg}</h6></div>`);
        }
    }

    const delete_pp = ()=>{
        axios.post('/pAMY').then(res=>{
            window.location.reload(); 
        }); 
    }

    const uploadPicture = ()=>{
        const kb = 1000; 
        const fileSize = 200; 
        $("#image_error").html(""); 
        let file = document.getElementById('tf-upload-img').files[0];
        if(!file)return; 
        let types = ['image/png', 'image/jpg', 'image/jpeg']; 

        if( !types.includes(file.type)  ){
            Msg('error', `Approved image types are png, jpg and jpeg`);
            return; 
        }
        if(file.size / kb > fileSize){
            Msg('error', `Image size must be ${fileSize} kb or less`); 
            return; 
        }
        let data = new FormData($("#p_image_form")[0]); 
         
        axios.post('/aK0qQq62l', data, {
            'accept': 'application/json',
            'Accept-Language': 'en-US,en;q=0.8',
            'Content-Type': `multipart/form-data;`
        }).then(res=>{
            if(res.data.error){
                Msg('error', res.data.error); 
            }else if(res.data.success){
                Msg('success', res.data.success); 
            }
        }).catch(error=>{
            Msg('error', "Something went wrong"); 
        }); 
    }
    window.onload = ()=>{
        $("#copy_address").on('click', ()=>{
            var copyText = document.getElementById("address_input");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");
            $("#copy_address").html("Copied");
            setTimeout(() => {
                $("#copy_address").html("COPY LINK");
            }, 2000);
        }); 
    }
</script>
@include('includes.footer')