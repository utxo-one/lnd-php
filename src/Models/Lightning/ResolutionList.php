<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class ResolutionList {
    public function __construct(private array $data)
    {
    }

    /** @return Resolution[] */
    public function __invoke(): array
    {
        $resolutions = [];

        foreach ($this->data['resolutions'] as $resolution) {
            $resolutions[] = new Resolution($resolution);
        }

        return $resolutions;
    }
}