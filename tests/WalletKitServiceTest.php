<?php

use UtxoOne\LndPhp\Enums\WalletKit\AddressType;
use UtxoOne\LndPhp\Services\WalletKitService;
use UtxoOne\LndPhp\Tests\BaseTest;

final class WalletKitTest extends BaseTest
{
    private WalletKitService $walletKitService;

    public function setUp(): void
    {
        parent::setUp();

        $this->walletKitService = new WalletKitService(
            host: $this->host,
            port: $this->port,
            macaroonPath: $this->macaroonPath,
            tlsCertificatePath: $this->tlsCertificatePath,
            useSsl: true,
        );
    }

    /** @group nextAddr */
    public function testNextAddr(): void
    {
        $response = $this->walletKitService->nextAddr(
            type: AddressType::TAPROOT_PUBKEY,
        );

        $this->assertIsString($response->getAddr());
    }
}
