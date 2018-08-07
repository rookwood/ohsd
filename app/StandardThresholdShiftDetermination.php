<?php

namespace App;

class StandardThresholdShiftDetermination
{
    /**
     * Determine if standard threshold shift has occured
     *
     * @param Audiogram $baseline
     * @param Audiogram $current
     * @return bool
     */
    public function test(Audiogram $baseline, Audiogram $current)
    {
        $baselineResponses = $this->extractRelevantResponses($baseline);

        $currentResponses = $this->extractRelevantResponses($current);

        return $this->compareResponses($baselineResponses, $currentResponses);
    }

    protected function extractRelevantResponses(Audiogram $audiogram)
    {
        return $audiogram->responses->filter(function ($response) {
            return $this->isRelevantFrequency($response->frequency);
        });
    }

    protected function isRelevantFrequency($frequency)
    {
        return in_array($frequency, [2000, 3000, 4000]);
    }

    protected function compareResponses($baseline, $current)
    {
        $differences = $baseline->zip($current)->map(function ($responsePair) {
            return $responsePair[1]->amplitude - $responsePair[0]->amplitude;
        });

        return ($differences->sum() / 3) > 10;
    }
}
