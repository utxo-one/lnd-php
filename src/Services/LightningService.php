<?php

namespace UtxoOne\LndPhp\Services;

use UtxoOne\LndPhp\Enums\Endpoint;
use Exception;
use UtxoOne\LndPhp\Enums\Lightning\InvoiceState;
use UtxoOne\LndPhp\Models\Lightning\ChannelPoint;
use UtxoOne\LndPhp\Models\Lightning\NodeInfo;
use UtxoOne\LndPhp\Responses\Lightning\AddInvoiceResponse;
use UtxoOne\LndPhp\Responses\Lightning\BakeMacaroonResponse;
use UtxoOne\LndPhp\Responses\Lightning\BatchOpenChannelResponse;
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
     * @param string        $rPreimage          Required. The hex-encoded preimage (32 byte) which will allow 
     *                                          settling an incoming HTLC payable to this preimage. 
     *                                          When using REST, this field must be encoded as base64.
     * 
     * @param string        $rHash              Required. The hash of the preimage. When using REST, 
     *                                          this field must be encoded as base64. 
     *                                          Note: Output only, don't specify for creating an invoice.
     * 
     * @param int           $value              Required. The value of this invoice in satoshis. 
     *                                          The fields value and value_msat are mutually exclusive.
     * 
     * @param int           $valueMsat          Required. The value of this invoice in millisatoshis. 
     *                                          The fields value and value_msat are mutually exclusive.
     * 
     * @param int           $creationDate       Required. When this invoice was created. 
     *                                          Note: Output only, don't specify for creating an invoice.
     * 
     * @param int           $settleDate         Required. When this invoice was settled. 
     *                                          Note: Output only, don't specify for creating an invoice.
     * 
     * @param int           $creationDate       Required. When this invoice was created. 
     *                                          Note: Output only, don't specify for creating an invoice.
     * 
     * @param string        $paymentRequest     Required. A bare-bones invoice for a payment within the Lightning Network. 
     *                                          With the details of the invoice, the sender has all the data necessary to 
     *                                          send a payment to the recipient. Note: Output only, don't specify for creating an invoice.
     * 
     * @param string        $descriptionHash    Required. Hash (SHA-256) of a description of the payment. 
     *                                          Used if the description of payment (memo) is too long to naturally 
     *                                          fit within the description field of an encoded payment request. 
     *                                          When using REST, this field must be encoded as base64.
     * 
     * @param int           $expiry             Required. Payment request expiry time in seconds. Default is 3600 (1 hour).
     * 
     * @param string        $fallbackAddr       Required. Fallback on-chain address.
     * 
     * @param int           $cltvExpiry         Required. Delta to use for the time-lock of the CLTV extended to the final hop.
     * 
     * @param array         $routeHints         Required. Route hints that can each be individually used to assist 
     *                                          in reaching the invoice's destination.
     * 
     * @param bool          $private            Required. Whether this invoice should include routing hints for private channels.
     * 
     * @param int           $addIndex           Required. The "add" index of this invoice. Each newly created invoice will increment 
     *                                          this index making it monotonically increasing. Callers to the SubscribeInvoices call 
     *                                          can use this to instantly get notified of all added invoices with an add_index greater 
     *                                          than this one. Note: Output only, don't specify for creating an invoice.
     * 
     * @param int           $settleIndex        Required. The "settle" index of this invoice. Each newly settled invoice will increment 
     *                                          this index making it monotonically increasing. Callers to the SubscribeInvoices call 
     *                                          can use this to instantly get notified of all settled invoices with an settle_index greater 
     *                                          than this one. Note: Output only, don't specify for creating an invoice.
     * 
     * @param int           $amtPaidSat         Required. The amount that was accepted for this invoice, in satoshis. This will ONLY be set if this invoice 
     *                                          has been settled. We provide this field as if the invoice was created with a zero value, 
     *                                          then we need to record what amount was ultimately accepted. 
     *                                          Additionally, it's possible that the sender paid MORE that was specified in the original invoice. 
     *                                          So we'll record that here as well. Note: Output only, don't specify for creating an invoice.
     * 
     * @param int           $amtPaidMsat        Required. The amount that was accepted for this invoice, in millisatoshis. This will ONLY be set if this invoice 
     *                                          has been settled. We provide this field as if the invoice was created with a zero value, 
     *                                          then we need to record what amount was ultimately accepted. 
     *                                          Additionally, it's possible that the sender paid MORE that was specified in the original invoice. 
     *                                          So we'll record that here as well. Note: Output only, don't specify for creating an invoice.
     * 
     * @param InvoiceState  $state              Required. The state the invoice is in. Note: Output only, don't specify for creating an invoice.
     * 
     * @param InvoiceHtlc[] $htlcs              Required. List of HTLCs paying to this invoice. Note: Output only, don't specify for creating an invoice.
     * 
     * @param array         $features           Required. List of features advertised on the invoice. Note: Output only, don't specify for creating an invoice.
     * 
     * @param bool          $isKeysend          Required. Whether this invoice was a keysend invoice. Note: Output only, don't specify for creating an invoice.
     * 
     * @param string        $paymentAddr        Required. The payment address of this invoice. This value will be used in MPP payments, 
     *                                          and also for newer invoices that always require the MPP payload for added end-to-end security. 
     *                                          Note: Output only, don't specify for creating an invoice.
     * 
     * @param bool          $isAmp              Required. Signals whether or not this is an AMP invoice.
     * 
     * @param array         $ampInvoiceState    Experimental. Maps a 32-byte hex-encoded set ID to the sub-invoice AMP state for the given set ID. 
     *                                          This field is always populated for AMP invoices, and can be used along side LookupInvoice to obtain 
     *                                          the HTLC information related to a given sub-invoice. Note: Output only, don't specify for creating an invoice.
     * 
     * @return AddInvoiceResponse
     * 
     * @throws Exception
     */
    public function addInvoice(
        string $receipt,
        string $rPreimage,
        string $rHash,
        int $value,
        int $valueMsat,
        int $creationDate,
        int $settleDate,
        string $paymentRequest,
        string $descriptionHash,
        int $expiry,
        string $fallbackAddr,
        int $cltvExpiry,
        array $routeHints,
        bool $private,
        int $addIndex,
        int $settleIndex,
        int $amtPaidSat,
        int $amtPaidMsat,
        InvoiceState $state,
        array $htlcs,
        array $features,
        bool $isKeysend,
        string $paymentAddr,
        bool $isAmp,
        array $ampInvoiceState = null,
        ?string $memo,
    ): AddInvoiceResponse {

        try {
            return new AddInvoiceResponse($this->call(
                method: Endpoint::LIGHTNING_ADDINVOICE->getMethod(),
                endpoint: Endpoint::LIGHTNING_ADDINVOICE->getPath(),
                data: [
                    'receipt' => $receipt,
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
