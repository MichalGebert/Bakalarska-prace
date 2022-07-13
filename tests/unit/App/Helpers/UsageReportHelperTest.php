<?php

namespace App\Helpers;

use Nette\Utils\Arrays;
use Nette\Utils\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class UsageReportHelperTest
 * @package App\Helpers
 * @covers \App\Helpers\UsageReportHelper
 */
class UsageReportHelperTest extends TestCase
{

    /**
     * @dataProvider provideDateData
     * @covers \App\Helpers\UsageReportHelper::getStringDateRange
     */
    public function testGetStringDateRange($expectedResult, $startDate, $endDate): void
    {
        $this->assertEquals($expectedResult, UsageReportHelper::getStringDateRange($startDate, $endDate));
    }

    public function provideDateData(): array
    {
        return [
            [
                [
                    'longDateRange' => 'January 1-7, 2022',
                    'shortDateRange' => '1-7 Jan',
                    'splitDateRange' => ['1-7', 'Jan']
                ],
                DateTime::from('2022-01-01 00:00:00'),
                DateTime::from('2022-01-07 23:59:59')
            ],
            [
                [
                    'longDateRange' => 'Dec 31 2021 - Jun 6 2022',
                    'shortDateRange' => '31 Dec 2021-6 Jun 2022',
                    'splitDateRange' => ['31 Dec 2021-', '6 Jun 2022']
                ],
                DateTime::from('2021-12-31 00:00:00'),
                DateTime::from('2022-06-06 23:59:59')
            ],
            [
                [
                    'longDateRange' => 'Jan 31 - Jun 6, 2022',
                    'shortDateRange' => '31 Jan-6 Jun',
                    'splitDateRange' => ['31 Jan-', '6 Jun']
                ],
                DateTime::from('2022-01-31 00:00:00'),
                DateTime::from('2022-06-06 23:59:59')
            ]
        ];
    }
}
