<?php

namespace Lighthouse\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;
use Lighthouse\CoreBundle\Types\Money;

/**
 * Сальдовая ведомость
 *
 * @property string $id
 * @property float  $beginningBalance
 * @property Money  $beginningBalanceMoney
 * @property float  $endingBalance
 * @property Money  $endingBalanceMoney
 * @property float  $receipts
 * @property Money  $receiptsMoney
 * @property float  $expenditure
 * @property Money  $expenditureMoney
 * @property Money  $unitValue
 *
 * @MongoDB\Document(
 *     repositoryClass="Lighthouse\CoreBundle\Document\TrialBalanceRepository"
 * )
 *
 * @package Lighthouse\CoreBundle\Document
 */
class TrialBalance extends AbstractDocument
{
    /**
     * @MongoDB\Id
     * @var string
     */
    protected $id;

    /**
     * Начальное сальдо
     * @MongoDB\Float
     * @var float
     */
    protected $beginningBalance;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $beginningBalanceMoney;

    /**
     * Конечное сальдо
     * @MongoDB\Float
     * @var float
     */
    protected $endingBalance;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $endingBalanceMoney;

    /**
     * Приход
     * @MongoDB\Float
     * @var float
     */
    protected $receipts;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $receiptsMoney;

    /**
     * Расход
     * @MongoDB\Float
     * @var float
     */
    protected $expenditure;

    /**
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $expenditureMoney;

    /**
     * Стоимость единицы товара
     * @MongoDB\Field(type="money")
     * @var Money
     */
    protected $unitValue;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Product", simple=true, cascade={"persist"})
     * @var Product
     */
    protected $product;

    /**
     * Основание
     * @MongoDB\ReferenceOne(
     *      targetDocument="Invoice",
     *      discriminatorField="reasonType",
     *      discriminatorMap={
     *          "invoice"="Lighthouse\CoreBundle\Document\Invoice"
     *      }
     * )
     * @var Invoice
     */
    protected $reason;

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->id,
            'beginningBalance' => $this->beginningBalance,
            'beginningBalanceMoney' => $this->beginningBalanceMoney,
            'endingBalance' => $this->endingBalance,
            'endingBalanceMoney' => $this->endingBalanceMoney,
            'receipts' => $this->receipts,
            'receiptsMoney' => $this->receiptsMoney,
            'expenditure' => $this->expenditure,
            'expenditureMoney' => $this->expenditureMoney,
            'unitValue' => $this->unitValue,
            'product' => $this->product,
            'reason' => $this->reason,
        );

    }

}