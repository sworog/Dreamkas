<?php

namespace Lighthouse\CoreBundle\Document\Payment;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class BankCardPayment extends Payment
{
    const TYPE = 'bankcard';
}
