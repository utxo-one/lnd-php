## PHP-LND (ALPHA RELEASE)

A PHP SDK for the LND implementation of the Lightning Network

### Installation

```
composer require utxo-one/lnd-php
```

### Usage

```php
use UtxoOne\LndPhp\Services\LightningService;

// Initialize an LND Instance.
$lightningService = new LightningService(
    host: $this->host,
    port: $this->port,
    macaroonHex: $this->macaroonHex,
    tlsCertificat: $this->tlsCertificate,
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

### Available Methods

```php
// Lightning Service
$lightningService->getInfo();
$lightningService->abandonChannel();
$lightningService->addInvoice();
$lightningService->bakeMacaroon();
$lightningService->batchOpenChannel();
$lightningService->channelAcceptor();
$lightningService->channelBalance();
$lightningService->checkMacaroonPermissions();
$lightningService->closeChannel();
$lightningService->closedChannels();
$lightningService->lookupInvoice();
$lightningService->sendCoins();
$lightningService->sendMany();
$lightningService->getInfo();

// WalletKit Service
$walletKitService->nextAddr();
$walletKitService->listAddresses();
$walletKitService->listUnspent();
```
