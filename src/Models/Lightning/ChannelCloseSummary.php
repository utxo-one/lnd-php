<?php

namespace UtxoOne\LndPhp\Models\Lightning;

use UtxoOne\LndPhp\Enums\Lightning\ClosureType;
use UtxoOne\LndPhp\Enums\Lightning\Initiator;

class ChannelCloseSummary
{
    public function __construct(private array $data)
    {  
    }

    /**
     * Get Channel Point
     * 
     * The outpoint (txid:index) of the funding transaction.
     * 
     * @return string
     */
    public function getChannelPoint(): string
    {
        return $this->data['channel_point'];
    }

    /**
     * Get Chan Id
     * 
     * The unique channel ID for the channel.
     * 
     * @return int
     */
    public function getChanId(): int
    {
        return $this->data['chan_id'];
    }

    /**
     * Get Chain Hash
     * 
     * The hash of the genesis block of the chain the channel resides within.
     * 
     * @return string
     */
    public function getChainHash(): string
    {
        return $this->data['chain_hash'];
    }
     
    /**
     * Get Closing Tx Hash
     * 
     * The hash of the closing transaction.
     * 
     * @return string
     */
    public function getClosingTxHash(): string
    {
        return $this->data['closing_tx_hash'];
    }

    /**
     * Get Remote Pubkey
     * 
     * The identity pubkey of the remote peer.
     * 
     * @return string
     */
    public function getRemotePubkey(): string
    {
        return $this->data['remote_pubkey'];
    }

    /**
     * Get Capacity
     * 
     * The total capacity of the channel.
     * 
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->data['capacity'];
    }

    /**
     * Get Close Height
     * 
     * The height at which the channel was closed.
     * 
     * @return int
     */
    public function getCloseHeight(): int
    {
        return $this->data['close_height'];
    }

    /**
     * Get Settled Balance
     * 
     * The sum of all the settled HTLCs in this channel.
     * 
     * @return int
     */
    public function getSettledBalance(): int
    {
        return $this->data['settled_balance'];
    }

    /**
     * Get Time Locked Balance
     * 
     * The sum of all the time-locked HTLCs in this channel.
     * 
     * @return int
     */
    public function getTimeLockedBalance(): int
    {
        return $this->data['time_locked_balance'];
    }

    /**
     * Get Close Type
     * 
     * The type of channel closure.
     * 
     * @return string
     */
    public function getCloseType(): ClosureType
    {
        return ClosureType::fromValue($this->data['close_type']);
    }

    /**
     * Get Open Initiator
     * 
     * Open initiator is the party that initiated opening the channel. 
     * Note that this value may be unknown if the channel was closed 
     * before we migrated to store open channel information after close.
     * 
     * @return Initiator
     */
    public function getOpenInitiator(): Initiator
    {
        return Initiator::fromValue($this->data['open_initiator']);
    }

    /**
     * Get Close Initiator
     * 
     * Close initiator indicates which party initiated the close. 
     * This value will be unknown for channels that were cooperatively 
     * closed before we started tracking cooperative close initiators. 
     * Note that this indicates which party initiated a close, 
     * and it is possible for both to initiate cooperative or force closes, 
     * although only one party's close will be confirmed on chain.
     * 
     * @return Initiator
     */
    public function getCloseInitiator(): Initiator
    {
        return Initiator::fromValue($this->data['close_initiator']);
    }

    /**
     * Get Resolutions
     * 
     * The resolution outcome of the channel.
     * 
     * @return ResolutionList
     */
    public function getResolutions(): ResolutionList
    {
        return new ResolutionList($this->data['resolutions']);
    }

    /**
     * Get Alias Scids
     * 
     * This lists out the set of alias short channel ids that existed for the closed channel. This may be empty.
     * 
     * @return array
     */
    public function getAliasScids(): array
    {
        return $this->data['alias_scids'];
    }

    /**
     * Get Zero Conf Confirmed Scid
     * 
     * 	The confirmed SCID for a zero-conf channel.
     * 
     * @return int
     */
    public function getZeroConfConfirmedScid(): int
    {
        return $this->data['zero_conf_confirmed_scid'];
    }
}
