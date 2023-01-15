<?php

namespace App\Utils;

use DateTimeImmutable;

trait FakerTrait
{

    /**
     * Get random items of an array
     *
     * @param array $array
     * @param int|null $num
     * 
     * @return array
     * 
     */
    public function randomElements(array $array, ?int $num = 1): array
    {
        $count = count($array);
        shuffle($array);
        $array = array_slice($array, 0, $num, true);

        return $array;
    }

    /**
     * Get a random item of an array
     *
     * @param array $array
     * @param int|null $num
     * 
     * @return mixed|array|string|bool|null
     * 
     */
    public function randomElement(array $array): mixed
    {
        if (count($array) > 0) {
            $count = count($array) - 1;

            $element = count($array) > 1 ? $array[random_int(0, $count)] : $array[0];

            return $element;
        }

        return $array[0];
    }

    /**
     * @param DateTimeImmutable $originalDateTime
     *
     * @return bool|DateTimeImmutable
     */
    public function setDateTimeBetween(string $startDate = '-30 years', string $endDate = 'now', string $timezone = null): bool | DateTimeImmutable
    {
        $timezone = $timezone ?? date_default_timezone_get();
        $start = new DateTimeImmutable($startDate);
        $end = new DateTimeImmutable($endDate);

        $interval = $end->diff($start);
        $days = 0;

        if ($interval->y > 0) {
            $days += ($interval->y * 365);
        }
        if ($interval->m > 0) {
            $days += ($interval->m * 30);
        }
        if ($interval->d > 0) {
            $days += $interval->d;
        }

        return $start->modify('+' . random_int(0, $days) . ' days');
    }

    /**
     * @param DateTimeImmutable $originalDateTime
     *
     * @return bool|DateTimeImmutable
     */
    public function setDateTimeAfter(DateTimeImmutable $originalDateTime): bool | DateTimeImmutable
    {
        $now = new DateTimeImmutable();
        $interval = $now->diff($originalDateTime);
        $days = 0;

        if ($interval->y > 0) {
            $days += ($interval->y * 365);
        }
        if ($interval->m > 0) {
            $days += ($interval->m * 30);
        }
        if ($interval->d > 0) {
            $days += $interval->d;
        }

        return $originalDateTime->modify('+' . random_int(0, $days) . ' days');
    }
}
