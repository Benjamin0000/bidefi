import Layout from "../Layout";
import Trending from "./Components/Trending";
import LiveAuction from "./Components/LiveAuction";
import TopBidders from "./Components/TopBidders";
import Upcoming from "./Components/Upcoming";
import Completed from "./Components/Completed";
import LatestWinners from "./Components/LatestWinners";

const Home = () => {
    return (
        <>
            <Trending/>
            <LiveAuction/>
            <TopBidders/>
            <Upcoming/>
            <Completed/>
            <LatestWinners/>
        </>
    );
};

Home.layout = (page) => <Layout children={page} />;
export default Home;
