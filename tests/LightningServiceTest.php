<?php

use UtxoOne\LndPhp\Tests\BaseTest;
use UtxoOne\LndPhp\Models\Lightning\ChainList;
use UtxoOne\LndPhp\Models\Lightning\Amount;
use UtxoOne\LndPhp\Models\Lightning\MacaroonPermission;
use UtxoOne\LndPhp\Models\Lightning\NodeFeatureList;
use UtxoOne\LndPhp\Models\Lightning\NodeInfo;
use UtxoOne\LndPhp\Responses\Lightning\BakeMacaroonResponse;
use UtxoOne\LndPhp\Responses\Lightning\ChannelBalanceResponse;
use UtxoOne\LndPhp\Services\LightningService;

final class LightningServiceTest extends BaseTest
{

    private LightningService $lightningService;

    public function setUp(): void
    {
        parent::setUp();

        $this->lightningService = new LightningService(
            host: $this->host,
            port: $this->port,
            macaroonPath: $this->macaroonPath,
            tlsCertificatePath: $this->tlsCertificatePath,
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
        $this->markTestIncomplete('requires testnet');
        //$this->lightningService->addInvoice();
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

        // Create a macroon permission object
        $macaroonPermission = new MacaroonPermission([
            'entity' => 'info',
            'action' => 'read',
        ]);

        // Bake a macraoon with the permissions we want to check
        $macaroon = $this->lightningService->bakeMacaroon(
            permissions: [$macaroonPermission->data],
            rootKeyId: 23452352345234,
        );

        // Check the permissions
        $permissions = $this->lightningService->checkMacaroonPermissions(
            macaroon: $macaroon->getMacaroon(),
            permissions: [$macaroonPermission->data],
            fullMethod: 'lnrpc.Lightning/CheckMacaroonPermissions',
        );

        $this->dd($permissions);
    }
}
