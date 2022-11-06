<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class NodeInfo {

    public function __construct(private array $data)
    {
    }

    /**
     * Get Version
     * 
     * @return string
     */
    public function getVersion(): string
    {
        return $this->data['version'];
    }

    /**
     * Get Commit Hash
     * 
     * @return string
     */
    public function getCommitHash(): string
    {
        return $this->data['commit_hash'];
    }

    /**
     * Get Identity Pubkey
     * 
     * @return string
     */
    public function getIdentityPubkey(): string
    {
        return $this->data['identity_pubkey'];
    }

    /**
     * Get Alias
     * 
     * @return string
     */
    public function getAlias(): string
    {
        return $this->data['alias'];
    }

    /**
     * Get Num Pending Channels
     * 
     * @return int
     */
    public function getNumPendingChannels(): int
    {
        return $this->data['num_pending_channels'];
    }

    /**
     * Get Num Active Channels
     * 
     * @return int
     */
    public function getNumActiveChannels(): int
    {
        return $this->data['num_active_channels'];
    }

    /**
     * Get Num Peers
     * 
     * @return int
     */
    public function getNumPeers(): int
    {
        return $this->data['num_peers'];
    }

    /**
     * Get Block Height
     * 
     * @return int
     */
    public function getBlockHeight(): int
    {
        return $this->data['block_height'];
    }

    /**
     * Get Block Hash
     * 
     * @return string
     */
    public function getBlockHash(): string
    {
        return $this->data['block_hash'];
    }

    /**
     * Get Best Header Timestamp
     *
     * @return string
     */
    public function getBestHeaderTimestamp(): string
    {
        return $this->data['best_header_timestamp'];
    }

    /**
     * Is Synced To Chain
     * 
     * @return bool
     */
    public function isSyncedToChain(): bool
    {
        return $this->data['synced_to_chain'];
    }

    /**
     * Is Synced to Graph
     * 
     * @return bool
     */
    public function isSyncedToGraph(): bool
    {
        return $this->data['synced_to_graph'];
    }

    /**
     * Is Testnet
     * 
     * @return bool
     */
    public function isTestnet(): bool
    {
        return $this->data['testnet'];
    }

    /**
     * Get Chains
     * 
     * @return ChainList
     */
    public function getChains(): ChainList
    {
        return new ChainList($this->data['chains']);
    }

    /**
     * Get URIs
     * 
     * @return array
     */
    public function getUris(): array
    {
        return $this->data['uris'];
    }

    /**
     * Get Features
     * 
     * @return NodeFeatureList
     */
    public function getFeatures(): NodeFeatureList
    {
        return new NodeFeatureList($this->data['features']);
    }

    /**
     * Requires Htlc Interceptor
     * 
     * @return bool
     */
    public function requiresHtlcInterceptor(): bool
    {
        return $this->data['require_htlc_interceptor'];
    }

}
