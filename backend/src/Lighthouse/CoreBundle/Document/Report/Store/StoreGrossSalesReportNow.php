<?php

namespace Lighthouse\CoreBundle\Document\Report\Store;

use Lighthouse\CoreBundle\Types\Numeric\Decimal;
use Lighthouse\CoreBundle\Types\Numeric\Money;

class StoreGrossSalesReportNow
{
    protected $today = array(
        'now' => array(
            'date' => null,
            'value' => null,
        ),
    );

    protected $yesterday = array(
        'now' => array(
            'date' => null,
            'value' => null,
            'diff' => null,
        ),
        'dayEnd' => array(
            'date' => null,
            'value' => null,
        ),
    );

    protected $weekAgo = array(
        'now' => array(
            'date' => null,
            'value' => null,
            'diff' => null,
        ),
        'dayEnd' => array(
            'date' => null,
            'value' => null,
        ),
    );

    public function __construct(StoreGrossSalesReportCollection $collection, $dates, $ids)
    {
        $this->populate($collection, $dates, $ids);
        $this->recalculateDiffs();
    }

    public function populate(StoreGrossSalesReportCollection $collection, $dates, $ids)
    {
        foreach ($collection as $report) {
            foreach ($ids as $key => $value) {
                if ($value == $report->id) {
                    $ids[$key] = $report;
                    break;
                }
            }
        }

        foreach ($ids as $name => $report) {
            /** @var StoreGrossSalesReport $report */
            switch ($name) {
                case 'nowDayHour':
                    if ($report instanceof StoreGrossSalesReport) {
                        $this->today['now'] = array(
                            'date' => $report->dayHour->modify("+1 hour"),
                            'value' => $report->runningSum,
                        );
                    } else {
                        $this->today['now'] = array(
                            'date' => $dates[$name]->modify("+1 hour"),
                            'value' => new Money(0),
                        );
                    }
                    break;

                case 'yesterdayNowDayHour':
                    if ($report instanceof StoreGrossSalesReport) {
                        $this->yesterday['now'] = array(
                            'date' => $report->dayHour->modify("+1 hour"),
                            'value' => $report->runningSum,
                        );
                    } else {
                        $this->yesterday['now'] = array(
                            'date' => $dates[$name]->modify("+1 hour"),
                            'value' => new Money(0),
                            'diff' => null,
                        );
                    }
                    break;

                case 'yesterdayEndDay':
                    if ($report instanceof StoreGrossSalesReport) {
                        $this->yesterday['dayEnd'] = array(
                            'date' => $report->dayHour->modify("+59 min 59 sec"),
                            'value' => $report->runningSum,
                        );
                    } else {
                        $this->yesterday['dayEnd'] = array(
                            'date' => $dates[$name]->modify("+59 min 59 sec"),
                            'value' => new Money(0),
                        );
                    }
                    break;

                case 'weekAgoNowDayHour':
                    if ($report instanceof StoreGrossSalesReport) {
                        $this->weekAgo['now'] = array(
                            'date' => $report->dayHour->modify("+1 hour"),
                            'value' => $report->runningSum,
                            'diff' => null,
                        );
                    } else {
                        $this->weekAgo['now'] = array(
                            'date' => $dates[$name]->modify("+1 hour"),
                            'value' => new Money(0),
                        );
                    }
                    break;

                case 'weekAgoEndDay':
                    if ($report instanceof StoreGrossSalesReport) {
                        $this->weekAgo['dayEnd'] = array(
                            'date' => $report->dayHour->modify("+59 min 59 sec"),
                            'value' => $report->runningSum,
                        );
                    } else {
                        $this->weekAgo['dayEnd'] = array(
                            'date' => $dates[$name]->modify("+59 min 59 sec"),
                            'value' => new Money(0),
                        );
                    }
                    break;
            }
        }
    }

    public function recalculateDiffs()
    {
        /** @var Money $todayNowValue */
        $todayNowValue = $this->today['now']['value'];
        if ($todayNowValue->getCount() == 0) {
            return;
        }

        $yesterdayDiff = ($todayNowValue->toNumber() / $this->yesterday['now']['value']->toNumber() - 1) * 100;
        $weekAgoDiff = ($todayNowValue->toNumber() / $this->weekAgo['now']['value']->toNumber() - 1) * 100;

        $this->yesterday['now']['diff'] = Decimal::createFromNumeric($yesterdayDiff, 2);
        $this->weekAgo['now']['diff'] = Decimal::createFromNumeric($weekAgoDiff, 2);
    }
}
