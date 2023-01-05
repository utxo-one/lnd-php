<?php

use UtxoOne\LndPhp\Enums\WalletKit\AddressType;
use UtxoOne\LndPhp\Models\Lightning\OutPoint;
use UtxoOne\LndPhp\Models\Lightning\Utxo;
use UtxoOne\LndPhp\Models\Lightning\UtxoList;
use UtxoOne\LndPhp\Responses\WalletKit\ListUnspentResponse;
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
            macaroonHex: $this->macaroon,
            tlsCertificate: $this->tlsCertificate,
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

    /** @group listAddresses */
    public function testListAddresses(): void
    {
        $this->markTestSkipped('appears to be a bug in the rest lnd');
        $response = $this->walletKitService->listAddresses();

        $this->assertIsArray($response->getAccountWithAddresses());
    }

    /** @group listUnspent */
    public function testListUnspent(): void
    {
        $utxoListResponse = $this->walletKitService->listUnspent();

        $this->assertInstanceOf(ListUnspentResponse::class, $utxoListResponse);

        $utxoList = $utxoListResponse->getUtxos();

        foreach ($utxoList as $utxo) {
            $this->assertInstanceOf(Utxo::class, $utxo);
            $this->assertIsInt($utxo->getAmountSat());
            $this->assertIsString($utxo->getAddress());
            $this->assertIsString($utxo->getPkScript());
            $this->assertInstanceOf(AddressType::class, $utxo->getAddressType());
            $this->assertInstanceOf(OutPoint::class, $utxo->getOutpoint());
            $this->assertIsString($utxo->getConfirmations());
        }
    }
}
