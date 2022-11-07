<?php

namespace UtxoOne\LndPhp\Responses\Lightning;

class CheckMacPermResponse
{
    public function __construct(private array $data)
    {
    }

    public function __invoke(): bool
    {
        return $this->data['valid'];
    }
}