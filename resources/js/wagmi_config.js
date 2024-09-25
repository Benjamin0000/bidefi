import { http, createConfig, createStorage } from '@wagmi/core'
import { mainnet, arbitrum, bsc, bscTestnet, base, optimism, zksync, zora, linea, mode, scroll } from '@wagmi/core/chains'
import { createClient } from 'viem'
import { injected, walletConnect, coinbaseWallet } from '@wagmi/connectors'

const chains = [mainnet, arbitrum, bsc, bscTestnet, base, optimism, zksync, zora, linea, mode, scroll]

export const config = createConfig({
  chains: chains,
  storage: createStorage({ storage: window.localStorage }), 
  client({ chain }) {
    return createClient({ chain, transport: http() })
  },
})

// transports: {
//     [mainnet.id]: http(),
//     [arbitrum.id]: http(),
//     [bsc.id]: http(),
//     [bscTestnet.id]: http(),
//     [base.id]: http(),
//     [optimism.id]: http(),
//     [zksync.id]: http(),
//     [zora.id]: http(),
//     [linea.id]: http(),
//     [mode.id]: http(),
//     [scroll.id]: http()
//   },