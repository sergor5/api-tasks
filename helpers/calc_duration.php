<?php
/**
 * Name: Duration Calculator
 * Description: This function is used to calculate the duration between two dates.
 * @param string $startDate The start date
 * @param string $endDate The end date
 * @param string $durationUnit The unit of time to calculate the duration in (HOURS, DAYS, WEEKS)
 */
function calc_duration($startDate, $endDate, $durationUnit = 'DAYS')
{
    if (isset($startDate) && isset($endDate)) {
        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);
        $interval = $endDate->diff($startDate);

        switch ($durationUnit) {
            case 'HOURS':
                return $interval->d * 24 + $interval->h;
            case 'DAYS':
                return $interval->d;
            case 'WEEKS':
                return ceil($interval->d / 7);
        }
    } else {
        return NULL;
    }
}