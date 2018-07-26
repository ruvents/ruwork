<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Helper;

use RunetId\Client\Result\Event\InfoResult;

final class EventDatesExtractor
{
    /**
     * @return \DateTimeImmutable[]
     */
    public static function getDates(InfoResult $event, \DateTimeZone $timeZone = null): \Generator
    {
        $date = (new \DateTimeImmutable('now', $timeZone))
            ->setDate($event->StartYear, $event->StartMonth, $event->StartDay)
            ->setTime(0, 0, 0);

        $end = $date->setDate($event->EndYear, $event->EndMonth, $event->EndDay);

        $interval = new \DateInterval('P1D');

        do {
            yield $date;
        } while (($date = $date->add($interval)) <= $end);
    }
}
