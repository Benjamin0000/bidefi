
export const mode = {
    id: "mode:34443",   // CAIP-2 standard
    chainId: 34443,
    chainNamespace: "eip155",   // Ethereum-compatible namespace
    name: "Mode Network",
    currency: "ETH",
    explorerUrl: "https://explorer.mode.network",
    rpcUrl: "https://mainnet.mode.network",   // You can also use https://34443.rpc.thirdweb.com or https://mode.drpc.org
    imageUrl: "https://mode.network/logo.png",  // Example logo URL
};

export const bscTestnet = {
    id: 'eip155:97',                 // CAIP-2 ID for BSC Testnet
    chainId: 97,                     // Chain ID for BSC Testnet
    chainNamespace: 'eip155',        // Ethereum-compatible namespace
    name: 'BSC Testnet',             // Network name
    currency: 'BNB',                 // Native currency of BSC Testnet
    explorerUrl: 'https://testnet.bscscan.com',  // Block explorer for BSC Testnet
    rpcUrl: 'https://data-seed-prebsc-1-s1.binance.org:8545/',  // RPC URL for BSC Testnet
    imageUrl: 'https://cryptologos.cc/logos/bnb-bnb-logo.png',  // Logo for BNB
    imageId: 'bnb-logo'
};

export const linea = {
    id: 'eip155:59144',              // CAIP-2 ID for Linea Mainnet
    chainId: 59144,                  // Chain ID for Linea Mainnet
    chainNamespace: 'eip155',        // Ethereum-compatible namespace
    name: 'Linea',                   // Network name
    currency: 'ETH',                 // Native currency of Linea
    explorerUrl: 'https://explorer.linea.build',  // Block explorer for Linea Mainnet
    rpcUrl: 'https://rpc.linea.build',   // RPC URL for Linea Mainnet
    imageUrl: 'https://cryptologos.cc/logos/ethereum-eth-logo.png',  // Logo for ETH
    imageId: 'linea-logo'
};

export const scroll = {
    id: 'eip155:534351',             // CAIP-2 ID for Scroll Mainnet
    chainId: 534351,                 // Chain ID for Scroll Mainnet
    chainNamespace: 'eip155',        // Ethereum-compatible namespace
    name: 'Scroll',                  // Network name
    currency: 'ETH',                 // Native currency of Scroll
    explorerUrl: 'https://scroll.io/explorer',  // Block explorer for Scroll Mainnet
    rpcUrl: 'https://rpc.scroll.io',  // RPC URL for Scroll Mainnet
    imageUrl: 'https://cryptologos.cc/logos/ethereum-eth-logo.png',  // Logo for ETH
    imageId: 'scroll-logo'
};
