<?php

use UtxoOne\LndPhp\Tests\BaseTest;
use UtxoOne\LndPhp\Models\ChainList;
use UtxoOne\LndPhp\Models\NodeFeatureList;
use UtxoOne\LndPhp\Models\NodeInfo;
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
            apiVersion: 'v1',
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

    
}
