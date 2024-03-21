
const mode = {
    id: 34443,
    name: "Mode",
    network: "Mode-Mainnet",
    nativeCurrency: {
        decimals: 18,
        name: "ETH",
        symbol: "ETH"
   },
    rpcUrls: {
        default: {
            http:  ["https://mainnet.mode.network"],
            webSocket:  ["wss://mainnet.mode.network"]
       },
        public: {
            http:  ["https://mainnet.mode.network"],
            webSocket:  ["wss://mainnet.mode.network"]
       }
   },
    blockExplorers: {
        default: {
            name: "Mode explorer",
            url: "https://explorer.mode.network"
       }
   },
    contracts: {
        multicall3: {
            address: "0xBAba8373113Fb7a68f195deF18732e01aF8eDfCF"
       }
   }
}
export default mode; 