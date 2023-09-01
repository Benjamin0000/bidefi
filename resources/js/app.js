import './bootstrap'
import { web3modal } from './connectors'
import axios from 'axios';
import { truncateAddress, bytestohex, paramsToObject } from './bootstrap';
import Abi from "./Bidding_ABI.json";
import { ethers } from "ethers";

function get_contract() {
  let id = window.network_id;
  let arbitrum = ""; 
  let optimism = ""; 
  let zk = "";
  let linea = "";
  let base = ""; 
  let bsc = '0xeE786C860418BB103853D9B519F819Dafb154182'; 

  if (id == 324) {
    return zk;
  } else if (id == 59144) {
    return linea;
  } else if(id == 8453){
    return base; 
  } else if(id == 97){
    return bsc; 
  } else if(id == 42161){
    return arbitrum;
  }else if(id == 10){
    return optimism; 
  }
  return 0;
}

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



$(document).on('click', '#connectbtn', () => {
  web3modal.openModal()
});

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
  fetchBalance({
    address: account.address,
    formatUnits: 'ether',
  }).then(balance => {
      $("#eth_bal").html(Number(balance.formatted).toFixed(5))
  }); 
}

//get item data
//get user point balance 
function setData(account) {
  readContract({
      address: get_contract(),
      abi: Abi,
      functionName: 'ItemData'
  }).then(data => {
      console.log(data)
      $("#ad_bid_price").val(ethers.formatEther(data[3])); 
      $("#ad_bid_com").val(data[2]);
      $("#ad_bid_fee").val(ethers.formatEther(data[4])); 
      readContract({
          address: get_contract(),
          abi: Abi,
          functionName: 'points',
          args: [account.address],
      }).then(bal => {
          let balance = Number(bal); 
          $(".bid_credit_info").html(balance); 
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
  if(account && account.address && network.chain.id == 97) {
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
             console.log("got here")
          }
        }
      });
  }
  if(network.chain && network.chain.id != 97){
      alert('we only support the Binance Testnet'); 
      switchNetwork({chainId: 97}); 
  }
});

watchAccount(user=>{
  if(window.auth_address && window.auth_address != user.address){
    logout();
  }
}); 


// buy bid credit
$(document).on('keyup', '#bid_amt', (e) => {
  $("#min_bid_info").hide();
  let value = e.target.value;
  let total = value * window.info.bid_price;
  $('#bid_eth_price').val(total);

  if (value < window.min_bid)
    $("#min_bid_info").show();
});



$(document).on('submit', '#buy_credit_form', (event) => {
  event.preventDefault()
  let amt = parseInt($("#bid_amt").val());
  let btn = $(event.target).find('button');
  btn.html("Please wait...")
  btn.attr('disabled', true)
  $("#msg").html('')
  let cost = amt * window.info.bid_price;
  if (amt < window.min_bid) return;
  let ref = $("#ref_val").val(); 
  if(!ref){
      ref = '0x0000000000000000000000000000000000000000'; 
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
            window.location.reload(); 
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

function confirm_credit_trx(msg, btn, token)
{
  axios.post('/ho8OJ92Bs9RyEW67', {ffffxfr:token}).then(res=>{
    if(res.data.done){
        msg.html("<div class='alert alert-success'><h5> Bid placed successfully </h5></div>");
        setTimeout(() => {
          window.location.reload()
        }, 2000);
    }else if(res.data.trial){
        return confirm_credit_trx(token); 
    }else if(res.data.stop){
        msg.html("<div class='alert alert-info'>Network error operation will still be completed in a short while</div>");
        return; 
    }
    btn.attr("disabled", false); 
  }).catch(error=>{
      confirm_credit_trx(msg, btn, token)
  }); 
}

//place a bid
$(document).on('submit', '#place_bid_form', (event) => {
  event.preventDefault()
  let msg = $(event.target).find('#bid_msg');
  let btn = $(event.target).find('button');
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);
  let id = params.get('id');
  let amt = Number(params.get('amt'));
  let min = Number(params.get('min')); 
  let cost = window.info.bid_fee; 
  msg.html('');

  if(amt < min){
    msg.html("<div class='alert alert-danger'><h5>Minimum bid is "+min+"</h5></div>");
    return ;
  }


  if(amt > window.info.points){
    msg.html("<div class='alert alert-danger'><h5>Insuficient bid credits</h5></div>");
    return ;
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
          confirm_credit_trx(msg, btn, secrete)
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



$(document).on('submit', '#create_form', (event) => {
  event.preventDefault()
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
    args: [id, _id, token_amount, _type, _address, startTime, freeCredit, reqPoints]
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