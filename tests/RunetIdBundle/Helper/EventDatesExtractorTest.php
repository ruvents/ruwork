<?php

declare(strict_types=1);

namespace Ruwork\RunetIdBundle\Helper;

use RunetId\Client\Result\Event\InfoResult;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

/**
 * @internal
 */
class EventDatesExtractorTest extends TestCase
{
    /**
     * @dataProvider getData
     */
    public function test(array $data, array $dates, \DateTimeZone $timeZone = null): void
    {
        $event = new InfoResult($data);
        $actualDates = [];

        foreach (EventDatesExtractor::getDates($event, $timeZone) as $date) {
            $actualDates[] = $date->format('c');
        }

        $this->assertSame($dates, $actualDates);
    }

    public function getData(): \Generator
    {
        \date_default_timezone_set('Etc/GMT-5');

        yield [
            [
                'StartYear' => 2017,
                'StartMonth' => 12,
                'StartDay' => 30,
                'EndYear' => 2016,
                'EndMonth' => 12,
                'EndDay' => 30,
            ],
            [
                '2017-12-30T00:00:00+05:00',
            ],
        ];

        yield [
            [
                'StartYear' => 2017,
                'StartMonth' => 12,
                'StartDay' => 30,
                'EndYear' => 2017,
                'EndMonth' => 12,
                'EndDay' => 30,
            ],
            [
                '2017-12-30T00:00:00+06:00',
            ],
            new \DateTimeZone('Etc/GMT-6'),
        ];

        yield [
            [
                'StartYear' => 2017,
                'StartMonth' => 12,
                'StartDay' => 30,
                'EndYear' => 2018,
                'EndMonth' => 1,
                'EndDay' => 2,
            ],
            [
                '2017-12-30T00:00:00+06:00',
                '2017-12-31T00:00:00+06:00',
                '2018-01-01T00:00:00+06:00',
                '2018-01-02T00:00:00+06:00',
            ],
            new \DateTimeZone('Etc/GMT-6'),
        ];
    }
}
