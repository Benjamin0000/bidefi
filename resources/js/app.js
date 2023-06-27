import './bootstrap'
import { web3modal } from './connectors'
import axios from 'axios';
import { truncateAddress, bytestohex, paramsToObject } from './bootstrap';
import Abi from "./Bidding_ABI.json";
import { ethers } from "ethers";
const bidding_contract = '0x0c94C4d8Cd13CD9dD75282F991e4fc4B4263cCfB'

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
} from '@wagmi/core'

$(document).on('click', '#connectbtn', () => {
  web3modal.openModal()
  console.log('modal open')
});

function logout() {
  disconnect().then(() => {
    axios.get('/RVgtFB').then(res => {
      if (res.data.done) {
        window.location.reload();
      }
    });
  });
}

$("#logout").click((e) => {
  logout()
});


watchNetwork((network) => { // when you disconnect & connect this will handle it. 
  let account = getAccount();
  if (account && account.address && network.chain.id == 5) {
    axios.get('/ogNkV').then(res => {
      if (!res.data.auth) {
        const msg = bytestohex();
        signMessage({ message: msg }).then(sig => {
          let data = {
            'address': account.address,
            'sig': sig,
            'message': msg
          }
          axios.post('/VgtFB', data).then(res2 => {
            if (res2.data.auth)
              window.location.reload();
          });
        });
      } else {
        if (account.address != res.data.address) {
          logout();
        }
        $("#address_show").html(truncateAddress(account.address));
        fetchBalance({
          address: account.address,
          formatUnits: 'ether',
        }).then(balance => {
          $("#eth_bal").html(Number(balance.formatted).toFixed(5))
        })
      }
    });
  }
});

window.onload = () => {
  let account = getAccount(); // client account
  if (account.address != window.auth_address) {
    logout();
  } else {
    if (account.address) {
      $("#address_show").html(truncateAddress(account.address));
      fetchBalance({
        address: account.address,
        formatUnits: 'ether',
      }).then(balance => {
        $("#eth_bal").html(Number(balance.formatted).toFixed(5))
      })
    }
  }

  console.log(account)
}

watchAccount((account) => {
  console.log(account)
  axios.get('/ogNkV').then(res => {
    if (res.data.auth && account.address) {
      if (account.address != res.data.address) {
        logout()
      }
    }
  });
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
  let cost = amt * window.bid_price;

  if (amt < window.min_bid) return;

  writeContract({
    address: bidding_contract,
    abi: Abi,
    functionName: 'buyPoint',
    args: [amt],
    value: ethers.utils.parseEther(cost.toString()),
  }).then(res => {
    waitForTransaction({ confirmations: 1, hash: res.hash }).then(res => {
      axios.post('/LETBOrwenhvqRifu7Lu', { ffffxfr: amt }).then(res => {
        if (res.data.done) {
          $("#bid_info").show();
          setTimeout(() => {
            window.location.reload();
          }, 3000);
        }
      })
    });
  }).catch(error => {
    btn.html("Buy Credit");
    btn.attr('disabled', false)
  });
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
    if (res.data.error) {
      msg.html("<div class='alert alert-danger'><h5>" + res.data.error + "</h5></div>");
      btn.html("Place a bid")
      btn.attr('disabled', false)
      return;
    }

    writeContract({
      address: bidding_contract,
      abi: Abi,
      functionName: 'placeBid',
      args: [id, amt]
    }).then(res => {
      waitForTransaction({ confirmations: 1, hash: res.hash }).then(res => {
        axios.post('/ho8OJ92Bs9RyEW67', {id:id, amt:amt}).then(res=>{
          msg.html("<div class='alert alert-success'><h5> Bid placed successfully </h5></div>");
          setTimeout(() => {
            window.location.reload()
          }, 2000);
        })
      });
    }).catch(error => {
      btn.html("Place a bid");
      btn.attr('disabled', false)
    });

  });

});

//claim price

$(document).on('click', '#claim_price', (event) => {
  let btn = $(event.target);
  let id = btn.attr('idd');
  btn.html('Please wait...');
  writeContract({
    address: bidding_contract,
    abi: Abi,
    functionName: 'claimPrice',
    args: [id]
  }).then(res => {
    waitForTransaction({ confirmations: 1, hash: res.hash }).then(res => {
      axios.post('/FapHqrwPfkewSHq', { id: id, hash: res.hash }).then(res => {
        alert("Price claimed")
        window.location.reload()
      })
    });
  }).catch(error => {
    btn.html("Claim");
    btn.attr('disabled', false);
  });
})

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

  // let paramsObj = paramsToObject(params); 

  //ethers.utils.parseEther(amount.toString())

  let _id = params.get('id') ? params.get('id') : 0;
  let token_amount = params.get('prize') ? params.get('prize') : 0;
  let _type = params.get('type');
  let _address = params.get('contract_address');
  let startTime = params.get('start_time');
  let _free_credit = params.get('free');
  btn.html("Sending...")
  btn.attr('disabled', true)

  writeContract({
    address: bidding_contract,
    abi: Abi,
    functionName: 'listItem',
    args: [_id, token_amount, _type, _address, startTime, _free_credit]
  }).then(res => {
    // waitForTransaction({ confirmations: 1, hash: res.hash }).then(res => {
      event.currentTarget.submit();

    // });
  }).catch(error => {
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

  if (price > 0) {
    writeContract({
      address: bidding_contract,
      abi: Abi,
      functionName: 'changePointPrice',
      args: [ethers.utils.parseEther(price.toString())]
    }).then(res => {
      // waitForTransaction({ confirmations: 1, hash: res.hash }).then(res => {
        event.currentTarget.submit();
      // });
    }).catch(error => {
      btn.html("Update");
      btn.attr('disabled', false);
    });
    return;
  }
  alert('please enter a valid number');
});

$(document).on('submit', '#create_admin_form', (event) => {
  event.preventDefault();
  let btn = $(event.target).find('button');
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);

  let address = params.get('address');
  btn.html("Sending...");
  btn.attr('disabled', true);

  axios.post('/admin/admins', { 'address': address, 'check': true }).then(res => {
    if (res.data.error) {
      $.notify(res.data.error, "error");
      btn.html("Create");
      btn.attr('disabled', false);
      return;
    }
    writeContract({
      address: bidding_contract,
      abi: Abi,
      functionName: 'approveAdmin',
      args: [address, true]
    }).then(res => {
      // waitForTransaction({ confirmations: 1, hash: res.hash }).then(res => {
        event.currentTarget.submit();
      // });
    }).catch(error => {
      btn.html("Create");
      btn.attr('disabled', false);
    });
    return;
  })
});

$(document).on('submit', '.remove_admin', (event) => {
  event.preventDefault();
  let btn = $(event.target).find('button');
  let data = $(event.target).serialize();
  let params = new URLSearchParams(data);

  let address = params.get('address');
  btn.html("Sending...");
  btn.attr('disabled', true);

  writeContract({
    address: bidding_contract,
    abi: Abi,
    functionName: 'approveAdmin',
    args: [address, false]
  }).then(res => {
    // waitForTransaction({ confirmations: 1, hash: res.hash }).then(res => {
      event.currentTarget.submit();
    // });
  }).catch(error => {
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

  writeContract({
    address: bidding_contract,
    abi: Abi,
    functionName: 'withdraw',
    args: [ethers.utils.parseEther(amt.toString())]
  }).then(res => {
    // waitForTransaction({ confirmations: 1, hash: res.hash }).then(res => {
      event.currentTarget.submit();
    // });
  }).catch(error => {
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