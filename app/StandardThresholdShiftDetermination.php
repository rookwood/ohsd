<?php

namespace App;

class StandardThresholdShiftDetermination
{
    const USE_AGE_ADJUSTMENT = 'use age adjustment';

    /**
     * Determine if standard threshold shift has occured
     *
     * @param Audiogram $baseline
     * @param Audiogram $current
     * @param bool      $adjustForAge
     * @return bool
     */
    public function test(Audiogram $baseline, Audiogram $current, $adjustForAge = false)
    {
        $baselineResponses = $this->extractRelevantResponses($baseline);

        $currentResponses = $this->extractRelevantResponses($current);

        $adjustments = $adjustForAge ?
            (new AgeRelatedThresholdAdjustment())($baseline->patient, $baseline, $current) :
            (new AgeRelatedThresholdAdjustment())->nullAdjustment();

        return $this->compareResponses($baselineResponses, $currentResponses, $adjustments);
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

    protected function compareResponses($baseline, $current, $adjustments)
    {
        $differences = $baseline->zip($current)->map(function ($responsePair) use ($adjustments) {
            return $responsePair[1]->amplitude - $responsePair[0]->amplitude - $adjustments[$responsePair[0]['frequency']];
        });

        return ($differences->sum() / 3) >= 10;
    }
}
