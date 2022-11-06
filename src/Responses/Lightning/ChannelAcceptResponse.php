<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

use UtxoOne\LndPhp\Enums\Lightning\ChannelCommitmentType;

class ChannelAcceptResponse
{
    public function __construct(private array $data)
    {
    }

    /**
     * Get Node Pubkey
     * 
     * The pubkey of the node that wishes to open an inbound channel.
     * 
     * @return string
     */
    public function getNodePubkey(): string
    {
        return $this->data['node_pubkey'];
    }

    /**
     * Get Chain Hash
     * 
     * The hash of the genesis block of the blockchain that this channel will be opened within.
     * 
     * @return string
     */
    public function getChainHash(): string
    {
        return $this->data['chain_hash'];
    }

    /**
     * Get Pending Chan Id
     * 
     * 	The pending channel id.
     * 
     * @return string
     */
    public function getPendingChanId(): string
    {
        return $this->data['pending_chan_id'];
    }

    /**
     * Get Funding Amt
     * 
     * The funding amount in satoshis that initiator wishes to use in the channel.
     * 
     * @return int
     */
    public function getFundingAmt(): int
    {
        return $this->data['funding_amt'];
    }

    /**
     * Get Push Amt
     * 
     * The push amount of the proposed channel in millisatoshis.
     * 
     * @return int
     */
    public function getPushAmt(): int
    {
        return $this->data['push_amt'];
    }

    /**
     * Get Dust Limit
     * 
     * 	The dust limit of the initiator's commitment tx.
     * 
     * @return int
     */
    public function getDustLimit(): int
    {
        return $this->data['dust_limit'];
    }

    /**
     * Get Max Value In Flight
     * 
     * The maximum amount of coins in millisatoshis that can be pending in this channel.
     * 
     * @return int
     */
    public function getMaxValueInFlight(): int
    {
        return $this->data['max_value_in_flight'];
    }

    /**
     * Get Channel Reserve
     * 
     * The minimum amount of satoshis the initiator requires us to have at all times.
     * 
     * @return int
     */
    public function getChannelReserve(): int
    {
        return $this->data['channel_reserve'];
    }

    /**
     * Get Min Htlc
     * 
     * The minimum HTLC size in millisatoshis that the initiator will accept.
     * 
     * @return int
     */
    public function getMinHtlc(): int
    {
        return $this->data['min_htlc'];
    }

    /**
     * Get Fee Per Kw
     * 
     * The fee rate of the proposed channel in satoshis per kilo-weight.
     * 
     * @return int
     */
    public function getFeePerKw(): int
    {
        return $this->data['fee_per_kw'];
    }

    /**
     * Get Csv Delay
     * 
     * The number of blocks that the initiator will wait to claim on-chain funds if the counterparty becomes unresponsive.
     * 
     * @return int
     */
    public function getCsvDelay(): int
    {
        return $this->data['csv_delay'];
    }

    /**
     * Get Max Accepted Htlcs
     * 
     * The maximum number of HTLCs that the initiator will accept.
     * 
     * @return int
     */
    public function getMaxAcceptedHtlcs(): int
    {
        return $this->data['max_accepted_htlcs'];
    }

    /**
     * Get Channel Flags
     * 
     * The channel flags that the initiator will use for the channel.
     * 
     * @return int
     */
    public function getChannelFlags(): int
    {
        return $this->data['channel_flags'];
    }

    /**
     * Get Commitment Type
     * 
     * The commitment type that the initiator will use for the channel.
     * 
     * @return string
     */
    public function getCommitmentType(): ChannelCommitmentType
    {
        return ChannelCommitmentType::fromValue($this->data['commitment_type']);
    }

    /**
     * Get Wants Zero Conf
     * 
     * Whether the initiator will accept zero-conf funding.
     * 
     * @return bool
     */
    public function getWantsZeroConf(): bool
    {
        return $this->data['wants_zero_conf'];
    }

    /**
     * Get Wants Scid Alias
     * 
     * Whether the initiator will accept a short channel id alias.
     * 
     * @return bool
     */
    public function getWantsScidAlias(): bool
    {
        return $this->data['wants_scid_alias'];
    }

}
