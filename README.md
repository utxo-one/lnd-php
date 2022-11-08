## PHP-LND (WORK IN PROGRESS)

A PHP SDK for the LND implementation of the Lightning Network

Note: Do not use! It is incomplete.


## Full IDE Autofill
![image](https://user-images.githubusercontent.com/111649294/200585201-22f52f04-71aa-4f4e-a0d1-125e2389b5a0.png)
![image](https://user-images.githubusercontent.com/111649294/200585329-f9f53ac2-22c6-4097-9c49-cfbbf3dc9db7.png)
![image](https://user-images.githubusercontent.com/111649294/200585618-2caa227e-ee5e-4e35-95c7-6bdb6a5e0ecc.png)
![image](https://user-images.githubusercontent.com/111649294/200585716-a95ee758-3aa0-4823-8c11-56a2ffec314a.png)

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






