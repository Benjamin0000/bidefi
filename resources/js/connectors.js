import { createWeb3Modal, defaultWagmiConfig } from '@web3modal/wagmi'
import { mainnet, arbitrum, bsc, base, linea, optimism, zkSync, bscTestnet} from "@wagmi/core/chains"
const metadata = {
  name: 'Bidefi',
  description: 'Bidefi',
  url: 'https://bidefi.io',
  icons: ['https://avatars.githubusercontent.com/u/37784886']
}
const projectId = '25050e2a2f9d7c534f9432098aa3d9c0'
const chains = [mainnet, bsc, arbitrum, base, linea, optimism, zkSync, bscTestnet]; 
const wagmiConfig = defaultWagmiConfig({ chains, projectId, metadata })
// 3. Create modal
export const web3Modal = createWeb3Modal({ wagmiConfig, projectId, chains })