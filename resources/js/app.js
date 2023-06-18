import './bootstrap'
import {web3modal} from './connectors'
import axios from 'axios';
import {truncateAddress, bytestohex} from './bootstrap'; 
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
  readContract
} from '@wagmi/core'

$(document).on('click', '#connectbtn', ()=>{
  web3modal.openModal()
}); 

function logout()
{
    disconnect().then(()=>{
        axios.get('/RVgtFB').then(res=>{
            if(res.data.done){
                window.location.reload(); 
            }
        }); 
    }); 
}

$("#logout").click((e)=>{
    logout()
}); 


watchNetwork((network) =>{ // when you disconnect & connect this will handle it. 
  let account = getAccount();
  if(account && account.address && network.chain.id == 97){
      axios.get('/ogNkV').then(res=>{
          if(!res.data.auth){
              const msg = bytestohex(); 
              signMessage({message: msg}).then(sig=>{
                  let data = {
                      'address':account.address,
                      'sig':sig,
                      'message':msg
                  }
                  axios.post('/VgtFB', data).then(res2=>{
                      if(res2.data.auth)
                          window.location.reload(); 
                  }); 
              }); 
          }else{
              if(account.address != res.data.address){
                  logout(); 
              }
              $("#address_show").html(truncateAddress(account.address));
              fetchBalance({
                address: account.address,
                formatUnits: 'ether',
              }).then(balance=>{
                $("#eth_bal").html(Number(balance.formatted).toFixed(5))
              })
          }
      });
  } 
}); 

window.onload = ()=>{
    let account = getAccount(); // client account
    if(account.address != window.auth_address){ 
        logout(); 
    }else{
      if(account.address){
        $("#address_show").html(truncateAddress(account.address));
        fetchBalance({
          address: account.address,
          formatUnits: 'ether',
        }).then(balance=>{
          $("#eth_bal").html(Number(balance.formatted).toFixed(5))
        })
      }
    }
}

watchAccount((account) => {
  axios.get('/ogNkV').then(res=>{
      if(res.data.auth && account.address){
          if(account.address != res.data.address){
              logout()
          }
      }
  }); 
})


$(document).on('submit', '#create_form', (e)=>{
  e.preventDefault()
  console.log(e.target)
}); 