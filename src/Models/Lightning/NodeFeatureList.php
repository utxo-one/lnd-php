<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class NodeFeatureList {
    public function __construct(private array $data)
    {
    }

    /** @return NodeFeature[] */
    public function __invoke(): array
    {
        $nodeFeatures = [];

        foreach ($this->data['features'] as $nodeFeature) {
            $nodeFeatures[] = new NodeFeature($nodeFeature);
        }

        return $nodeFeatures;
    }
}