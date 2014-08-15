<?php

namespace Lighthouse\CoreBundle\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use ReflectionProperty;

class FormExtraDataListener implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::SUBMIT => 'onFormSubmit');
    }

    /**
     * @param FormEvent $event
     */
    public function onFormSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $propertyReflection = new ReflectionProperty(get_class($form), 'extraData');
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue($form, array());
    }
}