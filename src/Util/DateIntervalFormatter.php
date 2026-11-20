<?php

namespace HBM\BasicsBundle\Util;

class DateIntervalFormatter
{
    public static function formatIso8601(\DateInterval $interval): string
    {
        $dateParts = [];

        foreach ([['y', 'Y'], ['m', 'M'], ['d', 'D']] as [$property, $unit]) {
            if ($interval->$property !== 0) {
                $dateParts[] = $interval->$property . $unit;
            }
        }

        $seconds      = $interval->s;
        $microseconds = (int) round($interval->f * 1_000_000);

        if ($microseconds >= 1_000_000) {
            ++$seconds;
            $microseconds -= 1_000_000;
        }

        $timeParts = [];

        foreach ([['h', 'H'], ['i', 'M']] as [$property, $unit]) {
            if ($interval->$property !== 0) {
                $timeParts[] = $interval->$property . $unit;
            }
        }

        if ($seconds !== 0 || $microseconds !== 0) {
            $secondValue = (string) $seconds;

            if ($microseconds !== 0) {
                $fraction = rtrim(str_pad((string) $microseconds, 6, '0', STR_PAD_LEFT), '0');
                $secondValue .= '.' . $fraction;
            }

            $timeParts[] = $secondValue . 'S';
        }

        if ($dateParts === [] && $timeParts === []) {
            return 'PT0S';
        }

        $duration = 'P' . implode('', $dateParts);

        if ($timeParts !== []) {
            $duration .= 'T' . implode('', $timeParts);
        }

        return ($interval->invert === 1 ? '-' : '') . $duration;
    }
}
