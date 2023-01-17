<?php

namespace UtxoOne\LndPhp\Services;

use UtxoOne\LndPhp\Enums\Endpoint;
use Exception;
use UtxoOne\LndPhp\Enums\Lightning\InvoiceState;
use UtxoOne\LndPhp\Models\Lightning\AddrToAmountEntryList;
use UtxoOne\LndPhp\Models\Lightning\ChannelCloseSummaryList;
use UtxoOne\LndPhp\Models\Lightning\ChannelPoint;
use UtxoOne\LndPhp\Models\Lightning\FeeLimit;
use UtxoOne\LndPhp\Models\Lightning\Invoice;
use UtxoOne\LndPhp\Models\Lightning\NodeInfo;
use UtxoOne\LndPhp\Responses\Lightning\AddInvoiceResponse;
use UtxoOne\LndPhp\Responses\Lightning\BakeMacaroonResponse;
use UtxoOne\LndPhp\Responses\Lightning\BatchOpenChannelResponse;
use UtxoOne\LndPhp\Responses\Lightning\ChannelAcceptResponse;
use UtxoOne\LndPhp\Responses\Lightning\ChannelBalanceResponse;
use UtxoOne\LndPhp\Responses\Lightning\CheckMacPermResponse;
use UtxoOne\LndPhp\Responses\Lightning\CloseChannelResponse;
use UtxoOne\LndPhp\Responses\Lightning\SendCoinsResponse;
use UtxoOne\LndPhp\Responses\Lightning\SendManyResponse;
use UtxoOne\LndPhp\Responses\Lightning\SendResponse;
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
     * @param ChannelPoint  $channelPoint
     * @param bool          $pendingFundingShimOnly
     * @param bool          $iKnowWhatIAmDoing
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
            $this->call(
                method: Endpoint::LIGHTNING_ABANDONCHANNEL->getMethod(),
                endpoint: Endpoint::LIGHTNING_ABANDONCHANNEL->getPath(),
                data: [
                    'channel_point' => $channelPoint->getFundingTxidStr(),
                    'pending_funding_shim_only' => $pendingFundingShimOnly,
                    'i_know_what_i_am_doing' => $iKnowWhatIAmDoing,
                ]
            );

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * AddInvoice
     *
     * AddInvoice attempts to add a new invoice to the invoice database.
     * Any duplicated invoices are rejected, therefore all invoices *must* have a unique payment preimage.
     *
     * @link https://api.lightning.community/#addinvoice
     *
     * @param string        $memo               Optional. An optional memo to attach along with the invoice.
     *                                          Used for record keeping purposes for the invoice's creator,
     *                                          and will also be set in the description field of the encoded
     *                                          payment request if the description_hash field is not being used.
     *
     * @param string        $rPreimage          Optional. The hex-encoded preimage (32 byte) which will allow
     *                                          settling an incoming HTLC payable to this preimage.
     *                                          When using REST, this field must be encoded as base64.
     *
     * @param string        $rHash              Optional. The hash of the preimage. When using REST,
     *                                          this field must be encoded as base64.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param int           $value              Required. The value of this invoice in satoshis.
     *                                          The fields value and value_msat are mutually exclusive.
     *
     * @param int           $valueMsat          Required. The value of this invoice in millisatoshis.
     *                                          The fields value and value_msat are mutually exclusive.
     *
     * @param int           $creationDate       Optional. When this invoice was created.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param int           $settleDate         Optional. When this invoice was settled.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param int           $creationDate       Optional. When this invoice was created.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param string        $paymentRequest     Optional. A bare-bones invoice for a payment within the Lightning Network.
     *                                          With the details of the invoice, the sender has all the data necessary to
     *                                          send a payment to the recipient. Note: Output only, don't specify for creating an invoice.
     *
     * @param string        $descriptionHash    Optional. Hash (SHA-256) of a description of the payment.
     *                                          Used if the description of payment (memo) is too long to naturally
     *                                          fit within the description field of an encoded payment request.
     *                                          When using REST, this field must be encoded as base64.
     *
     * @param int           $expiry             Optional. Payment request expiry time in seconds. Default is 3600 (1 hour).
     *
     * @param string        $fallbackAddr       Optional. Fallback on-chain address.
     *
     * @param int           $cltvExpiry         Optional. Delta to use for the time-lock of the CLTV extended to the final hop.
     *
     * @param array         $routeHints         Optional. Route hints that can each be individually used to assist
     *                                          in reaching the invoice's destination.
     *
     * @param bool          $private            Optional. Whether this invoice should include routing hints for private channels.
     *
     * @param int           $addIndex           Optional. The "add" index of this invoice. Each newly created invoice will increment
     *                                          this index making it monotonically increasing. Callers to the SubscribeInvoices call
     *                                          can use this to instantly get notified of all added invoices with an add_index greater
     *                                          than this one. Note: Output only, don't specify for creating an invoice.
     *
     * @param int           $settleIndex        Optional. The "settle" index of this invoice. Each newly settled invoice will increment
     *                                          this index making it monotonically increasing. Callers to the SubscribeInvoices call
     *                                          can use this to instantly get notified of all settled invoices with an settle_index greater
     *                                          than this one. Note: Output only, don't specify for creating an invoice.
     *
     * @param int           $amtPaidSat         Optional. The amount that was accepted for this invoice, in satoshis.
     *                                          This will ONLY be set if this invoice has been settled.
     *                                          We provide this field as if the invoice was created with a zero value,
     *                                          then we need to record what amount was ultimately accepted.
     *                                          Additionally, it's possible that the sender paid MORE that
     *                                          was specified in the original invoice. So we'll record that here as well.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param int           $amtPaidMsat        Optional. The amount that was accepted for this invoice, in millisatoshis.
     *                                          This will ONLY be set if this invoice
     *                                          has been settled. We provide this field as if the invoice was created with a zero value,
     *                                          then we need to record what amount was ultimately accepted.
     *                                          Additionally, it's possible that the sender paid MORE that was specified in the original invoice.
     *                                          So we'll record that here as well. Note: Output only, don't specify for creating an invoice.
     *
     * @param InvoiceState  $state              Optional. The state the invoice is in.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param InvoiceHtlc[] $htlcs              Optional. List of HTLCs paying to this invoice.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param array         $features           Optional. List of features advertised on the invoice.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param bool          $isKeysend          Optional. Whether this invoice was a keysend invoice.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param string        $paymentAddr        Optional. The payment address of this invoice. This value will be used in MPP payments,
     *                                          and also for newer invoices that always require the MPP payload for added end-to-end security.
     *                                          Note: Output only, don't specify for creating an invoice.
     *
     * @param bool          $isAmp              Optional. Signals whether or not this is an AMP invoice.
     *
     * @param array         $ampInvoiceState    Optional. Maps a 32-byte hex-encoded set ID to the sub-invoice AMP state for the given set ID.
     *                                          This field is always populated for AMP invoices, and can be used along side LookupInvoice to obtain
     *                                          the HTLC information related to a given sub-invoice. Note: Output only, don't specify for creating an invoice.
     *
     * @return AddInvoiceResponse
     *
     * @throws Exception
     */
    public function addInvoice(
        ?string $rPreimage = null,
        ?string $rHash = null,
        ?int $value = null,
        ?int $valueMsat = null,
        ?int $creationDate = null,
        ?int $settleDate = null,
        ?string $paymentRequest = null,
        ?string $descriptionHash = null,
        ?int $expiry = null,
        ?string $fallbackAddr = null,
        ?int $cltvExpiry = null,
        ?array $routeHints = null,
        ?bool $private = null,
        ?int $addIndex = null,
        ?int $settleIndex = null,
        ?int $amtPaidSat = null,
        ?int $amtPaidMsat = null,
        ?InvoiceState $state = null,
        ?array $htlcs = null,
        ?array $features = null,
        ?bool $isKeysend = null,
        ?string $paymentAddr = null,
        ?bool $isAmp = null,
        ?array $ampInvoiceState = null,
        ?string $memo = null,
    ): AddInvoiceResponse {
        try {
            return new AddInvoiceResponse($this->call(
                method: Endpoint::LIGHTNING_ADDINVOICE->getMethod(),
                endpoint: Endpoint::LIGHTNING_ADDINVOICE->getPath(),
                data: [
                    'r_preimage' => $rPreimage,
                    'r_hash' => $rHash,
                    'value' => $value,
                    'value_msat' => $valueMsat,
                    'creation_date' => $creationDate,
                    'settle_date' => $settleDate,
                    'payment_request' => $paymentRequest,
                    'description_hash' => $descriptionHash,
                    'expiry' => $expiry,
                    'fallback_addr' => $fallbackAddr,
                    'cltv_expiry' => $cltvExpiry,
                    'route_hints' => $routeHints,
                    'private' => $private,
                    'add_index' => $addIndex,
                    'settle_index' => $settleIndex,
                    'amt_paid_sat' => $amtPaidSat,
                    'amt_paid_msat' => $amtPaidMsat,
                    'state' => $state,
                    'htlcs' => $htlcs,
                    'features' => $features,
                    'is_keysend' => $isKeysend,
                    'payment_addr' => $paymentAddr,
                    'is_amp' => $isAmp,
                    'amp_invoice_state' => $ampInvoiceState,
                    'memo' => $memo,
                ]
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * BakeMacaroon
     *
     * BakeMacaroon allows the creation of a new macaroon with custom read and write permissions. Endpoint
     * No first-party caveats are added since this can be done offline.
     *
     * @link https://api.lightning.community/#bakeMacaroon
     *
     * @param MacaroonPermission[]      $permissions                Required. The list of permissions the new macaroon should grant.
     * @param string                    $rootKeyId                  Required. The root key ID used to create the macaroon, must be a positive integer.
     * @param bool                      $allowExternalPermissions   Required. Informs the RPC on whether to allow external permissions that LND is not aware of.
     *
     * @return BakeMacaroonResponse
     *
     * @throws Exception
     */
    public function bakeMacaroon(
        array $permissions,
        string $rootKeyId,
        bool $allowExternalPermissions = false,
    ): BakeMacaroonResponse {
        try {
            return new BakeMacaroonResponse($this->call(
                method: Endpoint::LIGHTNING_BAKEMACAROON->getMethod(),
                endpoint: Endpoint::LIGHTNING_BAKEMACAROON->getPath(),
                data: [
                    'permissions' => $permissions,
                    'root_key_id' => $rootKeyId,
                    'allow_external_permissions' => $allowExternalPermissions,
                ]
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * BatchOpenChannel
     *
     * BatchOpenChannel attempts to open multiple single-funded channels in a single transaction in an atomic way.
     * This means either all channel open requests succeed at once or all attempts are aborted if any of them fail.
     * This is the safer variant of using PSBTs to manually fund a batch of channels through the OpenChannel RPC.
     *
     * @param BatchOpenChannel[]   $channels            Required. The list of channel open requests.
     * @param int                  $targetConf          Required. The number of blocks that the funding transaction should be confirmed by.
     * @param int                  $satPerVbyte         Required. A manual fee rate set in sat/vbyte that should be used when crafting the funding transaction.
     * @param int                  $minConfs            Required. The minimum number of confirmations each of the channels should have before they are marked open.
     * @param bool                 $spendUnconfirmed    Required. Whether unconfirmed outputs should be used when attempting to fund the funding transaction.
     * @param string               $label               Optional. An optional label for the batch transaction, limited to 500 characters.
     *
     * @return BatchOpenChannelResponse
     *
     * @throws Exception
     */
    public function batchOpenChannel(
        array $channels,
        int $targetConf,
        int $satPerVbyte,
        int $minConfs,
        bool $spendUnconfirmed,
        ?string $label,
    ): BatchOpenChannelResponse {
        try {
            return new BatchOpenChannelResponse($this->call(
                method: Endpoint::LIGHTNING_BATCHOPENCHANNEL->getMethod(),
                endpoint: Endpoint::LIGHTNING_BATCHOPENCHANNEL->getPath(),
                data: [
                    'channels' => $channels,
                    'target_conf' => $targetConf,
                    'sat_per_vbyte' => $satPerVbyte,
                    'min_confs' => $minConfs,
                    'spend_unconfirmed' => $spendUnconfirmed,
                    'label' => $label,
                ]
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * ChannelAcceptor
     *
     * ChannelAcceptor is a bidirectional streaming RPC for allowing a node to accept inbound channels to its own peers.
     * This allows callers to specify their own criteria for accepting inbound channels, such as price, channel size,
     * or time of day. Each peer can have its own set of criteria, and the criteria can change over time.
     * This allows node operators to dynamically accept inbound channels based on their current needs.
     *
     * @param bool                 $accept              Required. Whether or not the client accepts the channel.
     *
     * @param string               $pendingChanId       Required. The pending channel id to which this response applies.
     *
     * @param string               $error               Optional. An optional error to send the initiating party
     *                                                  to indicate why the channel was rejected. This field should not
     *                                                  contain sensitive information, it will be sent to the initiating party.
     *                                                  This field should only be set if accept is false, the channel
     *                                                  will be rejected if an error is set with accept=true because
     *                                                  the meaning of this response is ambiguous. Limited to 500 characters
     *
     * @param string               $upfrontShutdown     Optional. An optional upfront shutdown address to use for the channel.
     *
     * @param string               $csvDelay            Required. The csv delay (in blocks) that we require for the remote party.
     *
     * @param string               $reserveSat          Required. The reserve amount in satoshis that we require
     *                                                  the remote peer to adhere to. We require that the remote peer
     *                                                  always have some reserve amount allocated to them so that
     *                                                  there is always a disincentive to broadcast old state
     *                                                  (if they hold 0 sats on their side of the channel, there is nothing to lose).
     *
     * @param string               $inFlightMaxMsat     Required. The maximum amount of funds in millisatoshis that
     *                                                  we allow the remote peer to have in outstanding htlcs.
     *
     * @param int                  $maxHtlcCount        Required. The maximum number of htlcs that we allow the remote peer to have in flight.
     *
     * @param string               $minHtlcIn           Required. The minimum htlc amount in millisatoshis that we allow the remote peer to send to us.
     *
     * @param int                  $minAcceptDepth      Required. The minimum number of confirmations that we require for the funding transaction.
     *
     * @param bool                 $zeroConf            Required. The maximum amount of funds in millisatoshis that we allow
     *                                                  the remote peer to have pending in their channel reserve.
     *
     *
     * @return ChannelAcceptResponse
     *
     * @throws Exception
     */
    public function channelAcceptor(
        bool $accept,
        string $pendingChanId,
        string $error,
        string $upfrontShutdown,
        string $csvDelay,
        string $reserveSat,
        string $inFlightMaxMsat,
        int $maxHtlcCount,
        string $minHtlcIn,
        int $minAcceptDepth,
        bool $zeroConf,
    ): ChannelAcceptResponse {
        try {
            return new ChannelAcceptResponse($this->call(
                method: Endpoint::LIGHTNING_CHANNELACCEPTOR->getMethod(),
                endpoint: Endpoint::LIGHTNING_CHANNELACCEPTOR->getPath(),
                data: [
                    'accept' => $accept,
                    'pending_chan_id' => $pendingChanId,
                    'error' => $error,
                    'upfront_shutdown' => $upfrontShutdown,
                    'csv_delay' => $csvDelay,
                    'reserve_sat' => $reserveSat,
                    'in_flight_max_msat' => $inFlightMaxMsat,
                    'max_htlc_count' => $maxHtlcCount,
                    'min_htlc_in' => $minHtlcIn,
                    'min_accept_depth' => $minAcceptDepth,
                    'zero_conf' => $zeroConf,
                ]
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * ChannelBalance
     *
     * lncli: channelbalance ChannelBalance returns a report on the total funds across all open channels,
     * categorized in local/remote, pending local/remote and unsettled local/remote balances.
     *
     * @link https://api.lightning.community/#v1-balance-channels
     *
     * @return ChannelBalanceResponse
     */
    public function channelBalance(): ChannelBalanceResponse
    {
        try {
            return new ChannelBalanceResponse($this->call(
                method: Endpoint::LIGHTNING_CHANNELBALANCE->getMethod(),
                endpoint: Endpoint::LIGHTNING_CHANNELBALANCE->getPath(),
                data: null,
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * CheckMacaroonPermissions
     *
     * CheckMacaroonPermissions checks whether a request follows the constraints imposed
     * on the macaroon and that the macaroon is authorized to follow the provided permissions.
     *
     * @link https://api.lightning.community/#v1-macaroon-checkpermissions
     *
     * @param string                        $macaroon       Required. The macaroon to check.
     * @param MacaroonPermission[]          $permissions    Required. The permissions to check.
     * @param string                        $fullMethod     Required. The full method name of the RPC call.
     *
     * @return CheckMacPermResponse
     *
     * @throws Exception
     */
    public function checkMacaroonPermissions(
        string $macaroon,
        array $permissions,
        string $fullMethod
    ): CheckMacPermResponse {
        try {
            return new CheckMacPermResponse($this->call(
                method: Endpoint::LIGHTNING_CHECKMACPERM->getMethod(),
                endpoint: Endpoint::LIGHTNING_CHECKMACPERM->getPath(),
                data: [
                    'macaroon' => $macaroon,
                    'permissions' => $permissions,
                    'fullMethod' => $fullMethod,
                ]
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * CloseChannel
     *
     * CloseChannel attempts to close an active channel identified by its channel outpoint (ChannelPoint).
     * The actions of this method can additionally be augmented to attempt a force close after a timeout
     * period in the case of an inactive peer. If a non-force close (cooperative closure) is requested,
     * then the user can specify either a target number of blocks until the closure transaction is confirmed,
     * or a manual fee rate. If neither are specified, then a default lax, block confirmation target is used.
     *
     * @link https://api.lightning.community/#v1-channels
     *
     * @param ChannelPoint      $channelPoint       Required. The ChannelPoint of the channel to close.
     * @param boolean           $force              Required. If true, then the channel will be closed forcefully.
     * @param int               $targetConf         Required. The number of blocks that the closure transaction should be confirmed by.
     * @param string            $satPerVbyte        Required. A manual fee rate set in sat/byte that should be used when crafting the closure transaction.
     * @param string            $deliveryAddress    Required. The address to send any funds remaining within the channel to.
     * @param string            $maxFeePerVbyte     Required. If true, then the closure transaction will be marked non-broadcastable.
     *
     * @return CloseChannelResponse
     *
     * @throws Exception
     */
    public function closeChannel(
        ChannelPoint $channelPoint,
        bool $force,
        int $targetConf,
        string $satPerVbyte,
        string $deliveryAddress,
        string $maxFeePerVbyte
    ): CloseChannelResponse {
        try {
            $query = http_build_query([
                'force' => $force,
                'target_conf' => $targetConf,
                'sat_per_vbyte' => $satPerVbyte,
                'delivery_address' => $deliveryAddress,
                'max_fee_per_vbyte' => $maxFeePerVbyte,
            ]);

            $endpoint = Endpoint::LIGHTNING_CLOSECHANNEL->getPath() .
                '/' . $channelPoint->getFundingTxidStr() . '/' . $channelPoint->getOutputIndex() .
                '?' . $query;

            return new CloseChannelResponse($this->call(
                method: Endpoint::LIGHTNING_CLOSECHANNEL->getMethod(),
                endpoint: $endpoint,
                data: [
                    'channel_point' => $channelPoint,
                    'force' => $force,
                    'target_conf' => $targetConf,
                    'sat_per_vbyte' => $satPerVbyte,
                    'delivery_address' => $deliveryAddress,
                    'max_fee_per_vbyte' => $maxFeePerVbyte,
                ]
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * ClosedChannels
     *
     * ClosedChannels returns a description of all the closed channels that this node was a participant in.
     *
     * @link https://api.lightning.community/#v1-channels-closed
     *
     * @param boolean   $cooperative    Required. If true, then only cooperative close channels will be returned.
     * @param boolean   $localForce     Required. If true, then only channels that were force closed by the local node will be returned.
     * @param boolean   $remoteForce    Required. If true, then only channels that were force closed by the remote node will be returned.
     * @param boolean   $breach         Required. If true, then only channels that were breached by the remote node will be returned.
     * @param boolean   $fundingCanceled    Required. If true, then only channels that were canceled by the remote node will be returned.
     * @param boolean   $abandoned      Required. If true, then only channels that were abandoned by the local node will be returned.
     *
     * @return ChannelCloseSummaryList
     *
     * @throws Exception
     */
    public function closedChannels(
        bool $cooperative,
        bool $localForce,
        bool $remoteForce,
        bool $breach,
        bool $fundingCanceled,
        bool $abandoned
    ): ChannelCloseSummaryList {
        try {
            $query = http_build_query([
                'cooperative' => $cooperative,
                'local_force' => $localForce,
                'remote_force' => $remoteForce,
                'breach' => $breach,
                'funding_canceled' => $fundingCanceled,
                'abandoned' => $abandoned,
            ]);

            $endpoint = Endpoint::LIGHTNING_CLOSEDCHANNELS->getPath() . '?' . $query;

            return new ChannelCloseSummaryList($this->call(
                method: Endpoint::LIGHTNING_CLOSEDCHANNELS->getMethod(),
                endpoint: $endpoint,
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * LookupInvoice
     *
     * LookupInvoice attempts to look up an invoice according to its payment hash. The passed payment hash
     * *must* be exactly 32 bytes, if not, an error is returned.
     *
     * @link https://api.lightning.community/#v1-invoice
     *
     * @param string    $rHash   Required. The payment hash to look for, encoded as a hex string.
     *
     * @return Invoice
     *
     * @throws Exception
     */
    public function lookupInvoice(string $rHash): Invoice
    {
        $rHash = bin2hex(base64_decode($rHash));
        try {
            return new Invoice($this->call(
                method: Endpoint::LIGHTNING_LOOKUPINVOICE->getMethod(),
                endpoint: Endpoint::LIGHTNING_LOOKUPINVOICE->getPath() . '/' . $rHash,
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * SendCoins
     *
     * SendCoins executes a request to send coins to a particular address. Unlike SendMany, this RPC call only
     * allows creating a single output at a time. If neither target_conf or sat_per_byte are set, then a
     * lax, block confirmation target is used.
     *
     * @link https://api.lightning.community/#v1-transactions
     *
     * @param string       $addr                    Required. The address to send coins to.
     * @param string       $amount                  Required. The amount to send expressed in satoshis.
     * @param int          $targetConf              Required. The number of blocks that the transaction should be confirmed by.
     * @param string       $satPerVbyte             Required. A manual fee rate set in sat/byte that should be used when crafting the transaction.
     * @param bool         $sendAll                 Required. If true, then the entire wallet balance will be sent to the address.
     * @param string       $label                   Required. A label to assign to the transaction.
     * @param int          $minConfs                Required. The minimum number of confirmations each of the referenced outputs must have.
     * @param bool         $spendUnconfirmed        Required. Whether unconfirmed outputs should be used as inputs for the transaction.
     *
     * @return SendCoinsResponse
     *
     * @throws Exception
     */
    public function sendCoins(
        string $addr,
        string $amount,
        int $targetConf = null,
        string $satPerVbyte = null,
        bool $sendAll = false,
        int $minConfs = null,
        bool $spendUnconfirmed = false,
        string $label = null,
    ): SendCoinsResponse {
        try {
            return new SendCoinsResponse($this->call(
                method: Endpoint::LIGHTNING_SENDCOINS->getMethod(),
                endpoint: Endpoint::LIGHTNING_SENDCOINS->getPath(),
                data: [
                    'addr' => $addr,
                    'amount' => $amount,
                    'target_conf' => $targetConf,
                    'sat_per_byte' => $satPerVbyte,
                    'send_all' => $sendAll,
                    'label' => $label,
                    'min_confs' => $minConfs,
                    'spend_unconfirmed' => $spendUnconfirmed,
                ]
            ));
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
            return new NodeInfo($this->call(
                method: Endpoint::LIGHTNING_GETINFO->getMethod(),
                endpoint: Endpoint::LIGHTNING_GETINFO->getPath(),
                data: null,
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * SendMany
     *
     * SendMany handles a request for a transaction that creates multiple
     * specified outputs in parallel. If neither target_conf,
     * or sat_per_vbyte are set, then the internal wallet will consult its
     * fee model to determine a fee for the default confirmation target.
     *
     * @link https://lightning.engineering/api-docs/api/lnd/lightning/send-many
     *
     * @param AddrToAmountEntryList       $addrToAmountEntries     Required. A list of outputs to create, with the format: {address: amount}.
     * @param int                        $targetConf              Required. The number of blocks that the transaction should be confirmed by.
     * @param string                     $satPerVbyte             Required. A manual fee rate set in sat/byte that should be used when crafting the transaction.
     * @param int                        $minConfs                Required. The minimum number of confirmations each of the referenced outputs must have.
     * @param bool                       $spendUnconfirmed        Required. Whether unconfirmed outputs should be used as inputs for the transaction.
     * @param string                     $label                   Required. A label to assign to the transaction.
     * @param int                        $satPerByte              Required. A manual fee rate set in sat/byte that should be used when crafting the transaction.
     */
    public function sendMany(
        AddrToAmountEntryList $addrToAmountEntryList,
        int $targetConf = null,
        string $satPerVbyte = null,
        int $minConfs = null,
        bool $spendUnconfirmed = false,
        string $label = null,
        int $satPerByte = null,
    ): SendManyResponse {
        try {
            return new SendManyResponse($this->call(
                method: Endpoint::LIGHTNING_SENDMANY->getMethod(),
                endpoint: Endpoint::LIGHTNING_SENDMANY->getPath(),
                data: [
                    'AddrToAmount' => $addrToAmountEntryList->toArray(),
                    'target_conf' => $targetConf,
                    'sat_per_vbyte' => $satPerVbyte,
                    'min_confs' => $minConfs,
                    'spend_unconfirmed' => $spendUnconfirmed,
                    'label' => $label,
                    'sat_per_byte' => $satPerByte,
                ],
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * SendPaymentSync
     *
     * SendPaymentSync is the synchronous non-streaming version of SendPayment.
     * This RPC is intended to be consumed by clients of the REST proxy.
     * Additionally, this RPC expects the destination's public key
     * and the payment hash (if any) to be encoded as hex strings.
     *
     * @link https://lightning.engineering/api-docs/api/lnd/lightning/send-payment-sync
     *
     * @param string            $dest                  The identity pubkey of the payment recipient. When using REST, this field must be encoded as base64.
     * @param string            $dest_string           The hex-encoded identity pubkey of the payment recipient.
     *                                                 Deprecated now that the REST gateway supports base64 encoding of bytes fields.
     * @param int               $amt                   The amount to send expressed in satoshis. The fields amt and amt_msat are mutually exclusive.
     * @param int               $amt_msat              The amount to send expressed in millisatoshis. The fields amt and amt_msat are mutually exclusive.
     * @param string            $payment_hash          The hex-encoded payment hash to use for the HTLC.
     * @param string            $payment_hash_string   The payment hash to use for the HTLC. Deprecated now that the REST gateway supports base64 encoding of bytes fields.
     * @param string            $payment_request       A bare-bones invoice for a payment within the Lightning Network.
     *                                                 With the details of the invoice, the sender has all the data necessary to send a payment to the recipient.
     * @param int               $final_cltv_delta      The final delta to use for the last hop. If not specified, the default final delta of 40 blocks is used.
     * @param FeeLimit          $fee_limit             The maximum fee allowed in satoshis when sending the payment.
     * @param int               $outgoing_chan_id      The outgoing channel id to use when routing the payment.
     * @param string            $last_hop_pubkey       The identity pubkey of the last hop. Deprecated now that the REST gateway supports base64 encoding of bytes fields.
     * @param int               $cltv_limit            The maximum number of blocks the payment can be routed through.
     * @param array             $dest_custom_records   A map from string to bytes that will be included in the final hop's payload for the destination.
     * @param bool              $allow_self_payment    If true, then we allow payments to ourselves.
     * @param FeatureBit[]      $dest_features         A list of feature bits that will be used to determine if the destination supports this payment.
     * @param string            $payment_addr          The hex-encoded payment address to use for the HTLC.
     *                                                 Deprecated now that the REST gateway supports base64 encoding of bytes fields.
     * @return SendResponse
     */
    public function sendPaymentSync(
        string $paymentRequest,
        ?string $dest = null,
        ?string $destString = null,
        ?int $amt = null,
        ?int $amt_msat = null,
        ?string $paymentHash = null,
        ?string $paymentHashString = null,
        ?int $finalCltvDelta = null,
        ?FeeLimit $feeLimit = null,
        ?int $outgoingChanId = null,
        ?string $lastHopPubkey = null,
        ?int $cltvLimit = null,
        ?array $destCustomRecords = null,
        ?bool $allowSelfPayment = null,
        ?array $destFeatures = null,
        ?string $paymentAddr = null,
    ): SendResponse {
        try {
            return new SendResponse($this->call(
                method: Endpoint::LIGHTNING_SENDPAYMENTSYNC->getMethod(),
                endpoint: Endpoint::LIGHTNING_SENDPAYMENTSYNC->getPath(),
                data: [
                    'dest' => $dest,
                    'dest_string' => $destString,
                    'amt' => $amt,
                    'amt_msat' => $amt_msat,
                    'payment_hash' => $paymentHash,
                    'payment_hash_string' => $paymentHashString,
                    'payment_request' => $paymentRequest,
                    'final_cltv_delta' => $finalCltvDelta,
                    'fee_limit' => $feeLimit,
                    'outgoing_chan_id' => $outgoingChanId,
                    'last_hop_pubkey' => $lastHopPubkey,
                    'cltv_limit' => $cltvLimit,
                    'dest_custom_records' => $destCustomRecords,
                    'allow_self_payment' => $allowSelfPayment,
                    'dest_features' => $destFeatures,
                    'payment_addr' => $paymentAddr,
                ],
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
