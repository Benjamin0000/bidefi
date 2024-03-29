import axios from 'axios'
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

export const truncateAddress = (address) => {
    if (!address) return "No Account";
    const match = address.match(
        /^(0x[a-zA-Z0-9]{3})[a-zA-Z0-9]+([a-zA-Z0-9]{4})$/
    );
    if (!match) return address;
    return `${match[1]}…${match[2]}`;
};

export function bytestohex() {
    let bytes = window.crypto.getRandomValues(new Uint8Array(32));
    var hexstring = '', h;
    for (var i = 0; i < bytes.length; i++) {
        h = bytes[i].toString(16);
        if (h.length == 1) { h = '0' + h; }
        hexstring += h;
    }
    let msg = "Hi there! \nWe just need you to sign this message to confirm that this wallet belongs to you.\n"
    return msg + hexstring + Date.now();
}

export function paramsToObject(entries) {
    const result = {}
    for (const [key, value] of entries) { // each 'entry' is a [key, value] tupple
        result[key] = value;
    }
    return result;
}

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: 'bidefi.io',
    forceTLS: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

window.Echo.channel(`main-channel`)
.listen('.adgedds', (e) => {
    let data = e.data;
    switch (data.type) {
        case 'started':
            window.location.reload();
            break;
        case 'ended':
            window.location.reload();
            break;
        case 'bid':
            let date = moment.utc(data.timer);
            if (window.show_id && window.show_id == data.id) {
                $("#the_author").html(data.bidder);

                $('#the_timer').countdown(date.toDate(), function (event) {
                    $(this).html("<span class='counter'>" + event.strftime('%S') + "</span>");
                });
                // $("#the_bid_price_eth").html(data.bid_price);
                $("#the_bid_price_usd").html('$' + data.bid_price);
                $(".price-box").css('background', 'orange'); 
                setTimeout(()=>{
                    $(".price-box").css('background', 'none');
                }, 200)
                if( window.user_id == data.user_id ){
                    $('#left').html(data.left);
                }
            } else {
                $("#timer" + data.id).countdown(date.toDate(), function (event) {
                    $(this).html("<span class='counter'>" + event.strftime('%S') + "</span>");
                });
                $("#c_bid" + data.id).html(data.bid_price);
                $("#author" + data.id).html(data.bidder2);
                $("#c_bid" + data.id).css('background', 'orange'); 
                setTimeout(()=>{
                    $("#c_bid" + data.id).css('background', 'none');
                }, 200)
            }
            break;
        case 'bidders':
            if (window.show_id && window.show_id == data.id) {
                $("#show_bidders").html(data.bidders);
            }
            break;
    }
})

window.Echo.join('chat.1') 
    .here((users) => {
        $("#on_u").html(users.length)
    })
    .joining((user) => {
        let old = Number($("#on_u").html())
        $("#on_u").html(old+1) 
    })
    .leaving((user) => {
        let old = Number($("#on_u").html())
        $("#on_u").html(old-1)
    }).listen('Chat', (e)=>{
        let user = e.username; 
        let msg = e.msg; 
        let online = e.online; 
        let mclass = ''; 
        if(online == user){
            mclass = 'p_text'; 
        }
        $('#chat_con').append("<div class='"+mclass+" chat_msg'><b>"+user+":</b> "+msg+"</div>")


        var objDiv = document.getElementById("chat_con");
        objDiv.scrollTop = 9999999;
        let chat = localStorage.getItem("chat");

        if(chat == "0"){
            let total = Number(localStorage.getItem("unread"));
            total+=1;
            localStorage.setItem("unread", total);
            $("#chat_sup").html(total); 
        }

    }) .error((error) => {
        console.log('an error occured')
        console.error(error);
    });