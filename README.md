## PHP-LND (WORK IN PROGRESS)

A PHP SDK for the LND implementation of the Lightning Network

Note: Do not use! It is incomplete.

### Usage

```php
use UtxoOne\LndPhp\Services\LightningService;

// Initialize an LND Instance.
$lightningService = new LightningService(
    host: $this->host,
    port: $this->port,
    macaroonPath: $this->macaroonPath,
    tlsCertificatePath: $this->tlsCertificatePath,
    apiVersion: 'v1',
    useSsl: true,
);

// Execute a command. ie getinfo
$nodeInfo = $lightningService->getInfo();

// Get the results
$nodeInfo->getVersion();
$nodeInfo->getCommitHash();
$nodeInfo->getIdentityPubkey();
$nodeInfo->getAlias();
$nodeInfo->getNumPendingChannels();
$nodeInfo->getNumActiveChannels();
$nodeInfo->getNumPeers();
$nodeInfo->getBlockHash();
$nodeInfo->getBlockHeight();
$nodeInfo->getUris();
$nodeInfo->getBestHeaderTimestamp();
$nodeInfo->isSyncedToChain();
$nodeInfo->isTestnet();
$nodeInfo->requiresHtlcInterceptor();
```



