<?php

namespace Lighthouse\CoreBundle\Form\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DefaultCheckboxValueListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    protected $fieldNames = array();

    /**
     * @param array $fieldNames
     */
    public function __construct(array $fieldNames)
    {
        $this->fieldNames = $fieldNames;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SUBMIT => 'preSubmit');
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();

        foreach ($this->fieldNames as $fieldName => $defaultValue) {
            if (!isset($data[$fieldName])) {
                $data[$fieldName] = $defaultValue;
            }
        }

        $event->setData($data);
    }
}
