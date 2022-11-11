<?php

namespace UtxoOne\LndPhp\Models\Lightning;

use UtxoOne\LndPhp\Enums\Lightning\InvoiceState;

class Invoice
{
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get Memo
     * 
     * An optional memo to attach along with the invoice. 
     * Used for record keeping purposes for the invoice's creator, 
     * and will also be set in the description field of the encoded 
     * payment request if the description_hash field is not being used.
     * 
     * @return string
     */
    public function getMemo(): string
    {
        return $this->data['memo'];
    }

    /**
     * Get R Preimage
     * 
     * The preimage (32 byte) which will allow settling an incoming HTLC payable to this preimage. 
     * When this is not set, a random preimage will be generated. 
     * This can be used to manually specify a preimage of a known invoice. 
     * This is useful when there are multiple pieces of business logic that 
     * might attempt to settle an invoice. 
     * Only the preimage that was specified in the original invoice can settle the invoice.
     * 
     * @return string
     */
    public function getRPreimage(): string
    {
        return $this->data['r_preimage'];
    }

    /**
     * Get R Hash
     * 
     * The hash (32 byte) to be used within the payment hash of the HTLC. 
     * When this is not set, a random preimage will be generated and the hash derived from that. 
     * This can be used to manually specify a payment hash of a known invoice. 
     * This is useful when there are multiple pieces of business logic that might attempt to settle an invoice. 
     * Only the preimage that was specified in the original invoice can settle the invoice.
     * 
     * @return string
     */
    public function getRHash(): string
    {
        return $this->data['r_hash'];
    }

    /**
     * Get Value
     * 
     * The value of this invoice in satoshis.
     * 
     * @return int
     */
    public function getValue(): int
    {
        return $this->data['value'];
    }

    /**
     * Get Value Msat
     * 
     * The value of this invoice in millisatoshis.
     * 
     * @return string
     */
    public function getValueMsat(): string
    {
        return $this->data['value_msat'];
    }

    /**
     * Get Settled
     * 
     * Whether this invoice has been settled. 
     * With the addition of the htlcs field, this field is deprecated and will be removed in the next major version of lnd.
     * 
     * @return bool
     */
    public function isSettled(): bool
    {
        return $this->data['settled'];
    }

    /**
     * Get Creation Date
     * 
     * The date this invoice was created.
     * 
     * @return int
     */
    public function getCreationDate(): int
    {
        return $this->data['creation_date'];
    }

    /**
     * Get Settle Date
     * 
     * The date this invoice was settled.
     * 
     * @return int
     */
    public function getSettleDate(): int
    {
        return $this->data['settle_date'];
    }

    /**
     * Get Payment Request
     * 
     * The hex-encoded payment request for this invoice. 
     * This field will only be populated if the invoice was created with the AddInvoice rpc or 
     * was decoded from an encoded payment request.
     * 
     * @return string
     */
    public function getPaymentRequest(): string
    {
        return $this->data['payment_request'];
    }

    /**
     * Get Description Hash
     * 
     * The hash of the description of the payment. 
     * Used if the description of payment (memo) is too long 
     * to naturally fit within the description field of an encoded payment request.
     * 
     * @return string
     */
    public function getDescriptionHash(): string
    {
        return $this->data['description_hash'];
    }

    /**
     * Get Expiry
     * 
     * The number of seconds after creation of the invoice that this invoice will expire. 
     * Default is 3600 (1 hour) or, if the invoice has a value of 0, then default is 86400 (1 day). 
     * Zero means no expiry.
     * 
     * @return int
     */
    public function getExpiry(): int
    {
        return $this->data['expiry'];
    }

    /**
     * Get Fallback Addr
     * 
     * Fallback on-chain address.
     * 
     * @return string
     */
    public function getFallbackAddr(): string
    {
        return $this->data['fallback_addr'];
    }

    /**
     * Get Cltv Expiry
     * 
     * Delta to use for the time-lock of the CLTV extended to the final hop.
     * 
     * @return string
     */
    public function getCltvExpiry(): string
    {
        return $this->data['cltv_expiry'];
    }

    /**
     * Get Route Hints
     * 
     * Route hints that can each be individually used to assist in reaching the invoice's destination.
     * 
     * @return array
     */
    public function getRouteHints(): array
    {
        return $this->data['route_hints'];
    }

    /**
     * Get Private
     * 
     * Whether this invoice should include routing hints for private channels.
     * 
     * @return bool
     */
    public function getPrivate(): bool
    {
        return $this->data['private'];
    }

    /**
     * Get Add Index
     * 
     * The add_index of this invoice. Each newly created invoice 
     * will increment this index making it monotonically increasing. 
     * Callers to the SubscribeInvoices call can use this to instantly 
     * get notified of all added invoices with an add_index greater than this one.
     * 
     * @return int
     */
    public function getAddIndex(): int
    {
        return $this->data['add_index'];
    }

    /**
     * Get Settle Index
     * 
     * The settle_index of this invoice. Each newly settled invoice will 
     * increment this index making it monotonically increasing. 
     * Callers to the SubscribeInvoices call can use this to instantly get 
     * notified of all settled invoices with an settle_index greater than this one.
     * 
     * @return int
     */
    public function getSettleIndex(): int
    {
        return $this->data['settle_index'];
    }

    /**
     * Get Amount Paid
     * 
     * The amount that was accepted for this invoice, in satoshis. 
     * This will ONLY be set if this invoice has been settled. 
     * We provide this field as if the invoice was created with a zero 
     * value, then we need to record what amount was ultimately accepted. 
     * Additionally, it's possible that the sender paid MORE that was specified 
     * in the original invoice. So we'll record that here as well.
     * 
     * @return int
     */
    public function getAmtPaid(): int
    {
        return $this->data['amt_paid'];
    }

    /**
     * Get Amount Paid Msat
     * 
     * The amount that was accepted for this invoice, in millisatoshis. 
     * This will ONLY be set if this invoice has been settled. 
     * We provide this field as if the invoice was created with a zero 
     * value, then we need to record what amount was ultimately accepted. 
     * Additionally, it's possible that the sender paid MORE that was specified 
     * in the original invoice. So we'll record that here as well.
     * 
     * @return int
     */
    public function getAmtPaidMsat(): int
    {
        return $this->data['amt_paid_msat'];
    }

    /**
     * Get State
     * 
     * The state the invoice is in.
     * 
     * @return InvoiceState
     */
    public function getState(): InvoiceState
    {
        return InvoiceState::fromName($this->data['state']);
    }

    /**
     * Get Htlcs
     * 
     * List of HTLCs paying to this invoice.
     * 
     * @return InvoiceHtlcList
     */
    public function getHtlcs(): InvoiceHtlcList
    {
        return new InvoiceHtlcList($this->data['htlcs']);
    }

    /**
     * Get Features
     * 
     * Features is a map of feature vectors that indicate which optional 
     * features are known to be supported for this invoice. 
     * The key of the map is the hex-encoded feature bit. 
     * The value of the map is a feature vector which represents the specific 
     * feature bits that are required to be set for this feature to be considered active.
     * 
     * @return InvoiceFeaturesEntryList
     */
    public function getFeatures(): InvoiceFeaturesEntryList
    {
        return new InvoiceFeaturesEntryList($this->data['features']);
    }

    /**
     * Is Key Send
     * 
     * Whether this invoice was created using the keysend RPC.
     * 
     * @return bool
     */
    public function isKeySend(): bool
    {
        return $this->data['is_keysend'];
    }

    /**
     * Get Payment Addr
     * 
     * The payment address of this invoice. This value will be used in MPP payments, 
     * and also for newer invoices that always require the MPP payload for added end-to-end security. 
     * Note: Output only, don't specify for creating an invoice.
     * 
     * @return string
     */
    public function getPaymentAddr(): string
    {
        return $this->data['payment_addr'];
    }

    /**
     * Is Amp
     * 
     * 	Signals whether or not this is an AMP invoice.
     * 
     * @return bool
     */
    public function isAmp(): bool
    {
        return $this->data['is_amp'];
    }

    /**
     * Get Amp Invoice State
     * 
     * [EXPERIMENTAL]: Maps a 32-byte hex-encoded set ID to the sub-invoice AMP state for the given set ID. 
     * This field is always populated for AMP invoices, and can be used along side LookupInvoice to obtain 
     * the HTLC information related to a given sub-invoice. Note: Output only, don't specify for creating an invoice.
     * 
     * @return AmpInvoiceStateEntryList
     */
    public function getAmpInvoiceState(): AmpInvoiceStateEntryList
    {
        return new AmpInvoiceStateEntryList($this->data['amp_invoice_state']);
    }
}