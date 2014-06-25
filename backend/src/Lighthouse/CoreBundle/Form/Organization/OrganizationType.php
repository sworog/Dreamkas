<?php

namespace Lighthouse\CoreBundle\Form\Organization;

use Lighthouse\CoreBundle\Document\LegalDetails\EntrepreneurLegalDetails;
use Lighthouse\CoreBundle\Document\LegalDetails\LegalEntityLegalDetails;
use Lighthouse\CoreBundle\Document\Organization\Organization;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('phone', 'text')
            ->add('fax', 'text')
            ->add('email', 'text')
            ->add('director', 'text')
            ->add('chiefAccountant', 'text')
            ->add('address', 'text')
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'setTypeForm'));
    }

    /**
     * @param FormEvent $event
     */
    public function setTypeForm(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (!isset($data['legalDetails']['type'])) {
            return;
        }

        /* @var Organization $organization */
        $organization = $form->getData();


        switch ($data['legalDetails']['type']) {
            case EntrepreneurLegalDetails::TYPE:
                if (!$organization->legalDetails instanceof EntrepreneurLegalDetails) {
                    $organization->legalDetails = new EntrepreneurLegalDetails();
                }
                $form->add(
                    'legalDetails',
                    new LegalDetailsType(),
                    array(
                        'data_class' => EntrepreneurLegalDetails::getClassName()
                    )
                );
                break;

            case LegalEntityLegalDetails::TYPE:
            default:
                if (!$organization->legalDetails instanceof LegalEntityLegalDetails) {
                    $organization->legalDetails = new LegalEntityLegalDetails();
                }
                $form->add(
                    'legalDetails',
                    new LegalDetailsType(),
                    array(
                        'data_class' => LegalEntityLegalDetails::getClassName()
                    )
                );
                break;
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Organization::getClassName(),
                'csrf_protection' => false,
                'cascade_validation' => true
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
