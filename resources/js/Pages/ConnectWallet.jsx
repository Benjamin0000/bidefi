import { useWeb3React } from '@web3-react/core'
// import {CoinbaseWallet} from '../connectors';
// import {WalletConnect} from '../connectors'; 
// import {Injected} from '../connectors'; 

const ConnectWallet = ()=>{
    // const { activate } = useWeb3React();
  

    return (
        <>
        <div className="modal fade popup" id="popup_bidf" tabIndex="-1" role="dialog" aria-hidden="true">
            <div className="modal-dialog modal-dialog-centered" role="document">
                <div className="modal-content">
                    <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div className="modal-body space-y-20 pd-40">
                        <h3>Connect Your Wallet</h3>
                        {/* <div className='c-style' onClick={()=>activate(Injected)}>
                            <div className="img">
                                <img src="/assets/images/icon/icon-1.png" alt="Image" />
                            </div>
                            <h4 className="heading">Meta Mask</h4>
                        </div>
                        <div className='c-style' onClick={()=>activate(WalletConnect)}>
                            <div className="img">
                                <img src="/assets/images/icon/WalletConnect.png" alt="Image"/>
                            </div>
                            <h4 className="heading">Wallet Connect</h4>
                        </div>
                        <div className='c-style' onClick={()=>activate(CoinbaseWallet)}>
                            <div className="img">
                                <img src="/assets/images/icon/icon-2.png" alt="Image"/>
                            </div>
                            <h4 className="heading">Coinbase Wallet</h4>
                        </div> */}
                    </div>
                </div>
            </div>
        </div>
        </>
    ); 
}

export default ConnectWallet; 