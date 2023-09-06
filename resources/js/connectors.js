import { EthereumClient, w3mConnectors, w3mProvider } from '@web3modal/ethereum'
import { Web3Modal } from '@web3modal/html'
import { configureChains, createConfig } from '@wagmi/core'
import { arbitrum, bsc, base, linea, optimism, zkSync, bscTestnet} from "@wagmi/core/chains"

const chains = [bsc, arbitrum, base, linea, optimism, zkSync, bscTestnet]; 
const projectId = '107dcb4dd9a4bc4dc47626c5dd590371'

const { publicClient } = configureChains(chains, [w3mProvider({ projectId })])
const wagmiConfig = createConfig({
  autoConnect: true,
  connectors: w3mConnectors({ projectId, version: 1, chains }),
  publicClient
})
const ethereumClient = new EthereumClient(wagmiConfig, chains)
export const web3modal = new Web3Modal({ projectId }, ethereumClient)

web3modal.setTheme({
    themeVariables: {
      '--w3m-font-family': 'Roboto, sans-serif',
      '--w3m-accent-color': '#F5841F',
      '--w3m-z-index': 100
    }
})