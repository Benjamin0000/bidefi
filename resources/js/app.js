import './bootstrap'
import { web3Modal } from './connectors'
import axios from 'axios';
import { truncateAddress, bytestohex } from './bootstrap';
import Abi from "./Bidding_ABI.json";
import { ethers } from "ethers";
//import Intract from '@intract/attribution'
import IntractAttribution from "@intract/attribution"
import {
  getAccount,
  signMessage,
  disconnect,
  watchNetwork,
  watchAccount,
  prepareWriteContract,
  writeContract,
  fetchBalance,
  waitForTransaction,
  readContract,
  switchNetwork
} from '@wagmi/core'

var supported_networks = [
  1,
  324,
  59144,
  8453,
  56,
  42161,
  10,
  97
]; 

$(document).on('click', '#connectbtn', () => {
  web3Modal.open() 
});


function get_contract() {
  let id = window.network_id;
  let main = "0x74bc2fab98B609E4765271b10C1673A914F753B8"; 
  let arbitrum = "0x88842fa0Af9266cfAe10B7470A9A80384195746c"; 
  let optimism = "0x88842fa0Af9266cfAe10B7470A9A80384195746c"; 
  let zk = "0x4d73cDFF03C4Cf245Bc203374B83f4c43a292bfC";
  let linea = "0x88842fa0Af9266cfAe10B7470A9A80384195746c";
  let base = "0x88842fa0Af9266cfAe10B7470A9A80384195746c"; 
  let bsc = '0x1EE4CC90e11a42635C1e7829Aa08d5e3FC5eDe8C'; 
  let bsc2 = '0xb0634bb4857bab45ac4fc440fee6e715824a96ef';
  
  if(id == 1){
    return main; 
  }else if (id == 324) {
    return zk;
  } else if (id == 59144) {
    return linea;
  } else if(id == 8453){
    return base; 
  } else if(id == 56){
    return bsc; 
  } else if(id == 42161){
    return arbitrum;
  }else if(id == 10){
    return optimism; 
  }else if(id == 97){
    return bsc2;
  }
}



function logout() {
  disconnect().then(() => {
    axios.get('/RVgtFB').then(res => {
      if(res.data.done) {
          window.location.reload();
      }
    });
  });
}

function showUserLogin(account){
  $("#address_show").html(truncateAddress(account.address));
  $("#network_shown").show();
  fetchBalance({
    address: account.address,
    formatUnits: 'ether',
  }).then(balance => {
      $("#eth_bal").html(Number(balance.formatted).toFixed(5))
      $(".nettt_btn").hide();
      if(!network_supported()){
        $("#network_shown").show(); 
      }
      $("#network_shown"+window.network_id).show(); 
      if(window.network_id == 56){
        $("#eth_symbol").html('BNB');  
      }else{
        $("#eth_symbol").html('ETH');
      }

  }); 
}

function network_supported(net=null){
  let network = window.network_id;
  if(net != null){
    if(net != network){
      return false;
    }
      return true; 
  }

  if(supported_networks.includes(network)){
    return true; 
  }
  return false; 
}

//get item data
//get user point balance 
function setData(account) {
  if(!network_supported())return; 

  let contract = get_contract(); 
  console.log("the contract")
  console.log(contract); 
  readContract({
      address: contract,
      abi: Abi,
      functionName: 'ItemData'
  }).then(data => {
      console.log(data)
      $("#bid_amt").val(0); 
      $("#bid_eth_price").val(0); 
      $("#msg").html('');
      $("#ad_bid_price").val(ethers.formatEther(data[3])); 
      $("#ad_bid_com").val(data[2]);
      $("#ad_bid_fee").val(ethers.formatEther(data[4])); 
      readContract({
          address: contract,
          abi: Abi,
          functionName: 'points',
          args: [account.address],
      }).then(bal => {
          let balance = Number(bal); 
          $(".bid_credit_info").html(balance); 
          if(balance <= 0){
            $("#buy_credit_link").html("<a href='/buy-credit' style='width:150px; height:30px;line-height:10px;' class='btn btn-primary'>Buy Bid Credit</a>"); 
          }
          window.info = {
            bid_price: ethers.formatEther(data[3]),
            bid_fee: ethers.formatEther(data[4]),
            points: balance
          }
      });
  });
}
$("#logout").click((e) => {
  logout()
});

watchNetwork((network) => {
  let account = getAccount();
  if(account && account.address){
    axios.get('/ogNkV').then(res => { //check auth 
      if(!res.data.auth) {
          const msg = bytestohex();
          signMessage({ message: msg }).then(sig => {
            let data = {
              'address': account.address,
              'sig': sig,
              'message': msg
            }
            axios.post('/VgtFB', data).then(res2 => {
              if(res2.data.auth){
                  window.location.reload(); 
              }
            });
          });
      }else{
        if(account.address != res.data.address){
            logout();
        }else{
            $("#network_input").val(network.chain.id); 
            window.network_id = network.chain.id;
            $(".net_show").html(network.chain.name);
            showUserLogin(account);
            setData(account);
        }
      }
    });
  }
});

watchAccount(user=>{
  if(window.auth_address && window.auth_address != user.address){
    logout();
  }
}); 


// buy bid credit
$(document).on('keyup', '#bid_amt', (e) => {
  if(network_supported()){
    $("#min_bid_info").hide();
    let value = e.target.value;
    let total = value * window.info.bid_price;
    $('#bid_eth_price').val(total);

    if (value < window.min_bid)
      $("#min_bid_info").show();
  }
});



$(document).on('submit', '#buy_credit_form', (event) => {
  event.preventDefault(); 
  if(!network_supported()){
    $("#select_network_modal").modal("show");
    return; 
  }
  let account = getAccount();
  let amt = parseInt($("#bid_amt").val());
  let btn = $(event.target).find('button');
  btn.html("Please wait...")
  btn.attr('disabled', true)
  $("#msg").html('')
  let cost = Number(amt * window.info.bid_price).toFixed(8);
  if (amt < window.min_bid) return;
  let ref = $("#ref_val").val(); 
  if(!ref){
      ref = '0x0000000000000000000000000000000000000000'; 
  }

  fetchBalance({
    address: account.address,
    formatUnits: 'ether',
  }).then(balance => {
      let bal = Number(balance.formatted).toFixed(5); 
      if(cost > bal){
        $("#msg").html("<div class='alert alert-danger'><h5>You don't have sufficient funds!</h5></div>");
        btn.html("Buy Credit");
        btn.attr('disabled', false)
        return; 
      }
      prepareWriteContract({
        address: get_contract(),
        abi: Abi,
        functionName: 'buyPoint',
        args: [amt, ref],
        value: ethers.parseEther(cost.toString())
      }).then(config=>{
        writeContract(config).then(res => {
          waitForTransaction({ confirmations: 2, hash: res.hash }).then(res=>{
            $("#bid_info").show();
            setTimeout(()=>{
              axios.post('/NAQvfoLAo', {amt:amt}).then(res=>{
                window.location.reload(); 
              }); 
            }, 2000); 
          }).catch(error=>{
            $("#msg").html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
            btn.html("Buy Credit");
            btn.attr('disabled', false)
          });
        }).catch(error=>{
          $("#msg").html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
          btn.html("Buy Credit");
          btn.attr('disabled', false)
        })
      }).catch(error => {
        $("#msg").html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
        btn.html("Buy Credit");
        btn.attr('disabled', false)
      })
    });
});

function confirm_credit_trx(msg, btn, token, fee)
{
  axios.post('/ho8OJ92Bs9RyEW67', {ffffxfr:token, fee:fee}).then(res=>{
    if(res.data.done){
        msg.html("<div class='alert alert-success'><h5> Bid placed successfully </h5></div>");
        setTimeout(() => {
          window.location.reload()
        }, 2000);
    }else if(res.data.trial){
        return confirm_credit_trx(msg, btn, token, fee); 
    }else if(res.data.stop){
        msg.html("<div class='alert alert-info'>Network error operation will still be completed in a short while</div>");
        return; 
    }
    btn.attr("disabled", false); 
  }).catch(error=>{
      confirm_credit_trx(msg, btn, token, fee)
  }); 
}

//place a bid
$(document).on('submit', '#place_bid_form', (event) => {
  event.preventDefault();
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);
  let network = params.get('network');
  if(!network_supported(network)){
    switchNetwork({chainId: network});
    return; 
  }

  let msg = $(event.target).find('#bid_msg');
  let btn = $(event.target).find('button');
  let id = params.get('id');
  let amt = Number(params.get('amt'));
  let min = Number(params.get('min')); 
  let free_bid = Number(params.get('free_bid')); 
  let used = Number(params.get('used'));  
  let cost = window.info.bid_fee; 


  msg.html('');

  if(amt < min){
    msg.html("<div class='alert alert-danger'><h5>Minimum bid is "+min+"</h5></div>");
    return ;
  }

  if(!free_bid){
    if(amt > window.info.points){
      msg.html("<div class='alert alert-danger'><h5>Insuficient bid credits</h5></div>");
      return ;
    }
  }else{
    if(used >= free_bid){
      msg.html("<div class='alert alert-danger'><h5>You have exhausted your free credits</h5></div>");
      return ;
    }else if(amt > free_bid){
      msg.html("<div class='alert alert-danger'><h5>Amount exceeds the aloted free credit</h5></div>");
      return ;
    }
  }


  btn.html("Please wait...")
  btn.attr('disabled', true)
 

  axios.post('/PwbcHF5tYjZpghfV7O', {id:id}).then(res=>{
    let secrete = res.data.token; 
    prepareWriteContract({
      address: get_contract(),
      abi: Abi,
      functionName: 'placeBid',
      args: [id, amt, secrete],
      value: ethers.parseEther(cost.toString())
    }).then(config=>{
      writeContract(config).then(res => {
        waitForTransaction({ confirmations: 2, hash: res.hash }).then(res=>{
          confirm_credit_trx(msg, btn, secrete, cost)
        }).catch(error=>{
          msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
          btn.html("Place a bid");
          btn.attr('disabled', false)
        });
      }).catch(error=>{
        msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
        btn.html("Place a bid");
        btn.attr('disabled', false)
      })
    }).catch(error => {
      msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
      btn.html("Place a bid");
      btn.attr('disabled', false)
    })
  }).catch(error => {
    msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
    btn.html("Place a bid");
    btn.attr('disabled', false);
  });

});

//claim price

$(document).on('click', '#claim_price', (event) => {
    let btn = $(event.target);
    let id = btn.attr('idd');
    let network = btn.attr('net'); 
    if(!network_supported(network)){
      switchNetwork({chainId: network});
      return; 
    }
    let msg = $("#c_msg");
    btn.html('Please wait...');
    msg.html(''); 
    btn.attr('disabled', true);

    prepareWriteContract({
      address: get_contract(),
      abi: Abi,
      functionName: 'claimPrice',
      args: [id]
    }).then(config=>{
      writeContract(config).then(res => {
        waitForTransaction({ confirmations: 2, hash: res.hash }).then(res => {
          axios.post('/FapHqrwPfkewSHq', { id: id, hash: res.hash }).then(res => {
            alert("Price claimed")
            window.location.reload()
          }); 
        }).catch(error => {
          msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
          btn.html("Claim");
          btn.attr('disabled', false);
        }); 
      }).catch(error => {
        msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
        btn.html("Claim");
        btn.attr('disabled', false);
      });
    }).catch(error => {
      msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
      btn.html("Claim");
      btn.attr('disabled', false);
    });
}); 


window.likeItem = function (id) {
  axios.post('/IN31Wd5njhG', { id: id })
}

window.count_views = function (id) {
  axios.post('/fAbAsLr7Zs', { id: id })
}

$(document).on('click', '.net_cc', (e)=>{
  let network = $(e.currentTarget).attr('net'); 
  switchNetwork({chainId: network});
  $("#select_network_modal").modal("hide");
}); 

$(document).on('click', '#close_net', (e)=>{
  $("#select_network_modal").modal("hide");
}); 



$(document).on('submit', '#create_form', (event) => {
  event.preventDefault();
  let btn = $("#create_btn");
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);
  let _id = params.get('_id') ? params.get('_id') : '0'; //nft id
  let id = params.get('id');
  let token_amount = params.get('prize') ? params.get('prize') : '0';
  let _type = params.get('type');
  let _address = params.get('contract_address');
  _address = _address ? _address : '0x0000000000000000000000000000000000000000'; 
  let decimal = params.get('decimal');
  let startTime = params.get('start_time');
  let freeCredit = params.get('free');
  let reqPoints = params.get('start_points');
  let share = params.get('share'); 

  $("#msg").html(''); 
  if(token_amount > 0){
    if(_type == 3){
        if(!decimal){
          alert('please enter a valid decimal for token'); 
          return ;
        }
        token_amount = token_amount * (10**decimal); 
    }else if(_type == 4){
      token_amount = ethers.parseEther(token_amount.toString())
    }
  }
  btn.html("Sending...")
  btn.attr('disabled', true)

  prepareWriteContract({
    address: get_contract(), 
    abi: Abi,
    functionName: 'listItem',
    args: [id, _id, token_amount, _type, _address, startTime, freeCredit, reqPoints, share]
  }).then(config=>{
      writeContract(config).then(res => {
        waitForTransaction({ confirmations: 2, hash: res.hash }).then(res => {
          event.currentTarget.submit();
        }).catch(error => {
            $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
            btn.html("Create");
            btn.attr('disabled', false)
        });
      }).catch(error => {
          $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
          btn.html("Create");
          btn.attr('disabled', false)
      });
  }).catch(error => {
    $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
    btn.html("Create");
    btn.attr('disabled', false)
  });
});  

$(document).on('submit', '#bid_price_form', (event) => {
  event.preventDefault();
  let btn = $(event.target).find('button');
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);
  let price = Number(params.get('price'));
  let pct = Number(params.get('commission')); 
  let fee = Number(params.get('bid_fee')); 

  if (price <= 0) {
    alert('please enter a valid price');
    return ;
  }

  if (pct <= 0) {
    alert('please enter a valid commission');
    return ;
  }

  if (fee <= 0) {
    alert('please enter a valid bid fee');
    return ;
  }

  btn.html("Sending...");
  $("#msg").html(''); 
  btn.attr('disabled', true);
  
  prepareWriteContract({
    address: get_contract(),
    abi: Abi,
    functionName: 'changePointPrice',
    args: [ethers.parseEther(price.toString()), pct, ethers.parseEther(fee.toString())]
  }).then(config=>{
    writeContract(config).then(res => { 
      waitForTransaction({confirmations: 2, hash: res.hash }).then(res => {
        $("#msg").html("<div class='alert alert-success'>Success!</div>");
        btn.html("Update");
        btn.attr('disabled', false);
      }).catch(error => {
        $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
        btn.html("Update");
        btn.attr('disabled', false);
      });
    }).catch(error => { 
      $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
      btn.html("Update");
      btn.attr('disabled', false);
    });
  }).catch(error=>{
    $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
    btn.html("Update");
    btn.attr('disabled', false);
  }); 
});

$(document).on('submit', '#create_admin_form', (event) => {
  event.preventDefault();
  let btn = $(event.target).find('button');
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);
  let address = params.get('address');
  btn.html("Sending...");
  btn.attr('disabled', true);
  $("#msg").html(''); 

  axios.post('/admin/admins', { 'address': address, 'check': true }).then(res => {
    if (res.data.error) {
      $.notify(res.data.error, "error");
      btn.html("Create");
      btn.attr('disabled', false);
      return;
    }
    prepareWriteContract({
      address: get_contract(),
      abi: Abi,
      functionName: 'approveAdmin',
      args: [address, true]
    }).then(config=>{
        writeContract(config).then(res => {
          waitForTransaction({ confirmations: 2, hash: res.hash }).then(res => {
            event.currentTarget.submit();
          }).catch(error => {
            $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
            btn.html("Create");
            btn.attr('disabled', false);
          });
        }).catch(error => {
          $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
          btn.html("Create");
          btn.attr('disabled', false);
        });
    }).catch(error => {
      $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
      btn.html("Create");
      btn.attr('disabled', false);
    });   
  }).catch(error => {
    $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
    btn.html("Create");
    btn.attr('disabled', false);
  });
});

$(document).on('submit', '.remove_admin', (event) => {
  event.preventDefault();
  let btn = $(event.target).find('button');
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);

  let address = params.get('address');
  btn.html("Sending...");
  btn.attr('disabled', true);

  prepareWriteContract({
    address: get_contract(),
    abi: Abi,
    functionName: 'approveAdmin',
    args: [address, false]
  }).then(config=>{
    writeContract(config).then(res => {
      waitForTransaction({ confirmations: 2, hash: res.hash }).then(res => {
        event.currentTarget.submit();
      }).catch(error => {
        $.notify(error.message, "error");
        btn.html("Delete");
        btn.attr('disabled', false);
      });
    }).catch(error => {
      $.notify(error.message, "error");
      btn.html("Delete");
      btn.attr('disabled', false);
    });
  }).catch(error => {
    $.notify(error.message, "error");
    btn.html("Delete");
    btn.attr('disabled', false); 
  }); 
});

$(document).on('submit', '#ad_withdrawal_form', (event) => {
  event.preventDefault();
  let btn = $(event.target).find('button');
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);
  let amt = params.get('amt');
  btn.html("Sending...");
  btn.attr('disabled', true);
  $("#msg").html(""); 

  prepareWriteContract({
    address: get_contract(),
    abi: Abi,
    functionName: 'withdraw',
    args: [ethers.parseEther(amt.toString())]
  }).then(config=>{
      writeContract(config).then(res => {
        waitForTransaction({ confirmations: 2, hash: res.hash }).then(res => {
          event.currentTarget.submit();
        }).catch(error => {
          $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
          btn.html("Continue");
          btn.attr('disabled', false);
        });
      }).catch(error => {
        $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
        btn.html("Continue");
        btn.attr('disabled', false);
      });
  }).catch(error => {
    $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
    btn.html("Continue");
    btn.attr('disabled', false);
  });
});

window.pg = 2; //for the loadmore
$(document).on('click', "#llmore", (e) => {
  let id = $(e.target).attr('data-id');

  $(e.target).html("Loading...");
  $(e.target).attr("disabled", true);

  axios.get('/kSHhWd/' + id + '?page=' + window.pg).then(res => {
    $(e.target).html("Load more");
    $(e.target).attr("disabled", false);
    let data = res.data;
    if (data.count == 8) {
      window.pg += 1;
    } else {
      $(e.target).hide();
    }
    if (data.count > 0) {
      $("#item_con").append(data.view);
    }
  });
})

$(document).on('click', ".llmore", (e) => {
  let id = $(e.target).attr('data-id');
  let page = Number($(e.target).attr('page'));

  $(e.target).html("Loading...");
  $(e.target).attr("disabled", true);

  axios.get('/kSHhWd/' + id + '?page=' + page).then(res => {
    $(e.target).html("Load more");
    $(e.target).attr("disabled", false);
    let data = res.data;
    if (data.count == 8) {
      $(e.target).attr('page', page+1); 
    } else {
      $(e.target).hide();
    }
    if (data.count > 0) {
      $("#home_item"+id).append(data.view);
    }
  });
})

$(document).ready(function () {
  // Intract("6549d8e333cc8772783b45b1") 
  IntractAttribution("6549d8e333cc8772783b45b1", {
    configAllowCookie: true,  
  });
  $(document).on('submit', ".update_contract", (event)=>{
      event.preventDefault();
      let data = $(event.target).serialize();
      let params = new URLSearchParams(data);
      let _id = params.get('_id') ? params.get('_id') : '0'; //nft id
      let id = params.get('id');
      let token_amount = params.get('prize') ? params.get('prize') : '0';
      let _type = params.get('type');
      let _address = params.get('contract_address');
      _address = _address ? _address : '0x0000000000000000000000000000000000000000'; 
      let decimal = params.get('decimal');
      let startTime = params.get('start_time');
      let freeCredit = params.get('free');
      let reqPoints = params.get('start_points');
      let share = params.get('share');
      let btn = $("#jj"+id);
      let msg = $("#msg"+id);

      msg.html(''); 
      if(token_amount > 0){
        if(_type == 3){
            if(!decimal){
              alert('please enter a valid decimal for token'); 
              return ;
            }
            token_amount = token_amount * (10**decimal); 
        }else if(_type == 4){
          token_amount = ethers.parseEther(token_amount.toString())
        }
      }
      btn.html("Sending...")
      btn.attr('disabled', true)
    
      prepareWriteContract({
        address: get_contract(), 
        abi: Abi,
        functionName: 'listItem',
        args: [id, _id, token_amount, _type, _address, startTime, freeCredit, reqPoints, share]
      }).then(config=>{
          writeContract(config).then(res => {
            waitForTransaction({ confirmations: 2, hash: res.hash }).then(res => {
              event.currentTarget.submit();
            }).catch(error => {
              msg.html("<div class='alert alert-danger'>"+error.message+"</div>");
                btn.html("Create");
                btn.attr('disabled', false)
            });
          }).catch(error => {
            msg.html("<div class='alert alert-danger'>"+error.message+"</div>");
              btn.html("Create");
              btn.attr('disabled', false)
          });
      }).catch(error => {
        msg.html("<div class='alert alert-danger'>"+error.message+"</div>");
        btn.html("Create");
        btn.attr('disabled', false)
      });
  }); 
});

$(document).on('click', '.net_drop_show', (event) => {
  let net_no = $(event.currentTarget).attr('tt'); 
  switchNetwork({chainId: net_no}); 
})

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


// sorting
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


$(document).on('click', '.net_start_soon_sort', function(e){
  $(".h_soon").hide(); 
  let net = $(e.currentTarget).attr('net');
  let net_name = $(e.currentTarget).attr('net_name');
  let start_soon_pp = $("#start_soon_pp").text();
  if(net == 'all'){
      $("#chain_name_start_soon").html('Blockchain')
      $(".h_soon").show();
      if( start_soon_pp != 'Category' ){
          $(".h_soon").hide();
          $('.h_soon'+start_soon_pp).show();
      }
  }else{
      $("#chain_name_start_soon").html(net_name)
      $("#chain_name_start_soon").attr('net', net)
      if( start_soon_pp == 'Category' ){
          $(".h_soon").hide();
      }else{
          $('.h_soon'+net+'.h_soon'+start_soon_pp).show(); 
          return; 
      }
      $('.h_soon'+net).show(); 
  }
})

$(document).on('click', '.start_soon_category', function(e){
  $(".h_soon").hide();
  let start_soon_pp = $("#start_soon_pp"); 
  let name = $(e.currentTarget).attr('pp_name');
  let chain = $("#chain_name_start_soon"); 
  let chainID = $("#chain_name_start_soon").attr('net'); 
  if(name == 'all'){
    start_soon_pp.html("Category")
      $(".h_soon").show();
      if( chain.text() != 'Blockchain' ){
          $(".h_soon").hide();
          $('.h_soon'+chainID).show();
      }
  }else{
    start_soon_pp.html(name)
      if( chain.text() != 'Blockchain' ){
          $(".h_soon").hide();
          $('.h_soon'+chainID+".h_soon"+name).show();
          return; 
      }
      $(".h_soon"+name).show();
  }
});

$(document).on('click', '.net_up_sort', function(e){
  $(".h_up").hide(); 
  let net = $(e.currentTarget).attr('net');
  let net_name = $(e.currentTarget).attr('net_name');
  let up_pp = $("#up_pp").text();
  if(net == 'all'){
      $("#chain_name_up").html('Blockchain')
      $(".h_up").show();
      if( up_pp != 'Category' ){
          $(".h_up").hide();
          $('.h_up'+up_pp).show();
      }
  }else{
      $("#chain_name_up").html(net_name)
      $("#chain_name_up").attr('net', net)
      if( up_pp == 'Category' ){
          $(".h_up").hide();
      }else{
          $('.h_up'+net+'.h_up'+up_pp).show(); 
          return; 
      }
      $('.h_up'+net).show(); 
  }
})

$(document).on('click', '.up_category', function(e){
  $(".h_up").hide();
  let up_pp = $("#up_pp"); 
  let name = $(e.currentTarget).attr('pp_name');
  let chain = $("#chain_name_up"); 
  let chainID = $("#chain_name_up").attr('net'); 
  if(name == 'all'){
      up_pp.html("Category")
      $(".h_up").show();
      if( chain.text() != 'Blockchain' ){
          $(".h_up").hide();
          $('.h_up'+chainID).show();
      }
  }else{
      up_pp.html(name)
      if( chain.text() != 'Blockchain' ){
          $(".h_up").hide();
          $('.h_up'+chainID+".h_up"+name).show();
          return; 
      }
      $(".h_up"+name).show();
  }
}); 


//end sorting