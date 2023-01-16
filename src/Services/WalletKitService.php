<?php

namespace UtxoOne\LndPhp\Services;

use Exception;
use UtxoOne\LndPhp\Enums\Endpoint;
use UtxoOne\LndPhp\Enums\WalletKit\AddressType;
use UtxoOne\LndPhp\Responses\WalletKit\ListAddressesResponse;
use UtxoOne\LndPhp\Responses\WalletKit\ListUnspentResponse;
use UtxoOne\LndPhp\Responses\WalletKit\NextAddrResponse;

class WalletKitService extends Lnd
{
    public function nextAddr(
        AddressType $type,
        string $account = null,
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

    public function listAddresses(
        ?string $account = null,
        bool $showCustomAccounts = false,
    ): ListAddressesResponse {
        try {
            $query = http_build_query([
                'account' => $account,
                'show_custom_accounts' => $showCustomAccounts,
            ]);
            return new ListAddressesResponse($this->call(
                method: Endpoint::WALLETKIT_LISTADDRESSES->getMethod(),
                endpoint: Endpoint::WALLETKIT_LISTADDRESSES->getPath() . '?' . $query,
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function listUnspent(
        ?string $account = null,
        int $minConfs = 1,
        int $maxConfs = 9999999,
        bool $unconfirmedOnly = false,
    ): ListUnspentResponse {
        try {
            return new ListUnspentResponse($this->call(
                method: Endpoint::WALLETKIT_LISTUNSPENT->getMethod(),
                endpoint: Endpoint::WALLETKIT_LISTUNSPENT->getPath(),
                data: [
                    'account' => $account,
                    'min_confs' => $minConfs,
                    'max_confs' => $maxConfs,
                    'unconfirmed_only' => $unconfirmedOnly,
                ]
            ));
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
