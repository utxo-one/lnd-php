<?php

namespace UtxoOne\LndPhp\Models\Lightning;

class ChannelCloseSummaryList {
    public function __construct(private array $data)
    {
    }

    /** @return ChannelCloseSummary[] */
    public function __invoke(): array
    {
        $channelCloseSummaries = [];

        foreach ($this->data['chains'] as $channelCloseSummary) {
            $channelCloseSummaries[] = new ChannelCloseSummary($channelCloseSummary);
        }

        return $channelCloseSummaries;
    }
}