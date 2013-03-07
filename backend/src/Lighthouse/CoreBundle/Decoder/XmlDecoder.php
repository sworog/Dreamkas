<?php


namespace Lighthouse\CoreBundle\Decoder;
use FOS\Rest\Decoder\XmlDecoder as BaseDecoder;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("lighthouse.core.decoder.xml")
 */
class XmlDecoder extends BaseDecoder
{
    /**
     * @param string $data
     * @return array|bool|string|void
     */
    public function decode($data)
    {
        $parsed = parent::decode($data);

        if (null !== $parsed) {
            $xml = @simplexml_load_string($data);
            $root = $xml->getName();
            return array($root => $parsed);
        } else {
            return $parsed;
        }
    }
}