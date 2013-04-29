<?php

namespace Lighthouse\CoreBundle\Tests\Document;

use Lighthouse\CoreBundle\Document\TrialBalance;

class TrialBalanceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $trialBalance = new TrialBalance();
        $this->assertInstanceOf('Lighthouse\\CoreBundle\\Document\\TrialBalance', $trialBalance);
    }

    /**
     * @dataProvider trialBalanceDataProvider
     */
    public function testGetSetProperties(array $trialBalanceData)
    {
        $trialBalance = new TrialBalance();

        foreach ($trialBalanceData as $key => $value) {
            $trialBalance->$key = $value;
            $this->assertEquals($value, $trialBalance->$key);
        }

        $this->assertNull($trialBalance->id);
    }

    /**
     * @dataProvider trialBalanceDataProvider
     */
    public function testPopulateAndToArray(array $data)
    {
        $trialBalance = new TrialBalance();
        $trialBalance->populate($data);

        $trialBalanceArray = $trialBalance->toArray();
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $trialBalanceArray[$key]);
        }
    }

    public function trialBalanceDataProvider()
    {
        return array(
            'trialBalance data' => array(
                array(
                    'beginningBalance' => 35,
                    'endingBalance' => 43,
                    'receipts' => 8,
                    'expenditure' => 0,
                    'beginningBalanceMoney' => 3500,
                    'endingBalanceMoney' => 4300,
                    'receiptsMoney' => 800,
                    'expenditureMoney' => 0,
                    'unitValue' => 100
                )
            ),
            'trialBalance data expenditure' => array(
                array(
                    'beginningBalance' => 55,
                    'endingBalance' => 52,
                    'receipts' => 0,
                    'expenditure' => 3,
                    'beginningBalanceMoney' => 3500,
                    'endingBalanceMoney' => 4300,
                    'receiptsMoney' => 800,
                    'expenditureMoney' => 0,
                    'unitValue' => 100
                )
            )
        );
    }
}
