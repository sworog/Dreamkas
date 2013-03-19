<?php

namespace Lighthouse\CoreBundle\Serializer\Handler;

use FOS\RestBundle\Util\Pluralization;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\VisitorInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\XmlSerializationVisitor;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Metadata\MetadataFactoryInterface;

/**
 * @DI\Service("lighthouse.core.serializer.handler.document")
 * @DI\Tag("jms_serializer.subscribing_handler")
 */
class DocumentHandler implements SubscribingHandlerInterface
{
    /**
     * @DI\Inject("jms_serializer.metadata_factory")
     * @var MetadataFactoryInterface
     */
    public $metadataFactory;

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = array();
        $formats = array('xml' => 'Xml');
        foreach ($formats as $format => $methodSuffix) {
            $methods[] = array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => $format,
                'type' => 'Document',
                'method' => 'serializeDocumentTo' . $methodSuffix,
            );
        }
        return $methods;
    }

    /**
     * @param \JMS\Serializer\VisitorInterface $visitor
     * @param AbstractCollection $value
     * @param array $type
     * @return string
     */
    public function serializeDocumentToXml(XmlSerializationVisitor $visitor, AbstractDocument $document, array $type)
    {
        /* @var ClassMetadata $documentMetadata  */
        $documentMetadata = $this->metadataFactory->getMetadataForClass(get_class($document));

        if (null === $documentMetadata->xmlRootName) {
            $className = $documentMetadata->reflection->getShortName();
            $documentMetadata->xmlRootName = $this->getItemTagName($className);
        }

        $visitor->getNavigator()->accept($document, null, $visitor);

        $xml = $visitor->document->saveXML();
    }

    /**
     * @param $className
     * @return string
     */
    protected function getItemTagName($className)
    {
        $className = lcfirst($className);
        return $className;
    }
}