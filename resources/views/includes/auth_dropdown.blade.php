
@php 
    $emojis = [
        "💰", "💎", "📈", "📉", "💹", "💲", "🔥", "🚀", "💡", "🔍",
        "🛠️", "🛒", "😀", "😎", "😊", "😋", "😏", "😮", "🤔", "😌",
        "🤑", "👍", "👎", "👋", "👋🏼", "🖐️", "🙌", "👐", "🤞", "🤝",
        "🔒", "🔓", "🔐", "📊", "📄", "📃", "💼", "💻", "📱", "📡",
        "🔬", "🧪", "🔮", "🏆", "🥇", "🥈", "🥉", "🎯", "🏹", "🎲",
        "💖", "💓", "💘", "💝", "💗", "💞", "💕", "💟", "💫", "🌀",
        "✨", "🌟", "₿"
    ]; 
@endphp 
<div class="" id="header_admin" style="padding-top:6px;">
    <div class="header_avatar">
        <div class="popup-notification">
            <div class="notification">

                <a href="javascript:void(0)" id="network_shown" class="nettt_btn pt_btn sc-button header-slider style style-1 fl-button pri-1">
                    <img src="/icon/glob.png" class="net_logo_h" alt="" width="20">
                    <ti class="net_name_h">Networks</ti> <li class="fas fa-caret-down"></li>
                </a>

                @foreach(all_networks() as $net_no)
                    <a href="javascript:void(0)" id="network_shown{{$net_no}}" class="nettt_btn pt_btn sc-button header-slider style style-1 fl-button pri-1">
                        <img src="{{get_logo($net_no)}}" class="net_logo_h" alt="" width="20">
                        <ti class="net_name_h">{{get_network_name($net_no)}}</ti> <li class="fas fa-caret-down"></li>
                    </a>
                @endforeach 
            </div> 
            <div class="avatar_popup2 mt-20">
                <div class="show mg-bt-18 text-center" style="margin-top:-10px;">
                    <h5 style="color:white;font-size:15px;">Supported Networks</h5>
                </div>
                
                @foreach(all_networks() as $net_no)
                    <div class="net_drop_show" tt="{{$net_no}}">
                        <a href="javascript:void(0)">
                            <img src="{{get_logo($net_no)}}" alt="" width="30">
                            {{get_network_name($net_no)}}
                        </a>
                    </div>
                @endforeach 
            </div>
        </div> 
        <div class="popup-user">
            @if($user->avatar)   
                <img class="avatar" src="{{Storage::url($user->avatar)}}" alt="avatar"/>
            @else 
                <img class="avatar" src="/assets/images/avatar/avt-5.jpg" alt="avatar"/>
            @endif 
            <div class="avatar_popup mt-20">
                @if($user->fname)
                <h4>{{$user->fname .' '. $user->lname}}</h4>
                @else 
                    <a href="/profile" style="color:aqua">Set name</a>
                @endif 
                <div class="d-flex align-items-center mt-20 mg-bt-12">
                    <div class="info">
                        <p>Network</p>
                        <p class="style" style="color:white;"><span class="net_show"></span></p>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-20 mg-bt-12">
                    <div class="info">
                        <p>Balance</p>
                        <p class="style" style="color:white;"><span id="eth_bal"></span> <span id="eth_symbol">ETH</span></p>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-20 mg-bt-12">
                    <div class="info">
                        <p>Bid Credit</p>
                        <p class="style" style="color:white;"><span class="bid_credit_info">0</span> &nbsp; <a href="/buy-credit" class="btn btn-primary">Buy Credit</a></p>
                    </div>
                </div>                
                <p>Wallet</p>
                <div class="d-flex align-items-center justify-content-between mg-t-5 mg-bt-17">
                    <p id="address_show"></p>
                </div>
                <div class="divider"></div>
                <div class="hr"></div>
                <div class="links mt-20">
                    @if($user->admin)
                        <a href="/admin/dashboard">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.774902 18.333C0.774902 18.7932 1.14762 19.1664 1.60824 19.1664C2.06885 19.1664 2.44157 18.7932 2.44157 18.333C2.44157 15.3923 4.13448 12.7889 6.77329 11.5578C7.68653 12.1513 8.77296 12.4997 9.94076 12.4997C11.113 12.4997 12.2036 12.1489 13.119 11.5513C13.9067 11.9232 14.6368 12.4235 15.2443 13.0307C16.6611 14.4479 17.4416 16.3311 17.4416 18.333C17.4416 18.7932 17.8143 19.1664 18.2749 19.1664C18.7355 19.1664 19.1083 18.7932 19.1083 18.333C19.1083 15.8859 18.1545 13.5845 16.4227 11.8523C15.8432 11.2725 15.1698 10.7754 14.4472 10.3655C15.2757 9.3581 15.7741 8.06944 15.7741 6.66635C15.7741 3.44979 13.1569 0.833008 9.94076 0.833008C6.72461 0.833008 4.10742 3.44979 4.10742 6.66635C4.10742 8.06604 4.60379 9.35154 5.42863 10.3579C2.56796 11.9685 0.774902 14.9779 0.774902 18.333V18.333ZM9.94076 2.49968C12.2381 2.49968 14.1074 4.36898 14.1074 6.66635C14.1074 8.96371 12.2381 10.833 9.94076 10.833C7.6434 10.833 5.77409 8.96371 5.77409 6.66635C5.77409 4.36898 7.6434 2.49968 9.94076 2.49968V2.49968Z" fill="white"/>
                            </svg>
                            <span>Admin</span>
                        </a>
                    @endif 
                    <a href="/profile">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.774902 18.333C0.774902 18.7932 1.14762 19.1664 1.60824 19.1664C2.06885 19.1664 2.44157 18.7932 2.44157 18.333C2.44157 15.3923 4.13448 12.7889 6.77329 11.5578C7.68653 12.1513 8.77296 12.4997 9.94076 12.4997C11.113 12.4997 12.2036 12.1489 13.119 11.5513C13.9067 11.9232 14.6368 12.4235 15.2443 13.0307C16.6611 14.4479 17.4416 16.3311 17.4416 18.333C17.4416 18.7932 17.8143 19.1664 18.2749 19.1664C18.7355 19.1664 19.1083 18.7932 19.1083 18.333C19.1083 15.8859 18.1545 13.5845 16.4227 11.8523C15.8432 11.2725 15.1698 10.7754 14.4472 10.3655C15.2757 9.3581 15.7741 8.06944 15.7741 6.66635C15.7741 3.44979 13.1569 0.833008 9.94076 0.833008C6.72461 0.833008 4.10742 3.44979 4.10742 6.66635C4.10742 8.06604 4.60379 9.35154 5.42863 10.3579C2.56796 11.9685 0.774902 14.9779 0.774902 18.333V18.333ZM9.94076 2.49968C12.2381 2.49968 14.1074 4.36898 14.1074 6.66635C14.1074 8.96371 12.2381 10.833 9.94076 10.833C7.6434 10.833 5.77409 8.96371 5.77409 6.66635C5.77409 4.36898 7.6434 2.49968 9.94076 2.49968V2.49968Z" fill="white"/>
                        </svg>
                        <span>My Profile</span>
                    </a>
                    <a href="/activity">
                        <svg fill="#fff" width="20" height="20" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
	 viewBox="0 0 483.1 483.1" xml:space="preserve">
<g>
	<path d="M434.55,418.7l-27.8-313.3c-0.5-6.2-5.7-10.9-12-10.9h-58.6c-0.1-52.1-42.5-94.5-94.6-94.5s-94.5,42.4-94.6,94.5h-58.6
		c-6.2,0-11.4,4.7-12,10.9l-27.8,313.3c0,0.4,0,0.7,0,1.1c0,34.9,32.1,63.3,71.5,63.3h243c39.4,0,71.5-28.4,71.5-63.3
		C434.55,419.4,434.55,419.1,434.55,418.7z M241.55,24c38.9,0,70.5,31.6,70.6,70.5h-141.2C171.05,55.6,202.65,24,241.55,24z
		 M363.05,459h-243c-26,0-47.2-17.3-47.5-38.8l26.8-301.7h47.6v42.1c0,6.6,5.4,12,12,12s12-5.4,12-12v-42.1h141.2v42.1
		c0,6.6,5.4,12,12,12s12-5.4,12-12v-42.1h47.6l26.8,301.8C410.25,441.7,389.05,459,363.05,459z"/>
</g>
</svg>
                        <span>Activity</span>
                    </a>
                    {{-- <a class="mt-10" href="profile.html">
                        <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.1154 0.730469H2.88461C1.29402 0.730469 0 2.02449 0 3.61508V14.3843C0 15.9749 1.29402 17.2689 2.88461 17.2689H17.1154C18.706 17.2689 20 15.9749 20 14.3843V3.61508C20 2.02449 18.706 0.730469 17.1154 0.730469ZM18.7529 10.6035H14.6154C13.6611 10.6035 13 9.95407 13 8.99969C13 8.04532 13.661 7.34544 14.6154 7.34544H18.7529V10.6035ZM18.7529 6.11508H14.6154C13.0248 6.11508 11.7308 7.40911 11.7308 8.99969C11.7308 10.5903 13.0248 11.8843 14.6154 11.8843H18.7529V14.3843C18.7529 15.3386 18.0698 15.9996 17.1154 15.9996H2.88461C1.93027 15.9996 1.29231 15.3387 1.29231 14.3843V3.61508C1.29231 2.66074 1.93023 1.99963 2.88461 1.99963H17.1266C18.0809 1.99963 18.7529 2.6607 18.7529 3.61508V6.11508Z" fill="white"/>
                        </svg>
                        <span>Wallet</span>
                    </a> --}}
                    <a class="mt-10" href="#" id="logout">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.9668 18.3057H2.49168C2.0332 18.3057 1.66113 17.9335 1.66113 17.4751V2.52492C1.66113 2.06644 2.03324 1.69437 2.49168 1.69437H9.9668C10.4261 1.69437 10.7973 1.32312 10.7973 0.863828C10.7973 0.404531 10.4261 0.0332031 9.9668 0.0332031H2.49168C1.11793 0.0332031 0 1.15117 0 2.52492V17.4751C0 18.8488 1.11793 19.9668 2.49168 19.9668H9.9668C10.4261 19.9668 10.7973 19.5955 10.7973 19.1362C10.7973 18.6769 10.4261 18.3057 9.9668 18.3057Z" fill="white"/>
                            <path d="M19.7525 9.40904L14.7027 4.42564C14.3771 4.10337 13.8505 4.10755 13.5282 4.43396C13.206 4.76036 13.2093 5.28611 13.5366 5.60837L17.1454 9.16982H7.47508C7.01578 9.16982 6.64453 9.54107 6.64453 10.0004C6.64453 10.4597 7.01578 10.8309 7.47508 10.8309H17.1454L13.5366 14.3924C13.2093 14.7147 13.2068 15.2404 13.5282 15.5668C13.691 15.7313 13.9053 15.8143 14.1196 15.8143C14.3306 15.8143 14.5415 15.7346 14.7027 15.5751L19.7525 10.5917C19.9103 10.4356 20 10.2229 20 10.0003C20 9.77783 19.9111 9.56603 19.7525 9.40904Z" fill="white"/>
                        </svg>
                        <span>Log out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="side_pt">
    <div style="background: #eee; height:50px;">
        <a href="javascript:void(0)" style="color:#111;" class="closebtn" onclick="closeNav()">&times;</a>
        <br>
        <h4 style="margin-bottom: 20px;color:#111;margin-left:10px;">Quests</h4>
    </div> 

    <div id="side_content"> 
        <br>
        @foreach($points2 as $point)
            <div class="row q-rr @if($user->task_completed($point->id)) done_task @endif">
                <div class="col-3 q-rr-col-one text-center" style="color:white;">
                    <div class="f-q-rr-con @if($user->task_completed($point->id)) done_task @else f-q-rr-con-color @endif "> 
                        <h5>{{$point->reward}}</h5>
                        <div>B-Points</div>
                    </div> 
                </div>
                <div class="col-9 q-rr-col-two">
                    <b>{{$point->title}}</b>
                    <br>
                    <br>
                    <div>
                        <span class="">
                            <img width="30" src="{{get_logo($point->network)}}" alt="">
                        </span>
                        <span class="badge" style="background: #ddd;color:black;">{{$user->get_completed_task($point->id)}} / {{$point->bid}}</span>
                        <span style="font-size:13px;">{{$point->created_at->isoFormat('ll')}} - {{$point->expiry_date->isoFormat('ll')}}</span>
                    </div>
                    {{-- @if($user->task_completed($point->id))
                        <div>
                            <span class="badge bg-success">completed</span>
                        </div> 
                    @endif  --}}
                </div>
            </div>
        @endforeach
    </div> 
</div>

<div id="side_chat">
    <div style="padding: 10px;">
        <div class="row">
            <div class="col" style="text-align: left; padding-top:10px;">
                <h4>BiDeFi Townsquare</h4>
            </div>
            <div class="col" style="text-align: right">
                <a href="javascript:void(0)" style="color:#111;font-size:40px;" class="closebtn" onclick="closeChat()">&times;</a>
            </div>
        </div>
    </div>
    <hr>
    <div id="chat_con">
        @foreach($msgs as $msg)
            <div class="chat_msg">
                <b>{{get_user_fname($msg['user_id'])}}:</b> {{$msg['msg']}}
            </div>
        @endforeach
    </div>

    <form id="chat_input">
        <div id="emoji_con">
            <div class="row" style="padding: 5px; ">
                <div class="col-6">
                    <br>
                    <h5>Emojis</h5>
                </div>
                <div class="col-6 text-right">
                    <a href="javascript:void(0)" style="color:#111;font-size:40px;" class="closebtn" onclick="closeEmoji()">&times;</a>
                </div>
            </div>
            <div id="emoji_con2">
                @foreach($emojis as $emoji)
                    <a href="javascript:void(0)" class="emoji-item">{{$emoji}}</a>
                @endforeach
            </div>
        </div> 
        <div id="chat_input_con">
            <div class="row">
                <div class="col-10">
                    {{-- <input type="text"  id="chat_input_text" placeholder="Type your message"> --}}
                    <textarea name="msg" rows="1" id="chat_input_text" placeholder="Type Message"></textarea>
                </div>
                <div class="col-2" style="margin-top:18px">
                    
                    <a href="javascript:void(0)" onclick="openEmoji()" id="emoji_btn">😍</a>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-6 text-left">
                <h5 style="font-size: 13px;margin-top:5px;"><span class="text-success fas fa-user"></span> Online: <span id="on_u">0</span></h5>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-6 text-right" style="padding-top:10px;">
                        <b id="msg_count" style="font-size: 15px;margin-right:10px">160</b>
                        {{-- <a href="javascript:void(0)" style="font-size: 15px;"><span class="fas fa-book"></span></a> --}}
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-primary" style="color:white !important" id="msg_send_btn">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


        
<div class="modal fade popup" id="chat_rules" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body space-y-20 pd-40">
                <h3><i class="fas fa-book"></i>Chat Rules</h3>

                
            </div>
        </div>
    </div>
</div> 



<script>
function openNav() {
  document.getElementById("side_pt").style.display = "block";
  document.getElementById("side_pt").style.width = "400px";
}
/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("side_pt").style.width = "0";
  document.getElementById("side_pt").style.display = "none";
}
function openChat() {
  document.getElementById("side_chat").style.display = "block";
  document.getElementById("side_chat").style.width = "400px";
  document.getElementById("wrapper").style.marginRight = "400px";
  var objDiv = document.getElementById("chat_con");
  objDiv.scrollTop = 9999999;
}
/* Set the width of the side navigation to 0 */
function closeChat() {
  document.getElementById("side_chat").style.width = "0";
  document.getElementById("side_chat").style.display = "none";
  document.getElementById("wrapper").style.marginRight= "0";
}
function openEmoji(){
    document.getElementById("emoji_con").style.display = "block";  
}
function closeEmoji(){
    document.getElementById("emoji_con").style.display = "none";  
}

function typeInTextarea(el, newText) {
    var start = el.prop("selectionStart")
    var end = el.prop("selectionEnd")
    var text = el.val()
    var before = text.substring(0, start)
    var after  = text.substring(end, text.length)
    el.val(before + newText + after)
    el[0].selectionStart = el[0].selectionEnd = start + newText.length
    el.focus()
}

</script>