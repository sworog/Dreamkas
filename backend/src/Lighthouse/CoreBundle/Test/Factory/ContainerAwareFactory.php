<?php

namespace Lighthouse\CoreBundle\Test\Factory;

use Doctrine\ODM\MongoDB\DocumentManager;
use Lighthouse\CoreBundle\Document\AbstractDocument;
use Lighthouse\CoreBundle\Document\ClassNameable;
use Lighthouse\CoreBundle\Validator\ExceptionalValidator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ContainerAwareFactory implements ContainerAwareInterface, ClassNameable
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->container->get('doctrine_mongodb.odm.document_manager');
    }

    /**
     * @return ExceptionalValidator
     */
    public function getValidator()
    {
        return $this->container->get('lighthouse.core.validator');
    }

    /**
     * @param AbstractDocument $document
     */
    public function save($document)
    {
        $this->getValidator()->validate($document);
        $this->getDocumentManager()->persist($document);
        $this->getDocumentManager()->flush();
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public static function getClassName()
    {
        return get_called_class();
    }
}
