@php 
    $user = Auth::user(); 
    $points = App\Models\Point::orderBy('reward', 'asc')->get(); 
    $points2 = [];

    if($user){
        foreach ($points as $point) {
            if( !$user->task_completed($point->id) ){
                array_push($points2, $point); 
            }
        }
        foreach ($points as $point) {
            if( $user->task_completed($point->id) ){
                array_push($points2, $point); 
            }
        }
    }

    $msgs = array_reverse(App\Models\ChatMsg::latest()->take(30)->get()->all(), true); 
@endphp 
<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>BiDefi: Play and win huge crypto prices and digital collectibles.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=7">
    <link rel="shortcut icon" href="/assets/images/logo/logo.png">
    <link rel="apple-touch-icon-precomposed" href="/assets/images/logo/logo.png">
    <style>
        .select_tag{
            border: 1px solid rgba(138,138,160,0.3);
            outline: 0;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            font-size: 18px;
            line-height: 28px;
            border-radius: 4px;
            padding: 13px 15px;
            width: 100%;
            background: transparent;
            color: #8A8AA0;
        }
        .tags{width: 70px !important;}
        .counter{
            font-weight: 600;
            font-size: 15px;
        }
        .pt_btn{
            font-size:15px;
            margin-right: 5px; 
            padding: 7px 15px;
            font-weight: bold;
        }
        .nettt_btn{
            padding: 10px 15px;
            font-size:15px;
            font-weight: bold;
        }
        .pt_btn:hover{
            color:black !important; 
        }
        @media only screen and (max-width: 1200px) {
            .pt_btn{
                margin-right:10px;
            }
            #pt_no{
                display:none; 
            }
            .nettt_btn{
                
            }
            .net_name_h{
                display:none;
            }
        }

        #side_pt{
            height: 100%; /* 100% Full-height */
            width: 0; /* 0 width - change this with JavaScript */
            display:none; 
            position: fixed; /* Stay in place */
            z-index: 1; /* Stay on top */
            top: 0; /* Stay at the top */
            right: 0;
            background-color: #fff; /*111 Black*/
            overflow-x: hidden; /* Disable horizontal scroll */
            overflow-y: scroll; /* Enable vertical scroll */
            /* padding-top: 60px; Place content 60px from the top */
            transition: 0.1s; /* 0.5 second transition effect to slide in the sidenav */
            /* padding-left:10px;  */
            box-shadow: 0px 0px 2px 2px #eee;
        }

        .side_chat_light{
            background-color: #fff; 
        }

        .side_chat_dark{
            background-color: #111;
        }

        #side_chat{
            background: #fff; 
            height: 100vh; /* 100% Full-height */
            width: 0; /* 0 width - change this with JavaScript */
            display:none; 
            position: fixed; /* Stay in place */
            z-index: 2; /* Stay on top */
            top: 0; /* Stay at the top */
            right: 0;
            /* padding-top: 60px; Place content 60px from the top */
            transition: 0.1s; /* 0.5 second transition effect to slide in the sidenav */
            /* padding-left:10px;  */
            box-shadow: 0px 0px 2px 2px #eee;
        }
        
        #side_pt .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px
        }

        #side_content{
            border-top:2px solid black; 
            padding:20px; 
        }
        .q-rr{
            margin-bottom: 10px; 
            /* padding-left:10px;   */
        }
        .f-q-rr-con-color{
            background:#4B50E6;
        }
        .f-q-rr-con{
            width: 100%;
            padding:5px;
            min-height:80px;
            line-height: 30px;
        }
        .q-rr-col-one{
            border:1px solid #222;
            font-size:15px;
            border-radius: 5px;
            font-weight: bold; 
            min-height: 70px; 
            padding:5px; 
            line-height: 100px; 
        }
        .q-rr-col-two{
            padding:10px;
            border:1px solid #222;
            font-size:15px; 
            border-radius: 5px;
            color:#222;
        }
        .done_task{
            background:#eee; 
            color:black; 
        }
        .net_drop_show{
            padding:5px;
        }
        .net_drop_show img{
            margin-right:4px;
        }
        .net_drop_show a {
            color:white;
            font-weight: bold;
        }
        .net_drop_show:hover{
            background:#333; 
        }

        .nettt_btn{
            display:none; 
        }
        #chat_con{
            padding: 20px; 
            height: 65vh;
            overflow-x: hidden; /* Disable horizontal scroll */
            overflow-y: scroll; /* Enable vertical scroll */
        }

        .chat_msg{
            padding:10px; 
            background: #eee; 
            margin-bottom:10px; 
            font-size:15px;
        }
        #chat_input{
            background: #eee; 
            height: 25vh;
            padding:10px; 
        }
        #chat_input_con{
            border:1px solid #5d5c5c;
            overflow: hidden;
        }
        .red_border{
            border: 1px solid red !important; 
        }
        #chat_input_text{
            border:none; 
            font-size:15px;
            color:black; 
            height: 70px;
        }
        #emoji_btn{
            font-size:20px; 
        }
        #emoji_con{
            position: absolute;
            z-index: 3;
            background: #eee; 
            margin-top:-250px;
            display: none;  
            width: 342px;
            overflow: hidden;
            border:1px solid black; 
            margin-left:15px; 
        }
        #emoji_con2{
            min-height:200px; 
            height: 200px; 
            width: 350px;
            overflow-y: scroll;
            text-align: center;
            border-top:1px solid black; 
        }
        .emoji-item{
            display: inline-block;
            text-decoration: none; 
            font-size: 25px;
            margin:5px;  
        }
    </style>
</head>
<body class="body header-fixed is_dark">
    <div class="preload preload-container">
        <div class="preload-logo"></div>
    </div> 
    <div id="wrapper">
        <div id="page" class="clearfix">
            <header id="header_main" class="header_1 header_2 style2 js-header">
                <div class="themesflat-container">
                    <div class="row">
                        <div class="col-md-12">                              
                            <div id="site-header-inner"> 
                                <div class="wrap-box flex">
                                    <div id="site-logo" class="clearfix">
                                        <div id="site-logo-inner">
                                            <a href="/" rel="home" class="main-logo">
                                                <img id="logo_header" src="/assets/images/logo/logo.png" alt="nft-gaming" width="50" height="56"
                                                    data-retina="/assets/images/logo/logo_dark@2x.png" data-width="133"
                                                    data-height="56">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mobile-button"><span></span></div><!-- /.mobile-button -->
                                    <nav id="main-nav" class="main-nav">
                                        <ul id="menu-primary-menu" class="menu">
                                            <li class="menu-item">
                                                <a href="/">Home</a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="/faq">FAQ</a>
                                            </li>
                                            <li class="menu-item">
                                                <a href="/contact-us">Contact</a>
                                            </li>
                                        </ul>
                                    </nav><!-- /#main-nav -->   
                                    <div class="flat-search-btn flex">
                                        @auth 
                                            <div class="sc-btn-top mg-r-12">
                                                <a href="javascript:void(0)" onclick="openChat()" class="pt_btn sc-button header-slider style style-1 fl-button pri-1">
                                                    <i class="fas fa-comment"></i>
                                                </a>
                                            </div> 
                                        @endauth
                                        @guest
                                            <div class="sc-btn-top mg-r-12" id="site-header">
                                                <a href="javascript:void(0)" id="connectbtn" class="pt_btn sc-button header-slider style style-1 wallet fl-button pri-1"><span>Connect Wallet
                                                </span></a>
                                            </div>
                                        @endguest 
                                        @auth
                                            <div class="sc-btn-top mg-r-12">
                                                <a href="javascript:void(0)" onclick="openNav()" class="pt_btn sc-button header-slider style style-1 fl-button pri-1">
                                                    <i class="fas fa-flame"></i> <ti id="pt_no">{{$user->points}} B-Points</ti>
                                                </a>
                                            </div> 
                                            @include('includes.auth_dropdown') 
                                        @endauth
                                    </div>
                                    
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mode_switcher" style="margin-top:4px;">
                    <h6>Dark mode <strong>Available</strong></h6>
                    <a href="#" class="light d-flex align-items-center">
                        <img src="/assets/images/icon/sun.png" alt="">
                    </a>
                    {{-- <a href="#" class="dark d-flex align-items-center is_active">
                        <img id="moon_dark" src="/assets/images/icon/moon-2.png" alt="">
                    </a> --}}
                </div>
            </header>
            <!-- Header -->      