<?php

namespace UtxoOne\LndPhp\Services;

use Exception;
use UtxoOne\LndPhp\Enums\Endpoint;
use UtxoOne\LndPhp\Enums\WalletKit\AddressType;
use UtxoOne\LndPhp\Responses\WalletKit\NextAddrResponse;

class WalletKitService extends Lnd
{
    public function nextAddr(
        string $account = null,
        AddressType $type = null,
        bool $change = false,
    ): NextAddrResponse {
        try {
            return new NextAddrResponse($this->call(
                method: Endpoint::WALLETKIT_NEXTADDR->getMethod(),
                endpoint: Endpoint::WALLETKIT_NEXTADDR->getPath(),
                data: [
                    'account' => $account,
                    'type' => $type->value,
                    'change' => $change,
                ]
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
