<?php

namespace UtxoOne\LndPhp\Services;

use Exception;
use UtxoOne\LndPhp\Models\ChannelPoint;
use UtxoOne\LndPhp\Models\NodeInfo;
use UtxoOne\LndPhp\Services\Lnd;

class LightningService extends Lnd
{

    /**
     * AbandonChannel
     * 
     * AbandonChannel removes all channel state from the database except for a close summary. 
     * This method can be used to get rid of permanently unusable channels due to bugs fixed in newer versions of lnd. 
     * This method can also be used to remove externally funded channels where the funding transaction was never broadcast. 
     * Only available for non-externally funded channels in dev build.
     * 
     * @link https://api.lightning.community/#abandonchannel
     * 
     * @param ChannelPoint $channelPoint
     * @param bool $pendingFundingShimOnly
     * @param bool $iKnowWhatIAmDoing
     * 
     * @return void
     * 
     * @throws Exception
     */
    public function abandonChannel(
        ChannelPoint $channelPoint,
        bool $pendingFundingShimOnly = false,
        bool $iKnowWhatIAmDoing = false
    ): bool {
        try {
            $this->call('GET', 'getinfo', [
                'channel_point' => $channelPoint->getFundingTxidStr(),
                'pending_funding_shim_only' => $pendingFundingShimOnly,
                'i_know_what_i_am_doing' => $iKnowWhatIAmDoing,
            ]);

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * GetInfo
     * 
     * GetInfo returns general information concerning the lightning node including it's 
     * identity pubkey, alias, the chains it is connected to, and information 
     * concerning the number of open+pending channels.
     * 
     * @link https://api.lightning.community/#getinfo
     * 
     * @return NodeInfo
     * 
     * @throws Exception
     */
    public function getInfo(): NodeInfo
    {
        try {
            return new NodeInfo($this->call('GET', 'getinfo', null));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
