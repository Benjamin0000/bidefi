import './bootstrap'
import { web3modal } from './connectors'
import axios from 'axios';
import { truncateAddress, bytestohex, paramsToObject } from './bootstrap';
import Abi from "./Bidding_ABI.json";
import { ethers } from "ethers";
const bidding_contract = '0xb66E5c378558e55015E0Da71b3dB99938c77879B'

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
             showUserLogin(account);
          }
        }
      });
  }
  if(network.chain && network.chain.id != 97){
      alert('we only support the Sepolia Testnet'); 
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
  let total = value * window.bid_price;
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
  let cost = amt * window.bid_price;
  if (amt < window.min_bid) return;

  prepareWriteContract({
    address: bidding_contract,
    abi: Abi,
    functionName: 'buyPoint',
    args: [amt],
    value: ethers.parseEther(cost.toString())
  }).then(config=>{
    writeContract(config).then(res => {
      waitForTransaction({ confirmations: 2, hash: res.hash }).then(res=>{
        axios.post('/LETBOrwenhvqRifu7Lu', { ffffxfr: amt }).then(res=>{
          if (res.data.done) {
            $("#bid_info").show();
            setTimeout(() => {
              window.location.reload();
            }, 3000);
          }
        }).catch(error => {
          $("#msg").html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
          btn.html("Buy Credit");
          btn.attr('disabled', false)
        }) 
      }).catch(error => {
        $("#msg").html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
        btn.html("Buy Credit");
        btn.attr('disabled', false)
      })
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
});

//place a bid
$(document).on('submit', '#place_bid_form', (event) => {
  event.preventDefault()
  let msg = $(event.target).find('#bid_msg');
  let btn = $(event.target).find('button');
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);
  let id = params.get('id');
  let amt = params.get('amt');

  btn.html("Please wait...")
  btn.attr('disabled', true)
  msg.html('');

  axios.post('/ho8OJ92Bs9RyEW67', { id: id, amt: amt, check: true }).then(res => {
    if(res.data.error) {
      msg.html("<div class='alert alert-danger'><h5>" + res.data.error + "</h5></div>");
      btn.html("Place a bid")
      btn.attr('disabled', false)
      return;
    }
    prepareWriteContract({
      address: bidding_contract,
      abi: Abi,
      functionName: 'placeBid',
      args: [id, amt]
    }).then(config=>{
      writeContract(config).then(res => {
        waitForTransaction({ confirmations: 2, hash: res.hash }).then(res => {
          axios.post('/ho8OJ92Bs9RyEW67', {id:id, amt:amt}).then(res=>{
            msg.html("<div class='alert alert-success'><h5> Bid placed successfully </h5></div>");
            setTimeout(() => {
              window.location.reload()
            }, 2000);
          }).catch(error => {
            msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
            btn.html("Place a bid");
            btn.attr('disabled', false);
          });
        }).catch(error => {
          msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
          btn.html("Place a bid");
          btn.attr('disabled', false);
        });
      }).catch(error => {
        msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
        btn.html("Place a bid");
        btn.attr('disabled', false);
      });
    }).catch(error=>{
      msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
      btn.html("Place a bid");
      btn.attr('disabled', false);
    })
  }).catch(error=>{
    msg.html("<div class='alert alert-danger'><h5>"+error.message+"</h5></div>");
    btn.html("Place a bid");
    btn.attr('disabled', false);
  })

});

//claim price

$(document).on('click', '#claim_price', (event) => {
    let btn = $(event.target);
    let id = btn.attr('idd');
    let msg = $("#c_msg");
    btn.html('Please wait...');
    msg.html(''); 
    prepareWriteContract({
      address: bidding_contract,
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
  let _id = params.get('id') ? params.get('id') : '0';
  let token_amount = params.get('prize') ? params.get('prize') : '0';
  let _type = params.get('type');
  let _address = params.get('contract_address');
  _address = _address ? _address : '0x0000000000000000000000000000000000000000'; 
  let startTime = params.get('start_time');
  let free_credit = params.get('free');
  let reqPoints = params.get('start_points'); 

  $("#msg").html(''); 
  if(token_amount > 0){
      token_amount = ethers.parseEther(token_amount.toString())
  }
  btn.html("Sending...")
  btn.attr('disabled', true)
  prepareWriteContract({
    address: bidding_contract, 
    abi: Abi,
    functionName: 'listItem',
    args: [_id, token_amount, _type, _address, startTime, free_credit, reqPoints]
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
  btn.html("Sending...");
  $("#msg").html(''); 

  if (price > 0) {
      prepareWriteContract({
        address: bidding_contract,
        abi: Abi,
        functionName: 'changePointPrice',
        args: [ethers.parseEther(price.toString())]
      }).then(config=>{
        writeContract(config).then(res => { 
          waitForTransaction({confirmations: 2, hash: res.hash }).then(res => {
            event.currentTarget.submit();
          }).catch(error => {
            console.log("from wait"); 
            console.log(res); 
            $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
            btn.html("Update");
            btn.attr('disabled', false);
          });
        }).catch(error => {
          console.log('from the write contract'); 
          console.log(res)
          $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
          btn.html("Update");
          btn.attr('disabled', false);
        });
      }).catch(error=>{
        $("#msg").html("<div class='alert alert-danger'>"+error.message+"</div>");
        btn.html("Update");
        btn.attr('disabled', false);
      }); 
  }else{
    alert('please enter a valid number');
  }
  
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
      address: bidding_contract,
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
    address: bidding_contract,
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
    address: bidding_contract,
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