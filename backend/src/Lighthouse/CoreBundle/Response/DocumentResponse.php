<?php

namespace Lighthouse\CoreBundle\Response;

use Lighthouse\CoreBundle\Document\AbstractDocument;
use Symfony\Component\HttpFoundation\Response;

class DocumentResponse extends Response
{
    /**
     * @var AbstractDocument
     */
    protected $document;

    /**
     * @param AbstractDocument $document
     */
    public function setDocument(AbstractDocument $document)
    {
        $this->document = $document;
    }

    /**
     * @return AbstractDocument
     */
    public function getDocument()
    {
        return $this->document;
    }
}
