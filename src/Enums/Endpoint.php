<?php

namespace UtxoOne\LndPhp\Enums;

enum Endpoint: string
{
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

    /**
     * Channel Acceptor
     *
     * lncli: channelacceptor ChannelAcceptor is a bidirectional stream between the server and the client in which OpenChannel
     * requests are sent to the client in order to determine whether the lnd node should accept or reject a channel open request.
     * The client responds with a boolean that tells lnd whether it should accept the request or not.
     *
     * @group Lightning
     *
     * @url https://api.lightning.community/#v1-channels-acceptor
     */
    case LIGHTNING_CHANNELACCEPTOR = 'POST v1/channels/acceptor';

    /**
     * Channel Balance
     *
     * lncli: channelbalance ChannelBalance returns a report on the total funds across all open channels,
     * categorized in local/remote, pending local/remote and unsettled local/remote balances.
     *
     * @group Lightning
     *
     * @url https://api.lightning.community/#v1-balance-channels
     */
    case LIGHTNING_CHANNELBALANCE = 'GET v1/balance/channels';

    /**
     * Get Info
     *
     * lncli: getinfo GetInfo returns general information concerning the lightning node including it's identity pubkey,
     * alias, the chains it is connected to, and information concerning the number of open+pending channels.
     *
     * @group Lightning
     *
     * @url https://api.lightning.community/#v1-getinfo
     */
    case LIGHTNING_GETINFO = 'GET v1/getinfo';

    /**
     * Check Macaroon Permissions
     *
     * CheckMacaroonPermissions checks whether a request follows the constraints imposed
     * on the macaroon and that the macaroon is authorized to follow the provided permissions.
     *
     * @group Lightning
     *
     * @url https://api.lightning.community/#v1-macaroon-checkpermissions
     */
    case LIGHTNING_CHECKMACPERM = 'POST v1/macaroon/checkpermissions';

    /**
     * Close Channel
     *
     * lncli: closechannel CloseChannel attempts to close an active channel identified by its channel outpoint (ChannelPoint).
     * The actions of this method can additionally be augmented to attempt a force close after a timeout period in the case of an inactive peer.
     * If a non-force close (cooperative closure) is requested, then the user can optionally specify either a target number of blocks until the closure transaction is confirmed (the target_conf) or a manual fee rate to us for the closure transaction (sat_per_byte).
     *
     * @group Lightning
     *
     * @url https://api.lightning.community/#v1-channels
     */
    case LIGHTNING_CLOSECHANNEL = 'DELETE v1/channels';

    /**
     * Closed Channels
     *
     * lncli: closedchannels ClosedChannels returns a description of all the closed channels that this node was a participant in.
     *
     * @group Lightning
     *
     * @url https://api.lightning.community/#v1-channels-closed
     */
    case LIGHTNING_CLOSEDCHANNELS = 'GET v1/channels/closed';

    /**
     * Lookup Invoice
     *
     * lncli: lookupinvoice LookupInvoice attempts to look up an invoice according to its payment hash.
     *
     * @group Lightning
     *
     * @url https://api.lightning.community/#v1-invoice
     */
    case LIGHTNING_LOOKUPINVOICE = 'GET v1/invoice';

    /**
     * Send Coins
     *
     * lncli: sendcoins SendCoins executes a request to send coins to a particular address.
     * Unlike SendMany, this RPC call only allows creating a single output at a time.
     * If neither target_conf, or sat_per_vbyte are set, then the internal wallet will
     * consult its fee model to determine a fee for the default confirmation target.
     *
     * @group Lightning
     *
     * @url https://api.lightning.community/#v1-transactions
     */
    case LIGHTNING_SENDCOINS = 'POST v1/transactions';

    /**
     * Next Addr
     *
     * NextAddr returns the next unused address within the wallet.
     *
     * @group WalletKit
     *
     * @url https://api.lightning.community/#v2-wallet-address-next
     */
    case WALLETKIT_NEXTADDR = 'POST v2/wallet/address/next';

    /**
     * List Addresses
     *
     * ListAddresses retrieves all the addresses along with their balance.
     * An account name filter can be provided to filter through all of the
     * wallet accounts and return the addresses of only those matching.
     *
     * @group WalletKit
     *
     * @url https://api.lightning.community/#v2-wallet-addresses
     */
    case WALLETKIT_LISTADDRESSES = 'POST v2/wallet/addresses';

    /**
     * List Unspent
     *
     * ListUnspent returns a list of all utxos spendable by the wallet with
     * a number of confirmations between the specified minimum and maximum.
     * By default, all utxos are listed. To list only the unconfirmed utxos,
     * set the unconfirmed_only to true.
     *
     * @group WalletKit
     *
     * @url https://api.lightning.community/#v2-wallet-utxos
     */
    case WALLETKIT_LISTUNSPENT = 'POST v2/wallet/utxos';

    /**
     * Send Many
     *
     * SendMany handles a request for a transaction that creates multiple specified outputs in parallel.
     * This is distinct from SendCoins in that SendMany allows creating multiple outputs at once,
     * and includes support for batched fee estimation. If neither target_conf, or sat_per_vbyte are set,
     * then the internal wallet will consult its fee model to determine a fee for the default confirmation target.
     *
     * @group Lightning
     *
     * @url https://lightning.engineering/api-docs/api/lnd/lightning/send-many
     */
    case LIGHTNING_SENDMANY = 'POST v1/transactions/many';

    /**
     * Send Payment Sync
     *
     * lncli: sendpayment SendPayment dispatches a bi-directional streaming RPC for sending payments through the Lightning Network.
     * A single RPC invocation creates a persistent bi-directional stream allowing clients to rapidly send payments through the Lightning Network with a single persistent connection.
     * This method differs from SendToRoute in that it allows the destination to specify the entire route, and the amount to send along each hop of the route.
     * This can be used for things like rebalancing, and atomic swaps.
     *
     * @group Lightning
     *
     * @url https://lightning.engineering/api-docs/api/lnd/lightning/send-payment-sync/index.html#lnrpchop
     */
    case LIGHTNING_SENDPAYMENTSYNC = 'POST v1/channels/transactions';

    public function getMethod(): string
    {
        return explode(' ', $this->value)[0];
    }

    public function getPath(): string
    {
        return explode(' ', $this->value)[1];
    }
}
