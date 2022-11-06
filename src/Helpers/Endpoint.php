<?php

enum Endpoint: string {

    /**
     * List Aliases
     * 
     * lncli: listaliases ListAliases returns the set of all aliases that have ever existed 
     * with their confirmed SCID (if it exists) and/or the base SCID (in the case of zero conf).
     * 
     * @group Lightning
     * 
     * @url https://api.lightning.community/#v1-aliases-list
     */
    case LIGHTNING_LISTALIASES = 'GET v1/aliases/list';

    /**
     * List Channels
     * 
     * lncli: listchannels ListChannels returns a description of all the open channels that this node is a participant in.
     * 
     * @group Lightning
     * 
     * @url https://api.lightning.community/#v1-channels
     */
    case LIGHTNING_LISTCHANNELS = 'GET v1/channels';

    /**
     * Abandon Channel
     * 
     * lncli: abandonchannel AbandonChannel removes all channel state from the database except for a close summary. 
     * This method can be used to get rid of permanently unusable channels due to bugs fixed in newer versions of lnd. 
     * This method can also be used to remove externally funded channels where the funding transaction was never broadcast. 
     * Only available for non-externally funded channels in dev build.
     * 
     * @group Lightning
     * 
     * @url https://api.lightning.community/#v1-channels-abandon
     */
    case LIGHTNING_ABANDONCHANNEL = 'DELETE v1/channels/abandon';

    /**
     * Add Invoice
     * 
     * lncli: addinvoice AddInvoice attempts to add a new invoice to the invoice database. 
     * Any duplicated invoices are rejected, therefore all invoices must have a unique payment preimage.
     * 
     * @group Lightning
     * 
     * @url https://api.lightning.community/#v1-invoices
     */
    case LIGHTNING_ADDINVOICE = 'POST v1/invoices';

    /**
     * Bake Macaroon
     * 
     * lncli: bakemacaroon BakeMacaroon allows the creation of a new macaroon with custom read and write permissions. 
     * No first-party caveats are added since this can be done offline.
     * 
     * @group Lightning
     * 
     * @url https://api.lightning.community/#v1-macaroon
     */
    case LIGHTNING_BAKEMACAROON = 'POST v1/macaroon';

    /**
     * Batch Open Channel
     * 
     * lncli: batchopenchannel BatchOpenChannel attempts to open multiple single-funded channels in a single transaction in an atomic way. 
     * This means either all channel open requests succeed at once or all attempts are aborted if any of them fail. 
     * This is the safer variant of using PSBTs to manually fund a batch of channels through the OpenChannel RPC.
     * 
     * @group Lightning
     * 
     * @url https://api.lightning.community/#v1-channels-batch
     */
     case LIGHTNING_BATCHOPENCHANNEL = 'POST v1/channels/batch';

    public function getMethod(): string 
    {
        return explode(' ', $this->value)[0];
    }

    public function getPath(): string 
    {
        return explode(' ', $this->value)[1];
    }
}