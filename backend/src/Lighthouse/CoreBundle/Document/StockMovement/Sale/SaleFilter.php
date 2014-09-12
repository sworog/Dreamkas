<?php

namespace Lighthouse\CoreBundle\Document\StockMovement\Sale;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\Product\Product;
use Lighthouse\CoreBundle\Document\Store\Store;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;

/**
 * @property DateTime $dateFrom
 * @property DateTime $dateTo
 * @property Store $store
 */
class SaleFilter extends AbstractDocument
{
    /**
     * @Assert\NotBlank
     * @Assert\DateTime
     * @var DateTime
     */
    protected $dateFrom;

    /**
     * @Assert\NotBlank
     * @Assert\DateTime
     * @var DateTime
     */
    protected $dateTo;

    /**
     * @var Store
     */
    protected $store;

    /**
     * @var Product
     */
    protected $product;
}
