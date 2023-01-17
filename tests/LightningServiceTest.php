<?php

use UtxoOne\LndPhp\Enums\Lightning\ClosureType;
use UtxoOne\LndPhp\Enums\Lightning\Initiator;
use UtxoOne\LndPhp\Enums\Lightning\InvoiceState;
use UtxoOne\LndPhp\Enums\Lightning\ResolutionOutcome;
use UtxoOne\LndPhp\Enums\Lightning\ResolutionType;
use UtxoOne\LndPhp\Enums\WalletKit\AddressType;
use UtxoOne\LndPhp\Models\Lightning\AddrToAmountEntry;
use UtxoOne\LndPhp\Models\Lightning\AddrToAmountEntryList;
use UtxoOne\LndPhp\Tests\BaseTest;
use UtxoOne\LndPhp\Models\Lightning\ChainList;
use UtxoOne\LndPhp\Models\Lightning\Amount;
use UtxoOne\LndPhp\Models\Lightning\ChannelCloseSummary;
use UtxoOne\LndPhp\Models\Lightning\ChannelCloseSummaryList;
use UtxoOne\LndPhp\Models\Lightning\ChannelPoint;
use UtxoOne\LndPhp\Models\Lightning\HopList;
use UtxoOne\LndPhp\Models\Lightning\Invoice;
use UtxoOne\LndPhp\Models\Lightning\InvoiceFeaturesEntry;
use UtxoOne\LndPhp\Models\Lightning\InvoiceFeaturesEntryList;
use UtxoOne\LndPhp\Models\Lightning\InvoiceHtlcList;
use UtxoOne\LndPhp\Models\Lightning\MacaroonPermission;
use UtxoOne\LndPhp\Models\Lightning\NodeFeatureList;
use UtxoOne\LndPhp\Models\Lightning\NodeInfo;
use UtxoOne\LndPhp\Models\Lightning\OutPoint;
use UtxoOne\LndPhp\Models\Lightning\ResolutionList;
use UtxoOne\LndPhp\Models\Lightning\Route;
use UtxoOne\LndPhp\Responses\Lightning\AddInvoiceResponse;
use UtxoOne\LndPhp\Responses\Lightning\BakeMacaroonResponse;
use UtxoOne\LndPhp\Responses\Lightning\ChannelBalanceResponse;
use UtxoOne\LndPhp\Responses\Lightning\SendCoinsResponse;
use UtxoOne\LndPhp\Responses\Lightning\SendManyResponse;
use UtxoOne\LndPhp\Responses\Lightning\SendResponse;
use UtxoOne\LndPhp\Services\LightningService;
use UtxoOne\LndPhp\Services\WalletKitService;

final class LightningServiceTest extends BaseTest
{
    private LightningService $lightningService;
    private WalletKitService $walletKitService;

    public function setUp(): void
    {
        parent::setUp();

        $this->lightningService = new LightningService(
            host: $this->host,
            port: $this->port,
            macaroonHex: $this->macaroon,
            tlsCertificate: $this->tlsCertificate,
            useSsl: true,
        );

        $this->walletKitService = new WalletKitService(
            host: $this->host,
            port: $this->port,
            macaroonHex: $this->macaroon,
            tlsCertificate: $this->tlsCertificate,
            useSsl: true,
        );
    }

    public function testItCanGetNodeInfo(): void
    {
        $nodeInfo = $this->lightningService->getInfo();

        $this->assertInstanceOf(NodeInfo::class, $nodeInfo);

        $this->assertIsString($nodeInfo->getVersion());
        $this->assertIsString($nodeInfo->getCommitHash());
        $this->assertIsString($nodeInfo->getIdentityPubkey());
        $this->assertIsString($nodeInfo->getAlias());
        $this->assertIsInt($nodeInfo->getNumPendingChannels());
        $this->assertIsInt($nodeInfo->getNumActiveChannels());
        $this->assertIsInt($nodeInfo->getNumPeers());
        $this->assertIsString($nodeInfo->getBlockHash());
        $this->assertIsInt($nodeInfo->getBlockHeight());
        $this->assertIsArray($nodeInfo->getUris());
        $this->assertIsString($nodeInfo->getBestHeaderTimestamp());
        $this->assertIsBool($nodeInfo->isSyncedToChain());
        $this->assertIsBool($nodeInfo->isTestnet());
        $this->assertIsBool($nodeInfo->requiresHtlcInterceptor());

        $this->assertInstanceOf(ChainList::class, $nodeInfo->getChains());
        foreach ($nodeInfo->getChains() as $chain) {
            $this->assertIsString($chain->getChain());
            $this->assertIsString($chain->getNetwork());
        }

        $this->assertInstanceOf(NodeFeatureList::class, $nodeInfo->getFeatures());
        foreach ($nodeInfo->getFeatures() as $feature) {
            $this->assertIsString($feature->getName());
            $this->assertIsBool($feature->isRequired());
            $this->assertIsBool($feature->isKnown());
        }
    }

    public function testItCanAbandonChannel(): void
    {
        $this->markTestIncomplete('requires testnet');
        //$this->lightningService->abandonChannel();
    }

    public function testItCanAddInvoice(): void
    {
        $invoice = $this->lightningService->addInvoice(
            value: 1000,
            memo: 'test invoice',
            expiry: 36000,
        );

        $this->assertInstanceOf(AddInvoiceResponse::class, $invoice);
        $this->assertIsString($invoice->getRHash());
        $this->assertIsString($invoice->getPaymentRequest());
        $this->assertIsInt($invoice->getAddIndex());
        $this->assertIsString($invoice->getPaymentAddr());
    }

    /** @group lookupInvoice */
    public function testItCanLookupInvoice(): void
    {
        $invoice = $this->lightningService->addInvoice(
            value: 1000,
            memo: 'test invoice',
            expiry: 36000,
        );

        $invoice = $this->lightningService->lookupInvoice(
            rHash: $invoice->getRHash(),
        );

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertIsString($invoice->getMemo());
        $this->assertIsString($invoice->getRHash());
        $this->assertIsString($invoice->getRPreimage());
        $this->assertIsInt($invoice->getValue());
        $this->assertIsInt($invoice->getSettleDate());
        $this->assertIsInt($invoice->getCreationDate());
        $this->assertIsInt($invoice->getSettleIndex());
        $this->assertIsInt($invoice->getAddIndex());
        $this->assertIsString($invoice->getPaymentRequest());
        $this->assertIsInt($invoice->getExpiry());
        $this->assertIsInt($invoice->getAmtPaid());
        $this->assertSame(InvoiceState::OPEN, $invoice->getState());
        $this->assertIsString($invoice->getPaymentAddr());

        $this->assertInstanceOf(InvoiceHtlcList::class, $invoice->getHtlcs());
        foreach ($invoice->getHtlcs() as $htlc) {
            $this->assertIsString($htlc->getChanId());
            $this->assertIsInt($htlc->getHtlcIndex());
            $this->assertIsInt($htlc->getAmtMsat());
            $this->assertIsInt($htlc->getAcceptHeight());
            $this->assertIsInt($htlc->getAcceptTime());
            $this->assertIsInt($htlc->getResolveTime());
            $this->assertIsInt($htlc->getExpiryHeight());
            $this->assertIsInt($htlc->getState());
            $this->assertIsString($htlc->getCustomRecords());
        }

        $this->assertInstanceOf(InvoiceFeaturesEntryList::class, $invoice->getFeatures());
        foreach ($invoice->getFeatures() as $feature) {
            $this->assertIsString($feature->getName());
            $this->assertIsBool($feature->isRequired());
            $this->assertIsBool($feature->isKnown());
        }

        $this->assertIsBool($invoice->isKeysend());
        $this->assertIsBool($invoice->isAmp());
    }

    public function testItCanBatchOpenChannels(): void
    {
        $this->markTestIncomplete('requires testnet');
        //$this->lightningService->batchOpenChannels();
    }

    /** @group bakeMacaroon */
    public function testItCanBakeMacaroon(): void
    {
        $macaroonPermission = new MacaroonPermission([
            'entity' => 'info',
            'action' => 'read',
        ]);

        $macaroon = $this->lightningService->bakeMacaroon(
            permissions: [$macaroonPermission->data],
            rootKeyId: 23452352345234,
        );

        $this->assertInstanceOf(BakeMacaroonResponse::class, $macaroon);
        $this->assertIsString($macaroon->getMacaroon());
    }

    public function testItCanRespondToChannelAcceptor(): void
    {
        $this->markTestIncomplete('requires testnet');
        //$this->lightningService->responseToChannelAcceptor();
    }

    /** @group channelBalance */
    public function testItCanGetChannelBalance(): void
    {
        $channelBalance = $this->lightningService->channelBalance();

        $this->assertInstanceOf(ChannelBalanceResponse::class, $channelBalance);

        $this->assertIsString($channelBalance->getBalance());
        $this->assertIsString($channelBalance->getPendingOpenBalance());

        $this->assertInstanceOf(Amount::class, $channelBalance->getLocalBalance());
        $this->assertIsString($channelBalance->getLocalBalance()->getSat());
        $this->assertIsString($channelBalance->getLocalBalance()->getMsat());

        $this->assertInstanceOf(Amount::class, $channelBalance->getRemoteBalance());
        $this->assertIsString($channelBalance->getRemoteBalance()->getSat());
        $this->assertIsString($channelBalance->getRemoteBalance()->getMsat());

        $this->assertInstanceOf(Amount::class, $channelBalance->getPendingOpenLocalBalance());
        $this->assertIsString($channelBalance->getPendingOpenLocalBalance()->getSat());
        $this->assertIsString($channelBalance->getPendingOpenLocalBalance()->getMsat());

        $this->assertInstanceOf(Amount::class, $channelBalance->getPendingOpenRemoteBalance());
        $this->assertIsString($channelBalance->getPendingOpenRemoteBalance()->getSat());
        $this->assertIsString($channelBalance->getPendingOpenRemoteBalance()->getMsat());

        $this->assertInstanceOf(Amount::class, $channelBalance->getUnsettledLocalBalance());
        $this->assertIsString($channelBalance->getUnsettledLocalBalance()->getSat());
        $this->assertIsString($channelBalance->getUnsettledLocalBalance()->getMsat());

        $this->assertInstanceOf(Amount::class, $channelBalance->getUnsettledRemoteBalance());
        $this->assertIsString($channelBalance->getUnsettledRemoteBalance()->getSat());
        $this->assertIsString($channelBalance->getUnsettledRemoteBalance()->getMsat());
    }

    /** @group checkMacaroonPermissions */
    public function testItCanCheckMacaroonPermissions(): void
    {
        $this->markTestIncomplete('fails: cannot determine data format of binary-encoded macaroon');

        // Create 3 macaroon permission objects
        $macaroonPermission1 = new MacaroonPermission([
            'entity' => 'info',
            'action' => 'read',
        ]);

        $macaroonPermission2 = new MacaroonPermission([
            'entity' => 'offchain',
            'action' => 'read',
        ]);

        $macaroonPermission3 = new MacaroonPermission([
            'entity' => 'onchain',
            'action' => 'read',
        ]);

        $macaroonPermissions = [
            $macaroonPermission1->data,
            $macaroonPermission2->data,
            $macaroonPermission3->data,
        ];

        // Bake a macraoon with the permissions we want to check
        $macaroon = $this->lightningService->bakeMacaroon(
            permissions: $macaroonPermissions,
            rootKeyId: 23452352345234,
        );

        // Check the permissions
        $permissions = $this->lightningService->checkMacaroonPermissions(
            macaroon: $macaroon->getMacaroon(),
            permissions: $macaroonPermissions,
            fullMethod: 'lnrpc.Lightning/CheckMacaroonPermissions',
        );
    }

    /** @group closeChannel */
    public function testItCanCloseChannel(): void
    {
        $this->markTestIncomplete('requires testnet');

        // Create a ChannelPoint object
        $channelPoint = new ChannelPoint([
            'funding_txid_bytes' => 'fundingTxidBytes',
            'funding_txid_str' => 'fundingTxidStr',
            'output_index' => 0,
        ]);

        $close = $this->lightningService->closeChannel(
            channelPoint: $channelPoint,
            force: false,
            targetConf: 0,
            satPerVbyte: '1',
            deliveryAddress: 'deliveryAddress',
            maxFeePerVbyte: '1',
        );

        $this->dd($close);
    }

    /** @group closedChannels */
    public function testItCanGetClosedChannels(): void
    {
        //$this->markTestIncomplete('requires closed channels to work');

        $closedChannels = $this->lightningService->closedChannels(
            cooperative: true,
            localForce: true,
            remoteForce: true,
            breach: true,
            fundingCanceled: true,
            abandoned: true,
        );

        $this->assertInstanceOf(ChannelCloseSummaryList::class, $closedChannels);

        foreach ($closedChannels as $channel) {
            $this->assertInstanceOf(ChannelCloseSummary::class, $channel);
            $this->assertIsString($channel->getChannelPoint());
            $this->assertIsString($channel->getChanId());
            $this->assertIsString($channel->getChainHash());
            $this->assertIsString($channel->getClosingTxHash());
            $this->assertIsString($channel->getRemotePubkey());
            $this->assertIsInt($channel->getCapacity());
            $this->assertIsInt($channel->getCloseHeight());
            $this->assertIsInt($channel->getSettledBalance());
            $this->assertIsInt($channel->getTimeLockedBalance());
            $this->assertInstanceOf(ClosureType::class, $channel->getCloseType());
            $this->assertInstanceOf(Initiator::class, $channel->getOpenInitiator());
            $this->assertInstanceOf(Initiator::class, $channel->getCloseInitiator());

            $this->assertInstanceOf(ResolutionList::class, $channel->getResolutions());
            foreach ($channel->getResolutions() as $resolution) {
                $this->assertInstanceOf(Resolution::class, $resolution);
                $this->assertInstanceOf(ResolutionType::class, $resolution->getResolutionType());
                $this->assertInstanceOf(ResolutionOutcome::class, $resolution->getOutcome());
                $this->assertInstanceOf(OutPoint::class, $resolution->getOutpoint());
                $this->assertIsInt($resolution->getAmountSat());
                $this->assertIsString($resolution->getSweepTxid());
            }

            $this->assertIsArray($channel->getAliasScids());
            foreach ($channel->getAliasScids() as $aliasScid) {
                $this->assertIsInt($aliasScid);
            }

            $this->assertIsInt($channel->getZeroConfConfirmedScid());
        }
    }

    /** @group sendCoins */
    public function testItCanSendCoins(): void
    {
        $tmpAddress = 'tb1qxp6sq0ymwdd35yxcgje6yrqe2ls6wfnnyxnmgc';

        $transaction = $this->lightningService->sendCoins(
            addr: $tmpAddress,
            amount: '2000',
        );

        $this->assertInstanceOf(SendCoinsResponse::class, $transaction);
        $this->assertIsString($transaction->getTxid());
    }

    /** @group sendMany */
    public function testItCanSendMany(): void
    {
        $address1 = $this->walletKitService->nextAddr(type: AddressType::TAPROOT_PUBKEY, );
        $address2 = $this->walletKitService->nextAddr(type: AddressType::TAPROOT_PUBKEY, );

        $addrToAmount1 = new AddrToAmountEntry(
            addr: $address1->getAddr(),
            amount: '1000',
        );

        $addrToAmount2 = new AddrToAmountEntry(
            addr: $address2->getAddr(),
            amount: '1000',
        );

        $addrToAmount = new AddrToAmountEntryList(
            data: [
                $addrToAmount1,
                $addrToAmount2,
            ],
        );

        $transaction = $this->lightningService->sendMany(
            addrToAmountEntryList: $addrToAmount,
        );

        $this->assertInstanceOf(SendManyResponse::class, $transaction);

        $this->assertIsString($transaction->getTxid());
    }

    /** @group sendPaymentSync */
    public function testItCanSendPaymentSync(): void
    {
        $invoice = $this->lightningService->addInvoice(
            value: '1000',
            memo: 'test',
            expiry: 3600,
        );

        $payInvoice = $this->lightningService->sendPaymentSync(
            paymentRequest: $invoice->getPaymentRequest(),
            allowSelfPayment: true,
        );

        $this->assertInstanceOf(SendResponse::class, $payInvoice);

        $this->assertIsString($payInvoice->getPaymentError());
        $this->assertIsString($payInvoice->getPaymentPreimage());

        $this->assertInstanceOf(Route::class, $payInvoice->getPaymentRoute());
        $this->assertIsInt($payInvoice->getPaymentRoute()->getTotalTimeLock());
        $this->assertIsString($payInvoice->getPaymentRoute()->getTotalFees());
        $this->assertIsString($payInvoice->getPaymentRoute()->getTotalAmount());

        $this->assertInstanceOf(HopList::class, $payInvoice->getPaymentRoute()->getHops());
        foreach ($payInvoice->getPaymentRoute()->getHops() as $hop) {
            $this->assertInstanceOf(Hop::class, $hop);
            $this->assertIsString($hop->getChanId());
            $this->assertIsString($hop->getChanCapacity());
            $this->assertIsString($hop->getAmtToForward());
            $this->assertIsString($hop->getFee());
            $this->assertIsString($hop->getExpiry());
            $this->assertIsString($hop->getAmtToForwardMsat());
            $this->assertIsString($hop->getFeeMsat());
            $this->assertIsString($hop->getPubKey());
            $this->assertIsString($hop->getTlvPayload());
        }
    }
}
