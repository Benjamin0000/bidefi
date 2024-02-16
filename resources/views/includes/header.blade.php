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
            font-size:20px;
            margin-right: 5px; 
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

.f-q-rr-con{
    background:#611d1d;
    width: 100%;
    padding:5px;
}
.q-rr-col-one{
    border:1px solid #222;
    font-size:15px;
    border-radius: 5px;
    font-weight: bold; 
    min-height: 60px; 
    padding:5px; 
    padding-top:8px; 
    line-height: 20px; 
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
                                        @guest
                                            <div class="sc-btn-top mg-r-12" id="site-header">
                                                <a href="#" id="connectbtn" class="sc-button header-slider style style-1 wallet fl-button pri-1"><span>Connect Wallet
                                                </span></a>
                                            </div>
                                        @endguest
                                        @auth
                                            <div class="sc-btn-top mg-r-12" >
                                                <a href="#" onclick="openNav()" class="pt_btn sc-button header-slider style style-1 fl-button pri-1">
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
                <div class="mode_switcher">
                    <h6>Dark mode <strong>Available</strong></h6>
                    <a href="#" class="light d-flex align-items-center">
                        <img src="/assets/images/icon/sun.png" alt="">
                    </a>
                    <a href="#" class="dark d-flex align-items-center is_active">
                        <img id="moon_dark" src="/assets/images/icon/moon-2.png" alt="">
                    </a>
                </div>
            </header>
            <!-- Header -->      